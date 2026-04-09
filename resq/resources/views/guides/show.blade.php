<x-app-layout>
    @push('styles')
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @endpush

    {{-- Guide Detail — Fluid Modern Dark Dashboard Style --}}
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

            <div class="relative overflow-hidden glass-dark border-b border-white/5 pb-12">
                <div class="absolute inset-0 overflow-hidden pointer-events-none">
                    <div class="absolute -top-16 -left-16 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-24 right-24 w-72 h-72 bg-emerald-700/10 rounded-full blur-3xl"></div>
                </div>

                {{-- Breadcrumb + Back --}}
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-5 relative z-10" data-aos="fade-down" data-aos-delay="100">
                    <nav class="flex items-center gap-2 text-sm mb-6">
                        <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-emerald-400 transition-colors">Dashboard</a>
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <a href="{{ route('guides.index') }}" class="text-slate-400 hover:text-emerald-400 transition-colors">Panduan</a>
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <span class="text-white font-medium capitalize">{{ $guide->category }}</span>
                    </nav>
                </div>

                {{-- Guide Header --}}
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-2 pb-4 relative z-10">
                    <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                        <a href="{{ route('guides.category', $guide->category) }}"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-full text-xs font-bold uppercase tracking-wider mb-5 hover:shadow-[0_0_20px_rgba(16,185,129,0.3)] hover:scale-[1.02] transition-all duration-300 active:scale-[0.98]">
                            <span class="capitalize">{{ $guide->category }}</span>
                        </a>

                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white leading-tight mb-4 text-balance">
                            {{ $guide->title }}
                        </h1>

                        <div class="flex items-center justify-center gap-4 text-sm text-slate-400">
                            @if($guide->video_url)
                                <span class="flex items-center gap-1.5 text-rose-400 font-medium bg-rose-500/10 px-3 py-1 rounded-full border border-rose-500/20">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                    </svg>
                                    Video tersedia
                                </span>
                            @endif
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                {{ count($steps) }} langkah
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Featured Image --}}
            @if($guide->image)
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 mb-10 relative z-20" data-aos="zoom-in" data-aos-delay="300">
                    <div class="rounded-2xl overflow-hidden shadow-2xl border border-white/10 relative group">
                        <img src="{{ asset('storage/' . $guide->image) }}" alt="{{ $guide->title }}" class="w-full h-64 md:h-96 object-cover group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent"></div>
                    </div>
                </div>
            @else
                <div class="h-8"></div>
            @endif

            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mb-12" data-aos="fade-up" data-aos-delay="400">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- Main Content --}}
                    <div class="lg:col-span-2 space-y-10">

                        {{-- Video Embed --}}
                        @if($guide->video_url)
                            <div class="aspect-video rounded-2xl overflow-hidden shadow-lg bg-slate-900 border border-white/5" data-aos="fade-right" data-aos-delay="500">
                                @if(str_contains($guide->video_url, 'youtube.com') || str_contains($guide->video_url, 'youtu.be'))
                                    @php
                                        $videoId = '';
                                        if (str_contains($guide->video_url, 'youtube.com/watch?v=')) {
                                            $videoId = explode('v=', $guide->video_url)[1] ?? '';
                                            $videoId = explode('&', $videoId)[0] ?? '';
                                        } elseif (str_contains($guide->video_url, 'youtu.be/')) {
                                            $videoId = explode('youtu.be/', $guide->video_url)[1] ?? '';
                                            $videoId = explode('?', $videoId)[0] ?? '';
                                        }
                                    @endphp
                                    @if($videoId)
                                        <iframe class="w-full h-full" src="https://www.youtube-nocookie.com/embed/{{ $videoId }}?rel=0&modestbranding=1" title="{{ $guide->title }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe>
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-white">
                                            <a href="{{ $guide->video_url }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 text-lg hover:text-emerald-400 transition-colors">
                                                <svg class="w-8 h-8 text-rose-500" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                                Tonton Video di YouTube
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-white">
                                        <a href="{{ $guide->video_url }}" target="_blank" class="flex items-center gap-2 text-lg hover:text-emerald-400 transition-colors">
                                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M10 12.5l8-5V3l-8 5v4.5zm0 0v4.5l8 5V17l-8-5z"/></svg>
                                            Tonton Video
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- Introduction HTML --}}
                        @if($guide->content)
                            <div class="prose prose-lg max-w-none prose-invert prose-emerald" data-aos="fade-up" data-aos-delay="600">
                                {!! $guide->content !!}
                            </div>
                        @endif

                        {{-- Steps --}}
                        @if(count($steps) > 0)
                            <div class="space-y-6">
                                <h2 class="text-xl sm:text-2xl font-bold text-white flex items-center gap-3" data-aos="fade-up">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20">
                                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                        </svg>
                                    </div>
                                    Langkah-langkah
                                </h2>

                                <div class="space-y-4">
                                    @foreach($steps as $index => $step)
                                        <div class="flex gap-4 p-5 glass-dark border border-white/5 rounded-2xl shadow-soft hover:bg-white/5 hover:border-emerald-500/30 transition-all duration-300" data-aos="fade-left" data-aos-delay="{{ $index * 100 }}">
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-green-600 text-white font-bold flex items-center justify-center text-sm shadow-md">
                                                    {{ $index + 1 }}
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                @if(is_array($step))
                                                    @if(isset($step['title']))
                                                        <h3 class="font-bold text-slate-200 mb-1.5 text-lg">{{ $step['title'] }}</h3>
                                                    @endif
                                                    @if(isset($step['description']))
                                                        <p class="text-[15px] text-slate-400 leading-relaxed">{{ $step['description'] }}</p>
                                                    @endif
                                                @else
                                                    <p class="text-[15px] text-slate-400 leading-relaxed">{{ $step }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- AI Assist CTA --}}
                        <div class="p-6 bg-gradient-to-br from-emerald-900/40 to-slate-800/40 rounded-2xl border border-emerald-500/20 shadow-lg relative overflow-hidden" data-aos="zoom-in" data-aos-delay="200">
                            <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
                            <div class="flex items-start gap-4 relative z-10">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center flex-shrink-0 shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-white mb-1.5">Punya Pertanyaan?</h3>
                                    <p class="text-sm text-slate-400 mb-4">
                                        Tanyakan ke AI Assist ResQ untuk informasi terperinci mengenai persiapan mitigasi ini.
                                    </p>
                                    <a href="{{ route('ai-assist.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-slate-900 rounded-full text-sm font-bold shadow-lg shadow-white/10 hover:bg-slate-100 hover:scale-[1.02] transition-all duration-300 active:scale-[0.98]">
                                        Tanya ke AI
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Sidebar --}}
                    <div class="space-y-6">
                        @if($otherCategories->count() > 0)
                            <div class="glass-dark rounded-2xl border border-white/5 shadow-soft p-5 sticky top-4" data-aos="fade-left" data-aos-delay="300">
                                <h3 class="text-base font-bold text-white mb-5 flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center border border-white/10">
                                        <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                    </div>
                                    Kategori Lain
                                </h3>
                                <div class="space-y-2">
                                    @foreach($otherCategories as $other)
                                        <a href="{{ route('guides.show', $other->slug) }}" class="group flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 border border-transparent hover:border-white/5 transition-all duration-300">
                                            <div class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center group-hover:bg-emerald-500/20 group-hover:text-emerald-400 transition-colors shadow-inner">
                                                <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-medium text-slate-300 group-hover:text-emerald-400 transition-colors truncate">{{ $other->title }}</h4>
                                                <p class="text-xs text-slate-500 capitalize">{{ $other->category }}</p>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <a href="{{ route('guides.index') }}" data-aos="fade-up" data-aos-delay="400" class="flex items-center justify-center gap-2 w-full px-4 py-3 glass-dark border border-white/5 rounded-xl shadow-soft text-sm font-medium text-slate-400 hover:text-emerald-400 hover:bg-white/5 hover:border-emerald-500/30 transition-all duration-300 active:scale-[0.98]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Kembali ke Panduan Utama
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- AOS Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                once: true,
                offset: 50,
                easing: 'ease-out-cubic'
            });
        });
    </script>
    @endpush

    <style>
        [x-cloak] { display: none !important; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .text-balance { text-wrap: balance; }
        
        /* Dark Theme Prose Overrides */
        .prose-invert h1, .prose-invert h2, .prose-invert h3, .prose-invert h4, .prose-invert h5, .prose-invert h6 {
            color: #f8fafc;
        }
        .prose-invert p, .prose-invert li {
            color: #94a3b8;
        }
        .prose-invert a {
            color: #34d399;
            text-decoration-color: #059669;
        }
    </style>
</x-app-layout>
