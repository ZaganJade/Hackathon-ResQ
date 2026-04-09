<section class="space-y-6">
    <header class="hidden">
        {{-- Header hidden since parent card already has the title --}}
    </header>

    <p class="text-sm text-slate-400">
        {{ __('Setelah akun Anda dihapus, semua data dan informasi akan dihapus secara permanen. Sebelum menghapus akun, silakan unduh data yang ingin Anda simpan.') }}
    </p>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-5 py-2.5 bg-gradient-to-r from-rose-500 to-red-600 text-white rounded-xl text-sm font-semibold hover:shadow-[0_0_20px_rgba(239,68,68,0.3)] hover:scale-[1.02] transition-all duration-300 active:scale-[0.98]"
    >{{ __('Hapus Akun') }}</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-slate-900 border border-white/10 rounded-2xl">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-white">
                {{ __('Apakah Anda yakin ingin menghapus akun?') }}
            </h2>

            <p class="mt-2 text-sm text-slate-400">
                {{ __('Setelah akun Anda dihapus, semua data akan dihapus secara permanen. Masukkan password Anda untuk mengonfirmasi.') }}
            </p>

            <div class="mt-6">
                <label for="password" class="sr-only">{{ __('Password') }}</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="w-3/4 rounded-xl border border-white/10 bg-white/5 focus:bg-white/10 ring-0 focus:ring-2 focus:ring-rose-500/50 focus:border-rose-500/30 px-4 py-2.5 text-sm text-white placeholder-slate-500 transition-all duration-200"
                    placeholder="{{ __('Password') }}"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                        class="px-5 py-2.5 glass border border-white/10 text-slate-300 rounded-xl text-sm font-medium hover:bg-white/10 transition-all duration-200">
                    {{ __('Batal') }}
                </button>

                <button type="submit"
                        class="px-5 py-2.5 bg-gradient-to-r from-rose-500 to-red-600 text-white rounded-xl text-sm font-semibold hover:shadow-[0_0_20px_rgba(239,68,68,0.3)] hover:scale-[1.02] transition-all duration-300 active:scale-[0.98]">
                    {{ __('Hapus Akun') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
