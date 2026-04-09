<x-app-layout>
    {{-- Edit Location --}}
    <div class="min-h-screen bg-slate-950 pb-24 lg:pb-8" x-data="locationPicker()" x-cloak>

        {{-- DESKTOP SIDEBAR --}}
        <aside class="hidden lg:flex fixed top-0 left-0 h-full z-50 flex-col"
               x-data="{ sidebarHover: false }"
               @mouseenter="sidebarHover = true"
               @mouseleave="sidebarHover = false">
            <div class="h-full bg-slate-900/95 backdrop-blur-2xl border-r border-white/5 shadow-soft-lg flex flex-col py-6 sidebar-spring overflow-hidden"
                 :class="sidebarHover ? 'w-64' : 'w-[72px]'">
                <div class="flex items-center gap-3 px-4 mb-8 overflow-hidden">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-400 flex items-center justify-center shrink-0 shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="font-bold text-xl text-white whitespace-nowrap transition-opacity duration-300"
                          :class="sidebarHover ? 'opacity-100' : 'opacity-0'">ResQ</span>
                </div>
                <nav class="flex-1 flex flex-col gap-1 px-3">
                    @php
                        $menuItems = [
                            ['route' => 'dashboard', 'label' => 'Beranda', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'active' => 'dashboard'],
                            ['route' => 'map.index', 'label' => 'Peta Interaktif', 'icon' => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7', 'active' => 'map.*'],
                            ['route' => 'guides.index', 'label' => 'Edukasi & Pelatihan', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'active' => 'guides.*'],
                            ['route' => 'articles.index', 'label' => 'Berita & Info', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z', 'active' => 'articles.*'],
                            ['route' => 'chat-history.index', 'label' => 'Riwayat Chat', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'active' => 'chat-history.*'],
                            ['route' => 'ai-assist.index', 'label' => 'AI Assistant', 'icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z', 'active' => 'ai-assist.*'],
                            ['route' => 'locations.index', 'label' => 'Lokasi Tersimpan', 'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z', 'active' => 'locations.*'],
                            ['route' => 'profile.edit', 'label' => 'Profil', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'active' => 'profile.*'],
                        ];
                    @endphp
                    @foreach($menuItems as $item)
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group whitespace-nowrap
                                  {{ request()->routeIs($item['active']) ? 'menu-active' : 'text-slate-400 hover:bg-white/5 hover:text-emerald-400' }}">
                            <div class="w-7 h-7 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium transition-opacity duration-300"
                                  :class="sidebarHover ? 'opacity-100' : 'opacity-0'">{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
                <div class="px-3 mt-auto relative" x-data="{ openOptions: false }">
                    <button @click="openOptions = !openOptions" @click.away="openOptions = false" class="relative z-[90] w-full flex items-center gap-3 px-3 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 overflow-hidden transition-all duration-200 group focus:outline-none">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center shrink-0 ring-2 ring-white/10 group-hover:ring-emerald-500/50 transition-all">
                            <span class="text-white font-bold text-xs">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                        </div>
                        <div class="min-w-0 text-left transition-opacity duration-300" :class="sidebarHover ? 'opacity-100' : 'opacity-0'">
                            <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </button>
                </div>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="lg:ml-[72px]">

            {{-- HEADER --}}
            <section class="relative overflow-hidden glass-dark border-b border-white/5">
                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-6 sm:pt-8 sm:pb-8">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('locations.index') }}" class="p-2 bg-white/[0.05] hover:bg-white/10 text-slate-300 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        </a>
                        <div>
                            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">Edit Lokasi</h1>
                            <p class="text-slate-400 text-sm mt-0.5">Perbarui informasi lokasi tersimpan</p>
                        </div>
                    </div>
                </div>
            </section>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                <form action="{{ route('locations.update', $location) }}" method="POST" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @csrf
                    @method('PATCH')

                    {{-- Left Column - Form --}}
                    <div class="space-y-6">
                        {{-- Location Name --}}
                        <div class="glass-dark border border-white/5 rounded-2xl p-5">
                            <label class="block text-sm font-medium text-white mb-2">Nama Lokasi <span class="text-rose-400">*</span></label>
                            <input type="text" name="name" x-model="name" required
                                   value="{{ old('name', $location->name) }}"
                                   class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/50">
                        </div>

                        {{-- Coordinates --}}
                        <div class="glass-dark border border-white/5 rounded-2xl p-5">
                            <label class="block text-sm font-medium text-white mb-3">Koordinat <span class="text-rose-400">*</span></label>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs text-slate-400 mb-1 block">Latitude</label>
                                    <input type="text" inputmode="decimal" name="latitude" x-model="latitude" required
                                           value="{{ old('latitude', $location->latitude) }}"
                                           class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/50">
                                </div>
                                <div>
                                    <label class="text-xs text-slate-400 mb-1 block">Longitude</label>
                                    <input type="text" inputmode="decimal" name="longitude" x-model="longitude" required
                                           value="{{ old('longitude', $location->longitude) }}"
                                           class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/50">
                                </div>
                            </div>
                            <div class="flex gap-2 mt-3">
                                <button type="button" @click="getCurrentLocation()"
                                        class="flex-1 py-2 px-3 bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/20 text-emerald-400 text-sm font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Gunakan Lokasi Saat Ini
                                </button>
                                <button type="button" @click="searchLocation()"
                                        class="flex-1 py-2 px-3 bg-white/[0.05] hover:bg-white/10 border border-white/10 text-slate-300 text-sm font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    Cari di Peta
                                </button>
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="glass-dark border border-white/5 rounded-2xl p-5">
                            <label class="block text-sm font-medium text-white mb-2">Alamat</label>
                            <textarea name="address" x-model="address" rows="3"
                                      class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/50 resize-none">{{ old('address', $location->address) }}</textarea>
                        </div>

                        {{-- Notification Settings --}}
                        <div class="glass-dark border border-white/5 rounded-2xl p-5">
                            <h3 class="font-medium text-white mb-4">Pengaturan Notifikasi</h3>

                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-sm text-white">Aktifkan Notifikasi</p>
                                    <p class="text-xs text-slate-400">Kirim peringatan via WhatsApp</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="notifications_enabled" x-model="notificationsEnabled" value="1"
                                           {{ old('notifications_enabled', $location->notifications_enabled) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                                </label>
                            </div>

                            <div>
                                <label class="block text-sm text-white mb-2">Radius Notifikasi</label>
                                <div class="flex items-center gap-3">
                                    <input type="range" name="notification_radius_km" x-model="radius" min="10" max="500" step="10"
                                           value="{{ old('notification_radius_km', $location->notification_radius_km) }}"
                                           class="flex-1 h-2 bg-slate-700 rounded-lg appearance-none cursor-pointer accent-emerald-500">
                                    <span class="text-sm text-white font-medium w-16 text-right" x-text="radius + ' km'"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Default Location --}}
                        <div class="glass-dark border border-white/5 rounded-2xl p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-white">Jadikan Lokasi Default</p>
                                    <p class="text-xs text-slate-400">Lokasi ini akan digunakan untuk widget status zona</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_default" x-model="isDefault" value="1"
                                           {{ old('is_default', $location->is_default) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                                </label>
                            </div>
                        </div>

                        {{-- Submit Buttons --}}
                        <div class="flex gap-3">
                            <a href="{{ route('locations.index') }}" class="flex-1 py-3 px-4 bg-white/[0.05] hover:bg-white/10 text-slate-300 text-sm font-semibold rounded-xl transition-colors text-center">
                                Batal
                            </a>
                            <button type="submit" class="flex-1 py-3 px-4 bg-gradient-to-r from-emerald-500 to-green-500 text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:scale-[1.02] transition-all duration-300">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>

                    {{-- Right Column - Map Preview --}}
                    <div class="lg:sticky lg:top-6 h-fit">
                        <div class="glass-dark border border-white/5 rounded-2xl p-5">
                            <h3 class="font-medium text-white mb-3">Pratinjau Lokasi</h3>
                            <div class="aspect-video bg-slate-800 rounded-xl overflow-hidden relative">
                                <iframe
                                    x-ref="mapFrame"
                                    :src="mapUrl"
                                    width="100%"
                                    height="100%"
                                    style="border:0;"
                                    allowfullscreen=""
                                    loading="lazy"
                                    class="absolute inset-0">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function locationPicker() {
            return {
                name: '{{ $location->name }}',
                latitude: '{{ $location->latitude }}',
                longitude: '{{ $location->longitude }}',
                address: '{{ $location->address ?? '' }}',
                notificationsEnabled: {{ $location->notifications_enabled ? 'true' : 'false' }},
                radius: {{ $location->notification_radius_km }},
                isDefault: {{ $location->is_default ? 'true' : 'false' }},

                get mapUrl() {
                    return `https://www.openstreetmap.org/export/embed.html?bbox=${parseFloat(this.longitude)-0.01}%2C${parseFloat(this.latitude)-0.01}%2C${parseFloat(this.longitude)+0.01}%2C${parseFloat(this.latitude)+0.01}&layer=mapnik&marker=${this.latitude}%2C${this.longitude}`;
                },

                async getCurrentLocation() {
                    if (!navigator.geolocation) {
                        alert('Browser Anda tidak mendukung geolocation');
                        return;
                    }

                    try {
                        const position = await new Promise((resolve, reject) => {
                            navigator.geolocation.getCurrentPosition(resolve, reject, {
                                enableHighAccuracy: true,
                                timeout: 10000
                            });
                        });

                        this.latitude = position.coords.latitude.toFixed(6);
                        this.longitude = position.coords.longitude.toFixed(6);

                        await this.reverseGeocode();
                    } catch (error) {
                        alert('Gagal mendapatkan lokasi: ' + error.message);
                    }
                },

                async reverseGeocode() {
                    try {
                        const response = await fetch('/locations/reverse-geocode', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                            },
                            body: JSON.stringify({
                                latitude: this.latitude,
                                longitude: this.longitude
                            })
                        });

                        const data = await response.json();
                        if (data.success && data.address) {
                            this.address = data.address;
                        }
                    } catch (e) {
                        console.error('Reverse geocode error:', e);
                    }
                },

                searchLocation() {
                    const lat = prompt('Masukkan Latitude:', this.latitude);
                    const lng = prompt('Masukkan Longitude:', this.longitude);

                    if (lat && lng) {
                        this.latitude = lat;
                        this.longitude = lng;
                        this.reverseGeocode();
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
