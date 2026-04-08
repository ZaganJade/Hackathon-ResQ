<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-sky-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="heading-4 text-primary-800">{{ __('Artikel & Berita') }}</h2>
                    <p class="body-small mt-1">{{ __('Informasi terkini seputar bencana dan mitigasi di Indonesia') }}</p>
                </div>
            </div>

            <!-- Search -->
            <form action="{{ route('articles.index') }}" method="GET" class="flex gap-2">
                <div class="relative flex-1 sm:w-72">
                    <input
                        type="text"
                        name="search"
                        value="{{ $searchQuery ?? '' }}"
                        placeholder="Cari artikel..."
                        class="input-field w-full pl-10"
                    >
                    <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    @if($searchQuery ?? false)
                        <a href="{{ route('articles.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    @endif
                </div>
                <button type="submit" class="btn-primary px-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-8 container-padding">
        <div class="max-w-7xl mx-auto">
            <!-- Search Results Info -->
            @if($searchQuery ?? false)
                <div class="mb-6 card p-4 bg-primary-50 border-primary-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span class="text-slate-800 font-medium">
                                {{ __('Hasil pencarian untuk:') }} "{{ $searchQuery }}"
                            </span>
                        </div>
                        <span class="text-sm text-primary-600 font-medium">
                            {{ $articles->total() ?? 0 }} {{ __('hasil') }}
                        </span>
                    </div>
                </div>
            @endif

            <!-- Category Filter Pills -->
            @if(($categories ?? collect())->count() > 0)
                <div class="mb-8">
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('articles.index') }}"
                           class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 {{ !($currentCategory ?? false) ? 'bg-primary-600 text-white shadow-soft' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            Semua
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('articles.category', $category) }}"
                               class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 {{ ($currentCategory ?? '') === $category ? 'bg-primary-600 text-white shadow-soft' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                {{ ucfirst($category) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Main Content -->
                <div class="flex-1">
                    <!-- Articles Grid -->
                    @if(($articles ?? collect())->count() > 0)
                        <div class="grid gap-6 md:grid-cols-2">
                            @foreach($articles as $index => $article)
                                <article class="card overflow-hidden group animate-fade-up stagger-{{ ($index % 6) + 1 }}">
                                    @if($article->image)
                                        <div class="relative h-48 overflow-hidden">
                                            <img src="{{ asset('storage/' . $article->image) }}"
                                                 alt="{{ $article->title }}"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                            @if($article->category)
                                                <span class="absolute top-3 left-3 badge badge-info">
                                                    {{ ucfirst($article->category) }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="h-24 bg-gradient-to-r from-primary-500 to-secondary-500 relative">
                                            @if($article->category)
                                                <span class="absolute top-3 left-3 badge badge-info">
                                                    {{ ucfirst($article->category) }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="p-5">
                                        <div class="flex items-center gap-3 text-xs text-slate-500 mb-3">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                {{ $article->published_at->format('d M Y') }}
                                            </span>
                                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                {{ number_format($article->view_count) }} {{ __('dibaca') }}
                                            </span>
                                        </div>

                                        <h2 class="text-xl font-bold text-slate-800 mb-2 group-hover:text-primary-600 transition-colors line-clamp-2">
                                            <a href="{{ route('articles.show', $article->slug) }}">
                                                {{ $article->title }}
                                            </a>
                                        </h2>

                                        <p class="text-slate-600 text-sm line-clamp-3 mb-4">
                                            {{ $article->excerpt }}
                                        </p>

                                        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-primary-600">
                                                        {{ substr($article->author?->name ?? 'A', 0, 1) }}
                                                    </span>
                                                </div>
                                                <span class="text-sm text-slate-600">
                                                    {{ $article->author?->name ?? __('Admin') }}
                                                </span>
                                            </div>

                                            <a href="{{ route('articles.show', $article->slug) }}"
                                               class="inline-flex items-center gap-1 text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">
                                                {{ __('Baca Selengkapnya') }}
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
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
                        <!-- Empty State / Sample Articles -->
                        @php
                            $sampleArticles = [
                                [
                                    'title' => 'Cara Menyusun Rencana Evakuasi Keluarga yang Efektif',
                                    'excerpt' => 'Rencana evakuasi yang baik dapat menyelamatkan nyawa saat bencana terjadi. Pelajari langkah-langkah penting dalam menyusun rencana evakuasi untuk keluarga Anda.',
                                    'category' => 'Kesiapsiagaan',
                                    'image' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=800&h=400&fit=crop',
                                ],
                                [
                                    'title' => 'Mengenal Tanda-Tanda Gempa Bumi dan Tindakan Pertama',
                                    'excerpt' => 'Memahami tanda-tanda awal gempa bumi dan mengetahui tindakan pertama yang benar sangat penting untuk keselamatan Anda dan keluarga.',
                                    'category' => 'Respons Darurat',
                                    'image' => 'https://images.unsplash.com/photo-1527482797697-8795b05a13fe?w=800&h=400&fit=crop',
                                ],
                                [
                                    'title' => 'Persiapan Pasca Bencana: Memulihkan Kehidupan',
                                    'excerpt' => 'Proses pemulihan pasca bencana membutuhkan perencanaan matang. Simak panduan lengkap untuk memulihkan kehidupan setelah bencana melanda.',
                                    'category' => 'Pemulihan',
                                    'image' => 'https://images.unsplash.com/photo-1508514177221-188b1cf16e9d?w=800&h=400&fit=crop',
                                ],
                                [
                                    'title' => 'Teknologi Modern dalam Mitigasi Bencana',
                                    'excerpt' => 'Dari sistem peringatan dini hingga aplikasi darurat, teknologi modern memainkan peran penting dalam mengurangi risiko dan dampak bencana.',
                                    'category' => 'Mitigasi',
                                    'image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=800&h=400&fit=crop',
                                ],
                                [
                                    'title' => 'Edukasi Bencana untuk Anak: Cara yang Tepat',
                                    'excerpt' => 'Menyampaikan informasi tentang bencana kepada anak membutuhkan pendekatan khusus. Pelajari cara yang efektif untuk mengedukasi anak tentang kesiapsiagaan.',
                                    'category' => 'Edukasi',
                                    'image' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=800&h=400&fit=crop',
                                ],
                                [
                                    'title' => 'Membangun Rumah Tahan Gempa: Panduan Dasar',
                                    'excerpt' => 'Struktur bangunan yang tepat dapat mengurangi risiko kerusakan saat gempa. Simak panduan dasar membangun rumah yang lebih tahan gempa.',
                                    'category' => 'Mitigasi',
                                    'image' => 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=400&fit=crop',
                                ],
                            ];
                        @endphp

                        <div class="grid gap-6 md:grid-cols-2">
                            @foreach($sampleArticles as $index => $article)
                                <article class="card overflow-hidden group animate-fade-up stagger-{{ ($index % 6) + 1 }}">
                                    <div class="relative h-48 overflow-hidden">
                                        <img src="{{ $article['image'] }}"
                                             alt="{{ $article['title'] }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        <div class="absolute top-3 left-3 badge badge-info">{{ $article['category'] }}</div>
                                    </div>

                                    <div class="p-5">
                                        <div class="flex items-center gap-3 text-xs text-slate-500 mb-3">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                {{ now()->format('d M Y') }}
                                            </span>
                                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                {{ rand(100, 1000) }} {{ __('dibaca') }}
                                            </span>
                                        </div>

                                        <h2 class="text-xl font-bold text-slate-800 mb-2 group-hover:text-primary-600 transition-colors line-clamp-2">
                                            {{ $article['title'] }}
                                        </h2>

                                        <p class="text-slate-600 text-sm line-clamp-3 mb-4">
                                            {{ $article['excerpt'] }}
                                        </p>

                                        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-primary-600">R</span>
                                                </div>
                                                <span class="text-sm text-slate-600">ResQ Admin</span>
                                            </div>

                                            <a href="#" class="inline-flex items-center gap-1 text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">
                                                {{ __('Baca Selengkapnya') }}
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="w-full lg:w-80 flex-shrink-0 space-y-6">
                    <!-- Popular Articles -->
                    @if(($popularArticles ?? collect())->count() > 0)
                        <div class="card p-5">
                            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                                </svg>
                                {{ __('Populer') }}
                            </h3>
                            <div class="space-y-4">
                                @foreach($popularArticles as $index => $popular)
                                    <a href="{{ route('articles.show', $popular->slug) }}" class="group flex gap-3">
                                        <span class="flex-shrink-0 w-6 h-6 rounded-full bg-gradient-to-br from-primary-500 to-secondary-500 text-white text-xs font-bold flex items-center justify-center">
                                            {{ $index + 1 }}
                                        </span>
                                        <div>
                                            <h4 class="text-sm font-medium text-slate-800 group-hover:text-primary-600 transition-colors line-clamp-2">
                                                {{ $popular->title }}
                                            </h4>
                                            <span class="text-xs text-slate-500 mt-1">
                                                {{ number_format($popular->view_count) }} {{ __('pembaca') }}
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Newsletter / Info Box -->
                    <div class="card p-5 bg-gradient-to-br from-primary-500 to-secondary-600 border-0 text-white">
                        <h3 class="text-lg font-bold mb-2">{{ __('Tetap Terinformasi') }}</h3>
                        <p class="text-sm text-primary-100 mb-4">
                            {{ __('Dapatkan informasi terbaru tentang bencana dan tips mitigasi langsung di WhatsApp Anda.') }}
                        </p>
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-primary-600 rounded-full text-sm font-medium hover:bg-slate-50 transition-colors shadow-soft">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            {{ __('Atur Notifikasi') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
