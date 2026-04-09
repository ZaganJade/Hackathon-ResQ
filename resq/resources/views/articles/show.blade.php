<x-app-layout>
    {{-- Article Detail — Fluid Modern Dashboard Style --}}

    <div class="min-h-screen bg-gradient-to-b from-green-50/60 via-white to-slate-50 pb-24 lg:pb-8" x-data="{}" x-cloak>

        {{-- DESKTOP SIDEBAR --}}
        <aside class="hidden lg:flex fixed top-0 left-0 h-full z-50 flex-col"
               x-data="{ sidebarHover: false }" @mouseenter="sidebarHover = true" @mouseleave="sidebarHover = false">
            <div class="h-full bg-slate-900/95 backdrop-blur-2xl border-r border-white/5 shadow-soft-lg flex flex-col py-6 sidebar-spring overflow-hidden" :class="sidebarHover ? 'w-64' : 'w-[72px]'">
                <div class="flex items-center gap-3 px-4 mb-8 overflow-hidden">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-primary-500 to-emerald-400 flex items-center justify-center shrink-0 shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="font-bold text-xl text-white whitespace-nowrap transition-opacity duration-300" :class="sidebarHover ? 'opacity-100' : 'opacity-0'">ResQ</span>
                </div>
                <nav class="flex-1 flex flex-col gap-1 px-3">
                    @php $menuItems = [
                        ['route'=>'dashboard','label'=>'Beranda','icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6','active'=>'dashboard'],
                        ['route'=>'map.index','label'=>'Peta Interaktif','icon'=>'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7','active'=>'map.*'],
                        ['route'=>'guides.index','label'=>'Edukasi & Pelatihan','icon'=>'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253','active'=>'guides.*'],
                        ['route'=>'articles.index','label'=>'Berita & Info','icon'=>'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z','active'=>'articles.*'],
                        ['route'=>'chat-history.index','label'=>'Riwayat Chat','icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z','active'=>'chat-history.*'],
                        ['route'=>'ai-assist.index','label'=>'AI Assistant','icon'=>'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z','active'=>'ai-assist.*'],
                        ['route'=>'profile.edit','label'=>'Profil','icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z','active'=>'profile.*'],
                    ]; @endphp
                    @foreach($menuItems as $item)
                        <a href="{{ route($item['route']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group whitespace-nowrap {{ request()->routeIs($item['active']) ? 'menu-active' : 'text-slate-400 hover:bg-white/5 hover:text-emerald-400' }}">
                            <div class="w-7 h-7 flex items-center justify-center shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path></svg></div>
                            <span class="text-sm font-medium transition-opacity duration-300" :class="sidebarHover ? 'opacity-100' : 'opacity-0'">{{ $item['label'] }}</span>
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
            {{-- Breadcrumb --}}
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-5">
                <nav class="flex items-center gap-2 text-sm animate-fade-up">
                    <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-primary-600 transition-colors">Dashboard</a>
                    <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <a href="{{ route('articles.index') }}" class="text-slate-400 hover:text-primary-600 transition-colors">Artikel</a>
                    <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-slate-600 font-medium truncate max-w-[200px]">{{ $article->title ?? 'Artikel' }}</span>
                </nav>
            </div>

            <article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                {{-- Article Header --}}
                <div class="text-center mb-8 animate-fade-up stagger-2">
                    @if($article->category ?? false)
                        <a href="{{ route('articles.category', $article->category) }}" class="inline-flex items-center gap-1 px-4 py-2 bg-gradient-to-r from-primary-500 to-emerald-500 text-white rounded-full text-xs font-bold uppercase tracking-wider mb-4 hover:shadow-lg hover:scale-[1.02] transition-all duration-300 active:scale-[0.98]">
                            {{ ucfirst($article->category) }}
                        </a>
                    @endif

                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-800 leading-tight mb-6 text-balance">
                        {{ $article->title ?? 'Judul Artikel' }}
                    </h1>

                    <div class="flex flex-wrap items-center justify-center gap-4 text-sm text-slate-400">
                        <div class="flex items-center gap-2">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary-400 to-emerald-500 flex items-center justify-center ring-2 ring-white shadow-sm">
                                <span class="text-sm font-bold text-white">{{ substr($article->author?->name ?? 'R', 0, 1) }}</span>
                            </div>
                            <div class="text-left">
                                <p class="font-semibold text-slate-700 text-sm">{{ $article->author?->name ?? 'ResQ Admin' }}</p>
                                <p class="text-[10px] text-slate-400">Penulis</p>
                            </div>
                        </div>
                        <span class="hidden sm:block w-px h-6 bg-slate-200"></span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ ($article->published_at ?? now())->format('d M Y') }}
                        </span>
                        <span class="hidden sm:block w-px h-6 bg-slate-200"></span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            {{ number_format($article->view_count ?? 0) }} dibaca
                        </span>
                    </div>
                </div>

                {{-- Featured Image --}}
                @if($article->image ?? false)
                    <div class="mb-8 rounded-2xl overflow-hidden shadow-soft-lg animate-fade-up stagger-3">
                        <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-full h-64 md:h-96 object-cover">
                    </div>
                @else
                    <div class="mb-8 h-48 md:h-64 bg-gradient-to-br from-sky-100 via-primary-50 to-emerald-100 rounded-2xl shadow-soft-lg flex items-center justify-center animate-fade-up stagger-3">
                        <svg class="w-16 h-16 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 space-y-8">
                        {{-- Content --}}
                        <div class="prose prose-lg max-w-none prose-slate prose-a:text-primary-600 prose-a:hover:text-primary-700 animate-fade-up stagger-4">
                            {!! $article->content ?? '<p>Konten artikel akan ditampilkan di sini.</p>' !!}
                        </div>

                        {{-- Share & AI CTA --}}
                        <div class="pt-6 border-t border-slate-200 animate-fade-up stagger-5">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div>
                                    <span class="text-sm font-semibold text-slate-700">Bagikan artikel:</span>
                                    <div class="flex gap-2 mt-2">
                                        <button onclick="shareArticle('whatsapp')" class="p-2.5 rounded-xl bg-emerald-500 text-white hover:bg-emerald-600 transition-all duration-200 shadow-sm hover:shadow-md hover:scale-[1.05] active:scale-[0.98]" title="WhatsApp">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-.175-.073-.601-.323-.434-.254-.793-.528-1.189-.923-.195-.194-.395-.39-.692-.682C6.301 16.569 5.5 17.957 5.5 19.5h13c0-1.543-.801-2.931-2.028-4.118zM12 14a4 4 0 100-8 4 4 0 000 8z"/></svg>
                                        </button>
                                        <button onclick="shareArticle('twitter')" class="p-2.5 rounded-xl bg-sky-500 text-white hover:bg-sky-600 transition-all duration-200 shadow-sm hover:shadow-md hover:scale-[1.05] active:scale-[0.98]" title="Twitter">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                                        </button>
                                        <button onclick="shareArticle('facebook')" class="p-2.5 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition-all duration-200 shadow-sm hover:shadow-md hover:scale-[1.05] active:scale-[0.98]" title="Facebook">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                        </button>
                                        <button onclick="copyLink()" class="p-2.5 rounded-xl bg-slate-500 text-white hover:bg-slate-600 transition-all duration-200 shadow-sm hover:shadow-md hover:scale-[1.05] active:scale-[0.98]" title="Salin Link">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        </button>
                                    </div>
                                </div>
                                <a href="{{ route('ai-assist.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-primary-500 to-emerald-500 text-white rounded-full text-sm font-semibold hover:shadow-lg hover:scale-[1.02] transition-all duration-300 active:scale-[0.98]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                                    Tanya AI Assist
                                </a>
                            </div>
                        </div>

                        {{-- Related Articles --}}
                        @if(($relatedArticles ?? collect())->count() > 0)
                            <div class="animate-fade-up stagger-6">
                                <h3 class="text-lg font-bold text-slate-800 mb-5 flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-sky-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    Artikel Terkait
                                </h3>
                                <div class="grid gap-3 md:grid-cols-2">
                                    @foreach($relatedArticles as $related)
                                        <a href="{{ route('articles.show', $related->slug) }}" class="group flex gap-4 p-4 bg-white rounded-2xl shadow-card hover:shadow-card-hover transition-all duration-300">
                                            @if($related->image)
                                                <img src="{{ asset('storage/' . $related->image) }}" alt="" class="w-16 h-16 rounded-xl object-cover flex-shrink-0 group-hover:scale-105 transition-transform duration-300">
                                            @else
                                                <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-sky-100 to-primary-100 flex-shrink-0 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                                </div>
                                            @endif
                                            <div class="min-w-0">
                                                <h4 class="font-semibold text-sm text-slate-700 group-hover:text-primary-600 transition-colors line-clamp-2">{{ $related->title }}</h4>
                                                <p class="text-xs text-slate-400 mt-1">{{ $related->published_at->format('d M Y') }}</p>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Sidebar --}}
                    <div class="space-y-5">
                        @if(($popularArticles ?? collect())->count() > 0)
                            <div class="bg-white rounded-2xl shadow-card p-5 sticky top-4 animate-fade-up stagger-5">
                                <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-amber-100 flex items-center justify-center"><svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg></div>
                                    Artikel Populer
                                </h3>
                                <div class="space-y-2">
                                    @foreach($popularArticles as $index => $popular)
                                        <a href="{{ route('articles.show', $popular->slug) }}" class="group flex gap-3 p-2 rounded-xl hover:bg-white/5 transition-all duration-200">
                                            <span class="flex-shrink-0 w-7 h-7 rounded-lg bg-gradient-to-br from-primary-500 to-emerald-500 text-white text-xs font-bold flex items-center justify-center shadow-sm">{{ $index + 1 }}</span>
                                            <div class="min-w-0">
                                                <h4 class="text-sm font-medium text-slate-700 group-hover:text-primary-600 transition-colors line-clamp-2">{{ $popular->title }}</h4>
                                                <span class="text-[10px] text-slate-400">{{ number_format($popular->view_count) }} pembaca</span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <a href="{{ route('articles.index') }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-white rounded-2xl shadow-card text-sm font-medium text-slate-500 hover:text-primary-700 hover:shadow-card-hover hover:bg-white/5 transition-all duration-300 active:scale-[0.98]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Kembali ke Artikel
                        </a>
                    </div>
                </div>
            </article>
        </div>
    </div>

    @push('scripts')
    <script>
        function shareArticle(platform) {
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent(document.title);
            let shareUrl = '';
            switch(platform) {
                case 'whatsapp': shareUrl = `https://wa.me/?text=${title}%20${url}`; break;
                case 'twitter': shareUrl = `https://twitter.com/intent/tweet?text=${title}&url=${url}`; break;
                case 'facebook': shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`; break;
            }
            if (shareUrl) window.open(shareUrl, '_blank', 'width=600,height=400');
        }
        function copyLink() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-20 right-4 bg-primary-600 text-white px-5 py-2.5 rounded-full shadow-lg z-50 animate-fade-up text-sm font-medium';
                toast.textContent = 'Link berhasil disalin!';
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            });
        }
    </script>
    @endpush

    <style>
        [x-cloak] { display: none !important; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .text-balance { text-wrap: balance; }
    </style>
</x-app-layout>
