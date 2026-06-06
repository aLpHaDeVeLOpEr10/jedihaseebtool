@extends('layouts.public')
@section('title', 'All Categories - ' . \App\Models\Setting::get('site_name', 'JEDISEBITOOL'))
@section('content')
<div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
        <h1 class="text-3xl font-bold text-gray-900">All Categories</h1>
        <p class="text-gray-500 mt-2">{{ $categories->count() }} categories of free online tools</p>
    </div>
</div>
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($categories as $category)
        <a href="{{ route('categories.show', $category) }}" class="card-hover p-6 flex items-start gap-5">
            <div class="text-4xl flex-shrink-0">{{ $category->icon }}</div>
            <div>
                <h2 class="font-bold text-gray-900 text-lg">{{ $category->name }}</h2>
                @if($category->description)
                <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $category->description }}</p>
                @endif
                <span class="badge badge-primary mt-2">{{ $category->active_tools_count }} tools</span>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endsection
