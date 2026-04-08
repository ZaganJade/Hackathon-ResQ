<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ResQ') }}</title>

        <!-- Fonts: Poppins (300,400,500,600,700) -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50">
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
    </body>
</html>
