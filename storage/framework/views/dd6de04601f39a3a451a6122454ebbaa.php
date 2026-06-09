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

    
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-10" x-data="watermarkTool()">

        
        <div x-show="error" x-transition
             class="alert alert-error mb-5 flex items-start gap-2">
            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span x-text="error"></span>
        </div>

        
        <div x-show="!hasFile" x-transition>
            <div
                class="border-2 border-dashed rounded-2xl p-14 sm:p-20 text-center cursor-pointer transition-all duration-200 select-none"
                :class="dragging ? 'border-brand-400 bg-brand-50' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50'"
                @dragover.prevent="dragging = true"
                @dragleave.prevent="dragging = false"
                @drop.prevent="onDrop($event)"
                @click="$refs.fileInput.click()"
            >
                <div class="flex flex-col items-center gap-4 pointer-events-none">
                    <div class="w-20 h-20 rounded-2xl bg-brand-50 flex items-center justify-center text-4xl shadow-sm">
                        💧
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-700">
                            <span x-show="!dragging">Drop your image here</span>
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
                    <p class="text-xs text-gray-400">Max file size: 50 MB</p>
                </div>
            </div>
            <input type="file" x-ref="fileInput" @change="onFileInput($event)"
                   accept="image/jpeg,image/jpg,image/png,image/webp,image/gif,image/bmp,image/x-bmp"
                   class="hidden">
        </div>

        
        <div x-show="hasFile" x-transition>

            
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 items-start">

                
                <div class="lg:col-span-2 space-y-4">

                    
                    <div class="card p-4 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 rounded-lg bg-brand-50 flex items-center justify-center text-base flex-shrink-0">🖼️</div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate" x-text="file ? file.name : ''"></p>
                                <p class="text-xs text-gray-400 mt-0.5"
                                   x-text="mimeLabel(originalMime) + (originalWidth ? ' · ' + originalWidth + '×' + originalHeight + ' px' : '') + ' · ' + formatBytes(originalSize)"></p>
                            </div>
                        </div>
                        <button @click="reset()" class="btn btn-secondary btn-sm flex-shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            Change
                        </button>
                    </div>

                    
                    <div class="card p-4">
                        <label class="form-label mb-2">Watermark Type</label>
                        <div class="flex bg-gray-100 rounded-xl p-1 gap-0.5">
                            <button type="button" @click="watermarkType = 'text'"
                                class="flex-1 py-2 rounded-lg text-sm font-medium transition-all duration-150 flex items-center justify-center gap-1.5"
                                :class="watermarkType === 'text' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
                                📝 Text
                            </button>
                            <button type="button" @click="watermarkType = 'logo'"
                                class="flex-1 py-2 rounded-lg text-sm font-medium transition-all duration-150 flex items-center justify-center gap-1.5"
                                :class="watermarkType === 'logo' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
                                🖼 Logo
                            </button>
                            <button type="button" @click="watermarkType = 'both'"
                                class="flex-1 py-2 rounded-lg text-sm font-medium transition-all duration-150 flex items-center justify-center gap-1.5"
                                :class="watermarkType === 'both' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
                                ✨ Both
                            </button>
                        </div>
                    </div>

                    
                    <div x-show="showText" x-transition class="card p-5 space-y-4">
                        <h3 class="text-sm font-semibold text-gray-700">Text Watermark</h3>

                        
                        <div>
                            <label class="form-label">Watermark Text</label>
                            <input type="text" x-model="textContent"
                                   @input="scheduleRender()"
                                   placeholder="© Your Name"
                                   maxlength="120"
                                   class="form-input">
                        </div>

                        
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="form-label">Font</label>
                                <select x-model="textFont" @change="scheduleRender()" class="form-input">
                                    <option value="Arial">Arial</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Times New Roman">Times New Roman</option>
                                    <option value="Courier New">Courier New</option>
                                    <option value="Verdana">Verdana</option>
                                    <option value="Impact">Impact</option>
                                    <option value="Trebuchet MS">Trebuchet MS</option>
                                    <option value="Comic Sans MS">Comic Sans MS</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Size <span class="text-gray-400 font-normal" x-text="'(' + textSize + '% width)'"></span></label>
                                <input type="range" x-model.number="textSize" @input="scheduleRender()"
                                       min="1" max="20" step="0.5"
                                       class="w-full h-2 rounded-full appearance-none cursor-pointer accent-brand-600 bg-gray-200 mt-3">
                            </div>
                        </div>

                        
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="form-label">Color</label>
                                <div class="relative">
                                    <div class="form-input flex items-center gap-2 cursor-pointer">
                                        <span class="w-5 h-5 rounded border border-gray-200 flex-shrink-0"
                                              :style="`background:${textColor}`"></span>
                                        <span class="font-mono text-xs uppercase" x-text="textColor"></span>
                                    </div>
                                    <input type="color" x-model="textColor" @input="scheduleRender()"
                                           class="absolute inset-0 opacity-0 cursor-pointer w-full h-full rounded-xl"
                                           title="Pick text color">
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Opacity <span class="text-brand-600 font-semibold" x-text="textOpacity + '%'"></span></label>
                                <input type="range" x-model.number="textOpacity" @input="scheduleRender()"
                                       min="10" max="100" step="5"
                                       class="w-full h-2 rounded-full appearance-none cursor-pointer accent-brand-600 bg-gray-200 mt-3">
                            </div>
                        </div>

                        
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="form-label mb-0">Rotation</label>
                                <span class="text-xs font-semibold text-brand-600" x-text="textRotation + '°'"></span>
                            </div>
                            <input type="range" x-model.number="textRotation" @input="scheduleRender()"
                                   min="-180" max="180" step="5"
                                   class="w-full h-2 rounded-full appearance-none cursor-pointer accent-brand-600 bg-gray-200">
                            <div class="flex justify-between text-xs text-gray-400 mt-1">
                                <span>−180°</span><span>0°</span><span>+180°</span>
                            </div>
                        </div>

                        
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <button type="button" role="switch" :aria-checked="textShadow"
                                    @click="textShadow = !textShadow; scheduleRender()"
                                    class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1"
                                    :class="textShadow ? 'bg-brand-600' : 'bg-gray-200'">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform"
                                          :class="textShadow ? 'translate-x-4' : 'translate-x-0'"></span>
                                </button>
                                <span class="text-xs text-gray-600">Shadow</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <button type="button" role="switch" :aria-checked="textTiled"
                                    @click="textTiled = !textTiled; scheduleRender()"
                                    class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1"
                                    :class="textTiled ? 'bg-brand-600' : 'bg-gray-200'">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform"
                                          :class="textTiled ? 'translate-x-4' : 'translate-x-0'"></span>
                                </button>
                                <span class="text-xs text-gray-600">Tile (repeat)</span>
                            </label>
                        </div>

                        
                        <div x-show="!textTiled" x-transition>
                            <label class="form-label mb-2">Position</label>
                            <div class="grid grid-cols-3 gap-1.5 p-2 bg-gray-100 rounded-xl" style="width:96px">
                                <button type="button" @click="textPosition='top-left'; scheduleRender()" title="Top Left"
                                        class="w-7 h-7 rounded-md transition-all focus:outline-none"
                                        :class="textPosition==='top-left' ? 'bg-brand-500 shadow-sm' : 'bg-gray-300 hover:bg-gray-400'"></button>
                                <button type="button" @click="textPosition='top-center'; scheduleRender()" title="Top Center"
                                        class="w-7 h-7 rounded-md transition-all focus:outline-none"
                                        :class="textPosition==='top-center' ? 'bg-brand-500 shadow-sm' : 'bg-gray-300 hover:bg-gray-400'"></button>
                                <button type="button" @click="textPosition='top-right'; scheduleRender()" title="Top Right"
                                        class="w-7 h-7 rounded-md transition-all focus:outline-none"
                                        :class="textPosition==='top-right' ? 'bg-brand-500 shadow-sm' : 'bg-gray-300 hover:bg-gray-400'"></button>
                                <button type="button" @click="textPosition='center-left'; scheduleRender()" title="Center Left"
                                        class="w-7 h-7 rounded-md transition-all focus:outline-none"
                                        :class="textPosition==='center-left' ? 'bg-brand-500 shadow-sm' : 'bg-gray-300 hover:bg-gray-400'"></button>
                                <button type="button" @click="textPosition='center'; scheduleRender()" title="Center"
                                        class="w-7 h-7 rounded-md transition-all focus:outline-none"
                                        :class="textPosition==='center' ? 'bg-brand-500 shadow-sm' : 'bg-gray-300 hover:bg-gray-400'"></button>
                                <button type="button" @click="textPosition='center-right'; scheduleRender()" title="Center Right"
                                        class="w-7 h-7 rounded-md transition-all focus:outline-none"
                                        :class="textPosition==='center-right' ? 'bg-brand-500 shadow-sm' : 'bg-gray-300 hover:bg-gray-400'"></button>
                                <button type="button" @click="textPosition='bottom-left'; scheduleRender()" title="Bottom Left"
                                        class="w-7 h-7 rounded-md transition-all focus:outline-none"
                                        :class="textPosition==='bottom-left' ? 'bg-brand-500 shadow-sm' : 'bg-gray-300 hover:bg-gray-400'"></button>
                                <button type="button" @click="textPosition='bottom-center'; scheduleRender()" title="Bottom Center"
                                        class="w-7 h-7 rounded-md transition-all focus:outline-none"
                                        :class="textPosition==='bottom-center' ? 'bg-brand-500 shadow-sm' : 'bg-gray-300 hover:bg-gray-400'"></button>
                                <button type="button" @click="textPosition='bottom-right'; scheduleRender()" title="Bottom Right"
                                        class="w-7 h-7 rounded-md transition-all focus:outline-none"
                                        :class="textPosition==='bottom-right' ? 'bg-brand-500 shadow-sm' : 'bg-gray-300 hover:bg-gray-400'"></button>
                            </div>
                        </div>
                        <div x-show="textTiled" class="text-xs text-gray-400 italic">
                            Position disabled — tiled mode fills the entire image.
                        </div>
                    </div>

                    
                    <div x-show="showLogo" x-transition class="card p-5 space-y-4">
                        <h3 class="text-sm font-semibold text-gray-700">Logo / Image Watermark</h3>

                        
                        <div>
                            <label class="form-label">Logo Image <span class="font-normal text-gray-400">(PNG with transparency recommended)</span></label>

                            
                            <div x-show="!hasLogo"
                                 class="border-2 border-dashed rounded-xl p-5 text-center cursor-pointer transition-all duration-150"
                                 :class="logoDragging ? 'border-brand-400 bg-brand-50' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50'"
                                 @dragover.prevent="logoDragging = true"
                                 @dragleave.prevent="logoDragging = false"
                                 @drop.prevent="onLogoDrop($event)"
                                 @click="$refs.logoInput.click()">
                                <div class="text-2xl mb-1">🖼️</div>
                                <p class="text-xs text-gray-500">Drop logo or <span class="text-brand-600 font-medium">browse</span></p>
                                <p class="text-xs text-gray-400 mt-0.5">Max 5 MB</p>
                            </div>

                            
                            <div x-show="hasLogo"
                                 class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                                <img :src="logoSrc" class="w-12 h-12 object-contain rounded-lg bg-white border border-gray-100 flex-shrink-0" alt="Logo">
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium text-gray-800 truncate" x-text="logoFile ? logoFile.name : ''"></p>
                                    <p class="text-xs text-gray-400" x-text="logoFile ? formatBytes(logoFile.size) : ''"></p>
                                </div>
                                <button @click="removeLogo()" class="text-gray-400 hover:text-red-500 transition-colors flex-shrink-0" title="Remove logo">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <input type="file" x-ref="logoInput" @change="onLogoInput($event)"
                                   accept="image/jpeg,image/jpg,image/png,image/webp,image/gif"
                                   class="hidden">
                        </div>

                        
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="form-label">Size <span class="text-brand-600 font-semibold" x-text="logoSize + '%'"></span></label>
                                <input type="range" x-model.number="logoSize" @input="scheduleRender()"
                                       min="5" max="80" step="1"
                                       class="w-full h-2 rounded-full appearance-none cursor-pointer accent-brand-600 bg-gray-200 mt-2">
                                <div class="flex justify-between text-xs text-gray-400 mt-1">
                                    <span>5%</span><span>80%</span>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Opacity <span class="text-brand-600 font-semibold" x-text="logoOpacity + '%'"></span></label>
                                <input type="range" x-model.number="logoOpacity" @input="scheduleRender()"
                                       min="10" max="100" step="5"
                                       class="w-full h-2 rounded-full appearance-none cursor-pointer accent-brand-600 bg-gray-200 mt-2">
                                <div class="flex justify-between text-xs text-gray-400 mt-1">
                                    <span>10%</span><span>100%</span>
                                </div>
                            </div>
                        </div>

                        
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="form-label mb-0">Rotation</label>
                                <span class="text-xs font-semibold text-brand-600" x-text="logoRotation + '°'"></span>
                            </div>
                            <input type="range" x-model.number="logoRotation" @input="scheduleRender()"
                                   min="-180" max="180" step="5"
                                   class="w-full h-2 rounded-full appearance-none cursor-pointer accent-brand-600 bg-gray-200">
                        </div>

                        
                        <div>
                            <label class="form-label mb-2">Position</label>
                            <div class="grid grid-cols-3 gap-1.5 p-2 bg-gray-100 rounded-xl" style="width:96px">
                                <button type="button" @click="logoPosition='top-left'; scheduleRender()" title="Top Left"
                                        class="w-7 h-7 rounded-md transition-all focus:outline-none"
                                        :class="logoPosition==='top-left' ? 'bg-brand-500 shadow-sm' : 'bg-gray-300 hover:bg-gray-400'"></button>
                                <button type="button" @click="logoPosition='top-center'; scheduleRender()" title="Top Center"
                                        class="w-7 h-7 rounded-md transition-all focus:outline-none"
                                        :class="logoPosition==='top-center' ? 'bg-brand-500 shadow-sm' : 'bg-gray-300 hover:bg-gray-400'"></button>
                                <button type="button" @click="logoPosition='top-right'; scheduleRender()" title="Top Right"
                                        class="w-7 h-7 rounded-md transition-all focus:outline-none"
                                        :class="logoPosition==='top-right' ? 'bg-brand-500 shadow-sm' : 'bg-gray-300 hover:bg-gray-400'"></button>
                                <button type="button" @click="logoPosition='center-left'; scheduleRender()" title="Center Left"
                                        class="w-7 h-7 rounded-md transition-all focus:outline-none"
                                        :class="logoPosition==='center-left' ? 'bg-brand-500 shadow-sm' : 'bg-gray-300 hover:bg-gray-400'"></button>
                                <button type="button" @click="logoPosition='center'; scheduleRender()" title="Center"
                                        class="w-7 h-7 rounded-md transition-all focus:outline-none"
                                        :class="logoPosition==='center' ? 'bg-brand-500 shadow-sm' : 'bg-gray-300 hover:bg-gray-400'"></button>
                                <button type="button" @click="logoPosition='center-right'; scheduleRender()" title="Center Right"
                                        class="w-7 h-7 rounded-md transition-all focus:outline-none"
                                        :class="logoPosition==='center-right' ? 'bg-brand-500 shadow-sm' : 'bg-gray-300 hover:bg-gray-400'"></button>
                                <button type="button" @click="logoPosition='bottom-left'; scheduleRender()" title="Bottom Left"
                                        class="w-7 h-7 rounded-md transition-all focus:outline-none"
                                        :class="logoPosition==='bottom-left' ? 'bg-brand-500 shadow-sm' : 'bg-gray-300 hover:bg-gray-400'"></button>
                                <button type="button" @click="logoPosition='bottom-center'; scheduleRender()" title="Bottom Center"
                                        class="w-7 h-7 rounded-md transition-all focus:outline-none"
                                        :class="logoPosition==='bottom-center' ? 'bg-brand-500 shadow-sm' : 'bg-gray-300 hover:bg-gray-400'"></button>
                                <button type="button" @click="logoPosition='bottom-right'; scheduleRender()" title="Bottom Right"
                                        class="w-7 h-7 rounded-md transition-all focus:outline-none"
                                        :class="logoPosition==='bottom-right' ? 'bg-brand-500 shadow-sm' : 'bg-gray-300 hover:bg-gray-400'"></button>
                            </div>
                        </div>
                    </div>

                    
                    <div class="card p-5 space-y-4">
                        <h3 class="text-sm font-semibold text-gray-700">Output Settings</h3>

                        
                        <div>
                            <label class="form-label">Output Format</label>
                            <div class="inline-flex bg-gray-100 rounded-xl p-1 gap-0.5 w-full">
                                <button type="button" @click="outputFormat = 'jpeg'"
                                    class="flex-1 px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-150"
                                    :class="outputFormat === 'jpeg' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
                                    JPEG
                                </button>
                                <button type="button" @click="outputFormat = 'png'"
                                    class="flex-1 px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-150"
                                    :class="outputFormat === 'png' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
                                    PNG
                                </button>
                                <button type="button" @click="outputFormat = 'webp'"
                                    class="flex-1 px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-150"
                                    :class="outputFormat === 'webp' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
                                    WebP
                                </button>
                            </div>
                        </div>

                        
                        <div x-show="outputFormat !== 'png'">
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="form-label mb-0">Quality</label>
                                <span class="text-sm font-semibold text-brand-600" x-text="outputQuality + '%'"></span>
                            </div>
                            <input type="range" x-model.number="outputQuality"
                                   min="1" max="100"
                                   class="w-full h-2 rounded-full appearance-none cursor-pointer accent-brand-600 bg-gray-200">
                        </div>
                    </div>

                    
                    <div class="flex flex-wrap gap-3">
                        <button @click="download()" :disabled="!previewSrc || loading" class="btn btn-success flex-1">
                            <svg x-show="loading" class="spinner w-4 h-4" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                            </svg>
                            <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            <span x-text="loading ? 'Exporting…' : 'Download Watermarked'"></span>
                        </button>
                        <button @click="reset()" class="btn btn-secondary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Clear
                        </button>
                    </div>

                </div>

                
                <div class="lg:col-span-3">
                    <div class="card overflow-hidden sticky top-6">

                        
                        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-700">Live Preview</span>
                            <div class="flex items-center gap-2">
                                <div x-show="loading" class="flex items-center gap-1.5 text-xs text-brand-600">
                                    <div class="spinner w-3.5 h-3.5 border-brand-400"></div>
                                    <span>Rendering…</span>
                                </div>
                                <span x-show="!loading && previewSrc" class="badge badge-success">Ready</span>
                                <span x-show="!loading && !previewSrc" class="badge badge-gray">Waiting</span>
                            </div>
                        </div>

                        
                        <div class="relative flex items-center justify-center bg-gray-100 overflow-hidden"
                             style="min-height:320px;background-image:url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20'%3E%3Crect width='10' height='10' fill='%23e5e7eb'/%3E%3Crect x='10' y='10' width='10' height='10' fill='%23e5e7eb'/%3E%3Crect x='10' width='10' height='10' fill='%23d1d5db'/%3E%3Crect y='10' width='10' height='10' fill='%23d1d5db'/%3E%3C/svg%3E&quot;)">

                            
                            <img
                                x-show="previewSrc"
                                :src="previewSrc"
                                x-transition
                                class="max-w-full max-h-full object-contain"
                                style="max-height: 520px; display: block;"
                                alt="Watermark preview"
                            >

                            
                            <div x-show="!previewSrc && !loading" class="text-gray-300 text-center p-10">
                                <svg class="w-16 h-16 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-sm">Loading image…</p>
                            </div>

                            
                            <div x-show="loading && previewSrc"
                                 class="absolute inset-0 bg-white/50 flex items-center justify-center">
                                <div class="spinner w-7 h-7 border-brand-500 border-2"></div>
                            </div>
                        </div>

                        
                        <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400">
                            <span>Preview at reduced resolution · Download exports full quality</span>
                            <span x-text="originalWidth ? originalWidth + '×' + originalHeight : ''"></span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        
        <div class="card p-6 mt-10">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">About Watermark Tool</h3>
            <ul class="space-y-2 text-sm text-gray-500">
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    Add text or logo watermarks to any image with full control over position, opacity, size, and rotation.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    <strong class="text-gray-700 font-medium">Tile mode</strong> repeats the text across the entire image for a professional watermark pattern.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    Use a PNG logo with a transparent background for the best overlay results.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    Download exports at the original image's full resolution — the preview is a scaled-down version for speed.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">🔒</span>
                    All processing happens entirely in your browser using the Canvas API — your images are never uploaded to any server.
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
 * Watermark Tool — pure browser-side via HTML5 Canvas API.
 * Supports text watermark (with tiling, rotation, shadow) and
 * logo/image watermark (PNG with transparency recommended).
 * Nothing is uploaded; all processing happens locally.
 */
function watermarkTool() {
    return {
        /* ── Base image ── */
        file:           null,
        originalSrc:    '',
        originalWidth:  0,
        originalHeight: 0,
        originalSize:   0,
        originalMime:   '',

        /* ── Watermark type ── */
        watermarkType: 'text',   // 'text' | 'logo' | 'both'

        /* ── Text watermark ── */
        textContent:  '© Watermark',
        textFont:     'Arial',
        textSize:     5,          // % of image width
        textColor:    '#ffffff',
        textOpacity:  70,
        textRotation: -30,
        textPosition: 'center',
        textTiled:    false,
        textShadow:   true,

        /* ── Logo watermark ── */
        logoFile:     null,
        logoSrc:      '',
        logoSize:     20,         // % of image width
        logoOpacity:  70,
        logoRotation: 0,
        logoPosition: 'bottom-right',
        logoDragging: false,

        /* ── Output ── */
        outputFormat:  'jpeg',
        outputQuality: 90,

        /* ── UI state ── */
        dragging:   false,
        previewSrc: '',
        loading:    false,
        error:      '',
        _img:       null,       // cached base Image element (non-reactive)
        _logoImg:   null,       // cached logo Image element (non-reactive)
        _timer:     null,

        /* ── Computed ── */
        get hasFile()   { return !!this.file; },
        get hasLogo()   { return !!this.logoFile; },
        get showText()  { return this.watermarkType === 'text'  || this.watermarkType === 'both'; },
        get showLogo()  { return this.watermarkType === 'logo'  || this.watermarkType === 'both'; },

        /* ── Lifecycle: watch all settings for live preview ── */
        init() {
            const re = () => this.scheduleRender();
            [
                'textContent','textFont','textSize','textColor','textOpacity',
                'textRotation','textPosition','textTiled','textShadow',
                'watermarkType',
                'logoSize','logoOpacity','logoRotation','logoPosition',
            ].forEach(prop => this.$watch(prop, re));
        },

        scheduleRender() {
            if (!this._img) return;
            clearTimeout(this._timer);
            this._timer = setTimeout(() => this.render(false), 150);
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
            if (file.size > 50 * 1024 * 1024) {
                this.error = 'File too large. Maximum supported size is 50 MB.';
                return;
            }
            this.error         = '';
            this.file          = file;
            this.originalSize  = file.size;
            this.originalMime  = file.type;
            this.previewSrc    = '';
            this._img          = null;

            /* Keep PNG transparency in output when input is PNG */
            if (file.type === 'image/png') this.outputFormat = 'png';
            else                           this.outputFormat = 'jpeg';

            const reader = new FileReader();
            reader.onload = ev => {
                this.originalSrc = ev.target.result;
                const img = new Image();
                img.onload = () => {
                    this._img          = img;
                    this.originalWidth  = img.naturalWidth;
                    this.originalHeight = img.naturalHeight;
                    this.render(false);
                };
                img.onerror = () => { this.error = 'Could not decode the image. The file may be corrupted.'; };
                img.src = this.originalSrc;
            };
            reader.onerror = () => { this.error = 'Could not read the file.'; };
            reader.readAsDataURL(file);
        },

        onLogoInput(e) { const f = e.target.files[0]; if (f) this.loadLogo(f); },
        onLogoDrop(e)  { this.logoDragging = false; const f = e.dataTransfer.files[0]; if (f) this.loadLogo(f); },

        loadLogo(file) {
            const ALLOWED = ['image/jpeg','image/jpg','image/png','image/webp','image/gif'];
            if (!ALLOWED.includes(file.type)) {
                this.error = 'Logo must be JPG, PNG, WebP, or GIF.';
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                this.error = 'Logo too large. Maximum is 5 MB.';
                return;
            }
            this.logoFile = file;
            const reader  = new FileReader();
            reader.onload = ev => {
                this.logoSrc = ev.target.result;
                const img = new Image();
                img.onload = () => {
                    this._logoImg = img;
                    this.scheduleRender();
                };
                img.src = this.logoSrc;
            };
            reader.readAsDataURL(file);
        },

        removeLogo() {
            this.logoFile = null;
            this.logoSrc  = '';
            this._logoImg = null;
            if (this.$refs.logoInput) this.$refs.logoInput.value = '';
            this.scheduleRender();
        },

        /* ── Core Canvas renderer ──────────────────────────────────────────
         *  fullRes = false → preview at ≤900 px wide, returns nothing
         *  fullRes = true  → full original resolution, returns a Blob
         * ─────────────────────────────────────────────────────────────────*/
        async render(fullRes) {
            if (!this._img) return;

            this.loading = true;
            if (!fullRes) await new Promise(r => setTimeout(r, 10)); // let spinner show

            try {
                const img = this._img;
                let w, h;

                if (fullRes) {
                    w = img.naturalWidth;
                    h = img.naturalHeight;
                } else {
                    const MAX_W = 900;
                    const scale = img.naturalWidth > MAX_W ? MAX_W / img.naturalWidth : 1;
                    w = Math.round(img.naturalWidth  * scale);
                    h = Math.round(img.naturalHeight * scale);
                }

                const canvas = document.createElement('canvas');
                canvas.width  = w;
                canvas.height = h;
                const ctx = canvas.getContext('2d');

                /* 1. Draw the base image */
                ctx.drawImage(img, 0, 0, w, h);

                /* Scale factor: UI values are in original-image space */
                const sf = w / img.naturalWidth;

                /* 2. Text watermark */
                if (this.showText && this.textContent.trim()) {
                    this._drawText(ctx, w, h, sf);
                }

                /* 3. Logo watermark */
                if (this.showLogo && this._logoImg) {
                    this._drawLogo(ctx, w, h, sf);
                }

                /* Return blob for download, or update preview src */
                if (fullRes) {
                    const mime = 'image/' + this.outputFormat;
                    const q    = this.outputFormat === 'png' ? undefined : this.outputQuality / 100;
                    return new Promise(resolve => canvas.toBlob(resolve, mime, q));
                } else {
                    this.previewSrc = canvas.toDataURL('image/jpeg', 0.88);
                }

            } catch (e) {
                this.error = 'Render error: ' + e.message;
            } finally {
                this.loading = false;
            }
        },

        /* ── Text drawing ── */
        _drawText(ctx, w, h, sf) {
            /* Font size as % of canvas width */
            const fontSize = Math.max(8, Math.round((this.textSize / 100) * w));
            const margin   = Math.round(20 * sf);
            const angle    = this.textRotation * Math.PI / 180;

            ctx.save();
            ctx.font         = `bold ${fontSize}px "${this.textFont}", sans-serif`;
            ctx.fillStyle    = this.textColor;
            ctx.globalAlpha  = this.textOpacity / 100;
            ctx.textBaseline = 'middle';

            if (this.textShadow) {
                ctx.shadowColor   = 'rgba(0,0,0,0.55)';
                ctx.shadowBlur    = Math.max(3, fontSize * 0.15);
                ctx.shadowOffsetX = Math.round(fontSize * 0.04);
                ctx.shadowOffsetY = Math.round(fontSize * 0.04);
            }

            const textW = ctx.measureText(this.textContent).width;
            const textH = fontSize * 1.25;

            if (this.textTiled) {
                /* Tile across entire canvas; start outside bounds to cover rotated corners */
                const stepX  = textW  * 2.2;
                const stepY  = textH  * 3.8;
                const maxDim = Math.max(w, h) * 1.5;

                for (let y = -maxDim; y < maxDim + h; y += stepY) {
                    for (let x = -maxDim; x < maxDim + w; x += stepX) {
                        ctx.save();
                        ctx.translate(x + textW / 2, y);
                        ctx.rotate(angle);
                        ctx.fillText(this.textContent, -textW / 2, 0);
                        ctx.restore();
                    }
                }
            } else {
                const pos = this._calcPos(w, h, textW, textH, this.textPosition, margin);
                ctx.translate(pos.cx, pos.cy);
                ctx.rotate(angle);
                ctx.fillText(this.textContent, -textW / 2, 0);
            }

            ctx.restore();
        },

        /* ── Logo drawing ── */
        _drawLogo(ctx, w, h, sf) {
            const logoW  = Math.round((this.logoSize / 100) * w);
            const ratio  = this._logoImg.naturalHeight / this._logoImg.naturalWidth;
            const logoH  = Math.round(logoW * ratio);
            const margin = Math.round(20 * sf);
            const angle  = this.logoRotation * Math.PI / 180;

            ctx.save();
            ctx.globalAlpha = this.logoOpacity / 100;

            const pos = this._calcPos(w, h, logoW, logoH, this.logoPosition, margin);
            ctx.translate(pos.cx, pos.cy);
            ctx.rotate(angle);
            ctx.drawImage(this._logoImg, -logoW / 2, -logoH / 2, logoW, logoH);

            ctx.restore();
        },

        /* Returns the center-point {cx, cy} for a watermark element */
        _calcPos(cw, ch, elemW, elemH, position, margin) {
            const hw = elemW / 2, hh = elemH / 2;
            const map = {
                'top-left':      { cx: margin + hw,          cy: margin + hh },
                'top-center':    { cx: cw / 2,               cy: margin + hh },
                'top-right':     { cx: cw - margin - hw,     cy: margin + hh },
                'center-left':   { cx: margin + hw,          cy: ch / 2 },
                'center':        { cx: cw / 2,               cy: ch / 2 },
                'center-right':  { cx: cw - margin - hw,     cy: ch / 2 },
                'bottom-left':   { cx: margin + hw,          cy: ch - margin - hh },
                'bottom-center': { cx: cw / 2,               cy: ch - margin - hh },
                'bottom-right':  { cx: cw - margin - hw,     cy: ch - margin - hh },
            };
            return map[position] || map['center'];
        },

        /* ── Download at full resolution ── */
        async download() {
            if (!this._img) return;
            this.error   = '';
            this.loading = true;
            try {
                const blob = await this.render(true);
                if (!blob) throw new Error('Failed to generate the output image.');

                const ext      = this.outputFormat === 'jpeg' ? 'jpg' : this.outputFormat;
                const baseName = this.file ? this.file.name.replace(/\.[^.]+$/, '') : 'image';
                const url      = URL.createObjectURL(blob);
                const a        = document.createElement('a');
                a.href         = url;
                a.download     = `${baseName}_watermarked.${ext}`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            } catch (e) {
                this.error = 'Download failed: ' + e.message;
            } finally {
                this.loading = false;
            }
        },

        /* ── Reset ── */
        reset() {
            this.file          = null;
            this.originalSrc   = '';
            this.originalWidth  = 0;
            this.originalHeight = 0;
            this.originalSize  = 0;
            this.originalMime  = '';
            this.previewSrc    = '';
            this.error         = '';
            this.loading       = false;
            this._img          = null;
            clearTimeout(this._timer);
            if (this.$refs.fileInput) this.$refs.fileInput.value = '';
            /* Keep watermark settings — user may want to apply same watermark to next image */
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
                'image/jpeg':'JPEG','image/jpg':'JPEG','image/png':'PNG',
                'image/webp':'WebP','image/gif':'GIF',
                'image/bmp':'BMP','image/x-bmp':'BMP','image/x-ms-bmp':'BMP',
            };
            return map[mime] || (mime ? mime.replace('image/','').toUpperCase() : '?');
        },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\watermark-tool.blade.php ENDPATH**/ ?>