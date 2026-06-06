@extends('layouts.public')

@section('title', 'All Tools - ' . \App\Models\Setting::get('site_name', 'JEDISEBITOOL'))
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
                {{-- Category Filter --}}
                <div class="card p-5">
                    <h3 class="font-semibold text-gray-900 text-sm mb-3">Category</h3>
                    <div class="space-y-1.5">
                        <a href="{{ route('tools.index', request()->except('category')) }}"
                           class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors {{ !request('category') ? 'bg-brand-50 text-brand-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                            <span>All Categories</span>
                            <span class="text-xs text-gray-400">{{ $tools->total() }}</span>
                        </a>
                        @foreach($categories as $cat)
                        <a href="{{ route('tools.index', array_merge(request()->all(), ['category' => $cat->slug])) }}"
                           class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors {{ request('category') === $cat->slug ? 'bg-brand-50 text-brand-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                            <span class="flex items-center gap-2">
                                <span>{{ $cat->icon }}</span>
                                <span>{{ $cat->name }}</span>
                            </span>
                            <span class="text-xs text-gray-400">{{ $cat->active_tools_count }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Tool Type Filter --}}
                <div class="card p-5">
                    <h3 class="font-semibold text-gray-900 text-sm mb-3">Tool Type</h3>
                    <div class="space-y-1.5">
                        <a href="{{ route('tools.index', request()->except('type')) }}"
                           class="block px-3 py-2 rounded-lg text-sm transition-colors {{ !request('type') ? 'bg-brand-50 text-brand-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                            All Types
                        </a>
                        @foreach($toolTypes as $type)
                        <a href="{{ route('tools.index', array_merge(request()->all(), ['type' => $type])) }}"
                           class="block px-3 py-2 rounded-lg text-sm transition-colors capitalize {{ request('type') === $type ? 'bg-brand-50 text-brand-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                            {{ ucfirst($type) }}
                        </a>
                        @endforeach
                    </div>
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
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500">Sort:</span>
                    <select onchange="window.location = this.value" class="form-input py-1.5 text-sm w-auto">
                        <option value="{{ route('tools.index', array_merge(request()->all(), ['sort' => 'default'])) }}" {{ request('sort', 'default') === 'default' ? 'selected' : '' }}>Default</option>
                        <option value="{{ route('tools.index', array_merge(request()->all(), ['sort' => 'popular'])) }}" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
                        <option value="{{ route('tools.index', array_merge(request()->all(), ['sort' => 'newest'])) }}" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="{{ route('tools.index', array_merge(request()->all(), ['sort' => 'name'])) }}" {{ request('sort') === 'name' ? 'selected' : '' }}>A–Z</option>
                    </select>
                </div>
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
