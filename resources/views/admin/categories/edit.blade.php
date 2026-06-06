@extends('layouts.admin')
@section('title', 'Edit: ' . $category->name)
@section('content')
<div class="max-w-2xl">
<form action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-6">
    @csrf @method('PUT')
    <div class="card p-6 space-y-4">
        <h2 class="font-semibold text-gray-900">Category Details</h2>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="form-label">Name *</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Slug *</label>
                <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" class="form-input font-mono" required>
            </div>
        </div>
        <div>
            <label class="form-label">Description</label>
            <textarea name="description" rows="3" class="form-input">{{ old('description', $category->description) }}</textarea>
        </div>
        <div class="grid sm:grid-cols-3 gap-4">
            <div>
                <label class="form-label">Icon</label>
                <input type="text" name="icon" value="{{ old('icon', $category->icon) }}" class="form-input text-2xl text-center" maxlength="5">
            </div>
            <div>
                <label class="form-label">Color</label>
                <input type="color" name="color" value="{{ old('color', $category->color) }}" class="form-input h-10">
            </div>
            <div>
                <label class="form-label">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" class="form-input">
            </div>
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded text-brand-600" {{ $category->is_active ? 'checked' : '' }}>
            <label for="is_active" class="text-sm">Active</label>
        </div>
    </div>
    <div class="card p-6 space-y-4">
        <h2 class="font-semibold text-gray-900">SEO</h2>
        <div>
            <label class="form-label">SEO Title</label>
            <input type="text" name="seo_title" value="{{ old('seo_title', $category->getOriginal('seo_title')) }}" class="form-input">
        </div>
        <div>
            <label class="form-label">Meta Description</label>
            <textarea name="seo_description" rows="2" class="form-input">{{ old('seo_description', $category->getOriginal('seo_description')) }}</textarea>
        </div>
        <div>
            <label class="form-label">Keywords</label>
            <input type="text" name="seo_keywords" value="{{ old('seo_keywords', $category->seo_keywords) }}" class="form-input">
        </div>
    </div>
    <div class="flex gap-3">
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="ml-auto"
              onsubmit="return confirm('Delete this category?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
    </div>
</form>
</div>
@endsection
