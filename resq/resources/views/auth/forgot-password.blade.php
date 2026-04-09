<x-guest-layout>
    <div class="text-center mb-6">
        <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-emerald-500/10 flex items-center justify-center">
            <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>
        <h2 class="text-xl font-bold text-white tracking-tight">Lupa Password?</h2>
    </div>

    <div class="mb-6 text-sm text-slate-400 leading-relaxed text-center">
        Tidak masalah. Masukkan email Anda dan kami akan mengirimkan link reset password.
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div class="opacity-0-start animate-slide-up delay-100">
            <label for="email" class="dark-label">Email</label>
            <input id="email" class="dark-input" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="opacity-0-start animate-slide-up delay-200">
            <button type="submit" class="btn-emerald">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Kirim Link Reset
            </button>
        </div>

        <div class="text-center opacity-0-start animate-slide-up delay-300">
            <a href="{{ route('login') }}" class="text-sm text-emerald-400 hover:text-emerald-300 font-semibold transition-colors">
                &larr; Kembali ke halaman masuk
            </a>
        </div>
    </form>
</x-guest-layout>
