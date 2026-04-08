<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto">
            <!-- Breadcrumb -->
            <nav class="flex items-center text-sm text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-gray-700">{{ __('Dashboard') }}</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-gray-700">{{ __('Artikel & Berita') }}</span>
            </nav>

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 leading-tight">
                        {{ __('Artikel & Berita') }}
                    </h1>
                    <p class="text-gray-600 mt-2">
                        {{ __('Informasi terkini seputar bencana dan mitigasi di Indonesia') }}
                    </p>
                </div>

                <!-- Search -->
                <form action="{{ route('articles.index') }}" method="GET" class="flex-shrink-0">
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ $searchQuery ?? '' }}"
                            placeholder="{{ __('Cari artikel...') }}"
                            class="w-full md:w-72 pl-10 pr-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow"
                        >
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        @if($searchQuery)
                            <a href="{{ route('articles.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Content -->
            <div class="flex-1">
                <!-- Category Filter -->
                @if($categories->count() > 0)
                    <div class="mb-6">
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('articles.index') }}"
                               class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 {{ !$currentCategory ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
                                {{ __('Semua') }}
                            </a>
                            @foreach($categories as $category)
                                <a href="{{ route('articles.category', $category) }}"
                                   class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 {{ $currentCategory === $category ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
                                    {{ ucfirst($category) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Search Results Info -->
                @if($searchQuery)
                    <div class="mb-6 p-4 bg-indigo-50 rounded-lg border border-indigo-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <span class="text-indigo-900 font-medium">
                                    {{ __('Hasil pencarian untuk:') }} "{{ $searchQuery }}"
                                </span>
                            </div>
                            <span class="text-sm text-indigo-600">
                                {{ $articles->total() }} {{ __('hasil') }}
                            </span>
                        </div>
                    </div>
                @endif

                <!-- Articles Grid -->
                @if($articles->count() > 0)
                    <div class="grid gap-6 md:grid-cols-2">
                        @foreach($articles as $article)
                            <article class="group bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300">
                                @if($article->image)
                                    <div class="relative h-48 overflow-hidden">
                                        <img src="{{ asset('storage/' . $article->image) }}"
                                             alt="{{ $article->title }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                        @if($article->category)
                                            <span class="absolute top-3 left-3 px-3 py-1 bg-white/90 backdrop-blur-sm text-xs font-semibold text-gray-700 rounded-full">
                                                {{ ucfirst($article->category) }}
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <div class="h-24 bg-gradient-to-r from-indigo-500 to-purple-600 relative">
                                        @if($article->category)
                                            <span class="absolute top-3 left-3 px-3 py-1 bg-white/90 backdrop-blur-sm text-xs font-semibold text-gray-700 rounded-full">
                                                {{ ucfirst($article->category) }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <div class="p-5">
                                    <div class="flex items-center gap-3 text-xs text-gray-500 mb-3">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ $article->published_at->format('d M Y') }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            {{ number_format($article->view_count) }} {{ __('dibaca') }}
                                        </span>
                                    </div>

                                    <h2 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors line-clamp-2">
                                        <a href="{{ route('articles.show', $article->slug) }}">
                                            {{ $article->title }}
                                        </a>
                                    </h2>

                                    <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                                        {{ $article->excerpt }}
                                    </p>

                                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <span class="text-sm font-medium text-indigo-600">
                                                    {{ substr($article->author?->name ?? 'A', 0, 1) }}
                                                </span>
                                            </div>
                                            <span class="text-sm text-gray-600">
                                                {{ $article->author?->name ?? __('Admin') }}
                                            </span>
                                        </div>

                                        <a href="{{ route('articles.show', $article->slug) }}"
                                           class="inline-flex items-center gap-1 text-sm font-medium text-indigo-600 hover:text-indigo-700 transition-colors">
                                            {{ __('Baca Selengkapnya') }}
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $articles->links() }}
                    </div>
                @else
                    <div class="text-center py-16 bg-white rounded-xl border border-gray-100">
                        <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">
                            {{ $searchQuery ? __('Tidak ada artikel ditemukan') : __('Belum ada artikel') }}
                        </h3>
                        <p class="text-gray-500">
                            {{ $searchQuery ? __('Coba kata kunci lain') : __('Artikel akan segera ditambahkan') }}
                        </p>
                        @if($searchQuery)
                            <a href="{{ route('articles.index') }}" class="mt-4 inline-flex items-center text-indigo-600 hover:text-indigo-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                {{ __('Kembali ke semua artikel') }}
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="w-full lg:w-80 flex-shrink-0 space-y-6">
                <!-- Popular Articles -->
                @if($popularArticles->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                            </svg>
                            {{ __('Populer') }}
                        </h3>
                        <div class="space-y-4">
                            @foreach($popularArticles as $index => $popular)
                                <a href="{{ route('articles.show', $popular->slug) }}" class="group flex gap-3">
                                    <span class="flex-shrink-0 w-6 h-6 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-xs font-bold flex items-center justify-center">
                                        {{ $index + 1 }}
                                    </span>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 group-hover:text-indigo-600 transition-colors line-clamp-2">
                                            {{ $popular->title }}
                                        </h4>
                                        <span class="text-xs text-gray-500 mt-1">
                                            {{ number_format($popular->view_count) }} {{ __('pembaca') }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Newsletter / Info Box -->
                <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-xl shadow-lg p-5 text-white">
                    <h3 class="text-lg font-bold mb-2">{{ __('Tetap Terinformasi') }}</h3>
                    <p class="text-sm text-indigo-100 mb-4">
                        {{ __('Dapatkan informasi terbaru tentang bencana dan tips mitigasi langsung di WhatsApp Anda.') }}
                    </p>
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-indigo-600 rounded-lg text-sm font-medium hover:bg-indigo-50 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.292-.497-.487-.692-.682-.396-.394-.754-.668-1.188-.922-.486-.286-.992-.493-1.514-.627-.044-.012-.087-.023-.131-.033-.42-.095-.846-.144-1.273-.144-.428 0-.854.049-1.273.144-.044.01-.087.021-.131.033-.522.134-1.028.341-1.514.627-.434.254-.792.528-1.188.922-.195.195-.395.39-.692.682l-.003.003C6.301 16.569 5.5 17.957 5.5 19.5h13c0-1.543-.801-2.931-2.025-4.115l-.003-.003zM12 14a4 4 0 100-8 4 4 0 000 8z"/>
                        </svg>
                        {{ __('Atur Notifikasi') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
