<x-app-layout>
    {{-- Dashboard — Reference-matched mobile-first design --}}

    <div class="min-h-screen bg-slate-50 pb-24 lg:pb-8" x-data="dashboardApp()">

        {{-- ============================================
             1. HERO / WELCOME SECTION
             Teal gradient with rounded bottom
             ============================================ --}}
        <section class="relative bg-gradient-to-br from-primary-700 via-primary-600 to-secondary-600 overflow-hidden">
            {{-- Decorative elements --}}
            <div class="absolute top-0 right-0 w-72 h-72 bg-white/5 rounded-full -translate-y-1/3 translate-x-1/4"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/3 -translate-x-1/4"></div>

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-10 sm:pt-8 sm:pb-14">
                {{-- Top bar: Welcome + Icons --}}
                <div class="flex items-center justify-between mb-6 sm:mb-8">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center ring-2 ring-white/30">
                            <span class="text-white font-bold text-sm">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                        </div>
                        <div>
                            <p class="text-primary-200 text-xs">Welcome</p>
                            <p class="text-white font-semibold text-sm">{{ Auth::user()->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('chat-history.index') }}" class="w-10 h-10 rounded-full bg-white/15 backdrop-blur-sm flex items-center justify-center hover:bg-white/25 transition-colors">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="w-10 h-10 rounded-full bg-white/15 backdrop-blur-sm flex items-center justify-center hover:bg-white/25 transition-colors relative">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            <span class="absolute -top-0.5 -right-0.5 w-3 h-3 bg-rose-500 rounded-full ring-2 ring-primary-600"></span>
                        </a>
                    </div>
                </div>

                {{-- Hero text --}}
                <div class="max-w-lg">
                    <h1 class="text-white text-xl sm:text-2xl lg:text-3xl font-bold leading-tight">
                        Ayo<br>
                        Siapkan Dirimu Hadapi<br>
                        Bencana Alam
                    </h1>
                </div>
            </div>

            {{-- Rounded bottom --}}
            <div class="absolute bottom-0 left-0 right-0 h-6 bg-slate-50 rounded-t-3xl"></div>
        </section>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 -mt-1">

            {{-- ============================================
                 2. LIST BENCANA ALAM — Horizontal Scroll
                 ============================================ --}}
            <section class="animate-fade-up">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-slate-800 text-base sm:text-lg">List Bencana Alam</h2>
                    <a href="{{ route('guides.index') }}" class="text-xs sm:text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors">Lihat semua</a>
                </div>

                {{-- Horizontal scroll container --}}
                <div class="flex gap-4 overflow-x-auto pb-4 -mx-4 px-4 snap-x snap-mandatory scrollbar-hide lg:grid lg:grid-cols-3 lg:overflow-visible lg:mx-0 lg:px-0">
                    {{-- Card: Tsunami --}}
                    <div class="snap-start shrink-0 w-72 sm:w-80 lg:w-auto bg-white rounded-2xl shadow-card hover:shadow-card-hover transition-all duration-300 overflow-hidden group">
                        <div class="relative h-40 sm:h-44 overflow-hidden">
                            <img src="{{ asset('images/dashboard/tsunami.png') }}" alt="Tsunami" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute top-3 left-3">
                                <span class="bg-secondary-600/90 backdrop-blur-sm text-white text-[10px] font-semibold px-3 py-1 rounded-full">Geologi</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-slate-800 text-base mb-1">Tsunami</h3>
                            <p class="text-xs text-slate-500 leading-relaxed line-clamp-2">Tsunami Umumnya Disebabkan Oleh Gempa Bumi Bawah Laut Yang...</p>
                            <a href="{{ route('guides.index') }}" class="inline-flex items-center gap-1 text-xs text-primary-600 font-semibold mt-3 hover:text-primary-700 transition-colors">
                                Lihat Selengkapnya
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        </div>
                    </div>

                    {{-- Card: Gempa Bumi --}}
                    <div class="snap-start shrink-0 w-72 sm:w-80 lg:w-auto bg-white rounded-2xl shadow-card hover:shadow-card-hover transition-all duration-300 overflow-hidden group">
                        <div class="relative h-40 sm:h-44 overflow-hidden">
                            <img src="{{ asset('images/dashboard/earthquake.png') }}" alt="Gempa Bumi" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute top-3 left-3">
                                <span class="bg-amber-600/90 backdrop-blur-sm text-white text-[10px] font-semibold px-3 py-1 rounded-full">Tektonik</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-slate-800 text-base mb-1">Gempa Bumi</h3>
                            <p class="text-xs text-slate-500 leading-relaxed line-clamp-2">Gempa bumi terjadi akibat pergerakan lempeng tektonik di bawah permukaan...</p>
                            <a href="{{ route('guides.index') }}" class="inline-flex items-center gap-1 text-xs text-primary-600 font-semibold mt-3 hover:text-primary-700 transition-colors">
                                Lihat Selengkapnya
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        </div>
                    </div>

                    {{-- Card: Erupsi Gunung --}}
                    <div class="snap-start shrink-0 w-72 sm:w-80 lg:w-auto bg-white rounded-2xl shadow-card hover:shadow-card-hover transition-all duration-300 overflow-hidden group">
                        <div class="relative h-40 sm:h-44 overflow-hidden">
                            <img src="{{ asset('images/dashboard/volcano.png') }}" alt="Erupsi Gunung Api" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute top-3 left-3">
                                <span class="bg-rose-600/90 backdrop-blur-sm text-white text-[10px] font-semibold px-3 py-1 rounded-full">Vulkanik</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-slate-800 text-base mb-1">Erupsi Gunung Api</h3>
                            <p class="text-xs text-slate-500 leading-relaxed line-clamp-2">Erupsi gunung api menghasilkan lava, abu vulkanik, dan awan panas yang sangat...</p>
                            <a href="{{ route('guides.index') }}" class="inline-flex items-center gap-1 text-xs text-primary-600 font-semibold mt-3 hover:text-primary-700 transition-colors">
                                Lihat Selengkapnya
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ============================================
                 3. BERITA TERKINI — 2-col grid
                 ============================================ --}}
            <section class="animate-fade-up stagger-2">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-slate-800 text-base sm:text-lg">Berita Terkini</h2>
                    <a href="{{ route('articles.index') }}" class="text-xs sm:text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors">Lihat semua</a>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                    {{-- News 1: Banjir --}}
                    <a href="{{ route('articles.index') }}" class="group">
                        <div class="relative rounded-2xl overflow-hidden aspect-[4/3] mb-2.5">
                            <img src="{{ asset('images/dashboard/flood.png') }}" alt="Banjir Bandang" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                        </div>
                        <h3 class="font-semibold text-slate-800 text-xs sm:text-sm leading-snug line-clamp-2 group-hover:text-primary-700 transition-colors">Banjir Bandang Melanda Kalimantan...</h3>
                        <p class="text-[10px] sm:text-xs text-slate-400 mt-1">Senin, 6 Juni 2025</p>
                    </a>

                    {{-- News 2: Longsor --}}
                    <a href="{{ route('articles.index') }}" class="group">
                        <div class="relative rounded-2xl overflow-hidden aspect-[4/3] mb-2.5">
                            <img src="{{ asset('images/dashboard/landslide.png') }}" alt="Tanah Longsor" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                        </div>
                        <h3 class="font-semibold text-slate-800 text-xs sm:text-sm leading-snug line-clamp-2 group-hover:text-primary-700 transition-colors">Angin Kencang dan Tanah Longsor...</h3>
                        <p class="text-[10px] sm:text-xs text-slate-400 mt-1">Senin, 6 Juni 2025</p>
                    </a>

                    {{-- News 3: Gempa (desktop only extra) --}}
                    <a href="{{ route('articles.index') }}" class="group hidden lg:block">
                        <div class="relative rounded-2xl overflow-hidden aspect-[4/3] mb-2.5">
                            <img src="{{ asset('images/dashboard/earthquake.png') }}" alt="Gempa Bumi" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                        </div>
                        <h3 class="font-semibold text-slate-800 text-xs sm:text-sm leading-snug line-clamp-2 group-hover:text-primary-700 transition-colors">Gempa M5.2 Guncang Sulawesi Utara</h3>
                        <p class="text-[10px] sm:text-xs text-slate-400 mt-1">Minggu, 5 Juni 2025</p>
                    </a>

                    {{-- News 4: Erupsi (desktop only extra) --}}
                    <a href="{{ route('articles.index') }}" class="group hidden lg:block">
                        <div class="relative rounded-2xl overflow-hidden aspect-[4/3] mb-2.5">
                            <img src="{{ asset('images/dashboard/volcano.png') }}" alt="Erupsi Gunung" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                        </div>
                        <h3 class="font-semibold text-slate-800 text-xs sm:text-sm leading-snug line-clamp-2 group-hover:text-primary-700 transition-colors">Aktivitas Gunung Merapi Meningkat</h3>
                        <p class="text-[10px] sm:text-xs text-slate-400 mt-1">Sabtu, 4 Juni 2025</p>
                    </a>
                </div>
            </section>

            {{-- ============================================
                 4. ARTIKEL — 2-col grid
                 ============================================ --}}
            <section class="animate-fade-up stagger-3">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-slate-800 text-base sm:text-lg">Artikel</h2>
                    <a href="{{ route('articles.index') }}" class="text-xs sm:text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors">Lihat semua</a>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                    {{-- Article 1 --}}
                    <a href="{{ route('articles.index') }}" class="group bg-white rounded-2xl overflow-hidden shadow-card hover:shadow-card-hover transition-all duration-300">
                        <div class="relative aspect-[4/3] overflow-hidden">
                            <img src="{{ asset('images/dashboard/earthquake.png') }}" alt="Menghadapi Gempa Bumi" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="p-3 sm:p-4">
                            <h3 class="font-semibold text-slate-800 text-xs sm:text-sm leading-snug line-clamp-2 group-hover:text-primary-700 transition-colors">Menghadapi Gempa Bumi</h3>
                            <p class="text-[10px] sm:text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Tim ResQ
                            </p>
                        </div>
                    </a>

                    {{-- Article 2 --}}
                    <a href="{{ route('articles.index') }}" class="group bg-white rounded-2xl overflow-hidden shadow-card hover:shadow-card-hover transition-all duration-300">
                        <div class="relative aspect-[4/3] overflow-hidden">
                            <img src="{{ asset('images/dashboard/volcano.png') }}" alt="Mitigasi Gunung Api" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="p-3 sm:p-4">
                            <h3 class="font-semibold text-slate-800 text-xs sm:text-sm leading-snug line-clamp-2 group-hover:text-primary-700 transition-colors">Mengenali Ancaman dan Mitigasi Gunung...</h3>
                            <p class="text-[10px] sm:text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Tim ResQ
                            </p>
                        </div>
                    </a>

                    {{-- Article 3 --}}
                    <a href="{{ route('guides.index') }}" class="group bg-white rounded-2xl overflow-hidden shadow-card hover:shadow-card-hover transition-all duration-300">
                        <div class="relative aspect-[4/3] overflow-hidden">
                            <img src="{{ asset('images/dashboard/preparedness.png') }}" alt="Tas Siaga Bencana" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="p-3 sm:p-4">
                            <h3 class="font-semibold text-slate-800 text-xs sm:text-sm leading-snug line-clamp-2 group-hover:text-primary-700 transition-colors">Cara Menyiapkan Tas Siaga Bencana</h3>
                            <p class="text-[10px] sm:text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Tim ResQ
                            </p>
                        </div>
                    </a>

                    {{-- Article 4 --}}
                    <a href="{{ route('guides.index') }}" class="group bg-white rounded-2xl overflow-hidden shadow-card hover:shadow-card-hover transition-all duration-300">
                        <div class="relative aspect-[4/3] overflow-hidden">
                            <img src="{{ asset('images/dashboard/flood.png') }}" alt="Panduan Evakuasi Banjir" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="p-3 sm:p-4">
                            <h3 class="font-semibold text-slate-800 text-xs sm:text-sm leading-snug line-clamp-2 group-hover:text-primary-700 transition-colors">Panduan Evakuasi Saat Banjir Datang</h3>
                            <p class="text-[10px] sm:text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Tim ResQ
                            </p>
                        </div>
                    </a>
                </div>
            </section>

            {{-- ============================================
                 5. NOMOR DARURAT — Desktop only sidebar feel
                 ============================================ --}}
            <section class="animate-fade-up stagger-4 lg:hidden">
                <h2 class="font-bold text-slate-800 text-base sm:text-lg mb-4">Nomor Darurat</h2>
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-white rounded-2xl p-4 shadow-card flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center shrink-0">
                            <span class="text-lg">🚨</span>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">BNPB</p>
                            <p class="font-bold text-slate-800">117</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-4 shadow-card flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center shrink-0">
                            <span class="text-lg">🚑</span>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Ambulans</p>
                            <p class="font-bold text-slate-800">118</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-4 shadow-card flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center shrink-0">
                            <span class="text-lg">⛑️</span>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Basarnas</p>
                            <p class="font-bold text-slate-800">115</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-4 shadow-card flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-sky-100 flex items-center justify-center shrink-0">
                            <span class="text-lg">👮</span>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Polisi</p>
                            <p class="font-bold text-slate-800">110</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        {{-- ============================================
             MOBILE BOTTOM NAVIGATION
             ============================================ --}}
        <nav class="fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-slate-100 shadow-lg lg:hidden">
            <div class="flex items-center justify-around h-16 max-w-lg mx-auto px-2">
                {{-- Beranda --}}
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center gap-0.5 text-primary-600 min-w-[56px]">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                    <span class="text-[10px] font-semibold">Beranda</span>
                </a>

                {{-- Peta --}}
                <a href="{{ route('map.index') }}" class="flex flex-col items-center justify-center gap-0.5 text-slate-400 hover:text-primary-600 transition-colors min-w-[56px]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                    <span class="text-[10px] font-medium">Peta</span>
                </a>

                {{-- AI Chat (Center CTA) --}}
                <a href="{{ route('ai-assist.index') }}" class="flex flex-col items-center justify-center -mt-5">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center shadow-lg shadow-primary-500/30 ring-4 ring-white">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    </div>
                    <span class="text-[10px] font-medium text-slate-500 mt-1">AI Chat</span>
                </a>

                {{-- Artikel --}}
                <a href="{{ route('articles.index') }}" class="flex flex-col items-center justify-center gap-0.5 text-slate-400 hover:text-primary-600 transition-colors min-w-[56px]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    <span class="text-[10px] font-medium">Artikel</span>
                </a>

                {{-- Profil --}}
                <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center gap-0.5 text-slate-400 hover:text-primary-600 transition-colors min-w-[56px]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span class="text-[10px] font-medium">Profil</span>
                </a>
            </div>
        </nav>
    </div>

    {{-- Alpine.js --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dashboardApp', () => ({
                init() {}
            }));
        });
    </script>

    {{-- Hide scrollbar utility --}}
    <style>
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
