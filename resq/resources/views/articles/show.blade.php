<x-app-layout>
    <x-slot name="header">
        <div class="max-w-4xl mx-auto">
            <!-- Breadcrumb -->
            <nav class="flex items-center text-sm text-slate-500 mb-4">
                <a href="{{ route('dashboard') }}" class="hover:text-primary-600 transition-colors">{{ __('Dashboard') }}</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <a href="{{ route('articles.index') }}" class="hover:text-primary-600 transition-colors">{{ __('Artikel') }}</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-slate-700 truncate max-w-xs">{{ $article->title ?? 'Judul Artikel' }}</span>
            </nav>
        </div>
    </x-slot>

    <article class="max-w-4xl mx-auto container-padding py-8 animate-fade-up">
        <!-- Article Header -->
        <div class="text-center mb-8">
            @if($article->category ?? false)
                <a href="{{ route('articles.category', $article->category) }}"
                   class="inline-block px-4 py-1.5 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold mb-4 hover:bg-primary-200 transition-colors">
                    {{ ucfirst($article->category) }}
                </a>
            @else
                <span class="inline-block px-4 py-1.5 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold mb-4">
                    Mitigasi Bencana
                </span>
            @endif

            <h1 class="heading-2 text-slate-800 mb-6">
                {{ $article->title ?? 'Cara Menyusun Rencana Evakuasi Keluarga yang Efektif' }}
            </h1>

            <!-- Meta Info -->
            <div class="flex flex-wrap items-center justify-center gap-4 text-sm text-slate-500">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                        <span class="text-lg font-medium text-primary-600">
                            {{ substr($article->author?->name ?? 'R', 0, 1) }}
                        </span>
                    </div>
                    <div class="text-left">
                        <p class="font-medium text-slate-800">{{ $article->author?->name ?? __('ResQ Admin') }}</p>
                        <p class="text-xs text-slate-500">{{ __('Penulis') }}</p>
                    </div>
                </div>

                <span class="hidden sm:block w-px h-6 bg-slate-200"></span>

                <div class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ ($article->published_at ?? now())->format('d M Y') }}
                </div>

                <span class="hidden sm:block w-px h-6 bg-slate-200"></span>

                <div class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    {{ number_format($article->view_count ?? 1234) }} {{ __('kali dibaca') }}
                </div>
            </div>
        </div>

        <!-- Featured Image -->
        @if($article->image ?? false)
            <div class="mb-8 rounded-2xl overflow-hidden shadow-soft-xl">
                <img src="{{ asset('storage/' . $article->image) }}"
                     alt="{{ $article->title }}"
                     class="w-full h-64 md:h-96 object-cover">
            </div>
        @else
            <div class="mb-8 h-48 md:h-64 bg-gradient-to-r from-primary-400 via-secondary-400 to-accent-400 rounded-2xl shadow-soft-xl"></div>
        @endif

        <!-- Article Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="prose prose-lg max-w-none prose-headings:text-slate-800 prose-p:text-slate-600 prose-a:text-primary-600 prose-a:hover:text-primary-700">
                    {!! $article->content ?? '<p>Memiliki rencana evakuasi yang efektif adalah salah satu langkah terpenting dalam kesiapsiagaan bencana. Rencana yang baik tidak hanya menyelamatkan nyawa, tetapi juga mengurangi kepanikan dan kebingungan saat keadaan darurat.</p><h2>Mengapa Rencana Evakuasi Penting?</h2><p>Ketika bencana terjadi, setiap detik sangat berharga. Rencana evakuasi yang sudah disiapkan sebelumnya memungkinkan keluarga Anda untuk bertindak cepat dan aman tanpa harus memikirkan langkah-langkah dasar yang seharusnya sudah menjadi kebiasaan.</p><h2>Langkah-Langkah Menyusun Rencana</h2><p>1. Kenali risiko bencana di daerah Anda<br>2. Tetapkan rute evakuasi utama dan alternatif<br>3. Tentukan tempat berkumpul yang aman<br>4. Siapkan tas siaga bencana<br>5. Latih rencana secara berkala dengan keluarga</p>' !!}
                </div>

                <!-- Share & Actions -->
                <div class="mt-10 pt-6 border-t border-slate-200">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <span class="text-sm font-medium text-slate-700">{{ __('Bagikan artikel:') }}</span>
                            <div class="flex gap-2 mt-2">
                                <button onclick="shareArticle('whatsapp')" class="p-2 rounded-full bg-success text-white hover:bg-emerald-600 transition-colors shadow-soft" title="WhatsApp">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.292-.497-.487-.692-.682-.396-.394-.754-.668-1.188-.922-.486-.286-.992-.493-1.514-.627-.044-.012-.087-.023-.131-.033-.42-.095-.846-.144-1.273-.144-.428 0-.854.049-1.273.144-.044.01-.087.021-.131.033-.522.134-1.028.341-1.514.627-.434.254-.792.528-1.188.922-.195.195-.395.39-.692.682l-.003.003C6.301 16.569 5.5 17.957 5.5 19.5h13c0-1.543-.801-2.931-2.025-4.115l-.003-.003zM12 14a4 4 0 100-8 4 4 0 000 8z"/>
                                    </svg>
                                </button>
                                <button onclick="shareArticle('twitter')" class="p-2 rounded-full bg-sky-500 text-white hover:bg-sky-600 transition-colors shadow-soft" title="Twitter">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                </button>
                                <button onclick="shareArticle('facebook')" class="p-2 rounded-full bg-blue-600 text-white hover:bg-blue-700 transition-colors shadow-soft" title="Facebook">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                </button>
                                <button onclick="copyLink()" class="p-2 rounded-full bg-slate-600 text-white hover:bg-slate-700 transition-colors shadow-soft" title="{{ __('Salin Link') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <a href="{{ route('ai-assist.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-100 text-primary-700 rounded-full hover:bg-primary-200 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                            {{ __('Tanya AI Assist') }}
                        </a>
                    </div>
                </div>

                <!-- Related Articles -->
                @if(($relatedArticles ?? collect())->count() > 0)
                    <div class="mt-12">
                        <h3 class="heading-4 text-slate-800 mb-6 flex items-center gap-2">
                            <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            {{ __('Artikel Terkait') }}
                        </h3>
                        <div class="grid gap-4 md:grid-cols-2">
                            @foreach($relatedArticles as $related)
                                <a href="{{ route('articles.show', $related->slug) }}" class="card p-4 flex gap-4 group">
                                    @if($related->image)
                                        <img src="{{ asset('storage/' . $related->image) }}" alt="" class="w-20 h-20 rounded-lg object-cover flex-shrink-0">
                                    @else
                                        <div class="w-20 h-20 rounded-lg bg-gradient-to-br from-primary-100 to-secondary-100 flex-shrink-0"></div>
                                    @endif
                                    <div>
                                        <h4 class="font-medium text-slate-800 group-hover:text-primary-600 transition-colors line-clamp-2">
                                            {{ $related->title }}
                                        </h4>
                                        <p class="text-sm text-slate-500 mt-1">
                                            {{ $related->published_at->format('d M Y') }}
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Popular Articles -->
                @if(($popularArticles ?? collect())->count() > 0)
                    <div class="card p-5 sticky top-4">
                        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                            </svg>
                            {{ __('Artikel Populer') }}
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

                <!-- Back to Articles -->
                <a href="{{ route('articles.index') }}" class="btn-ghost w-full justify-center border border-slate-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('Kembali ke Artikel') }}
                </a>
            </div>
        </div>
    </article>

    @push('scripts')
    <script>
        function shareArticle(platform) {
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent(document.title);

            let shareUrl = '';
            switch(platform) {
                case 'whatsapp':
                    shareUrl = `https://wa.me/?text=${title}%20${url}`;
                    break;
                case 'twitter':
                    shareUrl = `https://twitter.com/intent/tweet?text=${title}&url=${url}`;
                    break;
                case 'facebook':
                    shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
                    break;
            }

            if (shareUrl) {
                window.open(shareUrl, '_blank', 'width=600,height=400');
            }
        }

        function copyLink() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                // Show toast notification
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 right-4 bg-primary-600 text-white px-4 py-2 rounded-full shadow-lg z-50 animate-fade-up';
                toast.textContent = '{{ __('Link berhasil disalin!') }}';
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            });
        }
    </script>
    @endpush
</x-app-layout>
