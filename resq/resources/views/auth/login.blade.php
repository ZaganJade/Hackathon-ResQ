<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-white tracking-tight">Selamat Datang</h2>
        <p class="text-sm text-slate-400 mt-2">Masuk ke akun ResQ Anda</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div class="opacity-0-start animate-slide-up delay-100">
            <label for="email" class="dark-label">Email</label>
            <input id="email" class="dark-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@email.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="opacity-0-start animate-slide-up delay-200">
            <label for="password" class="dark-label">Password</label>
            <input id="password" class="dark-input" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between opacity-0-start animate-slide-up delay-300">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="w-4 h-4 rounded border-slate-600 bg-white/5 text-emerald-500 focus:ring-emerald-500/30 focus:ring-offset-0 transition-colors" name="remember">
                <span class="ms-2 text-sm text-slate-400">{{ __('Ingat saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-emerald-400 hover:text-emerald-300 font-medium transition-colors" href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="opacity-0-start animate-slide-up delay-400">
            <button type="submit" class="btn-emerald animate-glow">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                </svg>
                {{ __('Masuk') }}
            </button>
        </div>

        <!-- Divider -->
        <div class="relative opacity-0-start animate-slide-up delay-400">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-white/10"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-3 bg-transparent text-slate-500 backdrop-blur-sm">atau</span>
            </div>
        </div>

        <!-- Google Login Button -->
        <div class="opacity-0-start animate-slide-up delay-500">
            <a href="{{ route('auth.google') }}" class="flex w-full items-center justify-center gap-3 rounded-full border border-white/10 bg-white/[0.04] px-4 py-3 text-sm font-semibold text-slate-300 hover:bg-white/[0.08] hover:border-white/20 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition-all duration-300">
                <svg class="h-5 w-5" viewBox="0 0 24 24">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Lanjutkan dengan Google
            </a>
        </div>

        <!-- Register Link -->
        <div class="text-center opacity-0-start animate-slide-up delay-600">
            <p class="text-sm text-slate-500">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-emerald-400 hover:text-emerald-300 font-semibold transition-colors">
                    Daftar sekarang
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
