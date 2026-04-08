<x-app-layout>
    <x-slot name="header">
        <div class="max-w-4xl mx-auto">
            <!-- Breadcrumb -->
            <nav class="flex items-center text-sm text-gray-500 mb-4">
                <a href="{{ route('dashboard') }}" class="hover:text-gray-700">{{ __('Dashboard') }}</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('guides.index') }}" class="hover:text-gray-700">{{ __('Panduan') }}</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-gray-700 capitalize">{{ $guide->category }}</span>
            </nav>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Guide Header -->
        <div class="text-center mb-8">
            <a href="{{ route('guides.category', $guide->category) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-br from-indigo-500 to-purple-600 text-white rounded-full text-sm font-semibold mb-4 hover:shadow-lg transition-all">
                @if($guide->category === 'earthquake')
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                @elseif($guide->category === 'flood')
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                @elseif($guide->category === 'landslide')
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                @elseif($guide->category === 'tsunami')
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                @elseif($guide->category === 'fire')
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                    </svg>
                @elseif($guide->category === 'volcano')
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                @else
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                @endif
                <span class="capitalize">{{ $guide->category }}</span>
            </a>

            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight mb-4">
                {{ $guide->title }}
            </h1>

            <div class="flex items-center justify-center gap-4 text-sm text-gray-500">
                @if($guide->video_url)
                    <span class="flex items-center gap-1 text-red-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                        {{ __('Video tersedia') }}
                    </span>
                @endif
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    {{ count($steps) }} {{ __('langkah') }}
                </span>
            </div>
        </div>

        <!-- Featured Image -->
        @if($guide->image)
            <div class="mb-8 rounded-2xl overflow-hidden shadow-lg">
                <img src="{{ asset('storage/' . $guide->image) }}"
                     alt="{{ $guide->title }}"
                     class="w-full h-64 md:h-80 object-cover">
            </div>
        @endif

        <!-- Video Embed -->
        @if($guide->video_url)
            <div class="mb-8">
                <div class="aspect-video rounded-2xl overflow-hidden shadow-lg bg-gray-900">
                    @if(str_contains($guide->video_url, 'youtube.com') || str_contains($guide->video_url, 'youtu.be'))
                        @php
                            $videoId = '';
                            if (str_contains($guide->video_url, 'youtube.com/watch?v=')) {
                                $videoId = explode('v=', $guide->video_url)[1] ?? '';
                                $videoId = explode('&', $videoId)[0] ?? '';
                            } elseif (str_contains($guide->video_url, 'youtu.be/')) {
                                $videoId = explode('youtu.be/', $guide->video_url)[1] ?? '';
                            }
                        @endphp
                        <iframe
                            class="w-full h-full"
                            src="https://www.youtube.com/embed/{{ $videoId }}"
                            title="{{ $guide->title }}"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    @else
                        <div class="w-full h-full flex items-center justify-center text-white">
                            <a href="{{ $guide->video_url }}" target="_blank" class="flex items-center gap-2 text-lg hover:underline">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M10 12.5l8-5V3l-8 5v4.5zm0 0v4.5l8 5V17l-8-5z"/>
                                </svg>
                                {{ __('Tonton Video') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Introduction -->
                @if($guide->content)
                    <div class="prose prose-lg max-w-none prose-indigo mb-8">
                        {!! $guide->content !!}
                    </div>
                @endif

                <!-- Steps -->
                @if(count($steps) > 0)
                    <div class="space-y-4">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            {{ __('Langkah-langkah') }}
                        </h2>

                        @foreach($steps as $index => $step)
                            <div class="flex gap-4 p-5 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white font-bold flex items-center justify-center text-lg">
                                        {{ $index + 1 }}
                                    </div>
                                </div>
                                <div class="flex-1">
                                    @if(is_array($step))
                                        @if(isset($step['title']))
                                            <h3 class="font-semibold text-gray-900 mb-2">{{ $step['title'] }}</h3>
                                        @endif
                                        @if(isset($step['description']))
                                            <p class="text-gray-600">{{ $step['description'] }}</p>
                                        @endif
                                    @else
                                        <p class="text-gray-600">{{ $step }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- AI Assist CTA -->
                <div class="mt-10 p-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl border border-indigo-100">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-indigo-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">{{ __('Punya Pertanyaan?') }}</h3>
                            <p class="text-gray-600 text-sm mb-3">
                                {{ __('Tanyakan ke AI Assist ResQ untuk informasi lebih detail tentang mitigasi bencana ini.') }}
                            </p>
                            <a href="{{ route('ai-assist.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
                                {{ __('Tanya AI Assist') }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Related Guides -->
                @if($relatedGuides->count() > 0)
                    <div class="mt-10">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            {{ __('Panduan Terkait') }}
                        </h3>
                        <div class="grid gap-4 md:grid-cols-2">
                            @foreach($relatedGuides as $related)
                                <a href="{{ route('guides.show', $related->slug) }}" class="group flex gap-4 p-4 bg-white rounded-lg border border-gray-100 hover:shadow-md transition-all">
                                    @if($related->image)
                                        <img src="{{ asset('storage/' . $related->image) }}" alt="" class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                                    @else
                                        <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-indigo-100 to-purple-100 flex-shrink-0"></div>
                                    @endif
                                    <div>
                                        <h4 class="font-medium text-gray-900 group-hover:text-indigo-600 transition-colors line-clamp-2">
                                            {{ $related->title }}
                                        </h4>
                                        @if($related->steps)
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ count($related->getFormattedSteps()) }} {{ __('langkah') }}
                                            </p>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Other Categories -->
                @if($otherCategories->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 sticky top-4">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            {{ __('Kategori Lain') }}
                        </h3>
                        <div class="space-y-3">
                            @foreach($otherCategories as $other)
                                <a href="{{ route('guides.show', $other->slug) }}" class="group flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-medium text-gray-900 group-hover:text-indigo-600 transition-colors truncate">
                                            {{ $other->title }}
                                        </h4>
                                        <p class="text-xs text-gray-500 capitalize">{{ $other->category }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Back to Guides -->
                <a href="{{ route('guides.index') }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    {{ __('Kembali ke Panduan') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
