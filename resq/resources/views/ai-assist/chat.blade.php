<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary-500 to-secondary-500 flex items-center justify-center shadow-soft">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="heading-4 text-primary-800">
                        {{ __('AI Assist ResQ') }}
                    </h2>
                    <p class="body-small">Asisten cerdas untuk informasi mitigasi bencana</p>
                </div>
            </div>
            <button id="newChatBtn" class="btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('Chat Baru') }}
            </button>
        </div>
    </x-slot>

    <div class="py-6 container-padding">
        <div class="max-w-4xl mx-auto">
            <!-- Status Bar -->
            <div id="statusBar" class="mb-4 hidden">
                <div class="card p-4 border-l-4 border-primary-500 bg-primary-50">
                    <div class="flex items-center">
                        <div class="typing-indicator mr-3">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <span id="statusText" class="text-sm text-primary-700 font-medium">AI sedang mengetik...</span>
                    </div>
                </div>
            </div>

            <!-- Error Alert -->
            <div id="errorAlert" class="mb-4 hidden">
                <div class="card p-4 border-l-4 border-danger bg-rose-50">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-danger mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span id="errorText" class="text-sm text-rose-700 flex-1">Terjadi kesalahan. Silakan coba lagi.</span>
                        <button id="dismissError" class="text-rose-500 hover:text-rose-700 p-1 rounded-lg hover:bg-rose-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Chat Container -->
            <div class="card overflow-hidden border-0 shadow-soft-xl">
                <!-- Messages Area -->
                <div id="chatMessages" class="h-[500px] overflow-y-auto p-6 space-y-6 bg-slate-50/50">
                    <!-- Welcome Message -->
                    <div class="flex items-start space-x-4 animate-fade-in">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-500 to-secondary-500 flex items-center justify-center shadow-soft">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 bg-white rounded-2xl rounded-tl-none p-5 shadow-sm border border-slate-100">
                            <p class="text-slate-800 font-medium">Selamat datang di <span class="text-primary-600">AI Assist ResQ</span>! 👋</p>
                            <p class="text-slate-600 mt-2">Saya siap membantu Anda dengan informasi tentang:</p>
                            <ul class="mt-3 space-y-2 text-slate-600">
                                <li class="flex items-center">
                                    <span class="w-1.5 h-1.5 rounded-full bg-primary-500 mr-3"></span>
                                    Kesiapsiagaan bencana
                                </li>
                                <li class="flex items-center">
                                    <span class="w-1.5 h-1.5 rounded-full bg-secondary-500 mr-3"></span>
                                    Respons darurat
                                </li>
                                <li class="flex items-center">
                                    <span class="w-1.5 h-1.5 rounded-full bg-accent-500 mr-3"></span>
                                    Pemulihan pasca-bencana
                                </li>
                                <li class="flex items-center">
                                    <span class="w-1.5 h-1.5 rounded-full bg-sky-500 mr-3"></span>
                                    Mitigasi risiko
                                </li>
                            </ul>
                            <p class="text-slate-600 mt-4">Apa yang ingin Anda tanyakan hari ini?</p>
                        </div>
                    </div>
                </div>

                <!-- Input Area -->
                <div class="border-t border-slate-100 bg-white p-4">
                    <form id="chatForm" class="flex items-end space-x-3">
                        <div class="flex-1 relative">
                            <textarea
                                id="messageInput"
                                rows="1"
                                class="w-full resize-none rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-primary-500 px-4 py-3 pr-12 transition-all duration-200"
                                placeholder="Ketik pesan Anda di sini..."
                                maxlength="2000"
                            ></textarea>
                            <div class="absolute right-3 bottom-3 text-xs text-slate-400">
                                <span id="charCount">0</span>/2000
                            </div>
                        </div>
                        <button
                            type="submit"
                            id="sendButton"
                            class="btn-primary px-5 py-3"
                        >
                            <span id="sendText" class="hidden sm:inline">Kirim</span>
                            <svg id="sendIcon" class="w-5 h-5 sm:ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            <svg id="loadingIcon" class="animate-spin h-5 w-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </form>
                    <div class="mt-2 text-center">
                        <span class="text-xs text-slate-400">Tekan Enter untuk mengirim, Shift+Enter untuk baris baru</span>
                    </div>
                </div>
            </div>

            <!-- Conversation Info -->
            <div class="mt-4 flex flex-wrap items-center justify-between gap-4 text-sm text-slate-500">
                <div id="conversationInfo" class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-success animate-pulse"></span>
                    ID Percakapan:
                    <span id="conversationId" class="font-mono bg-slate-100 px-2 py-0.5 rounded">-</span>
                </div>
                <div id="responseTime" class="hidden flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Waktu respons: <span id="responseTimeValue" class="font-medium text-primary-600">-</span> detik
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

            // Generate new conversation ID on load
            generateNewConversation();

            // Auto-resize textarea
            messageInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 150) + 'px';
                charCount.textContent = this.value.length;
            });

            // Handle Enter key
            messageInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    chatForm.dispatchEvent(new Event('submit'));
                }
            });

            // Dismiss error
            dismissError.addEventListener('click', () => {
                errorAlert.classList.add('hidden');
            });

            // New chat button
            newChatBtn.addEventListener('click', () => {
                generateNewConversation();
                clearMessages();
                errorAlert.classList.add('hidden');
            });

            // Form submission
            chatForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const message = messageInput.value.trim();
                if (!message || isProcessing) return;

                // Add user message to chat
                addMessage('user', message);

                // Clear input
                messageInput.value = '';
                messageInput.style.height = 'auto';
                charCount.textContent = '0';

                // Show loading state
                setLoading(true);
                errorAlert.classList.add('hidden');

                try {
                    const response = await fetch('{{ route("ai-assist.chat") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            message: message,
                            conversation_id: conversationId
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        addMessage('assistant', data.reply);
                        conversationId = data.conversation_id;
                        conversationIdEl.textContent = conversationId.substring(0, 15) + '...';

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
                messageDiv.className = 'flex items-start space-x-4 animate-fade-up';

                const isUser = role === 'user';
                const avatarBg = isUser
                    ? 'bg-slate-600'
                    : 'bg-gradient-to-br from-primary-500 to-secondary-500';
                const avatarIcon = isUser
                    ? '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>'
                    : '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>';
                const bubbleClass = isUser
                    ? 'bg-primary-50 border-primary-200 rounded-2xl rounded-tr-none'
                    : 'bg-white border-slate-100 rounded-2xl rounded-tl-none';

                messageDiv.innerHTML = `
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full ${avatarBg} flex items-center justify-center shadow-sm">
                            ${avatarIcon}
                        </div>
                    </div>
                    <div class="flex-1 ${bubbleClass} p-4 shadow-sm border">
                        <div class="prose prose-sm max-w-none text-slate-800 leading-relaxed">${escapeHtml(content).replace(/\n/g, '<br>')}</div>
                        <div class="mt-2 text-xs text-slate-400">${formatTime(new Date())}</div>
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
                conversationIdEl.textContent = conversationId.substring(0, 15) + '...';
                responseTimeEl.classList.add('hidden');
            }

            function clearMessages() {
                chatMessages.innerHTML = `
                    <div class="flex items-start space-x-4 animate-fade-in">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-500 to-secondary-500 flex items-center justify-center shadow-soft">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 bg-white rounded-2xl rounded-tl-none p-5 shadow-sm border border-slate-100">
                            <p class="text-slate-800 font-medium">Selamat datang di <span class="text-primary-600">AI Assist ResQ</span>! 👋</p>
                            <p class="text-slate-600 mt-2">Saya siap membantu Anda dengan informasi tentang:</p>
                            <ul class="mt-3 space-y-2 text-slate-600">
                                <li class="flex items-center"><span class="w-1.5 h-1.5 rounded-full bg-primary-500 mr-3"></span>Kesiapsiagaan bencana</li>
                                <li class="flex items-center"><span class="w-1.5 h-1.5 rounded-full bg-secondary-500 mr-3"></span>Respons darurat</li>
                                <li class="flex items-center"><span class="w-1.5 h-1.5 rounded-full bg-accent-500 mr-3"></span>Pemulihan pasca-bencana</li>
                                <li class="flex items-center"><span class="w-1.5 h-1.5 rounded-full bg-sky-500 mr-3"></span>Mitigasi risiko</li>
                            </ul>
                            <p class="text-slate-600 mt-4">Apa yang ingin Anda tanyakan hari ini?</p>
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

    @push('styles')
    <style>
        /* Typing indicator animation */
        .typing-indicator {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .typing-indicator span {
            width: 8px;
            height: 8px;
            background-color: var(--color-primary-500);
            border-radius: 50%;
            animation: typing 1.4s infinite ease-in-out both;
        }
        .typing-indicator span:nth-child(1) { animation-delay: -0.32s; }
        .typing-indicator span:nth-child(2) { animation-delay: -0.16s; }
        @keyframes typing {
            0%, 80%, 100% { transform: scale(0.6); opacity: 0.5; }
            40% { transform: scale(1); opacity: 1; }
        }

        /* Custom scrollbar */
        #chatMessages::-webkit-scrollbar {
            width: 6px;
        }
        #chatMessages::-webkit-scrollbar-track {
            background: transparent;
        }
        #chatMessages::-webkit-scrollbar-thumb {
            background-color: var(--color-slate-300);
            border-radius: 20px;
        }
        #chatMessages::-webkit-scrollbar-thumb:hover {
            background-color: var(--color-slate-400);
        }
    </style>
    @endpush
</x-app-layout>
