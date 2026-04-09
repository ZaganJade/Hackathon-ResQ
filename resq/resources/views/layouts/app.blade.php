<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Anti-cache meta tags for favicon --}}
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="0">

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

        <!-- AI Chatbot Floating Widget -->
        @auth
            <x-ai-chatbot />
        @endauth

        @stack('scripts')
    </body>
</html>
