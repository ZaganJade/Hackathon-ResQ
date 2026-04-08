<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('app.notification.settings') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Kelola pengaturan notifikasi WhatsApp dan preferensi peringatan bencana Anda.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.notifications') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- WhatsApp Number -->
        <div>
            <x-input-label for="whatsapp_number" :value="__('Nomor WhatsApp')" />
            <x-text-input
                id="whatsapp_number"
                name="whatsapp_number"
                type="tel"
                class="mt-1 block w-full"
                :value="old('whatsapp_number', $user->notificationPreference?->whatsapp_number ?? $user->phone)"
                placeholder="08123456789"
                autocomplete="tel"
            />
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('Format: 08123456789 atau +628123456789') }}
            </p>
            <x-input-error class="mt-2" :messages="$errors->get('whatsapp_number')" />
        </div>

        <!-- Notifications Active Toggle -->
        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <div>
                <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                    {{ __('app.notification.whatsapp_notifications') }}
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Aktifkan notifikasi WhatsApp untuk peringatan darurat.') }}
                </p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    class="sr-only peer"
                    {{ old('is_active', $user->notificationPreference?->is_active ?? true) ? 'checked' : '' }}
                >
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 dark:peer-focus:ring-red-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-red-600"></div>
            </label>
        </div>

        <!-- Disaster Types -->
        <div>
            <x-input-label :value="__('app.disaster.alert_levels')" />
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 mb-3">
                {{ __('Pilih jenis bencana yang ingin Anda terima notifikasinya. Biarkan kosong untuk menerima semua jenis.') }}
            </p>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                @php
                    $disasterTypes = [
                        'earthquake' => __('app.disaster.types.earthquake'),
                        'flood' => __('app.disaster.types.flood'),
                        'tsunami' => __('app.disaster.types.tsunami'),
                        'landslide' => __('app.disaster.types.landslide'),
                        'volcanic' => __('app.disaster.types.volcanic'),
                        'fire' => __('app.disaster.types.fire'),
                        'drought' => __('app.disaster.types.drought'),
                        'other' => __('app.disaster.types.other'),
                    ];
                    $selectedTypes = old('disaster_types', $user->notificationPreference?->disaster_types ?? []);
                @endphp

                @foreach($disasterTypes as $key => $label)
                    <label class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        <input
                            type="checkbox"
                            name="disaster_types[]"
                            value="{{ $key }}"
                            class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600"
                            {{ in_array($key, $selectedTypes) ? 'checked' : '' }}
                        >
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                    </label>
                @endforeach
            </div>

            <x-input-error class="mt-2" :messages="$errors->get('disaster_types')" />
            <x-input-error class="mt-2" :messages="$errors->get('disaster_types.*')" />
        </div>

        <!-- Alert Level Threshold -->
        <div>
            <x-input-label for="min_alert_level" :value="__('Level Peringatan Minimum')" />
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 mb-3">
                {{ __('Anda hanya akan menerima notifikasi untuk bencana dengan level di atas atau sama dengan yang dipilih.') }}
            </p>

            <select
                id="min_alert_level"
                name="min_alert_level"
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
            >
                @php
                    $levels = [
                        'low' => __('app.disaster.levels.low'),
                        'moderate' => __('app.disaster.levels.moderate'),
                        'high' => __('app.disaster.levels.high'),
                        'critical' => __('app.disaster.levels.critical'),
                    ];
                    $selectedLevel = old('min_alert_level', $user->notificationPreference?->min_alert_level ?? 'moderate');
                @endphp

                @foreach($levels as $key => $label)
                    <option value="{{ $key }}" {{ $selectedLevel === $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            <x-input-error class="mt-2" :messages="$errors->get('min_alert_level')" />
        </div>

        <!-- Info Box -->
        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
            <div class="flex">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100">
                        {{ __('Informasi Penting') }}
                    </h4>
                    <p class="mt-1 text-sm text-blue-700 dark:text-blue-300">
                        {{ __('Notifikasi akan dikirim melalui WhatsApp ke nomor yang Anda daftarkan. Pastikan nomor WhatsApp Anda aktif dan dapat menerima pesan.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('app.save') }}</x-primary-button>

            @if (session('status') === 'notification-settings-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('app.saved') }}</p>
            @endif
        </div>
    </form>
</section>
