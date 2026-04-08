<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-2xl text-gray-900 leading-tight flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                    {{ __('Peta Bencana') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    {{ __('Pantau lokasi bencana di seluruh Indonesia secara real-time') }}
                </p>
            </div>
            <div class="flex items-center gap-3 text-sm">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800" id="connection-status">
                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                    {{ __('Live') }}
                </span>
                <span class="text-gray-500" id="last-updated">
                    {{ __('Terakhir diperbarui:') }} <span id="last-updated-time" class="font-medium">-</span>
                </span>
            </div>
        </div>
    </x-slot>

    <div class="flex flex-col lg:flex-row h-[calc(100vh-180px)] min-h-[600px] bg-gray-50">
        <!-- Sidebar Filters Panel -->
        <div class="w-full lg:w-80 bg-white border-r border-gray-200 overflow-y-auto flex-shrink-0 shadow-sm">
            <!-- Filters Header -->
            <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white">
                <h3 class="font-semibold text-lg flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                    {{ __('Panel Filter') }}
                </h3>
                <p class="text-indigo-100 text-xs mt-1">{{ __('Sesuaikan tampilan peta bencana') }}</p>
            </div>

            <div class="p-6 space-y-6">
                <!-- Location Search -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <label for="location-search" class="block text-sm font-semibold text-gray-800 mb-2 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ __('Cari Lokasi') }}
                    </label>
                    <div class="flex gap-2">
                        <input
                            type="text"
                            id="location-search"
                            placeholder="Contoh: Jakarta, Bandung..."
                            class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm bg-white"
                        >
                        <button
                            id="search-btn"
                            class="px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 text-sm transition-colors duration-200 shadow-sm"
                            title="{{ __('Cari') }}"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                    <p id="search-error" class="text-red-500 text-xs mt-2 hidden flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span></span>
                    </p>
                </div>

                <!-- Radius Filter -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <label class="block text-sm font-semibold text-gray-800 mb-2 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ __('Radius Pencarian') }}
                    </label>
                    <div class="relative">
                        <select id="radius-select" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm bg-white appearance-none pr-8">
                            <option value="25">25 km</option>
                            <option value="50" selected>50 km</option>
                            <option value="100">100 km</option>
                            <option value="200">200 km</option>
                            <option value="500">500 km</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Disaster Type Filters -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <label class="block text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        {{ __('Jenis Bencana') }}
                    </label>
                    <div class="space-y-2 max-h-48 overflow-y-auto pr-1" id="disaster-type-filters">
                        @foreach($disasterTypes as $type)
                            <label class="flex items-center p-2 rounded-md hover:bg-white hover:shadow-sm transition-all duration-200 cursor-pointer group">
                                <input
                                    type="checkbox"
                                    name="disaster_types"
                                    value="{{ $type }}"
                                    checked
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 group-hover:border-indigo-400"
                                >
                                <span class="ml-2 text-sm text-gray-700 capitalize flex items-center gap-2">
                                    @if($type === 'earthquake')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    @elseif($type === 'flood')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                        </svg>
                                    @elseif($type === 'landslide')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                        </svg>
                                    @elseif($type === 'tsunami')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    @elseif($type === 'fire')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                                        </svg>
                                    @elseif($type === 'volcano')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @endif
                                    {{ ucfirst($type) }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                    <div class="mt-3 flex gap-2 text-xs">
                        <button id="select-all-types" class="text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
                            {{ __('Pilih Semua') }}
                        </button>
                        <span class="text-gray-300">|</span>
                        <button id="deselect-all-types" class="text-gray-500 hover:text-gray-700 font-medium transition-colors">
                            {{ __('Batal Pilih') }}
                        </button>
                    </div>
                </div>

                <!-- Severity Filter -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <label class="block text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        {{ __('Tingkat Keparahan') }}
                    </label>
                    <div class="space-y-2">
                        <label class="flex items-center p-2 rounded-md hover:bg-white hover:shadow-sm transition-all duration-200 cursor-pointer group">
                            <input type="checkbox" name="severity" value="critical" checked class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 group-hover:border-red-400">
                            <span class="ml-2 text-sm text-gray-700 flex items-center flex-1">
                                <span class="w-3 h-3 rounded-full bg-red-600 mr-2 shadow-sm ring-2 ring-red-100"></span>
                                <span class="font-medium">{{ __('Kritis/Tinggi') }}</span>
                            </span>
                        </label>
                        <label class="flex items-center p-2 rounded-md hover:bg-white hover:shadow-sm transition-all duration-200 cursor-pointer group">
                            <input type="checkbox" name="severity" value="medium" checked class="rounded border-gray-300 text-amber-500 shadow-sm focus:ring-amber-500 group-hover:border-amber-400">
                            <span class="ml-2 text-sm text-gray-700 flex items-center flex-1">
                                <span class="w-3 h-3 rounded-full bg-amber-500 mr-2 shadow-sm ring-2 ring-amber-100"></span>
                                <span class="font-medium">{{ __('Sedang') }}</span>
                            </span>
                        </label>
                        <label class="flex items-center p-2 rounded-md hover:bg-white hover:shadow-sm transition-all duration-200 cursor-pointer group">
                            <input type="checkbox" name="severity" value="low" checked class="rounded border-gray-300 text-emerald-500 shadow-sm focus:ring-emerald-500 group-hover:border-emerald-400">
                            <span class="ml-2 text-sm text-gray-700 flex items-center flex-1">
                                <span class="w-3 h-3 rounded-full bg-emerald-500 mr-2 shadow-sm ring-2 ring-emerald-100"></span>
                                <span class="font-medium">{{ __('Rendah') }}</span>
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Date Range -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <label class="block text-sm font-semibold text-gray-800 mb-2 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ __('Rentang Waktu') }}
                    </label>
                    <div class="space-y-2">
                        <div class="relative">
                            <input
                                type="date"
                                id="date-from"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm bg-white"
                            >
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 pointer-events-none">Dari</span>
                        </div>
                        <div class="relative">
                            <input
                                type="date"
                                id="date-to"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm bg-white"
                            >
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 pointer-events-none">Sampai</span>
                        </div>
                    </div>
                </div>

                <!-- Stats Card -->
                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-lg p-4 border border-indigo-100">
                    <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        {{ __('Statistik') }}
                    </h4>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-white rounded-lg p-3 text-center shadow-sm">
                            <div class="text-2xl font-bold text-gray-900" id="total-disasters">0</div>
                            <div class="text-xs text-gray-500">{{ __('Total Bencana') }}</div>
                        </div>
                        <div class="bg-white rounded-lg p-3 text-center shadow-sm">
                            <div class="text-2xl font-bold text-indigo-600" id="visible-disasters">0</div>
                            <div class="text-xs text-gray-500">{{ __('Ditampilkan') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Reset Button -->
                <button
                    id="reset-filters"
                    class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center gap-2 shadow-sm"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    {{ __('Reset Filter') }}
                </button>
            </div>
        </div>

        <!-- Map Container -->
        <div class="flex-1 relative bg-gray-100">
            <div id="map" class="w-full h-full"></div>

            <!-- Loading Overlay -->
            <div id="map-loading" class="absolute inset-0 bg-white/90 backdrop-blur-sm flex items-center justify-center z-20 hidden">
                <div class="flex flex-col items-center bg-white rounded-xl shadow-lg px-8 py-6">
                    <div class="relative">
                        <div class="absolute inset-0 bg-indigo-500/20 rounded-full animate-ping"></div>
                        <svg class="animate-spin h-10 w-10 text-indigo-600 relative" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <span class="mt-4 text-sm font-medium text-gray-700">{{ __('Memuat data bencana...') }}</span>
                </div>
            </div>

            <!-- Auto-refresh Toggle -->
            <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-sm rounded-xl shadow-lg p-4 z-[5] border border-gray-100">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" id="auto-refresh" checked class="sr-only peer">
                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    <div class="ml-3 flex flex-col">
                        <span class="text-sm font-medium text-gray-700">{{ __('Auto-refresh') }}</span>
                        <span class="text-xs text-gray-500">{{ __('Perbarui otomatis setiap 5 menit') }}</span>
                    </div>
                </label>
            </div>

            <!-- Legend -->
            <div class="absolute bottom-4 left-4 bg-white/95 backdrop-blur-sm rounded-xl shadow-lg p-4 z-[5] border border-gray-100">
                <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                    {{ __('Keterangan') }}
                </h4>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center bg-red-50 rounded-lg px-3 py-2">
                        <span class="w-4 h-4 rounded-full bg-red-600 mr-3 shadow-sm ring-2 ring-red-200"></span>
                        <span class="font-medium text-gray-700">{{ __('Keparahan Tinggi/Kritis') }}</span>
                    </div>
                    <div class="flex items-center bg-amber-50 rounded-lg px-3 py-2">
                        <span class="w-4 h-4 rounded-full bg-amber-500 mr-3 shadow-sm ring-2 ring-amber-200"></span>
                        <span class="font-medium text-gray-700">{{ __('Keparahan Sedang') }}</span>
                    </div>
                    <div class="flex items-center bg-emerald-50 rounded-lg px-3 py-2">
                        <span class="w-4 h-4 rounded-full bg-emerald-500 mr-3 shadow-sm ring-2 ring-emerald-200"></span>
                        <span class="font-medium text-gray-700">{{ __('Keparahan Rendah') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let map;
        let markers = [];
        let markerCluster;
        let infoWindow;
        let autoRefreshInterval;
        let searchLocationMarker = null;
        let searchRadiusCircle = null;

        // Center of Indonesia
        const INDONESIA_CENTER = { lat: -2.5489, lng: 118.0149 };

        // Severity colors
        const SEVERITY_COLORS = {
            critical: '#DC2626',
            high: '#DC2626',
            medium: '#F59E0B',
            low: '#10B981',
        };

        // Initialize map
        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: INDONESIA_CENTER,
                zoom: 5,
                minZoom: 4,
                maxZoom: 18,
                mapTypeControl: true,
                streetViewControl: false,
                fullscreenControl: true,
                zoomControl: true,
            });

            infoWindow = new google.maps.InfoWindow();

            // Load initial disaster data
            loadDisasters();

            // Start auto-refresh
            startAutoRefresh();
        }

        // Load disasters from API
        async function loadDisasters() {
            showLoading();

            try {
                const params = buildQueryParams();
                const response = await fetch(`/api/disasters?${params}`);
                const data = await response.json();

                if (data.features) {
                    updateMarkers(data.features);
                    updateStats(data.meta.total);
                    updateLastUpdated();
                }
            } catch (error) {
                console.error('Error loading disasters:', error);
            } finally {
                hideLoading();
            }
        }

        // Build query params from filters
        function buildQueryParams() {
            const params = new URLSearchParams();

            // Disaster types
            const selectedTypes = Array.from(document.querySelectorAll('input[name="disaster_types"]:checked'))
                .map(cb => cb.value);
            if (selectedTypes.length > 0) {
                selectedTypes.forEach(type => params.append('types[]', type));
            }

            // Severity (use the most severe selected, or all)
            const selectedSeverities = Array.from(document.querySelectorAll('input[name="severity"]:checked'))
                .map(cb => cb.value);

            // Date range
            const dateFrom = document.getElementById('date-from').value;
            const dateTo = document.getElementById('date-to').value;
            if (dateFrom) params.append('date_from', dateFrom);
            if (dateTo) params.append('date_to', dateTo);

            return params.toString();
        }

        // Update markers on map
        function updateMarkers(features) {
            // Clear existing markers
            markers.forEach(marker => marker.setMap(null));
            markers = [];

            // Filter by severity client-side (since API may return multiple severities)
            const selectedSeverities = Array.from(document.querySelectorAll('input[name="severity"]:checked'))
                .map(cb => cb.value);

            const filteredFeatures = features.filter(feature =>
                selectedSeverities.includes(feature.properties.severity)
            );

            // Create new markers
            filteredFeatures.forEach(feature => {
                const marker = createMarker(feature);
                markers.push(marker);
            });

            // Update marker cluster
            if (markerCluster) {
                markerCluster.clearMarkers();
            }

            markerCluster = new markerClusterer.MarkerClusterer({
                markers,
                map,
            });

            // Update visible count
            document.getElementById('visible-disasters').textContent = filteredFeatures.length;
        }

        // Create a single marker
        function createMarker(feature) {
            const props = feature.properties;
            const [lng, lat] = feature.geometry.coordinates;

            const marker = new google.maps.Marker({
                position: { lat, lng },
                map: map,
                title: `${props.type} - ${props.location}`,
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    fillColor: props.color,
                    fillOpacity: 0.9,
                    strokeColor: '#ffffff',
                    strokeWeight: 2,
                    scale: 10,
                },
            });

            marker.addListener('click', () => {
                showInfoWindow(marker, props);
            });

            return marker;
        }

        // Show info window
        function showInfoWindow(marker, props) {
            const severityClass = {
                critical: 'text-red-600 font-bold',
                high: 'text-red-600 font-bold',
                medium: 'text-amber-500 font-semibold',
                low: 'text-emerald-500',
            }[props.severity] || 'text-gray-600';

            const content = `
                <div class="p-2 max-w-xs">
                    <h3 class="font-bold text-lg text-gray-800 capitalize mb-1">${props.type}</h3>
                    <p class="text-sm text-gray-600 mb-2">${props.location}</p>
                    <div class="space-y-1 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Keparahan:</span>
                            <span class="${severityClass} capitalize">${props.severity}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Status:</span>
                            <span class="capitalize">${props.status}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Waktu:</span>
                            <span>${new Date(props.created_at).toLocaleString('id-ID')}</span>
                        </div>
                    </div>
                    ${props.description ? `<p class="mt-2 text-sm text-gray-600">${props.description.substring(0, 100)}...</p>` : ''}
                </div>
            `;

            infoWindow.setContent(content);
            infoWindow.open(map, marker);
        }

        // Search location
        async function searchLocation() {
            const location = document.getElementById('location-search').value.trim();
            if (!location) return;

            showLoading();
            document.getElementById('search-error').classList.add('hidden');

            try {
                const response = await fetch(`/api/geocode?location=${encodeURIComponent(location)}`);

                if (!response.ok) {
                    throw new Error('Location not found');
                }

                const data = await response.json();

                if (data.location) {
                    const { lat, lng } = data.location;

                    // Clear previous search markers
                    if (searchLocationMarker) searchLocationMarker.setMap(null);
                    if (searchRadiusCircle) searchRadiusCircle.setMap(null);

                    // Add marker for searched location
                    searchLocationMarker = new google.maps.Marker({
                        position: { lat, lng },
                        map: map,
                        title: data.location.formatted_address,
                        icon: {
                            path: google.maps.SymbolPath.CIRCLE,
                            fillColor: '#4F46E5',
                            fillOpacity: 0.3,
                            strokeColor: '#4F46E5',
                            strokeWeight: 2,
                            scale: 15,
                        },
                    });

                    // Get selected radius
                    const radius = parseInt(document.getElementById('radius-select').value);

                    // Add radius circle
                    searchRadiusCircle = new google.maps.Circle({
                        map: map,
                        center: { lat, lng },
                        radius: radius * 1000, // Convert to meters
                        fillColor: '#4F46E5',
                        fillOpacity: 0.1,
                        strokeColor: '#4F46E5',
                        strokeOpacity: 0.5,
                        strokeWeight: 2,
                    });

                    // Pan to location with appropriate zoom
                    map.setCenter({ lat, lng });
                    map.setZoom(10);

                    // Reload disasters with location filter
                    loadDisastersWithLocation(lat, lng, radius);
                }
            } catch (error) {
                document.getElementById('search-error').textContent = 'Lokasi tidak ditemukan';
                document.getElementById('search-error').classList.remove('hidden');
            } finally {
                hideLoading();
            }
        }

        // Load disasters with location filter
        async function loadDisastersWithLocation(lat, lng, radius) {
            showLoading();

            try {
                const params = buildQueryParams();
                const response = await fetch(`/api/disasters?${params}&lat=${lat}&lng=${lng}&radius=${radius}`);
                const data = await response.json();

                if (data.features) {
                    updateMarkers(data.features);
                    updateStats(data.meta.total);
                }
            } catch (error) {
                console.error('Error loading disasters:', error);
            } finally {
                hideLoading();
            }
        }

        // Update statistics
        async function updateStats(visibleCount) {
            try {
                const response = await fetch('/api/disasters/stats');
                const data = await response.json();

                document.getElementById('total-disasters').textContent = data.total;
                document.getElementById('visible-disasters').textContent = visibleCount;
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        // Update last updated time
        function updateLastUpdated() {
            const now = new Date();
            document.getElementById('last-updated-time').textContent = now.toLocaleString('id-ID');
        }

        // Auto-refresh
        function startAutoRefresh() {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
            }

            autoRefreshInterval = setInterval(() => {
                const isEnabled = document.getElementById('auto-refresh').checked;
                if (isEnabled) {
                    loadDisasters();
                }
            }, 5 * 60 * 1000); // 5 minutes
        }

        // Show/hide loading
        function showLoading() {
            document.getElementById('map-loading').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('map-loading').classList.add('hidden');
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', () => {
            // Filter change handlers
            document.querySelectorAll('input[name="disaster_types"]').forEach(cb => {
                cb.addEventListener('change', loadDisasters);
            });

            document.querySelectorAll('input[name="severity"]').forEach(cb => {
                cb.addEventListener('change', loadDisasters);
            });

            document.getElementById('date-from').addEventListener('change', loadDisasters);
            document.getElementById('date-to').addEventListener('change', loadDisasters);

            // Search button
            document.getElementById('search-btn').addEventListener('click', searchLocation);
            document.getElementById('location-search').addEventListener('keypress', (e) => {
                if (e.key === 'Enter') searchLocation();
            });

            // Radius change
            document.getElementById('radius-select').addEventListener('change', () => {
                const location = document.getElementById('location-search').value.trim();
                if (location) {
                    searchLocation();
                }
            });

            // Select all / Deselect all
            document.getElementById('select-all-types').addEventListener('click', () => {
                document.querySelectorAll('input[name="disaster_types"]').forEach(cb => cb.checked = true);
                loadDisasters();
            });

            document.getElementById('deselect-all-types').addEventListener('click', () => {
                document.querySelectorAll('input[name="disaster_types"]').forEach(cb => cb.checked = false);
                loadDisasters();
            });

            // Reset filters
            document.getElementById('reset-filters').addEventListener('click', () => {
                document.querySelectorAll('input[name="disaster_types"]').forEach(cb => cb.checked = true);
                document.querySelectorAll('input[name="severity"]').forEach(cb => cb.checked = true);
                document.getElementById('date-from').value = '';
                document.getElementById('date-to').value = '';
                document.getElementById('location-search').value = '';
                document.getElementById('radius-select').value = '50';

                // Clear search markers
                if (searchLocationMarker) searchLocationMarker.setMap(null);
                if (searchRadiusCircle) searchRadiusCircle.setMap(null);

                map.setCenter(INDONESIA_CENTER);
                map.setZoom(5);

                loadDisasters();
            });

            // Auto-refresh toggle
            document.getElementById('auto-refresh').addEventListener('change', (e) => {
                if (e.target.checked) {
                    startAutoRefresh();
                }
            });
        });
    </script>

    <!-- Google Maps API with MarkerClusterer -->
    <script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsApiKey }}&callback=initMap">
    </script>
    @endpush
</x-app-layout>
