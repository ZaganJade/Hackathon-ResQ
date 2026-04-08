<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto">
            <!-- Breadcrumb -->
            <nav class="flex items-center text-sm text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-gray-700">{{ __('Dashboard') }}</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-gray-700">{{ __('Panduan Mitigasi') }}</span>
            </nav>

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 leading-tight">
                        {{ __('Panduan Mitigasi Bencana') }}
                    </h1>
                    <p class="text-gray-600 mt-2">
                        {{ __('Pelajari cara menghadapi dan mitigasi berbagai jenis bencana alam') }}
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Category Filter -->
        @if($categories->count() > 0)
            <div class="mb-8">
                <div class="flex flex-wrap gap-2">
                    <button onclick="scrollToSection('all')"
                            class="category-btn px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 bg-indigo-600 text-white shadow-md"
                            data-category="all">
                        {{ __('Semua') }}
                    </button>
                    @foreach($categories as $category)
                        <button onclick="scrollToSection('{{ $category }}')"
                                class="category-btn px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 bg-white text-gray-700 border border-gray-200 hover:bg-gray-50"
                                data-category="{{ $category }}">
                            {{ ucfirst($category) }}
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Guides by Category -->
        @if($guidesByCategory->count() > 0)
            <div class="space-y-12" id="guides-container">
                @foreach($guidesByCategory as $category => $guides)
                    <section id="section-{{ $category }}" class="category-section scroll-mt-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-md">
                                @if($category === 'earthquake')
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                @elseif($category === 'flood')
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                    </svg>
                                @elseif($category === 'landslide')
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                    </svg>
                                @elseif($category === 'tsunami')
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                @elseif($category === 'fire')
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                                    </svg>
                                @elseif($category === 'volcano')
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                @elseif($category === 'general')
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                @else
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 capitalize">{{ $category }}</h2>
                                <p class="text-sm text-gray-500">{{ $guides->count() }} {{ __('panduan') }}</p>
                            </div>
                            <a href="{{ route('guides.category', $category) }}" class="ml-auto text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                                {{ __('Lihat Semua') }}
                                <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                            @foreach($guides->take(6) as $guide)
                                <a href="{{ route('guides.show', $guide->slug) }}" class="group bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col">
                                    @if($guide->image)
                                        <div class="relative h-40 overflow-hidden">
                                            <img src="{{ asset('storage/' . $guide->image) }}" alt="{{ $guide->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        </div>
                                    @else
                                        <div class="h-24 bg-gradient-to-r from-gray-100 to-gray-200 flex items-center justify-center">
                                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        </div>
                                    @endif

                                    <div class="p-5 flex-1 flex flex-col">
                                        <h3 class="font-bold text-gray-900 group-hover:text-indigo-600 transition-colors line-clamp-2 mb-2">
                                            {{ $guide->title }}
                                        </h3>

                                        @if($guide->steps)
                                            <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                                </svg>
                                                {{ count($guide->getFormattedSteps()) }} {{ __('langkah') }}
                                            </div>
                                        @endif

                                        @if($guide->video_url)
                                            <div class="flex items-center gap-1 text-sm text-red-600 mt-auto">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                                </svg>
                                                {{ __('Ada video tutorial') }}
                                            </div>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-xl border border-gray-100">
                <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Belum ada panduan') }}</h3>
                <p class="text-gray-500">{{ __('Panduan mitigasi akan segera ditambahkan') }}</p>
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        function scrollToSection(category) {
            // Update active button
            document.querySelectorAll('.category-btn').forEach(btn => {
                if (btn.dataset.category === category) {
                    btn.classList.remove('bg-white', 'text-gray-700', 'border', 'border-gray-200');
                    btn.classList.add('bg-indigo-600', 'text-white', 'shadow-md');
                } else {
                    btn.classList.remove('bg-indigo-600', 'text-white', 'shadow-md');
                    btn.classList.add('bg-white', 'text-gray-700', 'border', 'border-gray-200');
                }
            });

            // Scroll to section
            if (category === 'all') {
                document.getElementById('guides-container').scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                const section = document.getElementById('section-' + category);
                if (section) {
                    section.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
