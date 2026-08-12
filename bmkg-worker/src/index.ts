/**
 * BMKG Earthquake Scraper — Cloudflare Worker
 *
 * Runs on a Cron Trigger. Scrapes BMKG's real-time earthquake page, compares
 * the latest entry against the last-seen one (stored in KV), and — only when
 * it's actually new — reports it to the main ResQ (Laravel) app's disaster
 * webhook, which owns persistence + notification.
 *
 * Ported from scheduler/scrap.py (now deprecated in favor of this Worker):
 * same value-parsing regexes (datetime/magnitude/depth/coordinates), same
 * "is this new" comparison fields. Table structure is walked with
 * HTMLRewriter instead of BeautifulSoup. Severity classification and the
 * "skip low severity" filter are intentionally NOT ported — that's the
 * Laravel app's job (Disaster model + NotificationService already own it).
 */

const BASE_URL = 'https://www.bmkg.go.id/gempabumi/gempabumi-realtime';

const MONTHS: Record<string, string> = {
	jan: '01', january: '01', januari: '01',
	feb: '02', february: '02', februari: '02',
	mar: '03', march: '03', maret: '03',
	apr: '04', april: '04',
	may: '05', mei: '05',
	jun: '06', june: '06', juni: '06',
	jul: '07', july: '07', juli: '07',
	aug: '08', august: '08', agustus: '08',
	sep: '09', september: '09',
	oct: '10', october: '10', oktober: '10',
	nov: '11', november: '11',
	dec: '12', december: '12', desember: '12',
};

interface EarthquakeRow {
	datetime: string | null;
	time: string | null;
	date: string | null;
	latitude: number | null;
	longitude: number | null;
	latitude_raw: string;
	longitude_raw: string;
	depth_km: number | null;
	magnitude: number | null;
	location: string;
	status: string;
}

// ---------------------------------------------------------------------------
// Value parsing (1:1 port of scrap.py's regexes)
// ---------------------------------------------------------------------------

function parseMagnitude(value: string): number | null {
	const match = value.trim().match(/(\d+\.?\d*)/);
	return match ? parseFloat(match[1]) : null;
}

function parseDepth(value: string): number | null {
	const match = value.trim().match(/(\d+\.?\d*)\s*(?:km|Km)/);
	return match ? parseFloat(match[1]) : null;
}

function parseCoordinatesCombined(coordString: string): {
	latitude: number | null;
	longitude: number | null;
	latitude_raw: string;
	longitude_raw: string;
} {
	const coords = { latitude: null as number | null, longitude: null as number | null, latitude_raw: '', longitude_raw: '' };

	const parts = coordString.split('-');
	if (parts.length < 2) return coords;

	const latPart = parts[0].trim();
	const lonPart = parts[1].trim();
	coords.latitude_raw = latPart;
	coords.longitude_raw = lonPart;

	const latMatch = latPart.match(/([\d,]+)\s+(LS|LU)/i);
	if (latMatch) {
		let latValue = parseFloat(latMatch[1].replace(',', '.'));
		if (latMatch[2].toUpperCase() === 'LS') latValue = -latValue;
		coords.latitude = latValue;
	}

	const lonMatch = lonPart.match(/([\d,]+)\s+(BT|BB)/i);
	if (lonMatch) {
		let lonValue = parseFloat(lonMatch[1].replace(',', '.'));
		if (lonMatch[2].toUpperCase() === 'BB') lonValue = -lonValue;
		coords.longitude = lonValue;
	}

	return coords;
}

function parseDatetimeIso(dateStr: string, timeStr: string): string | null {
	const dateMatch = dateStr.match(/^(\d{2})-(\d{2})-(\d{4})$/);
	const timeMatch = timeStr.match(/^\d{2}:\d{2}:\d{2}$/);
	if (!dateMatch || !timeMatch) return null;

	const [, day, month, year] = dateMatch;
	return `${year}-${month}-${day}T${timeStr}`;
}

// ---------------------------------------------------------------------------
// Table structure parsing via HTMLRewriter (first <table> on the page only,
// matching scrap.py's `soup.find('table')` behavior)
// ---------------------------------------------------------------------------

async function fetchTableRows(): Promise<string[][]> {
	const response = await fetch(BASE_URL, {
		headers: {
			'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
		},
	});

	if (!response.ok) {
		throw new Error(`Failed to fetch BMKG page: ${response.status}`);
	}

	return parseRowsFromResponse(response);
}

async function parseRowsFromResponse(response: Response): Promise<string[][]> {
	const rows: string[][] = [];
	let currentRow: string[] | null = null;
	let currentCell = '';
	let tablesSeen = 0;
	let firstTableActive = false;
	let firstTableFinished = false;

	const rewriter = new HTMLRewriter()
		.on('table', {
			element(el) {
				tablesSeen++;
				if (tablesSeen === 1) {
					firstTableActive = true;
					el.onEndTag(() => {
						firstTableActive = false;
						firstTableFinished = true;
					});
				}
			},
		})
		.on('tr', {
			element(el) {
				if (!firstTableActive || firstTableFinished) return;
				currentRow = [];
				const rowRef = currentRow;
				el.onEndTag(() => {
					if (rowRef.length > 0) rows.push(rowRef);
					currentRow = null;
				});
			},
		})
		.on('td', {
			element(el) {
				if (!firstTableActive || firstTableFinished || !currentRow) return;
				currentCell = '';
				el.onEndTag(() => {
					currentRow?.push(currentCell.trim());
				});
			},
			text(chunk) {
				if (!firstTableActive || firstTableFinished || !currentRow) return;
				currentCell += chunk.text;
			},
		});

	// Draining the transformed body is what actually drives the handlers above.
	await rewriter.transform(response).text();

	return rows;
}

function rowToEarthquake(cols: string[]): EarthquakeRow | null {
	if (cols.length < 7) return null;

	// [0] index, [1] datetime, [2] magnitude, [3] depth, [4] coordinates, [5] location, [6] status
	const indexText = cols[0].trim();
	if (!indexText || !/^\d+$/.test(indexText)) return null;

	// Seen both "09 Apr 202612:03:41 WIB" (no space, colon-separated) and
	// "12 Aug 2026 06.10.55 WIB" (space, period-separated) from BMKG at
	// different times, so both the space and the time separator are optional.
	const datetimeMatch = cols[1].match(/(\d{2})\s+(\w+)\s+(\d{4})\s*(\d{2})[.:](\d{2})[.:](\d{2})/);
	if (!datetimeMatch) return null;

	const [, day, monthStr, year, hour, minute, second] = datetimeMatch;
	const timeStr = `${hour}:${minute}:${second}`;
	const monthNum = MONTHS[monthStr.toLowerCase()] ?? '01';
	const dateStr = `${day}-${monthNum}-${year}`;

	const magnitude = parseMagnitude(cols[2].replace(',', '.'));
	const depth = parseDepth(cols[3]);
	const coords = parseCoordinatesCombined(cols[4]);
	const location = cols[5];
	const statusRaw = cols.length > 6 ? cols[6] : '–';
	const status = statusRaw !== '–' ? statusRaw : 'Automatic';
	const datetimeIso = dateStr && timeStr ? parseDatetimeIso(dateStr, timeStr) : null;

	if (coords.latitude === null || coords.longitude === null) return null;
	if (!depth && !magnitude) return null;

	return {
		datetime: datetimeIso,
		time: timeStr,
		date: dateStr,
		latitude: coords.latitude,
		longitude: coords.longitude,
		latitude_raw: coords.latitude_raw,
		longitude_raw: coords.longitude_raw,
		depth_km: depth,
		magnitude,
		location,
		status,
	};
}

async function fetchAndParseEarthquakes(): Promise<EarthquakeRow[]> {
	const rows = await fetchTableRows();
	const earthquakes: EarthquakeRow[] = [];

	for (const cols of rows) {
		const earthquake = rowToEarthquake(cols);
		if (earthquake) earthquakes.push(earthquake);
	}

	return earthquakes;
}

// ---------------------------------------------------------------------------
// Change detection (same field list as scrap.py's is_new_data check)
// ---------------------------------------------------------------------------

const COMPARISON_FIELDS: (keyof EarthquakeRow)[] = [
	'datetime', 'time', 'date', 'latitude', 'longitude',
	'latitude_raw', 'longitude_raw', 'depth_km', 'magnitude', 'location', 'status',
];

function isNewEarthquake(prev: EarthquakeRow, current: EarthquakeRow): boolean {
	return COMPARISON_FIELDS.some((field) => prev[field] !== current[field]);
}

// ---------------------------------------------------------------------------
// Forwarding to the Laravel app
// ---------------------------------------------------------------------------

function readableLocation(location: string): string {
	const digitsOnly = location.replace(/[.,\-]/g, '');
	return location && !/^\d+$/.test(digitsOnly) ? location : 'Lokasi tidak diketahui';
}

function buildDisasterPayload(earthquake: EarthquakeRow) {
	return {
		type: 'earthquake',
		location: readableLocation(earthquake.location),
		latitude: earthquake.latitude,
		longitude: earthquake.longitude,
		magnitude: earthquake.magnitude,
		depth_km: earthquake.depth_km,
		occurred_at: earthquake.datetime,
		source_id: `bmkg_${earthquake.datetime}_${earthquake.latitude}_${earthquake.longitude}`,
		raw_data: {
			status: earthquake.status,
			latitude_raw: earthquake.latitude_raw,
			longitude_raw: earthquake.longitude_raw,
			date: earthquake.date,
			time: earthquake.time,
		},
	};
}

async function reportDisaster(env: Env, earthquake: EarthquakeRow): Promise<boolean> {
	const response = await fetch(`${env.RESQ_API_URL}/api/v1/webhook/disasters`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'X-API-Key': env.RESQ_WEBHOOK_API_KEY,
		},
		body: JSON.stringify(buildDisasterPayload(earthquake)),
	});

	if (!response.ok) {
		console.error(`Disaster webhook failed: ${response.status} ${await response.text()}`);
		return false;
	}

	return true;
}

// ---------------------------------------------------------------------------
// Entry point
// ---------------------------------------------------------------------------

const KV_KEY = 'last_earthquake';

async function runOnce(env: Env): Promise<void> {
	const earthquakes = await fetchAndParseEarthquakes();

	if (earthquakes.length === 0) {
		console.warn('No earthquake rows parsed from BMKG page');
		return;
	}

	const current = earthquakes[0];
	const prev = await env.BMKG_STATE.get<EarthquakeRow>(KV_KEY, 'json');

	if (prev && !isNewEarthquake(prev, current)) {
		return;
	}

	const reported = await reportDisaster(env, current);

	// Only advance the KV fingerprint once Laravel has actually accepted the
	// report — a failed POST leaves state untouched so the next cron tick
	// retries the same earthquake instead of silently dropping it.
	if (reported) {
		await env.BMKG_STATE.put(KV_KEY, JSON.stringify(current));
	}
}

export default {
	async scheduled(_event: ScheduledController, env: Env, ctx: ExecutionContext): Promise<void> {
		ctx.waitUntil(runOnce(env));
	},
	// Manual trigger — lets ops force a run without waiting for the next cron
	// tick (e.g. `curl -X POST https://bmkg-worker.<subdomain>.workers.dev`).
	async fetch(_request: Request, env: Env, ctx: ExecutionContext): Promise<Response> {
		ctx.waitUntil(runOnce(env));
		return new Response('Triggered.');
	},
} satisfies ExportedHandler<Env>;
