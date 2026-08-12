# bmkg-worker

Cloudflare Worker that scrapes BMKG's real-time earthquake page on a Cron
Trigger and reports newly detected earthquakes to the ResQ (Laravel) app's
`POST /api/v1/webhook/disasters` endpoint. Replaces `../scheduler/scrap.py`
(kept for reference only, not run).

- **Scraping/parsing**: `src/index.ts` — table structure is walked with
  `HTMLRewriter`; datetime/magnitude/depth/coordinate values are parsed with
  regexes ported from `scrap.py`. Severity classification and any
  "skip low severity" filtering are intentionally *not* done here — that's
  the Laravel app's job (`Disaster` model + `NotificationService`).
- **Dedup**: a single KV key (`last_earthquake`) holds the last-seen
  earthquake's fingerprint, so repeat cron ticks don't re-report the same
  event. Only updated after Laravel accepts the report — a failed POST
  leaves it untouched so the next tick retries.

## Setup

```bash
npm install
```

1. Copy `.dev.vars.example` to `.dev.vars` and fill in the real webhook key
   (same value as `resq/.env`'s `WEBHOOK_API_KEY`) for local testing.
2. The `BMKG_STATE` KV namespace and its `id` in `wrangler.jsonc` are already
   provisioned. If you need a fresh one: `npx wrangler kv namespace create BMKG_STATE`
   and paste the returned id into `wrangler.jsonc`.
3. Before deploying, set the production secret:
   ```bash
   npx wrangler secret put RESQ_WEBHOOK_API_KEY
   ```
4. Update `vars.RESQ_API_URL` in `wrangler.jsonc` if the Vercel deployment
   URL changes.

## Commands

```bash
npm run dev      # wrangler dev (local KV simulation)
npm run deploy   # wrangler deploy
npm run types    # regenerate worker-configuration.d.ts after config changes
```

Manually trigger a run without waiting for cron:
```bash
# Local:
curl -X POST http://127.0.0.1:8787/
# Deployed:
curl -X POST https://bmkg-worker.<your-subdomain>.workers.dev/
```

Test the scheduled handler specifically (local dev only):
```bash
npx wrangler dev --test-scheduled
curl "http://127.0.0.1:8787/__scheduled?cron=*/5+*+*+*+*"
```
