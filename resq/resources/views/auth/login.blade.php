<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="heading-4 text-primary-700">Selamat Datang</h2>
        <p class="body-small mt-2">Masuk ke akun ResQ Anda</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div class="animate-fade-up stagger-1">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-2 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="animate-fade-up stagger-2">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-2 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between animate-fade-up stagger-3">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="w-4 h-4 rounded-lg border-slate-300 text-primary-600 focus:ring-primary-500 transition-colors" name="remember">
                <span class="ms-2 text-sm text-slate-600">{{ __('Ingat saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors" href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="animate-fade-up stagger-4">
            <x-primary-button class="w-full justify-center py-3">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                </svg>
                {{ __('Masuk') }}
            </x-primary-button>
        </div>

        <!-- Register Link -->
        <div class="text-center animate-fade-up stagger-5">
            <p class="text-sm text-slate-500">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-700 font-medium transition-colors">
                    Daftar sekarang
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
