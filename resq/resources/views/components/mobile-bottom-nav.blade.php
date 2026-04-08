{{-- Mobile Bottom Navigation - Reusable Component --}}
<nav class="fixed bottom-0 left-0 right-0 z-40 bg-slate-900/95 backdrop-blur-xl border-t border-white/5 shadow-lg lg:hidden">
    <div class="flex items-center justify-around h-16 max-w-lg mx-auto px-2">
        {{-- Beranda --}}
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center gap-0.5 {{ request()->routeIs('dashboard') ? 'text-emerald-400' : 'text-slate-500 hover:text-emerald-400' }} transition-colors min-w-[56px]">
            <svg class="w-6 h-6" {{ request()->routeIs('dashboard') ? 'fill="currentColor"' : 'fill="none"' }} stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v7a1 1 0 001 1h12a1 1 0 001-1V9m-9 4v2m0 0v2m0-6v2m9-2v2m0-6v2" />
            </svg>
            <span class="text-[10px] font-semibold">Beranda</span>
        </a>

        {{-- Peta --}}
        <a href="{{ route('map.index') }}" class="flex flex-col items-center justify-center gap-0.5 {{ request()->routeIs('map.*') ? 'text-emerald-400' : 'text-slate-500 hover:text-emerald-400' }} transition-colors min-w-[56px]">
            <svg class="w-6 h-6" {{ request()->routeIs('map.*') ? 'fill="currentColor"' : 'fill="none"' }} stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
            </svg>
            <span class="text-[10px] font-medium">Peta</span>
        </a>

        {{-- AI Chat (Center CTA) --}}
        <a href="{{ route('ai-assist.index') }}" class="flex flex-col items-center justify-center -mt-5">
            <div class="w-14 h-14 rounded-full bg-gradient-to-br from-emerald-500 to-primary-500 flex items-center justify-center shadow-lg shadow-emerald-500/30 ring-4 ring-slate-900 hover:shadow-emerald-500/50 transition-all duration-300">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
            </div>
            <span class="text-[10px] font-medium text-slate-500 mt-1">AI Chat</span>
        </a>

        {{-- Artikel --}}
        <a href="{{ route('articles.index') }}" class="flex flex-col items-center justify-center gap-0.5 {{ request()->routeIs('articles.*') ? 'text-emerald-400' : 'text-slate-500 hover:text-emerald-400' }} transition-colors min-w-[56px]">
            <svg class="w-6 h-6" {{ request()->routeIs('articles.*') ? 'fill="currentColor"' : 'fill="none"' }} stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
            </svg>
            <span class="text-[10px] font-medium">Artikel</span>
        </a>

        {{-- Profil --}}
        <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center gap-0.5 {{ request()->routeIs('profile.*') ? 'text-emerald-400' : 'text-slate-500 hover:text-emerald-400' }} transition-colors min-w-[56px]">
            <svg class="w-6 h-6" {{ request()->routeIs('profile.*') ? 'fill="currentColor"' : 'fill="none"' }} stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span class="text-[10px] font-medium">Profil</span>
        </a>
    </div>
</nav>
