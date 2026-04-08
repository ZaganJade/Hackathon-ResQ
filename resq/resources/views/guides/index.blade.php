<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-accent-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <h2 class="heading-4 text-primary-800">{{ __('Panduan Mitigasi') }}</h2>
                    <p class="body-small mt-1">{{ __('Pelajari cara menghadapi dan mitigasi berbagai jenis bencana alam') }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 container-padding">
        <div class="max-w-7xl mx-auto">
            <!-- Category Filter -->
            @if(($categories ?? collect())->count() > 0)
                <div class="mb-8">
                    <div class="flex flex-wrap gap-2">
                        <button onclick="scrollToSection('all')"
                                class="category-btn px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 bg-primary-600 text-white shadow-soft"
                                data-category="all">
                            {{ __('Semua') }}
                        </button>
                        @foreach($categories as $category)
                            <button onclick="scrollToSection('{{ $category }}')"
                                    class="category-btn px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 bg-slate-100 text-slate-700 hover:bg-slate-200"
                                    data-category="{{ $category }}">
                                {{ ucfirst($category) }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Guides by Category -->
            @if(($guidesByCategory ?? collect())->count() > 0)
                <div class="space-y-12" id="guides-container">
                    @foreach($guidesByCategory as $category => $guides)
                        <section id="section-{{ $category }}" class="category-section scroll-mt-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary-500 to-secondary-500 flex items-center justify-center shadow-soft">
                                    @if($category === 'earthquake')
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    @elseif($category === 'flood')
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                        </svg>
                                    @elseif($category === 'landslide')
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                        </svg>
                                    @elseif($category === 'tsunami')
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                    @elseif($category === 'fire')
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                                        </svg>
                                    @elseif($category === 'volcano')
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                        </svg>
                                    @elseif($category === 'general')
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-slate-800 capitalize">{{ $category }}</h2>
                                    <p class="text-sm text-slate-500">{{ $guides->count() }} {{ __('panduan') }}</p>
                                </div>
                                <a href="{{ route('guides.category', $category) }}" class="ml-auto text-sm text-primary-600 hover:text-primary-700 font-medium">
                                    {{ __('Lihat Semua') }}
                                    <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>

                            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                                @foreach($guides->take(6) as $guide)
                                    <a href="{{ route('guides.show', $guide->slug) }}" class="card overflow-hidden group flex flex-col">
                                        @if($guide->image)
                                            <div class="relative h-40 overflow-hidden">
                                                <img src="{{ asset('storage/' . $guide->image) }}" alt="{{ $guide->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                            </div>
                                        @else
                                            <div class="h-24 bg-gradient-to-r from-primary-100 to-secondary-100 flex items-center justify-center">
                                                <svg class="w-10 h-10 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                </svg>
                                            </div>
                                        @endif

                                        <div class="p-5 flex-1 flex flex-col">
                                            <h3 class="font-bold text-slate-800 group-hover:text-primary-600 transition-colors line-clamp-2 mb-2">
                                                {{ $guide->title }}
                                            </h3>

                                            @if($guide->steps)
                                                <div class="flex items-center gap-2 text-sm text-slate-500 mb-3">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                                    </svg>
                                                    {{ count($guide->getFormattedSteps()) }} {{ __('langkah') }}
                                                </div>
                                            @endif

                                            @if($guide->video_url)
                                                <div class="flex items-center gap-1 text-sm text-rose-600 mt-auto">
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
                <!-- Empty State / Sample Guides -->
                @php
                    $sampleGuides = [
                        [
                            'category' => 'Gempa Bumi',
                            'title' => 'Panduan Bertahan Saat Gempa Bumi',
                            'steps' => 8,
                            'image' => 'https://images.unsplash.com/photo-1527482797697-8795b05a13fe?w=800&h=400&fit=crop',
                        ],
                        [
                            'category' => 'Banjir',
                            'title' => 'Evakuasi Aman Saat Banjir',
                            'steps' => 6,
                            'image' => 'https://images.unsplash.com/photo-1508514177221-188b1cf16e9d?w=800&h=400&fit=crop',
                        ],
                        [
                            'category' => 'Kebakaran',
                            'title' => 'Menyelamatkan Diri Dari Kebakaran',
                            'steps' => 10,
                            'image' => 'https://images.unsplash.com/photo-1599169685322-b6f3309c1e62?w=800&h=400&fit=crop',
                        ],
                        [
                            'category' => 'Longsor',
                            'title' => 'Mengenali Tanda-Tanda Longsor',
                            'steps' => 5,
                            'image' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=800&h=400&fit=crop',
                        ],
                        [
                            'category' => 'Tsunami',
                            'title' => 'Evakuasi Cepat Saat Peringatan Tsunami',
                            'steps' => 7,
                            'image' => 'https://images.unsplash.com/photo-1547683905-3912b315a154?w=800&h=400&fit=crop',
                        ],
                        [
                            'category' => 'Gunung Berapi',
                            'title' => 'Menghadapi Erupsi Gunung Berapi',
                            'steps' => 9,
                            'image' => 'https://images.unsplash.com/photo-1462331940025-496dfbfc7564?w=800&h=400&fit=crop',
                        ],
                    ];
                @endphp

                <div class="space-y-12" id="guides-container">
                    <section class="category-section">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary-500 to-secondary-500 flex items-center justify-center shadow-soft">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-slate-800">Panduan Darurat</h2>
                                <p class="text-sm text-slate-500">6 panduan</p>
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                            @foreach($sampleGuides as $guide)
                                <a href="#" class="card overflow-hidden group flex flex-col">
                                    <div class="relative h-40 overflow-hidden">
                                        <img src="{{ $guide['image'] }}" alt="{{ $guide['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    </div>

                                    <div class="p-5 flex-1 flex flex-col">
                                        <span class="text-xs text-primary-600 font-medium mb-1">{{ $guide['category'] }}</span>
                                        <h3 class="font-bold text-slate-800 group-hover:text-primary-600 transition-colors line-clamp-2 mb-2">
                                            {{ $guide['title'] }}
                                        </h3>

                                        <div class="flex items-center gap-2 text-sm text-slate-500 mt-auto">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                            </svg>
                                            {{ $guide['steps'] }} langkah
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </section>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function scrollToSection(category) {
            document.querySelectorAll('.category-btn').forEach(btn => {
                if (btn.dataset.category === category) {
                    btn.classList.remove('bg-slate-100', 'text-slate-700');
                    btn.classList.add('bg-primary-600', 'text-white', 'shadow-soft');
                } else {
                    btn.classList.remove('bg-primary-600', 'text-white', 'shadow-soft');
                    btn.classList.add('bg-slate-100', 'text-slate-700');
                }
            });

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
