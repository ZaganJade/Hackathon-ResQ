<x-guest-layout>
    <div class="text-center mb-6">
        <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-emerald-500/10 flex items-center justify-center">
            <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
        </div>
        <h2 class="text-xl font-bold text-white tracking-tight">Reset Password</h2>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="opacity-0-start animate-slide-up delay-100">
            <label for="email" class="dark-label">Email</label>
            <input id="email" class="dark-input" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="opacity-0-start animate-slide-up delay-200">
            <label for="password" class="dark-label">Password Baru</label>
            <input id="password" class="dark-input" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="opacity-0-start animate-slide-up delay-300">
            <label for="password_confirmation" class="dark-label">Konfirmasi Password</label>
            <input id="password_confirmation" class="dark-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password baru">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="opacity-0-start animate-slide-up delay-400">
            <button type="submit" class="btn-emerald">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Reset Password
            </button>
        </div>
    </form>
</x-guest-layout>
