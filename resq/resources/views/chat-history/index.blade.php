<x-app-layout>
    @push('styles')
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @endpush

    {{-- Riwayat Chat — Modern Dark Dashboard Style (Matching Edukasi) --}}
    <div class="min-h-screen bg-slate-950 pb-24 lg:pb-8" x-data="{}" x-cloak>

        {{-- DESKTOP SIDEBAR --}}
        <aside class="hidden lg:flex fixed top-0 left-0 h-full z-50 flex-col"
               x-data="{ sidebarHover: false }" @mouseenter="sidebarHover = true" @mouseleave="sidebarHover = false">
            <div class="h-full bg-slate-900/95 backdrop-blur-2xl border-r border-white/5 shadow-soft-lg flex flex-col py-6 sidebar-spring overflow-hidden" :class="sidebarHover ? 'w-64' : 'w-[72px]'">
                <div class="flex items-center gap-3 px-4 mb-8 overflow-hidden">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-400 flex items-center justify-center shrink-0 shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="font-bold text-xl text-white whitespace-nowrap transition-opacity duration-300" :class="sidebarHover ? 'opacity-100' : 'opacity-0'">ResQ</span>
                </div>
                <nav class="flex-1 flex flex-col gap-1 px-3">
                    @php $menuItems = [
                        ['route'=>'dashboard','label'=>'Beranda','icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6','active'=>'dashboard'],
                        ['route'=>'map.index','label'=>'Peta Interaktif','icon'=>'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7','active'=>'map.*'],
                        ['route'=>'guides.index','label'=>'Edukasi & Pelatihan','icon'=>'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253','active'=>'guides.*'],
                        ['route'=>'articles.index','label'=>'Berita & Info','icon'=>'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z','active'=>'articles.*'],
                        ['route'=>'chat-history.index','label'=>'Riwayat Chat','icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z','active'=>'chat-history.*'],
                        ['route'=>'ai-assist.index','label'=>'AI Assistant','icon'=>'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z','active'=>'ai-assist.*'],
                        ['route'=>'profile.edit','label'=>'Profil','icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z','active'=>'profile.*'],
                    ]; @endphp
                    @foreach($menuItems as $item)
                        <a href="{{ route($item['route']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group whitespace-nowrap {{ request()->routeIs($item['active']) ? 'menu-active' : 'text-slate-400 hover:bg-white/5 hover:text-emerald-400' }}">
                            <div class="w-7 h-7 flex items-center justify-center shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path></svg></div>
                            <span class="text-sm font-medium transition-opacity duration-300" :class="sidebarHover ? 'opacity-100' : 'opacity-0'">{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
                <div class="px-3 mt-auto relative" x-data="{ openOptions: false }">
                    <button @click="openOptions = !openOptions" @click.away="openOptions = false" class="relative z-[90] w-full flex items-center gap-3 px-3 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 overflow-hidden transition-all duration-200 group focus:outline-none">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center shrink-0 ring-2 ring-white/10 group-hover:ring-emerald-500/50 transition-all">
                            <span class="text-white font-bold text-xs">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                        </div>
                        <div class="min-w-0 text-left transition-opacity duration-300" :class="sidebarHover ? 'opacity-100' : 'opacity-0'">
                            <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </button>
                    <div x-show="openOptions" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-1 scale-95" class="absolute left-1 right-1 w-auto mx-2 bg-slate-800 border border-white/10 rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.5)] overflow-hidden z-[100] py-1" style="bottom: 100%; margin-bottom: 8px; display: none;">
                        <a href="{{ route('profile.edit') }}" class="w-full text-left px-4 py-2.5 text-sm text-slate-300 hover:bg-white/5 hover:text-white transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Edit Profil
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <div class="lg:ml-[72px]">
            {{-- Hero --}}
            <section class="relative overflow-hidden glass-dark border-b border-white/5" data-aos="fade-down" data-aos-duration="1000">
                <div class="absolute inset-0 overflow-hidden pointer-events-none">
                    <div class="absolute -top-16 -right-16 w-72 h-72 bg-emerald-500/10 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl"></div>
                </div>
                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-6 sm:pt-8 sm:pb-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-4" data-aos="fade-right" data-aos-delay="200">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">Riwayat Chat</h1>
                                <p class="text-slate-400 text-sm mt-0.5">Kelola percakapan AI Anda</p>
                            </div>
                        </div>
                        <a href="{{ route('ai-assist.index') }}" data-aos="fade-left" data-aos-delay="300" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-full text-sm font-semibold hover:shadow-[0_0_20px_rgba(16,185,129,0.3)] hover:scale-[1.02] transition-all duration-300 active:scale-[0.98] shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Chat Baru
                        </a>
                    </div>
                </div>
            </section>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">
                {{-- Stats --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
                    @php $statCards = [
                        ['label'=>'Total Percakapan','value'=>$stats['total_conversations'],'icon'=>'💬','gradient'=>'from-emerald-500/20 to-green-500/10','border'=>'border-emerald-500/20','text'=>'text-emerald-400'],
                        ['label'=>'Total Pesan','value'=>$stats['total_messages'],'icon'=>'📝','gradient'=>'from-emerald-500/20 to-green-500/10','border'=>'border-emerald-500/20','text'=>'text-emerald-400'],
                        ['label'=>'Waktu Respons','value'=>$stats['avg_response_time'].'s','icon'=>'⚡','gradient'=>'from-amber-500/20 to-yellow-500/10','border'=>'border-amber-500/20','text'=>'text-amber-400'],
                        ['label'=>'Chat Pertama','value'=>$stats['first_conversation_date'] ? \Carbon\Carbon::parse($stats['first_conversation_date'])->format('d M Y') : '-','icon'=>'📅','gradient'=>'from-green-500/20 to-emerald-500/10','border'=>'border-green-500/20','text'=>'text-green-400'],
                    ]; @endphp
                    @foreach($statCards as $index => $s)
                        <div class="glass-dark rounded-2xl border {{ $s['border'] }} shadow-soft p-4 hover:bg-white/5 transition-all duration-300" data-aos="zoom-in" data-aos-delay="{{ $index * 100 }}">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $s['gradient'] }} flex items-center justify-center text-lg border {{ $s['border'] }}">{{ $s['icon'] }}</div>
                                <div class="min-w-0">
                                    <p class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">{{ $s['label'] }}</p>
                                    <p class="text-lg font-bold {{ $s['text'] }} truncate">{{ $s['value'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Filters --}}
                <div class="glass-dark rounded-2xl border border-white/5 shadow-soft p-4" data-aos="fade-up" data-aos-delay="400">
                    <form method="GET" action="{{ route('chat-history.index') }}" class="flex flex-wrap gap-3 items-end">
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5">Cari Percakapan</label>
                            <input type="text" name="search" value="{{ $filters['search'] }}" placeholder="Ketik kata kunci..."
                                   class="w-full rounded-xl border border-white/10 bg-white/5 focus:bg-white/10 ring-0 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/30 px-4 py-2.5 text-sm text-white placeholder-slate-500 transition-all duration-200">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5">Dari</label>
                            <input type="date" name="from_date" value="{{ $filters['from_date'] }}"
                                   class="rounded-xl border border-white/10 bg-white/5 focus:bg-white/10 ring-0 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/30 px-4 py-2.5 text-sm text-white transition-all duration-200">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5">Sampai</label>
                            <input type="date" name="to_date" value="{{ $filters['to_date'] }}"
                                   class="rounded-xl border border-white/10 bg-white/5 focus:bg-white/10 ring-0 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/30 px-4 py-2.5 text-sm text-white transition-all duration-200">
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-green-500 text-white rounded-xl text-sm font-semibold hover:shadow-[0_0_20px_rgba(16,185,129,0.3)] hover:scale-[1.02] transition-all duration-200 active:scale-[0.98]">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>Filter
                            </button>
                            <a href="{{ route('chat-history.index') }}" class="px-4 py-2.5 glass border border-white/10 text-slate-400 rounded-xl text-sm font-medium hover:bg-white/5 hover:text-white transition-all duration-200">Reset</a>
                        </div>
                    </form>
                </div>

                {{-- Conversations --}}
                <div class="space-y-3">
                    @if($conversations->isEmpty())
                        <div class="text-center py-16 glass-dark rounded-2xl shadow-soft border border-white/5" data-aos="fade-up">
                            <div class="w-20 h-20 mx-auto mb-4 bg-slate-800 rounded-2xl flex items-center justify-center border border-white/5 shadow-inner">
                                <span class="text-3xl">💬</span>
                            </div>
                            <h3 class="text-lg font-bold text-white mb-2">Belum Ada Percakapan</h3>
                            <p class="text-sm text-slate-400 mb-4">Mulai chat dengan AI Assist untuk melihat riwayat di sini.</p>
                            <a href="{{ route('ai-assist.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-full text-sm font-semibold hover:shadow-[0_0_20px_rgba(16,185,129,0.3)] hover:scale-[1.02] transition-all duration-300">Mulai Chat</a>
                        </div>
                    @else
                        @foreach($conversations as $index => $conversation)
                            <div class="glass-dark rounded-2xl border border-white/5 shadow-soft hover:border-emerald-500/20 hover:bg-white/5 transition-all duration-300 overflow-hidden group" data-aos="fade-up" data-aos-delay="{{ $index * 60 }}">
                                <div class="p-5 flex items-start justify-between gap-4">
                                    <a href="{{ route('chat-history.show', $conversation->conversation_id) }}" class="flex-1 min-w-0">
                                        <h3 class="font-bold text-white group-hover:text-emerald-400 transition-colors truncate mb-1">{{ $conversation->title }}</h3>
                                        <p class="text-sm text-slate-400 line-clamp-2 mb-3">{{ $conversation->preview }}</p>
                                        <div class="flex flex-wrap items-center gap-3 text-[10px] text-slate-500 font-medium">
                                            <span class="flex items-center gap-1 bg-white/5 px-2.5 py-1 rounded-lg border border-white/5">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                {{ $conversation->last_message_at->format('d M Y H:i') }}
                                            </span>
                                            <span class="flex items-center gap-1 bg-white/5 px-2.5 py-1 rounded-lg border border-white/5">💬 {{ $conversation->message_count }} pesan</span>
                                            <span class="flex items-center gap-1 bg-white/5 px-2.5 py-1 rounded-lg border border-white/5">⏱️ {{ $conversation->started_at->diffForHumans($conversation->last_message_at, true) }}</span>
                                        </div>
                                    </a>
                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('chat-history.show', $conversation->conversation_id) }}" class="p-2 rounded-xl text-slate-500 hover:text-emerald-400 hover:bg-emerald-500/10 transition-all duration-200" title="Lihat">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <button onclick="deleteConversation('{{ $conversation->conversation_id }}')" class="p-2 rounded-xl text-slate-500 hover:text-rose-400 hover:bg-rose-500/10 transition-all duration-200" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="mt-6">{{ $pagination->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div id="deleteModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="glass-dark rounded-2xl p-6 max-w-sm w-full mx-4 border border-white/10 shadow-2xl">
            <div class="w-14 h-14 mx-auto mb-4 bg-rose-500/10 rounded-2xl flex items-center justify-center border border-rose-500/20"><span class="text-2xl">🗑️</span></div>
            <h3 class="text-lg font-bold text-white text-center mb-2">Konfirmasi Hapus</h3>
            <p class="text-sm text-slate-400 text-center mb-6">Apakah Anda yakin? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 glass border border-white/10 text-slate-300 rounded-xl font-medium hover:bg-white/10 transition-colors">Batal</button>
                <button id="confirmDeleteBtn" class="flex-1 px-4 py-2.5 bg-rose-500 text-white rounded-xl font-medium hover:bg-rose-600 transition-colors">Hapus</button>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- AOS Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({ duration: 800, once: true, offset: 50 });
        });

        let conversationToDelete = null;
        function deleteConversation(id) { conversationToDelete = id; document.getElementById('deleteModal').classList.remove('hidden'); document.getElementById('deleteModal').classList.add('flex'); }
        function closeDeleteModal() { document.getElementById('deleteModal').classList.add('hidden'); document.getElementById('deleteModal').classList.remove('flex'); conversationToDelete = null; }
        document.getElementById('confirmDeleteBtn').addEventListener('click', async function() {
            if (!conversationToDelete) return;
            try { const r = await fetch(`/chat-history/${conversationToDelete}`, { method:'DELETE', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}}); const d = await r.json(); if(d.success) window.location.reload(); else alert(d.error||'Gagal menghapus.'); } catch(e) { alert('Terjadi kesalahan.'); }
            closeDeleteModal();
        });
        document.getElementById('deleteModal').addEventListener('click', function(e) { if(e.target===this) closeDeleteModal(); });
    </script>
    @endpush

    <style>
        [x-cloak] { display: none !important; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
</x-app-layout>
