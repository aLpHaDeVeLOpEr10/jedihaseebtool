<nav class="bg-white border-b border-gray-100 sticky top-0 z-40 backdrop-blur-sm" x-data="{ open: false, search: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-16">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 font-bold text-gray-900 hover:text-brand-600 transition-colors">
                <div class="w-8 h-8 rounded-lg hero-gradient flex items-center justify-center">
                    <span class="text-white font-bold text-sm">J</span>
                </div>
                <span class="hidden sm:block">{{ \App\Models\Setting::get('site_name', config('app.name')) }}</span>
            </a>

            {{-- Desktop Nav --}}
            <div class="hidden md:flex items-center gap-1">
                <a href="{{ route('home') }}" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-brand-600 rounded-lg hover:bg-brand-50 transition-all {{ request()->routeIs('home') ? 'text-brand-600 bg-brand-50' : '' }}">
                    Home
                </a>
                <a href="{{ route('tools.index') }}" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-brand-600 rounded-lg hover:bg-brand-50 transition-all {{ request()->routeIs('tools*') ? 'text-brand-600 bg-brand-50' : '' }}">
                    All Tools
                </a>
                <a href="{{ route('categories.index') }}" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-brand-600 rounded-lg hover:bg-brand-50 transition-all {{ request()->routeIs('categories*') ? 'text-brand-600 bg-brand-50' : '' }}">
                    Categories
                </a>
                <a href="{{ route('about') }}" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-brand-600 rounded-lg hover:bg-brand-50 transition-all {{ request()->routeIs('about') ? 'text-brand-600 bg-brand-50' : '' }}">
                    About
                </a>
            </div>

            {{-- Right side --}}
            <div class="flex items-center gap-2">
                {{-- Search toggle --}}
                <button @click="search = !search" class="p-2 text-gray-500 hover:text-brand-600 rounded-lg hover:bg-brand-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>

                @auth
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-sm">
                    Admin
                </a>
                @endauth

                {{-- Mobile menu --}}
                <button @click="open = !open" class="md:hidden p-2 text-gray-500">
                    <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="open" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Search Bar --}}
        <div x-show="search" x-cloak class="pb-3">
            <form action="{{ route('search') }}" method="GET">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="q" value="{{ request('q') }}"
                           placeholder="Search tools..."
                           class="form-input pl-10 pr-4"
                           autofocus>
                </div>
            </form>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="open" x-cloak class="md:hidden pb-4 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-2 text-sm text-gray-700 hover:text-brand-600 rounded-lg hover:bg-brand-50">Home</a>
            <a href="{{ route('tools.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:text-brand-600 rounded-lg hover:bg-brand-50">All Tools</a>
            <a href="{{ route('categories.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:text-brand-600 rounded-lg hover:bg-brand-50">Categories</a>
            <a href="{{ route('about') }}" class="block px-3 py-2 text-sm text-gray-700 hover:text-brand-600 rounded-lg hover:bg-brand-50">About</a>
            <a href="{{ route('contact') }}" class="block px-3 py-2 text-sm text-gray-700 hover:text-brand-600 rounded-lg hover:bg-brand-50">Contact</a>
        </div>
    </div>
</nav>
