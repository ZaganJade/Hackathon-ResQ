<div
    x-data="chatbotWithLocation()"
    class="fixed bottom-20 right-6 z-50 hidden lg:flex flex-col items-end"
    style="position: fixed; bottom: 5.5rem; right: 1.5rem; z-index: 9999;"
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
        class="w-[300px] sm:w-[330px] rounded-2xl overflow-hidden mb-4 flex flex-col bg-slate-900/95 backdrop-blur-2xl border border-white/[0.06] shadow-2xl shadow-black/50"
        style="height: 520px; max-height: calc(100vh - 100px); display: none;"
    >
        <!-- Header (matches AI Assist top bar) -->
        <div class="flex-shrink-0 border-b border-white/[0.06] px-4 py-3"
             :class="{
                 'bg-red-500/10': zoneStatus === 'danger',
                 'bg-amber-500/10': zoneStatus === 'warning',
                 'bg-slate-900/80': zoneStatus === 'safe' || zoneStatus === null
             }">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="relative flex-shrink-0">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center shadow-md shadow-emerald-500/20">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <!-- Zone Status Indicator Dot -->
                        <div
                            x-show="zoneStatus !== null"
                            class="absolute -top-0.5 -right-0.5 w-3 h-3 rounded-full border-2 border-slate-900"
                            :class="{
                                'bg-red-500 animate-pulse': zoneStatus === 'danger',
                                'bg-amber-400 animate-pulse': zoneStatus === 'warning',
                                'bg-emerald-400': zoneStatus === 'safe'
                            }"
                            :title="zoneLabel"
                        ></div>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-sm font-bold text-white leading-tight">AI Assist ResQ</h3>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <template x-if="zoneLabel">
                                <span class="text-xs font-medium"
                                      :class="{
                                          'text-red-400': zoneStatus === 'danger',
                                          'text-amber-400': zoneStatus === 'warning',
                                          'text-emerald-400': zoneStatus === 'safe'
                                      }"
                                      x-text="zoneLabel"></span>
                            </template>
                            <template x-if="!zoneLabel">
                                <span class="flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <span class="text-xs text-slate-400">Online — siap membantu</span>
                                </span>
                            </template>
                        </div>
                    </div>
                </div>
                <button @click="isOpen = false" class="flex-shrink-0 p-1.5 rounded-xl text-slate-400 hover:text-white hover:bg-white/[0.06] transition-all duration-200 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Zone Status Banner (when in danger/warning) -->
        <div
            x-show="zoneStatus === 'danger' || zoneStatus === 'warning'"
            class="flex-shrink-0 px-4 py-2 text-xs text-center font-medium border-b"
            :class="{
                'bg-red-500/10 text-red-300 border-red-500/10': zoneStatus === 'danger',
                'bg-amber-500/10 text-amber-300 border-amber-500/10': zoneStatus === 'warning'
            }"
        >
            <span x-show="zoneStatus === 'danger'">⚠️ ZONA BERBAHAYA — Segera cari informasi evakuasi</span>
            <span x-show="zoneStatus === 'warning'">⚡ Zona Waspada — Tetap waspada dan pantau informasi</span>
        </div>

        <!-- Location Request Banner -->
        <div
            x-show="locationStatus === 'requesting'"
            class="flex-shrink-0 px-4 py-2 text-xs text-center border-b border-white/[0.05]"
            style="background: rgba(14,165,233,0.06);"
        >
            <span class="inline-flex items-center gap-1.5 text-sky-300">
                <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                Mendeteksi lokasi Anda...
            </span>
        </div>
        <div
            x-show="locationStatus === 'error'"
            class="flex-shrink-0 px-4 py-2 text-xs text-center border-b border-white/[0.05] bg-white/[0.02]"
        >
            <span class="text-slate-400">Tidak dapat mengakses lokasi</span>
            <span class="mx-1 text-slate-600">·</span>
            <button @click="requestLocation()" class="text-emerald-400 hover:text-emerald-300 font-medium transition-colors">Coba Lagi</button>
        </div>

        <!-- Messages Area -->
        <div class="flex-1 overflow-y-auto px-4 py-4 space-y-4 chatbot-scroll" id="chat-messages" x-ref="messagesContainer">
            <!-- Initial Greeting (matches AI Assist welcome) -->
            <div class="flex items-start gap-2.5">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center shadow-sm shadow-emerald-500/20">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </div>
                <div class="flex-1 bg-white/[0.04] border border-white/[0.06] rounded-2xl rounded-tl-md p-3.5">
                    <p class="text-sm text-white font-semibold mb-1">Halo! 👋</p>
                    <p class="text-xs text-slate-400 leading-relaxed">Saya AI Assistant ResQ. Ada yang bisa saya bantu terkait informasi mitigasi, respons darurat, atau pemulihan bencana?</p>
                </div>
            </div>

            <!-- Chat History -->
            <template x-for="(msg, index) in messages" :key="index">
                <div class="flex items-start gap-2.5" :class="msg.role === 'user' ? 'flex-row-reverse' : ''">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center shadow-sm"
                             :class="msg.role === 'user'
                                 ? 'bg-gradient-to-br from-slate-600 to-slate-700'
                                 : 'bg-gradient-to-br from-emerald-500 to-green-600 shadow-emerald-500/20'">
                            <svg x-show="msg.role === 'user'" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <svg x-show="msg.role !== 'user'" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                    </div>
                    <div 
                        class="flex-1 p-3 rounded-2xl text-sm whitespace-pre-wrap leading-relaxed"
                        :class="msg.role === 'user'
                            ? 'bg-emerald-500/10 border border-emerald-500/20 rounded-tr-md text-slate-200'
                            : 'bg-white/[0.04] border border-white/[0.06] rounded-tl-md text-slate-300'"
                        x-html="formatMessage(msg.content)"
                    ></div>
                </div>
            </template>

            <!-- Loading Indicator -->
            <div x-show="isLoading" class="flex items-start gap-2.5" style="display: none;">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center shadow-sm shadow-emerald-500/20">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </div>
                <div class="bg-white/[0.04] border border-white/[0.06] rounded-2xl rounded-tl-md p-3 flex items-center gap-1.5">
                    <span class="chatbot-typing-dot"></span>
                    <span class="chatbot-typing-dot" style="animation-delay: 0.16s;"></span>
                    <span class="chatbot-typing-dot" style="animation-delay: 0.32s;"></span>
                </div>
            </div>
        </div>

        <!-- Input Area (matches AI Assist input) -->
        <div class="flex-shrink-0 border-t border-white/[0.06] px-3 py-3 bg-slate-900/80">
            <form @submit.prevent="sendMessage" class="flex items-center gap-2">
                <input 
                    type="text" 
                    x-model="newMessage" 
                    placeholder="Ketik pesan Anda..." 
                    class="flex-1 rounded-xl border border-white/10 bg-white/[0.04] focus:bg-white/[0.07] px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500/30 transition-all duration-200"
                    :disabled="isLoading"
                >
                <button 
                    type="submit" 
                    class="flex-shrink-0 px-3.5 py-2.5 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-xl hover:shadow-[0_0_15px_rgba(16,185,129,0.25)] hover:scale-[1.03] transition-all duration-300 active:scale-[0.97] disabled:opacity-40 disabled:hover:shadow-none disabled:hover:scale-100 focus:outline-none"
                    :disabled="isLoading || newMessage.trim() === ''"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                    </svg>
                </button>
            </form>
            
            <!-- Quick Prompts -->
            <div class="flex gap-1.5 overflow-x-auto mt-2 pb-0.5 chatbot-scroll-x" x-show="messages.length === 0">
                <button @click="quickAsk('Apa itu mitigasi bencana?')" type="button" class="whitespace-nowrap text-[11px] text-slate-400 hover:text-emerald-300 px-2.5 py-1 rounded-lg bg-white/[0.03] border border-white/[0.06] hover:border-emerald-500/30 hover:bg-emerald-500/5 transition-all duration-200">Mitigasi Bencana?</button>
                <button @click="quickAsk('Nomor darurat BNPB')" type="button" class="whitespace-nowrap text-[11px] text-slate-400 hover:text-emerald-300 px-2.5 py-1 rounded-lg bg-white/[0.03] border border-white/[0.06] hover:border-emerald-500/30 hover:bg-emerald-500/5 transition-all duration-200">Nomor Darurat</button>
                <button @click="quickAsk('Persiapan tas siaga bencana')" type="button" class="whitespace-nowrap text-[11px] text-slate-400 hover:text-emerald-300 px-2.5 py-1 rounded-lg bg-white/[0.03] border border-white/[0.06] hover:border-emerald-500/30 hover:bg-emerald-500/5 transition-all duration-200">Tas Siaga</button>
            </div>
        </div>
    </div>

    <!-- Floating Toggle Button -->
    <button 
        @click="isOpen = !isOpen" 
        class="group rounded-full p-4 shadow-xl transition-all duration-300 transform hover:scale-105 active:scale-95 focus:outline-none"
        :class="isOpen
            ? 'bg-slate-800 hover:bg-slate-700 text-slate-300 shadow-black/30 border border-white/[0.08]'
            : 'bg-gradient-to-br from-emerald-500 to-green-600 text-white shadow-emerald-500/30 hover:shadow-emerald-500/50 hover:shadow-2xl'"
        aria-label="Toggle AI Chatbot"
    >
        <svg x-show="!isOpen" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
        <svg x-show="isOpen" style="display: none;" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <style>
        .chatbot-scroll::-webkit-scrollbar { width: 4px; }
        .chatbot-scroll::-webkit-scrollbar-track { background: transparent; }
        .chatbot-scroll::-webkit-scrollbar-thumb { background-color: rgba(255,255,255,0.08); border-radius: 20px; }
        .chatbot-scroll::-webkit-scrollbar-thumb:hover { background-color: rgba(255,255,255,0.15); }
        .chatbot-scroll-x::-webkit-scrollbar { height: 0; display: none; }
        .chatbot-typing-dot {
            display: inline-block;
            width: 6px;
            height: 6px;
            background: linear-gradient(135deg, #10b981, #059669);
            border-radius: 50%;
            animation: chatbot-typing 1.4s infinite ease-in-out both;
        }
        @keyframes chatbot-typing {
            0%, 80%, 100% { transform: scale(0.5); opacity: 0.4; }
            40% { transform: scale(1); opacity: 1; }
        }
    </style>

    <script>
        document.addEventListener('alpine:init', () => {
            // Original chatbot data (for backward compatibility)
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

            // Location-aware chatbot
            Alpine.data('chatbotWithLocation', () => ({
                isOpen: false,
                newMessage: '',
                isLoading: false,
                messages: JSON.parse(sessionStorage.getItem('resq_ai_messages')) || [],

                // Location properties
                latitude: null,
                longitude: null,
                locationStatus: 'idle', // idle, requesting, granted, denied, error
                zoneStatus: null, // danger, warning, safe
                zoneLabel: null,
                zoneColor: null,
                nearbyDisasters: 0,
                locationErrorMessage: '',

                init() {
                    // Watch for changes and persist messages
                    this.$watch('messages', val => {
                        sessionStorage.setItem('resq_ai_messages', JSON.stringify(val));
                        this.scrollToBottom();
                    });

                    // When chat opens, request location
                    this.$watch('isOpen', val => {
                        if (val) {
                            this.scrollToBottom();
                            if (this.locationStatus === 'idle') {
                                this.requestLocation();
                            }
                        }
                    });

                    // Try to get location on init
                    if (navigator.geolocation) {
                        this.requestLocation();
                    } else {
                        this.locationStatus = 'error';
                        this.locationErrorMessage = 'Browser tidak mendukung geolocation';
                    }
                },

                async requestLocation() {
                    this.locationStatus = 'requesting';

                    try {
                        const position = await this.getCurrentPosition();
                        this.latitude = position.coords.latitude;
                        this.longitude = position.coords.longitude;
                        this.locationStatus = 'granted';

                        // Fetch zone status
                        await this.fetchZoneStatus();
                    } catch (error) {
                        console.error('Location error:', error);
                        this.locationStatus = 'error';
                        this.locationErrorMessage = this.getLocationErrorMessage(error);
                    }
                },

                getCurrentPosition() {
                    return new Promise((resolve, reject) => {
                        navigator.geolocation.getCurrentPosition(resolve, reject, {
                            enableHighAccuracy: false,
                            timeout: 10000,
                            maximumAge: 300000 // 5 minutes cache
                        });
                    });
                },

                getLocationErrorMessage(error) {
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            return 'Akses lokasi ditolak. Aktifkan izin lokasi untuk fitur peringatan bencana.';
                        case error.POSITION_UNAVAILABLE:
                            return 'Informasi lokasi tidak tersedia.';
                        case error.TIMEOUT:
                            return 'Waktu permintaan lokasi habis.';
                        default:
                            return 'Terjadi kesalahan saat mengakses lokasi.';
                    }
                },

                async fetchZoneStatus() {
                    if (!this.latitude || !this.longitude) return;

                    try {
                        const response = await fetch(`/api/v1/location/status?lat=${this.latitude}&lng=${this.longitude}`);

                        if (!response.ok) throw new Error('Failed to fetch zone status');

                        const data = await response.json();

                        if (data.success) {
                            this.zoneStatus = data.data.status;
                            this.zoneLabel = data.data.label;
                            this.zoneColor = data.data.color;
                            this.nearbyDisasters = data.data.total_disasters;

                            // If in danger zone, show alert in first message
                            if (this.zoneStatus === 'danger' && this.messages.length === 0) {
                                this.showZoneWarning(data.data);
                            }
                        }
                    } catch (error) {
                        console.error('Zone status fetch error:', error);
                    }
                },

                showZoneWarning(zoneData) {
                    const warningMsg = zoneData.label === 'Zona Berbahaya'
                        ? `⚠️ PERINGATAN: Anda berada di ${zoneData.label}! Terdeteksi ${zoneData.total_disasters} bencana di area 50km. Segera cari informasi evakuasi jika diperlukan.`
                        : `⚡ Anda berada di ${zoneData.label}. Terdeteksi ${zoneData.total_disasters} bencana di area sekitar. Tetap waspada.`;

                    this.messages.push({
                        role: 'assistant',
                        content: warningMsg,
                        isWarning: true
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

                        // Build request body with location if available
                        const requestBody = {
                            message: userText
                        };

                        if (this.latitude && this.longitude) {
                            requestBody.latitude = this.latitude;
                            requestBody.longitude = this.longitude;
                        }

                        const response = await fetch('/ai-assist/chat', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify(requestBody)
                        });

                        if (!response.ok) {
                            throw new Error('Network response status: ' + response.status);
                        }

                        const data = await response.json();

                        // Extract message - backend returns 'reply' field
                        const reply = data.reply || data.message || data.response || data.answer || 'Maaf, saya tidak dapat merespons saat ini.';

                        // Update zone status from response if available
                        if (data.location_context) {
                            this.zoneStatus = data.location_context.zone_status;
                            this.zoneLabel = data.location_context.zone_label;
                            this.zoneColor = data.location_context.zone_color;
                            this.nearbyDisasters = data.location_context.nearby_disasters_count;
                        }

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
