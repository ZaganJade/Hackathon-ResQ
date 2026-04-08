<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>503 - Pemeliharaan Sistem | ResQ</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-gray-900 to-gray-800 min-h-screen flex items-center justify-center">
        <div class="text-center px-4">
            <!-- Icon -->
            <div class="mb-8">
                <div class="w-32 h-32 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto animate-pulse">
                    <svg class="w-16 h-16 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Error Code -->
            <h1 class="text-8xl font-bold text-gray-700 mb-4">503</h1>

            <!-- Title -->
            <h2 class="text-3xl font-bold text-white mb-4">
                Sedang Dalam Pemeliharaan
            </h2>

            <!-- Description -->
            <p class="text-gray-400 mb-8 max-w-lg mx-auto">
                ResQ sedang melakukan pemeliharaan sistem untuk meningkatkan kualitas layanan. Mohon maaf atas ketidaknyamanannya. Silakan kembali lagi dalam beberapa saat.
            </p>

            <!-- Progress Bar -->
            <div class="max-w-md mx-auto mb-8">
                <div class="h-2 bg-gray-700 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500 rounded-full animate-[shimmer_2s_infinite]" style="width: 70%"></div>
                </div>
                <p class="mt-2 text-sm text-gray-500">Perkiraan selesai: Dalam 15-30 menit</p>
            </div>

            <!-- Contact Info -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6 text-gray-400">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    support@resq.id
                </div>
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    WhatsApp: +62 812-3456-7890
                </div>
            </div>

            <!-- Status Check Button -->
            <div class="mt-8">
                <button onclick="window.location.reload()" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-600/25 cursor-pointer">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Cek Status
                </button>
            </div>
        </div>

        <style>
            @keyframes shimmer {
                0% { opacity: 0.5; }
                50% { opacity: 1; }
                100% { opacity: 0.5; }
            }
        </style>
    </body>
</html>
