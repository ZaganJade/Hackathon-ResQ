<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

        <title>{{ config('app.name', 'ResQ') }} — Masuk</title>

        <!-- Fonts: Inter (same as landing page) -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-200 antialiased bg-slate-950">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
            <!-- Decorative background elements -->
            <div class="fixed inset-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-1/2 -right-1/2 w-full h-full bg-emerald-500/10 rounded-full opacity-30 blur-3xl"></div>
                <div class="absolute -bottom-1/2 -left-1/2 w-full h-full bg-green-500/10 rounded-full opacity-30 blur-3xl"></div>
            </div>

            <div class="relative z-10 animate-scale-in">
                <a href="/" class="flex flex-col items-center gap-2">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <span class="text-2xl font-bold text-white">ResQ</span>
                </a>
            </div>

            <div class="relative z-10 w-full sm:max-w-md mt-8 animate-fade-up stagger-2">
                <div class="bg-slate-900 shadow-2xl border border-white/5 p-8 rounded-3xl backdrop-blur-xl">
                    {{ $slot }}
                </div>
            </div>

            <!-- Footer -->
            <p class="relative z-10 mt-8 text-sm text-slate-600 opacity-0-start animate-fade-in delay-500">
                &copy; {{ date('Y') }} ResQ — Sistem Mitigasi Bencana Berbasis AI
            </p>
        </div>
    </body>
</html>
