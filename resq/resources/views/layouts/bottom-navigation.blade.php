<!-- Bottom Navigation - Mobile app style -->
<nav class="fixed bottom-0 left-0 right-0 z-50 bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800 sm:hidden">
    <div class="flex justify-around items-center h-20">
        <!-- Home -->
        <a href="{{ route('home.index') }}" class="flex flex-col items-center justify-center w-1/5 h-full {{ request()->routeIs('home.index') ? 'text-red-600' : 'text-gray-600 dark:text-gray-400' }} hover:text-red-600 dark:hover:text-red-500 transition">
            <svg class="w-6 h-6 mb-1" fill="{{ request()->routeIs('home.index') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v7a1 1 0 001 1h12a1 1 0 001-1V9m-9 4v2m0 0v2m0-6v2m9-2v2m0-6v2" />
            </svg>
            <span class="text-xs font-medium">Home</span>
        </a>

        <!-- News -->
        <a href="{{ route('news.index') }}" class="flex flex-col items-center justify-center w-1/5 h-full {{ request()->routeIs('news.index') ? 'text-red-600' : 'text-gray-600 dark:text-gray-400' }} hover:text-red-600 dark:hover:text-red-500 transition">
            <svg class="w-6 h-6 mb-1" fill="{{ request()->routeIs('news.index') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span class="text-xs font-medium">News</span>
        </a>

        <!-- Articles -->
        <a href="{{ route('articles.index') }}" class="flex flex-col items-center justify-center w-1/5 h-full {{ request()->routeIs('articles.index') ? 'text-red-600' : 'text-gray-600 dark:text-gray-400' }} hover:text-red-600 dark:hover:text-red-500 transition">
            <svg class="w-6 h-6 mb-1" fill="{{ request()->routeIs('articles.index') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.998 10-10.747S17.5 6.253 12 6.253z" />
            </svg>
            <span class="text-xs font-medium">Articles</span>
        </a>

        <!-- Mitigations -->
        <a href="{{ route('mitigations.index') }}" class="flex flex-col items-center justify-center w-1/5 h-full {{ request()->routeIs('mitigations.index') ? 'text-red-600' : 'text-gray-600 dark:text-gray-400' }} hover:text-red-600 dark:hover:text-red-500 transition">
            <svg class="w-6 h-6 mb-1" fill="{{ request()->routeIs('mitigations.index') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-xs font-medium">Mitigations</span>
        </a>

        <!-- Profile -->
        <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center w-1/5 h-full {{ request()->routeIs('profile.*') ? 'text-red-600' : 'text-gray-600 dark:text-gray-400' }} hover:text-red-600 dark:hover:text-red-500 transition">
            <svg class="w-6 h-6 mb-1" fill="{{ request()->routeIs('profile.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="text-xs font-medium">Profile</span>
        </a>
    </div>
</nav>

<!-- Top Navigation for Desktop and Detail Pages -->
<nav class="hidden sm:block fixed w-full top-0 z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-100 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home.index') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-red-600" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home.index')" :active="request()->routeIs('home.index')" class="text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500">
                        {{ __('Home') }}
                    </x-nav-link>
                    <x-nav-link :href="route('news.index')" :active="request()->routeIs('news.index')" class="text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500">
                        {{ __('News') }}
                    </x-nav-link>
                    <x-nav-link :href="route('articles.index')" :active="request()->routeIs('articles.index')" class="text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500">
                        {{ __('Articles') }}
                    </x-nav-link>
                    <x-nav-link :href="route('mitigations.index')" :active="request()->routeIs('mitigations.index')" class="text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500">
                        {{ __('Mitigations') }}
                    </x-nav-link>
                    <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')" class="text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500">
                        {{ __('Profile') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="flex items-center ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-300 bg-white dark:bg-gray-900 hover:text-gray-700 dark:hover:text-gray-100 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>
