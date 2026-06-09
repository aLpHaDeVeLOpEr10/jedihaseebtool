<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ── Shared slider base ── */
.cp-range {
    -webkit-appearance: none;
    appearance: none;
    width: 100%;
    height: 14px;
    border-radius: 7px;
    outline: none;
    cursor: pointer;
    border: none;
    display: block;
}
.cp-range::-webkit-slider-runnable-track {
    height: 14px;
    border-radius: 7px;
}
.cp-range::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 22px;
    height: 22px;
    margin-top: -4px;
    border-radius: 50%;
    background: #ffffff;
    border: 2px solid rgba(0,0,0,0.22);
    box-shadow: 0 1px 4px rgba(0,0,0,0.28);
    cursor: grab;
}
.cp-range:active::-webkit-slider-thumb { cursor: grabbing; }
.cp-range::-moz-range-track { height: 14px; border-radius: 7px; }
.cp-range::-moz-range-thumb {
    width: 22px; height: 22px;
    border-radius: 50%;
    background: #ffffff;
    border: 2px solid rgba(0,0,0,0.22);
    box-shadow: 0 1px 4px rgba(0,0,0,0.28);
    cursor: grab;
}

/* ── Alpha-track range (transparent track so checkerboard shows) ── */
.cp-alpha {
    -webkit-appearance: none;
    appearance: none;
    width: 100%;
    height: 100%;
    background: transparent !important;
    border: none;
    outline: none;
    cursor: pointer;
    display: block;
}
.cp-alpha::-webkit-slider-runnable-track { background: transparent; height: 14px; border-radius: 7px; }
.cp-alpha::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 22px; height: 22px;
    margin-top: -4px;
    border-radius: 50%;
    background: #fff;
    border: 2px solid rgba(0,0,0,0.22);
    box-shadow: 0 1px 4px rgba(0,0,0,0.28);
    cursor: grab;
}
.cp-alpha::-moz-range-track { background: transparent; height: 14px; border-radius: 7px; }
.cp-alpha::-moz-range-thumb {
    width: 22px; height: 22px; border-radius: 50%;
    background: #fff;
    border: 2px solid rgba(0,0,0,0.22);
    box-shadow: 0 1px 4px rgba(0,0,0,0.28);
}

/* ── Checkerboard (transparency indicator) ── */
.checker {
    background-image: linear-gradient(45deg,#ccc 25%,transparent 25%),
                      linear-gradient(-45deg,#ccc 25%,transparent 25%),
                      linear-gradient(45deg,transparent 75%,#ccc 75%),
                      linear-gradient(-45deg,transparent 75%,#ccc 75%);
    background-size: 8px 8px;
    background-position: 0 0, 0 4px, 4px -4px, -4px 0px;
}

/* ── Picker handle pulse on hover ── */
.picker-handle { transition: transform 0.05s ease; }
</style>

<div class="min-h-screen bg-gray-50">

    
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                <?php echo e($tool->icon); ?> <?php echo e($tool->name); ?>

            </h1>
            <p class="text-gray-500 mt-2"><?php echo e($tool->short_description); ?></p>
        </div>
    </div>

    
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8"
         x-data="colorPicker()"
         x-init="init()">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 items-start">

            
            <div class="space-y-4">

                
                <div class="card p-5">
                    <div class="relative w-full rounded-2xl overflow-hidden cursor-crosshair select-none"
                         style="aspect-ratio:1/1"
                         x-ref="sq"
                         @mousedown.prevent="startPick($event)"
                         @touchstart.prevent="startPickTouch($event)"
                         @touchmove.prevent="moveTouchPick($event)"
                         @touchend.prevent="endTouch()">

                        
                        <div class="absolute inset-0"
                             :style="'background:linear-gradient(to right,#fff,hsl('+h+',100%,50%))'">
                        </div>
                        
                        <div class="absolute inset-0"
                             style="background:linear-gradient(to bottom,rgba(0,0,0,0),#000)">
                        </div>
                        
                        <div class="picker-handle absolute w-5 h-5 rounded-full border-2 border-white shadow-lg pointer-events-none"
                             :style="'left:'+sat+'%;top:'+(100-val)+'%;transform:translate(-50%,-50%);background:'+hexColor">
                        </div>
                    </div>

                    
                    <div class="mt-4">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Hue</span>
                            <span class="font-mono text-xs text-gray-700" x-text="h+'°'"></span>
                        </div>
                        <input type="range" x-model.number="h" min="0" max="360" step="1"
                               @input="onPickerChange()"
                               class="cp-range"
                               style="background:linear-gradient(to right,#f00 0%,#ff0 17%,#0f0 33%,#0ff 50%,#00f 67%,#f0f 83%,#f00 100%)">
                    </div>

                    
                    <div class="mt-3">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Opacity</span>
                            <span class="font-mono text-xs text-gray-700" x-text="alpha+'%'"></span>
                        </div>
                        
                        <div class="relative rounded-full checker overflow-hidden" style="height:14px">
                            <div class="absolute inset-0 rounded-full"
                                 :style="'background:linear-gradient(to right,rgba('+rgb.r+','+rgb.g+','+rgb.b+',0),rgba('+rgb.r+','+rgb.g+','+rgb.b+',1))'">
                            </div>
                            <input type="range" x-model.number="alpha" min="0" max="100" step="1"
                                   @input="onPickerChange()"
                                   class="cp-alpha absolute inset-0">
                        </div>
                    </div>
                </div>

                
                <div class="card overflow-hidden">
                    <div class="flex h-16">
                        <button type="button"
                                class="flex-1 transition-all hover:opacity-80"
                                :style="'background:'+prevColor"
                                @click="restorePrev()"
                                title="Click to restore previous color">
                        </button>
                        <div class="flex-1" :style="'background:'+displayColor"></div>
                    </div>
                    <div class="flex text-xs divide-x divide-gray-100 border-t border-gray-100">
                        <div class="flex-1 py-2 text-center text-gray-400">Previous (click to restore)</div>
                        <div class="flex-1 py-2 text-center text-gray-600 font-medium">Current</div>
                    </div>
                </div>

                
                <div class="card p-4">
                    <div class="flex items-center gap-4">
                        <div class="relative flex-shrink-0 group" title="Open system color picker">
                            <input type="color"
                                   x-ref="native"
                                   @input="setFromNative($event.target.value)"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                   tabindex="-1">
                            <div class="w-14 h-14 rounded-2xl border-2 border-gray-200 shadow-sm cursor-pointer transition-all group-hover:scale-105 group-hover:shadow-md"
                                 :style="'background:'+hexColor">
                            </div>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-800">OS Color Picker</p>
                            <p class="text-xs text-gray-400 mt-0.5">Click the swatch to open your system color picker for advanced selection</p>
                        </div>
                    </div>
                </div>

            </div>

            
            <div class="space-y-4">

                
                <div class="card p-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Color Values</h3>
                    <div class="space-y-2.5">

                        
                        <div class="flex items-center gap-2.5 px-3 py-2.5 bg-gray-50 rounded-xl">
                            <div class="w-6 h-6 rounded-lg flex-shrink-0 border border-gray-200"
                                 :style="'background:'+hexColor"></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">HEX</p>
                                <p class="font-mono text-sm font-bold text-gray-900 truncate" x-text="hexStr"></p>
                            </div>
                            <button type="button" @click="copy(hexStr,'hex')"
                                    class="btn btn-secondary btn-sm flex-shrink-0 min-w-[72px]"
                                    :class="copiedKey==='hex' ? 'text-emerald-600' : ''">
                                <svg x-show="copiedKey==='hex'" class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span x-text="copiedKey==='hex' ? 'Copied!' : 'Copy'"></span>
                            </button>
                        </div>

                        
                        <div class="flex items-center gap-2.5 px-3 py-2.5 bg-gray-50 rounded-xl">
                            <div class="w-6 h-6 rounded-lg flex-shrink-0 border border-gray-200"
                                 :style="'background:'+hexColor"></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">RGB</p>
                                <p class="font-mono text-sm font-bold text-gray-900 truncate" x-text="rgbStr"></p>
                            </div>
                            <button type="button" @click="copy(rgbStr,'rgb')"
                                    class="btn btn-secondary btn-sm flex-shrink-0 min-w-[72px]"
                                    :class="copiedKey==='rgb' ? 'text-emerald-600' : ''">
                                <svg x-show="copiedKey==='rgb'" class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span x-text="copiedKey==='rgb' ? 'Copied!' : 'Copy'"></span>
                            </button>
                        </div>

                        
                        <div class="flex items-center gap-2.5 px-3 py-2.5 bg-gray-50 rounded-xl">
                            <div class="w-6 h-6 rounded-lg flex-shrink-0 border border-gray-200"
                                 :style="'background:'+hexColor"></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">HSL</p>
                                <p class="font-mono text-sm font-bold text-gray-900 truncate" x-text="hslStr"></p>
                            </div>
                            <button type="button" @click="copy(hslStr,'hsl')"
                                    class="btn btn-secondary btn-sm flex-shrink-0 min-w-[72px]"
                                    :class="copiedKey==='hsl' ? 'text-emerald-600' : ''">
                                <svg x-show="copiedKey==='hsl'" class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span x-text="copiedKey==='hsl' ? 'Copied!' : 'Copy'"></span>
                            </button>
                        </div>

                        
                        <div class="flex items-center gap-2.5 px-3 py-2.5 bg-gray-50 rounded-xl">
                            <div class="w-6 h-6 rounded-lg flex-shrink-0 border border-gray-200 checker overflow-hidden">
                                <div class="w-full h-full" :style="'background:'+displayColor"></div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">RGBA</p>
                                <p class="font-mono text-sm font-bold text-gray-900 truncate" x-text="rgbaStr"></p>
                            </div>
                            <button type="button" @click="copy(rgbaStr,'rgba')"
                                    class="btn btn-secondary btn-sm flex-shrink-0 min-w-[72px]"
                                    :class="copiedKey==='rgba' ? 'text-emerald-600' : ''">
                                <svg x-show="copiedKey==='rgba'" class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span x-text="copiedKey==='rgba' ? 'Copied!' : 'Copy'"></span>
                            </button>
                        </div>

                        
                        <div class="flex items-center gap-2.5 px-3 py-2.5 bg-gray-50 rounded-xl">
                            <div class="w-6 h-6 rounded-lg flex-shrink-0 border border-gray-200"
                                 :style="'background:'+hexColor"></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">HSV</p>
                                <p class="font-mono text-sm font-bold text-gray-900 truncate" x-text="hsvStr"></p>
                            </div>
                            <button type="button" @click="copy(hsvStr,'hsv')"
                                    class="btn btn-secondary btn-sm flex-shrink-0 min-w-[72px]"
                                    :class="copiedKey==='hsv' ? 'text-emerald-600' : ''">
                                <svg x-show="copiedKey==='hsv'" class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span x-text="copiedKey==='hsv' ? 'Copied!' : 'Copy'"></span>
                            </button>
                        </div>
                    </div>

                    
                    <button type="button" @click="copyAll()" class="btn btn-primary w-full mt-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <span x-text="copiedKey==='all' ? '✓ All Copied!' : 'Copy All Values'"></span>
                    </button>
                </div>

                
                <div class="card p-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Manual Input</h3>

                    
                    <div class="mb-4">
                        <label class="form-label">HEX Code</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 font-mono font-bold pointer-events-none select-none">#</span>
                            <input type="text"
                                   x-model="hexIn"
                                   @focus="_foc='hex'"
                                   @blur="applyHex()"
                                   @keydown.enter="applyHex()"
                                   maxlength="7"
                                   spellcheck="false"
                                   placeholder="4F46E5"
                                   class="form-input pl-8 font-mono uppercase tracking-widest"
                                   :class="hexErr ? 'border-red-400 focus:border-red-400 focus:ring-red-300' : ''">
                        </div>
                        <p x-show="hexErr" x-transition class="form-error" x-text="hexErr"></p>
                        <p class="form-help mt-1">Accepts 3 or 6 character hex codes, with or without #</p>
                    </div>

                    
                    <div class="mb-4">
                        <label class="form-label">RGB Channels</label>
                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <label class="text-xs text-red-500 font-semibold mb-1 block">R (0–255)</label>
                                <input type="number" x-model.number="rIn" min="0" max="255"
                                       @focus="_foc='rgb'" @blur="applyRgb()" @keydown.enter="applyRgb()"
                                       class="form-input text-center font-mono"
                                       :class="rgbErr ? 'border-red-400' : ''">
                            </div>
                            <div>
                                <label class="text-xs text-emerald-500 font-semibold mb-1 block">G (0–255)</label>
                                <input type="number" x-model.number="gIn" min="0" max="255"
                                       @focus="_foc='rgb'" @blur="applyRgb()" @keydown.enter="applyRgb()"
                                       class="form-input text-center font-mono"
                                       :class="rgbErr ? 'border-red-400' : ''">
                            </div>
                            <div>
                                <label class="text-xs text-blue-500 font-semibold mb-1 block">B (0–255)</label>
                                <input type="number" x-model.number="bIn" min="0" max="255"
                                       @focus="_foc='rgb'" @blur="applyRgb()" @keydown.enter="applyRgb()"
                                       class="form-input text-center font-mono"
                                       :class="rgbErr ? 'border-red-400' : ''">
                            </div>
                        </div>
                        <p x-show="rgbErr" x-transition class="form-error mt-1" x-text="rgbErr"></p>
                    </div>

                    
                    <div>
                        <label class="form-label">HSL Values</label>
                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <label class="text-xs text-gray-500 font-semibold mb-1 block">H (0–360)</label>
                                <input type="number" x-model.number="hIn" min="0" max="360"
                                       @focus="_foc='hsl'" @blur="applyHsl()" @keydown.enter="applyHsl()"
                                       class="form-input text-center font-mono"
                                       :class="hslErr ? 'border-red-400' : ''">
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 font-semibold mb-1 block">S (0–100)</label>
                                <input type="number" x-model.number="sIn" min="0" max="100"
                                       @focus="_foc='hsl'" @blur="applyHsl()" @keydown.enter="applyHsl()"
                                       class="form-input text-center font-mono"
                                       :class="hslErr ? 'border-red-400' : ''">
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 font-semibold mb-1 block">L (0–100)</label>
                                <input type="number" x-model.number="lIn" min="0" max="100"
                                       @focus="_foc='hsl'" @blur="applyHsl()" @keydown.enter="applyHsl()"
                                       class="form-input text-center font-mono"
                                       :class="hslErr ? 'border-red-400' : ''">
                            </div>
                        </div>
                        <p x-show="hslErr" x-transition class="form-error mt-1" x-text="hslErr"></p>
                    </div>
                </div>

                
                <div class="card p-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Channel Sliders</h3>
                    <div class="space-y-4">

                        
                        <div>
                            <div class="flex justify-between text-xs mb-1.5">
                                <span class="font-semibold text-red-500">Red</span>
                                <span class="font-mono text-gray-700" x-text="rgb.r"></span>
                            </div>
                            <input type="range" x-model.number="rIn" min="0" max="255" step="1"
                                   @input="applyRgbSlider()"
                                   class="cp-range"
                                   :style="'background:linear-gradient(to right,rgb(0,'+rgb.g+','+rgb.b+'),rgb(255,'+rgb.g+','+rgb.b+'))'">
                        </div>

                        
                        <div>
                            <div class="flex justify-between text-xs mb-1.5">
                                <span class="font-semibold text-emerald-500">Green</span>
                                <span class="font-mono text-gray-700" x-text="rgb.g"></span>
                            </div>
                            <input type="range" x-model.number="gIn" min="0" max="255" step="1"
                                   @input="applyRgbSlider()"
                                   class="cp-range"
                                   :style="'background:linear-gradient(to right,rgb('+rgb.r+',0,'+rgb.b+'),rgb('+rgb.r+',255,'+rgb.b+'))'">
                        </div>

                        
                        <div>
                            <div class="flex justify-between text-xs mb-1.5">
                                <span class="font-semibold text-blue-500">Blue</span>
                                <span class="font-mono text-gray-700" x-text="rgb.b"></span>
                            </div>
                            <input type="range" x-model.number="bIn" min="0" max="255" step="1"
                                   @input="applyRgbSlider()"
                                   class="cp-range"
                                   :style="'background:linear-gradient(to right,rgb('+rgb.r+','+rgb.g+',0),rgb('+rgb.r+','+rgb.g+',255))'">
                        </div>
                    </div>
                </div>

            </div>
        </div>

        
        <div class="card p-5 mt-5" x-show="history.length > 0" x-transition>
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-700">
                    Color History
                    <span class="ml-1 badge badge-gray" x-text="history.length"></span>
                </h3>
                <button type="button" @click="history=[]"
                        class="text-xs text-gray-400 hover:text-red-500 transition-colors">
                    Clear all
                </button>
            </div>
            <div class="flex flex-wrap gap-2">
                <template x-for="(c,i) in history" :key="i">
                    <button type="button"
                            @click="applyHistoryColor(c)"
                            :style="'background:'+c"
                            :title="c"
                            class="w-9 h-9 rounded-xl border-2 transition-all hover:scale-110 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-brand-400"
                            :class="hexColor===c ? 'border-brand-500 ring-2 ring-brand-300' : 'border-white shadow-sm'">
                        <span class="sr-only" x-text="c"></span>
                    </button>
                </template>
            </div>
        </div>

        
        <?php if($relatedTools->count()): ?>
        <div class="mt-8">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Related Tools</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
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
function colorPicker() {
    return {
        /* ── Core state (HSV colour model) ── */
        h:   220,   /* hue 0-360          */
        sat: 80,    /* saturation 0-100   */
        val: 90,    /* value/brightness 0-100 */
        alpha: 100, /* opacity 0-100       */

        /* ── UI state ── */
        prevColor: '#4f46e5',
        _picking:  false,
        _foc:      null,   /* 'hex'|'rgb'|'hsl'|null — which input is focused */
        copiedKey: '',
        _cpTimer:  null,

        /* ── Manual input state ── */
        hexIn: '', hexErr: '',
        rIn: 0, gIn: 0, bIn: 0, rgbErr: '',
        hIn: 0, sIn: 0, lIn: 0, hslErr: '',

        /* ── History ── */
        history: [],

        /* ════════ Init ════════ */
        init() {
            this._sync();
            this._updateNative();
        },

        /* ════════ Colour conversions ════════ */

        /* HSV → RGB */
        _hsvToRgb(h, s, v) {
            s /= 100; v /= 100;
            var c = v * s, x = c * (1 - Math.abs((h / 60) % 2 - 1)), m = v - c;
            var r = 0, g = 0, b = 0;
            if      (h < 60)  { r=c; g=x; b=0; }
            else if (h < 120) { r=x; g=c; b=0; }
            else if (h < 180) { r=0; g=c; b=x; }
            else if (h < 240) { r=0; g=x; b=c; }
            else if (h < 300) { r=x; g=0; b=c; }
            else              { r=c; g=0; b=x; }
            return { r: Math.round((r+m)*255), g: Math.round((g+m)*255), b: Math.round((b+m)*255) };
        },

        /* RGB → HSV */
        _rgbToHsv(r, g, b) {
            r/=255; g/=255; b/=255;
            var max=Math.max(r,g,b), min=Math.min(r,g,b), d=max-min;
            var h=0, s=max===0?0:d/max, v=max;
            if (max!==min) {
                switch(max) {
                    case r: h=((g-b)/d+(g<b?6:0))/6; break;
                    case g: h=((b-r)/d+2)/6; break;
                    case b: h=((r-g)/d+4)/6; break;
                }
            }
            return { h:Math.round(h*360), s:Math.round(s*100), v:Math.round(v*100) };
        },

        /* RGB → HSL */
        _rgbToHsl(r, g, b) {
            r/=255; g/=255; b/=255;
            var max=Math.max(r,g,b), min=Math.min(r,g,b), d=max-min;
            var h=0, s=0, l=(max+min)/2;
            if (max!==min) {
                s = l>0.5 ? d/(2-max-min) : d/(max+min);
                switch(max) {
                    case r: h=((g-b)/d+(g<b?6:0))/6; break;
                    case g: h=((b-r)/d+2)/6; break;
                    case b: h=((r-g)/d+4)/6; break;
                }
            }
            return { h:Math.round(h*360), s:Math.round(s*100), l:Math.round(l*100) };
        },

        /* HSL → RGB */
        _hslToRgb(h, s, l) {
            h/=360; s/=100; l/=100;
            var r,g,b;
            if (s===0) { r=g=b=l; }
            else {
                var q=l<0.5?l*(1+s):l+s-l*s, p=2*l-q;
                r=this._h2r(p,q,h+1/3);
                g=this._h2r(p,q,h);
                b=this._h2r(p,q,h-1/3);
            }
            return { r:Math.round(r*255), g:Math.round(g*255), b:Math.round(b*255) };
        },
        _h2r(p,q,t) {
            if(t<0)t+=1; if(t>1)t-=1;
            if(t<1/6) return p+(q-p)*6*t;
            if(t<1/2) return q;
            if(t<2/3) return p+(q-p)*(2/3-t)*6;
            return p;
        },

        /* HEX string → RGB object (null if invalid) */
        _hexToRgb(hex) {
            var raw = hex.replace(/^#/,'').trim();
            if (raw.length===3) raw=raw[0]+raw[0]+raw[1]+raw[1]+raw[2]+raw[2];
            if (!/^[0-9A-Fa-f]{6}$/.test(raw)) return null;
            return {
                r: parseInt(raw.slice(0,2),16),
                g: parseInt(raw.slice(2,4),16),
                b: parseInt(raw.slice(4,6),16),
            };
        },

        /* RGB → 6-char hex string (no #) */
        _rgbToHex(r,g,b) {
            return [r,g,b].map(function(v){
                return ('0'+Math.max(0,Math.min(255,Math.round(v))).toString(16)).slice(-2);
            }).join('');
        },

        /* ════════ Computed getters ════════ */

        get rgb()      { return this._hsvToRgb(this.h, this.sat, this.val); },
        get hex()      { return this._rgbToHex(this.rgb.r, this.rgb.g, this.rgb.b); },
        get hexColor() { return '#' + this.hex; },
        get hexStr()   { return '#' + this.hex.toUpperCase(); },

        get rgbStr() {
            var c = this.rgb;
            return 'rgb('+c.r+', '+c.g+', '+c.b+')';
        },
        get rgbaStr() {
            var c = this.rgb, a = (this.alpha/100).toFixed(2).replace(/0+$/,'').replace(/\.$/,'');
            return 'rgba('+c.r+', '+c.g+', '+c.b+', '+a+')';
        },
        get hslObj()  { return this._rgbToHsl(this.rgb.r, this.rgb.g, this.rgb.b); },
        get hslStr()  {
            var h = this.hslObj;
            return 'hsl('+h.h+', '+h.s+'%, '+h.l+'%)';
        },
        get hsvStr()  { return 'hsv('+this.h+', '+this.sat+'%, '+this.val+'%)'; },

        /* Color to show (rgba when alpha < 100) */
        get displayColor() {
            return this.alpha < 100 ? this.rgbaStr : this.hexColor;
        },

        /* ════════ Sync helpers ════════ */

        _sync() {
            var c = this.rgb, hsl = this.hslObj;
            if (this._foc !== 'hex') this.hexIn  = this.hex.toUpperCase();
            if (this._foc !== 'rgb') { this.rIn=c.r; this.gIn=c.g; this.bIn=c.b; }
            if (this._foc !== 'hsl') { this.hIn=hsl.h; this.sIn=hsl.s; this.lIn=hsl.l; }
            this._updateNative();
        },

        _updateNative() {
            var self = this;
            this.$nextTick(function() {
                if (self.$refs.native) self.$refs.native.value = self.hexColor;
            });
        },

        /* Called on any picker change (drag, hue/alpha slider) */
        onPickerChange() { this._sync(); },

        /* ════════ Gradient picker drag ════════ */

        startPick(e) {
            this._picking = true;
            this._movePick(e.clientX, e.clientY);
            var self = this;
            self._mv = function(ev) { if(self._picking) self._movePick(ev.clientX,ev.clientY); };
            self._up = function()   {
                self._picking = false;
                document.removeEventListener('mousemove', self._mv);
                document.removeEventListener('mouseup',   self._up);
                self._addHistory();
            };
            document.addEventListener('mousemove', self._mv);
            document.addEventListener('mouseup',   self._up);
        },

        _movePick(cx, cy) {
            var r = this.$refs.sq.getBoundingClientRect();
            this.sat = Math.round(Math.max(0,Math.min(1,(cx-r.left)/r.width))*100);
            this.val = Math.round(Math.max(0,Math.min(1,1-(cy-r.top)/r.height))*100);
            this._sync();
        },

        startPickTouch(e)  { this._picking=true; this._movePick(e.touches[0].clientX, e.touches[0].clientY); },
        moveTouchPick(e)   { if(this._picking) this._movePick(e.touches[0].clientX, e.touches[0].clientY); },
        endTouch()         { this._picking=false; this._addHistory(); },

        /* ════════ Set from external sources ════════ */

        setFromRgb(r, g, b) {
            var hsv = this._rgbToHsv(r,g,b);
            this.h=hsv.h; this.sat=hsv.s; this.val=hsv.v;
            this._sync();
            this._addHistory();
        },

        setFromNative(value) {
            this.prevColor = this.hexColor;
            var rgb = this._hexToRgb(value);
            if (rgb) this.setFromRgb(rgb.r,rgb.g,rgb.b);
        },

        restorePrev() {
            var old = this.prevColor;
            this.prevColor = this.hexColor;
            var rgb = this._hexToRgb(old);
            if (rgb) { var hsv=this._rgbToHsv(rgb.r,rgb.g,rgb.b); this.h=hsv.h; this.sat=hsv.s; this.val=hsv.v; this._sync(); }
        },

        applyHistoryColor(c) {
            this.prevColor = this.hexColor;
            var rgb = this._hexToRgb(c);
            if (rgb) { var hsv=this._rgbToHsv(rgb.r,rgb.g,rgb.b); this.h=hsv.h; this.sat=hsv.s; this.val=hsv.v; this._sync(); }
        },

        /* ════════ Apply manual inputs ════════ */

        applyHex() {
            this._foc = null; this.hexErr = '';
            var raw = this.hexIn.trim().replace(/^#/,'');
            if (!raw) return;
            if (raw.length===3) raw=raw[0]+raw[0]+raw[1]+raw[1]+raw[2]+raw[2];
            if (!/^[0-9A-Fa-f]{6}$/.test(raw)) {
                this.hexErr = raw.length!==6 ? 'HEX must be 3 or 6 hex characters.' : 'Only 0–9 and A–F are valid HEX characters.';
                return;
            }
            var rgb = this._hexToRgb(raw);
            this.prevColor = this.hexColor;
            this.setFromRgb(rgb.r, rgb.g, rgb.b);
        },

        applyRgb() {
            this._foc = null; this.rgbErr = '';
            var r=Math.round(this.rIn), g=Math.round(this.gIn), b=Math.round(this.bIn);
            if([r,g,b].some(function(v){return isNaN(v)||v<0||v>255;})) {
                this.rgbErr = 'Each channel must be a whole number from 0 to 255.'; return;
            }
            this.prevColor = this.hexColor;
            this.setFromRgb(r,g,b);
        },

        applyRgbSlider() {
            this.rgbErr = '';
            var r=Math.round(this.rIn), g=Math.round(this.gIn), b=Math.round(this.bIn);
            var hsv = this._rgbToHsv(r,g,b);
            this.h=hsv.h; this.sat=hsv.s; this.val=hsv.v;
            this._sync();
        },

        applyHsl() {
            this._foc = null; this.hslErr = '';
            var h=Math.round(this.hIn), s=Math.round(this.sIn), l=Math.round(this.lIn);
            if (isNaN(h)||h<0||h>360||isNaN(s)||s<0||s>100||isNaN(l)||l<0||l>100) {
                this.hslErr = 'H: 0–360 · S: 0–100 · L: 0–100'; return;
            }
            var rgb = this._hslToRgb(h,s,l);
            this.prevColor = this.hexColor;
            this.setFromRgb(rgb.r,rgb.g,rgb.b);
        },

        /* ════════ History ════════ */

        _addHistory() {
            var c = this.hexColor;
            this.prevColor = c;
            this.history = [c].concat(this.history.filter(function(x){return x!==c;})).slice(0,24);
        },

        /* ════════ Clipboard ════════ */

        copy(text, key) {
            var self = this;
            clearTimeout(this._cpTimer);
            navigator.clipboard.writeText(text).then(function() {
                self._setCopied(key);
            }).catch(function() {
                var ta = document.createElement('textarea');
                ta.value = text;
                ta.style.cssText = 'position:fixed;opacity:0;pointer-events:none';
                document.body.appendChild(ta);
                ta.select();
                try { document.execCommand('copy'); } catch(e) {}
                document.body.removeChild(ta);
                self._setCopied(key);
            });
        },
        _setCopied(k) {
            this.copiedKey = k;
            var self = this;
            this._cpTimer = setTimeout(function(){ self.copiedKey=''; }, 2000);
        },
        copyAll() {
            var lines = [
                'HEX:  ' + this.hexStr,
                'RGB:  ' + this.rgbStr,
                'RGBA: ' + this.rgbaStr,
                'HSL:  ' + this.hslStr,
                'HSV:  ' + this.hsvStr,
            ].join('\n');
            this.copy(lines, 'all');
        },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\color-picker.blade.php ENDPATH**/ ?>