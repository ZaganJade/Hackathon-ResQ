<x-app-layout>
    <!-- Mobile responsive layout -->
    <div class="bg-white dark:bg-gray-900 min-h-screen">
        <!-- Desktop/Tablet: With top navigation padding -->
        <div class="hidden sm:block pt-16">
            <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">Berita Bencana</h1>
                    <p class="text-gray-600 dark:text-gray-400">Breaking news dan peristiwa bencana aktif</p>
                </div>

                <!-- Filters -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 mb-8">
                    <form method="GET" class="flex flex-col sm:flex-row gap-4">
                        <!-- Type Filter -->
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipe Bencana</label>
                            <select name="type" class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-600">
                                <option value="">Semua Tipe</option>
                                @foreach($types as $type)
                                    <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Severity Filter -->
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tingkat Keparahan</label>
                            <select name="severity" class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-600">
                                <option value="">Semua Tingkatan</option>
                                @foreach($severities as $severity)
                                    <option value="{{ $severity }}" {{ request('severity') === $severity ? 'selected' : '' }}>
                                        {{ ucfirst($severity) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-end">
                            <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200 font-medium">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Disasters List -->
                <div class="space-y-4 mb-8">
                    @if($disasters->count())
                        @foreach($disasters as $disaster)
                            @include('partials.disaster-news-item', ['disaster' => $disaster])
                        @endforeach
                    @else
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-12 text-center">
                            <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-600 dark:text-gray-400 text-lg">Tidak ada bencana yang sesuai dengan kriteria Anda.</p>
                        </div>
                    @endif
                </div>

                <!-- Pagination -->
                @if($disasters->hasPages())
                    <div class="flex justify-center">
                        {{ $disasters->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Mobile Version -->
        <div class="sm:hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-red-600 to-orange-500 text-white p-6 pb-4">
                <h1 class="text-2xl font-bold mb-2">Berita Bencana</h1>
                <p class="text-white/90 text-sm">Tetap terinformasi tentang bencana aktif</p>
            </div>

            <!-- Filters -->
            <div class="bg-gray-50 dark:bg-gray-800 p-4 border-b border-gray-200 dark:border-gray-700">
                <form method="GET" class="space-y-3">
                    <!-- Type Filter -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Tipe Bencana</label>
                        <select name="type" class="w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                            <option value="">Semua Tipe</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Severity Filter -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Tingkat Keparahan</label>
                        <select name="severity" class="w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                            <option value="">Semua Tingkatan</option>
                            @foreach($severities as $severity)
                                <option value="{{ $severity }}" {{ request('severity') === $severity ? 'selected' : '' }}>
                                    {{ ucfirst($severity) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        Filter
                    </button>
                </form>
            </div>

            <!-- Disasters List -->
            <div class="space-y-3 p-4 pb-24">
                @if($disasters->count())
                    @foreach($disasters as $disaster)
                        @include('partials.disaster-news-item', ['disaster' => $disaster])
                    @endforeach
                @else
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-8 text-center">
                        <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">Tidak ada bencana ditemukan.</p>
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            @if($disasters->hasPages())
                <div class="p-4">
                    {{ $disasters->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
