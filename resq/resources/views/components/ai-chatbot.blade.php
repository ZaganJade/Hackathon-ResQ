<div 
    x-data="chatbot()" 
    class="fixed bottom-6 right-6 z-50 hidden lg:flex flex-col items-end"
    style="position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 9999;"
>
    <!-- Chat Window -->
    <div 
        x-show="isOpen" 
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        class="bg-white text-slate-800 w-80 sm:w-96 rounded-2xl shadow-2xl border border-slate-100 overflow-hidden mb-4 flex flex-col"
        style="height: 500px; max-height: calc(100vh - 100px); display: none;"
    >
        <!-- Header -->
        <div class="bg-primary-600 text-white p-4 flex justify-between items-center shadow-md">
            <div class="flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <h3 class="font-semibold text-lg tracking-tight">AI Assistant ResQ</h3>
            </div>
            <button @click="isOpen = false" class="text-white hover:text-primary-200 transition focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Messages Area -->
        <div class="flex-1 p-4 overflow-y-auto bg-slate-50 space-y-4" id="chat-messages" x-ref="messagesContainer">
            <!-- Initial Greeting -->
            <div class="flex flex-col space-y-1">
                <div class="bg-white border border-slate-200 text-slate-700 p-3 rounded-2xl rounded-tl-none self-start max-w-[85%] shadow-sm">
                    <p class="text-sm">Halo! Saya AI Assistant ResQ. Ada yang bisa saya bantu terkait informasi mitigasi, respons darurat, atau pemulihan bencana?</p>
                </div>
            </div>

            <!-- Chat History -->
            <template x-for="(msg, index) in messages" :key="index">
                <div class="flex flex-col space-y-1" :class="msg.role === 'user' ? 'items-end' : 'items-start'">
                    <div 
                        class="p-3 rounded-2xl max-w-[85%] shadow-sm text-sm whitespace-pre-wrap leading-relaxed"
                        :class="msg.role === 'user' ? 'bg-primary-600 text-white rounded-tr-none' : 'bg-white border border-slate-200 text-slate-700 rounded-tl-none'"
                        x-html="formatMessage(msg.content)"
                    ></div>
                </div>
            </template>

            <!-- Loading Indicator -->
            <div x-show="isLoading" class="flex flex-col space-y-1 items-start" style="display: none;">
                <div class="bg-white border border-slate-200 p-3 rounded-2xl rounded-tl-none self-start shadow-sm flex items-center space-x-2">
                    <div class="w-2 h-2 bg-primary-400 rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-primary-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    <div class="w-2 h-2 bg-primary-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-3 bg-white border-t border-slate-100">
            <form @submit.prevent="sendMessage" class="flex items-center gap-2">
                <input 
                    type="text" 
                    x-model="newMessage" 
                    placeholder="Ketik pesan Anda..." 
                    class="flex-1 border-slate-200 rounded-full px-4 py-2 text-sm focus:ring-primary-500 focus:border-primary-500 shadow-inner"
                    :disabled="isLoading"
                >
                <button 
                    type="submit" 
                    class="bg-primary-600 text-white rounded-full p-2 hover:bg-primary-700 transition disabled:opacity-50 shadow-md flex-shrink-0 focus:outline-none"
                    :disabled="isLoading || newMessage.trim() === ''"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                    </svg>
                </button>
            </form>
            
            <!-- Quick Prompts (Visible when no messages) -->
            <div class="flex gap-2 min-h-8 overflow-x-auto mt-3 pb-1 scrollbar-hide" x-show="messages.length === 0">
                <button @click="quickAsk('Apa itu mitigasi bencana?')" type="button" class="whitespace-nowrap text-xs bg-slate-50 text-slate-600 hover:bg-white/5 hover:text-primary-600 px-3 py-1.5 rounded-full transition border border-slate-200 hover:border-primary-200">Mitigasi Bencana?</button>
                <button @click="quickAsk('Nomor darurat BNPB')" type="button" class="whitespace-nowrap text-xs bg-slate-50 text-slate-600 hover:bg-white/5 hover:text-primary-600 px-3 py-1.5 rounded-full transition border border-slate-200 hover:border-primary-200">Nomor Darurat</button>
                <button @click="quickAsk('Persiapan tas siaga bencana')" type="button" class="whitespace-nowrap text-xs bg-slate-50 text-slate-600 hover:bg-white/5 hover:text-primary-600 px-3 py-1.5 rounded-full transition border border-slate-200 hover:border-primary-200">Tas Siaga</button>
            </div>
        </div>
    </div>

    <!-- Floating Toggle Button -->
    <button 
        @click="isOpen = !isOpen" 
        class="bg-primary-600 hover:bg-primary-700 text-white rounded-full p-4 shadow-xl shadow-primary-500/30 transition transform hover:scale-105 active:scale-95 focus:outline-none"
        :class="{'rotate-180 bg-slate-800 hover:bg-slate-900 shadow-slate-500/30': isOpen}"
        style="transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), background-color 0.2s;"
        aria-label="Toggle AI Chatbot"
    >
        <svg x-show="!isOpen" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
        <svg x-show="isOpen" style="display: none;" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('chatbot', () => ({
                isOpen: false,
                newMessage: '',
                isLoading: false,
                messages: JSON.parse(sessionStorage.getItem('resq_ai_messages')) || [],

                init() {
                    this.$watch('messages', val => {
                        sessionStorage.setItem('resq_ai_messages', JSON.stringify(val));
                        this.scrollToBottom();
                    });
                    this.$watch('isOpen', val => {
                        if (val) this.scrollToBottom();
                    });
                },

                quickAsk(text) {
                    this.newMessage = text;
                    this.sendMessage();
                },

                scrollToBottom() {
                    this.$nextTick(() => {
                        const container = this.$refs.messagesContainer;
                        if (container) {
                            container.scrollTop = container.scrollHeight;
                        }
                    });
                },

                formatMessage(text) {
                    return text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                               .replace(/\n/g, '<br>');
                },

                async sendMessage() {
                    if (this.newMessage.trim() === '' || this.isLoading) return;

                    const userText = this.newMessage.trim();
                    this.messages.push({ role: 'user', content: userText });
                    this.newMessage = '';
                    this.isLoading = true;
                    this.scrollToBottom();

                    try {
                        const metaCsrf = document.querySelector('meta[name="csrf-token"]');
                        const csrfToken = metaCsrf ? metaCsrf.getAttribute('content') : '';

                        const response = await fetch('/ai-assist/chat', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({ message: userText })
                        });

                        if (!response.ok) {
                            throw new Error('Network response status: ' + response.status);
                        }

                        const data = await response.json();

                        // Extract message - backend returns 'reply' field
                        const reply = data.reply || data.message || data.response || data.answer || 'Maaf, saya tidak dapat merespons saat ini.';
                        
                        this.messages.push({ 
                            role: 'assistant', 
                            content: reply 
                        });
                    } catch (error) {
                        let errorMessage = 'Terjadi kesalahan jaringan atau server. Mohon coba beberapa saat lagi.';
                        if (error.message) {
                            console.error('AI Chat Error:', error.message);
                        }
                        this.messages.push({
                            role: 'assistant',
                            content: errorMessage
                        });
                    } finally {
                        this.isLoading = false;
                        this.scrollToBottom();
                    }
                }
            }));
        });
    </script>
</div>
