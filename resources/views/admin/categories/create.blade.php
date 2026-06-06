@extends('layouts.admin')
@section('title', 'Create Category')
@section('content')
<div class="max-w-2xl">
<form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
    @csrf
    <div class="card p-6 space-y-4">
        <h2 class="font-semibold text-gray-900">Category Details</h2>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="form-label">Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-input" required>
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Slug</label>
                <input type="text" name="slug" value="{{ old('slug') }}" class="form-input font-mono" placeholder="auto-generated">
            </div>
        </div>
        <div>
            <label class="form-label">Description</label>
            <textarea name="description" rows="3" class="form-input">{{ old('description') }}</textarea>
        </div>
        <div class="grid sm:grid-cols-3 gap-4">
            <div>
                <label class="form-label">Icon (emoji)</label>
                <input type="text" name="icon" value="{{ old('icon', '🔧') }}" class="form-input text-2xl text-center" maxlength="5">
            </div>
            <div>
                <label class="form-label">Color</label>
                <input type="color" name="color" value="{{ old('color', '#6366f1') }}" class="form-input h-10">
            </div>
            <div>
                <label class="form-label">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="form-input">
            </div>
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded text-brand-600" checked {{ old('is_active') === '0' ? '' : 'checked' }}>
            <label for="is_active" class="text-sm text-gray-700">Active (visible on website)</label>
        </div>
    </div>
    <div class="card p-6 space-y-4">
        <h2 class="font-semibold text-gray-900">SEO</h2>
        <div>
            <label class="form-label">SEO Title</label>
            <input type="text" name="seo_title" value="{{ old('seo_title') }}" class="form-input" placeholder="Auto-generated if blank">
        </div>
        <div>
            <label class="form-label">Meta Description</label>
            <textarea name="seo_description" rows="2" class="form-input">{{ old('seo_description') }}</textarea>
        </div>
        <div>
            <label class="form-label">Keywords</label>
            <input type="text" name="seo_keywords" value="{{ old('seo_keywords') }}" class="form-input" placeholder="keyword1, keyword2">
        </div>
    </div>
    <div class="flex gap-3">
        <button type="submit" class="btn btn-primary">Create Category</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
</div>
@endsection
