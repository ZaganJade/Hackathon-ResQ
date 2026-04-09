<x-app-layout>
    {{-- Fluid Modern Dashboard — "Hidup, pintar, dan siap bantu saat darurat" --}}

    <div class="min-h-screen bg-slate-950 pb-24 lg:pb-8" x-data="fluidDashboard()" x-cloak>

        {{-- ============================================
        1. DYNAMIC ALERT BAR
        Color shifts: green → yellow → red
        ============================================ --}}
        <div class="alert-pulse relative overflow-hidden" :class="{
                 'bg-gradient-to-r from-emerald-500 to-green-500': alertLevel === 'aman',
                 'bg-gradient-to-r from-amber-500 to-yellow-500': alertLevel === 'waspada',
                 'bg-gradient-to-r from-rose-600 to-red-500': alertLevel === 'darurat'
             }">
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 flex items-center justify-center gap-2 relative z-10">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" :class="{
                              'bg-green-200': alertLevel === 'aman',
                              'bg-amber-200': alertLevel === 'waspada',
                              'bg-rose-200': alertLevel === 'darurat'
                          }"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5" :class="{
                              'bg-green-100': alertLevel === 'aman',
                              'bg-amber-100': alertLevel === 'waspada',
                              'bg-rose-100': alertLevel === 'darurat'
                          }"></span>
                </span>
                <p class="text-white text-xs sm:text-sm font-medium tracking-wide">
                    Status Wilayah:
                    <span class="font-bold" x-text="alertLabel"></span>
                </p>
            </div>
            {{-- Shimmer overlay --}}
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent animate-shimmer"
                style="background-size: 200% 100%;"></div>
        </div>

        {{-- ============================================
        2. ZONE STATUS WIDGET — Location-based disaster alert
        ============================================ --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <x-zone-status-widget />
        </div>

        {{-- ============================================
        3. DESKTOP SIDEBAR — Invisible → Visible
        ============================================ --}}
        <aside class="hidden lg:flex fixed top-0 left-0 h-full z-50 flex-col" x-data="{ sidebarHover: false }"
            @mouseenter="sidebarHover = true" @mouseleave="sidebarHover = false">
            {{-- Sidebar panel --}}
            <div class="h-full bg-slate-900/95 backdrop-blur-2xl border-r border-white/5 shadow-soft-lg flex flex-col py-6 sidebar-spring overflow-hidden"
                :class="sidebarHover ? 'w-64' : 'w-[72px]'">
                {{-- Logo --}}
                <div class="flex items-center gap-3 px-4 mb-8 overflow-hidden">
                    <div
                        class="w-10 h-10 rounded-2xl bg-gradient-to-br from-primary-500 to-emerald-400 flex items-center justify-center shrink-0 shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ $item['icon'] }}"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium transition-opacity duration-300"
                                :class="sidebarHover ? 'opacity-100' : 'opacity-0'">{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>

                {{-- User section with Dropdown (Click to Logout) --}}
                <div class="px-3 mt-auto relative" x-data="{ userMenuOpen: false }">
                    {{-- Popup Menu (Appears upwards) --}}
                    <div x-show="userMenuOpen" 
                         @click.away="userMenuOpen = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2"
                         class="absolute left-0 w-full px-3 z-[100]"
                         style="bottom: 100%; margin-bottom: 8px; display: none;">
                        <div class="bg-slate-800 border border-slate-700/50 rounded-xl shadow-lg ring-1 ring-black/5 overflow-hidden py-1 relative z-[101]">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-rose-400 hover:bg-slate-700/50 hover:text-rose-300 transition-all text-left font-medium">
                                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    <span class="whitespace-nowrap transition-opacity duration-300" :class="sidebarHover ? 'opacity-100' : 'opacity-0'">Keluar (Logout)</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- User Profile Button --}}
                    <button @click="userMenuOpen = !userMenuOpen" class="relative z-[90] w-full flex items-center gap-3 px-3 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 transition-colors duration-200 overflow-hidden text-left cursor-pointer border border-transparent hover:border-white/10">
                        <div
                            class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center shrink-0 ring-2 ring-white/10">
                            <span
                                class="text-white font-bold text-xs">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                        </div>
                        <div class="min-w-0 transition-opacity duration-300"
                            :class="sidebarHover ? 'opacity-100' : 'opacity-0'">
                            <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </button>
                </div>
            </div>
        </aside>

        {{-- ============================================
        MAIN CONTENT AREA
        ============================================ --}}
        <div class="lg:ml-[72px]">

            {{-- ============================================
            3. HERO SECTION — Smart Calm Intelligence
            ============================================ --}}
            <section class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-900 to-slate-950">
                {{-- Background decorative elements --}}
                <div class="absolute inset-0 overflow-hidden">
                    <div class="absolute -top-20 -right-20 w-96 h-96 bg-emerald-500/5 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-primary-500/5 rounded-full blur-3xl"></div>
                    {{-- Subtle topographic pattern --}}
                    <svg class="absolute inset-0 w-full h-full opacity-[0.02]" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="topo" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse">
                                <circle cx="50" cy="50" r="40" fill="none" stroke="currentColor" stroke-width="0.5" />
                                <circle cx="50" cy="50" r="30" fill="none" stroke="currentColor" stroke-width="0.5" />
                                <circle cx="50" cy="50" r="20" fill="none" stroke="currentColor" stroke-width="0.5" />
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#topo)" class="text-emerald-400" />
                    </svg>
                </div>

                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-10 sm:pt-10 sm:pb-14">
                    {{-- Greeting --}}
                    <div class="animate-fade-up">
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white leading-tight">
                            Halo, {{ Auth::user()->name }} 👋
                        </h1>
                        <p class="text-slate-400 mt-2 text-sm sm:text-base max-w-md">
                            Pantau kondisi & tetap siap siaga hari ini
                        </p>
                    </div>

                    {{-- AI Assist Search Bar — CENTERPIECE --}}
                    <div class="mt-6 sm:mt-8 animate-fade-up stagger-2">
                        <form action="{{ route('ai-assist.index') }}" method="GET" class="relative max-w-2xl">
                            <div
                                class="search-glow rounded-full bg-white/[0.06] backdrop-blur-xl shadow-card border border-white/10 flex items-center transition-all duration-300 hover:bg-white/[0.09]">
                                {{-- AI sparkle icon --}}
                                <div class="pl-5 pr-2 flex items-center">
                                    <div
                                        class="w-9 h-9 rounded-full bg-gradient-to-br from-primary-400 to-emerald-500 flex items-center justify-center shadow-sm">
                                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                        </svg>
                                    </div>
                                </div>
                                <input type="text" name="q" placeholder="Tanya AI: Bagaimana cara evakuasi saat gempa?"
                                    class="flex-1 bg-transparent border-0 py-4 px-3 text-white placeholder-slate-500 text-sm sm:text-base focus:ring-0 focus:outline-none" />
                                <button type="submit"
                                    class="mr-3 px-5 py-2.5 bg-gradient-to-r from-primary-500 to-emerald-500 text-white text-sm font-semibold rounded-full hover:shadow-lg hover:scale-[1.02] transition-all duration-200 active:scale-[0.98]">
                                    Tanya
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Rounded bottom --}}
                <div
                    class="absolute bottom-0 left-0 right-0 h-6 bg-gradient-to-b from-transparent to-slate-950 rounded-t-3xl">
                </div>
            </section>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10 mt-2">

                {{-- ============================================
                4. QUICK ACTIONS — Card Grid
                ============================================ --}}
                <section class="animate-fade-up stagger-2">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 stagger-children">
                        {{-- AI Assistant --}}
                        <a href="{{ route('ai-assist.index') }}"
                            class="quick-action group card-fluid bg-white/[0.04] border border-white/[0.06] rounded-2xl p-5 shadow-card text-center cursor-pointer hover:bg-white/[0.08] transition-all duration-300">
                            <div
                                class="w-12 h-12 mx-auto rounded-2xl bg-gradient-to-br from-primary-100 to-emerald-100 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                            </div>
                            <h3 class="font-semibold text-white text-sm">AI Assistant</h3>
                            <p class="text-xs text-slate-500 mt-1">Tanya apa saja</p>
                        </a>

                        {{-- Peta Bencana --}}
                        <a href="{{ route('map.index') }}"
                            class="quick-action group card-fluid bg-white/[0.04] border border-white/[0.06] rounded-2xl p-5 shadow-card text-center cursor-pointer hover:bg-white/[0.08] transition-all duration-300">
                            <div
                                class="w-12 h-12 mx-auto rounded-2xl bg-gradient-to-br from-sky-100 to-blue-100 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                </svg>
                            </div>
                            <h3 class="font-semibold text-white text-sm">Peta Bencana</h3>
                            <p class="text-xs text-slate-500 mt-1">Pantau lokasi</p>
                        </a>

                        {{-- Edukasi --}}
                        <a href="{{ route('guides.index') }}"
                            class="quick-action group card-fluid bg-white/[0.04] border border-white/[0.06] rounded-2xl p-5 shadow-card text-center cursor-pointer hover:bg-white/[0.08] transition-all duration-300">
                            <div
                                class="w-12 h-12 mx-auto rounded-2xl bg-gradient-to-br from-amber-100 to-yellow-100 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <h3 class="font-semibold text-white text-sm">Edukasi</h3>
                            <p class="text-xs text-slate-500 mt-1">Belajar mitigasi</p>
                        </a>

                        {{-- Notifikasi --}}
                        <a href="{{ route('articles.index') }}"
                            class="quick-action group card-fluid bg-white/[0.04] border border-white/[0.06] rounded-2xl p-5 shadow-card text-center cursor-pointer hover:bg-white/[0.08] transition-all duration-300">
                            <div
                                class="w-12 h-12 mx-auto rounded-2xl bg-gradient-to-br from-rose-100 to-pink-100 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </div>
                            <h3 class="font-semibold text-white text-sm">Berita & Info</h3>
                            <p class="text-xs text-slate-500 mt-1">Update terkini</p>
                        </a>
                    </div>
                </section>

                {{-- ============================================
                5. DISASTER CONTENT — Modern Horizontal Cards
                Desktop: 2-col, Tablet/Mobile: 1-col
                ============================================ --}}
                <section class="animate-fade-up stagger-3">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h2 class="font-bold text-white text-lg sm:text-xl">Panduan Bencana</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Pelajari cara menghadapi bencana alam</p>
                        </div>
                        <a href="{{ route('guides.index') }}"
                            class="text-sm text-emerald-400 hover:text-emerald-300 font-semibold transition-colors flex items-center gap-1 group">
                            Lihat semua
                            <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5 stagger-children">
                        {{-- Card: Tsunami --}}
                        <div
                            class="card-fluid bg-white/[0.04] border border-white/[0.06] rounded-2xl shadow-card overflow-hidden group hover:bg-white/[0.07] transition-all duration-300">
                            <div class="flex flex-col sm:flex-row">
                                <div class="sm:w-2/5 relative overflow-hidden">
                                    <div class="aspect-[3/2] sm:aspect-auto sm:h-full">
                                        <img src="{{ asset('images/dashboard/tsunami.png') }}" alt="Tsunami"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                    </div>
                                    <div class="absolute top-3 left-3">
                                        <span
                                            class="bg-secondary-600/90 backdrop-blur-sm text-white text-[10px] font-semibold px-3 py-1 rounded-full">Geologi</span>
                                    </div>
                                </div>
                                <div class="sm:w-3/5 p-5 sm:p-6 flex flex-col justify-center">
                                    <h3
                                        class="font-bold text-white text-base sm:text-lg group-hover:text-emerald-400 transition-colors">
                                        Tsunami</h3>
                                    <p class="text-sm text-slate-400 leading-relaxed mt-2 line-clamp-2">Tsunami umumnya
                                        disebabkan oleh gempa bumi bawah laut. Pelajari cara evakuasi dan tanda-tanda
                                        alam sebelum tsunami.</p>
                                    <p class="text-xs text-slate-500 mt-2">Diperbarui: 6 Juni 2025</p>
                                    <a href="{{ route('guides.index') }}"
                                        class="inline-flex items-center gap-1.5 text-sm text-emerald-400 font-semibold mt-3 hover:text-emerald-300 transition-colors group/link">
                                        Lihat Selengkapnya
                                        <svg class="w-4 h-4 group-hover/link:translate-x-0.5 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Card: Gempa Bumi --}}
                        <div
                            class="card-fluid bg-white/[0.04] border border-white/[0.06] rounded-2xl shadow-card overflow-hidden group hover:bg-white/[0.07] transition-all duration-300">
                            <div class="flex flex-col sm:flex-row">
                                <div class="sm:w-2/5 relative overflow-hidden">
                                    <div class="aspect-[3/2] sm:aspect-auto sm:h-full">
                                        <img src="{{ asset('images/dashboard/earthquake.png') }}" alt="Gempa Bumi"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                    </div>
                                    <div class="absolute top-3 left-3">
                                        <span
                                            class="bg-amber-600/90 backdrop-blur-sm text-white text-[10px] font-semibold px-3 py-1 rounded-full">Tektonik</span>
                                    </div>
                                </div>
                                <div class="sm:w-3/5 p-5 sm:p-6 flex flex-col justify-center">
                                    <h3
                                        class="font-bold text-white text-base sm:text-lg group-hover:text-emerald-400 transition-colors">
                                        Gempa Bumi</h3>
                                    <p class="text-sm text-slate-400 leading-relaxed mt-2 line-clamp-2">Gempa bumi
                                        terjadi akibat pergerakan lempeng tektonik. Ketahui prosedur perlindungan diri
                                        saat gempa.</p>
                                    <p class="text-xs text-slate-500 mt-2">Diperbarui: 5 Juni 2025</p>
                                    <a href="{{ route('guides.index') }}"
                                        class="inline-flex items-center gap-1.5 text-sm text-primary-600 font-semibold mt-3 hover:text-primary-700 transition-colors group/link">
                                        Lihat Selengkapnya
                                        <svg class="w-4 h-4 group-hover/link:translate-x-0.5 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Card: Erupsi Gunung Api --}}
                        <div
                            class="card-fluid bg-white/[0.04] border border-white/[0.06] rounded-2xl shadow-card overflow-hidden group hover:bg-white/[0.07] transition-all duration-300">
                            <div class="flex flex-col sm:flex-row">
                                <div class="sm:w-2/5 relative overflow-hidden">
                                    <div class="aspect-[3/2] sm:aspect-auto sm:h-full">
                                        <img src="{{ asset('images/dashboard/volcano.png') }}" alt="Erupsi Gunung Api"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                    </div>
                                    <div class="absolute top-3 left-3">
                                        <span
                                            class="bg-rose-600/90 backdrop-blur-sm text-white text-[10px] font-semibold px-3 py-1 rounded-full">Vulkanik</span>
                                    </div>
                                </div>
                                <div class="sm:w-3/5 p-5 sm:p-6 flex flex-col justify-center">
                                    <h3
                                        class="font-bold text-white text-base sm:text-lg group-hover:text-emerald-400 transition-colors">
                                        Erupsi Gunung Api</h3>
                                    <p class="text-sm text-slate-400 leading-relaxed mt-2 line-clamp-2">Erupsi gunung
                                        api menghasilkan lava, abu vulkanik, dan awan panas. Ketahui radius aman dan
                                        jalur evakuasi.</p>
                                    <p class="text-xs text-slate-500 mt-2">Diperbarui: 4 Juni 2025</p>
                                    <a href="{{ route('guides.index') }}"
                                        class="inline-flex items-center gap-1.5 text-sm text-primary-600 font-semibold mt-3 hover:text-primary-700 transition-colors group/link">
                                        Lihat Selengkapnya
                                        <svg class="w-4 h-4 group-hover/link:translate-x-0.5 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Card: Banjir --}}
                        <div
                            class="card-fluid bg-white/[0.04] border border-white/[0.06] rounded-2xl shadow-card overflow-hidden group hover:bg-white/[0.07] transition-all duration-300">
                            <div class="flex flex-col sm:flex-row">
                                <div class="sm:w-2/5 relative overflow-hidden">
                                    <div class="aspect-[3/2] sm:aspect-auto sm:h-full">
                                        <img src="{{ asset('images/dashboard/flood.png') }}" alt="Banjir Bandang"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                    </div>
                                    <div class="absolute top-3 left-3">
                                        <span
                                            class="bg-sky-600/90 backdrop-blur-sm text-white text-[10px] font-semibold px-3 py-1 rounded-full">Hidrometeorologi</span>
                                    </div>
                                </div>
                                <div class="sm:w-3/5 p-5 sm:p-6 flex flex-col justify-center">
                                    <h3
                                        class="font-bold text-white text-base sm:text-lg group-hover:text-emerald-400 transition-colors">
                                        Banjir Bandang</h3>
                                    <p class="text-sm text-slate-400 leading-relaxed mt-2 line-clamp-2">Banjir bandang
                                        datang dengan cepat tanpa peringatan. Pelajari langkah evakuasi dan persiapan
                                        darurat.</p>
                                    <p class="text-xs text-slate-500 mt-2">Diperbarui: 3 Juni 2025</p>
                                    <a href="{{ route('guides.index') }}"
                                        class="inline-flex items-center gap-1.5 text-sm text-primary-600 font-semibold mt-3 hover:text-primary-700 transition-colors group/link">
                                        Lihat Selengkapnya
                                        <svg class="w-4 h-4 group-hover/link:translate-x-0.5 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- ============================================
                6. TWO-COLUMN: TIPS CAROUSEL + RECENT ACTIVITY
                ============================================ --}}
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 animate-fade-up stagger-4">

                    {{-- Tips Carousel — 3 col --}}
                    <section class="lg:col-span-3">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="font-bold text-white text-lg sm:text-xl">💡 Tips Mitigasi</h2>
                            <div class="flex gap-1.5">
                                <button @click="prevTip()"
                                    class="w-8 h-8 rounded-full bg-white/[0.06] border border-white/10 flex items-center justify-center text-slate-400 hover:text-emerald-400 hover:bg-white/[0.1] transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <button @click="nextTip()"
                                    class="w-8 h-8 rounded-full bg-white/[0.06] border border-white/10 flex items-center justify-center text-slate-400 hover:text-emerald-400 hover:bg-white/[0.1] transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="relative bg-white/[0.04] border border-white/[0.06] rounded-2xl shadow-card overflow-hidden"
                            style="min-height: 180px;">
                            <template x-for="(tip, index) in tips" :key="index">
                                <div x-show="currentTip === index" x-transition:enter="transition ease-out duration-500"
                                    x-transition:enter-start="opacity-0 translate-x-8"
                                    x-transition:enter-end="opacity-100 translate-x-0"
                                    x-transition:leave="transition ease-in duration-300"
                                    x-transition:leave-start="opacity-100 translate-x-0"
                                    x-transition:leave-end="opacity-0 -translate-x-8" class="p-6 sm:p-8">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 text-2xl"
                                            :class="tip.bgColor">
                                            <span x-text="tip.icon"></span>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-white text-base sm:text-lg" x-text="tip.title">
                                            </h3>
                                            <p class="text-sm text-slate-400 mt-2 leading-relaxed" x-text="tip.desc">
                                            </p>
                                        </div>
                                    </div>
                                    {{-- Progress dots --}}
                                    <div class="flex gap-1.5 mt-6 justify-center">
                                        <template x-for="(_, i) in tips" :key="'dot-' + i">
                                            <button @click="currentTip = i"
                                                class="h-1.5 rounded-full transition-all duration-300"
                                                :class="currentTip === i ? 'w-6 bg-emerald-500' : 'w-1.5 bg-slate-600 hover:bg-slate-500'">
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </section>

                    {{-- Recent Activity — 2 col --}}
                    <section class="lg:col-span-2">
                        <h2 class="font-bold text-white text-lg sm:text-xl mb-4">⏰ Aktivitas Terbaru</h2>
                        <div class="space-y-3">
                            {{-- Activity bubble 1 --}}
                            <div
                                class="bg-white/[0.04] border border-white/[0.06] rounded-2xl p-4 hover:bg-white/[0.07] transition-all duration-300 flex items-center gap-3 group cursor-pointer">
                                <div
                                    class="w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-white truncate">Chat AI: "Cara evakuasi gempa"
                                    </p>
                                    <p class="text-xs text-slate-500 mt-0.5">2 menit lalu</p>
                                </div>
                            </div>

                            {{-- Activity bubble 2 --}}
                            <div
                                class="bg-white/[0.04] border border-white/[0.06] rounded-2xl p-4 hover:bg-white/[0.07] transition-all duration-300 flex items-center gap-3 group cursor-pointer">
                                <div
                                    class="w-10 h-10 rounded-xl bg-sky-100 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-white truncate">Melihat peta: Gempa Sulawesi</p>
                                    <p class="text-xs text-slate-500 mt-0.5">15 menit lalu</p>
                                </div>
                            </div>

                            {{-- Activity bubble 3 --}}
                            <div
                                class="bg-white/[0.04] border border-white/[0.06] rounded-2xl p-4 hover:bg-white/[0.07] transition-all duration-300 flex items-center gap-3 group cursor-pointer">
                                <div
                                    class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-white truncate">Membaca: Panduan Tas Siaga</p>
                                    <p class="text-xs text-slate-500 mt-0.5">1 jam lalu</p>
                                </div>
                            </div>

                            {{-- Activity bubble 4 --}}
                            <div
                                class="bg-white/[0.04] border border-white/[0.06] rounded-2xl p-4 hover:bg-white/[0.07] transition-all duration-300 flex items-center gap-3 group cursor-pointer">
                                <div
                                    class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-white truncate">Artikel: Banjir Kalimantan</p>
                                    <p class="text-xs text-slate-500 mt-0.5">3 jam lalu</p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                {{-- ============================================
                7. BERITA TERKINI — Grid
                ============================================ --}}
                <section class="animate-fade-up stagger-5">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h2 class="font-bold text-white text-lg sm:text-xl">📰 Berita Terkini</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Informasi terbaru seputar bencana alam</p>
                        </div>
                        <a href="{{ route('articles.index') }}"
                            class="text-sm text-emerald-400 hover:text-emerald-300 font-semibold transition-colors flex items-center gap-1 group">
                            Lihat semua
                            <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 stagger-children">
                        {{-- News 1 --}}
                        <a href="{{ route('articles.index') }}" class="group">
                            <div
                                class="card-fluid bg-white/[0.04] border border-white/[0.06] rounded-2xl overflow-hidden hover:bg-white/[0.07] transition-all duration-300">
                                <div class="relative aspect-[4/3] overflow-hidden">
                                    <img src="{{ asset('images/dashboard/flood.png') }}" alt="Banjir Bandang"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/10 to-transparent">
                                    </div>
                                    <div class="absolute bottom-3 left-3 right-3">
                                        <span
                                            class="bg-white/20 backdrop-blur-sm text-white text-[9px] font-semibold px-2 py-0.5 rounded-full">BREAKING</span>
                                    </div>
                                </div>
                                <div class="p-3.5">
                                    <h3
                                        class="font-semibold text-white text-xs sm:text-sm leading-snug line-clamp-2 group-hover:text-emerald-400 transition-colors">
                                        Banjir Bandang Melanda Kalimantan</h3>
                                    <p class="text-[10px] sm:text-xs text-slate-500 mt-1.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Senin, 6 Juni 2025
                                    </p>
                                </div>
                            </div>
                        </a>

                        {{-- News 2 --}}
                        <a href="{{ route('articles.index') }}" class="group">
                            <div
                                class="card-fluid bg-white/[0.04] border border-white/[0.06] rounded-2xl overflow-hidden hover:bg-white/[0.07] transition-all duration-300">
                                <div class="relative aspect-[4/3] overflow-hidden">
                                    <img src="{{ asset('images/dashboard/landslide.png') }}" alt="Tanah Longsor"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/10 to-transparent">
                                    </div>
                                </div>
                                <div class="p-3.5">
                                    <h3
                                        class="font-semibold text-white text-xs sm:text-sm leading-snug line-clamp-2 group-hover:text-emerald-400 transition-colors">
                                        Angin Kencang dan Tanah Longsor di Jawa</h3>
                                    <p class="text-[10px] sm:text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Senin, 6 Juni 2025
                                    </p>
                                </div>
                            </div>
                        </a>

                        {{-- News 3 --}}
                        <a href="{{ route('articles.index') }}" class="group hidden lg:block">
                            <div
                                class="card-fluid bg-white/[0.04] border border-white/[0.06] rounded-2xl overflow-hidden hover:bg-white/[0.07] transition-all duration-300">
                                <div class="relative aspect-[4/3] overflow-hidden">
                                    <img src="{{ asset('images/dashboard/earthquake.png') }}" alt="Gempa Bumi"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/10 to-transparent">
                                    </div>
                                </div>
                                <div class="p-3.5">
                                    <h3
                                        class="font-semibold text-white text-xs sm:text-sm leading-snug line-clamp-2 group-hover:text-emerald-400 transition-colors">
                                        Gempa M5.2 Guncang Sulawesi Utara</h3>
                                    <p class="text-[10px] sm:text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Minggu, 5 Juni 2025
                                    </p>
                                </div>
                            </div>
                        </a>

                        {{-- News 4 --}}
                        <a href="{{ route('articles.index') }}" class="group hidden lg:block">
                            <div
                                class="card-fluid bg-white/[0.04] border border-white/[0.06] rounded-2xl overflow-hidden hover:bg-white/[0.07] transition-all duration-300">
                                <div class="relative aspect-[4/3] overflow-hidden">
                                    <img src="{{ asset('images/dashboard/volcano.png') }}" alt="Erupsi Gunung"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/10 to-transparent">
                                    </div>
                                </div>
                                <div class="p-3.5">
                                    <h3
                                        class="font-semibold text-white text-xs sm:text-sm leading-snug line-clamp-2 group-hover:text-emerald-400 transition-colors">
                                        Aktivitas Gunung Merapi Meningkat</h3>
                                    <p class="text-[10px] sm:text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Sabtu, 4 Juni 2025
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                </section>

                {{-- ============================================
                8. NOMOR DARURAT — Mobile only
                ============================================ --}}
                <section class="animate-fade-up stagger-6 lg:hidden">
                    <h2 class="font-bold text-white text-lg mb-4">🚨 Nomor Darurat</h2>
                    <div class="grid grid-cols-2 gap-3">
                        <div
                            class="bg-white/[0.04] border border-white/[0.06] rounded-2xl p-4 flex items-center gap-3 hover:bg-white/[0.07] transition-all duration-300">
                            <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center shrink-0">
                                <span class="text-lg">🚨</span>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">BNPB</p>
                                <p class="font-bold text-white">117</p>
                            </div>
                        </div>
                        <div
                            class="bg-white/[0.04] border border-white/[0.06] rounded-2xl p-4 flex items-center gap-3 hover:bg-white/[0.07] transition-all duration-300">
                            <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center shrink-0">
                                <span class="text-lg">🚑</span>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Ambulans</p>
                                <p class="font-bold text-white">118</p>
                            </div>
                        </div>
                        <div
                            class="bg-white/[0.04] border border-white/[0.06] rounded-2xl p-4 flex items-center gap-3 hover:bg-white/[0.07] transition-all duration-300">
                            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center shrink-0">
                                <span class="text-lg">⛑️</span>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Basarnas</p>
                                <p class="font-bold text-white">115</p>
                            </div>
                        </div>
                        <div
                            class="bg-white/[0.04] border border-white/[0.06] rounded-2xl p-4 flex items-center gap-3 hover:bg-white/[0.07] transition-all duration-300">
                            <div class="w-10 h-10 rounded-xl bg-sky-100 flex items-center justify-center shrink-0">
                                <span class="text-lg">👮</span>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Polisi</p>
                                <p class="font-bold text-white">110</p>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>

    {{-- Alpine.js: Fluid Dashboard Controller --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('fluidDashboard', () => ({
                // Alert system
                alertLevel: 'aman', // 'aman', 'waspada', 'darurat'

                get alertLabel() {
                    const labels = { aman: 'Aman (Hijau)', waspada: 'Waspada (Kuning)', darurat: 'Darurat (Merah)' };
                    return labels[this.alertLevel] || 'Aman (Hijau)';
                },

                // Tips carousel
                currentTip: 0,
                tipInterval: null,
                tips: [
                    {
                        icon: '🏃',
                        title: 'Kenali Jalur Evakuasi',
                        desc: 'Pastikan Anda mengetahui jalur evakuasi di lingkungan rumah, kantor, dan sekolah. Latih keluarga untuk mengikuti jalur ini secara rutin.',
                        bgColor: 'bg-primary-100'
                    },
                    {
                        icon: '🎒',
                        title: 'Siapkan Tas Siaga',
                        desc: 'Sediakan tas siaga berisi air minum, makanan tahan lama, obat-obatan, senter, dan dokumen penting. Periksa isinya setiap 3 bulan.',
                        bgColor: 'bg-amber-100'
                    },
                    {
                        icon: '📱',
                        title: 'Simpan Nomor Darurat',
                        desc: 'Catat nomor BNPB (117), Ambulans (118), Basarnas (115), dan Polisi (110). Simpan juga di dinding rumah yang mudah terlihat.',
                        bgColor: 'bg-sky-100'
                    },
                    {
                        icon: '🏠',
                        title: 'Periksa Struktur Bangunan',
                        desc: 'Pastikan rumah Anda memenuhi standar bangunan tahan gempa. Periksa fondasi, dinding, dan atap secara berkala.',
                        bgColor: 'bg-rose-100'
                    },
                    {
                        icon: '🤝',
                        title: 'Bangun Komunikasi Komunitas',
                        desc: 'Koordinasi dengan RT/RW setempat untuk rencana evakuasi bersama. Tetangga adalah responder pertama saat bencana terjadi.',
                        bgColor: 'bg-purple-100'
                    }
                ],

                init() {
                    // Auto-slide tips
                    this.tipInterval = setInterval(() => {
                        this.nextTip();
                    }, 6000);
                },

                nextTip() {
                    this.currentTip = (this.currentTip + 1) % this.tips.length;
                    this.resetTipInterval();
                },

                prevTip() {
                    this.currentTip = (this.currentTip - 1 + this.tips.length) % this.tips.length;
                    this.resetTipInterval();
                },

                resetTipInterval() {
                    clearInterval(this.tipInterval);
                    this.tipInterval = setInterval(() => {
                        this.nextTip();
                    }, 6000);
                }
            }));
        });
    </script>

    {{-- Scoped styles --}}
    <style>
        [x-cloak] {
            display: none !important;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-app-layout>