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
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10" x-data="imageCompressor()">

        {{-- Global error --}}
        <div x-show="error" x-transition
             class="alert alert-error mb-5 flex items-start gap-2">
            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span x-text="error"></span>
        </div>

        {{-- ── UPLOAD ZONE (no file selected) ── --}}
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
                        🗜️
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-700">
                            <span x-show="!dragging">Drop your image here</span>
                            <span x-show="dragging" class="text-brand-600">Release to upload</span>
                        </p>
                        <p class="text-sm text-gray-400 mt-1">or <span class="text-brand-600 font-medium">click to browse</span> your files</p>
                    </div>
                    <div class="flex gap-2 flex-wrap justify-center">
                        <span class="badge badge-gray">JPEG</span>
                        <span class="badge badge-gray">PNG</span>
                        <span class="badge badge-gray">WebP</span>
                        <span class="badge badge-gray">GIF → static</span>
                    </div>
                    <p class="text-xs text-gray-400">Max file size: 20 MB</p>
                </div>
            </div>

            <input
                type="file"
                x-ref="fileInput"
                @change="onFileInput($event)"
                accept="image/jpeg,image/jpg,image/png,image/webp,image/gif"
                class="hidden"
            >
        </div>

        {{-- ── TOOL AREA (file loaded) ── --}}
        <div x-show="hasFile" x-transition>

            {{-- File info bar --}}
            <div class="card p-4 mb-5 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-xl bg-brand-50 flex items-center justify-center text-lg flex-shrink-0">🖼️</div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate" x-text="file ? file.name : ''"></p>
                        <p class="text-xs text-gray-400 mt-0.5" x-text="formatBytes(originalSize) + (originalDims ? '  ·  ' + originalDims + ' px' : '')"></p>
                    </div>
                </div>
                <button @click="reset()" class="btn btn-secondary btn-sm flex-shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Change Image
                </button>
            </div>

            {{-- Settings card --}}
            <div class="card p-5 mb-5">
                <div class="flex flex-wrap gap-5 items-start">

                    {{-- Output format --}}
                    <div class="flex-shrink-0">
                        <label class="form-label">Output Format</label>
                        <div class="inline-flex bg-gray-100 rounded-xl p-1 gap-0.5">
                            <button type="button"
                                @click="format = 'jpeg'; onSettingChange()"
                                class="px-3.5 py-1.5 rounded-lg text-sm font-medium transition-all duration-150"
                                :class="format === 'jpeg' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                            >JPEG</button>
                            <button type="button"
                                @click="format = 'webp'; onSettingChange()"
                                class="px-3.5 py-1.5 rounded-lg text-sm font-medium transition-all duration-150"
                                :class="format === 'webp' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                            >WebP</button>
                            <button type="button"
                                @click="format = 'png'; onSettingChange()"
                                class="px-3.5 py-1.5 rounded-lg text-sm font-medium transition-all duration-150"
                                :class="format === 'png' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                            >PNG</button>
                        </div>
                    </div>

                    {{-- Quality slider (hidden for PNG) --}}
                    <div class="flex-1 min-w-48" x-show="format !== 'png'">
                        <div class="flex items-center justify-between mb-2">
                            <label class="form-label mb-0">Quality</label>
                            <span class="text-sm font-semibold text-brand-600" x-text="quality + '%'"></span>
                        </div>
                        <input
                            type="range"
                            x-model.number="quality"
                            @input="onSettingChange()"
                            min="1" max="100"
                            class="w-full h-2 rounded-full appearance-none cursor-pointer accent-brand-600 bg-gray-200"
                        >
                        <div class="flex justify-between text-xs text-gray-400 mt-1">
                            <span>Smaller file</span>
                            <span>Better quality</span>
                        </div>
                    </div>

                    {{-- PNG note --}}
                    <div x-show="format === 'png'" class="flex-1 min-w-48">
                        <label class="form-label">Quality</label>
                        <p class="text-sm text-gray-400 bg-gray-50 rounded-xl px-4 py-2.5 border border-gray-100">
                            PNG uses lossless compression — quality is always 100%. Reduce dimensions below to save space.
                        </p>
                    </div>

                    {{-- Max dimension --}}
                    <div class="flex-shrink-0">
                        <label class="form-label">Max Width</label>
                        <select x-model="maxWidth" @change="onSettingChange()" class="form-input">
                            <option value="0">Original size</option>
                            <option value="1920">1920 px (Full HD)</option>
                            <option value="1280">1280 px (HD)</option>
                            <option value="800">800 px (Web)</option>
                            <option value="600">600 px (Thumbnail)</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Before / After preview --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">

                {{-- Original --}}
                <div class="card p-4">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-semibold text-gray-700">Original</span>
                        <span class="badge badge-gray" x-text="formatBytes(originalSize)"></span>
                    </div>
                    <div class="bg-gray-50 rounded-xl flex items-center justify-center overflow-hidden"
                         style="height: 220px; background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16'%3E%3Crect width='8' height='8' fill='%23f3f4f6'/%3E%3Crect x='8' y='8' width='8' height='8' fill='%23f3f4f6'/%3E%3Crect x='8' width='8' height='8' fill='%23e5e7eb'/%3E%3Crect y='8' width='8' height='8' fill='%23e5e7eb'/%3E%3C/svg%3E&quot;)">
                        <img x-show="originalSrc"
                             :src="originalSrc"
                             class="max-w-full max-h-full object-contain"
                             style="max-height: 220px;"
                             alt="Original image">
                    </div>
                    <p class="text-xs text-gray-400 mt-2 text-center" x-text="originalDims ? originalDims + ' px' : ''"></p>
                </div>

                {{-- Compressed --}}
                <div class="card p-4">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-semibold text-gray-700">Compressed</span>
                        <span
                            x-show="compressedSize > 0 && !loading"
                            class="badge"
                            :class="savings > 0 ? 'badge-success' : 'badge-warning'"
                            x-text="formatBytes(compressedSize)"
                        ></span>
                    </div>
                    <div class="bg-gray-50 rounded-xl flex items-center justify-center overflow-hidden"
                         style="height: 220px; background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16'%3E%3Crect width='8' height='8' fill='%23f3f4f6'/%3E%3Crect x='8' y='8' width='8' height='8' fill='%23f3f4f6'/%3E%3Crect x='8' width='8' height='8' fill='%23e5e7eb'/%3E%3Crect y='8' width='8' height='8' fill='%23e5e7eb'/%3E%3C/svg%3E&quot;)">
                        {{-- Loading spinner --}}
                        <div x-show="loading" class="flex flex-col items-center gap-3 text-gray-400">
                            <div class="spinner w-8 h-8 border-brand-400 border-2"></div>
                            <span class="text-sm">Compressing…</span>
                        </div>
                        {{-- Compressed preview --}}
                        <img
                            x-show="!loading && compressedSrc"
                            :src="compressedSrc"
                            x-transition
                            class="max-w-full max-h-full object-contain"
                            style="max-height: 220px;"
                            alt="Compressed image"
                        >
                        {{-- Placeholder --}}
                        <div x-show="!loading && !compressedSrc" class="text-gray-300 text-sm text-center px-4">
                            <svg class="w-12 h-12 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Adjusting settings will auto-compress
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2 text-center" x-text="compressedDims && !loading ? compressedDims + ' px' : ''"></p>
                </div>
            </div>

            {{-- Larger-than-original warning --}}
            <div
                x-show="hasResult && compressedSize >= originalSize"
                x-transition
                class="alert alert-warning mb-4 text-sm flex items-start gap-2"
            >
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span>The compressed file is larger than the original. Try lowering the quality, switching to WebP, or reducing the image dimensions.</span>
            </div>

            {{-- Stats row --}}
            <div x-show="hasResult" x-transition class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
                <div class="card p-4 text-center">
                    <div class="text-xl font-bold text-gray-900" x-text="formatBytes(originalSize)"></div>
                    <div class="text-xs text-gray-500 mt-1">Original size</div>
                </div>
                <div class="card p-4 text-center">
                    <div class="text-xl font-bold text-brand-600" x-text="formatBytes(compressedSize)"></div>
                    <div class="text-xs text-gray-500 mt-1">Compressed size</div>
                </div>
                <div class="card p-4 text-center">
                    <div class="text-xl font-bold"
                         :class="savedBytes > 0 ? 'text-emerald-600' : 'text-amber-600'"
                         x-text="(savedBytes > 0 ? '−' : '+') + formatBytes(Math.abs(savedBytes))"></div>
                    <div class="text-xs text-gray-500 mt-1">Size change</div>
                </div>
                <div class="card p-4 text-center">
                    <div class="text-xl font-bold"
                         :class="savings > 0 ? 'text-emerald-600' : 'text-amber-600'"
                         x-text="(savings > 0 ? '−' : '+') + Math.abs(savings) + '%'"></div>
                    <div class="text-xs text-gray-500 mt-1">Reduction</div>
                </div>
            </div>

            {{-- Action buttons --}}
            <div class="flex flex-wrap gap-3">
                <button
                    @click="download()"
                    :disabled="!hasResult || loading"
                    class="btn btn-success"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download Compressed Image
                </button>

                <button @click="compress()" :disabled="loading" class="btn btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span x-text="loading ? 'Compressing…' : 'Re-compress'"></span>
                </button>

                <button @click="reset()" class="btn btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Clear
                </button>
            </div>
        </div>

        {{-- Info card --}}
        <div class="card p-6 mt-8">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">About Image Compressor</h3>
            <ul class="space-y-2 text-sm text-gray-500">
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    Reduces image file size by re-encoding at a lower quality using your browser's built-in engine.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    <strong class="text-gray-700 font-medium">JPEG</strong> — Best for photographs. Quality slider controls the compression level.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    <strong class="text-gray-700 font-medium">WebP</strong> — Modern format with excellent compression. Recommended for web use.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    <strong class="text-gray-700 font-medium">PNG</strong> — Lossless format. Use the Max Width option to reduce dimensions and save space.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-amber-500 mt-0.5">🔒</span>
                    Your images are processed entirely in your browser using the HTML5 Canvas API — nothing is uploaded to any server.
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
 * Image Compressor — pure browser-side via HTML5 Canvas API.
 * No files are uploaded; all processing happens locally.
 */
function imageCompressor() {
    return {
        /* ── State ── */
        file:           null,
        originalSrc:    '',
        compressedSrc:  '',
        originalSize:   0,
        compressedSize: 0,
        originalDims:   '',
        compressedDims: '',
        quality:        80,
        format:         'jpeg',
        maxWidth:       '0',
        loading:        false,
        dragging:       false,
        error:          '',
        _debounce:      null,
        _img:           null,   // cached Image element (non-reactive, intentional)

        /* ── Computed ── */
        get hasFile()    { return !!this.file; },
        get hasResult()  { return !!this.compressedSrc && !this.loading; },
        get savings()    {
            if (!this.originalSize || !this.compressedSize) return 0;
            return Math.round((1 - this.compressedSize / this.originalSize) * 100);
        },
        get savedBytes() { return this.originalSize - this.compressedSize; },

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
            const ALLOWED = ['image/jpeg','image/jpg','image/png','image/webp','image/gif'];
            if (!ALLOWED.includes(file.type)) {
                this.error = 'Unsupported file type. Please upload a JPEG, PNG, WebP, or GIF image.';
                return;
            }
            if (file.size > 20 * 1024 * 1024) {
                this.error = 'File too large. Maximum supported size is 20 MB.';
                return;
            }

            this.error         = '';
            this.file          = file;
            this.originalSize  = file.size;
            this.compressedSrc = '';
            this.compressedSize = 0;
            this.compressedDims = '';
            this._img          = null;

            /* Auto-detect best output format */
            if (file.type === 'image/png')  this.format = 'png';
            else if (file.type === 'image/webp') this.format = 'webp';
            else this.format = 'jpeg';

            const reader = new FileReader();
            reader.onload = (ev) => {
                this.originalSrc = ev.target.result;

                const img = new Image();
                img.onload = () => {
                    this._img        = img;
                    this.originalDims = img.naturalWidth + ' × ' + img.naturalHeight;
                    this.compress();
                };
                img.onerror = () => { this.error = 'Could not read the image file.'; };
                img.src = this.originalSrc;
            };
            reader.onerror = () => { this.error = 'Could not read the file.'; };
            reader.readAsDataURL(file);
        },

        /* Called when any setting changes — debounced */
        onSettingChange() {
            if (!this._img) return;
            clearTimeout(this._debounce);
            this._debounce = setTimeout(() => this.compress(), 400);
        },

        /* ── Core compression via Canvas ── */
        async compress() {
            if (!this._img) return;

            this.loading       = true;
            this.compressedSrc = '';
            this.error         = '';

            /* Yield to browser so the loading spinner renders */
            await new Promise(r => setTimeout(r, 20));

            try {
                const img = this._img;
                let w = img.naturalWidth;
                let h = img.naturalHeight;
                const mw = parseInt(this.maxWidth) || 0;

                if (mw > 0 && w > mw) {
                    h = Math.round(h * mw / w);
                    w = mw;
                }

                this.compressedDims = w + ' × ' + h;

                const canvas = document.createElement('canvas');
                canvas.width  = w;
                canvas.height = h;
                const ctx = canvas.getContext('2d');

                /* White background prevents black fill on JPEG transparency */
                if (this.format === 'jpeg') {
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(0, 0, w, h);
                }

                ctx.drawImage(img, 0, 0, w, h);

                const mime     = 'image/' + this.format;
                const qualityV = this.format === 'png' ? undefined : this.quality / 100;

                const blob = await new Promise(resolve =>
                    canvas.toBlob(resolve, mime, qualityV)
                );

                if (!blob) throw new Error('Canvas returned no data. The browser may not support this format.');

                this.compressedSize = blob.size;

                /* Convert blob → data URL for <img> src and download */
                this.compressedSrc = await new Promise((resolve, reject) => {
                    const r = new FileReader();
                    r.onload  = e => resolve(e.target.result);
                    r.onerror = reject;
                    r.readAsDataURL(blob);
                });

            } catch (e) {
                this.error = 'Compression failed: ' + e.message;
            } finally {
                this.loading = false;
            }
        },

        /* ── Download ── */
        download() {
            if (!this.compressedSrc) return;
            const ext      = this.format === 'jpeg' ? 'jpg' : this.format;
            const baseName = this.file
                ? this.file.name.replace(/\.[^.]+$/, '')
                : 'image';
            const a = document.createElement('a');
            a.href     = this.compressedSrc;
            a.download = baseName + '_compressed.' + ext;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        },

        /* ── Reset ── */
        reset() {
            this.file           = null;
            this.originalSrc    = '';
            this.compressedSrc  = '';
            this.originalSize   = 0;
            this.compressedSize = 0;
            this.originalDims   = '';
            this.compressedDims = '';
            this.quality        = 80;
            this.format         = 'jpeg';
            this.maxWidth       = '0';
            this.error          = '';
            this.loading        = false;
            this.dragging       = false;
            this._img           = null;
            clearTimeout(this._debounce);
            if (this.$refs.fileInput) this.$refs.fileInput.value = '';
        },

        /* ── Utility ── */
        formatBytes(bytes) {
            if (!bytes || bytes <= 0) return '0 B';
            if (bytes < 1024)        return bytes + ' B';
            if (bytes < 1048576)     return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / 1048576).toFixed(2) + ' MB';
        },
    };
}
</script>
@endpush
