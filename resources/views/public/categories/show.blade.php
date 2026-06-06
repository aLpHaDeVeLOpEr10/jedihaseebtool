@extends('layouts.public')

@section('title', $category->seo_title)
@section('description', $category->seo_description)

@section('content')
<div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('home') }}" class="hover:text-brand-600">Home</a>
            <span>›</span>
            <a href="{{ route('categories.index') }}" class="hover:text-brand-600">Categories</a>
            <span>›</span>
            <span class="text-gray-900">{{ $category->name }}</span>
        </nav>
        <div class="flex items-center gap-4">
            <span class="text-5xl">{{ $category->icon }}</span>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
                @if($category->description)
                <p class="text-gray-500 mt-2">{{ $category->description }}</p>
                @endif
                <p class="text-sm text-gray-400 mt-1">{{ $tools->total() }} tools available</p>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-gray-500">
            Showing <strong>{{ $tools->firstItem() }}–{{ $tools->lastItem() }}</strong> of <strong>{{ $tools->total() }}</strong>
        </p>
        <select onchange="window.location = this.value" class="form-input py-1.5 text-sm w-auto">
            <option value="{{ route('categories.show', [$category, 'sort' => 'default']) }}" {{ !request('sort') ? 'selected' : '' }}>Default</option>
            <option value="{{ route('categories.show', [$category, 'sort' => 'popular']) }}" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
            <option value="{{ route('categories.show', [$category, 'sort' => 'newest']) }}" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest</option>
            <option value="{{ route('categories.show', [$category, 'sort' => 'name']) }}" {{ request('sort') === 'name' ? 'selected' : '' }}>A–Z</option>
        </select>
    </div>

    @if($tools->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($tools as $tool)
        @include('components.tool-card', ['tool' => $tool])
        @endforeach
    </div>
    <div class="mt-8">{{ $tools->links() }}</div>
    @else
    <div class="card p-16 text-center">
        <div class="text-5xl mb-4">🔍</div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">No tools in this category yet</h3>
        <p class="text-gray-500">Check back soon — we're adding new tools regularly.</p>
        <a href="{{ route('tools.index') }}" class="btn btn-primary mt-4">Browse All Tools</a>
    </div>
    @endif

    @if($relatedCategories->count() > 0)
    <div class="mt-12">
        <h2 class="text-xl font-bold text-gray-900 mb-5">Other Categories</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3">
            @foreach($relatedCategories as $cat)
            <a href="{{ route('categories.show', $cat) }}"
               class="card-hover p-4 text-center flex flex-col items-center gap-2">
                <span class="text-2xl">{{ $cat->icon }}</span>
                <span class="text-xs font-medium text-gray-700">{{ $cat->name }}</span>
                <span class="text-xs text-gray-400">{{ $cat->tools_count }}</span>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
