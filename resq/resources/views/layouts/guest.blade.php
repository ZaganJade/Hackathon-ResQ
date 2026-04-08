<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ResQ') }}</title>

        <!-- Fonts: Poppins -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-800 antialiased bg-slate-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
            <!-- Decorative background elements -->
            <div class="fixed inset-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-1/2 -right-1/2 w-full h-full bg-primary-100 rounded-full opacity-30 blur-3xl"></div>
                <div class="absolute -bottom-1/2 -left-1/2 w-full h-full bg-secondary-100 rounded-full opacity-30 blur-3xl"></div>
            </div>

            <div class="relative z-10 animate-scale-in">
                <a href="/" class="flex flex-col items-center gap-2">
                    <x-application-logo class="w-20 h-20 fill-current text-primary-600" />
                    <span class="text-2xl font-bold text-primary-700">ResQ</span>
                </a>
            </div>

            <div class="relative z-10 w-full sm:max-w-md mt-8 animate-fade-up stagger-2">
                <div class="card p-8">
                    {{ $slot }}
                </div>
            </div>

            <!-- Footer -->
            <p class="relative z-10 mt-8 text-sm text-slate-400 animate-fade-in stagger-3">
                Sistem Informasi Bencana Indonesia
            </p>
        </div>
    </body>
</html>
