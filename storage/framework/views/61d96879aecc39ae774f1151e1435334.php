<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50">

    
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 py-10">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                <?php echo e($tool->icon); ?> <?php echo e($tool->name); ?>

            </h1>
            <p class="text-gray-500 mt-2"><?php echo e($tool->short_description); ?></p>
        </div>
    </div>

    
    <div class="max-w-2xl mx-auto px-4 sm:px-6 py-10 space-y-5"
         x-data="hexToRgb()"
         x-init="init()">

        
        <div class="card p-6">
            <label class="form-label">Enter a HEX color code</label>

            
            <div class="flex gap-3 items-start">

                
                <div class="flex-shrink-0 relative" title="Click to pick a color">
                    <input type="color"
                           x-ref="colorPicker"
                           @input="setFromPicker($event.target.value)"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                           tabindex="-1">
                    <div class="w-12 h-10 rounded-xl border-2 transition-all cursor-pointer flex items-center justify-center overflow-hidden"
                         :class="isValid ? 'border-gray-300' : 'border-dashed border-gray-300'"
                         :style="isValid ? 'background:#' + _normalized : 'background:white'">
                        <svg x-show="!isValid" class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                        </svg>
                    </div>
                </div>

                
                <div class="flex-1">
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 font-mono font-bold select-none pointer-events-none">#</span>
                        <input type="text"
                               x-model="hex"
                               @input="convert()"
                               @keydown.enter="convert()"
                               placeholder="FFFFFF  or  FFF  or  #FF5733"
                               maxlength="7"
                               autocomplete="off"
                               spellcheck="false"
                               class="form-input pl-8 font-mono tracking-widest uppercase">
                        
                        <button type="button" x-show="hex.length > 0"
                                @click="hex = ''; isValid = false; error = ''"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <p class="form-help mt-1.5">Supports <code class="bg-gray-100 px-1 rounded text-xs">#FFFFFF</code>, <code class="bg-gray-100 px-1 rounded text-xs">FFFFFF</code>, <code class="bg-gray-100 px-1 rounded text-xs">#FFF</code>, and <code class="bg-gray-100 px-1 rounded text-xs">FFF</code></p>
                </div>
            </div>

            
            <div x-show="error" x-transition class="mt-3 alert alert-error flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span x-text="error"></span>
            </div>
        </div>

        
        <div x-show="!isValid && !error" class="card p-12 text-center text-gray-400">
            <div class="text-5xl mb-3">🎨</div>
            <p class="font-medium text-gray-500">Enter a HEX code above to convert it instantly</p>
            <p class="text-sm mt-1">Or click the color swatch to pick from a visual color picker</p>
        </div>

        
        <div x-show="isValid" x-transition class="space-y-4">

            
            <div class="card overflow-hidden">
                <div class="h-28 w-full transition-all duration-300"
                     :style="'background: #' + _normalized"></div>
                <div class="px-5 py-3 flex items-center justify-between bg-white">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Preview</p>
                        <p class="font-mono font-bold text-gray-800 text-sm" x-text="'#' + _normalized.toUpperCase()"></p>
                    </div>
                    <div class="flex gap-2">
                        <span class="badge"
                              :class="isDark ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700'"
                              x-text="isDark ? 'Dark' : 'Light'"></span>
                        <span class="badge badge-gray" x-text="'Hue ' + hslH + '°'"></span>
                    </div>
                </div>
            </div>

            
            <div class="card p-6 space-y-4">
                <h3 class="text-sm font-semibold text-gray-700">Conversion Results</h3>

                
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl gap-3">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">RGB</p>
                        <p class="font-mono text-sm font-semibold text-gray-900 truncate" x-text="rgbStr"></p>
                    </div>
                    <button type="button" @click="copy(rgbStr, 'rgb')"
                            class="btn btn-secondary btn-sm flex-shrink-0">
                        <svg x-show="copiedKey !== 'rgb'" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <svg x-show="copiedKey === 'rgb'" class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span x-text="copiedKey === 'rgb' ? 'Copied!' : 'Copy'"></span>
                    </button>
                </div>

                
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl gap-3">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">HEX (Normalized)</p>
                        <p class="font-mono text-sm font-semibold text-gray-900 truncate" x-text="'#' + _normalized.toUpperCase()"></p>
                    </div>
                    <button type="button" @click="copy('#' + _normalized.toUpperCase(), 'hex')"
                            class="btn btn-secondary btn-sm flex-shrink-0">
                        <svg x-show="copiedKey !== 'hex'" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <svg x-show="copiedKey === 'hex'" class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span x-text="copiedKey === 'hex' ? 'Copied!' : 'Copy'"></span>
                    </button>
                </div>

                
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl gap-3">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">HSL</p>
                        <p class="font-mono text-sm font-semibold text-gray-900 truncate" x-text="hslStr"></p>
                    </div>
                    <button type="button" @click="copy(hslStr, 'hsl')"
                            class="btn btn-secondary btn-sm flex-shrink-0">
                        <svg x-show="copiedKey !== 'hsl'" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <svg x-show="copiedKey === 'hsl'" class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span x-text="copiedKey === 'hsl' ? 'Copied!' : 'Copy'"></span>
                    </button>
                </div>
            </div>

            
            <div class="card p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Channel Breakdown</h3>
                <div class="space-y-3">
                    
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-red-500 flex-shrink-0"></span>
                                <span class="text-sm font-medium text-gray-700">Red</span>
                            </div>
                            <span class="font-mono text-sm font-bold text-gray-900"
                                  x-text="r + '  (' + pct(r) + '%)'"></span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                            <div class="h-2.5 rounded-full bg-red-500 transition-all duration-300"
                                 :style="'width:' + pct(r) + '%'"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-emerald-500 flex-shrink-0"></span>
                                <span class="text-sm font-medium text-gray-700">Green</span>
                            </div>
                            <span class="font-mono text-sm font-bold text-gray-900"
                                  x-text="g + '  (' + pct(g) + '%)'"></span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                            <div class="h-2.5 rounded-full bg-emerald-500 transition-all duration-300"
                                 :style="'width:' + pct(g) + '%'"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-blue-500 flex-shrink-0"></span>
                                <span class="text-sm font-medium text-gray-700">Blue</span>
                            </div>
                            <span class="font-mono text-sm font-bold text-gray-900"
                                  x-text="b + '  (' + pct(b) + '%)'"></span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                            <div class="h-2.5 rounded-full bg-blue-500 transition-all duration-300"
                                 :style="'width:' + pct(b) + '%'"></div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">CSS Usage</h3>
                <div class="code-block text-xs space-y-1">
                    <p><span class="text-purple-400">color</span><span class="text-gray-400">:</span> <span class="text-emerald-400" x-text="rgbStr"></span><span class="text-gray-400">;</span></p>
                    <p><span class="text-purple-400">background-color</span><span class="text-gray-400">:</span> <span class="text-emerald-400" x-text="'#' + _normalized.toUpperCase()"></span><span class="text-gray-400">;</span></p>
                    <p><span class="text-purple-400">border-color</span><span class="text-gray-400">:</span> <span class="text-emerald-400" x-text="hslStr"></span><span class="text-gray-400">;</span></p>
                    <p><span class="text-purple-400">--my-color</span><span class="text-gray-400">:</span> <span class="text-emerald-400" x-text="r + ', ' + g + ', ' + b"></span><span class="text-gray-400">; /* for rgba() usage */</span></p>
                </div>
                <button type="button" @click="copy(cssSnippet, 'css')"
                        class="btn btn-secondary btn-sm mt-3 w-full">
                    <svg x-show="copiedKey !== 'css'" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <svg x-show="copiedKey === 'css'" class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span x-text="copiedKey === 'css' ? 'Copied!' : 'Copy CSS Snippet'"></span>
                </button>
            </div>

        </div>

        
        <div class="card p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Quick Color Presets</h3>
            <div class="grid grid-cols-5 sm:grid-cols-10 gap-2">
                <template x-for="preset in presets" :key="preset.hex">
                    <button type="button"
                            @click="applyPreset(preset.hex)"
                            class="group relative aspect-square rounded-xl border-2 transition-all hover:scale-110 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1"
                            :class="'#' + _normalized.toUpperCase() === '#' + preset.hex ? 'border-brand-500 ring-2 ring-brand-300' : 'border-transparent'"
                            :style="'background:#' + preset.hex"
                            :title="preset.name + ' — #' + preset.hex">
                        <span class="sr-only" x-text="preset.name"></span>
                    </button>
                </template>
            </div>
            <p class="form-help mt-3 text-center">Click any swatch to load that color instantly</p>
        </div>

        
        <?php if($relatedTools->count()): ?>
        <div class="mt-2">
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
function hexToRgb() {
    return {
        /* ── State ── */
        hex:       '',
        error:     '',
        isValid:   false,
        r: 0, g: 0, b: 0,
        _normalized: 'ffffff',   /* always 6-char lowercase */
        copiedKey: '',
        _copyTimer: null,

        /* ── Presets ── */
        presets: [
            { name:'Red',       hex:'FF0000' },
            { name:'Orange',    hex:'FF6B35' },
            { name:'Amber',     hex:'F59E0B' },
            { name:'Yellow',    hex:'FDE047' },
            { name:'Lime',      hex:'84CC16' },
            { name:'Emerald',   hex:'10B981' },
            { name:'Cyan',      hex:'06B6D4' },
            { name:'Sky',       hex:'0EA5E9' },
            { name:'Blue',      hex:'3B82F6' },
            { name:'Indigo',    hex:'6366F1' },
            { name:'Violet',    hex:'8B5CF6' },
            { name:'Purple',    hex:'A855F7' },
            { name:'Pink',      hex:'EC4899' },
            { name:'Rose',      hex:'F43F5E' },
            { name:'Stone',     hex:'78716C' },
            { name:'Gray',      hex:'6B7280' },
            { name:'Slate',     hex:'64748B' },
            { name:'Black',     hex:'000000' },
            { name:'White',     hex:'FFFFFF' },
            { name:'Brand',     hex:'4F46E5' },
        ],

        /* ── Init ── */
        init() {
            /* Start with a nice default so the page isn't blank */
            this.applyPreset('4F46E5');
        },

        /* ── Main convert ── */
        convert() {
            var raw = this.hex.trim().replace(/^#+/, '');   /* strip leading # */

            /* Empty input */
            if (raw.length === 0) {
                this.isValid = false;
                this.error   = '';
                return;
            }

            /* Expand 3-char shorthand → 6 chars */
            if (raw.length === 3) {
                raw = raw[0]+raw[0] + raw[1]+raw[1] + raw[2]+raw[2];
            }

            /* Validate: must be exactly 6 hex chars */
            if (!/^[0-9A-Fa-f]{6}$/.test(raw)) {
                this.isValid = false;
                if (raw.length > 0 && raw.length !== 6) {
                    this.error = 'HEX codes must be 3 or 6 characters (e.g. FFF or FF5733).';
                } else {
                    this.error = 'Invalid characters — only 0–9 and A–F are allowed in a HEX code.';
                }
                return;
            }

            /* Valid! */
            this.isValid     = true;
            this.error       = '';
            this._normalized = raw.toLowerCase();
            this.r           = parseInt(raw.substring(0,2), 16);
            this.g           = parseInt(raw.substring(2,4), 16);
            this.b           = parseInt(raw.substring(4,6), 16);

            /* Sync native color picker */
            this.$nextTick(function() {
                if (this.$refs.colorPicker) {
                    this.$refs.colorPicker.value = '#' + raw.toLowerCase();
                }
            }.bind(this));
        },

        /* ── Called when native color picker changes ── */
        setFromPicker(value) {
            /* value from <input type="color"> is always #rrggbb */
            var raw = value.replace('#', '').toUpperCase();
            this.hex = raw;
            this.convert();
        },

        /* ── Preset click ── */
        applyPreset(hexCode) {
            this.hex = hexCode.toUpperCase();
            this.convert();
        },

        /* ── Copy to clipboard ── */
        copy(text, key) {
            var self = this;
            clearTimeout(this._copyTimer);
            navigator.clipboard.writeText(text).then(function() {
                self._setCopied(key);
            }).catch(function() {
                /* Fallback for older browsers */
                var ta = document.createElement('textarea');
                ta.value = text;
                ta.style.cssText = 'position:fixed;opacity:0;top:0;left:0';
                document.body.appendChild(ta);
                ta.focus(); ta.select();
                try { document.execCommand('copy'); } catch(e) {}
                document.body.removeChild(ta);
                self._setCopied(key);
            });
        },
        _setCopied(key) {
            this.copiedKey = key;
            var self = this;
            this._copyTimer = setTimeout(function() { self.copiedKey = ''; }, 2000);
        },

        /* ═══════════ Computed getters ═══════════ */

        get rgbStr()  { return 'rgb(' + this.r + ', ' + this.g + ', ' + this.b + ')'; },

        get hslStr()  {
            var h = this.hslH, s = this.hslS, l = this.hslL;
            return 'hsl(' + h + ', ' + s + '%, ' + l + '%)';
        },

        get hslH() { return this._toHsl().h; },
        get hslS() { return this._toHsl().s; },
        get hslL() { return this._toHsl().l; },

        _toHsl() {
            var r = this.r / 255, g = this.g / 255, b = this.b / 255;
            var max = Math.max(r, g, b), min = Math.min(r, g, b);
            var h = 0, s = 0, l = (max + min) / 2;
            if (max !== min) {
                var d = max - min;
                s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
                switch (max) {
                    case r: h = ((g - b) / d + (g < b ? 6 : 0)) / 6; break;
                    case g: h = ((b - r) / d + 2) / 6; break;
                    case b: h = ((r - g) / d + 4) / 6; break;
                }
            }
            return {
                h: Math.round(h * 360),
                s: Math.round(s * 100),
                l: Math.round(l * 100),
            };
        },

        /* Perceived brightness (0–255); <128 = dark bg → use white text */
        get brightness() {
            return (0.299 * this.r + 0.587 * this.g + 0.114 * this.b);
        },
        get isDark() { return this.brightness < 128; },

        /* Percentage of 255 */
        pct(val) { return Math.round((val / 255) * 100); },

        get cssSnippet() {
            return [
                'color: ' + this.rgbStr + ';',
                'background-color: #' + this._normalized.toUpperCase() + ';',
                'border-color: ' + this.hslStr + ';',
                '--my-color: ' + this.r + ', ' + this.g + ', ' + this.b + '; /* use with rgba() */',
            ].join('\n');
        },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\hex-to-rgb-converter.blade.php ENDPATH**/ ?>