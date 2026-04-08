<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>500 - Kesalahan Server | ResQ</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center">
        <div class="text-center px-4">
            <!-- Icon -->
            <div class="mb-8">
                <div class="w-32 h-32 bg-orange-100 dark:bg-orange-900/20 rounded-full flex items-center justify-center mx-auto">
                    <svg class="w-16 h-16 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>

            <!-- Error Code -->
            <h1 class="text-8xl font-bold text-gray-200 dark:text-gray-800 mb-4">500</h1>

            <!-- Title -->
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                Kesalahan Server Internal
            </h2>

            <!-- Description -->
            <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                Maaf, terjadi kesalahan pada server kami. Tim teknis kami telah diberitahu dan sedang bekerja untuk memperbaiki masalah ini.
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <button onclick="window.location.reload()" class="inline-flex items-center px-6 py-3 bg-orange-600 text-white rounded-xl hover:bg-orange-700 transition shadow-lg shadow-orange-600/25 cursor-pointer">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Coba Lagi
                </button>

                <a href="{{ url('/') }}" class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition border border-gray-200 dark:border-gray-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>

            <!-- Help Text -->
            <div class="mt-8 p-4 bg-gray-100 dark:bg-gray-800 rounded-lg max-w-md mx-auto">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    <strong>ID Error:</strong> {{ time() }}<br>
                    Jika masalah berlanjut, silakan hubungi kami dengan menyertakan ID error di atas.
                </p>
            </div>
        </div>
    </body>
</html>
