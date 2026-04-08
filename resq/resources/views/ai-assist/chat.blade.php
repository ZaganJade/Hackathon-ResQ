<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="bg-blue-600 p-2 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('AI Assist ResQ') }}
                    </h2>
                    <p class="text-sm text-gray-500">Asisten cerdas untuk informasi mitigasi bencana</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button id="newChatBtn" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('Chat Baru') }}
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Status Bar -->
            <div id="statusBar" class="mb-4 hidden">
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-md">
                    <div class="flex items-center">
                        <svg class="animate-spin h-5 w-5 text-blue-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span id="statusText" class="text-sm text-blue-700">AI sedang mengetik...</span>
                    </div>
                </div>
            </div>

            <!-- Error Alert -->
            <div id="errorAlert" class="mb-4 hidden">
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span id="errorText" class="text-sm text-red-700">Terjadi kesalahan. Silakan coba lagi.</span>
                        <button id="dismissError" class="ml-auto text-red-500 hover:text-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Chat Container -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <!-- Messages Area -->
                <div id="chatMessages" class="h-[500px] overflow-y-auto p-6 space-y-4 bg-gray-50">
                    <!-- Welcome Message -->
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 bg-white rounded-lg p-4 shadow-sm border border-gray-100">
                            <p class="text-gray-800">Selamat datang di <strong>AI Assist ResQ</strong>! 👋</p>
                            <p class="text-gray-600 mt-2">Saya siap membantu Anda dengan informasi tentang:</p>
                            <ul class="mt-2 space-y-1 text-gray-600">
                                <li class="flex items-center"><span class="mr-2">•</span> Kesiapsiagaan bencana</li>
                                <li class="flex items-center"><span class="mr-2">•</span> Respons darurat</li>
                                <li class="flex items-center"><span class="mr-2">•</span> Pemulihan pasca-bencana</li>
                                <li class="flex items-center"><span class="mr-2">•</span> Mitigasi risiko</li>
                            </ul>
                            <p class="text-gray-600 mt-3">Apa yang ingin Anda tanyakan hari ini?</p>
                        </div>
                    </div>
                </div>

                <!-- Input Area -->
                <div class="border-t border-gray-200 bg-white p-4">
                    <form id="chatForm" class="flex items-end space-x-3">
                        <div class="flex-1">
                            <textarea
                                id="messageInput"
                                rows="1"
                                class="w-full resize-none rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                placeholder="Ketik pesan Anda di sini..."
                                maxlength="2000"
                            ></textarea>
                            <div class="mt-1 flex justify-between items-center">
                                <span id="charCount" class="text-xs text-gray-400">0/2000</span>
                                <span class="text-xs text-gray-400">Tekan Enter untuk mengirim, Shift+Enter untuk baris baru</span>
                            </div>
                        </div>
                        <button
                            type="submit"
                            id="sendButton"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span id="sendText">Kirim</span>
                            <svg id="sendIcon" class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            <svg id="loadingIcon" class="animate-spin h-4 w-4 ml-2 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Conversation Info -->
            <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
                <div id="conversationInfo">
                    ID Percakapan: <span id="conversationId" class="font-mono">-</span>
                </div>
                <div id="responseTime" class="hidden">
                    Waktu respons: <span id="responseTimeValue">-</span> detik
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
                charCount.textContent = `${this.value.length}/2000`;
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
                charCount.textContent = '0/2000';

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
                        conversationIdEl.textContent = conversationId.substring(0, 20) + '...';

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
                messageDiv.className = 'flex items-start space-x-3 animate-fade-in';

                const isUser = role === 'user';
                const avatarBg = isUser ? 'bg-gray-600' : 'bg-blue-600';
                const avatarIcon = isUser
                    ? '<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>'
                    : '<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>';
                const bubbleBg = isUser ? 'bg-blue-50 border-blue-200' : 'bg-white border-gray-100';

                messageDiv.innerHTML = `
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full ${avatarBg} flex items-center justify-center">
                            ${avatarIcon}
                        </div>
                    </div>
                    <div class="flex-1 ${bubbleBg} rounded-lg p-4 shadow-sm border">
                        <div class="prose prose-sm max-w-none text-gray-800">${escapeHtml(content).replace(/\n/g, '<br>')}</div>
                        <div class="mt-1 text-xs text-gray-400">${formatTime(new Date())}</div>
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
                conversationIdEl.textContent = conversationId.substring(0, 20) + '...';
                responseTimeEl.classList.add('hidden');
            }

            function clearMessages() {
                chatMessages.innerHTML = `
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 bg-white rounded-lg p-4 shadow-sm border border-gray-100">
                            <p class="text-gray-800">Selamat datang di <strong>AI Assist ResQ</strong>! 👋</p>
                            <p class="text-gray-600 mt-2">Saya siap membantu Anda dengan informasi tentang:</p>
                            <ul class="mt-2 space-y-1 text-gray-600">
                                <li class="flex items-center"><span class="mr-2">•</span> Kesiapsiagaan bencana</li>
                                <li class="flex items-center"><span class="mr-2">•</span> Respons darurat</li>
                                <li class="flex items-center"><span class="mr-2">•</span> Pemulihan pasca-bencana</li>
                                <li class="flex items-center"><span class="mr-2">•</span> Mitigasi risiko</li>
                            </ul>
                            <p class="text-gray-600 mt-3">Apa yang ingin Anda tanyakan hari ini?</p>
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

            // Add animation styles
            const style = document.createElement('style');
            style.textContent = `
                .animate-fade-in {
                    animation: fadeIn 0.3s ease-in-out;
                }
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(10px); }
                    to { opacity: 1; transform: translateY(0); }
                }
            `;
            document.head.appendChild(style);
        })();
    </script>
    @endpush
</x-app-layout>
