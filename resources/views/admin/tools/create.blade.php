@extends('layouts.admin')
@section('title', 'Create New Tool')

@section('content')
<form action="{{ route('admin.tools.store') }}" method="POST" x-data="toolForm()" class="space-y-6">
    @csrf

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Main form --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Basic Info --}}
            <div class="card p-6">
                <h2 class="font-semibold text-gray-900 mb-5">Basic Information</h2>
                <div class="space-y-4">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Tool Name *</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   x-model="name" @input="updateSlug()"
                                   class="form-input" required placeholder="e.g. Percentage Calculator">
                            @error('name')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">Slug *</label>
                            <input type="text" name="slug" x-model="slug"
                                   class="form-input font-mono" placeholder="percentage-calculator">
                            <p class="form-help">URL: /tools/<span x-text="slug || 'your-slug'" class="font-medium text-brand-600"></span></p>
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Category *</label>
                            <select name="category_id" class="form-input" required>
                                <option value="">Select category...</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->icon }} {{ $cat->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Tool Type *</label>
                            <select name="tool_type" class="form-input" required>
                                @foreach($toolTypes as $type)
                                <option value="{{ $type }}" {{ old('tool_type') === $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Short Description</label>
                        <input type="text" name="short_description" value="{{ old('short_description') }}"
                               class="form-input" maxlength="500"
                               placeholder="Brief one-line description">
                    </div>

                    <div>
                        <label class="form-label">Long Description</label>
                        <textarea name="long_description" rows="5" class="form-input"
                                  placeholder="Detailed description shown on the tool page...">{{ old('long_description') }}</textarea>
                    </div>

                    <div class="grid sm:grid-cols-3 gap-4">
                        <div>
                            <label class="form-label">Icon (emoji)</label>
                            <input type="text" name="icon" value="{{ old('icon', '🔧') }}"
                                   class="form-input text-xl text-center" maxlength="5">
                        </div>
                        <div>
                            <label class="form-label">Brand Color</label>
                            <input type="color" name="color" value="{{ old('color', '#6366f1') }}"
                                   class="form-input h-10">
                        </div>
                        <div>
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                                   class="form-input">
                        </div>
                    </div>
                </div>
            </div>

            {{-- SEO --}}
            <div class="card p-6">
                <h2 class="font-semibold text-gray-900 mb-5">SEO Settings</h2>
                <div class="space-y-4">
                    <div>
                        <label class="form-label">SEO Title</label>
                        <input type="text" name="seo_title" value="{{ old('seo_title') }}"
                               class="form-input" placeholder="Leave blank to auto-generate">
                    </div>
                    <div>
                        <label class="form-label">Meta Description</label>
                        <textarea name="seo_description" rows="3" class="form-input"
                                  placeholder="Leave blank to use short description">{{ old('seo_description') }}</textarea>
                    </div>
                    <div>
                        <label class="form-label">SEO Keywords</label>
                        <input type="text" name="seo_keywords" value="{{ old('seo_keywords') }}"
                               class="form-input" placeholder="comma, separated, keywords">
                    </div>
                </div>
            </div>

            {{-- Input Fields Builder --}}
            <div class="card p-6" x-data="inputBuilder()">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="font-semibold text-gray-900">Input Fields</h2>
                    <button type="button" @click="addField()" class="btn btn-secondary btn-sm">+ Add Field</button>
                </div>
                <input type="hidden" name="inputs" :value="JSON.stringify(fields)">

                <div class="space-y-4">
                    <template x-for="(field, index) in fields" :key="index">
                        <div class="border border-gray-200 rounded-xl p-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700" x-text="`Field ${index + 1}`"></span>
                                <button type="button" @click="removeField(index)"
                                        class="text-red-400 hover:text-red-600 text-sm">Remove</button>
                            </div>
                            <div class="grid sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="text-xs text-gray-500 mb-1 block">Field Name</label>
                                    <input type="text" x-model="field.field_name"
                                           class="form-input text-sm" placeholder="my_field">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 mb-1 block">Label</label>
                                    <input type="text" x-model="field.field_label"
                                           class="form-input text-sm" placeholder="My Field">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 mb-1 block">Type</label>
                                    <select x-model="field.field_type" class="form-input text-sm">
                                        <option value="text">Text</option>
                                        <option value="number">Number</option>
                                        <option value="textarea">Textarea</option>
                                        <option value="select">Select</option>
                                        <option value="checkbox">Checkbox</option>
                                        <option value="radio">Radio</option>
                                        <option value="date">Date</option>
                                        <option value="time">Time</option>
                                        <option value="color">Color</option>
                                        <option value="range">Range</option>
                                        <option value="file">File</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="text-xs text-gray-500 mb-1 block">Placeholder</label>
                                    <input type="text" x-model="field.placeholder"
                                           class="form-input text-sm">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 mb-1 block">Default Value</label>
                                    <input type="text" x-model="field.default_value"
                                           class="form-input text-sm">
                                </div>
                                <div class="flex items-end">
                                    <label class="flex items-center gap-2 text-sm text-gray-600">
                                        <input type="checkbox" x-model="field.required" class="rounded text-brand-600">
                                        Required
                                    </label>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div x-show="fields.length === 0" class="alert alert-info text-sm">
                        No input fields yet. Add fields to create the tool form.
                    </div>
                </div>
            </div>

            {{-- FAQs Builder --}}
            <div class="card p-6" x-data="faqBuilder()">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="font-semibold text-gray-900">FAQs</h2>
                    <button type="button" @click="addFaq()" class="btn btn-secondary btn-sm">+ Add FAQ</button>
                </div>
                <input type="hidden" name="faqs" :value="JSON.stringify(faqs)">

                <div class="space-y-4">
                    <template x-for="(faq, index) in faqs" :key="index">
                        <div class="border border-gray-200 rounded-xl p-4 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-700">FAQ {{ index + 1 }}</span>
                                <button type="button" @click="removeFaq(index)"
                                        class="text-red-400 hover:text-red-600 text-sm">Remove</button>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Question</label>
                                <input type="text" x-model="faq.question" class="form-input text-sm">
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Answer</label>
                                <textarea x-model="faq.answer" rows="2" class="form-input text-sm"></textarea>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            <div class="card p-5 sticky top-20">
                <h2 class="font-semibold text-gray-900 mb-4">Publish Settings</h2>
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Status</label>
                        <select name="status" class="form-input">
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_featured" id="is_featured"
                               class="rounded text-brand-600" {{ old('is_featured') ? 'checked' : '' }}>
                        <label for="is_featured" class="text-sm text-gray-700">Featured Tool</label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="generate_blade" id="generate_blade"
                               class="rounded text-brand-600" checked>
                        <label for="generate_blade" class="text-sm text-gray-700">Auto-generate Blade file</label>
                    </div>
                </div>

                <div class="mt-5 space-y-2">
                    <button type="submit" class="btn btn-primary w-full">Create Tool</button>
                    <a href="{{ route('admin.tools.index') }}" class="btn btn-secondary w-full">Cancel</a>
                </div>
            </div>

            {{-- Engine Config --}}
            <div class="card p-5">
                <h2 class="font-semibold text-gray-900 mb-4">Engine Config</h2>
                <div class="space-y-3">
                    <div>
                        <label class="form-label">Engine Class</label>
                        <input type="text" name="engine_class" value="{{ old('engine_class') }}"
                               class="form-input font-mono text-sm"
                               placeholder="App\Services\CalculatorEngine">
                    </div>
                    <div>
                        <label class="form-label">Engine Method</label>
                        <input type="text" name="engine_method" value="{{ old('engine_method') }}"
                               class="form-input font-mono text-sm"
                               placeholder="percentage">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
function toolForm() {
    return {
        name: '{{ old('name') }}',
        slug: '{{ old('slug') }}',
        updateSlug() {
            if (!this.slug || this.slug === this.slugify(this.prevName || '')) {
                this.slug = this.slugify(this.name);
            }
            this.prevName = this.name;
        },
        slugify(str) {
            return str.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
        }
    };
}
function inputBuilder() {
    return {
        fields: [],
        addField() {
            this.fields.push({
                field_name: '', field_label: '', field_type: 'text',
                placeholder: '', default_value: '', required: false, help_text: ''
            });
        },
        removeField(i) { this.fields.splice(i, 1); }
    };
}
function faqBuilder() {
    return {
        faqs: [],
        addFaq() { this.faqs.push({ question: '', answer: '' }); },
        removeFaq(i) { this.faqs.splice(i, 1); }
    };
}
</script>
@endpush
@endsection
