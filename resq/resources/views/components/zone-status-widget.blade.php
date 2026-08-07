<?php
/**
 * Zone Status Widget Component
 * Displays user's current disaster risk zone status
 * Requires user to allow location access
 */
?>
<div
    x-data="zoneStatusWidget()"
    class="bg-white/[0.04] backdrop-blur-sm rounded-2xl shadow-card border border-white/[0.06] overflow-hidden h-full"
>
    <!-- Header -->
    <div class="px-6 py-4 bg-white/[0.03] border-b border-white/[0.06] flex items-center justify-between">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <h3 class="font-semibold text-white">Status Zona Anda</h3>
        </div>
        <button
            @click="refreshLocation()"
            class="text-slate-400 hover:text-sky-400 transition"
            :class="{ 'animate-spin': isLoading }"
            title="Refresh lokasi"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
        </button>
    </div>

    <!-- Content -->
    <div class="p-6">
        <!-- Loading State -->
        <div x-show="status === 'loading'" class="text-center py-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white/[0.05] mb-4">
                <svg class="animate-spin h-8 w-8 text-sky-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            <p class="text-slate-300">Mendeteksi lokasi Anda...</p>
        </div>

        <!-- Permission Request State -->
        <div x-show="status === 'requesting'" class="text-center py-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-sky-500/10 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <p class="text-slate-300 mb-4">Izinkan akses lokasi untuk mendapatkan peringatan bencana personal</p>
            <button
                @click="requestLocation()"
                class="bg-sky-500 text-white px-4 py-2 rounded-lg hover:bg-sky-600 transition"
            >
                Izinkan Lokasi
            </button>
        </div>

        <!-- Denied State -->
        <div x-show="status === 'denied'" class="text-center py-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-amber-500/10 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <p class="text-slate-300 mb-2">Akses lokasi ditolak</p>
            <p class="text-sm text-slate-400 mb-4">Aktifkan izin lokasi di browser Anda untuk fitur peringatan bencana</p>
            <div class="flex flex-col gap-2">
                <button
                    @click="requestLocation()"
                    class="text-sky-400 hover:underline text-sm"
                >
                    Coba Lagi
                </button>
                @if(auth()->check() && auth()->user()->locations()->exists())
                <button
                    @click="useSavedLocation()"
                    class="text-sm bg-sky-500/10 hover:bg-sky-500/20 text-sky-400 px-4 py-2 rounded-lg transition"
                >
                    Gunakan Lokasi Tersimpan
                </button>
                @endif
                <a href="{{ route('locations.create') }}" class="text-sm text-slate-400 hover:text-white transition">
                    + Tambah Lokasi Baru
                </a>
            </div>
        </div>

        <!-- Using Saved Location State -->
        <div x-show="status === 'saved'" class="text-center py-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-500/10 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <p class="text-slate-300 mb-2">Menggunakan Lokasi Tersimpan</p>
            <p class="text-sm text-blue-400 mb-4" x-text="savedLocationName"></p>
            <div class="flex justify-center gap-2 flex-wrap">
                <a href="{{ route('locations.index') }}" class="text-xs text-slate-400 hover:text-white transition">
                    Kelola Lokasi
                </a>
                <span class="text-slate-600">|</span>
                <button @click="requestLocation()" class="text-xs text-blue-400 hover:underline">
                    Gunakan Lokasi Real-time
                </button>
            </div>
        </div>

        <!-- Warning State (Permission Denied but with warning icon variation) -->
        <div x-show="status === 'warning'" class="text-center py-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-amber-500/10 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-slate-300 mb-2" x-text="errorMessage || 'Peringatan lokasi'"></p>
            <button
                @click="requestLocation()"
                class="text-sky-400 hover:underline text-sm"
            >
                Coba Lagi
            </button>
        </div>

        <!-- Error State -->
        <div x-show="status === 'error'" class="text-center py-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-rose-500/10 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-slate-300 mb-2">Gagal mendeteksi lokasi</p>
            <p class="text-sm text-slate-400 mb-4" x-text="errorMessage"></p>

            <!-- Tips untuk akurasi GPS lebih baik -->
            <div class="bg-slate-800/50 rounded-lg p-3 mb-4 text-left mx-4">
                <p class="text-xs text-slate-400 mb-2 font-medium">Tips untuk akurasi lebih baik:</p>
                <ul class="text-xs text-slate-500 space-y-1">
                    <li class="flex items-start gap-1">
                        <span class="text-blue-400">•</span>
                        <span>Pindah ke area terbuka (outdoor)</span>
                    </li>
                    <li class="flex items-start gap-1">
                        <span class="text-blue-400">•</span>
                        <span>Pastikan WiFi dan Bluetooth aktif (membantu triangulasi)</span>
                    </li>
                    <li class="flex items-start gap-1">
                        <span class="text-blue-400">•</span>
                        <span>Tunggu 10-30 detik untuk GPS lock</span>
                    </li>
                    <li class="flex items-start gap-1">
                        <span class="text-blue-400">•</span>
                        <span>Hindari gedung tinggi atau area tertutup</span>
                    </li>
                </ul>
            </div>

            <button
                @click="requestLocation()"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition"
            >
                Coba Lagi dengan Akurasi Tinggi
            </button>
        </div>

        <!-- Active Status Display -->
        <div x-show="status === 'active'" class="space-y-4">
            <!-- Status Badge -->
            <div class="flex items-center justify-between">
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold"
                    :class="{
                        'bg-rose-500/20 text-rose-300 border border-rose-500/30': zoneStatus === 'danger',
                        'bg-amber-500/20 text-amber-300 border border-amber-500/30': zoneStatus === 'warning',
                        'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30': zoneStatus === 'safe'
                    }"
                >
                    <span
                        class="w-2.5 h-2.5 rounded-full animate-pulse"
                        :class="{
                            'bg-rose-400': zoneStatus === 'danger',
                            'bg-amber-400': zoneStatus === 'warning',
                            'bg-emerald-400': zoneStatus === 'safe'
                        }"
                    ></span>
                    <span x-text="zoneLabel"></span>
                </div>
                <span class="text-xs text-slate-400" x-text="lastUpdated"></span>
            </div>

            <!-- Alert Message for Danger/Warning -->
            <div
                x-show="zoneStatus === 'danger' || zoneStatus === 'warning'"
                class="rounded-lg p-4 text-sm border"
                :class="{
                    'bg-rose-500/10 border-rose-500/20 text-rose-200': zoneStatus === 'danger',
                    'bg-amber-500/10 border-amber-500/20 text-amber-200': zoneStatus === 'warning'
                }"
            >
                <div class="flex items-start gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="font-medium mb-1" x-show="zoneStatus === 'danger'">⚠️ Area Berbahaya Terdeteksi</p>
                        <p class="font-medium mb-1" x-show="zoneStatus === 'warning'">⚡ Tingkat Waspada</p>
                        <p x-text="warningMessage"></p>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-white/[0.03] rounded-lg p-3">
                    <p class="text-xs text-slate-400 mb-1">Bencana di Area (50km)</p>
                    <p class="text-xl font-bold text-white" x-text="metrics.total_disasters"></p>
                </div>
                <div class="bg-white/[0.03] rounded-lg p-3">
                    <p class="text-xs text-slate-400 mb-1">Cluster Aktif</p>
                    <p
                        class="text-xl font-bold"
                        :class="{
                            'text-rose-400': metrics.max_cluster >= 10,
                            'text-amber-400': metrics.max_cluster >= 5 && metrics.max_cluster < 10,
                            'text-emerald-400': metrics.max_cluster < 5
                        }"
                        x-text="metrics.max_cluster"
                    ></p>
                </div>
            </div>

            <!-- Location Accuracy Warning -->
            <div x-show="accuracy && accuracy > 500" class="flex items-center gap-2 text-sm bg-amber-500/10 border border-amber-500/20 rounded-lg p-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <span class="text-amber-200 text-xs">Lokasi tidak akurat (±<span x-text="Math.round(accuracy)"></span>m). Data bencana mungkin tidak tepat.</span>
            </div>

            <!-- Trend -->
            <div class="flex items-center gap-2 text-sm">
                <span class="text-slate-400">Trend:</span>
                <span
                    class="font-medium"
                    :class="{
                        'text-rose-400': trend === 'increasing',
                        'text-emerald-400': trend === 'decreasing',
                        'text-slate-300': trend === 'stable'
                    }"
                    x-text="trendLabel"
                ></span>
                <span
                    x-show="trendChange !== 0"
                    class="text-xs"
                    :class="{
                        'text-rose-400': trendChange > 0,
                        'text-emerald-400': trendChange < 0
                    }"
                    x-text="(trendChange > 0 ? '+' : '') + trendChange + '%'"
                ></span>
            </div>

            <!-- Recommendations -->
            <div x-show="recommendations.length > 0" class="space-y-2">
                <p class="text-sm font-medium text-slate-200">Rekomendasi:</p>
                <ul class="space-y-1">
                    <template x-for="rec in recommendations.slice(0, 3)" :key="rec">
                        <li class="flex items-start gap-2 text-sm text-slate-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-sky-400 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span x-text="rec"></span>
                        </li>
                    </template>
                </ul>
            </div>

            <!-- Nearby Disasters List -->
            <div x-show="nearbyDisasters.length > 0" class="space-y-2">
                <p class="text-sm font-medium text-slate-200">Bencana Terdekat:</p>
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    <template x-for="disaster in nearbyDisasters.slice(0, 5)" :key="disaster.id">
                        <div class="flex items-center justify-between p-2 rounded-lg bg-white/[0.03] text-sm">
                            <div class="flex items-center gap-2">
                                <span
                                    class="w-2 h-2 rounded-full"
                                    :class="{
                                        'bg-rose-400': disaster.severity === 'critical' || disaster.severity === 'high',
                                        'bg-amber-400': disaster.severity === 'medium',
                                        'bg-emerald-400': disaster.severity === 'low'
                                    }"
                                ></span>
                                <div>
                                    <p class="font-medium text-slate-200 capitalize" x-text="disaster.type"></p>
                                    <p class="text-xs text-slate-400" x-text="disaster.location"></p>
                                </div>
                            </div>
                            <span class="text-xs text-slate-400" x-text="disaster.distance_km + ' km'"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="px-6 py-3 bg-white/[0.03] border-t border-white/[0.06]">
        <div class="flex items-center justify-between gap-2">
            <button
                @click="requestLocation()"
                class="flex items-center gap-2 text-sm text-slate-400 hover:text-sky-400 transition"
                :class="{ 'animate-pulse': isLoading }"
                :disabled="isLoading"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span x-text="isLoading ? 'Memperbarui...' : 'Perbarui Lokasi'"></span>
            </button>
            <a href="{{ route('map.index') }}" class="flex items-center gap-2 text-sm text-sky-400 hover:text-sky-300 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 7m0 13V7" />
                </svg>
                Lihat Peta Bencana
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('zoneStatusWidget', () => ({
                status: 'requesting', // requesting, loading, active, denied, error, saved
                isLoading: false,
                latitude: null,
                longitude: null,
                accuracy: null, // Akurasi lokasi dalam meter
                zoneStatus: null,
                zoneLabel: null,
                warningMessage: '',
                metrics: {
                    total_disasters: 0,
                    max_cluster: 0
                },
                trend: 'stable',
                trendChange: 0,
                recommendations: [],
                nearbyDisasters: [],
                lastUpdated: '',
                errorMessage: '',
                savedLocationName: '',

                init() {
                    // Coba pakai lokasi tersimpan dulu untuk tampilkan data sementara
                    const stored = this.getStoredLocation();
                    if (stored) {
                        this.latitude = stored.latitude;
                        this.longitude = stored.longitude;
                        this.accuracy = stored.accuracy;
                        // Fetch data dengan lokasi tersimpan sementara
                        this.fetchZoneAnalysis();
                    }

                    // Check if geolocation is available
                    if (!navigator.geolocation) {
                        // Try to use saved location as fallback
                        this.tryUseSavedLocation();
                        return;
                    }

                    // Request lokasi akurat dari GPS (akan update data jika berhasil)
                    this.requestLocation();

                    // Auto-refresh zone analysis every 1 minute for real-time updates
                    setInterval(() => {
                        this.refreshLocation();
                    }, 60000);
                },

                async requestLocation() {
                    this.status = 'loading';
                    this.accuracy = null; // Reset akurasi saat request baru

                    try {
                        const position = await this.getCurrentPosition();
                        this.latitude = position.coords.latitude;
                        this.longitude = position.coords.longitude;
                        this.accuracy = position.coords.accuracy; // Simpan akurasi dalam meter

                        console.log('Lokasi diperbarui:', {
                            lat: this.latitude,
                            lng: this.longitude,
                            accuracy: this.accuracy
                        });

                        // Simpan lokasi akurat ke localStorage untuk fallback
                        this.saveAccurateLocation();

                        await this.fetchZoneAnalysis();
                        this.status = 'active';
                    } catch (error) {
                        this.handleLocationError(error);
                    }
                },

                // Simpan lokasi dengan akurasi tinggi ke localStorage
                saveAccurateLocation() {
                    if (this.accuracy && this.accuracy <= 100) { // Hanya simpan jika akurasi <= 100m
                        const locationData = {
                            latitude: this.latitude,
                            longitude: this.longitude,
                            accuracy: this.accuracy,
                            timestamp: new Date().toISOString()
                        };
                        localStorage.setItem('resq_last_accurate_location', JSON.stringify(locationData));
                    }
                },

                // Ambil lokasi tersimpan dari localStorage sebagai fallback
                getStoredLocation() {
                    try {
                        const stored = localStorage.getItem('resq_last_accurate_location');
                        if (stored) {
                            const data = JSON.parse(stored);
                            // Cek apakah data masih valid (kurang dari 24 jam)
                            const storedTime = new Date(data.timestamp);
                            const now = new Date();
                            const hoursDiff = (now - storedTime) / (1000 * 60 * 60);

                            if (hoursDiff < 24 && data.accuracy <= 100) {
                                return data;
                            }
                        }
                    } catch (e) {
                        console.error('Error reading stored location:', e);
                    }
                    return null;
                },

                async useSavedLocation() {
                    this.status = 'loading';
                    this.tryUseSavedLocation();
                },

                tryUseSavedLocation() {
                    // Coba pakai lokasi dari localStorage dulu (lebih akurat dan fresh)
                    const stored = this.getStoredLocation();
                    if (stored) {
                        this.latitude = stored.latitude;
                        this.longitude = stored.longitude;
                        this.accuracy = stored.accuracy;
                        this.savedLocationName = 'Lokasi Tersimpan (Akurat)';
                        this.fetchZoneAnalysis().then(() => {
                            this.status = 'saved';
                        });
                        return;
                    }

                    // Fallback ke lokasi tersimpan di database jika ada
                    @if(auth()->check() && $defaultLocation = auth()->user()->locations()->where('is_default', true)->first())
                        this.latitude = {{ $defaultLocation->latitude }};
                        this.longitude = {{ $defaultLocation->longitude }};
                        this.accuracy = null; // Tidak tahu akurasi lokasi tersimpan
                        this.savedLocationName = '{{ $defaultLocation->name }}';
                        this.fetchZoneAnalysis().then(() => {
                            this.status = 'saved';
                        });
                    @else
                        this.status = 'denied';
                    @endif
                },

                async refreshLocation() {
                    if (this.isLoading) return;
                    this.isLoading = true;
                    this.accuracy = null; // Reset akurasi saat refresh
                    await this.requestLocation();
                    this.isLoading = false;
                },

                getCurrentPosition() {
                    return new Promise((resolve, reject) => {
                        navigator.geolocation.getCurrentPosition(resolve, reject, {
                            enableHighAccuracy: true,
                            timeout: 30000, // 30 detik untuk akurasi GPS lebih tinggi
                            maximumAge: 0 // Selalu ambil lokasi fresh, tanpa cache
                        });
                    });
                },

                handleLocationError(error) {
                    console.error('Geolocation error:', error);

                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            // Try saved location as fallback
                            this.tryUseSavedLocation();
                            break;
                        case error.POSITION_UNAVAILABLE:
                            // Coba pakai lokasi tersimpan jika ada
                            const stored = this.getStoredLocation();
                            if (stored) {
                                this.latitude = stored.latitude;
                                this.longitude = stored.longitude;
                                this.accuracy = stored.accuracy;
                                this.fetchZoneAnalysis().then(() => {
                                    this.status = 'active';
                                });
                            } else {
                                this.status = 'error';
                                this.errorMessage = 'Informasi lokasi tidak tersedia. Aktifkan GPS untuk hasil lebih akurat.';
                            }
                            break;
                        case error.TIMEOUT:
                            // Coba pakai lokasi tersimpan jika timeout
                            const cached = this.getStoredLocation();
                            if (cached) {
                                this.latitude = cached.latitude;
                                this.longitude = cached.longitude;
                                this.accuracy = cached.accuracy;
                                this.fetchZoneAnalysis().then(() => {
                                    this.status = 'active';
                                });
                            } else {
                                this.status = 'error';
                                this.errorMessage = 'Waktu permintaan lokasi habis. Pastikan sinyal GPS bagus atau coba di area terbuka.';
                            }
                            break;
                        default:
                            this.status = 'error';
                            this.errorMessage = 'Terjadi kesalahan saat mengakses lokasi';
                    }
                },

                async fetchZoneAnalysis() {
                    if (!this.latitude || !this.longitude) return;

                    try {
                        const response = await fetch('/api/v1/location/analyze', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                            },
                            body: JSON.stringify({
                                latitude: this.latitude,
                                longitude: this.longitude
                            })
                        });

                        if (!response.ok) throw new Error('Failed to fetch zone analysis');

                        const result = await response.json();

                        if (result.success) {
                            const data = result.data;

                            this.zoneStatus = data.zone.status;
                            this.zoneLabel = data.zone.label;
                            this.warningMessage = data.warning || '';
                            this.metrics = {
                                total_disasters: data.metrics.total_nearby_disasters,
                                max_cluster: data.metrics.max_cluster_size
                            };
                            this.trend = data.trend.trend;
                            this.trendChange = data.trend.change_percent;
                            this.recommendations = data.recommendations || [];
                            this.nearbyDisasters = data.nearby_disasters || [];
                            this.lastUpdated = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

                            // Update lokasi tersimpan jika akurasi bagus
                            if (this.accuracy && this.accuracy <= 100) {
                                this.saveAccurateLocation();
                            }
                        }
                    } catch (error) {
                        console.error('Zone analysis error:', error);
                        this.status = 'error';
                        this.errorMessage = 'Gagal mengambil data status zona';
                    }
                },

                get trendLabel() {
                    const labels = {
                        'increasing': 'Meningkat',
                        'decreasing': 'Menurun',
                        'stable': 'Stabil'
                    };
                    return labels[this.trend] || this.trend;
                }
            }));
        });
    </script>
</div>
