@extends('layouts.public')
@section('title', $page->seo_title ?: $page->title)
@section('description', $page->seo_description)
@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-16">
    <h1 class="text-4xl font-bold text-gray-900 mb-8">{{ $page->title }}</h1>
    <div class="card p-8 tool-prose">
        {!! $page->content !!}
    </div>
</div>
@endsection
