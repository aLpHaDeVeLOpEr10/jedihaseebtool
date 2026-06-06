@extends('layouts.public')
@section('title', '500 - Server Error')
@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4">
    <div class="text-center max-w-lg">
        <div class="text-8xl font-black text-gray-100 leading-none mb-4">500</div>
        <h1 class="text-3xl font-bold text-gray-900 mb-3">Server error</h1>
        <p class="text-gray-500 mb-8">Something went wrong on our end. We've been notified and are working on a fix.</p>
        <a href="{{ route('home') }}" class="btn btn-primary btn-lg">Go Home</a>
    </div>
</div>
@endsection
