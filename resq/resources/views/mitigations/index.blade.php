<x-app-layout>
    <div class="bg-white dark:bg-gray-900 min-h-screen">
        <!-- Desktop/Tablet: With top navigation padding -->
        <div class="hidden sm:block pt-16">
            <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">Panduan Mitigasi</h1>
                    <p class="text-gray-600 dark:text-gray-400">Panduan langkah demi langkah untuk keselamatan dan persiapan bencana</p>
                </div>

                <!-- Category Tabs Filter -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 mb-8 overflow-x-auto">
                    <form method="GET" class="flex gap-2">
                        <a href="{{ route('mitigations.index') }}" class="px-4 py-2 rounded-lg font-medium transition-colors duration-200 {{ !request('category') ? 'bg-orange-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600' }}">
                            Semua Panduan
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('mitigations.index', ['category' => $category]) }}" class="px-4 py-2 rounded-lg font-medium transition-colors duration-200 whitespace-nowrap {{ request('category') === $category ? 'bg-orange-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600' }}">
                                {{ ucfirst($category) }}
                            </a>
                        @endforeach
                    </form>
                </div>

                <!-- Guides Grid -->
                @if($guides->count())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        @foreach($guides as $guide)
                            @include('partials.guide-card', ['guide' => $guide])
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-12 text-center mb-8">
                        <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-600 dark:text-gray-400 text-lg">Tidak ada panduan dalam kategori ini.</p>
                    </div>
                @endif

                <!-- Pagination -->
                @if($guides->hasPages())
                    <div class="flex justify-center">
                        {{ $guides->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Mobile Version -->
        <div class="sm:hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-orange-500 to-red-600 text-white p-6 pb-4">
                <h1 class="text-2xl font-bold mb-2">Panduan Mitigasi</h1>
                <p class="text-white/90 text-sm">Persiapan dan keselamatan bencana alam</p>
            </div>

            <!-- Category Tabs Filter - Horizontal Scroll -->
            <div class="bg-gray-50 dark:bg-gray-800 p-3 border-b border-gray-200 dark:border-gray-700 overflow-x-auto">
                <div class="flex gap-2">
                    <a href="{{ route('mitigations.index') }}" class="px-4 py-2 rounded-lg font-medium text-sm transition-colors duration-200 whitespace-nowrap {{ !request('category') ? 'bg-orange-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                        Semua
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('mitigations.index', ['category' => $category]) }}" class="px-4 py-2 rounded-lg font-medium text-sm transition-colors duration-200 whitespace-nowrap {{ request('category') === $category ? 'bg-orange-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                            {{ ucfirst($category) }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Guides List (vertical scroll) -->
            <div class="space-y-3 p-4 pb-24">
                @if($guides->count())
                    @foreach($guides as $guide)
                        @include('partials.guide-card', ['guide' => $guide])
                    @endforeach
                @else
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-8 text-center">
                        <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">Tidak ada panduan ditemukan.</p>
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            @if($guides->hasPages())
                <div class="p-4">
                    {{ $guides->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
