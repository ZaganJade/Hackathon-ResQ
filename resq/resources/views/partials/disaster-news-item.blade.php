<div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden border border-gray-100 dark:border-gray-700 h-full flex flex-col">
    <div class="flex items-start p-4 gap-3 flex-1 flex-col">
        <!-- Severity Indicator -->
        <div class="flex-shrink-0">
            @php
                $severityColors = [
                    'low' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
                    'medium' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400',
                    'high' => 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400',
                    'critical' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
                ];
                $severityClass = $severityColors[$disaster->severity] ?? 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-400';
            @endphp
            <div class="p-3 rounded-lg {{ $severityClass }}">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>

        <!-- Content -->
        <div class="flex-1">
            <!-- Header -->
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ ucfirst($disaster->type) }} Alert</h3>
                    @if($disaster->location)
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">📍 {{ $disaster->location }}</p>
                    @endif
                </div>
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $severityClass }}">
                    {{ ucfirst($disaster->severity) }}
                </span>
            </div>

            <!-- Description -->
            @if($disaster->description)
                <p class="text-gray-700 dark:text-gray-300 mt-3 text-sm line-clamp-2">
                    {{ $disaster->description }}
                </p>
            @endif

            <!-- Meta Information -->
            <div class="flex flex-wrap gap-4 mt-4 text-xs text-gray-600 dark:text-gray-400">
                <div class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                    </svg>
                    {{ $disaster->created_at->format('M d, Y H:i') }}
                </div>
                @if($disaster->status)
                    <div class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7a2 2 0 012 2v2h1a1 1 0 110 2h-.22l-.368 5.953A2 2 0 1620.016 20H4.032a2 2 0 01-1.99-2.286L2.22 9h-.22a1 1 0 110-2h1V4a2 2 0 012-2h12zm-4 2H8v4h8V6z" />
                        </svg>
                        <span class="capitalize">{{ $disaster->status }}</span>
                    </div>
                @endif
            </div>

            <!-- Action Button -->
            <a href="#" class="inline-block mt-4 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                Learn More
            </a>
        </div>
    </div>
</div>
