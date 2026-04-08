<div class="bg-white dark:bg-gray-900 rounded-2xl shadow hover:shadow-lg transition-shadow duration-300 overflow-hidden flex flex-col h-full border border-gray-100 dark:border-gray-800">
    <!-- Image -->
    @if($article->image)
        <div class="w-full h-48 bg-gradient-to-br from-gray-300 to-gray-400 overflow-hidden">
            <img src="{{ $article->image }}" alt="{{ $article->title }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" />
        </div>
    @else
        <div class="w-full h-48 bg-gradient-to-br from-red-400 to-orange-500 flex items-center justify-center">
            <svg class="w-12 h-12 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    @endif

    <!-- Content -->
    <div class="p-4 flex-1 flex flex-col">
        <!-- Category Badge -->
        @if($article->category)
            <span class="inline-block w-fit px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-semibold rounded-full mb-2">
                {{ ucfirst($article->category) }}
            </span>
        @endif

        <!-- Title -->
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-2 flex-1">
            {{ $article->title }}
        </h3>

        <!-- Excerpt -->
        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-3">
            {{ $article->excerpt ?? Str::limit(strip_tags($article->content), 100) }}
        </p>

        <!-- Meta -->
        <div class="text-xs text-gray-500 dark:text-gray-400 mb-4 space-y-1">
            @if($article->author)
                <p>By {{ $article->author->name }}</p>
            @endif
            @if($article->published_at)
                <p>{{ $article->published_at->format('M d, Y') }}</p>
            @endif
            @if($article->view_count)
                <p>{{ $article->view_count }} views</p>
            @endif
        </div>

        <!-- Read More Link -->
        <a href="#" class="inline-block text-red-600 dark:text-red-500 hover:text-red-700 dark:hover:text-red-400 font-semibold text-sm mt-auto">
            Read More →
        </a>
    </div>
</div>
