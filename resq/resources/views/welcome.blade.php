<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ResQ') }} - Sistem Mitigasi Bencana</title>

        <!-- Fonts: Poppins -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50">
        <!-- Navigation -->
        <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-slate-100 shadow-soft">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="/" class="flex items-center space-x-2">
                            <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-xl flex items-center justify-center shadow-soft">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <span class="text-xl font-bold text-slate-800">ResQ</span>
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="#features" class="text-slate-600 hover:text-primary-600 transition">Fitur</a>
                        <a href="#how-it-works" class="text-slate-600 hover:text-primary-600 transition">Cara Kerja</a>
                        <a href="#about" class="text-slate-600 hover:text-primary-600 transition">Tentang</a>
                    </div>

                    <!-- Auth Buttons -->
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="hidden md:inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-full hover:bg-primary-700 transition shadow-soft">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-slate-600 hover:text-slate-800 transition">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-full hover:bg-primary-700 transition shadow-soft">
                                Daftar
                            </a>
                        @endauth

                        <!-- Mobile menu button -->
                        <button id="mobile-menu-btn" class="md:hidden p-2 text-slate-600 hover:text-primary-600 hover:bg-primary-50 rounded-full transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile Menu -->
                <div id="mobile-menu" class="hidden md:hidden py-4 border-t border-slate-100">
                    <div class="flex flex-col space-y-3">
                        <a href="#features" class="text-slate-600 hover:text-primary-600 px-3 py-2 rounded-lg hover:bg-primary-50 transition">Fitur</a>
                        <a href="#how-it-works" class="text-slate-600 hover:text-primary-600 px-3 py-2 rounded-lg hover:bg-primary-50 transition">Cara Kerja</a>
                        <a href="#about" class="text-slate-600 hover:text-primary-600 px-3 py-2 rounded-lg hover:bg-primary-50 transition">Tentang</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative pt-32 pb-20 lg:pt-40 lg:pb-32 overflow-hidden">
            <!-- Nature-inspired Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-primary-50 via-slate-50 to-secondary-50 -z-10"></div>
            <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-primary-100/50 to-transparent -z-10 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-1/3 h-1/2 bg-gradient-to-tr from-secondary-100/30 to-transparent -z-10 blur-3xl"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-4xl mx-auto">
                    <!-- Badge -->
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-primary-100 text-primary-700 text-sm font-medium mb-6 animate-fade-in">
                        <span class="w-2 h-2 bg-success rounded-full mr-2 animate-pulse"></span>
                        Sistem Mitigasi Bencana Berbasis AI
                    </div>

                    <!-- Heading -->
                    <h1 class="heading-1 text-slate-800 mb-6 animate-fade-up stagger-1">
                        Siap Tanggap, <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-secondary-600">Selamat Bersama</span>
                    </h1>

                    <!-- Description -->
                    <p class="body-large mb-10 max-w-2xl mx-auto animate-fade-up stagger-2">
                        ResQ adalah platform mitigasi bencana yang menggabungkan kecerdasan buatan, peta interaktif, dan informasi real-time untuk membantu masyarakat menghadapi dan mengatasi bencana alam.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-up stagger-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn-primary px-8 py-4 text-base">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Mulai Sekarang
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn-primary px-8 py-4 text-base shadow-soft-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Mulai Sekarang
                            </a>
                        @endauth
                        <a href="#features" class="btn-ghost px-8 py-4 text-base border border-slate-200 bg-white">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Pelajari Fitur
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-12 bg-white border-y border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div class="animate-fade-up stagger-1">
                        <div class="text-3xl md:text-4xl font-bold text-primary-600 mb-1">AI</div>
                        <div class="text-sm text-slate-600">Asisten Pintar</div>
                    </div>
                    <div class="animate-fade-up stagger-2">
                        <div class="text-3xl md:text-4xl font-bold text-secondary-600 mb-1">24/7</div>
                        <div class="text-sm text-slate-600">Monitoring Aktif</div>
                    </div>
                    <div class="animate-fade-up stagger-3">
                        <div class="text-3xl md:text-4xl font-bold text-accent-600 mb-1">Real-time</div>
                        <div class="text-sm text-slate-600">Update Peta Bencana</div>
                    </div>
                    <div class="animate-fade-up stagger-4">
                        <div class="text-3xl md:text-4xl font-bold text-primary-700 mb-1">100%</div>
                        <div class="text-sm text-slate-600">Informasi Terpercaya</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 bg-slate-50/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="heading-2 text-slate-800 mb-4">Fitur Unggulan</h2>
                    <p class="body-large max-w-2xl mx-auto">
                        ResQ hadir dengan berbagai fitur untuk membantu Anda menghadapi situasi darurat dengan lebih siap dan tenang.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- AI Assist -->
                    <div class="card p-6 animate-fade-up stagger-1">
                        <div class="w-14 h-14 bg-primary-100 rounded-2xl flex items-center justify-center mb-4">
                            <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-800 mb-2">AI Assist</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Asisten AI yang siap membantu memberikan informasi, panduan darurat, dan jawaban atas pertanyaan terkait bencana.
                        </p>
                    </div>

                    <!-- Disaster Map -->
                    <div class="card p-6 animate-fade-up stagger-2">
                        <div class="w-14 h-14 bg-secondary-100 rounded-2xl flex items-center justify-center mb-4">
                            <svg class="w-7 h-7 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 01-.447-.894L15 7m0 13V7"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-800 mb-2">Peta Bencana</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Pantau lokasi bencana real-time dengan peta interaktif lengkap dengan filter jenis dan level bencana.
                        </p>
                    </div>

                    <!-- Articles -->
                    <div class="card p-6 animate-fade-up stagger-3">
                        <div class="w-14 h-14 bg-sky-100 rounded-2xl flex items-center justify-center mb-4">
                            <svg class="w-7 h-7 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-800 mb-2">Artikel Mitigasi</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Akses artikel terkini tentang mitigasi bencana, tips persiapan, dan informasi penting lainnya.
                        </p>
                    </div>

                    <!-- Guides -->
                    <div class="card p-6 animate-fade-up stagger-4">
                        <div class="w-14 h-14 bg-accent-100 rounded-2xl flex items-center justify-center mb-4">
                            <svg class="w-7 h-7 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-800 mb-2">Panduan Darurat</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Panduan lengkap cara menghadapi berbagai jenis bencana, mulai dari gempa bumi hingga banjir.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section id="how-it-works" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="heading-2 text-slate-800 mb-4">Cara Kerja ResQ</h2>
                    <p class="body-large max-w-2xl mx-auto">
                        Tiga langkah sederhana untuk memanfaatkan ResQ dalam situasi darurat.
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Step 1 -->
                    <div class="relative text-center animate-fade-up stagger-1">
                        <div class="absolute top-8 left-1/2 w-full h-0.5 bg-slate-200 -z-10 hidden md:block"></div>
                        <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold shadow-soft-lg">
                            1
                        </div>
                        <h3 class="text-xl font-semibold text-slate-800 mb-2">Daftar Akun</h3>
                        <p class="text-slate-600">
                            Buat akun ResQ gratis untuk mengakses semua fitur dan menerima notifikasi penting.
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative text-center animate-fade-up stagger-2">
                        <div class="absolute top-8 left-1/2 w-full h-0.5 bg-slate-200 -z-10 hidden md:block"></div>
                        <div class="w-16 h-16 bg-gradient-to-br from-secondary-500 to-secondary-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold shadow-soft-lg">
                            2
                        </div>
                        <h3 class="text-xl font-semibold text-slate-800 mb-2">Jelajahi Fitur</h3>
                        <p class="text-slate-600">
                            Gunakan AI Assist, pantau peta bencana, dan pelajari panduan darurat yang tersedia.
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative text-center animate-fade-up stagger-3">
                        <div class="w-16 h-16 bg-gradient-to-br from-accent-500 to-accent-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold shadow-soft-lg">
                            3
                        </div>
                        <h3 class="text-xl font-semibold text-slate-800 mb-2">Siap Siaga</h3>
                        <p class="text-slate-600">
                            Aktifkan notifikasi WhatsApp untuk menerima peringatan dini dan informasi terkini.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 bg-gradient-to-br from-primary-600 to-secondary-600 relative overflow-hidden">
            <!-- Decorative elements -->
            <div class="absolute top-0 left-0 w-full h-full">
                <div class="absolute top-10 left-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute bottom-10 right-10 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
            </div>

            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Siap Menjadi Lebih Siap?</h2>
                <p class="text-lg text-primary-100 mb-10 max-w-2xl mx-auto">
                    Bergabung dengan ResQ sekarang dan dapatkan akses ke sistem mitigasi bencana berbasis AI yang siap membantu Anda 24/7.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-8 py-4 bg-white text-primary-600 rounded-full hover:bg-slate-50 transition shadow-lg font-medium">
                            Buka Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 bg-white text-primary-600 rounded-full hover:bg-slate-50 transition shadow-lg font-medium">
                            Daftar Gratis
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center px-8 py-4 bg-transparent text-white rounded-full hover:bg-white/10 transition border border-white/30 font-medium">
                            Sudah Punya Akun? Masuk
                        </a>
                    @endauth
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="py-20 bg-slate-50/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="heading-2 text-slate-800 mb-6">Tentang ResQ</h2>
                        <p class="text-slate-600 mb-6 leading-relaxed">
                            ResQ adalah platform mitigasi bencana yang dikembangkan untuk membantu masyarakat Indonesia menghadapi tantangan bencana alam. Dengan memanfaatkan teknologi AI, peta interaktif, dan sistem notifikasi real-time, ResQ berkomitmen untuk meningkatkan kesiapsiagaan dan keselamatan masyarakat.
                        </p>
                        <p class="text-slate-600 mb-8 leading-relaxed">
                            Kami percaya bahwa informasi yang tepat pada waktu yang tepat dapat menyelamatkan nyawa. ResQ hadir sebagai solusi digital yang menjembatani kebutuhan informasi darurat dengan teknologi modern.
                        </p>
                        <div class="flex flex-wrap items-center gap-4">
                            <div class="flex items-center text-slate-600">
                                <svg class="w-5 h-5 text-primary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Informasi Terpercaya
                            </div>
                            <div class="flex items-center text-slate-600">
                                <svg class="w-5 h-5 text-primary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Akses 24/7
                            </div>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-3xl transform rotate-3 opacity-20"></div>
                        <div class="relative bg-white rounded-3xl p-8 shadow-soft-xl border border-slate-100">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-primary-50 rounded-2xl p-4 text-center">
                                    <div class="text-3xl font-bold text-primary-600 mb-1">AI</div>
                                    <div class="text-sm text-slate-600">Powered</div>
                                </div>
                                <div class="bg-secondary-50 rounded-2xl p-4 text-center">
                                    <div class="text-3xl font-bold text-secondary-600 mb-1">24/7</div>
                                    <div class="text-sm text-slate-600">Support</div>
                                </div>
                                <div class="bg-sky-50 rounded-2xl p-4 text-center">
                                    <div class="text-3xl font-bold text-sky-600 mb-1">Real</div>
                                    <div class="text-sm text-slate-600">Time</div>
                                </div>
                                <div class="bg-accent-50 rounded-2xl p-4 text-center">
                                    <div class="text-3xl font-bold text-accent-600 mb-1">Safe</div>
                                    <div class="text-sm text-slate-600">First</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-slate-900 text-slate-300 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-4 gap-8 mb-8">
                    <!-- Brand -->
                    <div class="col-span-2">
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <span class="text-xl font-bold text-white">ResQ</span>
                        </div>
                        <p class="text-slate-400 mb-4 max-w-md">
                            Sistem Mitigasi Bencana berbasis AI untuk membantu masyarakat Indonesia menghadapi dan mengatasi bencana alam.
                        </p>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h4 class="text-white font-semibold mb-4">Menu Cepat</h4>
                        <ul class="space-y-2">
                            <li><a href="{{ route('dashboard') }}" class="hover:text-primary-400 transition">Dashboard</a></li>
                            <li><a href="{{ route('ai-assist.index') }}" class="hover:text-primary-400 transition">AI Assist</a></li>
                            <li><a href="{{ route('map.index') }}" class="hover:text-primary-400 transition">Peta Bencana</a></li>
                            <li><a href="{{ route('chat-history.index') }}" class="hover:text-primary-400 transition">Riwayat Chat</a></li>
                        </ul>
                    </div>

                    <!-- Support -->
                    <div>
                        <h4 class="text-white font-semibold mb-4">Bantuan</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="hover:text-primary-400 transition">Panduan Penggunaan</a></li>
                            <li><a href="#" class="hover:text-primary-400 transition">FAQ</a></li>
                            <li><a href="#" class="hover:text-primary-400 transition">Hubungi Kami</a></li>
                            <li><a href="#" class="hover:text-primary-400 transition">Kebijakan Privasi</a></li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-slate-800 pt-8 text-center">
                    <p class="text-slate-500 text-sm">
                        &copy; {{ date('Y') }} ResQ. Hak Cipta Dilindungi.
                    </p>
                </div>
            </div>
        </footer>

        <!-- Mobile Menu Script -->
        <script>
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');

            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });

            // Close menu when clicking links
            mobileMenu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.add('hidden');
                });
            });
        </script>
    </body>
</html>
