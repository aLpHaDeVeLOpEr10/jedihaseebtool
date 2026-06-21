@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Hero Section --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
            <div class="flex items-center gap-4 mb-4">
                <div class="tool-icon bg-brand-100 text-brand-600 text-3xl w-14 h-14">
                    {{ $tool->icon }}
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $tool->name }}</h1>
                    <p class="text-gray-500 mt-1">{{ $tool->short_description }}</p>
                </div>
            </div>
            <x-breadcrumb :items="[
                ['label' => 'Home', 'url' => url('/')],
                ['label' => $tool->category->name, 'url' => route('categories.show', $tool->category)],
                ['label' => $tool->name]
            ]"/>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
        <div class="grid gap-8 lg:grid-cols-3">
            {{-- Main Tool Area --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Tool Card --}}
                <div class="card p-6"
                     x-data="toolRunner('decision-maker-wheel')"
                     x-init="init()">

                    <h2 class="text-lg font-semibold text-gray-900 mb-5">Use the Tool</h2>

                    <form @submit.prevent="submit()" class="space-y-4">
                        @foreach($tool->inputs->where('is_visible', true) as $input)
                            <x-tool-input :input="$input" />
                        @endforeach

                        @if($tool->inputs->isEmpty())
                            <div class="alert alert-info">
                                This tool's input form is being configured. Check back soon.
                            </div>
                        @endif

                        <button type="submit" class="btn btn-primary w-full btn-lg"
                                :disabled="loading">
                            <span x-show="!loading">⚡ Run Tool</span>
                            <span x-show="loading" class="flex items-center gap-2">
                                <span class="spinner"></span> Processing...
                            </span>
                        </button>
                    </form>

                    {{-- Results --}}
                    <div x-show="result" x-cloak class="mt-6 result-animate">
                        <x-tool-result />
                    </div>

                    {{-- Error --}}
                    <div x-show="error" x-cloak class="mt-4">
                        <div class="alert alert-error" x-text="error"></div>
                    </div>
                </div>

                {{-- Long Description --}}
                @if($tool->long_description)
                <div class="card p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">About This Tool</h2>
                    <div class="tool-prose">
                        {!! nl2br(e($tool->long_description)) !!}
                    </div>
                </div>
                @endif

                {{-- FAQs --}}
                @if($tool->faqs->count() > 0)
                <div class="card p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Frequently Asked Questions</h2>
                    <x-faq-list :faqs="$tool->faqs" />
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Category --}}
                <div class="card p-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Category</h3>
                    <a href="{{ route('categories.show', $tool->category) }}"
                       class="flex items-center gap-3 p-3 rounded-xl bg-brand-50 hover:bg-brand-100 transition-colors">
                        <span class="text-xl">{{ $tool->category->icon }}</span>
                        <span class="font-medium text-brand-700">{{ $tool->category->name }}</span>
                    </a>
                </div>

                {{-- Related Tools --}}
                @if($relatedTools->count() > 0)
                <div class="card p-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Related Tools</h3>
                    <div class="space-y-2">
                        @foreach($relatedTools as $related)
                        <a href="{{ route('tools.show', $related) }}"
                           class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-gray-50 transition-colors group">
                            <span class="text-lg">{{ $related->icon }}</span>
                            <span class="text-sm text-gray-700 group-hover:text-brand-600 transition-colors">{{ $related->name }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection