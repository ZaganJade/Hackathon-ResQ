<x-app-layout>
    {{-- Chat Detail — Fluid Modern Dashboard Style --}}
    <div class="min-h-screen bg-gradient-to-b from-green-50/60 via-white to-slate-50 pb-24 lg:pb-8" x-data="{}" x-cloak>

        {{-- DESKTOP SIDEBAR --}}
        <aside class="hidden lg:flex fixed top-0 left-0 h-full z-50 flex-col"
               x-data="{ sidebarHover: false }" @mouseenter="sidebarHover = true" @mouseleave="sidebarHover = false">
            <div class="h-full bg-slate-900/95 backdrop-blur-2xl border-r border-white/5 shadow-soft-lg flex flex-col py-6 sidebar-spring overflow-hidden" :class="sidebarHover ? 'w-64' : 'w-[72px]'">
                <div class="flex items-center gap-3 px-4 mb-8 overflow-hidden">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-primary-500 to-emerald-400 flex items-center justify-center shrink-0 shadow-md">
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
                <div class="px-3 mt-auto">
                    <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-white/5 overflow-hidden">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-400 to-emerald-500 flex items-center justify-center shrink-0 ring-2 ring-white"><span class="text-white font-bold text-xs">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span></div>
                        <div class="min-w-0 transition-opacity duration-300" :class="sidebarHover ? 'opacity-100' : 'opacity-0'">
                            <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <div class="lg:ml-[72px]">
            {{-- Top Bar --}}
            <div class="bg-white/80 backdrop-blur-xl border-b border-slate-100/80 shadow-sm animate-fade-up">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <a href="{{ route('chat-history.index') }}" class="p-2 rounded-xl text-slate-400 hover:text-primary-600 hover:bg-white/5 transition-all duration-200 flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            </a>
                            <div class="min-w-0">
                                <h1 class="text-base sm:text-lg font-bold text-slate-800 truncate">{{ $title }}</h1>
                                <p class="text-xs text-slate-400">{{ $metadata['started_at']?->format('d M Y H:i') }} · {{ $metadata['total_messages'] }} pesan</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <button onclick="exportConversation('json')" class="p-2 rounded-xl text-slate-400 hover:text-primary-600 hover:bg-white/5 transition-all duration-200" title="Export JSON"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg></button>
                            <button onclick="exportConversation('text')" class="p-2 rounded-xl text-slate-400 hover:text-primary-600 hover:bg-white/5 transition-all duration-200" title="Export Text"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></button>
                            <button onclick="deleteConversation('{{ $conversationId }}')" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all duration-200" title="Hapus"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
                {{-- Stats --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 animate-fade-up stagger-2">
                    @php $metaCards = [
                        ['l'=>'Total Pesan','v'=>$metadata['total_messages'],'i'=>'💬'],
                        ['l'=>'Pesan Anda','v'=>$metadata['user_messages'],'i'=>'👤'],
                        ['l'=>'Respons AI','v'=>$metadata['ai_messages'],'i'=>'🤖'],
                        ['l'=>'Waktu Respons','v'=>$metadata['avg_response_time'] ? number_format($metadata['avg_response_time'],2).'s' : '-','i'=>'⚡'],
                    ]; @endphp
                    @foreach($metaCards as $m)
                        <div class="bg-white rounded-2xl shadow-card p-4 text-center">
                            <span class="text-xl">{{ $m['i'] }}</span>
                            <p class="text-lg font-bold text-slate-800 mt-1">{{ $m['v'] }}</p>
                            <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">{{ $m['l'] }}</p>
                        </div>
                    @endforeach
                </div>

                {{-- Messages --}}
                <div class="space-y-4 animate-fade-up stagger-3">
                    @foreach($messages as $message)
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                @if($message->role === 'user')
                                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-slate-600 to-slate-700 flex items-center justify-center shadow-md">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-violet-500 to-primary-500 flex items-center justify-center shadow-md">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 {{ $message->role === 'user' ? 'bg-gradient-to-br from-primary-50 to-emerald-50 border-primary-100/40 rounded-2xl rounded-tr-md' : 'bg-white border-slate-100/60 rounded-2xl rounded-tl-md' }} p-4 shadow-card border">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold {{ $message->role === 'user' ? 'text-primary-700' : 'text-violet-600' }}">{{ $message->role === 'user' ? 'Anda' : 'AI ResQ' }}</span>
                                    <div class="flex items-center gap-2">
                                        @if($message->role !== 'user' && isset($message->metadata['response_time']))
                                            <span class="text-[10px] text-slate-300">⚡{{ $message->metadata['response_time'] }}s</span>
                                        @endif
                                        <span class="text-[10px] text-slate-300">{{ $message->created_at->format('H:i, d M') }}</span>
                                    </div>
                                </div>
                                <div class="text-sm text-slate-700 prose prose-sm max-w-none leading-relaxed">{!! nl2br(e($message->message)) !!}</div>
                                @if($message->role !== 'user' && isset($message->metadata['model']))
                                    <div class="mt-2 text-[10px] text-slate-300 font-mono">{{ $message->metadata['model'] }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Continue Button --}}
                <div class="text-center animate-fade-up stagger-5">
                    <a href="{{ route('ai-assist.index') }}?conversation={{ $conversationId }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-violet-500 to-primary-500 text-white rounded-full text-sm font-semibold hover:shadow-lg hover:scale-[1.02] transition-all duration-300 active:scale-[0.98] shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        Lanjutkan Percakapan
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div id="deleteModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-6 max-w-sm w-full mx-4 shadow-soft-lg">
            <div class="w-14 h-14 mx-auto mb-4 bg-rose-100 rounded-2xl flex items-center justify-center"><span class="text-2xl">🗑️</span></div>
            <h3 class="text-lg font-bold text-slate-800 text-center mb-2">Konfirmasi Hapus</h3>
            <p class="text-sm text-slate-400 text-center mb-6">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-medium hover:bg-slate-200 transition-colors">Batal</button>
                <button id="confirmDeleteBtn" class="flex-1 px-4 py-2.5 bg-rose-500 text-white rounded-xl font-medium hover:bg-rose-600 transition-colors">Hapus</button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let conversationToDelete = null;
        function deleteConversation(id) { conversationToDelete = id; document.getElementById('deleteModal').classList.remove('hidden'); document.getElementById('deleteModal').classList.add('flex'); }
        function closeDeleteModal() { document.getElementById('deleteModal').classList.add('hidden'); document.getElementById('deleteModal').classList.remove('flex'); conversationToDelete = null; }
        document.getElementById('confirmDeleteBtn').addEventListener('click', async function() {
            if (!conversationToDelete) return;
            try { const r = await fetch(`/chat-history/${conversationToDelete}`, { method:'DELETE', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}}); const d = await r.json(); if(d.success) window.location.href='{{ route('chat-history.index') }}'; else alert(d.error||'Gagal.'); } catch(e) { alert('Error.'); }
            closeDeleteModal();
        });
        function exportConversation(format) {
            const url = `/chat-history/{{ $conversationId }}/export?format=${format}`;
            if (format==='text') { const a=document.createElement('a'); a.href=url; a.download=`chat-{{ $conversationId }}.txt`; document.body.appendChild(a); a.click(); document.body.removeChild(a); } else { window.open(url,'_blank'); }
        }
        document.getElementById('deleteModal').addEventListener('click', function(e) { if(e.target===this) closeDeleteModal(); });
    </script>
    @endpush
    <style>[x-cloak]{display:none!important}</style>
</x-app-layout>
