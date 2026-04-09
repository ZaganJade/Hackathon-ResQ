<section>
    <header class="hidden">
        {{-- Header hidden since parent card already has the title --}}
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-slate-300 mb-1.5">{{ __('Password Saat Ini') }}</label>
            <input id="update_password_current_password" name="current_password" type="password"
                   class="w-full rounded-xl border border-white/10 bg-white/5 focus:bg-white/10 ring-0 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/30 px-4 py-2.5 text-sm text-white placeholder-slate-500 transition-all duration-200"
                   autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-medium text-slate-300 mb-1.5">{{ __('Password Baru') }}</label>
            <input id="update_password_password" name="password" type="password"
                   class="w-full rounded-xl border border-white/10 bg-white/5 focus:bg-white/10 ring-0 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/30 px-4 py-2.5 text-sm text-white placeholder-slate-500 transition-all duration-200"
                   autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-slate-300 mb-1.5">{{ __('Konfirmasi Password') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                   class="w-full rounded-xl border border-white/10 bg-white/5 focus:bg-white/10 ring-0 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/30 px-4 py-2.5 text-sm text-white placeholder-slate-500 transition-all duration-200"
                   autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-green-500 text-white rounded-xl text-sm font-semibold hover:shadow-[0_0_20px_rgba(16,185,129,0.3)] hover:scale-[1.02] transition-all duration-300 active:scale-[0.98]">
                {{ __('Simpan') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-emerald-400 font-medium">
                    {{ __('Tersimpan.') }}
                </p>
            @endif
        </div>
    </form>
</section>
