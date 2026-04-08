<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="heading-4 text-primary-700">Buat Akun Baru</h2>
        <p class="body-small mt-2">Daftar untuk akses fitur ResQ</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div class="animate-fade-up stagger-1">
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" class="block mt-2 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Masukkan nama lengkap" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="animate-fade-up stagger-2">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-2 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="animate-fade-up stagger-3">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-2 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password"
                            placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="animate-fade-up stagger-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
            <x-text-input id="password_confirmation" class="block mt-2 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password"
                            placeholder="Ulangi password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <div class="animate-fade-up stagger-5">
            <x-primary-button class="w-full justify-center py-3">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                {{ __('Daftar Sekarang') }}
            </x-primary-button>
        </div>

        <!-- Login Link -->
        <div class="text-center animate-fade-up stagger-6">
            <p class="text-sm text-slate-500">
                Sudah punya akun?
                <a class="text-primary-600 hover:text-primary-700 font-medium transition-colors" href="{{ route('login') }}">
                    Masuk di sini
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
