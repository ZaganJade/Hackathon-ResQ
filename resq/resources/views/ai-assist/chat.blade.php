<x-app-layout>
    {{-- AI Assist — Modern Dark Dashboard Style (Matching Edukasi) --}}

    <div class="min-h-screen bg-slate-950 pb-24 lg:pb-0" x-data="{}" x-cloak>

        {{-- DESKTOP SIDEBAR --}}
        <aside class="hidden lg:flex fixed top-0 left-0 h-full z-50 flex-col"
               x-data="{ sidebarHover: false }"
               @mouseenter="sidebarHover = true"
               @mouseleave="sidebarHover = false">
            <div class="h-full bg-slate-900/95 backdrop-blur-2xl border-r border-white/5 shadow-soft-lg flex flex-col py-6 sidebar-spring overflow-hidden"
                 :class="sidebarHover ? 'w-64' : 'w-[72px]'">
                <div class="flex items-center gap-3 px-4 mb-8 overflow-hidden">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-400 flex items-center justify-center shrink-0 shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="font-bold text-xl text-white whitespace-nowrap transition-opacity duration-300"
                          :class="sidebarHover ? 'opacity-100' : 'opacity-0'">ResQ</span>
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

        {{-- MAIN CONTENT --}}
        <div class="lg:ml-[72px] flex flex-col h-screen lg:h-screen">

            {{-- Top Bar --}}
            <div class="flex-shrink-0 glass-dark border-b border-white/5">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-lg shadow-violet-500/20">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-base sm:text-lg font-bold text-white">AI Assist ResQ</h1>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <span class="text-xs text-slate-400">Online — siap membantu</span>
                                </div>
                            </div>
                        </div>
                        <button id="newChatBtn" class="inline-flex items-center gap-2 px-4 py-2.5 glass border border-white/10 rounded-full text-sm font-semibold text-slate-300 hover:text-white hover:bg-white/10 hover:border-emerald-500/30 transition-all duration-300 hover:scale-[1.02] active:scale-[0.98]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span class="hidden sm:inline">Chat Baru</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Status Bar --}}
            <div id="statusBar" class="hidden flex-shrink-0">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
                    <div class="glass-dark rounded-2xl p-3 border border-violet-500/20 shadow-soft">
                        <div class="flex items-center">
                            <div class="typing-indicator mr-3">
                                <span></span><span></span><span></span>
                            </div>
                            <span id="statusText" class="text-sm text-violet-400 font-medium">AI sedang mengetik...</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Error Alert --}}
            <div id="errorAlert" class="hidden flex-shrink-0">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
                    <div class="glass-dark rounded-2xl p-3 border border-rose-500/20 shadow-soft">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-rose-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span id="errorText" class="text-sm text-rose-400 flex-1">Terjadi kesalahan. Silakan coba lagi.</span>
                            <button id="dismissError" class="text-rose-400 hover:text-rose-300 p-1 rounded-lg hover:bg-rose-500/10 transition-colors ml-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Chat Messages --}}
            <div id="chatMessages" class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 py-6 space-y-5 max-w-4xl mx-auto w-full">
                {{-- Welcome Message --}}
                <div class="flex items-start gap-3 animate-fade-up">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-md shadow-violet-500/20">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                    </div>
                    <div class="flex-1 glass-dark rounded-2xl rounded-tl-md p-5 border border-white/5 shadow-soft">
                        <p class="text-white font-semibold">Selamat datang di <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-400 to-emerald-400">AI Assist ResQ</span>! 👋</p>
                        <p class="text-slate-400 mt-2 text-sm">Saya siap membantu Anda dengan informasi tentang:</p>
                        <div class="mt-3 grid grid-cols-2 gap-2">
                            <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20">
                                <div class="w-7 h-7 rounded-lg bg-emerald-500/20 flex items-center justify-center"><span class="text-xs">🛡️</span></div>
                                <span class="text-xs font-medium text-slate-300">Kesiapsiagaan bencana</span>
                            </div>
                            <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-rose-500/10 border border-rose-500/20">
                                <div class="w-7 h-7 rounded-lg bg-rose-500/20 flex items-center justify-center"><span class="text-xs">🚨</span></div>
                                <span class="text-xs font-medium text-slate-300">Respons darurat</span>
                            </div>
                            <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-amber-500/10 border border-amber-500/20">
                                <div class="w-7 h-7 rounded-lg bg-amber-500/20 flex items-center justify-center"><span class="text-xs">🏗️</span></div>
                                <span class="text-xs font-medium text-slate-300">Pemulihan pasca-bencana</span>
                            </div>
                            <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-sky-500/10 border border-sky-500/20">
                                <div class="w-7 h-7 rounded-lg bg-sky-500/20 flex items-center justify-center"><span class="text-xs">📋</span></div>
                                <span class="text-xs font-medium text-slate-300">Mitigasi risiko</span>
                            </div>
                        </div>
                        <p class="text-slate-400 mt-4 text-sm">Apa yang ingin Anda tanyakan hari ini?</p>
                    </div>
                </div>
            </div>

            {{-- Input Area — Fixed Bottom --}}
            <div class="flex-shrink-0 glass-dark border-t border-white/5">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4">
                    <form id="chatForm" class="flex items-end gap-3">
                        <div class="flex-1 relative">
                            <textarea
                                id="messageInput"
                                rows="1"
                                class="w-full resize-none rounded-2xl border border-white/10 bg-white/5 focus:bg-white/10 ring-0 focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500/30 px-5 py-3.5 pr-14 text-sm text-white placeholder-slate-500 transition-all duration-200"
                                placeholder="Ketik pesan Anda di sini..."
                                maxlength="2000"
                            ></textarea>
                            <div class="absolute right-4 bottom-3.5 text-[10px] text-slate-500 font-mono">
                                <span id="charCount">0</span>/2K
                            </div>
                        </div>
                        <button type="submit" id="sendButton"
                                class="px-5 py-3.5 bg-gradient-to-r from-violet-500 to-purple-600 text-white rounded-2xl hover:shadow-[0_0_20px_rgba(139,92,246,0.3)] hover:scale-[1.02] transition-all duration-300 active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2 font-semibold text-sm shadow-md">
                            <span id="sendText" class="hidden sm:inline">Kirim</span>
                            <svg id="sendIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            <svg id="loadingIcon" class="animate-spin h-5 w-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </form>
                    <div class="flex items-center justify-between mt-2 px-1">
                        <span class="text-[10px] text-slate-500">Enter kirim · Shift+Enter baris baru</span>
                        <div id="conversationInfo" class="flex items-center gap-1.5 text-[10px] text-slate-500">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="hidden sm:inline">ID:</span>
                            <span id="conversationId" class="font-mono">-</span>
                            <span id="responseTime" class="hidden ml-2">
                                · <span id="responseTimeValue" class="text-violet-400 font-medium">-</span>s
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (function() {
            const chatMessages = document.getElementById('chatMessages');
            const chatForm = document.getElementById('chatForm');
            const messageInput = document.getElementById('messageInput');
            const sendButton = document.getElementById('sendButton');
            const sendText = document.getElementById('sendText');
            const sendIcon = document.getElementById('sendIcon');
            const loadingIcon = document.getElementById('loadingIcon');
            const statusBar = document.getElementById('statusBar');
            const statusText = document.getElementById('statusText');
            const errorAlert = document.getElementById('errorAlert');
            const errorText = document.getElementById('errorText');
            const dismissError = document.getElementById('dismissError');
            const newChatBtn = document.getElementById('newChatBtn');
            const conversationIdEl = document.getElementById('conversationId');
            const responseTimeEl = document.getElementById('responseTime');
            const responseTimeValue = document.getElementById('responseTimeValue');
            const charCount = document.getElementById('charCount');

            let conversationId = null;
            let isProcessing = false;

            generateNewConversation();

            messageInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 150) + 'px';
                charCount.textContent = this.value.length;
            });

            messageInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    chatForm.dispatchEvent(new Event('submit'));
                }
            });

            dismissError.addEventListener('click', () => {
                errorAlert.classList.add('hidden');
            });

            newChatBtn.addEventListener('click', () => {
                generateNewConversation();
                clearMessages();
                errorAlert.classList.add('hidden');
            });

            chatForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const message = messageInput.value.trim();
                if (!message || isProcessing) return;

                addMessage('user', message);
                messageInput.value = '';
                messageInput.style.height = 'auto';
                charCount.textContent = '0';
                setLoading(true);
                errorAlert.classList.add('hidden');

                try {
                    const response = await fetch('{{ route("ai-assist.chat") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ message, conversation_id: conversationId })
                    });

                    const data = await response.json();

                    if (data.success) {
                        addMessage('assistant', data.reply);
                        conversationId = data.conversation_id;
                        conversationIdEl.textContent = conversationId.substring(0, 12) + '…';
                        if (data.response_time) {
                            responseTimeEl.classList.remove('hidden');
                            responseTimeValue.textContent = data.response_time;
                        }
                    } else {
                        throw new Error(data.error || 'Terjadi kesalahan');
                    }
                } catch (error) {
                    showError(error.message || 'Gagal terhubung ke AI. Silakan coba lagi.');
                } finally {
                    setLoading(false);
                }
            });

            function addMessage(role, content) {
                const messageDiv = document.createElement('div');
                messageDiv.className = 'flex items-start gap-3 animate-fade-up';

                const isUser = role === 'user';
                const avatarClass = isUser
                    ? 'bg-gradient-to-br from-slate-600 to-slate-700'
                    : 'bg-gradient-to-br from-violet-500 to-purple-600';
                const avatarIcon = isUser
                    ? '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>'
                    : '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>';
                const bubbleClass = isUser
                    ? 'bg-emerald-500/10 border-emerald-500/20 rounded-2xl rounded-tr-md'
                    : 'glass-dark border-white/5 rounded-2xl rounded-tl-md';

                messageDiv.innerHTML = `
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-2xl ${avatarClass} flex items-center justify-center shadow-md">${avatarIcon}</div>
                    </div>
                    <div class="flex-1 ${bubbleClass} p-4 border shadow-soft">
                        <div class="prose prose-sm max-w-none text-slate-300 leading-relaxed">${escapeHtml(content).replace(/\n/g, '<br>')}</div>
                        <div class="mt-2 text-[10px] text-slate-500 font-medium">${formatTime(new Date())}</div>
                    </div>
                `;

                chatMessages.appendChild(messageDiv);
                scrollToBottom();
            }

            function setLoading(loading) {
                isProcessing = loading;
                sendButton.disabled = loading;
                if (loading) {
                    sendText.textContent = 'Mengirim...';
                    sendIcon.classList.add('hidden');
                    loadingIcon.classList.remove('hidden');
                    statusBar.classList.remove('hidden');
                    statusText.textContent = 'AI sedang mengetik...';
                } else {
                    sendText.textContent = 'Kirim';
                    sendIcon.classList.remove('hidden');
                    loadingIcon.classList.add('hidden');
                    statusBar.classList.add('hidden');
                }
            }

            function showError(message) {
                errorText.textContent = message;
                errorAlert.classList.remove('hidden');
            }

            function generateNewConversation() {
                conversationId = 'conv_' + Math.random().toString(36).substring(2, 18);
                conversationIdEl.textContent = conversationId.substring(0, 12) + '…';
                responseTimeEl.classList.add('hidden');
            }

            function clearMessages() {
                chatMessages.innerHTML = `
                    <div class="flex items-start gap-3 animate-fade-up">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-md shadow-violet-500/20">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                        </div>
                        <div class="flex-1 glass-dark rounded-2xl rounded-tl-md p-5 border border-white/5 shadow-soft">
                            <p class="text-white font-semibold">Selamat datang di <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-400 to-emerald-400">AI Assist ResQ</span>! 👋</p>
                            <p class="text-slate-400 mt-2 text-sm">Saya siap membantu Anda dengan informasi tentang:</p>
                            <div class="mt-3 grid grid-cols-2 gap-2">
                                <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20">
                                    <div class="w-7 h-7 rounded-lg bg-emerald-500/20 flex items-center justify-center"><span class="text-xs">🛡️</span></div>
                                    <span class="text-xs font-medium text-slate-300">Kesiapsiagaan bencana</span>
                                </div>
                                <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-rose-500/10 border border-rose-500/20">
                                    <div class="w-7 h-7 rounded-lg bg-rose-500/20 flex items-center justify-center"><span class="text-xs">🚨</span></div>
                                    <span class="text-xs font-medium text-slate-300">Respons darurat</span>
                                </div>
                                <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-amber-500/10 border border-amber-500/20">
                                    <div class="w-7 h-7 rounded-lg bg-amber-500/20 flex items-center justify-center"><span class="text-xs">🏗️</span></div>
                                    <span class="text-xs font-medium text-slate-300">Pemulihan pasca-bencana</span>
                                </div>
                                <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-sky-500/10 border border-sky-500/20">
                                    <div class="w-7 h-7 rounded-lg bg-sky-500/20 flex items-center justify-center"><span class="text-xs">📋</span></div>
                                    <span class="text-xs font-medium text-slate-300">Mitigasi risiko</span>
                                </div>
                            </div>
                            <p class="text-slate-400 mt-4 text-sm">Apa yang ingin Anda tanyakan hari ini?</p>
                        </div>
                    </div>
                `;
            }

            function scrollToBottom() {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            function formatTime(date) {
                return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            }
        })();
    </script>
    @endpush

    <style>
        [x-cloak] { display: none !important; }
        .typing-indicator { display: flex; align-items: center; gap: 4px; }
        .typing-indicator span {
            width: 7px; height: 7px;
            background: linear-gradient(135deg, #8b5cf6, #10b981);
            border-radius: 50%;
            animation: typing 1.4s infinite ease-in-out both;
        }
        .typing-indicator span:nth-child(1) { animation-delay: -0.32s; }
        .typing-indicator span:nth-child(2) { animation-delay: -0.16s; }
        @keyframes typing {
            0%, 80%, 100% { transform: scale(0.6); opacity: 0.4; }
            40% { transform: scale(1); opacity: 1; }
        }
        #chatMessages::-webkit-scrollbar { width: 5px; }
        #chatMessages::-webkit-scrollbar-track { background: transparent; }
        #chatMessages::-webkit-scrollbar-thumb { background-color: rgba(255,255,255,0.1); border-radius: 20px; }
        #chatMessages::-webkit-scrollbar-thumb:hover { background-color: rgba(255,255,255,0.2); }
    </style>
</x-app-layout>
