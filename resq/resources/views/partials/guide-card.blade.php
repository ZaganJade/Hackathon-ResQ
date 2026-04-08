<div class="bg-white dark:bg-gray-900 rounded-2xl shadow hover:shadow-lg transition-shadow duration-300 overflow-hidden flex flex-col h-full border border-gray-100 dark:border-gray-800">
    <!-- Image -->
    @if($guide->image)
        <div class="w-full h-48 bg-gradient-to-br from-gray-300 to-gray-400 overflow-hidden">
            <img src="{{ $guide->image }}" alt="{{ $guide->title }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" />
        </div>
    @else
        <div class="w-full h-48 bg-gradient-to-br from-orange-400 to-yellow-500 flex items-center justify-center">
            <svg class="w-12 h-12 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    @endif

    <!-- Content -->
    <div class="p-4 flex-1 flex flex-col">
        <!-- Category Badge -->
        @if($guide->category)
            <span class="inline-block w-fit px-3 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 text-xs font-semibold rounded-full mb-2">
                {{ ucfirst($guide->category) }}
            </span>
        @endif

        <!-- Title -->
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-2 flex-1">
            {{ $guide->title }}
        </h3>

        <!-- Steps Count -->
        @php
            $steps = is_string($guide->steps) ? json_decode($guide->steps, true) : ($guide->steps ?? []);
            $stepCount = is_array($steps) ? count($steps) : 0;
        @endphp
        @if($stepCount > 0)
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-4">
                <svg class="w-5 h-5 text-orange-600 dark:text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2H6a6 6 0 016 6v3h1a1 1 0 100-2h-1V9a1 1 0 10-2 0v1h-1V7a4 4 0 00-4-4H8a1 1 0 000 2h2a2 2 0 012 2v1H4z" clip-rule="evenodd"></path>
                </svg>
                <span><strong>{{ $stepCount }}</strong> steps to follow</span>
            </div>
        @endif

        <!-- Read More Link -->
        <a href="#" class="inline-block text-orange-600 dark:text-orange-500 hover:text-orange-700 dark:hover:text-orange-400 font-semibold text-sm mt-auto">
            View Guide →
        </a>
    </div>
</div>
