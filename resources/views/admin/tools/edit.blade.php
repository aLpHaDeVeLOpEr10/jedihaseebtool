@extends('layouts.admin')
@section('title', 'Edit: ' . $tool->name)

@section('header_actions')
<div class="flex gap-2">
    <a href="{{ route('tools.show', $tool->slug) }}" target="_blank" class="btn btn-secondary btn-sm">👁 View</a>
    <form action="{{ route('admin.tools.toggle-featured', $tool) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-secondary btn-sm">
            {{ $tool->is_featured ? '★ Unfeature' : '☆ Feature' }}
        </button>
    </form>
    <form action="{{ route('admin.tools.toggle-status', $tool) }}" method="POST">
        @csrf
        <button type="submit" class="btn {{ $tool->status === 'active' ? 'btn-secondary' : 'btn-success' }} btn-sm">
            {{ $tool->status === 'active' ? 'Deactivate' : 'Activate' }}
        </button>
    </form>
</div>
@endsection

@section('content')

@if(session('success'))
<div class="alert alert-success mb-4 flex items-center gap-2">
    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="alert alert-error mb-4">
    <p class="font-medium mb-1">Please fix the following errors:</p>
    <ul class="list-disc list-inside text-sm space-y-1">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div x-data="{ activeTab: 'basic' }">
    {{-- Tabs --}}
    <div class="flex gap-1 mb-6 border-b border-gray-200">
        @foreach([['basic', 'Basic Info'], ['seo', 'SEO'], ['content', 'Content Sections'], ['inputs', 'Inputs & FAQs'], ['blade', 'Blade Template']] as [$tab, $label])
        <button type="button" @click="activeTab = '{{ $tab }}'"
                :class="activeTab === '{{ $tab }}' ? 'border-brand-500 text-brand-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors">
            {{ $label }}
        </button>
        @endforeach
    </div>

    <form id="update-form" action="{{ route('admin.tools.update', $tool) }}" method="POST" class="space-y-6">
        @csrf @method('PUT')

        {{-- Basic Info Tab --}}
        <div x-show="activeTab === 'basic'" class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="card p-6">
                    <h2 class="font-semibold text-gray-900 mb-5">Basic Information</h2>
                    <div class="space-y-4">
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Tool Name *</label>
                                <input type="text" name="name" value="{{ old('name', $tool->name) }}"
                                       class="form-input" required>
                            </div>
                            <div>
                                <label class="form-label">Slug *</label>
                                <input type="text" name="slug" value="{{ old('slug', $tool->slug) }}"
                                       class="form-input font-mono">
                            </div>
                        </div>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Category *</label>
                                <select name="category_id" class="form-input" required>
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $tool->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->icon }} {{ $cat->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Tool Type *</label>
                                <select name="tool_type" class="form-input" required>
                                    @foreach($toolTypes as $type)
                                    <option value="{{ $type }}" {{ old('tool_type', $tool->tool_type) === $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Short Description</label>
                            <input type="text" name="short_description"
                                   value="{{ old('short_description', $tool->short_description) }}"
                                   class="form-input" maxlength="500">
                        </div>
                        <div>
                            <label class="form-label">Long Description</label>
                            <textarea name="long_description" rows="6" class="form-input">{{ old('long_description', $tool->long_description) }}</textarea>
                        </div>
                        <div class="grid sm:grid-cols-3 gap-4">
                            <div>
                                <label class="form-label">Icon (emoji)</label>
                                <input type="text" name="icon" value="{{ old('icon', $tool->icon) }}"
                                       class="form-input text-xl text-center" maxlength="5">
                            </div>
                            <div>
                                <label class="form-label">Brand Color</label>
                                <input type="color" name="color" value="{{ old('color', $tool->color) }}"
                                       class="form-input h-10">
                            </div>
                            <div>
                                <label class="form-label">Sort Order</label>
                                <input type="number" name="sort_order"
                                       value="{{ old('sort_order', $tool->sort_order) }}"
                                       class="form-input">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Engine Config --}}
                <div class="card p-6">
                    <h2 class="font-semibold text-gray-900 mb-5">Engine Configuration</h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Engine Class</label>
                            <input type="text" name="engine_class"
                                   value="{{ old('engine_class', $tool->engine_class) }}"
                                   class="form-input font-mono text-sm"
                                   placeholder="App\Services\CalculatorEngine">
                        </div>
                        <div>
                            <label class="form-label">Engine Method</label>
                            <input type="text" name="engine_method"
                                   value="{{ old('engine_method', $tool->engine_method) }}"
                                   class="form-input font-mono text-sm"
                                   placeholder="percentage">
                        </div>
                    </div>
                    <p class="form-help mt-2">Leave blank to use automatic dispatch based on tool type and slug.</p>
                </div>
            </div>

            <div class="space-y-6">
                <div class="card p-5 sticky top-20">
                    <h2 class="font-semibold text-gray-900 mb-4">Publish</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">Status</label>
                            <select name="status" class="form-input">
                                <option value="active" {{ old('status', $tool->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="draft" {{ old('status', $tool->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="inactive" {{ old('status', $tool->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_featured" id="is_featured_edit"
                                   class="rounded text-brand-600" {{ $tool->is_featured ? 'checked' : '' }}>
                            <label for="is_featured_edit" class="text-sm text-gray-700">Featured Tool</label>
                        </div>
                    </div>
                    <div class="mt-5 space-y-2">
                        <button type="submit" form="update-form" class="btn btn-primary w-full">Save Changes</button>
                        <a href="{{ route('admin.tools.index') }}" class="btn btn-secondary w-full">Back to List</a>
                        {{-- Button references the standalone delete form placed AFTER the main form --}}
                        <button type="submit" form="tool-delete-form"
                                onclick="return confirm('Permanently delete this tool?')"
                                class="btn btn-danger w-full btn-sm">Delete Tool</button>
                    </div>
                </div>

                <div class="card p-5">
                    <h2 class="font-semibold text-gray-900 mb-3 text-sm">Tool Stats</h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Views</span>
                            <span class="font-medium">{{ number_format($tool->view_count) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Uses</span>
                            <span class="font-medium">{{ number_format($tool->use_count) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Created</span>
                            <span class="font-medium">{{ $tool->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Has Blade</span>
                            <span class="font-medium">{{ $bladeExists ? '✅ Yes' : '❌ No' }}</span>
                        </div>
                    </div>
                    @if(!$bladeExists)
                    {{-- Button references the standalone generate-blade form placed AFTER the main form --}}
                    <button type="submit" form="tool-generate-blade-form"
                            class="btn btn-secondary btn-sm w-full mt-3">Generate Blade File</button>
                    @endif
                </div>
            </div>
        </div>

        {{-- SEO Tab --}}
        <div x-show="activeTab === 'seo'" x-cloak class="space-y-6 max-w-3xl">

            {{-- Primary SEO --}}
            <div class="card p-6">
                <h2 class="font-semibold text-gray-900 mb-1">Primary SEO</h2>
                <p class="text-xs text-gray-400 mb-5">Leave any field blank to use the auto-generated fallback. Values are saved as-is — no suffix is added automatically.</p>
                <div class="space-y-4">
                    <div>
                        <label class="form-label">SEO Title <span class="text-gray-400 font-normal">(recommended ≤ 60 chars)</span></label>
                        <input type="text" name="seo_title"
                               value="{{ old('seo_title', $tool->getRawOriginal('seo_title')) }}"
                               class="form-input" maxlength="200"
                               placeholder="Leave blank to auto-generate from tool name + suffix">
                        <p class="form-help">Shown in browser tab and Google results. Blank = "{{ $tool->name }} {{ \App\Models\Setting::get('seo_title_suffix','') }}"</p>
                    </div>
                    <div>
                        <label class="form-label">Meta Description <span class="text-gray-400 font-normal">(recommended ≤ 160 chars)</span></label>
                        <textarea name="seo_description" rows="3" class="form-input" maxlength="500"
                                  placeholder="Leave blank to use short description or global default">{{ old('seo_description', $tool->getRawOriginal('seo_description')) }}</textarea>
                        <p class="form-help">Shown as the snippet in Google search results.</p>
                    </div>
                    <div>
                        <label class="form-label">Keywords</label>
                        <input type="text" name="seo_keywords"
                               value="{{ old('seo_keywords', $tool->getRawOriginal('seo_keywords')) }}"
                               class="form-input" maxlength="500"
                               placeholder="tiktok downloader, free, no watermark">
                        <p class="form-help">Comma-separated. Not a major ranking signal but used by some search engines.</p>
                    </div>
                    <div>
                        <label class="form-label">Canonical URL</label>
                        <input type="url" name="canonical_url"
                               value="{{ old('canonical_url', $tool->getRawOriginal('canonical_url')) }}"
                               class="form-input" maxlength="500"
                               placeholder="Leave blank to use the current page URL">
                        <p class="form-help">Override only if this page is a duplicate of another URL.</p>
                    </div>
                    <div>
                        <label class="form-label">Robots</label>
                        <select name="robots" class="form-input">
                            @php $currentRobots = old('robots', $tool->getRawOriginal('robots') ?? '') @endphp
                            <option value="" {{ $currentRobots === '' ? 'selected' : '' }}>Default (index, follow)</option>
                            <option value="index, follow"   {{ $currentRobots === 'index, follow'   ? 'selected' : '' }}>index, follow</option>
                            <option value="noindex, follow" {{ $currentRobots === 'noindex, follow' ? 'selected' : '' }}>noindex, follow</option>
                            <option value="index, nofollow" {{ $currentRobots === 'index, nofollow' ? 'selected' : '' }}>index, nofollow</option>
                            <option value="noindex, nofollow" {{ $currentRobots === 'noindex, nofollow' ? 'selected' : '' }}>noindex, nofollow</option>
                        </select>
                        <p class="form-help">Controls whether search engines index this page and follow its links.</p>
                    </div>
                </div>
            </div>

            {{-- Open Graph --}}
            <div class="card p-6">
                <h2 class="font-semibold text-gray-900 mb-1">Open Graph (Facebook / LinkedIn)</h2>
                <p class="text-xs text-gray-400 mb-5">Leave blank to fall back to primary SEO title / description above.</p>
                <div class="space-y-4">
                    <div>
                        <label class="form-label">OG Title</label>
                        <input type="text" name="og_title"
                               value="{{ old('og_title', $tool->getRawOriginal('og_title')) }}"
                               class="form-input" maxlength="200"
                               placeholder="Defaults to SEO title">
                    </div>
                    <div>
                        <label class="form-label">OG Description</label>
                        <textarea name="og_description" rows="2" class="form-input" maxlength="500"
                                  placeholder="Defaults to meta description">{{ old('og_description', $tool->getRawOriginal('og_description')) }}</textarea>
                    </div>
                    <div>
                        <label class="form-label">OG Image URL</label>
                        <input type="url" name="og_image"
                               value="{{ old('og_image', $tool->getRawOriginal('og_image')) }}"
                               class="form-input" maxlength="500"
                               placeholder="https://yoursite.com/images/og/tool-name.jpg">
                        <p class="form-help">Recommended size: 1200 × 630 px. Must be a full URL.</p>
                    </div>
                </div>
            </div>

            {{-- Twitter Card --}}
            <div class="card p-6">
                <h2 class="font-semibold text-gray-900 mb-1">Twitter Card</h2>
                <p class="text-xs text-gray-400 mb-5">Leave blank to fall back to primary SEO title / description above.</p>
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Twitter Title</label>
                        <input type="text" name="twitter_title"
                               value="{{ old('twitter_title', $tool->getRawOriginal('twitter_title')) }}"
                               class="form-input" maxlength="200"
                               placeholder="Defaults to SEO title">
                    </div>
                    <div>
                        <label class="form-label">Twitter Description</label>
                        <textarea name="twitter_description" rows="2" class="form-input" maxlength="500"
                                  placeholder="Defaults to meta description">{{ old('twitter_description', $tool->getRawOriginal('twitter_description')) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Schema Markup --}}
            <div class="card p-6">
                <h2 class="font-semibold text-gray-900 mb-1">Schema Markup (JSON-LD)</h2>
                <p class="text-xs text-gray-400 mb-5">Paste a valid JSON-LD block. It will be output inside a &lt;script type="application/ld+json"&gt; tag on the tool page.</p>
                <div>
                    <textarea name="schema_markup" rows="8" class="form-input font-mono text-xs leading-relaxed"
                              placeholder='{
  "@context": "https://schema.org",
  "@type": "WebApplication",
  "name": "{{ $tool->name }}",
  "url": "{{ $tool->url }}"
}'>{{ old('schema_markup', $tool->getRawOriginal('schema_markup')) }}</textarea>
                    <p class="form-help">Validate at <a href="https://validator.schema.org/" target="_blank" class="underline text-brand-600">validator.schema.org</a> before publishing.</p>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save SEO Settings</button>
        </div>

        {{-- Content Sections Tab --}}
        <div x-show="activeTab === 'content'" x-cloak class="space-y-6"
             x-data="contentSectionsEditor({{ $tool->contents->toJson() }})">

            <input type="hidden" name="contents" :value="JSON.stringify(sections)">

            <div class="card p-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="font-semibold text-gray-900">Content Sections</h2>
                        <p class="text-sm text-gray-500 mt-1">Add rich content blocks that appear below the tool on the public page.</p>
                    </div>
                    <button type="button" @click="addSection()" class="btn btn-secondary btn-sm">+ Add Section</button>
                </div>

                <div class="space-y-5">
                    <template x-for="(section, index) in sections" :key="index">
                        <div class="border border-gray-200 rounded-xl p-5 space-y-4 bg-gray-50">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700"
                                      x-text="'Section ' + (index + 1) + (section.title ? ': ' + section.title : '')"></span>
                                <div class="flex items-center gap-3">
                                    <label class="flex items-center gap-1.5 text-sm text-gray-600 cursor-pointer">
                                        <input type="checkbox" x-model="section.is_visible" class="rounded text-brand-600">
                                        Visible
                                    </label>
                                    <button type="button" @click="removeSection(index)"
                                            class="text-red-400 hover:text-red-600 text-sm transition-colors">Remove</button>
                                </div>
                            </div>

                            <div class="grid sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Section Key</label>
                                    <input type="text" x-model="section.section_key"
                                           placeholder="e.g. about, how_to_use, tips"
                                           class="form-input text-sm mt-1 font-mono">
                                    <p class="text-xs text-gray-400 mt-1">Internal identifier (no spaces).</p>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Section Title</label>
                                    <input type="text" x-model="section.title"
                                           placeholder="e.g. How to Use This Tool"
                                           class="form-input text-sm mt-1">
                                    <p class="text-xs text-gray-400 mt-1">Displayed as a heading. Leave blank to hide heading.</p>
                                </div>
                            </div>

                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Content</label>
                                <textarea x-model="section.content" rows="6"
                                          placeholder="Write the section content here..."
                                          class="form-input text-sm mt-1 leading-relaxed"></textarea>
                            </div>

                            <div class="flex items-center gap-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Sort Order</label>
                                <input type="number" x-model.number="section.sort_order"
                                       class="form-input text-sm w-20">
                            </div>
                        </div>
                    </template>

                    <button type="button" @click="addSection()"
                            class="w-full border-2 border-dashed border-gray-200 rounded-xl p-4 text-sm text-gray-400 hover:border-brand-300 hover:text-brand-500 transition-colors">
                        + Add Content Section
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save Content Sections</button>
        </div>

        {{-- Inputs & FAQs Tab --}}
        <div x-show="activeTab === 'inputs'" x-cloak class="space-y-6"
             x-data="editInputBuilder({{ $tool->inputs->toJson() }}, {{ $tool->faqs->toJson() }})">

            <input type="hidden" name="inputs" :value="JSON.stringify(fields)">
            <input type="hidden" name="faqs" :value="JSON.stringify(faqs)">

            <div class="card p-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="font-semibold text-gray-900">Input Fields</h2>
                    <button type="button" @click="addField()" class="btn btn-secondary btn-sm">+ Add Field</button>
                </div>
                <div class="space-y-4">
                    <template x-for="(field, index) in fields" :key="index">
                        <div class="border border-gray-200 rounded-xl p-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700" x-text="`Field ${index + 1}: ` + (field.field_label || 'Untitled')"></span>
                                <button type="button" @click="removeField(index)" class="text-red-400 hover:text-red-600 text-sm">Remove</button>
                            </div>
                            <div class="grid sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="text-xs text-gray-500">Field Name</label>
                                    <input type="text" x-model="field.field_name" class="form-input text-sm mt-1">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">Label</label>
                                    <input type="text" x-model="field.field_label" class="form-input text-sm mt-1">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">Type</label>
                                    <select x-model="field.field_type" class="form-input text-sm mt-1">
                                        <option value="text">Text</option>
                                        <option value="number">Number</option>
                                        <option value="textarea">Textarea</option>
                                        <option value="select">Select</option>
                                        <option value="checkbox">Checkbox</option>
                                        <option value="date">Date</option>
                                        <option value="color">Color</option>
                                        <option value="range">Range</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="text-xs text-gray-500">Placeholder</label>
                                    <input type="text" x-model="field.placeholder" class="form-input text-sm mt-1">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">Default</label>
                                    <input type="text" x-model="field.default_value" class="form-input text-sm mt-1">
                                </div>
                                <div class="flex items-end pb-1">
                                    <label class="flex items-center gap-2 text-sm text-gray-600">
                                        <input type="checkbox" x-model="field.required" class="rounded text-brand-600">
                                        Required
                                    </label>
                                </div>
                            </div>
                        </div>
                    </template>
                    <button type="button" @click="addField()" class="w-full border-2 border-dashed border-gray-200 rounded-xl p-3 text-sm text-gray-400 hover:border-brand-300 hover:text-brand-500 transition-colors">
                        + Add Input Field
                    </button>
                </div>
            </div>

            <div class="card p-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="font-semibold text-gray-900">FAQs</h2>
                    <button type="button" @click="addFaq()" class="btn btn-secondary btn-sm">+ Add FAQ</button>
                </div>
                <div class="space-y-4">
                    <template x-for="(faq, index) in faqs" :key="index">
                        <div class="border border-gray-200 rounded-xl p-4 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-700" x-text="`FAQ ${index + 1}`"></span>
                                <button type="button" @click="removeFaq(index)" class="text-red-400 text-sm">Remove</button>
                            </div>
                            <div>
                                <input type="text" x-model="faq.question" placeholder="Question..." class="form-input text-sm mb-2">
                                <textarea x-model="faq.answer" rows="2" placeholder="Answer..." class="form-input text-sm"></textarea>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save Inputs & FAQs</button>
        </div>

        {{-- Blade Tab --}}
        <div x-show="activeTab === 'blade'" x-cloak class="card p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="font-semibold text-gray-900">Blade Template</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Path: <code class="font-mono text-xs bg-gray-100 px-2 py-0.5 rounded">resources/views/tools/generated/{{ $tool->slug }}.blade.php</code>
                    </p>
                </div>
                @if(!$bladeExists)
                {{-- References the standalone generate-blade form placed AFTER the main form --}}
                <button type="submit" form="tool-generate-blade-form"
                        class="btn btn-secondary">Generate Template</button>
                @endif
            </div>

            @if($bladeExists)
            <div class="alert alert-success mb-4">
                ✅ Custom Blade file exists. You can edit it below.
            </div>
            <div>
                <label class="form-label">Blade Content</label>
                <textarea name="blade_content" rows="30"
                          class="form-input font-mono text-xs leading-relaxed"
                          placeholder="Blade template content...">{{ $bladeContent }}</textarea>
                <p class="form-help">This is the generated Blade template for this tool. Customize it as needed.</p>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Save Blade Template</button>
            </div>
            @else
            <div class="alert alert-info">
                No custom Blade file has been generated yet. Click "Generate Template" to create one.
                <br><br>
                The generic renderer will be used until a custom template is created.
            </div>
            @endif
        </div>
    </form>

    {{-- ─── Standalone action forms (never nested inside #update-form) ─────────── --}}

    {{-- Delete tool — one-field form, no inputs needed beyond CSRF + method spoof --}}
    <form id="tool-delete-form"
          action="{{ route('admin.tools.destroy', $tool) }}" method="POST">
        @csrf @method('DELETE')
    </form>

    {{-- Generate / regenerate the custom Blade file for this tool --}}
    @if(!$bladeExists)
    <form id="tool-generate-blade-form"
          action="{{ route('admin.tools.generate-blade', $tool) }}" method="POST">
        @csrf
    </form>
    @endif

</div>

@push('scripts')
<script>
function contentSectionsEditor(existingSections) {
    return {
        sections: (existingSections || []).map(function(s) {
            return {
                id:           s.id           || null,
                section_key:  s.section_key  || '',
                title:        s.title        || '',
                content:      s.content      || '',
                sort_order:   s.sort_order   || 0,
                is_visible:   s.is_visible !== undefined ? !!s.is_visible : true,
            };
        }),
        addSection() {
            this.sections.push({
                id: null,
                section_key: '',
                title: '',
                content: '',
                sort_order: this.sections.length,
                is_visible: true,
            });
        },
        removeSection(i) { this.sections.splice(i, 1); },
    };
}

function editInputBuilder(existingFields, existingFaqs) {
    return {
        fields: existingFields.map(f => ({
            field_name: f.field_name || '',
            field_label: f.field_label || '',
            field_type: f.field_type || 'text',
            placeholder: f.placeholder || '',
            default_value: f.default_value || '',
            required: !!f.required,
            help_text: f.help_text || ''
        })),
        faqs: existingFaqs.map(f => ({
            question: f.question || '',
            answer: f.answer || ''
        })),
        addField() {
            this.fields.push({ field_name: '', field_label: '', field_type: 'text', placeholder: '', default_value: '', required: false });
        },
        removeField(i) { this.fields.splice(i, 1); },
        addFaq() { this.faqs.push({ question: '', answer: '' }); },
        removeFaq(i) { this.faqs.splice(i, 1); }
    };
}
</script>
@endpush
@endsection
