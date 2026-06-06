@extends('layouts.public')
@section('title', '404 - Page Not Found')
@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4">
    <div class="text-center max-w-lg">
        <div class="text-8xl font-black text-gray-100 leading-none mb-4">404</div>
        <h1 class="text-3xl font-bold text-gray-900 mb-3">Page not found</h1>
        <p class="text-gray-500 mb-8">Sorry, we couldn't find the page you're looking for. It may have been moved or deleted.</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home') }}" class="btn btn-primary btn-lg">Go Home</a>
            <a href="{{ route('tools.index') }}" class="btn btn-outline btn-lg">Browse Tools</a>
        </div>
    </div>
</div>
@endsection
