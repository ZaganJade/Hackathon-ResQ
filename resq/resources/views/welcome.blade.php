<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="ResQ — Platform mitigasi bencana berbasis AI untuk Indonesia. Peta interaktif, panduan darurat, dan asisten AI 24/7.">
    <title>{{ config('app.name', 'ResQ') }} — Sistem Mitigasi Bencana Berbasis AI</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Three.js for 3D Globe -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Inter', system-ui, sans-serif; overflow-x: hidden; }

        /* Smooth scroll */
        html { scroll-behavior: smooth; }

        /* 3D Canvas */
        #globe-canvas { position: absolute; inset: 0; z-index: 0; pointer-events: none; }

        /* Animations */
        @keyframes float { 0%,100% { transform: translateY(0px); } 50% { transform: translateY(-12px); } }
        @keyframes pulse-ring { 0% { transform: scale(0.9); opacity: 0.7; } 100% { transform: scale(1.4); opacity: 0; } }
        @keyframes slide-up { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slide-down { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }
        @keyframes glow { 0%,100% { box-shadow: 0 0 20px rgba(16,185,129,0.15); } 50% { box-shadow: 0 0 40px rgba(16,185,129,0.3); } }
        @keyframes gradient-x { 0%,100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }

        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-pulse-ring { animation: pulse-ring 2s cubic-bezier(0.4,0,0.6,1) infinite; }
        .animate-slide-up { animation: slide-up 0.8s ease-out forwards; }
        .animate-slide-down { animation: slide-down 0.6s ease-out forwards; }
        .animate-fade-in { animation: fade-in 1s ease-out forwards; }
        .animate-glow { animation: glow 3s ease-in-out infinite; }
        .animate-gradient { background-size: 200% 200%; animation: gradient-x 4s ease infinite; }

        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .delay-500 { animation-delay: 0.5s; }
        .delay-600 { animation-delay: 0.6s; }
        .delay-700 { animation-delay: 0.7s; }

        .opacity-0-start { opacity: 0; }

        /* Glass card */
        .glass { background: rgba(255,255,255,0.7); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.3); }
        .glass-dark { background: rgba(15,23,42,0.7); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.05); }

        /* Noise texture overlay */
        .noise::after {
            content: ''; position: absolute; inset: 0; z-index: 1;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.02'/%3E%3C/svg%3E");
            pointer-events: none;
        }

        /* Feature card hover */
        .feature-card { transition: all 0.4s cubic-bezier(0.4,0,0.2,1); }
        .feature-card:hover { transform: translateY(-8px) scale(1.02); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.08); }

        /* Scroll indicator */
        @keyframes scroll-bounce { 0%,20%,50%,80%,100% { transform: translateY(0) translateX(-50%); } 40% { transform: translateY(8px) translateX(-50%); } 60% { transform: translateY(4px) translateX(-50%); } }
        .scroll-indicator { animation: scroll-bounce 2.5s ease infinite; }

        /* Particle dots */
        .particle { position: absolute; width: 4px; height: 4px; border-radius: 50%; background: rgba(16,185,129,0.3); animation: float 8s ease-in-out infinite; }
    </style>
</head>
<body class="antialiased bg-slate-950 text-white">

    <!-- NAVIGATION -->
    <nav class="fixed w-full z-50 transition-all duration-500" id="mainNav">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 sm:h-20">
                <a href="/" class="flex items-center gap-2.5 group">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-400 to-primary-500 flex items-center justify-center shadow-lg shadow-emerald-500/20 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <span class="text-xl font-extrabold tracking-tight text-white">ResQ</span>
                </a>

                <div class="hidden md:flex items-center gap-8">
                    <a href="#features" class="text-sm text-slate-300 hover:text-emerald-400 transition-colors font-medium">Fitur</a>
                    <a href="#how-it-works" class="text-sm text-slate-300 hover:text-emerald-400 transition-colors font-medium">Cara Kerja</a>
                    <a href="#about" class="text-sm text-slate-300 hover:text-emerald-400 transition-colors font-medium">Tentang</a>
                </div>

                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="hidden sm:inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-primary-500 text-white rounded-full text-sm font-semibold hover:shadow-lg hover:shadow-emerald-500/30 hover:scale-[1.03] transition-all duration-300 active:scale-[0.98]">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline-flex text-sm text-slate-300 hover:text-white transition-colors font-medium">Masuk</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-primary-500 text-white rounded-full text-sm font-semibold hover:shadow-lg hover:shadow-emerald-500/30 hover:scale-[1.03] transition-all duration-300 active:scale-[0.98]">Daftar</a>
                    @endauth
                    <button id="mobile-menu-btn" class="md:hidden p-2 text-slate-300 hover:text-white rounded-xl hover:bg-white/10 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>
            <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-white/10 mt-2 pt-4">
                <div class="flex flex-col gap-2">
                    <a href="#features" class="text-slate-300 hover:text-emerald-400 px-3 py-2.5 rounded-xl hover:bg-white/5 transition text-sm font-medium">Fitur</a>
                    <a href="#how-it-works" class="text-slate-300 hover:text-emerald-400 px-3 py-2.5 rounded-xl hover:bg-white/5 transition text-sm font-medium">Cara Kerja</a>
                    <a href="#about" class="text-slate-300 hover:text-emerald-400 px-3 py-2.5 rounded-xl hover:bg-white/5 transition text-sm font-medium">Tentang</a>
                    @guest
                        <a href="{{ route('login') }}" class="text-slate-300 hover:text-white px-3 py-2.5 rounded-xl hover:bg-white/5 transition text-sm font-medium sm:hidden">Masuk</a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION with 3D Globe -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden noise">
        <!-- 3D Globe Canvas -->
        <canvas id="globe-canvas"></canvas>

        <!-- Radial glow behind globe -->
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-[1]">
            <div class="w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-[120px]"></div>
        </div>

        <!-- Floating particles -->
        <div class="particle" style="top:15%;left:10%;animation-delay:0s;"></div>
        <div class="particle" style="top:25%;left:80%;animation-delay:2s;"></div>
        <div class="particle" style="top:70%;left:15%;animation-delay:4s;"></div>
        <div class="particle" style="top:60%;left:85%;animation-delay:1s;"></div>
        <div class="particle" style="top:40%;left:5%;animation-delay:3s;"></div>
        <div class="particle" style="top:80%;left:70%;animation-delay:5s;"></div>

        <!-- Content -->
        <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center pt-24 pb-16">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-dark text-emerald-400 text-xs sm:text-sm font-semibold mb-8 opacity-0-start animate-slide-down">
                <span class="relative flex h-2.5 w-2.5"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span></span>
                Sistem Mitigasi Bencana Berbasis AI
            </div>

            <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black tracking-tight leading-[1.1] mb-6 opacity-0-start animate-slide-up delay-200">
                Siap Tanggap,<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-primary-400 to-sky-400 animate-gradient">Selamat Bersama</span>
            </h1>

            <p class="text-base sm:text-lg md:text-xl text-slate-400 max-w-2xl mx-auto mb-10 leading-relaxed opacity-0-start animate-slide-up delay-300">
                Platform mitigasi bencana yang menggabungkan kecerdasan buatan, peta interaktif, dan informasi real-time untuk keselamatan masyarakat Indonesia.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 opacity-0-start animate-slide-up delay-400">
                @auth
                    <a href="{{ route('dashboard') }}" class="group inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-emerald-500 to-primary-500 text-white rounded-full text-base font-bold shadow-xl shadow-emerald-500/25 hover:shadow-2xl hover:shadow-emerald-500/40 hover:scale-[1.03] transition-all duration-300 active:scale-[0.98] animate-glow">
                        <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Buka Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="group inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-emerald-500 to-primary-500 text-white rounded-full text-base font-bold shadow-xl shadow-emerald-500/25 hover:shadow-2xl hover:shadow-emerald-500/40 hover:scale-[1.03] transition-all duration-300 active:scale-[0.98] animate-glow">
                        <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Mulai Sekarang
                    </a>
                @endauth
                <a href="#features" class="inline-flex items-center gap-2 px-8 py-4 rounded-full text-base font-semibold text-slate-300 hover:text-white border border-white/10 hover:border-white/20 hover:bg-white/5 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Pelajari Fitur
                </a>
            </div>
        </div>

        <!-- Scroll indicator -->
        <div class="absolute bottom-8 left-1/2 scroll-indicator z-10">
            <div class="w-6 h-10 rounded-full border-2 border-white/20 flex items-start justify-center p-1.5">
                <div class="w-1.5 h-3 bg-emerald-400 rounded-full animate-pulse"></div>
            </div>
        </div>
    </section>

    <!-- STATS -->
    <section class="relative py-16 bg-slate-900 border-y border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
                @php $stats = [
                    ['value'=>'AI','label'=>'Asisten Pintar','color'=>'from-emerald-400 to-primary-400','delay'=>'delay-100'],
                    ['value'=>'24/7','label'=>'Monitoring Aktif','color'=>'from-sky-400 to-blue-400','delay'=>'delay-200'],
                    ['value'=>'Real-time','label'=>'Update Peta','color'=>'from-amber-400 to-orange-400','delay'=>'delay-300'],
                    ['value'=>'100%','label'=>'Info Terpercaya','color'=>'from-violet-400 to-purple-400','delay'=>'delay-400'],
                ]; @endphp
                @foreach($stats as $stat)
                    <div class="text-center p-4 rounded-2xl bg-white/[0.03] border border-white/[0.05] hover:bg-white/[0.06] transition-all duration-300 opacity-0-start animate-slide-up {{ $stat['delay'] }}">
                        <div class="text-2xl sm:text-3xl md:text-4xl font-black mb-1 text-transparent bg-clip-text bg-gradient-to-r {{ $stat['color'] }}">{{ $stat['value'] }}</div>
                        <div class="text-xs sm:text-sm text-slate-500 font-medium">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section id="features" class="py-20 sm:py-28 bg-slate-950 noise relative">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-96 h-96 bg-emerald-500/5 rounded-full blur-[150px] pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-xs font-bold uppercase tracking-wider mb-4">Fitur Unggulan</span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-white mb-4 tracking-tight">Semua yang Anda Butuhkan</h2>
                <p class="text-slate-400 max-w-2xl mx-auto text-base sm:text-lg">Fitur lengkap untuk membantu Anda menghadapi situasi darurat dengan lebih siap dan tenang.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-5">
                @php $features = [
                    ['title'=>'AI Assist','desc'=>'Asisten AI yang siap 24/7 memberikan panduan darurat dan jawaban terkait bencana.','icon'=>'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z','color'=>'from-emerald-500 to-primary-500','bg'=>'bg-emerald-500/10','text'=>'text-emerald-400','delay'=>'delay-100'],
                    ['title'=>'Peta Bencana','desc'=>'Pantau lokasi bencana real-time dengan peta interaktif dan filter lengkap.','icon'=>'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7','color'=>'from-sky-500 to-blue-500','bg'=>'bg-sky-500/10','text'=>'text-sky-400','delay'=>'delay-200'],
                    ['title'=>'Artikel Mitigasi','desc'=>'Akses artikel terkini tentang mitigasi, tips persiapan, dan informasi penting.','icon'=>'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z','color'=>'from-amber-500 to-orange-500','bg'=>'bg-amber-500/10','text'=>'text-amber-400','delay'=>'delay-300'],
                    ['title'=>'Panduan Darurat','desc'=>'Panduan lengkap cara menghadapi berbagai jenis bencana dari gempa hingga banjir.','icon'=>'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253','color'=>'from-violet-500 to-purple-500','bg'=>'bg-violet-500/10','text'=>'text-violet-400','delay'=>'delay-400'],
                ]; @endphp
                @foreach($features as $f)
                    <div class="feature-card glass-dark rounded-2xl p-6 opacity-0-start animate-slide-up {{ $f['delay'] }}">
                        <div class="w-12 h-12 {{ $f['bg'] }} rounded-2xl flex items-center justify-center mb-5">
                            <svg class="w-6 h-6 {{ $f['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f['icon'] }}"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">{{ $f['title'] }}</h3>
                        <p class="text-sm text-slate-400 leading-relaxed">{{ $f['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section id="how-it-works" class="py-20 sm:py-28 bg-slate-900 relative overflow-hidden">
        <div class="absolute inset-0"><div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-primary-500/5 rounded-full blur-[200px]"></div></div>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-primary-500/10 text-primary-400 text-xs font-bold uppercase tracking-wider mb-4">Cara Kerja</span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-white mb-4 tracking-tight">Tiga Langkah Mudah</h2>
                <p class="text-slate-400 max-w-xl mx-auto">Mulai pakai ResQ dalam hitungan menit.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6 sm:gap-8">
                @php $steps = [
                    ['num'=>'01','title'=>'Daftar Akun','desc'=>'Buat akun ResQ gratis untuk mengakses semua fitur dan notifikasi.','color'=>'from-emerald-500 to-primary-500','delay'=>'delay-200'],
                    ['num'=>'02','title'=>'Jelajahi Fitur','desc'=>'Gunakan AI Assist, pantau peta bencana, dan pelajari panduan darurat.','color'=>'from-sky-500 to-blue-500','delay'=>'delay-300'],
                    ['num'=>'03','title'=>'Siap Siaga','desc'=>'Aktifkan notifikasi WhatsApp untuk peringatan dini dan informasi terkini.','color'=>'from-amber-500 to-orange-500','delay'=>'delay-400'],
                ]; @endphp
                @foreach($steps as $step)
                    <div class="text-center opacity-0-start animate-slide-up {{ $step['delay'] }}">
                        <div class="relative inline-flex mb-6">
                            <div class="w-16 h-16 bg-gradient-to-br {{ $step['color'] }} rounded-2xl flex items-center justify-center text-white text-xl font-black shadow-lg">{{ $step['num'] }}</div>
                            <div class="absolute -inset-2 bg-gradient-to-br {{ $step['color'] }} rounded-2xl opacity-20 blur-lg"></div>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">{{ $step['title'] }}</h3>
                        <p class="text-sm text-slate-400 leading-relaxed">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-20 sm:py-28 bg-slate-950 relative overflow-hidden noise">
        <div class="absolute inset-0">
            <div class="absolute top-10 left-10 w-48 h-48 bg-emerald-500/10 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-10 right-10 w-64 h-64 bg-primary-500/10 rounded-full blur-[120px]"></div>
        </div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-white mb-6 tracking-tight">Siap Menjadi Lebih <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-sky-400">Siap</span>?</h2>
            <p class="text-base sm:text-lg text-slate-400 mb-10 max-w-2xl mx-auto leading-relaxed">
                Bergabung dengan ResQ sekarang dan dapatkan akses ke sistem mitigasi bencana berbasis AI yang siap membantu Anda 24/7.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-emerald-500 to-primary-500 text-white rounded-full text-base font-bold shadow-xl shadow-emerald-500/25 hover:shadow-2xl hover:shadow-emerald-500/40 hover:scale-[1.03] transition-all duration-300 active:scale-[0.98]">Buka Dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-emerald-500 to-primary-500 text-white rounded-full text-base font-bold shadow-xl shadow-emerald-500/25 hover:shadow-2xl hover:shadow-emerald-500/40 hover:scale-[1.03] transition-all duration-300 active:scale-[0.98]">Daftar Gratis</a>
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-full text-base font-semibold text-slate-300 border border-white/10 hover:border-white/20 hover:bg-white/5 transition-all duration-300">Sudah Punya Akun? Masuk</a>
                @endauth
            </div>
        </div>
    </section>

    <!-- ABOUT -->
    <section id="about" class="py-20 sm:py-28 bg-slate-900 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-xs font-bold uppercase tracking-wider mb-4">Tentang ResQ</span>
                    <h2 class="text-3xl sm:text-4xl font-black text-white mb-6 tracking-tight">Platform Mitigasi Bencana #1 di Indonesia</h2>
                    <p class="text-slate-400 mb-5 leading-relaxed">
                        ResQ dikembangkan untuk membantu masyarakat Indonesia menghadapi tantangan bencana alam. Dengan memanfaatkan teknologi AI, peta interaktif, dan sistem notifikasi real-time, ResQ berkomitmen meningkatkan kesiapsiagaan dan keselamatan masyarakat.
                    </p>
                    <p class="text-slate-400 mb-8 leading-relaxed">
                        Kami percaya bahwa informasi yang tepat pada waktu yang tepat dapat menyelamatkan nyawa. ResQ hadir sebagai solusi digital yang modern dan mudah diakses.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-500/10 text-emerald-400 text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Informasi Terpercaya
                        </div>
                        <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-sky-500/10 text-sky-400 text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Akses 24/7
                        </div>
                        <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-amber-500/10 text-amber-400 text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Gratis
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute -inset-4 bg-gradient-to-r from-emerald-500 to-primary-500 rounded-3xl opacity-10 blur-2xl"></div>
                    <div class="relative glass-dark rounded-3xl p-8 border border-white/5">
                        <div class="grid grid-cols-2 gap-4">
                            @php $aboutStats = [
                                ['v'=>'AI','l'=>'Powered','c'=>'from-emerald-400 to-primary-400'],
                                ['v'=>'24/7','l'=>'Support','c'=>'from-sky-400 to-blue-400'],
                                ['v'=>'Real','l'=>'Time','c'=>'from-amber-400 to-orange-400'],
                                ['v'=>'Safe','l'=>'First','c'=>'from-violet-400 to-purple-400'],
                            ]; @endphp
                            @foreach($aboutStats as $as)
                                <div class="bg-white/[0.03] rounded-2xl p-5 text-center border border-white/[0.05] hover:bg-white/[0.06] transition-all duration-300">
                                    <div class="text-2xl sm:text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r {{ $as['c'] }} mb-1">{{ $as['v'] }}</div>
                                    <div class="text-xs text-slate-500 font-medium">{{ $as['l'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-slate-950 border-t border-white/5 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-12">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-500 to-primary-500 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <span class="text-lg font-extrabold text-white">ResQ</span>
                    </div>
                    <p class="text-sm text-slate-500 max-w-md leading-relaxed">Sistem Mitigasi Bencana berbasis AI untuk membantu masyarakat Indonesia menghadapi dan mengatasi bencana alam.</p>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-white mb-4 uppercase tracking-wider">Menu Cepat</h4>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('dashboard') }}" class="text-sm text-slate-500 hover:text-emerald-400 transition-colors">Dashboard</a></li>
                        <li><a href="{{ route('ai-assist.index') }}" class="text-sm text-slate-500 hover:text-emerald-400 transition-colors">AI Assist</a></li>
                        <li><a href="{{ route('map.index') }}" class="text-sm text-slate-500 hover:text-emerald-400 transition-colors">Peta Bencana</a></li>
                        <li><a href="{{ route('articles.index') }}" class="text-sm text-slate-500 hover:text-emerald-400 transition-colors">Artikel</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-white mb-4 uppercase tracking-wider">Bantuan</h4>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('guides.index') }}" class="text-sm text-slate-500 hover:text-emerald-400 transition-colors">Panduan</a></li>
                        <li><a href="#" class="text-sm text-slate-500 hover:text-emerald-400 transition-colors">FAQ</a></li>
                        <li><a href="#" class="text-sm text-slate-500 hover:text-emerald-400 transition-colors">Hubungi Kami</a></li>
                        <li><a href="#" class="text-sm text-slate-500 hover:text-emerald-400 transition-colors">Kebijakan Privasi</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/5 pt-8 text-center">
                <p class="text-xs text-slate-600">&copy; {{ date('Y') }} ResQ. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- SCRIPTS -->
    <script>
        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenuBtn.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));
        mobileMenu.querySelectorAll('a').forEach(link => link.addEventListener('click', () => mobileMenu.classList.add('hidden')));

        // Nav blur on scroll
        const mainNav = document.getElementById('mainNav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                mainNav.classList.add('bg-slate-950/80', 'backdrop-blur-xl', 'border-b', 'border-white/5');
            } else {
                mainNav.classList.remove('bg-slate-950/80', 'backdrop-blur-xl', 'border-b', 'border-white/5');
            }
        });

        // Intersection Observer for scroll animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.opacity-0-start').forEach(el => observer.observe(el));

        // ========== THREE.JS 3D INTERACTIVE GLOBE ==========
        (function() {
            const canvas = document.getElementById('globe-canvas');
            if (!canvas || typeof THREE === 'undefined') return;

            const scene = new THREE.Scene();
            const camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 1000);
            const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true });
            renderer.setSize(window.innerWidth, window.innerHeight);
            renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

            // Globe sphere with wireframe
            const globeGeom = new THREE.SphereGeometry(2.2, 48, 48);
            const globeMat = new THREE.MeshBasicMaterial({
                color: 0x10b981,
                wireframe: true,
                transparent: true,
                opacity: 0.08
            });
            const globe = new THREE.Mesh(globeGeom, globeMat);
            scene.add(globe);

            // Inner solid sphere (subtle glow)
            const innerGeom = new THREE.SphereGeometry(2.15, 48, 48);
            const innerMat = new THREE.MeshBasicMaterial({
                color: 0x064e3b,
                transparent: true,
                opacity: 0.15
            });
            const inner = new THREE.Mesh(innerGeom, innerMat);
            scene.add(inner);

            // Points on the globe (simulating disaster hotspots)
            const pointsGeom = new THREE.BufferGeometry();
            const pointCount = 120;
            const positions = new Float32Array(pointCount * 3);
            const colors = new Float32Array(pointCount * 3);

            for (let i = 0; i < pointCount; i++) {
                const phi = Math.acos(-1 + (2 * i) / pointCount);
                const theta = Math.sqrt(pointCount * Math.PI) * phi;
                const r = 2.25;

                positions[i * 3] = r * Math.cos(theta) * Math.sin(phi);
                positions[i * 3 + 1] = r * Math.sin(theta) * Math.sin(phi);
                positions[i * 3 + 2] = r * Math.cos(phi);

                // Vary colors between emerald and sky
                const t = Math.random();
                colors[i * 3] = 0.06 + t * 0.2;
                colors[i * 3 + 1] = 0.6 + t * 0.15;
                colors[i * 3 + 2] = 0.5 + t * 0.3;
            }

            pointsGeom.setAttribute('position', new THREE.BufferAttribute(positions, 3));
            pointsGeom.setAttribute('color', new THREE.BufferAttribute(colors, 3));

            const pointsMat = new THREE.PointsMaterial({
                size: 0.04,
                vertexColors: true,
                transparent: true,
                opacity: 0.7,
                sizeAttenuation: true
            });
            const points = new THREE.Points(pointsGeom, pointsMat);
            scene.add(points);

            // Orbit ring
            const ringGeom = new THREE.RingGeometry(3.0, 3.02, 128);
            const ringMat = new THREE.MeshBasicMaterial({ color: 0x10b981, transparent: true, opacity: 0.06, side: THREE.DoubleSide });
            const ring = new THREE.Mesh(ringGeom, ringMat);
            ring.rotation.x = Math.PI / 3;
            scene.add(ring);

            const ring2 = ring.clone();
            ring2.rotation.x = Math.PI / 2.5;
            ring2.rotation.z = Math.PI / 4;
            scene.add(ring2);

            camera.position.z = 6;
            camera.position.y = 0.5;

            // Mouse interaction
            let mouseX = 0, mouseY = 0;
            canvas.parentElement.addEventListener('mousemove', (e) => {
                mouseX = (e.clientX / window.innerWidth) * 2 - 1;
                mouseY = -(e.clientY / window.innerHeight) * 2 + 1;
            });

            // Touch interaction for mobile
            canvas.parentElement.addEventListener('touchmove', (e) => {
                if (e.touches.length > 0) {
                    mouseX = (e.touches[0].clientX / window.innerWidth) * 2 - 1;
                    mouseY = -(e.touches[0].clientY / window.innerHeight) * 2 + 1;
                }
            }, { passive: true });

            // Animation loop
            function animate() {
                requestAnimationFrame(animate);

                const time = Date.now() * 0.001;

                globe.rotation.y += 0.002;
                globe.rotation.x = mouseY * 0.15;
                inner.rotation.y += 0.002;
                inner.rotation.x = mouseY * 0.15;
                points.rotation.y += 0.002;
                points.rotation.x = mouseY * 0.15;

                ring.rotation.z += 0.001;
                ring2.rotation.z -= 0.0008;

                // Subtle camera movement
                camera.position.x += (mouseX * 0.5 - camera.position.x) * 0.02;
                camera.position.y += (mouseY * 0.3 + 0.5 - camera.position.y) * 0.02;
                camera.lookAt(0, 0, 0);

                renderer.render(scene, camera);
            }
            animate();

            // Resize handler
            window.addEventListener('resize', () => {
                camera.aspect = window.innerWidth / window.innerHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(window.innerWidth, window.innerHeight);
            });
        })();
    </script>
</body>
</html>
