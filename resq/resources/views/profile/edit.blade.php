<x-app-layout>
    @push('styles')
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @endpush

    {{-- Profil — Modern Dark Dashboard Style (Matching Edukasi) --}}
    <div class="min-h-screen bg-slate-950 pb-24 lg:pb-8" x-data="{}" x-cloak>

        {{-- DESKTOP SIDEBAR --}}
        <aside class="hidden lg:flex fixed top-0 left-0 h-full z-50 flex-col"
               x-data="{ sidebarHover: false }" @mouseenter="sidebarHover = true" @mouseleave="sidebarHover = false">
            <div class="h-full bg-slate-900/95 backdrop-blur-2xl border-r border-white/5 shadow-soft-lg flex flex-col py-6 sidebar-spring overflow-hidden" :class="sidebarHover ? 'w-64' : 'w-[72px]'">
                <div class="flex items-center gap-3 px-4 mb-8 overflow-hidden">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-400 flex items-center justify-center shrink-0 shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="font-bold text-xl text-white whitespace-nowrap transition-opacity duration-300" :class="sidebarHover ? 'opacity-100' : 'opacity-0'">ResQ</span>
                </div>
                <nav class="flex-1 flex flex-col gap-1 px-3">
                    @php $menuItems = [
                        ['route'=>'dashboard','label'=>'Beranda','icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6','active'=>'dashboard'],
                        ['route'=>'map.index','label'=>'Peta Interaktif','icon'=>'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7','active'=>'map.*'],
                        ['route'=>'guides.index','label'=>'Edukasi & Pelatihan','icon'=>'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253','active'=>'guides.*'],
                        ['route'=>'articles.index','label'=>'Berita & Info','icon'=>'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z','active'=>'articles.*'],
                        ['route'=>'chat-history.index','label'=>'Riwayat Chat','icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z','active'=>'chat-history.*'],
                        ['route'=>'ai-assist.index','label'=>'AI Assistant','icon'=>'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z','active'=>'ai-assist.*'],
                        ['route'=>'profile.edit','label'=>'Profil','icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z','active'=>'profile.*'],
                    ]; @endphp
                    @foreach($menuItems as $item)
                        <a href="{{ route($item['route']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group whitespace-nowrap {{ request()->routeIs($item['active']) ? 'menu-active' : 'text-slate-400 hover:bg-white/5 hover:text-emerald-400' }}">
                            <div class="w-7 h-7 flex items-center justify-center shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path></svg></div>
                            <span class="text-sm font-medium transition-opacity duration-300" :class="sidebarHover ? 'opacity-100' : 'opacity-0'">{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
                <div class="px-3 mt-auto relative" x-data="{ openOptions: false }">
                    <button @click="openOptions = !openOptions" @click.away="openOptions = false" class="relative z-[90] w-full flex items-center gap-3 px-3 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 overflow-hidden transition-all duration-200 group focus:outline-none">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center shrink-0 ring-2 ring-white/10 group-hover:ring-emerald-500/50 transition-all">
                            <span class="text-white font-bold text-xs">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                        </div>
                        <div class="min-w-0 text-left transition-opacity duration-300" :class="sidebarHover ? 'opacity-100' : 'opacity-0'">
                            <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </button>
                    <div x-show="openOptions" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-1 scale-95" class="absolute left-1 right-1 w-auto mx-2 bg-slate-800 border border-white/10 rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.5)] overflow-hidden z-[100] py-1" style="bottom: 100%; margin-bottom: 8px; display: none;">
                        <a href="{{ route('profile.edit') }}" class="w-full text-left px-4 py-2.5 text-sm text-slate-300 hover:bg-white/5 hover:text-white transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Edit Profil
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <div class="lg:ml-[72px]">
            {{-- Hero --}}
            <section class="relative overflow-hidden glass-dark border-b border-white/5" data-aos="fade-down" data-aos-duration="1000">
                <div class="absolute inset-0 overflow-hidden pointer-events-none">
                    <div class="absolute -top-16 -right-16 w-72 h-72 bg-emerald-500/10 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-green-500/10 rounded-full blur-3xl"></div>
                </div>
                <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-6 sm:pt-8 sm:pb-8">
                    <div class="flex items-center gap-4" data-aos="fade-right" data-aos-delay="200">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center shadow-lg shadow-emerald-500/20 ring-4 ring-white/10">
                            <span class="text-2xl font-bold text-white">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">{{ Auth::user()->name }}</h1>
                            <p class="text-slate-400 text-sm mt-0.5">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-5">
                {{-- Profile Information --}}
                <div class="glass-dark rounded-2xl border border-white/5 shadow-soft p-6 sm:p-8" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-9 h-9 rounded-xl bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20"><span class="text-sm">👤</span></div>
                        <h2 class="text-lg font-bold text-white">Informasi Profil</h2>
                    </div>
                    <div class="max-w-xl profile-dark-form">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                {{-- Password --}}
                <div class="glass-dark rounded-2xl border border-white/5 shadow-soft p-6 sm:p-8" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-9 h-9 rounded-xl bg-amber-500/10 flex items-center justify-center border border-amber-500/20"><span class="text-sm">🔑</span></div>
                        <h2 class="text-lg font-bold text-white">Ubah Password</h2>
                    </div>
                    <div class="max-w-xl profile-dark-form">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                {{-- Notification Settings --}}
                <div class="glass-dark rounded-2xl border border-white/5 shadow-soft p-6 sm:p-8" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-9 h-9 rounded-xl bg-sky-500/10 flex items-center justify-center border border-sky-500/20"><span class="text-sm">🔔</span></div>
                        <h2 class="text-lg font-bold text-white">Pengaturan Notifikasi</h2>
                    </div>
                    <div class="max-w-xl profile-dark-form">
                        @include('profile.partials.update-notification-settings-form')
                    </div>
                </div>

                {{-- Delete Account --}}
                <div class="glass-dark rounded-2xl border border-rose-500/20 shadow-soft p-6 sm:p-8" data-aos="fade-up" data-aos-delay="400">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-9 h-9 rounded-xl bg-rose-500/10 flex items-center justify-center border border-rose-500/20"><span class="text-sm">⚠️</span></div>
                        <h2 class="text-lg font-bold text-white">Hapus Akun</h2>
                    </div>
                    <div class="max-w-xl profile-dark-form">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({ duration: 800, once: true, offset: 50 });
        });
    </script>
    @endpush

    <style>
        [x-cloak] { display: none !important; }

        /* Dark Theme Overrides for Profile Forms */
        .profile-dark-form h2.text-lg.font-medium.text-gray-900,
        .profile-dark-form h2 {
            color: #f1f5f9 !important;
        }
        .profile-dark-form p.text-sm.text-gray-600,
        .profile-dark-form .text-gray-600 {
            color: #64748b !important;
        }
        .profile-dark-form .text-gray-900,
        .profile-dark-form h3.text-sm.font-medium.text-gray-900 {
            color: #e2e8f0 !important;
        }
        .profile-dark-form .text-gray-500 {
            color: #64748b !important;
        }
        .profile-dark-form .text-gray-700 {
            color: #94a3b8 !important;
        }
        .profile-dark-form .text-gray-800 {
            color: #cbd5e1 !important;
        }

        /* Form Label Overrides */
        .profile-dark-form label {
            color: #cbd5e1 !important;
        }

        /* Input Overrides */
        .profile-dark-form input[type="text"],
        .profile-dark-form input[type="email"],
        .profile-dark-form input[type="password"],
        .profile-dark-form input[type="tel"],
        .profile-dark-form select,
        .profile-dark-form textarea {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #f1f5f9 !important;
        }
        .profile-dark-form input::placeholder,
        .profile-dark-form textarea::placeholder {
            color: #475569 !important;
        }
        .profile-dark-form input:focus,
        .profile-dark-form select:focus,
        .profile-dark-form textarea:focus {
            background-color: rgba(255, 255, 255, 0.08) !important;
            border-color: rgba(16, 185, 129, 0.3) !important;
            --tw-ring-color: rgba(16, 185, 129, 0.3) !important;
        }

        /* Checkbox Overrides */
        .profile-dark-form input[type="checkbox"] {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.2) !important;
        }
        .profile-dark-form input[type="checkbox"]:checked {
            background-color: #10b981 !important;
            border-color: #10b981 !important;
        }

        /* Toggle Overrides */
        .profile-dark-form .bg-gray-200 {
            background-color: rgba(255, 255, 255, 0.1) !important;
        }
        .profile-dark-form .peer-checked\\:bg-red-600 {
            background-color: #10b981 !important;
        }

        /* Card-like containers */
        .profile-dark-form .bg-gray-50,
        .profile-dark-form .dark\\:bg-gray-800 {
            background-color: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 0.75rem;
        }

        /* Checkbox Labels in grid */
        .profile-dark-form label.flex.items-center.p-3 {
            border-color: rgba(255, 255, 255, 0.1) !important;
            background-color: transparent !important;
        }
        .profile-dark-form label.flex.items-center.p-3:hover {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }

        /* Info box */
        .profile-dark-form .bg-blue-50,
        .profile-dark-form .dark\\:bg-blue-900\\/20 {
            background-color: rgba(56, 189, 248, 0.05) !important;
            border-color: rgba(56, 189, 248, 0.15) !important;
        }
        .profile-dark-form .text-blue-600,
        .profile-dark-form .dark\\:text-blue-400 {
            color: #38bdf8 !important;
        }
        .profile-dark-form .text-blue-900,
        .profile-dark-form .dark\\:text-blue-100 {
            color: #e0f2fe !important;
        }
        .profile-dark-form .text-blue-700,
        .profile-dark-form .dark\\:text-blue-300 {
            color: #7dd3fc !important;
        }

        /* Select Dropdown */
        .profile-dark-form select option {
            background-color: #1e293b;
            color: #f1f5f9;
        }

        /* Primary Button Overrides */
        .profile-dark-form button[type="submit"]:not(.bg-red-600):not(.inline-flex),
        .profile-dark-form .inline-flex.items-center.px-4.py-2.bg-gray-800 {
            background: linear-gradient(135deg, #10b981, #059669) !important;
            border: none !important;
            color: white !important;
            border-radius: 0.75rem !important;
            font-weight: 600 !important;
            transition: all 0.3s !important;
        }
        .profile-dark-form button[type="submit"]:not(.bg-red-600):not(.inline-flex):hover {
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.3) !important;
            transform: scale(1.02) !important;
        }

        /* Danger Button */
        .profile-dark-form .bg-red-600,
        .profile-dark-form button.inline-flex.items-center.justify-center.px-4.py-2.bg-red-600 {
            background: linear-gradient(135deg, #ef4444, #dc2626) !important;
            border-radius: 0.75rem !important;
        }

        /* Secondary Button */
        .profile-dark-form .bg-white.border.border-gray-300 {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #94a3b8 !important;
            border-radius: 0.75rem !important;
        }
        .profile-dark-form .bg-white.border.border-gray-300:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
        }

        /* Success message */
        .profile-dark-form .text-green-600 {
            color: #34d399 !important;
        }
    </style>
</x-app-layout>
