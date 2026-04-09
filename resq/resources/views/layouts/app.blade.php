<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Cache control: allow assets to be cached --}}
        <meta http-equiv="Cache-Control" content="public, max-age=3600">

        {{-- Favicon with multiple formats for best compatibility --}}
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}?v=2">
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon.png') }}?v=2">

        <title>{{ config('app.name', 'ResQ') }}</title>

        <!-- Fonts: Poppins (300,400,500,600,700) -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-slate-950">
        <div class="min-h-screen flex flex-col pb-16 lg:pb-0">
            <!-- Page Content -->
            <main class="flex-1 page-transition">
                {{ $slot }}
            </main>

            <!-- Mobile Bottom Navigation Component (reusable) -->
            <x-mobile-bottom-nav />
        </div>

        <!-- Global Footer -->
        <footer class="bg-slate-900 border-t border-white/5 py-4 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-sm text-slate-400">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span class="font-semibold text-white">ResQ</span>
                        <span class="hidden sm:inline">|</span>
                        <span>&copy; {{ date('Y') }} Team ResQ. Hak Cipta Dilindungi.</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-xs">Siap hadapi bencana alam sebelum, sesaat, dan sesudah.<br> dengan ResQ panduan selamat yang selalu ada di genggaman.</span>
                    </div>
                </div>
            </div>
        </footer>

        <!-- AI Chatbot Floating Widget -->
        @auth
            <x-ai-chatbot />
        @endauth

        @stack('scripts')
    </body>
</html>
