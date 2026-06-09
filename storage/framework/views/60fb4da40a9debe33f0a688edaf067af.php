<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50">

    
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                <?php echo e($tool->icon); ?> <?php echo e($tool->name); ?>

            </h1>
            <p class="text-gray-500 mt-2"><?php echo e($tool->short_description); ?></p>
        </div>
    </div>

    
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10" x-data="imageResizer()">

        
        <div x-show="error" x-transition
             class="alert alert-error mb-5 flex items-start gap-2">
            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span x-text="error"></span>
        </div>

        
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
                        🖼️
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
                        <span class="badge badge-gray">JPEG</span>
                        <span class="badge badge-gray">PNG</span>
                        <span class="badge badge-gray">WebP</span>
                        <span class="badge badge-gray">GIF</span>
                    </div>
                    <p class="text-xs text-gray-400">Max file size: 30 MB</p>
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

        
        <div x-show="hasFile" x-transition>

            
            <div class="card p-4 mb-5 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-xl bg-brand-50 flex items-center justify-center text-lg flex-shrink-0">
                        🖼️
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate" x-text="file ? file.name : ''"></p>
                        <p class="text-xs text-gray-400 mt-0.5"
                           x-text="formatBytes(originalSize) + (originalWidth ? '  ·  ' + originalWidth + ' × ' + originalHeight + ' px' : '')"></p>
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

            
            <div class="card p-5 mb-5">

                
                <div class="mb-5">
                    <label class="form-label">Target Dimensions</label>
                    <div class="flex items-end gap-2">
                        
                        <div class="flex-1">
                            <label class="text-xs text-gray-500 mb-1 block">Width (px)</label>
                            <input
                                type="number"
                                x-model.number="newWidth"
                                @input="onWidthInput()"
                                min="1" max="10000"
                                placeholder="Width"
                                class="form-input text-center font-mono"
                            >
                        </div>

                        
                        <div class="flex flex-col items-center gap-1 pb-0.5">
                            <span class="text-xs text-gray-400" x-text="lockRatio ? 'Locked' : 'Free'"></span>
                            <button
                                type="button"
                                @click="toggleLock()"
                                class="w-9 h-9 rounded-xl border-2 flex items-center justify-center transition-all duration-150 focus:outline-none"
                                :class="lockRatio
                                    ? 'bg-brand-50 border-brand-300 text-brand-600 hover:bg-brand-100'
                                    : 'bg-gray-50 border-gray-200 text-gray-400 hover:bg-gray-100'"
                                :title="lockRatio ? 'Click to unlock aspect ratio' : 'Click to lock aspect ratio'"
                            >
                                
                                <svg x-show="lockRatio" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                
                                <svg x-show="!lockRatio" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 11V7a4 4 0 018 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                </svg>
                            </button>
                        </div>

                        
                        <div class="flex-1">
                            <label class="text-xs text-gray-500 mb-1 block">Height (px)</label>
                            <input
                                type="number"
                                x-model.number="newHeight"
                                @input="onHeightInput()"
                                min="1" max="10000"
                                placeholder="Height"
                                class="form-input text-center font-mono"
                                :class="lockRatio ? 'bg-gray-50 text-gray-500 cursor-not-allowed' : ''"
                                :readonly="lockRatio"
                            >
                        </div>
                    </div>

                    
                    <p
                        x-show="newWidth > originalWidth || newHeight > originalHeight"
                        class="text-xs text-amber-600 mt-2 flex items-center gap-1"
                    >
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Upscaling beyond the original size may result in a blurry image.
                    </p>
                </div>

                
                <div class="mb-5">
                    <label class="form-label">Quick Presets <span class="font-normal text-gray-400">(sets width; height adjusts if ratio is locked)</span></label>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" @click="applyPreset(320)"  class="btn btn-secondary btn-sm">320 px</button>
                        <button type="button" @click="applyPreset(640)"  class="btn btn-secondary btn-sm">640 px</button>
                        <button type="button" @click="applyPreset(800)"  class="btn btn-secondary btn-sm">800 px</button>
                        <button type="button" @click="applyPreset(1280)" class="btn btn-secondary btn-sm">1280 px</button>
                        <button type="button" @click="applyPreset(1920)" class="btn btn-secondary btn-sm">1920 px</button>
                        <button type="button" @click="applyPreset(originalWidth)" class="btn btn-secondary btn-sm">Original</button>
                    </div>
                </div>

                
                <div class="flex flex-wrap gap-5 items-start">
                    <div class="flex-shrink-0">
                        <label class="form-label">Output Format</label>
                        <div class="inline-flex bg-gray-100 rounded-xl p-1 gap-0.5">
                            <button type="button"
                                @click="format = 'jpeg'"
                                class="px-3.5 py-1.5 rounded-lg text-sm font-medium transition-all duration-150"
                                :class="format === 'jpeg' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                            >JPEG</button>
                            <button type="button"
                                @click="format = 'webp'"
                                class="px-3.5 py-1.5 rounded-lg text-sm font-medium transition-all duration-150"
                                :class="format === 'webp' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                            >WebP</button>
                            <button type="button"
                                @click="format = 'png'"
                                class="px-3.5 py-1.5 rounded-lg text-sm font-medium transition-all duration-150"
                                :class="format === 'png' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                            >PNG</button>
                        </div>
                    </div>

                    <div class="flex-1 min-w-48" x-show="format !== 'png'">
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

                    <div x-show="format === 'png'" class="flex-1 min-w-48">
                        <label class="form-label">Quality</label>
                        <p class="text-sm text-gray-400 bg-gray-50 rounded-xl px-4 py-2.5 border border-gray-100">
                            PNG is lossless — quality is always 100%.
                        </p>
                    </div>
                </div>
            </div>

            
            <div class="flex flex-wrap gap-3 mb-5">
                <button @click="resize()" :disabled="loading" class="btn btn-primary">
                    <svg x-show="loading" class="spinner w-4 h-4" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                    </svg>
                    <span x-text="loading ? 'Resizing…' : 'Resize Image'"></span>
                </button>

                <button @click="download()" :disabled="!hasResult || loading" class="btn btn-success" x-show="hasResult">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download
                </button>

                <button @click="reset()" class="btn btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Clear
                </button>
            </div>

            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">

                
                <div class="card p-4">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-semibold text-gray-700">Original</span>
                        <div class="flex items-center gap-2">
                            <span class="badge badge-gray text-xs" x-text="originalWidth + ' × ' + originalHeight"></span>
                            <span class="badge badge-gray" x-text="formatBytes(originalSize)"></span>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl flex items-center justify-center overflow-hidden"
                         style="height:220px;background-image:url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16'%3E%3Crect width='8' height='8' fill='%23f3f4f6'/%3E%3Crect x='8' y='8' width='8' height='8' fill='%23f3f4f6'/%3E%3Crect x='8' width='8' height='8' fill='%23e5e7eb'/%3E%3Crect y='8' width='8' height='8' fill='%23e5e7eb'/%3E%3C/svg%3E&quot;)">
                        <img x-show="originalSrc"
                             :src="originalSrc"
                             class="max-w-full max-h-full object-contain"
                             style="max-height:220px"
                             alt="Original">
                    </div>
                </div>

                
                <div class="card p-4">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-semibold text-gray-700">Resized</span>
                        <div class="flex items-center gap-2" x-show="hasResult">
                            <span class="badge badge-primary text-xs" x-text="newWidth + ' × ' + newHeight"></span>
                            <span class="badge badge-success" x-text="formatBytes(resizedSize)"></span>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl flex items-center justify-center overflow-hidden"
                         style="height:220px;background-image:url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16'%3E%3Crect width='8' height='8' fill='%23f3f4f6'/%3E%3Crect x='8' y='8' width='8' height='8' fill='%23f3f4f6'/%3E%3Crect x='8' width='8' height='8' fill='%23e5e7eb'/%3E%3Crect y='8' width='8' height='8' fill='%23e5e7eb'/%3E%3C/svg%3E&quot;)">

                        
                        <div x-show="loading" class="flex flex-col items-center gap-3 text-gray-400">
                            <div class="spinner w-8 h-8 border-brand-400 border-2"></div>
                            <span class="text-sm">Resizing…</span>
                        </div>

                        
                        <img
                            x-show="!loading && resizedSrc"
                            :src="resizedSrc"
                            x-transition
                            class="max-w-full max-h-full object-contain"
                            style="max-height:220px"
                            alt="Resized"
                        >

                        
                        <div x-show="!loading && !resizedSrc"
                             class="text-gray-300 text-sm text-center px-4">
                            <svg class="w-12 h-12 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                            </svg>
                            Set dimensions and click Resize
                        </div>
                    </div>
                </div>
            </div>

            
            <div x-show="hasResult" x-transition class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
                <div class="card p-4 text-center">
                    <div class="text-lg font-bold text-gray-900" x-text="originalWidth + '×' + originalHeight"></div>
                    <div class="text-xs text-gray-500 mt-1">Original dims</div>
                </div>
                <div class="card p-4 text-center">
                    <div class="text-lg font-bold text-brand-600" x-text="newWidth + '×' + newHeight"></div>
                    <div class="text-xs text-gray-500 mt-1">New dims</div>
                </div>
                <div class="card p-4 text-center">
                    <div class="text-lg font-bold text-gray-900" x-text="formatBytes(originalSize)"></div>
                    <div class="text-xs text-gray-500 mt-1">Original size</div>
                </div>
                <div class="card p-4 text-center">
                    <div class="text-lg font-bold"
                         :class="resizedSize < originalSize ? 'text-emerald-600' : 'text-amber-600'"
                         x-text="formatBytes(resizedSize)"></div>
                    <div class="text-xs text-gray-500 mt-1">Output size</div>
                </div>
            </div>

        </div>

        
        <div class="card p-6 mt-8">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">About Image Resizer</h3>
            <ul class="space-y-2 text-sm text-gray-500">
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    Resize images to any custom width and height using your browser's Canvas API.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    <strong class="text-gray-700 font-medium">Lock ratio</strong> — keeps width and height proportional so the image isn't distorted.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    <strong class="text-gray-700 font-medium">Free resize</strong> — unlock the ratio to set any width and height independently.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    Supports JPEG, PNG, and WebP output. GIF input is converted to a static image.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-amber-500 mt-0.5">🔒</span>
                    Your images are processed entirely in your browser — nothing is sent to any server.
                </li>
            </ul>
        </div>

        
        <?php if($relatedTools->count()): ?>
        <div class="mt-8">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Related Tools</h3>
            <div class="grid grid-cols-2 gap-3">
                <?php $__currentLoopData = $relatedTools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('tools.show', $related->slug)); ?>"
                   class="card-hover p-4 flex items-center gap-3 no-underline">
                    <span class="text-2xl"><?php echo e($related->icon); ?></span>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate"><?php echo e($related->name); ?></p>
                        <p class="text-xs text-gray-400 truncate"><?php echo e($related->short_description); ?></p>
                    </div>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
/*
 * Image Resizer — pure browser-side via HTML5 Canvas API.
 * No files are uploaded; all processing happens locally.
 */
function imageResizer() {
    return {
        /* ── State ── */
        file:          null,
        originalSrc:   '',
        resizedSrc:    '',
        originalWidth: 0,
        originalHeight:0,
        originalSize:  0,
        resizedSize:   0,
        newWidth:      0,
        newHeight:     0,
        lockRatio:     true,
        format:        'jpeg',
        quality:       90,
        loading:       false,
        dragging:      false,
        error:         '',
        _ratio:        1,   // w/h aspect ratio (non-reactive)
        _img:          null, // cached Image element (non-reactive)

        /* ── Computed ── */
        get hasFile()   { return !!this.file; },
        get hasResult() { return !!this.resizedSrc && !this.loading; },

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
            if (file.size > 30 * 1024 * 1024) {
                this.error = 'File too large. Maximum supported size is 30 MB.';
                return;
            }

            this.error         = '';
            this.file          = file;
            this.originalSize  = file.size;
            this.resizedSrc    = '';
            this.resizedSize   = 0;
            this._img          = null;

            /* Auto-detect output format */
            if      (file.type === 'image/png')  this.format = 'png';
            else if (file.type === 'image/webp') this.format = 'webp';
            else                                 this.format = 'jpeg';

            const reader = new FileReader();
            reader.onload = (ev) => {
                this.originalSrc = ev.target.result;
                const img = new Image();
                img.onload = () => {
                    this._img          = img;
                    this.originalWidth  = img.naturalWidth;
                    this.originalHeight = img.naturalHeight;
                    this._ratio        = img.naturalWidth / img.naturalHeight;
                    this.newWidth      = img.naturalWidth;
                    this.newHeight     = img.naturalHeight;
                };
                img.onerror = () => { this.error = 'Could not read the image file.'; };
                img.src = this.originalSrc;
            };
            reader.onerror = () => { this.error = 'Could not read the file.'; };
            reader.readAsDataURL(file);
        },

        /* ── Dimension change handlers ── */
        onWidthInput() {
            const w = parseInt(this.newWidth) || 0;
            if (this.lockRatio && w > 0 && this._ratio > 0) {
                this.newHeight = Math.max(1, Math.round(w / this._ratio));
            }
        },

        onHeightInput() {
            /* only fires when ratio is unlocked (height field is readonly when locked) */
            const h = parseInt(this.newHeight) || 0;
            if (!this.lockRatio && h > 0 && this._ratio > 0) {
                /* no auto-adjustment when free */
            }
        },

        toggleLock() {
            this.lockRatio = !this.lockRatio;
            /* If re-locking, sync height from current width */
            if (this.lockRatio && this._ratio > 0) {
                const w = parseInt(this.newWidth) || this.originalWidth;
                this.newHeight = Math.max(1, Math.round(w / this._ratio));
            }
        },

        applyPreset(w) {
            this.newWidth = w;
            if (this.lockRatio && this._ratio > 0) {
                this.newHeight = Math.max(1, Math.round(w / this._ratio));
            }
        },

        /* ── Core resize via Canvas ── */
        async resize() {
            if (!this._img) return;

            const w = parseInt(this.newWidth);
            const h = parseInt(this.newHeight);

            if (!w || !h || w <= 0 || h <= 0) {
                this.error = 'Please enter valid width and height values (must be positive numbers).';
                return;
            }
            if (w > 10000 || h > 10000) {
                this.error = 'Maximum dimension is 10,000 px per side.';
                return;
            }

            this.error      = '';
            this.loading    = true;
            this.resizedSrc = '';

            /* Yield to browser so spinner renders */
            await new Promise(r => setTimeout(r, 20));

            try {
                const canvas = document.createElement('canvas');
                canvas.width  = w;
                canvas.height = h;
                const ctx = canvas.getContext('2d');

                /* White background for JPEG to prevent black fill on transparent pixels */
                if (this.format === 'jpeg') {
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(0, 0, w, h);
                }

                /* Use high-quality image smoothing */
                ctx.imageSmoothingEnabled  = true;
                ctx.imageSmoothingQuality  = 'high';

                ctx.drawImage(this._img, 0, 0, w, h);

                const mime     = 'image/' + this.format;
                const qualityV = this.format === 'png' ? undefined : this.quality / 100;

                const blob = await new Promise(resolve =>
                    canvas.toBlob(resolve, mime, qualityV)
                );

                if (!blob) throw new Error('Canvas returned no data. The browser may not support this output format.');

                this.resizedSize = blob.size;

                this.resizedSrc = await new Promise((resolve, reject) => {
                    const r = new FileReader();
                    r.onload  = e => resolve(e.target.result);
                    r.onerror = reject;
                    r.readAsDataURL(blob);
                });

            } catch (e) {
                this.error = 'Resize failed: ' + e.message;
            } finally {
                this.loading = false;
            }
        },

        /* ── Download ── */
        download() {
            if (!this.resizedSrc) return;
            const ext      = this.format === 'jpeg' ? 'jpg' : this.format;
            const baseName = this.file
                ? this.file.name.replace(/\.[^.]+$/, '')
                : 'image';
            const a = document.createElement('a');
            a.href     = this.resizedSrc;
            a.download = `${baseName}_${this.newWidth}x${this.newHeight}.${ext}`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        },

        /* ── Reset ── */
        reset() {
            this.file          = null;
            this.originalSrc   = '';
            this.resizedSrc    = '';
            this.originalWidth  = 0;
            this.originalHeight = 0;
            this.originalSize  = 0;
            this.resizedSize   = 0;
            this.newWidth      = 0;
            this.newHeight     = 0;
            this.lockRatio     = true;
            this.format        = 'jpeg';
            this.quality       = 90;
            this.error         = '';
            this.loading       = false;
            this._img          = null;
            this._ratio        = 1;
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\image-resizer.blade.php ENDPATH**/ ?>