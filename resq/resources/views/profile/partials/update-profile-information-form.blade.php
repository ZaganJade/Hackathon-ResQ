<section>
    <header class="hidden">
        {{-- Header hidden since parent card already has the title --}}
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-sm font-medium text-slate-300 mb-1.5">{{ __('Nama') }}</label>
            <input id="name" name="name" type="text"
                   class="w-full rounded-xl border border-white/10 bg-white/5 focus:bg-white/10 ring-0 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/30 px-4 py-2.5 text-sm text-white placeholder-slate-500 transition-all duration-200"
                   value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">{{ __('Email') }}</label>
            <input id="email" name="email" type="email"
                   class="w-full rounded-xl border border-white/10 bg-white/5 focus:bg-white/10 ring-0 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/30 px-4 py-2.5 text-sm text-white placeholder-slate-500 transition-all duration-200"
                   value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-slate-400">
                        {{ __('Email Anda belum diverifikasi.') }}
                        <button form="send-verification" class="underline text-sm text-emerald-400 hover:text-emerald-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-emerald-400">
                            {{ __('Link verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-green-500 text-white rounded-xl text-sm font-semibold hover:shadow-[0_0_20px_rgba(16,185,129,0.3)] hover:scale-[1.02] transition-all duration-300 active:scale-[0.98]">
                {{ __('Simpan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-emerald-400 font-medium">
                    {{ __('Tersimpan.') }}
                </p>
            @endif
        </div>
    </form>
</section>
