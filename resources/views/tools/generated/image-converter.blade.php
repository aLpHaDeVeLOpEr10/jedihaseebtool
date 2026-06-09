@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')
<div class="min-h-screen bg-gray-50">

    {{-- Page Header --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                {{ $tool->icon }} {{ $tool->name }}
            </h1>
            <p class="text-gray-500 mt-2">{{ $tool->short_description }}</p>
        </div>
    </div>

    {{-- Tool Body --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10" x-data="imageConverter()">

        {{-- Error --}}
        <div x-show="error" x-transition
             class="alert alert-error mb-5 flex items-start gap-2">
            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                      clip-rule="evenodd"/>
            </svg>
            <span x-text="error"></span>
        </div>

        {{-- Warning (e.g. GIF animation) --}}
        <div x-show="warning" x-transition
             class="alert alert-warning mb-5 flex items-start gap-2">
            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                      d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                      clip-rule="evenodd"/>
            </svg>
            <span x-text="warning"></span>
        </div>

        {{-- ── UPLOAD ZONE ── --}}
        <div x-show="!hasFile" x-transition>
            <div
                class="border-2 border-dashed rounded-2xl p-12 sm:p-16 text-center cursor-pointer transition-all duration-200 select-none"
                :class="dragging
                    ? 'border-brand-400 bg-brand-50'
                    : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50'"
                @dragover.prevent="dragging = true"
                @dragleave.prevent="dragging = false"
                @drop.prevent="onDrop($event)"
                @click="$refs.fileInput.click()"
            >
                <div class="flex flex-col items-center gap-4 pointer-events-none">
                    <div class="w-20 h-20 rounded-2xl bg-brand-50 flex items-center justify-center text-4xl shadow-sm">
                        🔄
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-700">
                            <span x-show="!dragging">Drop your image here</span>
                            <span x-show="dragging" class="text-brand-600">Release to upload</span>
                        </p>
                        <p class="text-sm text-gray-400 mt-1">
                            or <span class="text-brand-600 font-medium">click to browse</span> your files
                        </p>
                    </div>
                    <div class="flex gap-2 flex-wrap justify-center">
                        <span class="badge badge-gray">JPG</span>
                        <span class="badge badge-gray">PNG</span>
                        <span class="badge badge-gray">WebP</span>
                        <span class="badge badge-gray">GIF</span>
                        <span class="badge badge-gray">BMP</span>
                    </div>
                    <p class="text-xs text-gray-400">Max file size: 50 MB</p>
                </div>
            </div>

            <input
                type="file"
                x-ref="fileInput"
                @change="onFileInput($event)"
                accept="image/jpeg,image/jpg,image/png,image/webp,image/gif,image/bmp,image/x-bmp,image/x-ms-bmp"
                class="hidden"
            >
        </div>

        {{-- ── TOOL AREA ── --}}
        <div x-show="hasFile" x-transition>

            {{-- File info bar --}}
            <div class="card p-4 mb-5 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-xl bg-brand-50 flex items-center justify-center text-lg flex-shrink-0">🖼️</div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate" x-text="file ? file.name : ''"></p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            <span x-text="mimeLabel(originalMime)"></span>
                            <span x-show="originalWidth"> · <span x-text="originalWidth + ' × ' + originalHeight + ' px'"></span></span>
                            <span> · <span x-text="formatBytes(originalSize)"></span></span>
                        </p>
                    </div>
                </div>
                <button @click="reset()" class="btn btn-secondary btn-sm flex-shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Change Image
                </button>
            </div>

            {{-- Settings card --}}
            <div class="card p-6 mb-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-4">Choose Output Format</h2>

                {{-- Format tiles --}}
                <div class="grid grid-cols-3 gap-3 mb-6">

                    {{-- JPEG --}}
                    <button
                        type="button"
                        @click="outputFormat = 'jpeg'; convertedSrc = ''"
                        class="p-4 rounded-2xl border-2 text-left transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:ring-offset-1"
                        :class="outputFormat === 'jpeg'
                            ? 'border-brand-400 bg-brand-50 ring-2 ring-brand-200'
                            : 'border-gray-100 bg-white hover:border-gray-200 hover:bg-gray-50'"
                    >
                        <div class="text-2xl mb-2">📷</div>
                        <div class="font-semibold text-sm text-gray-800">.JPG</div>
                        <div class="text-xs text-gray-500 mt-1 hidden sm:block">Best for photos</div>
                        <div class="text-xs text-gray-400 mt-0.5 hidden sm:block">Lossy · quality control</div>
                        <div x-show="outputFormat === 'jpeg'"
                             class="mt-2 inline-flex items-center gap-1 text-xs font-medium text-brand-600">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Selected
                        </div>
                    </button>

                    {{-- PNG --}}
                    <button
                        type="button"
                        @click="outputFormat = 'png'; convertedSrc = ''"
                        class="p-4 rounded-2xl border-2 text-left transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:ring-offset-1"
                        :class="outputFormat === 'png'
                            ? 'border-brand-400 bg-brand-50 ring-2 ring-brand-200'
                            : 'border-gray-100 bg-white hover:border-gray-200 hover:bg-gray-50'"
                    >
                        <div class="text-2xl mb-2">🎨</div>
                        <div class="font-semibold text-sm text-gray-800">.PNG</div>
                        <div class="text-xs text-gray-500 mt-1 hidden sm:block">Lossless quality</div>
                        <div class="text-xs text-gray-400 mt-0.5 hidden sm:block">Transparency support</div>
                        <div x-show="outputFormat === 'png'"
                             class="mt-2 inline-flex items-center gap-1 text-xs font-medium text-brand-600">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Selected
                        </div>
                    </button>

                    {{-- WebP --}}
                    <button
                        type="button"
                        @click="outputFormat = 'webp'; convertedSrc = ''"
                        class="p-4 rounded-2xl border-2 text-left transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:ring-offset-1"
                        :class="outputFormat === 'webp'
                            ? 'border-brand-400 bg-brand-50 ring-2 ring-brand-200'
                            : 'border-gray-100 bg-white hover:border-gray-200 hover:bg-gray-50'"
                    >
                        <div class="text-2xl mb-2">⚡</div>
                        <div class="font-semibold text-sm text-gray-800">.WebP</div>
                        <div class="text-xs text-gray-500 mt-1 hidden sm:block">Best compression</div>
                        <div class="text-xs text-gray-400 mt-0.5 hidden sm:block">Modern · web-optimized</div>
                        <div x-show="outputFormat === 'webp'"
                             class="mt-2 inline-flex items-center gap-1 text-xs font-medium text-brand-600">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Selected
                        </div>
                    </button>
                </div>

                {{-- Quality slider (JPEG / WebP only) --}}
                <div x-show="outputFormat !== 'png'" x-transition class="mb-2">
                    <div class="flex items-center justify-between mb-2">
                        <label class="form-label mb-0">Quality</label>
                        <span class="text-sm font-semibold text-brand-600" x-text="quality + '%'"></span>
                    </div>
                    <input
                        type="range"
                        x-model.number="quality"
                        min="1" max="100"
                        class="w-full h-2 rounded-full appearance-none cursor-pointer accent-brand-600 bg-gray-200"
                    >
                    <div class="flex justify-between text-xs text-gray-400 mt-1">
                        <span>Smaller file</span>
                        <span>Better quality</span>
                    </div>
                </div>

                {{-- PNG lossless note --}}
                <div x-show="outputFormat === 'png'" x-transition>
                    <p class="text-sm text-gray-400 bg-gray-50 rounded-xl px-4 py-2.5 border border-gray-100">
                        PNG uses lossless compression — every pixel is preserved at full quality.
                    </p>
                </div>
            </div>

            {{-- Action buttons --}}
            <div class="flex flex-wrap gap-3 mb-5">
                <button @click="convert()" :disabled="loading" class="btn btn-primary">
                    <svg x-show="loading" class="spinner w-4 h-4" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span x-text="loading ? 'Converting…' : 'Convert Image'"></span>
                </button>

                <button
                    @click="download()"
                    :disabled="!hasResult || loading"
                    x-show="hasResult"
                    class="btn btn-success"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download <span x-text="'.' + outputExt" class="uppercase font-bold"></span>
                </button>

                <button @click="reset()" class="btn btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Clear
                </button>
            </div>

            {{-- Before / After preview --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">

                {{-- Original --}}
                <div class="card p-4">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-semibold text-gray-700">Original</span>
                        <div class="flex items-center gap-1.5">
                            <span class="badge badge-gray" x-text="mimeLabel(originalMime)"></span>
                            <span class="badge badge-gray" x-text="formatBytes(originalSize)"></span>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl flex items-center justify-center overflow-hidden"
                         style="height:220px;background-image:url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16'%3E%3Crect width='8' height='8' fill='%23f3f4f6'/%3E%3Crect x='8' y='8' width='8' height='8' fill='%23f3f4f6'/%3E%3Crect x='8' width='8' height='8' fill='%23e5e7eb'/%3E%3Crect y='8' width='8' height='8' fill='%23e5e7eb'/%3E%3C/svg%3E&quot;)">
                        <img
                            x-show="originalSrc"
                            :src="originalSrc"
                            class="max-w-full max-h-full object-contain"
                            style="max-height:220px"
                            alt="Original image"
                        >
                    </div>
                    <p class="text-xs text-gray-400 mt-2 text-center"
                       x-text="originalWidth ? originalWidth + ' × ' + originalHeight + ' px' : ''"></p>
                </div>

                {{-- Converted --}}
                <div class="card p-4">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-semibold text-gray-700">Converted</span>
                        <div class="flex items-center gap-1.5" x-show="hasResult">
                            <span class="badge badge-primary" x-text="outputLabel"></span>
                            <span
                                class="badge"
                                :class="convertedSize < originalSize ? 'badge-success' : 'badge-warning'"
                                x-text="formatBytes(convertedSize)"
                            ></span>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl flex items-center justify-center overflow-hidden"
                         style="height:220px;background-image:url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16'%3E%3Crect width='8' height='8' fill='%23f3f4f6'/%3E%3Crect x='8' y='8' width='8' height='8' fill='%23f3f4f6'/%3E%3Crect x='8' width='8' height='8' fill='%23e5e7eb'/%3E%3Crect y='8' width='8' height='8' fill='%23e5e7eb'/%3E%3C/svg%3E&quot;)">

                        {{-- Spinner --}}
                        <div x-show="loading" class="flex flex-col items-center gap-3 text-gray-400">
                            <div class="spinner w-8 h-8 border-brand-400 border-2"></div>
                            <span class="text-sm">Converting…</span>
                        </div>

                        {{-- Result --}}
                        <img
                            x-show="!loading && convertedSrc"
                            :src="convertedSrc"
                            x-transition
                            class="max-w-full max-h-full object-contain"
                            style="max-height:220px"
                            alt="Converted image"
                        >

                        {{-- Placeholder --}}
                        <div x-show="!loading && !convertedSrc"
                             class="text-gray-300 text-sm text-center px-4">
                            <svg class="w-12 h-12 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Choose a format and click Convert
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2 text-center"
                       x-text="hasResult ? originalWidth + ' × ' + originalHeight + ' px' : ''"></p>
                </div>
            </div>

            {{-- Stats row --}}
            <div x-show="hasResult" x-transition class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
                <div class="card p-4 text-center">
                    <div class="text-lg font-bold text-gray-900" x-text="mimeLabel(originalMime)"></div>
                    <div class="text-xs text-gray-500 mt-1">Input format</div>
                </div>
                <div class="card p-4 text-center">
                    <div class="text-lg font-bold text-brand-600" x-text="outputLabel"></div>
                    <div class="text-xs text-gray-500 mt-1">Output format</div>
                </div>
                <div class="card p-4 text-center">
                    <div class="text-lg font-bold text-gray-900" x-text="formatBytes(originalSize)"></div>
                    <div class="text-xs text-gray-500 mt-1">Original size</div>
                </div>
                <div class="card p-4 text-center">
                    <div class="text-lg font-bold"
                         :class="convertedSize <= originalSize ? 'text-emerald-600' : 'text-amber-600'"
                         x-text="formatBytes(convertedSize)"></div>
                    <div class="text-xs text-gray-500 mt-1">Output size</div>
                </div>
            </div>

            {{-- Size increased notice --}}
            <div
                x-show="hasResult && convertedSize > originalSize"
                x-transition
                class="alert alert-warning mb-4 text-sm flex items-start gap-2"
            >
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span>
                    The converted file is larger than the original — this is expected when converting to lossless formats like PNG.
                    <span x-show="outputFormat === 'jpeg' || outputFormat === 'webp'">Try lowering the quality slider.</span>
                </span>
            </div>

        </div>{{-- /tool area --}}

        {{-- Info card --}}
        <div class="card p-6 mt-8">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">About Image Converter</h3>
            <ul class="space-y-2 text-sm text-gray-500">
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    Converts images between JPG, PNG, WebP, GIF, and BMP using your browser's Canvas API.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    <strong class="text-gray-700 font-medium">JPEG</strong> — Ideal for photographs. Smaller files, some quality loss. Use the quality slider to balance size and clarity.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    <strong class="text-gray-700 font-medium">PNG</strong> — Lossless with transparency. Best for graphics, logos, and screenshots. Files may be larger.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    <strong class="text-gray-700 font-medium">WebP</strong> — Modern format with excellent compression. Supported in all current browsers.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-amber-500 mt-0.5">ℹ️</span>
                    Transparent pixels (PNG, WebP → JPEG) are filled with a white background since JPEG does not support transparency.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">🔒</span>
                    All conversion happens entirely in your browser — your images are never uploaded to any server.
                </li>
            </ul>
        </div>

        {{-- Related Tools --}}
        @if($relatedTools->count())
        <div class="mt-8">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Related Tools</h3>
            <div class="grid grid-cols-2 gap-3">
                @foreach($relatedTools as $related)
                <a href="{{ route('tools.show', $related->slug) }}"
                   class="card-hover p-4 flex items-center gap-3 no-underline">
                    <span class="text-2xl">{{ $related->icon }}</span>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $related->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $related->short_description }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
/*
 * Image Converter — pure browser-side via HTML5 Canvas API.
 * Supports input: JPG, PNG, WebP, GIF (first frame), BMP.
 * Supports output: JPG, PNG, WebP.
 * Nothing is uploaded; all processing happens locally.
 */
function imageConverter() {
    return {
        /* ── State ── */
        file:          null,
        originalSrc:   '',
        convertedSrc:  '',
        originalWidth: 0,
        originalHeight:0,
        originalSize:  0,
        convertedSize: 0,
        originalMime:  '',
        outputFormat:  'jpeg',  // 'jpeg' | 'png' | 'webp'
        quality:       85,
        loading:       false,
        dragging:      false,
        error:         '',
        warning:       '',
        _img:          null,    // cached Image element (non-reactive)

        /* ── Computed ── */
        get hasFile()   { return !!this.file; },
        get hasResult() { return !!this.convertedSrc && !this.loading; },
        get outputExt() {
            return this.outputFormat === 'jpeg' ? 'jpg' : this.outputFormat;
        },
        get outputLabel() {
            return { jpeg: 'JPEG', png: 'PNG', webp: 'WebP' }[this.outputFormat] || this.outputFormat.toUpperCase();
        },

        /* ── File input handlers ── */
        onFileInput(e) {
            const f = e.target.files[0];
            if (f) this.loadFile(f);
        },

        onDrop(e) {
            this.dragging = false;
            const f = e.dataTransfer.files[0];
            if (f) this.loadFile(f);
        },

        loadFile(file) {
            const ALLOWED = [
                'image/jpeg', 'image/jpg', 'image/png', 'image/webp',
                'image/gif',  'image/bmp', 'image/x-bmp', 'image/x-ms-bmp',
            ];
            if (!ALLOWED.includes(file.type)) {
                this.error   = `Unsupported file type "${file.type || 'unknown'}". Please upload a JPG, PNG, WebP, GIF, or BMP image.`;
                this.warning = '';
                return;
            }
            if (file.size > 50 * 1024 * 1024) {
                this.error   = 'File too large. Maximum supported size is 50 MB.';
                this.warning = '';
                return;
            }

            this.error         = '';
            this.warning       = '';
            this.file          = file;
            this.originalSize  = file.size;
            this.originalMime  = file.type;
            this.convertedSrc  = '';
            this.convertedSize = 0;
            this._img          = null;

            /* GIF → only first frame is captured by Canvas */
            if (file.type === 'image/gif') {
                this.warning = 'Animated GIFs are converted as a static image — only the first frame is preserved.';
            }

            /* Auto-suggest a useful output format (different from input) */
            if      (file.type === 'image/jpeg' || file.type === 'image/jpg') this.outputFormat = 'webp';
            else if (file.type === 'image/webp')                               this.outputFormat = 'jpeg';
            else if (file.type === 'image/png')                                this.outputFormat = 'webp';
            else                                                                this.outputFormat = 'jpeg';

            const reader = new FileReader();
            reader.onload = (ev) => {
                this.originalSrc = ev.target.result;
                const img = new Image();
                img.onload = () => {
                    this._img          = img;
                    this.originalWidth  = img.naturalWidth;
                    this.originalHeight = img.naturalHeight;
                };
                img.onerror = () => { this.error = 'Could not decode the image. The file may be corrupted.'; };
                img.src = this.originalSrc;
            };
            reader.onerror = () => { this.error = 'Could not read the file.'; };
            reader.readAsDataURL(file);
        },

        /* ── Core conversion via Canvas ── */
        async convert() {
            if (!this._img) {
                this.error = 'No image loaded. Please upload an image first.';
                return;
            }

            this.error        = '';
            this.loading      = true;
            this.convertedSrc = '';

            /* Yield so the loading spinner renders before the CPU-intensive work */
            await new Promise(r => setTimeout(r, 20));

            try {
                const canvas = document.createElement('canvas');
                canvas.width  = this._img.naturalWidth;
                canvas.height = this._img.naturalHeight;
                const ctx = canvas.getContext('2d');

                /* JPEG does not support transparency — fill white first */
                if (this.outputFormat === 'jpeg') {
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                }

                ctx.imageSmoothingEnabled = true;
                ctx.imageSmoothingQuality = 'high';
                ctx.drawImage(this._img, 0, 0);

                const mime     = 'image/' + this.outputFormat;
                const qualityV = this.outputFormat === 'png' ? undefined : this.quality / 100;

                const blob = await new Promise(resolve =>
                    canvas.toBlob(resolve, mime, qualityV)
                );

                if (!blob) {
                    throw new Error(`Your browser does not support ${this.outputLabel} output. Try a different format.`);
                }

                /* Some browsers silently fall back to PNG when WebP output isn't supported */
                if (this.outputFormat === 'webp' && blob.type !== 'image/webp') {
                    this.warning = 'Your browser does not support WebP output — the image was saved as PNG instead.';
                }

                this.convertedSize = blob.size;

                /* Blob → data URL for the preview <img> and download link */
                this.convertedSrc = await new Promise((resolve, reject) => {
                    const r = new FileReader();
                    r.onload  = e => resolve(e.target.result);
                    r.onerror = reject;
                    r.readAsDataURL(blob);
                });

            } catch (e) {
                this.error = 'Conversion failed: ' + e.message;
            } finally {
                this.loading = false;
            }
        },

        /* ── Download ── */
        download() {
            if (!this.convertedSrc) return;
            const baseName = this.file
                ? this.file.name.replace(/\.[^.]+$/, '')
                : 'image';
            const a = document.createElement('a');
            a.href     = this.convertedSrc;
            a.download = `${baseName}.${this.outputExt}`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        },

        /* ── Reset ── */
        reset() {
            this.file          = null;
            this.originalSrc   = '';
            this.convertedSrc  = '';
            this.originalWidth  = 0;
            this.originalHeight = 0;
            this.originalSize  = 0;
            this.convertedSize = 0;
            this.originalMime  = '';
            this.outputFormat  = 'jpeg';
            this.quality       = 85;
            this.loading       = false;
            this.error         = '';
            this.warning       = '';
            this._img          = null;
            if (this.$refs.fileInput) this.$refs.fileInput.value = '';
        },

        /* ── Utilities ── */
        formatBytes(bytes) {
            if (!bytes || bytes <= 0) return '0 B';
            if (bytes < 1024)        return bytes + ' B';
            if (bytes < 1048576)     return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / 1048576).toFixed(2) + ' MB';
        },

        mimeLabel(mime) {
            const map = {
                'image/jpeg':    'JPEG', 'image/jpg': 'JPEG',
                'image/png':     'PNG',
                'image/webp':    'WebP',
                'image/gif':     'GIF',
                'image/bmp':     'BMP',  'image/x-bmp': 'BMP', 'image/x-ms-bmp': 'BMP',
            };
            return map[mime] || (mime ? mime.replace('image/', '').toUpperCase() : '?');
        },
    };
}
</script>
@endpush
