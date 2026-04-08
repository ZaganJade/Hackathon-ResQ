<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Log Notifikasi WhatsApp') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
                @php
                    $stats = [
                        ['label' => 'Total Hari Ini',   'value' => $statistics['total_today'],   'color' => 'bg-indigo-50 text-indigo-700'],
                        ['label' => 'Pending',          'value' => $statistics['pending'],        'color' => 'bg-yellow-50 text-yellow-700'],
                        ['label' => 'Terkirim',         'value' => $statistics['sent'],           'color' => 'bg-blue-50 text-blue-700'],
                        ['label' => 'Diterima',         'value' => $statistics['delivered'],      'color' => 'bg-green-50 text-green-700'],
                        ['label' => 'Gagal',            'value' => $statistics['failed'],         'color' => 'bg-red-50 text-red-700'],
                    ];
                @endphp
                @foreach ($stats as $stat)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 flex flex-col gap-1 items-center {{ $stat['color'] }}">
                        <p class="text-2xl font-bold">{{ $stat['value'] }}</p>
                        <p class="text-xs font-medium uppercase tracking-wide opacity-75">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Success Rate --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 flex items-center gap-4">
                <span class="text-sm text-gray-600">Tingkat Keberhasilan Hari Ini:</span>
                <div class="flex-1 bg-gray-200 rounded-full h-3">
                    <div class="bg-green-500 h-3 rounded-full" style="width: {{ $statistics['success_rate'] }}%"></div>
                </div>
                <span class="text-sm font-semibold text-gray-700">{{ $statistics['success_rate'] }}%</span>
            </div>

            {{-- Filters --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                <form method="GET" action="{{ route('admin.notifications.logs') }}" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label for="status" class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                        <select id="status" name="status" class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Semua Status</option>
                            @foreach (['pending','sent','delivered','retrying','failed'] as $s)
                                <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="date" class="block text-xs font-medium text-gray-600 mb-1">Tanggal</label>
                        <input type="date" id="date" name="date" value="{{ request('date') }}"
                               class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-md transition">
                        Filter
                    </button>
                    <a href="{{ route('admin.notifications.logs') }}" class="text-sm text-gray-600 hover:underline self-center">
                        Reset
                    </a>
                </form>
            </div>

            {{-- Logs Table --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pengguna</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomor</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Percobaan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Error</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pesan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($logs as $log)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-gray-500">{{ $log->id }}</td>
                                    <td class="px-4 py-3">{{ $log->user?->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 font-mono text-xs">{{ $log->phone_number }}</td>
                                    <td class="px-4 py-3">
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
                                    <td class="px-4 py-3 text-center">{{ $log->retry_count }}</td>
                                    <td class="px-4 py-3 text-red-600 text-xs max-w-[160px] truncate" title="{{ $log->error_code }}">
                                        {{ $log->error_code ?? '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $log->created_at->format('d/m H:i') }}</td>
                                    <td class="px-4 py-3 text-gray-700 max-w-xs truncate" title="{{ $log->message }}">
                                        {{ Str::limit($log->message, 60) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-gray-400">Tidak ada log ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($logs->hasPages())
                    <div class="px-4 py-3 border-t border-gray-200">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
