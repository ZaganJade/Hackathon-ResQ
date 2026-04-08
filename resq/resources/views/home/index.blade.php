<x-app-layout>
    <!-- Mobile App Style Home Page -->
    <div class="bg-white dark:bg-gray-900 min-h-screen">
        <!-- Desktop Version (pt-16 for fixed nav) -->
        <div class="hidden sm:block pt-16">
            <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Grid layout for desktop -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    <!-- Latest Articles -->
                    <div>
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Artikel Terbaru</h2>
                        </div>
                        @if($articles->count())
                            <div class="space-y-4">
                                @foreach($articles->take(3) as $article)
                                    @include('partials.article-card', ['article' => $article])
                                @endforeach
                            </div>
                            <a href="{{ route('articles.index') }}" class="mt-4 inline-block text-red-600 hover:text-red-700 font-medium">
                                Lihat Semua →
                            </a>
                        @else
                            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-8 text-center">
                                <p class="text-gray-600 dark:text-gray-400">No articles available yet.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Mitigation Guides -->
                    <div>
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Panduan Mitigasi</h2>
                        </div>
                        @if($guides->count())
                            <div class="space-y-4">
                                @foreach($guides->take(3) as $guide)
                                    @include('partials.guide-card', ['guide' => $guide])
                                @endforeach
                            </div>
                            <a href="{{ route('mitigations.index') }}" class="mt-4 inline-block text-orange-600 hover:text-orange-700 font-medium">
                                Lihat Semua →
                            </a>
                        @else
                            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-8 text-center">
                                <p class="text-gray-600 dark:text-gray-400">No mitigation guides available yet.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Active Disasters / News -->
                    <div>
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Berita Terkini</h2>
                        </div>
                        @if($disasters->count())
                            <div class="space-y-4">
                                @foreach($disasters->take(3) as $disaster)
                                    @include('partials.disaster-news-item', ['disaster' => $disaster])
                                @endforeach
                            </div>
                            <a href="{{ route('news.index') }}" class="mt-4 inline-block text-red-600 hover:text-red-700 font-medium">
                                Lihat Semua →
                            </a>
                        @else
                            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-8 text-center">
                                <p class="text-gray-600 dark:text-gray-400">No active disasters reported.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Version -->
        <div class="sm:hidden">
            <!-- Header with Logo -->
            <div class="bg-gradient-to-r from-red-600 to-orange-500 text-white p-6 pt-8">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-2xl font-bold">ResQ</h1>
                    <span class="text-sm font-medium opacity-90">Disaster Mitigation</span>
                </div>
                <p class="text-white/90 text-sm">Stay informed, Stay prepared</p>
            </div>

            <!-- Scrollable Sections -->
            <div class="px-4 py-6 space-y-8">
                <!-- Section 1: List Bencana Alam (Mitigation) - Show Latest -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">List Bencana Alam</h2>
                        <a href="{{ route('mitigations.index') }}" class="text-orange-600 dark:text-orange-500 hover:text-orange-700 text-sm font-medium">
                            Lihat Semua
                        </a>
                    </div>
                    
                    @if($guides->count())
                        <!-- Show only the latest guide in full width -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-100 dark:border-gray-700 mb-4">
                            @foreach($guides->take(1) as $guide)
                                @include('partials.guide-card', ['guide' => $guide])
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 text-center">
                            <p class="text-gray-600 dark:text-gray-400 text-sm">No guides available yet.</p>
                        </div>
                    @endif
                </div>

                <!-- Section 2: Berita Terkini (News) - Horizontal Scroll -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Berita Terkini</h2>
                        <a href="{{ route('news.index') }}" class="text-red-600 dark:text-red-500 hover:text-red-700 text-sm font-medium">
                            Lihat Semua
                        </a>
                    </div>
                    
                    @if($disasters->count())
                        <div class="flex gap-3 overflow-x-auto pb-2 -mx-4 px-4 snap-x snap-mandatory">
                            @foreach($disasters as $disaster)
                                <div class="flex-shrink-0 w-72 snap-center">
                                    @include('partials.disaster-news-item', ['disaster' => $disaster])
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 text-center">
                            <p class="text-gray-600 dark:text-gray-400 text-sm">No news available yet.</p>
                        </div>
                    @endif
                </div>

                <!-- Section 3: Artikel (Articles) - Horizontal Scroll -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Artikel</h2>
                        <a href="{{ route('articles.index') }}" class="text-red-600 dark:text-red-500 hover:text-red-700 text-sm font-medium">
                            Lihat Semua
                        </a>
                    </div>
                    
                    @if($articles->count())
                        <div class="flex gap-3 overflow-x-auto pb-2 -mx-4 px-4 snap-x snap-mandatory">
                            @foreach($articles as $article)
                                <div class="flex-shrink-0 w-64 snap-center">
                                    @include('partials.article-card', ['article' => $article])
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 text-center">
                            <p class="text-gray-600 dark:text-gray-400 text-sm">No articles available yet.</p>
                        </div>
                    @endif
                </div>

                <!-- Spacer for bottom nav -->
                <div class="h-8"></div>
            </div>
        </div>
    </div>
</x-app-layout>
