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
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight truncate max-w-md">
                        {{ $title }}
                    </h2>
                    <p class="text-sm text-gray-500">
                        {{ $metadata['started_at']?->format('d M Y H:i') }} • {{ $metadata['total_messages'] }} pesan
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('chat-history.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    {{ __('Kembali') }}
                </a>
                <button onclick="exportConversation('json')" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    {{ __('Export JSON') }}
                </button>
                <button onclick="exportConversation('text')" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ __('Export Text') }}
                </button>
                <button onclick="deleteConversation('{{ $conversationId }}')" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    {{ __('Hapus') }}
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Metadata Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Total Pesan</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $metadata['total_messages'] }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Pesan Anda</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $metadata['user_messages'] }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Respons AI</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $metadata['ai_messages'] }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Waktu Respons Rata-rata</p>
                        <p class="text-lg font-semibold text-gray-800">
                            {{ $metadata['avg_response_time'] ? number_format($metadata['avg_response_time'], 2) . 's' : '-' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Conversation Messages -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 space-y-6">
                    @foreach($messages as $message)
                        <div class="flex items-start space-x-3 {{ $message->role === 'user' ? 'flex-row' : 'flex-row' }}">
                            @if($message->role === 'user')
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-gray-600 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 bg-blue-50 rounded-lg p-4 border border-blue-100">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-medium text-gray-900">Anda</span>
                                        <span class="text-xs text-gray-500">{{ $message->created_at->format('H:i, d M Y') }}</span>
                                    </div>
                                    <div class="text-gray-800 whitespace-pre-wrap">{{ $message->message }}</div>
                                </div>
                            @else
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-medium text-gray-900">AI ResQ</span>
                                        <div class="flex items-center space-x-2">
                                            @if(isset($message->metadata['response_time']))
                                                <span class="text-xs text-gray-400 flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ $message->metadata['response_time'] }}s
                                                </span>
                                            @endif
                                            <span class="text-xs text-gray-500">{{ $message->created_at->format('H:i, d M Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="text-gray-800 prose prose-sm max-w-none">{!! nl2br(e($message->message)) !!}</div>
                                    @if(isset($message->metadata['model']))
                                        <div class="mt-2 text-xs text-gray-400">
                                            Model: {{ $message->metadata['model'] }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Continue Chat Button -->
            <div class="mt-6 flex justify-center">
                <a href="{{ route('ai-assist.index') }}?conversation={{ $conversationId }}" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    Lanjutkan Percakapan
                </a>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Konfirmasi Hapus</h3>
            <p class="text-gray-500 mb-6">Apakah Anda yakin ingin menghapus percakapan ini? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex justify-end space-x-3">
                <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                    Batal
                </button>
                <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Hapus
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let conversationToDelete = null;

        function deleteConversation(conversationId) {
            conversationToDelete = conversationId;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
            conversationToDelete = null;
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', async function() {
            if (!conversationToDelete) return;

            try {
                const response = await fetch(`/chat-history/${conversationToDelete}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = '{{ route('chat-history.index') }}';
                } else {
                    alert(data.error || 'Gagal menghapus percakapan.');
                }
            } catch (error) {
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }

            closeDeleteModal();
        });

        function exportConversation(format) {
            const url = `/chat-history/{{ $conversationId }}/export?format=${format}`;

            if (format === 'text') {
                // Download as file
                const link = document.createElement('a');
                link.href = url;
                link.download = `chat-{{ $conversationId }}.txt`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            } else {
                // Open JSON in new tab
                window.open(url, '_blank');
            }
        }

        // Close modal on outside click
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
    @endpush
</x-app-layout>
