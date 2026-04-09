<x-app-layout>
    @push('styles')
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @endpush

    {{-- Berita & Informasi — Modern Dark Dashboard Style (Matching Edukasi) --}}
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
                    <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl"></div>
                </div>

                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-6 sm:pt-8 sm:pb-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-4" data-aos="fade-right" data-aos-delay="200">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">Berita & Informasi</h1>
                                <p class="text-slate-400 text-sm mt-0.5">Informasi terkini seputar bencana dan mitigasi di Indonesia</p>
                            </div>
                        </div>

                        {{-- Search Bar --}}
                        <form action="{{ route('articles.index') }}" method="GET" class="flex gap-2" data-aos="fade-left" data-aos-delay="300">
                            <div class="relative flex-1 sm:w-72">
                                <input type="text" name="search" value="{{ $searchQuery ?? '' }}" placeholder="Cari artikel..."
                                       class="w-full bg-white/5 backdrop-blur-sm border border-white/10 rounded-full pl-10 pr-4 py-2.5 text-sm text-white placeholder-slate-500 focus:bg-white/10 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/30 focus:outline-none transition-all duration-200">
                                <svg class="w-4 h-4 text-slate-500 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                @if($searchQuery ?? false)
                                    <a href="{{ route('articles.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </a>
                                @endif
                            </div>
                            <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-green-500 text-white rounded-full hover:shadow-[0_0_20px_rgba(16,185,129,0.3)] hover:scale-[1.02] transition-all duration-200 active:scale-[0.98]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </section>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-8">

                {{-- Search Results Info --}}
                @if($searchQuery ?? false)
                    <div class="glass-dark rounded-2xl p-4 border border-emerald-500/20 shadow-soft" data-aos="fade-up" data-aos-delay="100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20">
                                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                <span class="text-sm text-slate-300 font-medium">Hasil pencarian untuk: "<span class="text-emerald-400 font-semibold">{{ $searchQuery }}</span>"</span>
                            </div>
                            <span class="text-xs text-emerald-400 font-bold bg-emerald-500/10 px-3 py-1 rounded-full border border-emerald-500/20">{{ $articles->total() ?? 0 }} hasil</span>
                        </div>
                    </div>
                @endif

                {{-- Category Pills --}}
                @if(($categories ?? collect())->count() > 0)
                    <div data-aos="fade-up" data-aos-delay="200">
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('articles.index') }}"
                               class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 {{ !($currentCategory ?? false) ? 'bg-gradient-to-r from-emerald-500 to-green-500 text-white shadow-soft hover:shadow-soft-lg' : 'glass border border-white/5 text-slate-300 shadow-soft hover:bg-white/10 hover:text-white' }} hover:scale-[1.02] active:scale-[0.98]">
                                📰 Semua
                            </a>
                            @foreach($categories as $category)
                                <a href="{{ route('articles.category', $category) }}"
                                   class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 capitalize {{ ($currentCategory ?? '') === $category ? 'bg-gradient-to-r from-emerald-500 to-green-500 text-white shadow-soft hover:shadow-soft-lg' : 'glass border border-white/5 text-slate-300 shadow-soft hover:bg-white/10 hover:text-white' }} hover:scale-[1.02] active:scale-[0.98]">
                                    {{ ucfirst($category) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="flex flex-col lg:flex-row gap-8">
                    {{-- Main Content --}}
                    <div class="flex-1">
                        @if(($articles ?? collect())->count() > 0)
                            <div class="grid gap-5 md:grid-cols-2">
                                @foreach($articles as $index => $article)
                                    <article class="glass-dark border border-white/5 rounded-2xl shadow-soft overflow-hidden group hover:border-emerald-500/20 transition-all duration-300" data-aos="zoom-in-up" data-aos-delay="{{ $index * 80 }}">
                                        @if($article->image)
                                            <div class="relative h-48 overflow-hidden border-b border-white/5">
                                                <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>
                                                @if($article->category)
                                                    <span class="absolute top-3 left-3 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-xs font-bold text-white border border-white/20 shadow-sm">
                                                        {{ ucfirst($article->category) }}
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <div class="h-36 bg-slate-800/50 relative flex items-center justify-center overflow-hidden border-b border-white/5">
                                                <svg class="w-10 h-10 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                                <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-xl"></div>
                                                @if($article->category)
                                                    <span class="absolute top-3 left-3 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-xs font-bold text-white border border-white/20">{{ ucfirst($article->category) }}</span>
                                                @endif
                                            </div>
                                        @endif

                                        <div class="p-5">
                                            <div class="flex items-center gap-3 text-xs text-slate-500 mb-3">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                    {{ $article->published_at->format('d M Y') }}
                                                </span>
                                                <span class="w-1 h-1 rounded-full bg-slate-600"></span>
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                    {{ number_format($article->view_count) }} dibaca
                                                </span>
                                            </div>

                                            <h2 class="text-base sm:text-lg font-bold text-white mb-2 group-hover:text-emerald-400 transition-colors line-clamp-2">
                                                <a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
                                            </h2>

                                            <p class="text-slate-400 text-sm line-clamp-2 mb-4">{{ $article->excerpt }}</p>

                                            <div class="flex items-center justify-between pt-4 border-t border-white/5">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center">
                                                        <span class="text-[10px] font-bold text-white">{{ substr($article->author?->name ?? 'A', 0, 1) }}</span>
                                                    </div>
                                                    <span class="text-xs text-slate-500 font-medium">{{ $article->author?->name ?? 'Admin' }}</span>
                                                </div>
                                                <a href="{{ route('articles.show', $article->slug) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition-colors group/link">
                                                    Baca
                                                    <svg class="w-3.5 h-3.5 group-hover/link:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                </a>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>

                            <div class="mt-8">{{ $articles->links() }}</div>
                        @else
                            {{-- Sample Articles --}}
                            @php
                                $sampleArticles = [
                                    ['title' => 'Cara Menyusun Rencana Evakuasi Keluarga yang Efektif', 'excerpt' => 'Rencana evakuasi yang baik dapat menyelamatkan nyawa saat bencana terjadi.', 'category' => 'Kesiapsiagaan', 'gradient' => 'from-emerald-500/20 to-green-500/10'],
                                    ['title' => 'Mengenal Tanda-Tanda Gempa Bumi dan Tindakan Pertama', 'excerpt' => 'Memahami tanda-tanda awal gempa bumi sangat penting untuk keselamatan.', 'category' => 'Respons Darurat', 'gradient' => 'from-amber-500/20 to-orange-500/10'],
                                    ['title' => 'Persiapan Pasca Bencana: Memulihkan Kehidupan', 'excerpt' => 'Proses pemulihan pasca bencana membutuhkan perencanaan matang.', 'category' => 'Pemulihan', 'gradient' => 'from-emerald-500/20 to-green-500/10'],
                                    ['title' => 'Teknologi Modern dalam Mitigasi Bencana', 'excerpt' => 'Dari sistem peringatan dini hingga aplikasi darurat.', 'category' => 'Mitigasi', 'gradient' => 'from-purple-500/20 to-indigo-500/10'],
                                    ['title' => 'Edukasi Bencana untuk Anak: Cara yang Tepat', 'excerpt' => 'Menyampaikan informasi bencana kepada anak membutuhkan pendekatan khusus.', 'category' => 'Edukasi', 'gradient' => 'from-rose-500/20 to-pink-500/10'],
                                    ['title' => 'Membangun Rumah Tahan Gempa: Panduan Dasar', 'excerpt' => 'Struktur bangunan yang tepat dapat mengurangi risiko kerusakan.', 'category' => 'Mitigasi', 'gradient' => 'from-teal-500/20 to-cyan-500/10'],
                                ];
                            @endphp
                            <div class="grid gap-5 md:grid-cols-2">
                                @foreach($sampleArticles as $index => $article)
                                    <article class="glass-dark border border-white/5 rounded-2xl shadow-soft overflow-hidden group hover:border-emerald-500/20 transition-all duration-300" data-aos="zoom-in-up" data-aos-delay="{{ $index * 100 }}">
                                        <div class="h-36 bg-gradient-to-br {{ $article['gradient'] }} relative flex items-center justify-center overflow-hidden border-b border-white/5">
                                            <svg class="w-10 h-10 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                            <span class="absolute top-3 left-3 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-xs font-bold text-white border border-white/20">{{ $article['category'] }}</span>
                                        </div>
                                        <div class="p-5">
                                            <div class="flex items-center gap-3 text-xs text-slate-500 mb-3">
                                                <span>{{ now()->format('d M Y') }}</span>
                                                <span class="w-1 h-1 rounded-full bg-slate-600"></span>
                                                <span>{{ rand(100, 1000) }} dibaca</span>
                                            </div>
                                            <h2 class="text-base font-bold text-white mb-2 group-hover:text-emerald-400 transition-colors line-clamp-2">{{ $article['title'] }}</h2>
                                            <p class="text-slate-400 text-sm line-clamp-2 mb-4">{{ $article['excerpt'] }}</p>
                                            <div class="flex items-center justify-between pt-4 border-t border-white/5">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center">
                                                        <span class="text-[10px] font-bold text-white">R</span>
                                                    </div>
                                                    <span class="text-xs text-slate-500 font-medium">ResQ Admin</span>
                                                </div>
                                                <a href="#" class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition-colors">
                                                    Baca <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                </a>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Sidebar --}}
                    <div class="w-full lg:w-80 flex-shrink-0 space-y-5">
                        {{-- Popular Articles --}}
                        @if(($popularArticles ?? collect())->count() > 0)
                            <div class="glass-dark rounded-2xl border border-white/5 shadow-soft p-5" data-aos="fade-left" data-aos-delay="300">
                                <h3 class="text-base font-bold text-white mb-4 flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20">
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                                    </div>
                                    Populer
                                </h3>
                                <div class="space-y-3">
                                    @foreach($popularArticles as $index => $popular)
                                        <a href="{{ route('articles.show', $popular->slug) }}" class="group flex gap-3 p-2 rounded-xl hover:bg-white/5 transition-all duration-200">
                                            <span class="flex-shrink-0 w-7 h-7 rounded-lg bg-gradient-to-br from-emerald-500 to-green-600 text-white text-xs font-bold flex items-center justify-center shadow-sm">
                                                {{ $index + 1 }}
                                            </span>
                                            <div class="min-w-0">
                                                <h4 class="text-sm font-medium text-slate-300 group-hover:text-emerald-400 transition-colors line-clamp-2">{{ $popular->title }}</h4>
                                                <span class="text-[10px] text-slate-500 mt-0.5">{{ number_format($popular->view_count) }} pembaca</span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Newsletter Card --}}
                        <div class="bg-gradient-to-br from-emerald-900/40 to-slate-800/40 rounded-2xl border border-emerald-500/20 shadow-lg p-5 text-white overflow-hidden relative" data-aos="fade-left" data-aos-delay="400">
                            <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
                            <div class="absolute -top-10 -left-10 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>
                            <div class="relative z-10">
                                <h3 class="text-base font-bold mb-2">Tetap Terinformasi</h3>
                                <p class="text-sm text-slate-400 mb-4 leading-relaxed">
                                    Dapatkan informasi terbaru tentang bencana dan tips mitigasi langsung di WhatsApp Anda.
                                </p>
                                <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-slate-900 rounded-full text-sm font-bold shadow-lg shadow-white/10 hover:bg-slate-100 hover:scale-[1.02] transition-all duration-300 active:scale-[0.98]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                    Atur Notifikasi
                                </a>
                            </div>
                        </div>

                        {{-- Quick Links to Guides --}}
                        <div class="glass-dark rounded-2xl border border-white/5 shadow-soft p-5" data-aos="fade-left" data-aos-delay="500">
                            <h3 class="text-base font-bold text-white mb-4 flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20">
                                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                                Panduan Edukasi
                            </h3>
                            <p class="text-sm text-slate-400 mb-4">Pelajari cara menghadapi bencana alam melalui panduan langkah demi langkah.</p>
                            <a href="{{ route('guides.index') }}" class="inline-flex items-center gap-2 w-full justify-center px-4 py-2.5 glass border border-emerald-500/20 text-emerald-400 rounded-xl text-sm font-semibold hover:bg-emerald-500/10 hover:text-emerald-300 transition-all duration-300 active:scale-[0.98]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                Lihat Panduan
                            </a>
                        </div>
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
            });
        });
    </script>
    @endpush

    <style>
        [x-cloak] { display: none !important; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    </style>

</x-app-layout>
