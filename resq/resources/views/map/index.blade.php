<x-app-layout>
    @push('styles')
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @endpush

    {{-- Peta Interaktif — Fluid Modern Dark Dashboard Style --}}

    <div class="min-h-screen bg-slate-950 pb-24 lg:pb-0"
         x-data="mapDashboard()" x-cloak>

        {{-- ============================================
             1. DESKTOP SIDEBAR
             ============================================ --}}
        <aside class="hidden lg:flex fixed top-0 left-0 h-full z-50 flex-col"
               x-data="{ sidebarHover: false }"
               @mouseenter="sidebarHover = true"
               @mouseleave="sidebarHover = false">
            <div class="h-full bg-slate-900/95 backdrop-blur-2xl border-r border-white/5 shadow-soft-lg flex flex-col py-6 sidebar-spring overflow-hidden"
                 :class="sidebarHover ? 'w-64' : 'w-[72px]'">
                {{-- Logo --}}
                <div class="flex items-center gap-3 px-4 mb-8 overflow-hidden">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-400 flex items-center justify-center shrink-0 shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="font-bold text-xl text-white whitespace-nowrap transition-opacity duration-300"
                          :class="sidebarHover ? 'opacity-100' : 'opacity-0'">ResQ</span>
                </div>

                {{-- Menu Items --}}
                <nav class="flex-1 flex flex-col gap-1 px-3">
                    @php
                        $menuItems = [
                            ['route' => 'dashboard', 'label' => 'Beranda', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'active' => 'dashboard'],
                            ['route' => 'map.index', 'label' => 'Peta Interaktif', 'icon' => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7', 'active' => 'map.*'],
                            ['route' => 'guides.index', 'label' => 'Edukasi & Pelatihan', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'active' => 'guides.*'],
                            ['route' => 'articles.index', 'label' => 'Berita & Info', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z', 'active' => 'articles.*'],
                            ['route' => 'chat-history.index', 'label' => 'Riwayat Chat', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'active' => 'chat-history.*'],
                            ['route' => 'ai-assist.index', 'label' => 'AI Assistant', 'icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z', 'active' => 'ai-assist.*'],
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

                {{-- User section --}}
                <div class="px-3 mt-auto">
                    <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-white/5 overflow-hidden">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center shrink-0 ring-2 ring-white/10">
                            <span class="text-white font-bold text-xs">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                        </div>
                        <div class="min-w-0 transition-opacity duration-300" :class="sidebarHover ? 'opacity-100' : 'opacity-0'">
                            <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        {{-- ============================================
             MAIN CONTENT AREA
             ============================================ --}}
        <div class="lg:ml-[72px]">

            {{-- ============================================
                 2. HERO HEADER — Map Edition
                 ============================================ --}}
            <section class="relative overflow-hidden glass-dark border-b border-white/5 pt-4" data-aos="fade-down" data-aos-duration="1000">
                {{-- Decorative elements --}}
                <div class="absolute inset-0 overflow-hidden pointer-events-none">
                    <div class="absolute -top-16 -right-16 w-72 h-72 bg-sky-500/10 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl"></div>
                    {{-- Grid pattern --}}
                    <svg class="absolute inset-0 w-full h-full opacity-[0.05]" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="grid-map" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                                <path d="M 40 0 L 0 0 0 40" fill="none" stroke="currentColor" stroke-width="0.5"/>
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#grid-map)" class="text-white"/>
                    </svg>
                </div>

                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-6 sm:pt-8 sm:pb-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-4" data-aos="fade-right" data-aos-delay="200">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">Peta Interaktif</h1>
                                <p class="text-slate-400 text-sm mt-0.5">Pantau lokasi bencana di seluruh Indonesia secara real-time</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3" data-aos="fade-left" data-aos-delay="300">
                            <span class="inline-flex items-center gap-2 glass border border-white/10 px-4 py-2 rounded-full shadow-sm" id="connection-status">
                                <span class="relative flex h-2.5 w-2.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                                </span>
                                <span class="text-sm font-medium text-slate-300">Live</span>
                            </span>
                            <span class="text-xs text-slate-400 hidden sm:inline" id="last-updated">
                                Terakhir: <span id="last-updated-time" class="font-medium text-slate-300">-</span>
                            </span>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ============================================
                 3. MAP + FILTER LAYOUT
                 ============================================ --}}
            <div class="max-w-[1920px] mx-auto p-4 sm:p-6 lg:p-8">
                <div class="flex flex-col lg:flex-row gap-6" style="height: calc(100vh - 200px); min-height: 600px;">

                    {{-- ========================
                         FILTER PANEL
                         ======================== --}}
                    <div class="w-full lg:w-80 flex-shrink-0 flex flex-col glass-dark border border-white/5 rounded-2xl shadow-soft" x-data="{ filterOpen: window.innerWidth >= 1024 }">

                        {{-- Mobile toggle --}}
                        <button @click="filterOpen = !filterOpen"
                                class="lg:hidden flex items-center justify-between w-full px-4 py-4 border-b border-white/5">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                </svg>
                                <span class="font-semibold text-sm text-white">Filter & Pencarian</span>
                            </div>
                            <svg class="w-5 h-5 text-slate-400 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 :class="filterOpen ? 'rotate-180' : ''">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- Filter content --}}
                        <div x-show="filterOpen"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-2"
                             class="overflow-y-auto lg:h-full custom-scrollbar flex-1"
                             style="max-height: 60vh;"
                             :style="window.innerWidth >= 1024 ? 'max-height: none' : ''">

                            <div class="p-5 space-y-5">
                                {{-- Location Search Card --}}
                                <div class="bg-white/5 border border-white/10 rounded-xl p-4 shadow-sm" data-aos="fade-up" data-aos-delay="100">
                                    <label for="location-search" class="flex items-center gap-2 text-sm font-semibold text-white mb-3">
                                        <div class="w-7 h-7 rounded-lg bg-sky-500/10 flex items-center justify-center border border-sky-500/20">
                                            <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                        Cari Lokasi
                                    </label>
                                    <div class="flex gap-2">
                                        <div class="flex-1 relative">
                                            <input type="text" id="location-search"
                                                   placeholder="Contoh: Jakarta..."
                                                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all duration-200 shadow-inner">
                                        </div>
                                        <button id="search-btn" class="px-3.5 py-2.5 bg-gradient-to-r from-emerald-500 to-green-500 text-white rounded-xl shadow-lg shadow-emerald-500/20 hover:scale-[1.02] transition-all duration-200 active:scale-[0.98]" title="Cari">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <p id="search-error" class="text-xs text-rose-400 mt-2 hidden flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>Lokasi tidak ditemukan</span>
                                    </p>
                                </div>

                                {{-- Radius --}}
                                <div class="bg-white/5 border border-white/10 rounded-xl p-4 shadow-sm" data-aos="fade-up" data-aos-delay="200">
                                    <label class="flex items-center gap-2 text-sm font-semibold text-white mb-3">
                                        <div class="w-7 h-7 rounded-lg bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20">
                                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        Radius Pencarian
                                    </label>
                                    <div class="relative">
                                        <select id="radius-select" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white appearance-none cursor-pointer focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all duration-200 pr-10 shadow-inner">
                                            <option value="25">25 km</option>
                                            <option value="50" selected>50 km</option>
                                            <option value="100">100 km</option>
                                            <option value="200">200 km</option>
                                            <option value="500">500 km</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 pointer-events-none">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Disaster Type Filters --}}
                                <div class="bg-white/5 border border-white/10 rounded-xl p-4 shadow-sm" data-aos="fade-up" data-aos-delay="300">
                                    <label class="flex items-center gap-2 text-sm font-semibold text-white mb-3">
                                        <div class="w-7 h-7 rounded-lg bg-amber-500/10 flex items-center justify-center border border-amber-500/20">
                                            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                        </div>
                                        Jenis Bencana
                                    </label>
                                    <div class="space-y-1.5 max-h-44 overflow-y-auto pr-1 custom-scrollbar w-[101%]" id="disaster-type-filters">
                                        @foreach($disasterTypes as $type)
                                            <label class="flex items-center p-2.5 rounded-xl hover:bg-white/5 cursor-pointer transition-all duration-200 group mr-[2%]">
                                                <input type="checkbox" name="disaster_types" value="{{ $type }}" checked
                                                       class="w-4 h-4 rounded-lg bg-slate-900 border-white/20 text-emerald-500 focus:ring-emerald-500 transition-colors cursor-pointer">
                                                <span class="ml-2.5 text-sm text-slate-300 capitalize flex items-center gap-2 group-hover:text-white transition-colors">
                                                    @if($type === 'earthquake')
                                                        <span class="w-5 h-5 rounded-lg bg-orange-500/20 flex items-center justify-center text-[10px] border border-orange-500/30">🌍</span>
                                                    @elseif($type === 'flood')
                                                        <span class="w-5 h-5 rounded-lg bg-blue-500/20 flex items-center justify-center text-[10px] border border-blue-500/30">🌊</span>
                                                    @elseif($type === 'landslide')
                                                        <span class="w-5 h-5 rounded-lg bg-amber-500/20 flex items-center justify-center text-[10px] border border-amber-500/30">⛰️</span>
                                                    @elseif($type === 'tsunami')
                                                        <span class="w-5 h-5 rounded-lg bg-cyan-500/20 flex items-center justify-center text-[10px] border border-cyan-500/30">🌊</span>
                                                    @elseif($type === 'fire')
                                                        <span class="w-5 h-5 rounded-lg bg-rose-500/20 flex items-center justify-center text-[10px] border border-rose-500/30">🔥</span>
                                                    @elseif($type === 'volcano')
                                                        <span class="w-5 h-5 rounded-lg bg-red-500/20 flex items-center justify-center text-[10px] border border-red-500/30">🌋</span>
                                                    @else
                                                        <span class="w-5 h-5 rounded-lg bg-slate-500/20 flex items-center justify-center text-[10px] border border-slate-500/30">⚠️</span>
                                                    @endif
                                                    {{ ucfirst($type) }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <div class="mt-3 flex gap-3 text-xs pt-3 border-t border-white/5">
                                        <button id="select-all-types" class="text-emerald-400 hover:text-emerald-300 font-semibold transition-colors">
                                            Pilih Semua
                                        </button>
                                        <span class="text-slate-600">|</span>
                                        <button id="deselect-all-types" class="text-slate-400 hover:text-white font-medium transition-colors">
                                            Batal Pilih
                                        </button>
                                    </div>
                                </div>

                                {{-- Severity filter --}}
                                <div class="bg-white/5 border border-white/10 rounded-xl p-4 shadow-sm" data-aos="fade-up" data-aos-delay="400">
                                    <label class="flex items-center gap-2 text-sm font-semibold text-white mb-3">
                                        <div class="w-7 h-7 rounded-lg bg-rose-500/10 flex items-center justify-center border border-rose-500/20">
                                            <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                        </div>
                                        Tingkat Keparahan
                                    </label>
                                    <div class="space-y-1.5">
                                        <label class="flex items-center p-2.5 rounded-xl hover:bg-white/5 cursor-pointer transition-all duration-200 group">
                                            <input type="checkbox" name="severity" value="critical" checked class="w-4 h-4 rounded-lg bg-slate-900 border-white/20 text-rose-500 focus:ring-rose-500 cursor-pointer">
                                            <span class="ml-2.5 text-sm text-slate-300 flex items-center gap-2 group-hover:text-white transition-colors">
                                                <span class="w-3 h-3 rounded-full bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.6)]"></span>
                                                <span class="font-medium">Kritis</span>
                                            </span>
                                        </label>
                                        <label class="flex items-center p-2.5 rounded-xl hover:bg-white/5 cursor-pointer transition-all duration-200 group">
                                            <input type="checkbox" name="severity" value="high" checked class="w-4 h-4 rounded-lg bg-slate-900 border-white/20 text-rose-500 focus:ring-rose-500 cursor-pointer">
                                            <span class="ml-2.5 text-sm text-slate-300 flex items-center gap-2 group-hover:text-white transition-colors">
                                                <span class="w-3 h-3 rounded-full bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.6)]"></span>
                                                <span class="font-medium">Tinggi</span>
                                            </span>
                                        </label>
                                        <label class="flex items-center p-2.5 rounded-xl hover:bg-white/5 cursor-pointer transition-all duration-200 group">
                                            <input type="checkbox" name="severity" value="medium" checked class="w-4 h-4 rounded-lg bg-slate-900 border-white/20 text-amber-500 focus:ring-amber-500 cursor-pointer">
                                            <span class="ml-2.5 text-sm text-slate-300 flex items-center gap-2 group-hover:text-white transition-colors">
                                                <span class="w-3 h-3 rounded-full bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.6)]"></span>
                                                <span class="font-medium">Sedang</span>
                                            </span>
                                        </label>
                                        <label class="flex items-center p-2.5 rounded-xl hover:bg-white/5 cursor-pointer transition-all duration-200 group">
                                            <input type="checkbox" name="severity" value="low" checked class="w-4 h-4 rounded-lg bg-slate-900 border-white/20 text-emerald-500 focus:ring-emerald-500 cursor-pointer">
                                            <span class="ml-2.5 text-sm text-slate-300 flex items-center gap-2 group-hover:text-white transition-colors">
                                                <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.6)]"></span>
                                                <span class="font-medium">Rendah</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Date Range --}}
                                <div class="bg-white/5 border border-white/10 rounded-xl p-4 shadow-sm" data-aos="fade-up" data-aos-delay="500">
                                    <label class="flex items-center gap-2 text-sm font-semibold text-white mb-3">
                                        <div class="w-7 h-7 rounded-lg bg-purple-500/10 flex items-center justify-center border border-purple-500/20">
                                            <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        Rentang Waktu
                                    </label>
                                    <div class="space-y-2">
                                        <div class="relative dark-calendar">
                                            <input type="date" id="date-from" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none shadow-inner">
                                        </div>
                                        <div class="flex items-center justify-center">
                                            <span class="text-[11px] font-semibold tracking-wider text-slate-500 uppercase">sampai</span>
                                        </div>
                                        <div class="relative dark-calendar">
                                            <input type="date" id="date-to" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none shadow-inner">
                                        </div>
                                    </div>
                                </div>

                                {{-- Stats Card --}}
                                <div class="bg-gradient-to-br from-emerald-950 to-slate-900 rounded-xl p-4 shadow-sm border border-emerald-500/20 relative overflow-hidden" data-aos="fade-up" data-aos-delay="600">
                                    <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl"></div>
                                    <h4 class="text-sm font-semibold text-white mb-3 flex items-center gap-2 relative z-10">
                                        <div class="w-7 h-7 rounded-lg bg-emerald-500/20 flex items-center justify-center border border-emerald-500/30">
                                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                        </div>
                                        Statistik
                                    </h4>
                                    <div class="grid grid-cols-2 gap-3 relative z-10">
                                        <div class="bg-slate-900/80 rounded-xl p-3 text-center border border-white/5">
                                            <div class="text-2xl font-bold text-white" id="total-disasters">0</div>
                                            <div class="text-[10px] text-slate-400 font-medium mt-0.5">Total Bencana</div>
                                        </div>
                                        <div class="bg-slate-900/80 rounded-xl p-3 text-center border border-white/5">
                                            <div class="text-2xl font-bold text-emerald-400" id="visible-disasters">0</div>
                                            <div class="text-[10px] text-slate-400 font-medium mt-0.5">Ditampilkan</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Reset Button --}}
                                <button id="reset-filters" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white/5 rounded-xl border border-white/10 text-sm font-medium text-slate-400 hover:text-emerald-400 hover:bg-white/10 hover:border-emerald-500/30 transition-all duration-300 active:scale-[0.98]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Reset Semua Filter
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- ========================
                         MAP CONTAINER
                         ======================== --}}
                    <div class="flex-1 relative rounded-2xl overflow-hidden glass-dark border border-white/5 shadow-soft" data-aos="zoom-in" data-aos-delay="200">
                        <div id="map" class="w-full h-full min-h-[500px]"></div>

                        {{-- Loading Overlay --}}
                        <div id="map-loading" class="absolute inset-0 bg-slate-900/80 backdrop-blur-md flex items-center justify-center hidden" style="z-index: 9999;">
                            <div class="flex flex-col items-center bg-slate-800 rounded-2xl shadow-2xl px-8 py-6 border border-white/20 ring-1 ring-black/50">
                                <div class="w-12 h-12 border-[3px] border-slate-600 border-t-emerald-500 rounded-full animate-spin"></div>
                                <span class="mt-4 text-sm font-medium text-slate-300">Memuat rute dan data satelit...</span>
                            </div>
                        </div>

                        {{-- Auto-refresh Toggle --}}
                        <div class="absolute top-4 right-4 bg-slate-800/95 backdrop-blur-xl rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.5)] p-3.5 border border-white/20 hover:border-emerald-500/50 transition-all duration-300" style="z-index: 9999;">
                            <label class="flex items-center cursor-pointer gap-3">
                                <input type="checkbox" id="auto-refresh" checked class="sr-only peer">
                                <div class="relative w-10 h-5 bg-slate-700 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-emerald-500/50 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500 after:shadow-sm"></div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-white">Auto-refresh</span>
                                    <span class="text-[10px] text-slate-400">Tiap 5 menit</span>
                                </div>
                            </label>
                        </div>

                        {{-- Legend --}}
                        <div class="absolute bottom-4 left-4 lg:bottom-6 lg:left-6 bg-slate-800/95 backdrop-blur-xl rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.5)] p-4 border border-white/20" style="z-index: 9999;">
                            <h4 class="text-[11px] font-bold text-slate-300 mb-3 flex items-center gap-1.5 uppercase tracking-wider">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                </svg>
                                Indikator
                            </h4>
                            <div class="space-y-2">
                                <div class="flex items-center gap-2 bg-rose-500/10 rounded-lg px-2.5 py-1.5 border border-rose-500/20">
                                    <span class="w-3.5 h-3.5 rounded-full bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.6)]"></span>
                                    <span class="text-[11px] font-semibold text-rose-300 uppercase tracking-wide">Tinggi / Kritis</span>
                                </div>
                                <div class="flex items-center gap-2 bg-amber-500/10 rounded-lg px-2.5 py-1.5 border border-amber-500/20">
                                    <span class="w-3.5 h-3.5 rounded-full bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.6)]"></span>
                                    <span class="text-[11px] font-semibold text-amber-300 uppercase tracking-wide">Sedang</span>
                                </div>
                                <div class="flex items-center gap-2 bg-emerald-500/10 rounded-lg px-2.5 py-1.5 border border-emerald-500/20">
                                    <span class="w-3.5 h-3.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.6)]"></span>
                                    <span class="text-[11px] font-semibold text-emerald-300 uppercase tracking-wide">Rendah</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alpine.js Controller --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('mapDashboard', () => ({
                init() {}
            }));
        });
    </script>

    {{-- Map JavaScript --}}
    @push('scripts')
    <!-- AOS Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

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

        // Severity colors (nature theme)
        const SEVERITY_COLORS = {
            critical: '#f43f5e',   // rose-500
            high: '#f43f5e',       // rose-500
            medium: '#f59e0b',     // amber-500
            low: '#10b981',        // emerald-500
        };

        // Initialize map with Leaflet.js
        function initMap() {
            // Init AOS
            AOS.init({
                duration: 800,
                once: true,
                offset: 50,
            });

            // Initialize Leaflet map
            map = L.map('map', {
                center: [INDONESIA_CENTER.lat, INDONESIA_CENTER.lng],
                zoom: 5,
                minZoom: 4,
                maxZoom: 18,
                zoomControl: false, // We'll re-add it customized
                attributionControl: true
            });

            // Add Zoom Control Bottom Right
            L.control.zoom({
                position: 'bottomright'
            }).addTo(map);

            // Using Carto Dark Matter base map perfectly suits our slate-950 theme
            L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                maxZoom: 18
            }).addTo(map);

            // Initialize marker cluster group with dark theme styling
            markerCluster = L.markerClusterGroup({
                chunkedLoading: true,
                spiderfyOnMaxZoom: true,
                showCoverageOnHover: false,
                zoomToBoundsOnClick: true,
                maxClusterRadius: 80,
                iconCreateFunction: function(cluster) {
                    const count = cluster.getChildCount();
                    let color = '#059669'; // emerald-600
                    let glow = 'rgba(5, 150, 105, 0.5)';
                    if (count > 10) { color = '#d97706'; glow = 'rgba(217, 119, 6, 0.5)'; }
                    if (count > 50) { color = '#e11d48'; glow = 'rgba(225, 29, 72, 0.5)'; }

                    return L.divIcon({
                        html: `<div style="background-color: ${color}; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 14px; border: 2px solid rgba(255,255,255,0.2); box-shadow: 0 0 15px ${glow}; backdrop-filter: blur(4px);">${count}</div>`,
                        className: 'marker-cluster-custom',
                        iconSize: L.point(40, 40),
                        iconAnchor: [20, 20]
                    });
                }
            });

            map.addLayer(markerCluster);
            loadDisasters();
            startAutoRefresh();
        }

        document.addEventListener('DOMContentLoaded', initMap);

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

        function buildQueryParams() {
            const params = new URLSearchParams();

            const selectedTypes = Array.from(document.querySelectorAll('input[name="disaster_types"]:checked'))
                .map(cb => cb.value);
            if (selectedTypes.length > 0) {
                selectedTypes.forEach(type => params.append('types[]', type));
            }

            const selectedSeverities = Array.from(document.querySelectorAll('input[name="severity"]:checked'))
                .map(cb => cb.value);
            if (selectedSeverities.length > 0) {
                selectedSeverities.forEach(sev => params.append('severity[]', sev));
            }

            const dateFrom = document.getElementById('date-from').value;
            const dateTo = document.getElementById('date-to').value;
            if (dateFrom) params.append('date_from', dateFrom);
            if (dateTo) params.append('date_to', dateTo);

            return params.toString();
        }

        function updateMarkers(features) {
            markerCluster.clearLayers();
            markers = [];

            features.forEach(feature => {
                const marker = createMarker(feature);
                markers.push(marker);
                markerCluster.addLayer(marker);
            });

            if (markers.length > 0) {
                const group = new L.featureGroup(markers);
                map.fitBounds(group.getBounds().pad(0.1));
            }

            document.getElementById('visible-disasters').textContent = features.length;
        }

        function createMarker(feature) {
            const props = feature.properties;
            const [lng, lat] = feature.geometry.coordinates;

            const glowColor = props.color;

            const marker = L.circleMarker([lat, lng], {
                radius: 12,
                fillColor: props.color,
                fillOpacity: 0.8,
                color: '#1e293b', // slate-800
                weight: 2,
                opacity: 1
            });

            // Make popup fully dark themed
            const severityClass = {
                critical: 'color:#fb7185;font-weight:700',
                high: 'color:#fb7185;font-weight:700',
                medium: 'color:#fbbf24;font-weight:700',
                low: 'color:#34d399;font-weight:700',
            }[props.severity] || 'color:#94a3b8';

            const severityBg = {
                critical: 'background:rgba(225, 29, 72, 0.1);border:1px solid rgba(225, 29, 72, 0.2)',
                high: 'background:rgba(225, 29, 72, 0.1);border:1px solid rgba(225, 29, 72, 0.2)',
                medium: 'background:rgba(217, 119, 6, 0.1);border:1px solid rgba(217, 119, 6, 0.2)',
                low: 'background:rgba(16, 185, 129, 0.1);border:1px solid rgba(16, 185, 129, 0.2)',
            }[props.severity] || 'background:rgba(255, 255, 255, 0.05);border:1px solid rgba(255, 255, 255, 0.1)';

            const popupContent = `
                <div style="padding:16px;max-width:280px;font-family:Poppins,system-ui,sans-serif;background:#0f172a;color:#f8fafc;">
                    <h3 style="font-weight:700;font-size:16px;color:#fff;text-transform:capitalize;margin:0 0 4px 0">${props.type}</h3>
                    <p style="font-size:13px;color:#94a3b8;margin:0 0 12px 0">${props.location}</p>
                    <div style="display:flex;flex-direction:column;gap:6px;font-size:13px;">
                        <div style="${severityBg};border-radius:10px;padding:8px 12px;display:flex;justify-content:space-between;align-items:center">
                            <span style="color:#94a3b8">Keparahan</span>
                            <span style="${severityClass};text-transform:capitalize">${props.severity}</span>
                        </div>
                        <div style="background:rgba(255, 255, 255, 0.05);border:1px solid rgba(255, 255, 255, 0.1);border-radius:10px;padding:8px 12px;display:flex;justify-content:space-between;align-items:center">
                            <span style="color:#94a3b8">Status</span>
                            <span style="color:#e2e8f0;text-transform:capitalize;font-weight:600">${props.status}</span>
                        </div>
                        <div style="background:rgba(255, 255, 255, 0.05);border:1px solid rgba(255, 255, 255, 0.1);border-radius:10px;padding:8px 12px;display:flex;justify-content:space-between;align-items:center">
                            <span style="color:#94a3b8">Waktu</span>
                            <span style="color:#e2e8f0;font-weight:600;font-size:12px">${new Date(props.created_at).toLocaleString('id-ID')}</span>
                        </div>
                    </div>
                    ${props.description ? `<p style="margin:12px 0 0 0;font-size:12px;color:#cbd5e1;background:rgba(255, 255, 255, 0.05);padding:10px;border-radius:10px;line-height:1.5">${props.description.substring(0, 120)}...</p>` : ''}
                </div>
            `;

            marker.bindPopup(popupContent, {
                maxWidth: 300,
                className: 'custom-dark-popup'
            });

            return marker;
        }

        async function searchLocation() {
            const location = document.getElementById('location-search').value.trim();
            if (!location) return;

            showLoading();
            document.getElementById('search-error').classList.add('hidden');

            try {
                const searchQuery = location.toLowerCase().includes('indonesia') ? location : `${location}, Indonesia`;
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery)}&limit=1&accept-language=id`);

                if (!response.ok) throw new Error('Geocoding request failed');

                const data = await response.json();

                if (data && data.length > 0) {
                    const result = data[0];
                    const lat = parseFloat(result.lat);
                    const lng = parseFloat(result.lon);

                    if (searchLocationMarker) map.removeLayer(searchLocationMarker);
                    if (searchRadiusCircle) map.removeLayer(searchRadiusCircle);

                    searchLocationMarker = L.circleMarker([lat, lng], {
                        radius: 15,
                        fillColor: '#34d399',
                        fillOpacity: 0.3,
                        color: '#34d399',
                        weight: 2,
                        opacity: 0.8
                    }).addTo(map);

                    searchLocationMarker.bindPopup(`<b style="color:#1e293b">${result.display_name}</b>`).openPopup();

                    const radius = parseInt(document.getElementById('radius-select').value);
                    searchRadiusCircle = L.circle([lat, lng], {
                        radius: radius * 1000,
                        fillColor: '#10b981',
                        fillOpacity: 0.1,
                        color: '#10b981',
                        weight: 2,
                        opacity: 0.4
                    }).addTo(map);

                    map.setView([lat, lng], 10);
                    loadDisastersWithLocation(lat, lng, radius);
                } else {
                    const fallbackResponse = await fetch(`/api/geocode?location=${encodeURIComponent(location)}`);
                    if (fallbackResponse.ok) {
                        const fallbackData = await fallbackResponse.json();
                        if (fallbackData.location) {
                            const { lat, lng } = fallbackData.location;
                            handleSearchResult(lat, lng, fallbackData.location.formatted_address || location);
                        } else throw new Error('Location not found');
                    } else throw new Error('Location not found');
                }
            } catch (error) {
                console.error('Search error:', error);
                document.getElementById('search-error').classList.remove('hidden');
            } finally {
                hideLoading();
            }
        }

        function handleSearchResult(lat, lng, displayName) {
            if (searchLocationMarker) map.removeLayer(searchLocationMarker);
            if (searchRadiusCircle) map.removeLayer(searchRadiusCircle);

            searchLocationMarker = L.circleMarker([lat, lng], {
                radius: 15,
                fillColor: '#34d399',
                fillOpacity: 0.3,
                color: '#34d399',
                weight: 2,
                opacity: 0.8
            }).addTo(map);

            searchLocationMarker.bindPopup(`<b style="color:#1e293b">${displayName}</b>`).openPopup();

            const radius = parseInt(document.getElementById('radius-select').value);
            searchRadiusCircle = L.circle([lat, lng], {
                radius: radius * 1000,
                fillColor: '#10b981',
                fillOpacity: 0.1,
                color: '#10b981',
                weight: 2,
                opacity: 0.4
            }).addTo(map);

            map.setView([lat, lng], 10);
            loadDisastersWithLocation(lat, lng, radius);
        }

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

        function updateLastUpdated() {
            const now = new Date();
            document.getElementById('last-updated-time').textContent = now.toLocaleString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function startAutoRefresh() {
            if (autoRefreshInterval) clearInterval(autoRefreshInterval);
            autoRefreshInterval = setInterval(() => {
                if (document.getElementById('auto-refresh').checked) loadDisasters();
            }, 5 * 60 * 1000);
        }

        function showLoading() {
            document.getElementById('map-loading').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('map-loading').classList.add('hidden');
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('input[name="disaster_types"], input[name="severity"], #date-from, #date-to').forEach(el => {
                el.addEventListener('change', loadDisasters);
            });

            document.getElementById('search-btn').addEventListener('click', searchLocation);
            document.getElementById('location-search').addEventListener('keypress', (e) => {
                if (e.key === 'Enter') searchLocation();
            });

            document.getElementById('radius-select').addEventListener('change', () => {
                if (document.getElementById('location-search').value.trim()) searchLocation();
            });

            document.getElementById('select-all-types').addEventListener('click', () => {
                document.querySelectorAll('input[name="disaster_types"]').forEach(cb => cb.checked = true);
                loadDisasters();
            });

            document.getElementById('deselect-all-types').addEventListener('click', () => {
                document.querySelectorAll('input[name="disaster_types"]').forEach(cb => cb.checked = false);
                loadDisasters();
            });

            document.getElementById('reset-filters').addEventListener('click', () => {
                document.querySelectorAll('input[name="disaster_types"], input[name="severity"]').forEach(cb => cb.checked = true);
                document.getElementById('date-from').value = '';
                document.getElementById('date-to').value = '';
                document.getElementById('location-search').value = '';
                document.getElementById('radius-select').value = '50';

                if (searchLocationMarker) { map.removeLayer(searchLocationMarker); searchLocationMarker = null; }
                if (searchRadiusCircle) { map.removeLayer(searchRadiusCircle); searchRadiusCircle = null; }

                map.setView([INDONESIA_CENTER.lat, INDONESIA_CENTER.lng], 5);
                loadDisasters();
            });

            document.getElementById('auto-refresh').addEventListener('change', (e) => {
                if (e.target.checked) startAutoRefresh();
            });
        });
    </script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
    @endpush

    {{-- Scoped styles --}}
    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(16, 185, 129, 0.3);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(16, 185, 129, 0.5);
        }

        /* Leaflet DARK Custom Popup Styling */
        .custom-dark-popup .leaflet-popup-content-wrapper {
            background-color: #0f172a !important; /* slate-900 */
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 16px !important;
            padding: 0 !important;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5) !important;
        }
        .custom-dark-popup .leaflet-popup-tip {
            background: #0f172a !important;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            border-right: 1px solid rgba(255,255,255,0.1);
        }
        .custom-dark-popup .leaflet-popup-close-button {
            color: #94a3b8 !important;
            right: 10px !important;
            top: 10px !important;
        }
        .custom-dark-popup .leaflet-popup-close-button:hover {
            color: #f8fafc !important;
        }

        /* Marker Cluster Custom Styling */
        .marker-cluster-custom {
            background: transparent !important;
        }

        /* Dark Leaflet Controls */
        .leaflet-control-zoom a {
            border-radius: 12px !important;
            margin: 4px !important;
            width: 32px !important;
            height: 32px !important;
            line-height: 32px !important;
            font-size: 18px !important;
            background: rgba(15, 23, 42, 0.9) !important;
            color: #e2e8f0 !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3) !important;
            backdrop-filter: blur(8px);
        }
        .leaflet-control-zoom a:hover {
            background: rgba(16, 185, 129, 0.2) !important;
            color: #34d399 !important;
        }
        .leaflet-control-attribution {
            font-size: 10px !important;
            background: rgba(15, 23, 42, 0.8) !important;
            color: #94a3b8 !important;
            border-radius: 8px 0 0 0 !important;
            padding: 4px 8px !important;
            border-top: 1px solid rgba(255,255,255,0.1);
            border-left: 1px solid rgba(255,255,255,0.1);
            backdrop-filter: blur(4px);
        }
        .leaflet-control-attribution a {
            color: #34d399 !important;
        }
        
        /* White calendar icon for date inputs */
        .dark-calendar input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            opacity: 0.5;
            cursor: pointer;
        }
        .dark-calendar input[type="date"]::-webkit-calendar-picker-indicator:hover {
            opacity: 0.8;
        }
    </style>
</x-app-layout>
