<x-app-layout>
    <div class="bg-white dark:bg-gray-900 min-h-screen">
        <!-- Desktop/Tablet: With top navigation padding -->
        <div class="hidden sm:block pt-16">
            <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">Artikel</h1>
                    <p class="text-gray-600 dark:text-gray-400">Pengetahuan edukatif dan wawasan bencana alam</p>
                </div>

                <!-- Search and Filters -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 mb-8">
                    <form method="GET" class="space-y-4">
                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cari Artikel</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan judul atau konten..." class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-red-600" />
                        </div>

                        <!-- Category Filter -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kategori</label>
                                <select name="category" class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-600">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                                            {{ ucfirst($category) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-end">
                                <button type="submit" class="w-full px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200 font-medium">
                                    Cari
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Articles Grid -->
                @if($articles->count())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        @foreach($articles as $article)
                            @include('partials.article-card', ['article' => $article])
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-12 text-center mb-8">
                        <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.248 6.248 2 10.248 2 15s4.248 8.75 10 8.75c5.378 0 9.601-3.894 10-8.75" />
                        </svg>
                        <p class="text-gray-600 dark:text-gray-400 text-lg">Tidak ada artikel yang sesuai dengan pencarian Anda.</p>
                    </div>
                @endif

                <!-- Pagination -->
                @if($articles->hasPages())
                    <div class="flex justify-center">
                        {{ $articles->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Mobile Version -->
        <div class="sm:hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-red-600 to-orange-500 text-white p-6 pb-4">
                <h1 class="text-2xl font-bold mb-2">Artikel</h1>
                <p class="text-white/90 text-sm">Pengetahuan dan tips tentang bencana alam</p>
            </div>

            <!-- Search and Filters -->
            <div class="bg-gray-50 dark:bg-gray-800 p-4 border-b border-gray-200 dark:border-gray-700">
                <form method="GET" class="space-y-3">
                    <!-- Search -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Cari Artikel</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari artikel..." class="w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-red-600" />
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Kategori</label>
                        <select name="category" class="w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                                    {{ ucfirst($category) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        Cari
                    </button>
                </form>
            </div>

            <!-- Articles List (vertical scroll) -->
            <div class="space-y-3 p-4 pb-24">
                @if($articles->count())
                    @foreach($articles as $article)
                        @include('partials.article-card', ['article' => $article])
                    @endforeach
                @else
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-8 text-center">
                        <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.248 6.248 2 10.248 2 15s4.248 8.75 10 8.75c5.378 0 9.601-3.894 10-8.75" />
                        </svg>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">Tidak ada artikel ditemukan.</p>
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            @if($articles->hasPages())
                <div class="p-4">
                    {{ $articles->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
