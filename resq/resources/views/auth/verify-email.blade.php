<x-guest-layout>
    <div class="text-center mb-6">
        <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-emerald-500/10 flex items-center justify-center">
            <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"/></svg>
        </div>
        <h2 class="text-xl font-bold text-white tracking-tight">Verifikasi Email</h2>
    </div>

    <div class="mb-6 text-sm text-slate-400 leading-relaxed text-center">
        Terima kasih telah mendaftar! Sebelum memulai, silakan verifikasi email Anda dengan mengklik link yang baru saja kami kirimkan. Jika belum menerima email, kami dengan senang hati mengirim ulang.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-sm text-emerald-400 text-center">
            Link verifikasi baru telah dikirim ke alamat email Anda.
        </div>
    @endif

    <div class="flex items-center justify-between gap-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-emerald text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Kirim Ulang
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-slate-500 hover:text-slate-300 font-medium transition-colors">
                Keluar
            </button>
        </form>
    </div>
</x-guest-layout>
