@extends('layouts.public')

@section('title', 'All Tools - ' . \App\Models\Setting::get('site_name', 'Toolsearch'))
@section('description', 'Browse our complete library of free online tools — calculators, converters, generators and more.')

@section('content')
<div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
        <h1 class="text-3xl font-bold text-gray-900">All Tools</h1>
        <p class="text-gray-500 mt-2">{{ $tools->total() }} tools available — all free, no account needed</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
    <div class="flex flex-col lg:flex-row gap-8">

        {{-- Sidebar Filters --}}
        <aside class="lg:w-64 flex-shrink-0" x-data="{ open: false }">
            <button @click="open = !open" class="lg:hidden w-full btn btn-outline mb-4 flex items-center justify-between">
                <span>Filters</span>
                <svg class="w-4 h-4" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div class="hidden lg:block space-y-6" :class="open ? '!block' : ''">
                {{-- Filters are submitted as GET forms rather than links.
                     Crawlers do not submit forms, so the category/type/sort
                     combinations never become crawlable URLs, while the
                     controls still work with JavaScript disabled. --}}

                {{-- Category Filter --}}
                <div class="card p-5">
                    <h3 class="font-semibold text-gray-900 text-sm mb-3">Category</h3>
                    <form method="GET" action="{{ route('tools.index') }}" class="space-y-1.5">
                        @if(request('type'))<input type="hidden" name="type" value="{{ request('type') }}">@endif
                        @if(request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}">@endif

                        <button type="submit" name="category" value=""
                                class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm text-left transition-colors {{ !request('category') ? 'bg-brand-50 text-brand-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                            <span>All Categories</span>
                            <span class="text-xs text-gray-400">{{ $tools->total() }}</span>
                        </button>
                        @foreach($categories as $cat)
                        <button type="submit" name="category" value="{{ $cat->slug }}"
                                class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm text-left transition-colors {{ request('category') === $cat->slug ? 'bg-brand-50 text-brand-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                            <span class="flex items-center gap-2">
                                <span>{{ $cat->icon }}</span>
                                <span>{{ $cat->name }}</span>
                            </span>
                            <span class="text-xs text-gray-400">{{ $cat->active_tools_count }}</span>
                        </button>
                        @endforeach
                    </form>
                    <p class="mt-3 text-xs text-gray-400">
                        Browse category pages: <a href="{{ route('categories.index') }}" class="text-brand-600 hover:underline">all categories</a>
                    </p>
                </div>

                {{-- Tool Type Filter --}}
                <div class="card p-5">
                    <h3 class="font-semibold text-gray-900 text-sm mb-3">Tool Type</h3>
                    <form method="GET" action="{{ route('tools.index') }}" class="space-y-1.5">
                        @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                        @if(request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}">@endif

                        <button type="submit" name="type" value=""
                                class="w-full block px-3 py-2 rounded-lg text-sm text-left transition-colors {{ !request('type') ? 'bg-brand-50 text-brand-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                            All Types
                        </button>
                        @foreach($toolTypes as $type)
                        <button type="submit" name="type" value="{{ $type }}"
                                class="w-full block px-3 py-2 rounded-lg text-sm text-left capitalize transition-colors {{ request('type') === $type ? 'bg-brand-50 text-brand-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                            {{ ucfirst($type) }}
                        </button>
                        @endforeach
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1">
            {{-- Sort bar --}}
            <div class="flex items-center justify-between mb-6">
                <p class="text-sm text-gray-500">
                    Showing <strong>{{ $tools->firstItem() }}–{{ $tools->lastItem() }}</strong> of <strong>{{ $tools->total() }}</strong> tools
                </p>
                <form method="GET" action="{{ route('tools.index') }}" class="flex items-center gap-2">
                    @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                    @if(request('type'))<input type="hidden" name="type" value="{{ request('type') }}">@endif
                    <label for="tools-sort" class="text-sm text-gray-500">Sort:</label>
                    <select id="tools-sort" name="sort" onchange="this.form.submit()" class="form-input py-1.5 text-sm w-auto">
                        <option value="default" {{ request('sort', 'default') === 'default' ? 'selected' : '' }}>Default</option>
                        <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>A–Z</option>
                    </select>
                    <noscript><button type="submit" class="btn btn-secondary btn-sm">Apply</button></noscript>
                </form>
            </div>

            {{-- Tools Grid --}}
            @if($tools->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($tools as $tool)
                @include('components.tool-card', ['tool' => $tool])
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $tools->links() }}
            </div>
            @else
            <div class="card p-16 text-center">
                <div class="text-5xl mb-4">🔍</div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No tools found</h3>
                <p class="text-gray-500">Try adjusting your filters or browse all categories.</p>
                <a href="{{ route('tools.index') }}" class="btn btn-primary mt-4">Clear Filters</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
