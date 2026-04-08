<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Peta Bencana') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    {{ __('Pantau lokasi bencana di seluruh Indonesia secara real-time') }}
                </p>
            </div>
            <div class="text-sm text-gray-500" id="last-updated">
                {{ __('Terakhir diperbarui:') }} <span id="last-updated-time">-</span>
            </div>
        </div>
    </x-slot>

    <div class="flex h-[calc(100vh-200px)] min-h-[500px]">
        <!-- Sidebar Filters -->
        <div class="w-80 bg-white border-r border-gray-200 overflow-y-auto flex-shrink-0">
            <div class="p-6 space-y-6">
                <!-- Location Search -->
                <div>
                    <label for="location-search" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Cari Lokasi') }}
                    </label>
                    <div class="flex gap-2">
                        <input
                            type="text"
                            id="location-search"
                            placeholder="Contoh: Jakarta, Bandung..."
                            class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                        >
                        <button
                            id="search-btn"
                            class="px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                    <p id="search-error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>

                <!-- Radius Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Radius Pencarian') }}
                    </label>
                    <select id="radius-select" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="25">25 km</option>
                        <option value="50" selected>50 km</option>
                        <option value="100">100 km</option>
                        <option value="200">200 km</option>
                        <option value="500">500 km</option>
                    </select>
                </div>

                <!-- Disaster Type Filters -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        {{ __('Jenis Bencana') }}
                    </label>
                    <div class="space-y-2" id="disaster-type-filters">
                        @foreach($disasterTypes as $type)
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="disaster_types"
                                    value="{{ $type }}"
                                    checked
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                <span class="ml-2 text-sm text-gray-700 capitalize">{{ ucfirst($type) }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div class="mt-3 flex gap-2">
                        <button id="select-all-types" class="text-xs text-indigo-600 hover:text-indigo-800">
                            {{ __('Pilih Semua') }}
                        </button>
                        <span class="text-gray-300">|</span>
                        <button id="deselect-all-types" class="text-xs text-indigo-600 hover:text-indigo-800">
                            {{ __('Batal Pilih') }}
                        </button>
                    </div>
                </div>

                <!-- Severity Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        {{ __('Tingkat Keparahan') }}
                    </label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="severity" value="critical" checked class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                            <span class="ml-2 text-sm text-gray-700 flex items-center">
                                <span class="w-3 h-3 rounded-full bg-red-600 mr-2"></span>
                                {{ __('Kritis/Tinggi') }}
                            </span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="severity" value="medium" checked class="rounded border-gray-300 text-amber-500 shadow-sm focus:ring-amber-500">
                            <span class="ml-2 text-sm text-gray-700 flex items-center">
                                <span class="w-3 h-3 rounded-full bg-amber-500 mr-2"></span>
                                {{ __('Sedang') }}
                            </span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="severity" value="low" checked class="rounded border-gray-300 text-emerald-500 shadow-sm focus:ring-emerald-500">
                            <span class="ml-2 text-sm text-gray-700 flex items-center">
                                <span class="w-3 h-3 rounded-full bg-emerald-500 mr-2"></span>
                                {{ __('Rendah') }}
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Date Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Rentang Waktu') }}
                    </label>
                    <div class="space-y-2">
                        <input
                            type="date"
                            id="date-from"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                        >
                        <input
                            type="date"
                            id="date-to"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                        >
                    </div>
                </div>

                <!-- Stats -->
                <div class="border-t pt-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">{{ __('Statistik') }}</h4>
                    <div class="space-y-1 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('Total Bencana:') }}</span>
                            <span id="total-disasters" class="font-medium">0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('Ditampilkan:') }}</span>
                            <span id="visible-disasters" class="font-medium">0</span>
                        </div>
                    </div>
                </div>

                <!-- Reset Button -->
                <button
                    id="reset-filters"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    {{ __('Reset Filter') }}
                </button>
            </div>
        </div>

        <!-- Map Container -->
        <div class="flex-1 relative">
            <div id="map" class="w-full h-full"></div>

            <!-- Loading Overlay -->
            <div id="map-loading" class="absolute inset-0 bg-white bg-opacity-80 flex items-center justify-center z-10 hidden">
                <div class="flex flex-col items-center">
                    <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="mt-2 text-sm text-gray-600">{{ __('Memuat data...') }}</span>
                </div>
            </div>

            <!-- Auto-refresh Toggle -->
            <div class="absolute top-4 right-4 bg-white rounded-lg shadow-md p-3 z-[5]">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" id="auto-refresh" checked class="sr-only peer">
                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    <span class="ml-3 text-sm font-medium text-gray-700">{{ __('Auto-refresh (5m)') }}</span>
                </label>
            </div>

            <!-- Legend -->
            <div class="absolute bottom-4 left-4 bg-white rounded-lg shadow-md p-4 z-[5]">
                <h4 class="text-sm font-semibold text-gray-700 mb-2">{{ __('Keterangan') }}</h4>
                <div class="space-y-1 text-xs">
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-red-600 mr-2"></span>
                        <span>{{ __('Keparahan Tinggi/Kritis') }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-amber-500 mr-2"></span>
                        <span>{{ __('Keparahan Sedang') }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-emerald-500 mr-2"></span>
                        <span>{{ __('Keparahan Rendah') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let map;
        let markers = [];
        let markerCluster;
        let infoWindow;
        let autoRefreshInterval;
        let searchLocationMarker = null;
        let searchRadiusCircle = null;

        // Center of Indonesia
        const INDONESIA_CENTER = { lat: -2.5489, lng: 118.0149 };

        // Severity colors
        const SEVERITY_COLORS = {
            critical: '#DC2626',
            high: '#DC2626',
            medium: '#F59E0B',
            low: '#10B981',
        };

        // Initialize map
        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: INDONESIA_CENTER,
                zoom: 5,
                minZoom: 4,
                maxZoom: 18,
                mapTypeControl: true,
                streetViewControl: false,
                fullscreenControl: true,
                zoomControl: true,
            });

            infoWindow = new google.maps.InfoWindow();

            // Load initial disaster data
            loadDisasters();

            // Start auto-refresh
            startAutoRefresh();
        }

        // Load disasters from API
        async function loadDisasters() {
            showLoading();

            try {
                const params = buildQueryParams();
                const response = await fetch(`/api/disasters?${params}`);
                const data = await response.json();

                if (data.features) {
                    updateMarkers(data.features);
                    updateStats(data.meta.total);
                    updateLastUpdated();
                }
            } catch (error) {
                console.error('Error loading disasters:', error);
            } finally {
                hideLoading();
            }
        }

        // Build query params from filters
        function buildQueryParams() {
            const params = new URLSearchParams();

            // Disaster types
            const selectedTypes = Array.from(document.querySelectorAll('input[name="disaster_types"]:checked'))
                .map(cb => cb.value);
            if (selectedTypes.length > 0) {
                selectedTypes.forEach(type => params.append('types[]', type));
            }

            // Severity (use the most severe selected, or all)
            const selectedSeverities = Array.from(document.querySelectorAll('input[name="severity"]:checked'))
                .map(cb => cb.value);

            // Date range
            const dateFrom = document.getElementById('date-from').value;
            const dateTo = document.getElementById('date-to').value;
            if (dateFrom) params.append('date_from', dateFrom);
            if (dateTo) params.append('date_to', dateTo);

            return params.toString();
        }

        // Update markers on map
        function updateMarkers(features) {
            // Clear existing markers
            markers.forEach(marker => marker.setMap(null));
            markers = [];

            // Filter by severity client-side (since API may return multiple severities)
            const selectedSeverities = Array.from(document.querySelectorAll('input[name="severity"]:checked'))
                .map(cb => cb.value);

            const filteredFeatures = features.filter(feature =>
                selectedSeverities.includes(feature.properties.severity)
            );

            // Create new markers
            filteredFeatures.forEach(feature => {
                const marker = createMarker(feature);
                markers.push(marker);
            });

            // Update marker cluster
            if (markerCluster) {
                markerCluster.clearMarkers();
            }

            markerCluster = new markerClusterer.MarkerClusterer({
                markers,
                map,
            });

            // Update visible count
            document.getElementById('visible-disasters').textContent = filteredFeatures.length;
        }

        // Create a single marker
        function createMarker(feature) {
            const props = feature.properties;
            const [lng, lat] = feature.geometry.coordinates;

            const marker = new google.maps.Marker({
                position: { lat, lng },
                map: map,
                title: `${props.type} - ${props.location}`,
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    fillColor: props.color,
                    fillOpacity: 0.9,
                    strokeColor: '#ffffff',
                    strokeWeight: 2,
                    scale: 10,
                },
            });

            marker.addListener('click', () => {
                showInfoWindow(marker, props);
            });

            return marker;
        }

        // Show info window
        function showInfoWindow(marker, props) {
            const severityClass = {
                critical: 'text-red-600 font-bold',
                high: 'text-red-600 font-bold',
                medium: 'text-amber-500 font-semibold',
                low: 'text-emerald-500',
            }[props.severity] || 'text-gray-600';

            const content = `
                <div class="p-2 max-w-xs">
                    <h3 class="font-bold text-lg text-gray-800 capitalize mb-1">${props.type}</h3>
                    <p class="text-sm text-gray-600 mb-2">${props.location}</p>
                    <div class="space-y-1 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Keparahan:</span>
                            <span class="${severityClass} capitalize">${props.severity}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Status:</span>
                            <span class="capitalize">${props.status}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Waktu:</span>
                            <span>${new Date(props.created_at).toLocaleString('id-ID')}</span>
                        </div>
                    </div>
                    ${props.description ? `<p class="mt-2 text-sm text-gray-600">${props.description.substring(0, 100)}...</p>` : ''}
                </div>
            `;

            infoWindow.setContent(content);
            infoWindow.open(map, marker);
        }

        // Search location
        async function searchLocation() {
            const location = document.getElementById('location-search').value.trim();
            if (!location) return;

            showLoading();
            document.getElementById('search-error').classList.add('hidden');

            try {
                const response = await fetch(`/api/geocode?location=${encodeURIComponent(location)}`);

                if (!response.ok) {
                    throw new Error('Location not found');
                }

                const data = await response.json();

                if (data.location) {
                    const { lat, lng } = data.location;

                    // Clear previous search markers
                    if (searchLocationMarker) searchLocationMarker.setMap(null);
                    if (searchRadiusCircle) searchRadiusCircle.setMap(null);

                    // Add marker for searched location
                    searchLocationMarker = new google.maps.Marker({
                        position: { lat, lng },
                        map: map,
                        title: data.location.formatted_address,
                        icon: {
                            path: google.maps.SymbolPath.CIRCLE,
                            fillColor: '#4F46E5',
                            fillOpacity: 0.3,
                            strokeColor: '#4F46E5',
                            strokeWeight: 2,
                            scale: 15,
                        },
                    });

                    // Get selected radius
                    const radius = parseInt(document.getElementById('radius-select').value);

                    // Add radius circle
                    searchRadiusCircle = new google.maps.Circle({
                        map: map,
                        center: { lat, lng },
                        radius: radius * 1000, // Convert to meters
                        fillColor: '#4F46E5',
                        fillOpacity: 0.1,
                        strokeColor: '#4F46E5',
                        strokeOpacity: 0.5,
                        strokeWeight: 2,
                    });

                    // Pan to location with appropriate zoom
                    map.setCenter({ lat, lng });
                    map.setZoom(10);

                    // Reload disasters with location filter
                    loadDisastersWithLocation(lat, lng, radius);
                }
            } catch (error) {
                document.getElementById('search-error').textContent = 'Lokasi tidak ditemukan';
                document.getElementById('search-error').classList.remove('hidden');
            } finally {
                hideLoading();
            }
        }

        // Load disasters with location filter
        async function loadDisastersWithLocation(lat, lng, radius) {
            showLoading();

            try {
                const params = buildQueryParams();
                const response = await fetch(`/api/disasters?${params}&lat=${lat}&lng=${lng}&radius=${radius}`);
                const data = await response.json();

                if (data.features) {
                    updateMarkers(data.features);
                    updateStats(data.meta.total);
                }
            } catch (error) {
                console.error('Error loading disasters:', error);
            } finally {
                hideLoading();
            }
        }

        // Update statistics
        async function updateStats(visibleCount) {
            try {
                const response = await fetch('/api/disasters/stats');
                const data = await response.json();

                document.getElementById('total-disasters').textContent = data.total;
                document.getElementById('visible-disasters').textContent = visibleCount;
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        // Update last updated time
        function updateLastUpdated() {
            const now = new Date();
            document.getElementById('last-updated-time').textContent = now.toLocaleString('id-ID');
        }

        // Auto-refresh
        function startAutoRefresh() {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
            }

            autoRefreshInterval = setInterval(() => {
                const isEnabled = document.getElementById('auto-refresh').checked;
                if (isEnabled) {
                    loadDisasters();
                }
            }, 5 * 60 * 1000); // 5 minutes
        }

        // Show/hide loading
        function showLoading() {
            document.getElementById('map-loading').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('map-loading').classList.add('hidden');
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', () => {
            // Filter change handlers
            document.querySelectorAll('input[name="disaster_types"]').forEach(cb => {
                cb.addEventListener('change', loadDisasters);
            });

            document.querySelectorAll('input[name="severity"]').forEach(cb => {
                cb.addEventListener('change', loadDisasters);
            });

            document.getElementById('date-from').addEventListener('change', loadDisasters);
            document.getElementById('date-to').addEventListener('change', loadDisasters);

            // Search button
            document.getElementById('search-btn').addEventListener('click', searchLocation);
            document.getElementById('location-search').addEventListener('keypress', (e) => {
                if (e.key === 'Enter') searchLocation();
            });

            // Radius change
            document.getElementById('radius-select').addEventListener('change', () => {
                const location = document.getElementById('location-search').value.trim();
                if (location) {
                    searchLocation();
                }
            });

            // Select all / Deselect all
            document.getElementById('select-all-types').addEventListener('click', () => {
                document.querySelectorAll('input[name="disaster_types"]').forEach(cb => cb.checked = true);
                loadDisasters();
            });

            document.getElementById('deselect-all-types').addEventListener('click', () => {
                document.querySelectorAll('input[name="disaster_types"]').forEach(cb => cb.checked = false);
                loadDisasters();
            });

            // Reset filters
            document.getElementById('reset-filters').addEventListener('click', () => {
                document.querySelectorAll('input[name="disaster_types"]').forEach(cb => cb.checked = true);
                document.querySelectorAll('input[name="severity"]').forEach(cb => cb.checked = true);
                document.getElementById('date-from').value = '';
                document.getElementById('date-to').value = '';
                document.getElementById('location-search').value = '';
                document.getElementById('radius-select').value = '50';

                // Clear search markers
                if (searchLocationMarker) searchLocationMarker.setMap(null);
                if (searchRadiusCircle) searchRadiusCircle.setMap(null);

                map.setCenter(INDONESIA_CENTER);
                map.setZoom(5);

                loadDisasters();
            });

            // Auto-refresh toggle
            document.getElementById('auto-refresh').addEventListener('change', (e) => {
                if (e.target.checked) {
                    startAutoRefresh();
                }
            });
        });
    </script>

    <!-- Google Maps API with MarkerClusterer -->
    <script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsApiKey }}&callback=initMap">
    </script>
    @endpush
</x-app-layout>
