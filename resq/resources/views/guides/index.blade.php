<x-app-layout>
    @push('styles')
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @endpush

    {{-- Edukasi & Pelatihan — Fluid Modern Dark Dashboard Style --}}
    <div class="min-h-screen bg-slate-950 pb-24 lg:pb-8" x-data="{}" x-cloak>

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
                    
                    {{-- Dropdown Menu --}}
                    <div x-show="openOptions" 
                         x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="opacity-0 translate-y-1 scale-95" 
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
                         x-transition:leave="transition ease-in duration-150" 
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
                         x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                         class="absolute  left-1 right-1 w-auto mx-2 bg-slate-800 border border-white/10 rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.5)] overflow-hidden z-[100] py-1"
                         style="bottom: 100%; margin-bottom: 8px; display: none;">
                        
                        <a href="{{ route('profile.edit') }}" class="w-full text-left px-4 py-2.5 text-sm text-slate-300 hover:bg-white/5 hover:text-white transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Edit Profil
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="lg:ml-[72px]">

            {{-- HERO HEADER --}}
            <section class="relative overflow-hidden glass-dark border-b border-white/5" data-aos="fade-down" data-aos-duration="1000">
                <div class="absolute inset-0 overflow-hidden pointer-events-none">
                    <div class="absolute -top-16 -right-16 w-72 h-72 bg-emerald-500/10 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-green-500/10 rounded-full blur-3xl"></div>
                </div>

                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-6 sm:pt-8 sm:pb-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-4" data-aos="fade-right" data-aos-delay="200">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">Edukasi & Pelatihan</h1>
                                <p class="text-slate-400 text-sm mt-0.5">Pelajari cara menghadapi dan mitigasi berbagai jenis bencana alam</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2" data-aos="fade-left" data-aos-delay="300">
                            <a href="{{ route('ai-assist.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 glass-dark rounded-full shadow-sm border border-emerald-500/30 text-sm font-medium text-emerald-400 hover:bg-emerald-500/10 transition-all duration-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                                Tanya AI
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-10">

                {{-- Category Pills --}}
                @if(($categories ?? collect())->count() > 0)
                    <div data-aos="fade-up" data-aos-delay="400">
                        <div class="flex flex-wrap gap-2">
                            <button onclick="scrollToSection('all')"
                                    class="category-btn px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 bg-gradient-to-r from-emerald-500 to-green-500 text-white shadow-soft hover:shadow-soft-lg hover:scale-[1.02] active:scale-[0.98]"
                                    data-category="all">
                                Semua
                            </button>
                            @foreach($categories as $category)
                                <button onclick="scrollToSection('{{ $category }}')"
                                        class="category-btn px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 glass border border-white/5 text-slate-300 shadow-soft hover:bg-white/10 hover:text-white hover:scale-[1.02] active:scale-[0.98]"
                                        data-category="{{ $category }}">
                                    @if($category === 'earthquake') 🌍
                                    @elseif($category === 'flood') 🌊
                                    @elseif($category === 'landslide') ⛰️
                                    @elseif($category === 'tsunami') 🌊
                                    @elseif($category === 'fire') 🔥
                                    @elseif($category === 'volcano') 🌋
                                    @else 📚
                                    @endif
                                    {{ ucfirst($category) }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Guides by Category --}}
                @if(($guidesByCategory ?? collect())->count() > 0)
                    <div class="space-y-12" id="guides-container">
                        @foreach($guidesByCategory as $category => $guides)
                            <section id="section-{{ $category }}" class="category-section scroll-mt-8" data-aos="fade-up" data-aos-offset="100">
                                {{-- Category Header --}}
                                <div class="flex items-center justify-between mb-6 border-b border-white/5 pb-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-md
                                            @if($category === 'earthquake') bg-gradient-to-br from-orange-400 to-amber-500
                                            @elseif($category === 'flood') bg-gradient-to-br from-sky-400 to-blue-500
                                            @elseif($category === 'landslide') bg-gradient-to-br from-amber-600 to-yellow-700
                                            @elseif($category === 'tsunami') bg-gradient-to-br from-cyan-400 to-teal-500
                                            @elseif($category === 'fire') bg-gradient-to-br from-rose-400 to-red-500
                                            @elseif($category === 'volcano') bg-gradient-to-br from-red-500 to-rose-700
                                            @else bg-gradient-to-br from-emerald-500 to-green-600
                                            @endif">
                                            @if($category === 'earthquake')
                                                <span class="text-xl text-white">🌍</span>
                                            @elseif($category === 'flood')
                                                <span class="text-xl text-white">🌊</span>
                                            @elseif($category === 'landslide')
                                                <span class="text-xl text-white">⛰️</span>
                                            @elseif($category === 'tsunami')
                                                <span class="text-xl text-white">🌊</span>
                                            @elseif($category === 'fire')
                                                <span class="text-xl text-white">🔥</span>
                                            @elseif($category === 'volcano')
                                                <span class="text-xl text-white">🌋</span>
                                            @else
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <h2 class="text-xl sm:text-2xl font-bold text-white capitalize">{{ $category }}</h2>
                                            <p class="text-xs text-slate-400">{{ $guides->count() }} panduan</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('guides.category', $category) }}" class="text-sm text-emerald-400 hover:text-emerald-300 font-semibold transition-colors flex items-center gap-1 group">
                                        Lihat Semua
                                        <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>

                                {{-- Guide Cards --}}
                                <div class="grid gap-4 sm:gap-5 md:grid-cols-2 lg:grid-cols-3">
                                    @foreach($guides->take(6) as $index => $guide)
                                        <a href="{{ route('guides.show', $guide->slug) }}"
                                           class="tilt-card card-fluid glass-dark border border-white/5 rounded-2xl shadow-soft overflow-hidden group flex flex-col transition-all duration-300 transform"
                                           data-aos="zoom-in-up" data-aos-delay="{{ $index * 100 }}"
                                           data-tilt data-tilt-glare data-tilt-max-glare="0.2" data-tilt-scale="1.02">
                                            @if($guide->image)
                                                <div class="relative h-44 overflow-hidden border-b border-white/5">
                                                    <img src="{{ asset('storage/' . $guide->image) }}" alt="{{ $guide->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>
                                                </div>
                                            @else
                                                <div class="h-44 bg-slate-800/50 flex items-center justify-center relative overflow-hidden border-b border-white/5">
                                                    <svg class="w-12 h-12 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                    </svg>
                                                    <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-xl"></div>
                                                </div>
                                            @endif

                                            <div class="p-5 flex-1 flex flex-col relative z-20">
                                                <h3 class="font-bold text-white group-hover:text-emerald-400 transition-colors line-clamp-2 mb-2 text-sm sm:text-base">
                                                    {{ $guide->title }}
                                                </h3>

                                                @if($guide->steps)
                                                    <div class="flex items-center gap-2 text-xs text-slate-400 mb-4">
                                                        <div class="w-6 h-6 rounded-lg bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20 shadow-inner">
                                                            <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                                            </svg>
                                                        </div>
                                                        <span class="font-medium text-slate-300">{{ count($guide->getFormattedSteps()) }} langkah</span>
                                                    </div>
                                                @endif

                                                <div class="mt-auto flex items-center justify-between">
                                                    @if($guide->video_url)
                                                        <div class="flex items-center gap-1.5 text-xs text-rose-400 font-medium bg-rose-500/10 px-2.5 py-1 rounded-full border border-rose-500/20">
                                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                                            </svg>
                                                            Video
                                                        </div>
                                                    @endif
                                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-400 group-hover:text-emerald-300 group-hover:translate-x-1 transition-all ml-auto">
                                                        Baca
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </section>
                        @endforeach
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="text-center py-16 glass-dark rounded-2xl shadow-card border border-white/5" data-aos="fade-up">
                        <div class="w-20 h-20 mx-auto mb-4 bg-slate-800 rounded-2xl flex items-center justify-center border border-white/5 shadow-inner">
                            <svg class="w-10 h-10 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Belum Ada Panduan</h3>
                        <p class="text-slate-400">Panduan mitigasi bencana belum tersedia saat ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- AOS Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- VanillaTilt Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS
            AOS.init({
                duration: 800,
                once: true,
                offset: 50,
            });

            // Initialize VanillaTilt for desktop only (performance & UX)
            if (window.matchMedia("(min-width: 768px)").matches) {
                VanillaTilt.init(document.querySelectorAll(".tilt-card"), {
                    max: 5,
                    speed: 400,
                    glare: true,
                    "max-glare": 0.15,
                });
            }
        });

        function scrollToSection(category) {
            document.querySelectorAll('.category-btn').forEach(btn => {
                if (btn.dataset.category === category) {
                    btn.classList.remove('glass', 'text-slate-300', 'border-white/5');
                    btn.classList.add('bg-gradient-to-r', 'from-emerald-500', 'to-green-500', 'text-white', 'shadow-soft');
                } else {
                    btn.classList.remove('bg-gradient-to-r', 'from-emerald-500', 'to-green-500', 'text-white', 'shadow-soft');
                    btn.classList.add('glass', 'text-slate-300', 'border', 'border-white/5');
                }
            });

            if (category === 'all') {
                document.getElementById('guides-container').scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                const section = document.getElementById('section-' + category);
                if (section) {
                    section.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        }
    </script>
    @endpush

    <style>
        [x-cloak] { display: none !important; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
</x-app-layout>
