<x-guest-layout>
    <div class="text-center mb-6">
        <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-emerald-500/10 flex items-center justify-center">
            <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        </div>
        <h2 class="text-xl font-bold text-white tracking-tight">Konfirmasi Password</h2>
    </div>

    <div class="mb-6 text-sm text-slate-400 leading-relaxed text-center">
        Ini adalah area aman dari aplikasi. Silakan konfirmasi password Anda sebelum melanjutkan.
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <!-- Password -->
        <div class="opacity-0-start animate-slide-up delay-100">
            <label for="password" class="dark-label">Password</label>
            <input id="password" class="dark-input" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password Anda">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="opacity-0-start animate-slide-up delay-200">
            <button type="submit" class="btn-emerald">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Konfirmasi
            </button>
        </div>
    </form>
</x-guest-layout>
