<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ResQ') }} - Sistem Mitigasi Bencana</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-white dark:bg-gray-900">
        <!-- Navigation -->
        <nav class="fixed w-full z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-100 dark:border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="/" class="flex items-center space-x-2">
                            <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <span class="text-xl font-bold text-gray-900 dark:text-white">ResQ</span>
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="#features" class="text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 transition">Fitur</a>
                        <a href="#how-it-works" class="text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 transition">Cara Kerja</a>
                        <a href="#about" class="text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 transition">Tentang</a>
                    </div>

                    <!-- Auth Buttons -->
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="hidden md:inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                Daftar
                            </a>
                        @endauth

                        <!-- Mobile menu button -->
                        <button id="mobile-menu-btn" class="md:hidden p-2 text-gray-600 dark:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile Menu -->
                <div id="mobile-menu" class="hidden md:hidden py-4 border-t border-gray-100 dark:border-gray-800">
                    <div class="flex flex-col space-y-3">
                        <a href="#features" class="text-gray-600 dark:text-gray-300 hover:text-red-600">Fitur</a>
                        <a href="#how-it-works" class="text-gray-600 dark:text-gray-300 hover:text-red-600">Cara Kerja</a>
                        <a href="#about" class="text-gray-600 dark:text-gray-300 hover:text-red-600">Tentang</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative pt-32 pb-20 lg:pt-40 lg:pb-32 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-red-50 via-white to-orange-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800 -z-10"></div>
            <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-red-100/50 to-transparent dark:from-red-900/20 -z-10 blur-3xl"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-4xl mx-auto">
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-sm font-medium mb-6">
                        <span class="w-2 h-2 bg-red-600 rounded-full mr-2 animate-pulse"></span>
                        Sistem Mitigasi Bencana Berbasis AI
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">
                        Siap Tanggap, <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-orange-600">Selamat Bersama</span>
                    </h1>
                    <p class="text-lg md:text-xl text-gray-600 dark:text-gray-300 mb-10 max-w-2xl mx-auto">
                        ResQ adalah platform mitigasi bencana yang menggabungkan kecerdasan buatan, peta interaktif, dan informasi real-time untuk membantu masyarakat menghadapi dan mengatasi bencana alam.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-8 py-4 bg-red-600 text-white rounded-xl hover:bg-red-700 transition shadow-lg shadow-red-600/25">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Mulai Sekarang
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 bg-red-600 text-white rounded-xl hover:bg-red-700 transition shadow-lg shadow-red-600/25">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Mulai Sekarang
                            </a>
                        @endauth
                        <a href="#features" class="inline-flex items-center px-8 py-4 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition border border-gray-200 dark:border-gray-700">
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
        <section class="py-12 bg-white dark:bg-gray-900 border-y border-gray-100 dark:border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-red-600 mb-1">AI</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Asisten Pintar</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-orange-600 mb-1">24/7</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Monitoring Aktif</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-yellow-600 mb-1">Real-time</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Update Peta Bencana</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-green-600 mb-1">100%</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Informasi Terpercaya</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 bg-gray-50 dark:bg-gray-800/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">Fitur Unggulan</h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                        ResQ hadir dengan berbagai fitur untuk membantu Anda menghadapi situasi darurat dengan lebih siap dan tenang.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- AI Assist -->
                    <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-lg hover:shadow-xl transition border border-gray-100 dark:border-gray-700">
                        <div class="w-14 h-14 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">AI Assist</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            Asisten AI yang siap membantu memberikan informasi, panduan darurat, dan jawaban atas pertanyaan terkait bencana.
                        </p>
                    </div>

                    <!-- Disaster Map -->
                    <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-lg hover:shadow-xl transition border border-gray-100 dark:border-gray-700">
                        <div class="w-14 h-14 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Peta Bencana</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            Pantau lokasi bencana real-time dengan peta interaktif lengkap dengan filter jenis dan level bencana.
                        </p>
                    </div>

                    <!-- Articles -->
                    <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-lg hover:shadow-xl transition border border-gray-100 dark:border-gray-700">
                        <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Artikel Mitigasi</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            Akses artikel terkini tentang mitigasi bencana, tips persiapan, dan informasi penting lainnya.
                        </p>
                    </div>

                    <!-- Guides -->
                    <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-lg hover:shadow-xl transition border border-gray-100 dark:border-gray-700">
                        <div class="w-14 h-14 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Panduan Darurat</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            Panduan lengkap cara menghadapi berbagai jenis bencana, mulai dari gempa bumi hingga banjir.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section id="how-it-works" class="py-20 bg-white dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">Cara Kerja ResQ</h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                        Tiga langkah sederhana untuk memanfaatkan ResQ dalam situasi darurat.
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Step 1 -->
                    <div class="relative text-center">
                        <div class="absolute top-8 left-1/2 w-full h-0.5 bg-gray-200 dark:bg-gray-700 -z-10 hidden md:block"></div>
                        <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold shadow-lg shadow-red-600/30">
                            1
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Daftar Akun</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Buat akun ResQ gratis untuk mengakses semua fitur dan menerima notifikasi penting.
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative text-center">
                        <div class="absolute top-8 left-1/2 w-full h-0.5 bg-gray-200 dark:bg-gray-700 -z-10 hidden md:block"></div>
                        <div class="w-16 h-16 bg-orange-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold shadow-lg shadow-orange-600/30">
                            2
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Jelajahi Fitur</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Gunakan AI Assist, pantau peta bencana, dan pelajari panduan darurat yang tersedia.
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative text-center">
                        <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold shadow-lg shadow-green-600/30">
                            3
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Siap Siaga</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Aktifkan notifikasi WhatsApp untuk menerima peringatan dini dan informasi terkini.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 bg-gradient-to-br from-red-600 to-red-700">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Siap Menjadi Lebih Siap?</h2>
                <p class="text-lg text-red-100 mb-10 max-w-2xl mx-auto">
                    Bergabung dengan ResQ sekarang dan dapatkan akses ke sistem mitigasi bencana berbasis AI yang siap membantu Anda 24/7.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-8 py-4 bg-white text-red-600 rounded-xl hover:bg-gray-100 transition shadow-lg">
                            Buka Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 bg-white text-red-600 rounded-xl hover:bg-gray-100 transition shadow-lg">
                            Daftar Gratis
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center px-8 py-4 bg-transparent text-white rounded-xl hover:bg-white/10 transition border border-white/30">
                            Sudah Punya Akun? Masuk
                        </a>
                    @endauth
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="py-20 bg-gray-50 dark:bg-gray-800/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-6">Tentang ResQ</h2>
                        <p class="text-gray-600 dark:text-gray-300 mb-6 leading-relaxed">
                            ResQ adalah platform mitigasi bencana yang dikembangkan untuk membantu masyarakat Indonesia menghadapi tantangan bencana alam. Dengan memanfaatkan teknologi AI, peta interaktif, dan sistem notifikasi real-time, ResQ berkomitmen untuk meningkatkan kesiapsiagaan dan keselamatan masyarakat.
                        </p>
                        <p class="text-gray-600 dark:text-gray-300 mb-8 leading-relaxed">
                            Kami percaya bahwa informasi yang tepat pada waktu yang tepat dapat menyelamatkan nyawa. ResQ hadir sebagai solusi digital yang menjembatani kebutuhan informasi darurat dengan teknologi modern.
                        </p>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center text-gray-600 dark:text-gray-300">
                                <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Informasi Terpercaya
                            </div>
                            <div class="flex items-center text-gray-600 dark:text-gray-300">
                                <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Akses 24/7
                            </div>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-red-600 to-orange-600 rounded-3xl transform rotate-3 opacity-20"></div>
                        <div class="relative bg-white dark:bg-gray-900 rounded-3xl p-8 shadow-xl border border-gray-100 dark:border-gray-700">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-red-50 dark:bg-red-900/20 rounded-2xl p-4 text-center">
                                    <div class="text-3xl font-bold text-red-600 mb-1">AI</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Powered</div>
                                </div>
                                <div class="bg-orange-50 dark:bg-orange-900/20 rounded-2xl p-4 text-center">
                                    <div class="text-3xl font-bold text-orange-600 mb-1">24/7</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Support</div>
                                </div>
                                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-4 text-center">
                                    <div class="text-3xl font-bold text-blue-600 mb-1">Real</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Time</div>
                                </div>
                                <div class="bg-green-50 dark:bg-green-900/20 rounded-2xl p-4 text-center">
                                    <div class="text-3xl font-bold text-green-600 mb-1">Safe</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">First</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-300 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-4 gap-8 mb-8">
                    <!-- Brand -->
                    <div class="col-span-2">
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <span class="text-xl font-bold text-white">ResQ</span>
                        </div>
                        <p class="text-gray-400 mb-4 max-w-md">
                            Sistem Mitigasi Bencana berbasis AI untuk membantu masyarakat Indonesia menghadapi dan mengatasi bencana alam.
                        </p>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h4 class="text-white font-semibold mb-4">Menu Cepat</h4>
                        <ul class="space-y-2">
                            <li><a href="{{ route('dashboard') }}" class="hover:text-red-500 transition">Dashboard</a></li>
                            <li><a href="{{ route('ai-assist.index') }}" class="hover:text-red-500 transition">AI Assist</a></li>
                            <li><a href="#" class="hover:text-red-500 transition">Peta Bencana</a></li>
                            <li><a href="#" class="hover:text-red-500 transition">Artikel</a></li>
                        </ul>
                    </div>

                    <!-- Support -->
                    <div>
                        <h4 class="text-white font-semibold mb-4">Bantuan</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="hover:text-red-500 transition">Panduan Penggunaan</a></li>
                            <li><a href="#" class="hover:text-red-500 transition">FAQ</a></li>
                            <li><a href="#" class="hover:text-red-500 transition">Hubungi Kami</a></li>
                            <li><a href="#" class="hover:text-red-500 transition">Kebijakan Privasi</a></li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-gray-800 pt-8 text-center">
                    <p class="text-gray-500 text-sm">
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
