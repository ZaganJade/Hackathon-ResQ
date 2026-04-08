<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-white dark:bg-gray-900">
        <div class="min-h-screen flex flex-col pb-20 sm:pb-0">
            <!-- Top Navigation - Hidden on Home, visible on detail pages -->
            @if(!request()->routeIs('home.index'))
                <div class="sticky top-0 z-40">
                    @include('layouts.navigation')
                </div>
            @endif

            <!-- Page Content -->
            <main class="flex-1">
                {{ $slot }}
            </main>

            <!-- Bottom Navigation - Mobile app style -->
            @include('layouts.bottom-navigation')

            <!-- AI Chat Modal -->
            @include('components.ai-chat-modal')
        </div>
    </body>
</html>
