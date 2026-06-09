<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50">

    
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                <?php echo e($tool->icon); ?> <?php echo e($tool->name); ?>

            </h1>
            <p class="text-gray-500 mt-2"><?php echo e($tool->short_description); ?></p>
        </div>
    </div>

    
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-10" x-data="memeGenerator()">

        
        <div x-show="error" x-transition
             class="alert alert-error mb-5 flex items-start gap-2">
            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span x-text="error"></span>
        </div>

        
        <div x-show="!hasImage" x-transition>

            
            <div
                class="border-2 border-dashed rounded-2xl p-14 sm:p-20 text-center cursor-pointer transition-all duration-200 select-none mb-4"
                :class="dragging ? 'border-brand-400 bg-brand-50' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50'"
                @dragover.prevent="dragging = true"
                @dragleave.prevent="dragging = false"
                @drop.prevent="onDrop($event)"
                @click="$refs.fileInput.click()"
            >
                <div class="flex flex-col items-center gap-4 pointer-events-none">
                    <div class="w-20 h-20 rounded-2xl bg-brand-50 flex items-center justify-center text-5xl shadow-sm">😂</div>
                    <div>
                        <p class="text-lg font-semibold text-gray-700">
                            <span x-show="!dragging">Upload your meme image</span>
                            <span x-show="dragging" class="text-brand-600">Release to upload</span>
                        </p>
                        <p class="text-sm text-gray-400 mt-1">or <span class="text-brand-600 font-medium">click to browse</span></p>
                    </div>
                    <div class="flex gap-2 flex-wrap justify-center">
                        <span class="badge badge-gray">JPG</span>
                        <span class="badge badge-gray">PNG</span>
                        <span class="badge badge-gray">WebP</span>
                        <span class="badge badge-gray">GIF</span>
                        <span class="badge badge-gray">BMP</span>
                    </div>
                    <p class="text-xs text-gray-400">Max 20 MB</p>
                </div>
            </div>
            <input type="file" x-ref="fileInput" @change="onFileInput($event)"
                   accept="image/jpeg,image/jpg,image/png,image/webp,image/gif,image/bmp" class="hidden">

            
            <div class="card p-4">
                <label class="form-label mb-2">Or load from URL</label>
                <div class="flex gap-2">
                    <input type="url" x-model="imageUrl"
                           @keydown.enter.prevent="loadFromUrl()"
                           placeholder="https://example.com/meme-template.jpg"
                           class="form-input flex-1">
                    <button @click="loadFromUrl()" class="btn btn-secondary flex-shrink-0">Load</button>
                </div>
                <p class="form-help mt-1.5">⚠ Some image hosts block cross-origin requests — upload directly for best results.</p>
            </div>
        </div>

        
        <div x-show="hasImage" x-transition>
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 items-start">

                
                <div class="lg:col-span-2 space-y-4">

                    
                    <div class="card p-4 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-xl bg-brand-50 flex items-center justify-center text-lg flex-shrink-0">🖼️</div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate"
                                   x-text="file ? file.name : (imageUrl ? 'Image from URL' : 'Image')"></p>
                                <p class="text-xs text-gray-400 mt-0.5"
                                   x-text="(originalWidth ? originalWidth + '×' + originalHeight + ' px' : '') + (originalSize ? ' · ' + formatBytes(originalSize) : '')"></p>
                            </div>
                        </div>
                        <button @click="reset()" class="btn btn-secondary btn-sm flex-shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            Change
                        </button>
                    </div>

                    
                    <div class="card p-5 space-y-4">
                        <h3 class="text-sm font-semibold text-gray-700">Meme Text</h3>

                        <div>
                            <label class="form-label">Top Text</label>
                            <textarea
                                x-model="topText"
                                rows="2"
                                placeholder="TOP TEXT"
                                class="form-input resize-none font-bold tracking-wide uppercase"
                                style="font-family: Impact, Arial Black, sans-serif;"
                            ></textarea>
                        </div>

                        <div>
                            <label class="form-label">Bottom Text</label>
                            <textarea
                                x-model="bottomText"
                                rows="2"
                                placeholder="BOTTOM TEXT"
                                class="form-input resize-none font-bold tracking-wide uppercase"
                                style="font-family: Impact, Arial Black, sans-serif;"
                            ></textarea>
                        </div>
                    </div>

                    
                    <div class="card p-5">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Style Presets</h3>
                        <div class="grid grid-cols-2 gap-2">

                            <button type="button" @click="applyPreset('classic')"
                                class="p-3 rounded-xl border-2 transition-all text-left"
                                :class="activePreset === 'classic' ? 'border-brand-400 bg-brand-50' : 'border-gray-100 hover:border-gray-200 hover:bg-gray-50'">
                                <div class="text-sm font-black text-white leading-tight px-1 py-0.5 rounded text-center"
                                     style="font-family:Impact,Arial Black,sans-serif;background:#222;text-shadow:-1px -1px 0 #000,1px -1px 0 #000,-1px 1px 0 #000,1px 1px 0 #000">
                                    CLASSIC
                                </div>
                                <p class="text-xs text-gray-500 mt-1.5">Impact · White · Black outline</p>
                            </button>

                            <button type="button" @click="applyPreset('modern')"
                                class="p-3 rounded-xl border-2 transition-all text-left"
                                :class="activePreset === 'modern' ? 'border-brand-400 bg-brand-50' : 'border-gray-100 hover:border-gray-200 hover:bg-gray-50'">
                                <div class="text-sm font-black text-white leading-tight px-1 py-0.5 rounded text-center"
                                     style="font-family:Arial Black,sans-serif;background:#444;text-shadow:-1px -1px 0 #111,1px -1px 0 #111,-1px 1px 0 #111,1px 1px 0 #111">
                                    Modern
                                </div>
                                <p class="text-xs text-gray-500 mt-1.5">Arial Black · Mixed case</p>
                            </button>

                            <button type="button" @click="applyPreset('dark')"
                                class="p-3 rounded-xl border-2 transition-all text-left"
                                :class="activePreset === 'dark' ? 'border-brand-400 bg-brand-50' : 'border-gray-100 hover:border-gray-200 hover:bg-gray-50'">
                                <div class="text-sm font-black text-gray-900 leading-tight px-1 py-0.5 rounded text-center"
                                     style="font-family:Arial Black,sans-serif;background:#e5e7eb;text-shadow:-1px -1px 0 #fff,1px -1px 0 #fff,-1px 1px 0 #fff,1px 1px 0 #fff">
                                    Dark Text
                                </div>
                                <p class="text-xs text-gray-500 mt-1.5">Black text · White outline</p>
                            </button>

                            <button type="button" @click="applyPreset('clean')"
                                class="p-3 rounded-xl border-2 transition-all text-left"
                                :class="activePreset === 'clean' ? 'border-brand-400 bg-brand-50' : 'border-gray-100 hover:border-gray-200 hover:bg-gray-50'">
                                <div class="text-sm font-bold text-white leading-tight px-1 py-0.5 rounded text-center"
                                     style="font-family:Verdana,sans-serif;background:#555">
                                    Clean
                                </div>
                                <p class="text-xs text-gray-500 mt-1.5">Verdana · Thin outline</p>
                            </button>
                        </div>
                    </div>

                    
                    <div class="card p-5 space-y-4">
                        <h3 class="text-sm font-semibold text-gray-700">Text Style</h3>

                        
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="form-label">Font</label>
                                <select x-model="fontFamily" class="form-input">
                                    <option value="Impact">Impact</option>
                                    <option value="Arial Black">Arial Black</option>
                                    <option value="Comic Sans MS">Comic Sans MS</option>
                                    <option value="Verdana">Verdana</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Trebuchet MS">Trebuchet MS</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">
                                    Size
                                    <span class="text-brand-600 font-semibold ml-1" x-text="fontSize + '%'"></span>
                                </label>
                                <input type="range" x-model.number="fontSize"
                                       min="3" max="18" step="0.5"
                                       class="w-full h-2 rounded-full appearance-none cursor-pointer accent-brand-600 bg-gray-200 mt-3">
                            </div>
                        </div>

                        
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="form-label">Text Color</label>
                                <div class="relative">
                                    <div class="form-input flex items-center gap-2 cursor-pointer">
                                        <span class="w-5 h-5 rounded border border-gray-200 flex-shrink-0"
                                              :style="`background:${textColor}`"></span>
                                        <span class="font-mono text-xs uppercase" x-text="textColor"></span>
                                    </div>
                                    <input type="color" x-model="textColor"
                                           class="absolute inset-0 opacity-0 cursor-pointer w-full h-full rounded-xl">
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Outline Color</label>
                                <div class="relative">
                                    <div class="form-input flex items-center gap-2 cursor-pointer">
                                        <span class="w-5 h-5 rounded border border-gray-200 flex-shrink-0"
                                              :style="`background:${strokeColor}`"></span>
                                        <span class="font-mono text-xs uppercase" x-text="strokeColor"></span>
                                    </div>
                                    <input type="color" x-model="strokeColor"
                                           class="absolute inset-0 opacity-0 cursor-pointer w-full h-full rounded-xl">
                                </div>
                            </div>
                        </div>

                        
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="form-label mb-0">Outline Width</label>
                                <span class="text-xs font-semibold text-brand-600" x-text="strokeWidth === 0 ? 'Off' : strokeWidth + 'px'"></span>
                            </div>
                            <input type="range" x-model.number="strokeWidth"
                                   min="0" max="12" step="1"
                                   class="w-full h-2 rounded-full appearance-none cursor-pointer accent-brand-600 bg-gray-200">
                            <div class="flex justify-between text-xs text-gray-400 mt-1">
                                <span>None</span><span>Thick</span>
                            </div>
                        </div>

                        
                        <div>
                            <label class="form-label mb-2">Text Alignment</label>
                            <div class="inline-flex bg-gray-100 rounded-xl p-1 gap-0.5 w-full">
                                <button type="button" @click="textAlign = 'left'"
                                    class="flex-1 py-1.5 rounded-lg transition-all duration-150 flex items-center justify-center"
                                    :class="textAlign === 'left' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-400 hover:text-gray-600'">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h8a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h8a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                <button type="button" @click="textAlign = 'center'"
                                    class="flex-1 py-1.5 rounded-lg transition-all duration-150 flex items-center justify-center"
                                    :class="textAlign === 'center' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-400 hover:text-gray-600'">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm2 4a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm-2 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm2 4a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                <button type="button" @click="textAlign = 'right'"
                                    class="flex-1 py-1.5 rounded-lg transition-all duration-150 flex items-center justify-center"
                                    :class="textAlign === 'right' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-400 hover:text-gray-600'">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm4 4a1 1 0 011-1h8a1 1 0 110 2H8a1 1 0 01-1-1zm-4 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm4 4a1 1 0 011-1h8a1 1 0 110 2H8a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        
                        <div class="flex flex-wrap gap-x-5 gap-y-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <button type="button" role="switch" :aria-checked="uppercase"
                                    @click="uppercase = !uppercase"
                                    class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1"
                                    :class="uppercase ? 'bg-brand-600' : 'bg-gray-200'">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform"
                                          :class="uppercase ? 'translate-x-4' : 'translate-x-0'"></span>
                                </button>
                                <span class="text-xs text-gray-600 font-medium">ALL CAPS</span>
                            </label>

                            <label class="flex items-center gap-2 cursor-pointer">
                                <button type="button" role="switch" :aria-checked="bold"
                                    @click="bold = !bold"
                                    class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1"
                                    :class="bold ? 'bg-brand-600' : 'bg-gray-200'">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform"
                                          :class="bold ? 'translate-x-4' : 'translate-x-0'"></span>
                                </button>
                                <span class="text-xs text-gray-600 font-medium">Bold</span>
                            </label>

                            <label class="flex items-center gap-2 cursor-pointer">
                                <button type="button" role="switch" :aria-checked="italic"
                                    @click="italic = !italic"
                                    class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1"
                                    :class="italic ? 'bg-brand-600' : 'bg-gray-200'">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform"
                                          :class="italic ? 'translate-x-4' : 'translate-x-0'"></span>
                                </button>
                                <span class="text-xs text-gray-600 font-medium"><em>Italic</em></span>
                            </label>
                        </div>
                    </div>

                    
                    <div class="card p-5 space-y-3">
                        <h3 class="text-sm font-semibold text-gray-700">Export</h3>
                        <div>
                            <label class="form-label">Format</label>
                            <div class="inline-flex bg-gray-100 rounded-xl p-1 gap-0.5 w-full">
                                <button type="button" @click="downloadFormat = 'png'"
                                    class="flex-1 px-3 py-1.5 rounded-lg text-sm font-medium transition-all"
                                    :class="downloadFormat === 'png' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
                                    PNG <span class="text-xs text-gray-400">(lossless)</span>
                                </button>
                                <button type="button" @click="downloadFormat = 'jpeg'"
                                    class="flex-1 px-3 py-1.5 rounded-lg text-sm font-medium transition-all"
                                    :class="downloadFormat === 'jpeg' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
                                    JPG <span class="text-xs text-gray-400">(smaller)</span>
                                </button>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <button @click="download()" class="btn btn-success flex-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Download Meme
                            </button>
                            <button @click="reset()" class="btn btn-secondary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Reset
                            </button>
                        </div>
                    </div>

                </div>

                
                <div class="lg:col-span-3">
                    <div class="card overflow-hidden sticky top-6">

                        
                        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-700">Live Preview</span>
                            <span class="badge badge-success text-xs">Updates automatically</span>
                        </div>

                        
                        <div class="flex items-center justify-center bg-gray-100 p-3"
                             style="min-height:300px;background-image:url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20'%3E%3Crect width='10' height='10' fill='%23e5e7eb'/%3E%3Crect x='10' y='10' width='10' height='10' fill='%23e5e7eb'/%3E%3Crect x='10' width='10' height='10' fill='%23d1d5db'/%3E%3Crect y='10' width='10' height='10' fill='%23d1d5db'/%3E%3C/svg%3E&quot;)">
                            <canvas
                                x-ref="canvas"
                                class="block rounded-lg shadow-md"
                                style="max-width:100%;max-height:520px;"
                            ></canvas>
                        </div>

                        
                        <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400">
                            <span>Preview scales to fit · Download saves full resolution</span>
                            <span x-text="originalWidth && originalHeight ? originalWidth + ' × ' + originalHeight : ''"></span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        
        <div class="card p-6 mt-10">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">About Meme Generator</h3>
            <ul class="space-y-2 text-sm text-gray-500">
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    Upload any image or load from URL, add top and bottom text, customize the style, and download your meme.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    Long text is automatically word-wrapped to fit the image width.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    Style presets let you jump straight to classic Impact, modern, dark, or clean looks with one click.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    Download as PNG (lossless, best quality) or JPG (smaller file size, great for sharing).
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">🔒</span>
                    Your images are processed entirely in your browser using the Canvas API — nothing is uploaded to any server.
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
 * Meme Generator — pure browser-side via HTML5 Canvas API.
 * Renders top/bottom text with word-wrap, outline, and full font control.
 * Download exports the canvas at original image resolution.
 */
function memeGenerator() {
    return {
        /* ── Image ── */
        file:           null,
        originalWidth:  0,
        originalHeight: 0,
        originalSize:   0,
        originalMime:   '',
        imageUrl:       '',
        dragging:       false,

        /* ── Text ── */
        topText:    'TOP TEXT',
        bottomText: 'BOTTOM TEXT',

        /* ── Style ── */
        fontFamily:   'Impact',
        fontSize:     8,        // % of image width
        uppercase:    true,
        bold:         false,
        italic:       false,
        textColor:    '#ffffff',
        strokeColor:  '#000000',
        strokeWidth:  4,        // scaled internally by image size
        textAlign:    'center',

        /* ── Export ── */
        downloadFormat: 'png',

        /* ── UI ── */
        activePreset: 'classic',
        error:        '',
        _img:         null,     // cached Image element (non-reactive)
        _timer:       null,

        /* ── Computed ── */
        get hasImage() { return !!this._img; },

        /* ── Lifecycle: register watchers for live re-render ── */
        init() {
            const re = () => this.scheduleRender();
            [
                'topText','bottomText','fontFamily','fontSize',
                'uppercase','bold','italic',
                'textColor','strokeColor','strokeWidth','textAlign',
            ].forEach(prop => this.$watch(prop, re));
        },

        scheduleRender() {
            if (!this._img) return;
            clearTimeout(this._timer);
            this._timer = setTimeout(() => this.render(), 120);
        },

        /* ── File handlers ── */
        onFileInput(e) { const f = e.target.files[0]; if (f) this.loadFile(f); },
        onDrop(e)      { this.dragging = false; const f = e.dataTransfer.files[0]; if (f) this.loadFile(f); },

        loadFile(file) {
            const ALLOWED = [
                'image/jpeg','image/jpg','image/png','image/webp',
                'image/gif','image/bmp','image/x-bmp','image/x-ms-bmp',
            ];
            if (!ALLOWED.includes(file.type)) {
                this.error = 'Unsupported format. Please upload JPG, PNG, WebP, GIF, or BMP.';
                return;
            }
            if (file.size > 20 * 1024 * 1024) {
                this.error = 'File too large. Maximum supported size is 20 MB.';
                return;
            }
            this.error         = '';
            this.file          = file;
            this.originalSize  = file.size;
            this.originalMime  = file.type;
            this.imageUrl      = '';

            const reader = new FileReader();
            reader.onload = ev => {
                const img = new Image();
                img.onload = () => {
                    this._img          = img;
                    this.originalWidth  = img.naturalWidth;
                    this.originalHeight = img.naturalHeight;
                    this.render();
                };
                img.onerror = () => { this.error = 'Could not decode the image. The file may be corrupted.'; };
                img.src = ev.target.result;
            };
            reader.onerror = () => { this.error = 'Could not read the file.'; };
            reader.readAsDataURL(file);
        },

        loadFromUrl() {
            const url = this.imageUrl.trim();
            if (!url) { this.error = 'Please enter an image URL.'; return; }
            this.error = '';

            const img = new Image();
            img.crossOrigin = 'anonymous'; // required for canvas security model
            img.onload = () => {
                this._img          = img;
                this.originalWidth  = img.naturalWidth;
                this.originalHeight = img.naturalHeight;
                this.originalSize  = 0;
                this.originalMime  = '';
                this.file          = null;
                this.render();
            };
            img.onerror = () => {
                this.error = 'Could not load image from URL. The server may block cross-origin requests. Try downloading the image and uploading it directly.';
            };
            img.src = url;
        },

        /* ── Style presets ── */
        applyPreset(name) {
            const P = {
                classic: { fontFamily:'Impact',      fontSize:8,  uppercase:true,  bold:false, italic:false, textColor:'#ffffff', strokeColor:'#000000', strokeWidth:4, textAlign:'center' },
                modern:  { fontFamily:'Arial Black',  fontSize:7,  uppercase:false, bold:true,  italic:false, textColor:'#ffffff', strokeColor:'#111111', strokeWidth:3, textAlign:'center' },
                dark:    { fontFamily:'Arial Black',  fontSize:7,  uppercase:false, bold:true,  italic:false, textColor:'#000000', strokeColor:'#ffffff', strokeWidth:3, textAlign:'center' },
                clean:   { fontFamily:'Verdana',      fontSize:5.5,uppercase:false, bold:true,  italic:false, textColor:'#ffffff', strokeColor:'#000000', strokeWidth:2, textAlign:'center' },
            };
            if (P[name]) { Object.assign(this, P[name]); this.activePreset = name; }
        },

        /* ── Core renderer ── */
        render() {
            if (!this._img) return;
            const canvas = this.$refs.canvas;
            if (!canvas) return;

            const img = this._img;
            canvas.width  = img.naturalWidth;
            canvas.height = img.naturalHeight;
            const ctx = canvas.getContext('2d');

            /* 1. Base image */
            ctx.drawImage(img, 0, 0);

            /* 2. Compute font metrics */
            const fontSize = Math.max(12, Math.round((this.fontSize / 100) * img.naturalWidth));
            const weight   = (this.bold || this.fontFamily === 'Impact') ? 'bold ' : '';
            const style    = this.italic ? 'italic ' : '';
            ctx.font         = `${style}${weight}${fontSize}px "${this.fontFamily}", Impact, "Arial Black", sans-serif`;
            ctx.textAlign    = this.textAlign;
            ctx.textBaseline = 'top';
            ctx.lineJoin     = 'round';
            ctx.miterLimit   = 2;

            const maxW  = img.naturalWidth  * 0.92;
            const padX  = img.naturalWidth  * 0.04;
            const padY  = img.naturalHeight * 0.025;
            const lineH = fontSize * 1.25;

            const x = this.textAlign === 'left'  ? padX
                     : this.textAlign === 'right' ? img.naturalWidth - padX
                     : img.naturalWidth / 2;

            /* 3. Top text (anchored to top) */
            if (this.topText.trim()) {
                const t     = this.uppercase ? this.topText.toUpperCase() : this.topText;
                const lines = this._wrap(ctx, t, maxW);
                let y = padY;
                for (const line of lines) {
                    this._drawLine(ctx, line, x, y, fontSize, img.naturalWidth);
                    y += lineH;
                }
            }

            /* 4. Bottom text (anchored to bottom) */
            if (this.bottomText.trim()) {
                const t     = this.uppercase ? this.bottomText.toUpperCase() : this.bottomText;
                const lines = this._wrap(ctx, t, maxW);
                /* Start y so the last line sits just above padY from the bottom */
                let y = img.naturalHeight - padY - lines.length * lineH;
                for (const line of lines) {
                    this._drawLine(ctx, line, x, y, fontSize, img.naturalWidth);
                    y += lineH;
                }
            }
        },

        /* Draw one text line: stroke first (outline), then fill (text) */
        _drawLine(ctx, text, x, y, fontSize, canvasWidth) {
            if (this.strokeWidth > 0) {
                /* Scale stroke relative to a 600 px reference so it looks consistent */
                ctx.lineWidth   = Math.max(1, this.strokeWidth * (canvasWidth / 600));
                ctx.strokeStyle = this.strokeColor;
                ctx.strokeText(text, x, y);
            }
            ctx.fillStyle = this.textColor;
            ctx.fillText(text, x, y);
        },

        /* Word-wrap text to fit within maxWidth pixels */
        _wrap(ctx, text, maxWidth) {
            if (!text.trim()) return [];
            const words = text.split(' ');
            const lines = [];
            let line = '';
            for (const word of words) {
                const test = line ? line + ' ' + word : word;
                if (line && ctx.measureText(test).width > maxWidth) {
                    lines.push(line);
                    line = word;
                } else {
                    line = test;
                }
            }
            if (line) lines.push(line);
            return lines;
        },

        /* ── Download ── */
        download() {
            const canvas = this.$refs.canvas;
            if (!canvas || !this._img) {
                this.error = 'Please add an image first.';
                return;
            }
            const mime = this.downloadFormat === 'jpeg' ? 'image/jpeg' : 'image/png';
            const q    = this.downloadFormat === 'jpeg' ? 0.95 : undefined;
            const ext  = this.downloadFormat === 'jpeg' ? 'jpg'  : 'png';
            const base = this.file ? this.file.name.replace(/\.[^.]+$/, '') : 'meme';
            const a    = document.createElement('a');
            a.href     = canvas.toDataURL(mime, q);
            a.download = `${base}_meme.${ext}`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        },

        /* ── Reset ── */
        reset() {
            this.file          = null;
            this.originalWidth  = 0;
            this.originalHeight = 0;
            this.originalSize  = 0;
            this.originalMime  = '';
            this.imageUrl      = '';
            this.topText       = 'TOP TEXT';
            this.bottomText    = 'BOTTOM TEXT';
            this.error         = '';
            this._img          = null;
            this.activePreset  = 'classic';
            clearTimeout(this._timer);

            const canvas = this.$refs.canvas;
            if (canvas) { canvas.width = 0; canvas.height = 0; }
            if (this.$refs.fileInput) this.$refs.fileInput.value = '';
        },

        /* ── Utilities ── */
        formatBytes(bytes) {
            if (!bytes || bytes <= 0) return '–';
            if (bytes < 1024)        return bytes + ' B';
            if (bytes < 1048576)     return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / 1048576).toFixed(2) + ' MB';
        },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\meme-generator.blade.php ENDPATH**/ ?>