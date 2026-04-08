<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="heading-3 text-primary-800">
                    {{ __('Dashboard') }}
                </h2>
                <p class="body-small mt-1">Selamat datang kembali, {{ Auth::user()->name }}</p>
            </div>
            <div class="hidden sm:flex items-center gap-2">
                <span class="badge badge-success">
                    <span class="w-2 h-2 bg-primary-500 rounded-full mr-1.5 animate-pulse"></span>
                    Sistem Aktif
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 container-padding">
        <div class="max-w-7xl mx-auto space-y-8">

            <!-- Quick Actions Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 animate-fade-up">
                <!-- AI Assist Card -->
                <a href="{{ route('ai-assist.index') }}" class="card p-6 group">
                    <div class="w-12 h-12 rounded-2xl bg-primary-100 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-800 mb-1">AI Assist</h3>
                    <p class="text-sm text-slate-500">Tanya tentang bencana dan mitigasi</p>
                </a>

                <!-- Map Card -->
                <a href="{{ route('map.index') }}" class="card p-6 group">
                    <div class="w-12 h-12 rounded-2xl bg-secondary-100 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 01-.447-.894L15 7m0 13V7"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-800 mb-1">Peta Bencana</h3>
                    <p class="text-sm text-slate-500">Lihat lokasi bencana real-time</p>
                </a>

                <!-- Chat History Card -->
                <a href="{{ route('chat-history.index') }}" class="card p-6 group">
                    <div class="w-12 h-12 rounded-2xl bg-amber-100 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-800 mb-1">Riwayat Chat</h3>
                    <p class="text-sm text-slate-500">Lihat percakapan sebelumnya</p>
                </a>

                <!-- Profile Card -->
                <a href="{{ route('profile.edit') }}" class="card p-6 group">
                    <div class="w-12 h-12 rounded-2xl bg-sky-100 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-800 mb-1">Profil</h3>
                    <p class="text-sm text-slate-500">Kelola akun dan notifikasi</p>
                </a>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Stats -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 animate-fade-up stagger-2">
                        <div class="card p-5">
                            <p class="text-sm text-slate-500 mb-1">Chat AI Hari Ini</p>
                            <p class="text-2xl font-bold text-primary-600">12</p>
                            <p class="text-xs text-slate-400 mt-1">+3 dari kemarin</p>
                        </div>
                        <div class="card p-5">
                            <p class="text-sm text-slate-500 mb-1">Bencana Aktif</p>
                            <p class="text-2xl font-bold text-warning">5</p>
                            <p class="text-xs text-slate-400 mt-1">Di Indonesia</p>
                        </div>
                        <div class="card p-5">
                            <p class="text-sm text-slate-500 mb-1">Notifikasi Terkirim</p>
                            <p class="text-2xl font-bold text-secondary-600">1,234</p>
                            <p class="text-xs text-slate-400 mt-1">Bulan ini</p>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="card p-6 animate-fade-up stagger-3">
                        <h3 class="font-semibold text-slate-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Aktivitas Terbaru
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-start gap-3 pb-4 border-b border-slate-100">
                                <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-800">Chat dengan AI Assist</p>
                                    <p class="text-xs text-slate-500">"Apa yang harus dilakukan saat gempa bumi?"</p>
                                    <p class="text-xs text-slate-400 mt-1">2 jam yang lalu</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 pb-4 border-b border-slate-100">
                                <div class="w-8 h-8 rounded-full bg-secondary-100 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-800">Notifikasi Diterima</p>
                                    <p class="text-xs text-slate-500">Peringatan banjir di Jakarta</p>
                                    <p class="text-xs text-slate-400 mt-1">5 jam yang lalu</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 01-.447-.894L15 7m0 13V7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-800">Melihat Peta Bencana</p>
                                    <p class="text-xs text-slate-500">Menelusuri lokasi bencana di Jawa Barat</p>
                                    <p class="text-xs text-slate-400 mt-1">Kemarin</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Info & Tips -->
                <div class="space-y-6">
                    <!-- Emergency Contact -->
                    <div class="card p-6 border-l-4 border-danger animate-fade-up stagger-2">
                        <h3 class="font-semibold text-slate-800 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zM12 9v4m0 4h.01"></path>
                            </svg>
                            Nomor Darurat
                        </h3>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-600">BNPB</span>
                                <span class="font-semibold text-slate-800">117</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-600">Basarnas</span>
                                <span class="font-semibold text-slate-800">115</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-600">Polisi</span>
                                <span class="font-semibold text-slate-800">110</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-600">Ambulans</span>
                                <span class="font-semibold text-slate-800">118</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Tip -->
                    <div class="card p-6 bg-gradient-to-br from-primary-50 to-secondary-50 border-0 animate-fade-up stagger-3">
                        <div class="w-10 h-10 rounded-full bg-primary-200 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-primary-800 mb-2">Tips Hari Ini</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Saat terjadi gempa bumi, jangan panik. Segera mencari tempat berlindung di bawah meja atau perabotan yang kuat. Jauhi jendela dan benda yang bisa jatuh.
                        </p>
                        <a href="#" class="inline-flex items-center text-sm text-primary-600 font-medium mt-3 hover:text-primary-700">
                            Baca selengkapnya
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>

                    <!-- Newsletter -->
                    <div class="card p-6 animate-fade-up stagger-4">
                        <h3 class="font-semibold text-slate-800 mb-2">Update Bencana</h3>
                        <p class="text-sm text-slate-500 mb-4">Dapatkan notifikasi bencana di wilayah Anda</p>
                        <form class="space-y-3">
                            <input type="tel" placeholder="Nomor WhatsApp" class="input-field text-sm">
                            <button type="button" class="btn-primary w-full text-sm py-2.5">
                                Aktifkan Notifikasi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
