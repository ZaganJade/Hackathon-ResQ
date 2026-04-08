<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Notifikasi WhatsApp') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded relative" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded relative" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Current status --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Status Langganan</h3>
                @if ($preference && $preference->is_active)
                    <div class="flex items-center gap-2 text-green-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Aktif – {{ $preference->whatsapp_number }}</span>
                    </div>
                @else
                    <div class="flex items-center gap-2 text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                        <span>Tidak aktif</span>
                    </div>
                @endif
            </div>

            {{-- Subscription form --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Pengaturan Notifikasi</h3>

                <form id="notification-form" method="POST" action="{{ route('notifications.store') }}" class="space-y-5">
                    @csrf

                    {{-- Phone number --}}
                    <div>
                        <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-1">
                            Nomor WhatsApp <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="whatsapp_number"
                            name="whatsapp_number"
                            value="{{ old('whatsapp_number', $preference?->whatsapp_number) }}"
                            placeholder="08123456789 atau +628123456789"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 @error('whatsapp_number') border-red-500 @enderror"
                        >
                        @error('whatsapp_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Format: 08xxx, 628xxx, atau +628xxx</p>
                    </div>

                    {{-- Disaster type checkboxes --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Bencana (kosongkan untuk semua jenis)
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach ($disasterTypes as $key => $label)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input
                                        type="checkbox"
                                        name="disaster_types[]"
                                        value="{{ $key }}"
                                        class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                        @checked(in_array($key, old('disaster_types', $preference?->disaster_types ?? [])))
                                    >
                                    <span class="text-sm text-gray-700">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <button
                            type="submit"
                            id="subscribe-btn"
                            class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg transition"
                        >
                            {{ $preference && $preference->is_active ? 'Perbarui Preferensi' : 'Mulai Berlangganan' }}
                        </button>

                        @if ($preference && $preference->is_active)
                            <form method="POST" action="{{ route('notifications.destroy') }}" onsubmit="return confirm('Yakin ingin berhenti berlangganan?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm underline">
                                    Berhenti Berlangganan
                                </button>
                            </form>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Recent notification history --}}
            @if ($recentLogs->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Notifikasi Terakhir</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Pesan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($recentLogs as $log)
                                    <tr>
                                        <td class="px-4 py-2 text-gray-600 whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-4 py-2">
                                            @php
                                                $badgeColor = match($log->status) {
                                                    'delivered' => 'bg-green-100 text-green-800',
                                                    'sent'      => 'bg-blue-100 text-blue-800',
                                                    'pending'   => 'bg-yellow-100 text-yellow-800',
                                                    'retrying'  => 'bg-orange-100 text-orange-800',
                                                    'failed'    => 'bg-red-100 text-red-800',
                                                    default     => 'bg-gray-100 text-gray-800',
                                                };
                                            @endphp
                                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $badgeColor }}">
                                                {{ ucfirst($log->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-gray-700 truncate max-w-xs">{{ Str::limit($log->message, 80) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
