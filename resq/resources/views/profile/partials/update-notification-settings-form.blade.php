<section>
    <header class="hidden">
        {{-- Header hidden since parent card already has the title --}}
    </header>

    <p class="text-sm text-slate-400 mb-5">
        {{ __('Kelola pengaturan notifikasi WhatsApp dan preferensi peringatan bencana Anda.') }}
    </p>

    <form method="post" action="{{ route('profile.notifications') }}" class="space-y-6">
        @csrf
        @method('patch')

        <!-- WhatsApp Number -->
        <div>
            <label for="whatsapp_number" class="block text-sm font-medium text-slate-300 mb-1.5">{{ __('Nomor WhatsApp') }}</label>
            <input
                id="whatsapp_number"
                name="whatsapp_number"
                type="tel"
                class="w-full rounded-xl border border-white/10 bg-white/5 focus:bg-white/10 ring-0 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/30 px-4 py-2.5 text-sm text-white placeholder-slate-500 transition-all duration-200"
                value="{{ old('whatsapp_number', $user->notificationPreference?->whatsapp_number ?? $user->phone) }}"
                placeholder="08123456789"
                autocomplete="tel"
            />
            <p class="mt-1.5 text-xs text-slate-500">
                {{ __('Format: 08123456789 atau +628123456789') }}
            </p>
            <x-input-error class="mt-2" :messages="$errors->get('whatsapp_number')" />
        </div>

        <!-- Notifications Active Toggle -->
        <div class="flex items-center justify-between p-4 bg-white/[0.03] rounded-xl border border-white/5">
            <div>
                <h3 class="text-sm font-medium text-white">
                    {{ __('app.notification.whatsapp_notifications') }}
                </h3>
                <p class="text-sm text-slate-400 mt-0.5">
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
                <div class="w-11 h-6 bg-white/10 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-500/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
            </label>
        </div>

        <!-- Disaster Types -->
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">{{ __('app.disaster.alert_levels') }}</label>
            <p class="text-sm text-slate-500 mb-3">
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
                    <label class="flex items-center p-3 border border-white/10 rounded-xl cursor-pointer hover:bg-white/5 transition-all duration-200 group">
                        <input
                            type="checkbox"
                            name="disaster_types[]"
                            value="{{ $key }}"
                            class="w-4 h-4 bg-white/5 border-white/20 rounded text-emerald-500 focus:ring-emerald-500/50 focus:ring-offset-0"
                            {{ in_array($key, $selectedTypes) ? 'checked' : '' }}
                        >
                        <span class="ml-2.5 text-sm text-slate-300 group-hover:text-white transition-colors">{{ $label }}</span>
                    </label>
                @endforeach
            </div>

            <x-input-error class="mt-2" :messages="$errors->get('disaster_types')" />
            <x-input-error class="mt-2" :messages="$errors->get('disaster_types.*')" />
        </div>

        <!-- Alert Level Threshold -->
        <div>
            <label for="min_alert_level" class="block text-sm font-medium text-slate-300 mb-1.5">{{ __('Level Peringatan Minimum') }}</label>
            <p class="text-sm text-slate-500 mb-3">
                {{ __('Anda hanya akan menerima notifikasi untuk bencana dengan level di atas atau sama dengan yang dipilih.') }}
            </p>

            <select
                id="min_alert_level"
                name="min_alert_level"
                class="w-full rounded-xl border border-white/10 bg-white/5 focus:bg-white/10 ring-0 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/30 px-4 py-2.5 text-sm text-white transition-all duration-200"
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
                    <option value="{{ $key }}" {{ $selectedLevel === $key ? 'selected' : '' }} class="bg-slate-900 text-white">
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            <x-input-error class="mt-2" :messages="$errors->get('min_alert_level')" />
        </div>

        <!-- Info Box -->
        <div class="p-4 bg-sky-500/5 border border-sky-500/15 rounded-xl">
            <div class="flex">
                <svg class="w-5 h-5 text-sky-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="text-sm font-medium text-sky-300">
                        {{ __('Informasi Penting') }}
                    </h4>
                    <p class="mt-1 text-sm text-sky-400/80 leading-relaxed">
                        {{ __('Notifikasi akan dikirim melalui WhatsApp ke nomor yang Anda daftarkan. Pastikan nomor WhatsApp Anda aktif dan dapat menerima pesan.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-green-500 text-white rounded-xl text-sm font-semibold hover:shadow-[0_0_20px_rgba(16,185,129,0.3)] hover:scale-[1.02] transition-all duration-300 active:scale-[0.98]">
                {{ __('app.save') }}
            </button>

            @if (session('status') === 'notification-settings-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-emerald-400 font-medium">
                    {{ __('app.saved') }}
                </p>
            @endif
        </div>
    </form>
</section>
