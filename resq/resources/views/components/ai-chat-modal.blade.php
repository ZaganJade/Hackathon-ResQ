<!-- AI Chat Modal -->
<div id="aiChatContainer" class="fixed bottom-6 right-6 z-50">
    <!-- Floating Action Button -->
    <button id="aiChatFab" class="bg-red-600 hover:bg-red-700 text-white rounded-full p-4 shadow-lg shadow-red-600/25 transition-all duration-200 flex items-center justify-center w-14 h-14">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </button>

    <!-- Chat Modal Drawer -->
    <div id="aiChatModal" class="hidden fixed bottom-0 right-0 w-full sm:w-96 h-screen sm:h-[600px] bg-white dark:bg-gray-900 shadow-2xl flex flex-col rounded-t-xl sm:rounded-xl transform transition-all duration-300">
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-600 to-orange-600 text-white px-6 py-4 flex justify-between items-center rounded-t-xl sm:rounded-t-xl">
            <div>
                <h3 class="text-lg font-semibold">ResQ AI Assistant</h3>
                <p class="text-sm text-red-100">Ask for disaster guidance</p>
            </div>
            <button id="closeChatBtn" class="text-white hover:text-red-100 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Messages Container -->
        <div id="chatMessages" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50 dark:bg-gray-800">
            <!-- Messages will be inserted here -->
            <div class="flex justify-center items-center h-full">
                <p class="text-gray-500 dark:text-gray-400 text-sm">Start a conversation with AI Assistant</p>
            </div>
        </div>

        <!-- Input Area -->
        <div class="border-t border-gray-200 dark:border-gray-700 p-4 bg-white dark:bg-gray-900 rounded-b-xl">
            <form id="chatForm" class="flex space-x-2">
                <input type="text" id="chatInput" placeholder="Ask something..." class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent" />
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Modal Overlay (for mobile) -->
    <div id="chatOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 sm:hidden"></div>
</div>

<script>
    // AI Chat Modal Handler
    document.addEventListener('DOMContentLoaded', function() {
        const fab = document.getElementById('aiChatFab');
        const modal = document.getElementById('aiChatModal');
        const overlay = document.getElementById('chatOverlay');
        const closeBtn = document.getElementById('closeChatBtn');
        const chatForm = document.getElementById('chatForm');
        const chatInput = document.getElementById('chatInput');
        const chatMessages = document.getElementById('chatMessages');

        // Toggle modal
        function toggleModal() {
            const isHidden = modal.classList.contains('hidden');
            if (isHidden) {
                modal.classList.remove('hidden');
                overlay.classList.remove('hidden');
                chatInput.focus();
            } else {
                modal.classList.add('hidden');
                overlay.classList.add('hidden');
            }
        }

        fab.addEventListener('click', toggleModal);
        closeBtn.addEventListener('click', toggleModal);
        overlay.addEventListener('click', toggleModal);

        // Send message
        chatForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const message = chatInput.value.trim();
            if (!message) return;

            // Add user message to chat
            addMessageToChat(message, 'user');
            chatInput.value = '';

            // Send to backend
            try {
                const response = await fetch('{{ route("ai-assist.chat") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ message: message }),
                });

                if (response.ok) {
                    const data = await response.json();
                    addMessageToChat(data.response || 'Unable to process response', 'assistant');
                } else {
                    addMessageToChat('Error: Unable to get response from AI', 'assistant');
                }
            } catch (error) {
                console.error('Error:', error);
                addMessageToChat('Error: Unable to connect to AI service', 'assistant');
            }
        });

        function addMessageToChat(content, sender) {
            // Clear initial placeholder if needed
            if (chatMessages.querySelector('p')) {
                chatMessages.innerHTML = '';
            }

            const messageDiv = document.createElement('div');
            messageDiv.className = `flex ${sender === 'user' ? 'justify-end' : 'justify-start'}`;
            
            const messageBubble = document.createElement('div');
            messageBubble.className = `max-w-xs px-4 py-2 rounded-lg ${
                sender === 'user'
                    ? 'bg-red-600 text-white rounded-br-none'
                    : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white rounded-bl-none'
            }`;
            messageBubble.textContent = content;

            messageDiv.appendChild(messageBubble);
            chatMessages.appendChild(messageDiv);

            // Scroll to bottom
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    });
</script>
