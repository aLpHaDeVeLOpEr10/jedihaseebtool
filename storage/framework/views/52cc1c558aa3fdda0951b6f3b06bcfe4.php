<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ── ASCII output pane ── */
.ascii-pre {
    font-family: 'Courier New', Courier, monospace;
    line-height: 1.2;
    white-space: pre;
    overflow-x: auto;
    padding: 1.5rem;
    border-radius: 0 0 1rem 1rem;
    min-height: 200px;
    tab-size: 1;
    transition: background .25s, color .25s, font-size .15s;
    display: block;
}

/* ── Font pill ── */
.font-pill {
    padding: .3rem .75rem;
    border-radius: .75rem;
    border: 1.5px solid;
    font-size: .72rem;
    font-weight: 500;
    cursor: pointer;
    transition: all .12s;
    white-space: nowrap;
    line-height: 1.5;
}

/* ── Align button ── */
.align-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .35rem;
    padding: .5rem;
    border: 1.5px solid;
    border-radius: .875rem;
    font-size: .78rem;
    font-weight: 600;
    cursor: pointer;
    transition: all .12s;
}

/* ── Theme swatch ── */
.theme-swatch {
    width: 2rem; height: 2rem;
    border-radius: .5rem;
    border: 2.5px solid transparent;
    cursor: pointer;
    transition: transform .15s, border-color .15s;
}
.theme-swatch:hover       { transform: scale(1.1); }
.theme-swatch.is-active   { border-color: #4f46e5; transform: scale(1.12); }

/* ── Preview size buttons ── */
.psize-btn {
    padding: .25rem .55rem;
    border-radius: .5rem;
    border: 1.5px solid #e5e7eb;
    background: white;
    color: #6b7280;
    font-size: .7rem;
    font-weight: 600;
    cursor: pointer;
    transition: all .12s;
}
.psize-btn.is-active {
    border-color: #4f46e5;
    background: #eef2ff;
    color: #3730a3;
}

/* ── Spinner ── */
@keyframes spin { to { transform: rotate(360deg); } }
.spinner { display:inline-block; animation: spin .65s linear infinite; }

/* ── Pulse badge on copy ── */
@keyframes copyFlash {
    0%,100% { background:#f0fdf4; color:#166534; border-color:#86efac; }
    50%      { background:#dcfce7; color:#14532d; border-color:#4ade80; }
}
.copy-flash { animation: copyFlash .6s ease 3; }

/* ── Scrollbar for font list ── */
.font-scroll::-webkit-scrollbar { width:4px; }
.font-scroll::-webkit-scrollbar-track { background:transparent; }
.font-scroll::-webkit-scrollbar-thumb { background:#e2e8f0; border-radius:9999px; }

/* ── Output scrollbar ── */
.ascii-pre::-webkit-scrollbar { height:5px; }
.ascii-pre::-webkit-scrollbar-track { background:rgba(255,255,255,.05); }
.ascii-pre::-webkit-scrollbar-thumb { background:rgba(255,255,255,.15); border-radius:9999px; }
</style>

<div class="min-h-screen bg-gray-50"
     x-data="asciiArtGen()"
     x-init="init()">

    
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-10">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                <?php echo e($tool->icon); ?> <?php echo e($tool->name); ?>

            </h1>
            <p class="text-gray-500 mt-2"><?php echo e($tool->short_description); ?></p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">

            
            <div class="lg:col-span-2 space-y-4">

                
                <div class="card p-5">
                    <label class="form-label">Your Text</label>
                    <textarea x-model="text"
                              @input="scheduleGenerate()"
                              placeholder="Type something…"
                              rows="3"
                              maxlength="200"
                              class="form-input font-mono resize-y"></textarea>
                    <div class="flex justify-between mt-1">
                        <span class="form-help">Tip: ALL CAPS looks bolder</span>
                        <span class="text-xs font-semibold"
                              :class="text.length > 170 ? 'text-amber-600' : 'text-gray-400'"
                              x-text="text.length + '/200'"></span>
                    </div>
                    <p x-show="inputError" class="form-error mt-1" x-text="inputError"></p>
                </div>

                
                <div class="card p-5">
                    <div class="flex items-center justify-between mb-3">
                        <label class="form-label mb-0">Font</label>
                        <span class="badge badge-primary text-xs" x-text="font"></span>
                    </div>

                    <input type="text"
                           x-model="fontSearch"
                           placeholder="Search fonts…"
                           class="form-input mb-3">

                    <div class="font-scroll max-h-56 overflow-y-auto space-y-3 pr-0.5">
                        <template x-for="g in filteredGroups" :key="g.group">
                            <div x-show="g.fonts.length > 0">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1.5"
                                   x-text="g.group"></p>
                                <div class="flex flex-wrap gap-1.5">
                                    <template x-for="f in g.fonts" :key="f">
                                        <button type="button"
                                                @click="selectFont(f)"
                                                class="font-pill"
                                                :class="font === f
                                                    ? 'border-brand-500 bg-brand-50 text-brand-700'
                                                    : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300 hover:bg-gray-50'">
                                            <span x-text="f"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                        <div x-show="totalVisible === 0"
                             class="text-center py-6 text-sm text-gray-400">
                            No fonts match "<span x-text="fontSearch"></span>"
                        </div>
                    </div>
                </div>

                
                <div class="card p-5 space-y-5">

                    
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="form-label mb-0">Output Width</label>
                            <span class="text-sm font-bold text-indigo-600"
                                  x-text="width + ' cols'"></span>
                        </div>
                        <input type="range"
                               x-model.number="width"
                               @change="generate()"
                               @input="generate()"
                               min="40" max="200" step="10"
                               class="w-full accent-indigo-600 cursor-pointer">
                        <div class="flex justify-between text-xs text-gray-400 mt-1">
                            <span>40</span><span>Narrow ← → Wide</span><span>200</span>
                        </div>
                    </div>

                    
                    <div>
                        <label class="form-label">Text Alignment</label>
                        <div class="flex gap-2">
                            <template x-for="a in ALIGNS" :key="a.val">
                                <button type="button"
                                        @click="align = a.val; generate()"
                                        class="align-btn"
                                        :class="align === a.val
                                            ? 'border-brand-500 bg-brand-50 text-brand-700'
                                            : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300 hover:bg-gray-50'">
                                    <span x-text="a.icon" class="text-base leading-none"></span>
                                    <span x-text="a.label" class="hidden sm:inline"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    
                    <div>
                        <label class="form-label">
                            Fill Character
                            <span class="font-normal text-gray-400">(optional – 1 char)</span>
                        </label>
                        <div class="flex gap-3 items-start">
                            <input type="text"
                                   x-model="fillChar"
                                   @input="onFillChar()"
                                   maxlength="1"
                                   placeholder="e.g. # @ * ■"
                                   class="form-input w-20 text-center font-mono text-xl font-bold">
                            <p class="flex-1 text-xs text-gray-400 pt-2 leading-relaxed">
                                Replaces all art characters with this symbol.
                            </p>
                        </div>
                    </div>
                </div>

                
                <div class="flex gap-3">
                    <button type="button"
                            @click="generate()"
                            :disabled="loading"
                            class="btn btn-primary btn-lg flex-1">
                        <span x-show="loading" class="spinner">⏳</span>
                        <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Generate
                    </button>
                    <button type="button" @click="reset()" class="btn btn-secondary btn-lg">
                        Reset
                    </button>
                </div>
            </div>

            
            <div class="lg:col-span-3 space-y-4">

                
                <div class="card overflow-visible">

                    
                    <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100 flex-wrap gap-y-2">

                        
                        <div class="flex items-center gap-2 flex-1">
                            <span class="text-sm font-semibold text-gray-700">Output</span>
                            <template x-if="output">
                                <span class="badge badge-gray" x-text="lineCount + ' lines'"></span>
                            </template>
                            <template x-if="output">
                                <span class="badge badge-gray" x-text="charCount.toLocaleString() + ' chars'"></span>
                            </template>
                        </div>

                        
                        <div class="flex gap-1">
                            <template x-for="s in SIZES" :key="s.key">
                                <button type="button"
                                        @click="previewSize = s.key"
                                        class="psize-btn"
                                        :class="previewSize === s.key ? 'is-active' : ''"
                                        x-text="s.label">
                                </button>
                            </template>
                        </div>

                        
                        <button type="button"
                                @click="copyOutput()"
                                :disabled="!output"
                                class="btn btn-secondary btn-sm"
                                :class="copied ? 'copy-flash border border-emerald-200 text-emerald-700' : ''">
                            <span x-text="copied ? '✓ Copied!' : '📋 Copy'"></span>
                        </button>

                        
                        <button type="button"
                                @click="download()"
                                :disabled="!output"
                                class="btn btn-secondary btn-sm">
                            ⬇ .txt
                        </button>
                    </div>

                    
                    <div class="relative">
                        
                        <div x-show="loading"
                             class="absolute inset-0 z-10 flex items-center justify-center rounded-b-2xl"
                             :style="'background:' + currentTheme.bg + 'cc'">
                            <div class="flex items-center gap-2 text-sm"
                                 :style="'color:' + currentTheme.fg">
                                <span class="spinner">⏳</span> Rendering…
                            </div>
                        </div>

                        
                        <div x-show="renderError && !loading"
                             class="ascii-pre flex items-center justify-center"
                             :style="'background:' + currentTheme.bg">
                            <div class="text-center">
                                <p class="text-rose-400 text-sm" x-text="renderError"></p>
                                <button @click="generate()"
                                        class="mt-3 text-xs text-indigo-400 underline">
                                    Try again
                                </button>
                            </div>
                        </div>

                        
                        <div x-show="!output && !loading && !renderError"
                             class="ascii-pre flex items-center justify-center"
                             :style="'background:' + currentTheme.bg">
                            <div class="text-center" :style="'color:' + currentTheme.fg + '66'">
                                <p class="text-5xl mb-3">🔤</p>
                                <p class="text-sm font-medium">Enter text to generate ASCII art</p>
                                <p class="text-xs mt-1 opacity-60">Pick a font, then click Generate</p>
                            </div>
                        </div>

                        
                        <pre x-show="output && !loading && !renderError"
                             x-text="output"
                             class="ascii-pre"
                             :style="'font-size:' + currentSize.px + ';background:' + currentTheme.bg + ';color:' + currentTheme.fg"
                             x-ref="asciiPre"></pre>
                    </div>
                </div>

                
                <div class="card p-4">
                    <div class="flex items-center gap-4 flex-wrap">
                        <span class="text-sm font-medium text-gray-600 shrink-0">Display Theme:</span>
                        <div class="flex gap-2 flex-wrap">
                            <template x-for="t in THEMES" :key="t.name">
                                <button type="button"
                                        @click="theme = t.name"
                                        class="theme-swatch"
                                        :class="theme === t.name ? 'is-active' : ''"
                                        :style="'background:' + t.bg + ';box-shadow:inset 0 0 0 1px rgba(0,0,0,.12)'"
                                        :title="t.name">
                                </button>
                            </template>
                        </div>
                        <span class="ml-auto text-xs text-gray-400" x-text="theme"></span>
                    </div>
                </div>

                
                <div class="card p-5">
                    <h3 class="font-semibold text-gray-800 mb-3 text-sm">💡 Tips for great ASCII art</h3>
                    <div class="grid sm:grid-cols-2 gap-2 text-xs text-gray-500">
                        <div class="flex gap-2">
                            <span class="text-indigo-400 font-bold shrink-0">→</span>
                            Use <strong class="text-gray-700">UPPERCASE</strong> — letters render fuller and bolder.
                        </div>
                        <div class="flex gap-2">
                            <span class="text-indigo-400 font-bold shrink-0">→</span>
                            Short words (1–2) look best. Long sentences may overflow.
                        </div>
                        <div class="flex gap-2">
                            <span class="text-indigo-400 font-bold shrink-0">→</span>
                            Increase <strong class="text-gray-700">width</strong> if letters are getting cut off.
                        </div>
                        <div class="flex gap-2">
                            <span class="text-indigo-400 font-bold shrink-0">→</span>
                            Try <strong class="text-gray-700">fill chars</strong> like <code class="bg-gray-100 px-1 rounded font-mono">@</code> <code class="bg-gray-100 px-1 rounded font-mono">#</code> <code class="bg-gray-100 px-1 rounded font-mono">■</code> for unique styles.
                        </div>
                        <div class="flex gap-2 sm:col-span-2">
                            <span class="text-indigo-400 font-bold shrink-0">→</span>
                            Download as <strong class="text-gray-700">.txt</strong> to paste into emails, bios, code comments or social media.
                        </div>
                    </div>
                </div>

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

<script src="https://cdn.jsdelivr.net/npm/figlet@1.7.0/lib/figlet.js"></script>
<script>
/* ══════════════════════════════════════════════════════════
   ASCII ART GENERATOR  —  Alpine.js component
══════════════════════════════════════════════════════════ */
function asciiArtGen() {
    return {
        /* ── State ── */
        text:        '',
        font:        'Standard',
        width:       80,
        align:       'left',
        fillChar:    '',
        output:      '',
        loading:     false,
        copied:      false,
        inputError:  '',
        renderError: '',
        fontSearch:  '',
        previewSize: 'sm',
        theme:       'Dark',
        _debounce:   null,

        /* ── Static config ── */
        FONT_GROUPS: [
            { group: 'Popular',    fonts: ['Standard', 'Big', 'Slant', 'Shadow', 'Small', 'Mini'] },
            { group: 'Block',      fonts: ['Block', 'Banner', 'Doom', 'Colossal', 'Letters', 'Digital'] },
            { group: 'Decorative', fonts: ['Bubble', 'Puffy', 'Gothic', 'Ogre', 'Graffiti', 'Script'] },
            { group: 'Fancy',      fonts: ['Star Wars', 'Larry 3D', 'Speed', 'Lean', 'Thin', 'Epic'] },
        ],

        ALIGNS: [
            { val: 'left',   label: 'Left',   icon: '⬛⬜⬜' },
            { val: 'center', label: 'Center', icon: '⬜⬛⬜' },
            { val: 'right',  label: 'Right',  icon: '⬜⬜⬛' },
        ],

        SIZES: [
            { key: 'xs', label: 'XS', px: '9px'  },
            { key: 'sm', label: 'SM', px: '11px' },
            { key: 'md', label: 'MD', px: '13px' },
            { key: 'lg', label: 'LG', px: '15px' },
        ],

        THEMES: [
            { name: 'Dark',   bg: '#0f172a', fg: '#e2e8f0' },
            { name: 'Matrix', bg: '#052e16', fg: '#4ade80' },
            { name: 'Amber',  bg: '#1c1100', fg: '#fcd34d' },
            { name: 'Cyan',   bg: '#082f49', fg: '#67e8f9' },
            { name: 'Rose',   bg: '#1f0a12', fg: '#fda4af' },
            { name: 'Light',  bg: '#f8fafc', fg: '#0f172a' },
            { name: 'Paper',  bg: '#fefce8', fg: '#78350f' },
        ],

        /* ── Computed ── */

        get filteredGroups() {
            var q = this.fontSearch.toLowerCase().trim();
            return this.FONT_GROUPS.map(function(g) {
                return {
                    group: g.group,
                    fonts: q
                        ? g.fonts.filter(function(f) { return f.toLowerCase().indexOf(q) !== -1; })
                        : g.fonts.slice(),
                };
            });
        },

        get totalVisible() {
            return this.filteredGroups.reduce(function(sum, g) { return sum + g.fonts.length; }, 0);
        },

        get lineCount() {
            if (!this.output) return 0;
            return this.output.split('\n').filter(function(l) { return l.trim().length > 0; }).length;
        },

        get charCount() {
            return this.output ? this.output.length : 0;
        },

        get currentTheme() {
            var self = this;
            return this.THEMES.find(function(t) { return t.name === self.theme; }) || this.THEMES[0];
        },

        get currentSize() {
            var self = this;
            return this.SIZES.find(function(s) { return s.key === self.previewSize; }) || this.SIZES[1];
        },

        /* ── Lifecycle ── */

        init() {
            /* Point figlet at the CDN fonts directory */
            figlet.defaults({ fontPath: 'https://cdn.jsdelivr.net/npm/figlet@1.7.0/fonts' });

            /* Preload the most-used fonts so first render is instant */
            var self = this;
            var starter = ['Standard', 'Big', 'Slant', 'Shadow', 'Small'];

            if (typeof figlet.preloadFonts === 'function') {
                figlet.preloadFonts(starter, function() {
                    self.text = 'Hello!';
                    self.generate();
                });
            } else {
                self.text = 'Hello!';
                self.generate();
            }
        },

        /* ── Actions ── */

        selectFont(f) {
            this.font = f;
            this.generate();
        },

        onFillChar() {
            /* Keep only the last typed character */
            if (this.fillChar.length > 1) {
                this.fillChar = this.fillChar.slice(-1);
            }
            this.generate();
        },

        scheduleGenerate() {
            var self = this;
            clearTimeout(this._debounce);
            this._debounce = setTimeout(function() { self.generate(); }, 380);
        },

        generate() {
            clearTimeout(this._debounce);
            this.inputError  = '';
            this.renderError = '';

            if (!this.text.trim()) {
                this.output = '';
                return;
            }

            this.loading = true;
            var self = this;

            figlet.text(
                this.text,
                {
                    font:             this.font,
                    horizontalLayout: 'default',
                    verticalLayout:   'default',
                    width:            this.width,
                    whitespaceBreak:  true,
                },
                function(err, result) {
                    self.loading = false;

                    if (err || !result) {
                        self.renderError = 'Could not load the "' + self.font + '" font. Try another one.';
                        return;
                    }

                    /* Optional: replace every non-whitespace char with fillChar */
                    if (self.fillChar && self.fillChar.length === 1) {
                        var ch = self.fillChar;
                        result = result.replace(/[^\s\n]/g, ch);
                    }

                    /* Alignment — figlet outputs left-aligned by default */
                    if (self.align !== 'left') {
                        var lines  = result.split('\n');
                        var maxLen = lines.reduce(function(m, l) {
                            return Math.max(m, l.trimEnd().length);
                        }, 0);

                        result = lines.map(function(line) {
                            var trimmed = line.trimEnd();
                            var pad     = maxLen - trimmed.length;
                            if (self.align === 'center') {
                                return Array(Math.floor(pad / 2) + 1).join(' ') + trimmed;
                            }
                            if (self.align === 'right') {
                                return Array(pad + 1).join(' ') + trimmed;
                            }
                            return trimmed;
                        }).join('\n');
                    }

                    self.output = result;
                }
            );
        },

        copyOutput() {
            if (!this.output) return;
            var self = this;
            var done = function() {
                self.copied = true;
                setTimeout(function() { self.copied = false; }, 2400);
            };
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(this.output).then(done).catch(function() {
                    self._execCopy();
                    done();
                });
            } else {
                this._execCopy();
                done();
            }
        },

        _execCopy() {
            var el        = document.createElement('textarea');
            el.value      = this.output;
            el.style.cssText = 'position:fixed;top:0;left:0;opacity:0;pointer-events:none';
            document.body.appendChild(el);
            el.focus();
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
        },

        download() {
            if (!this.output) return;
            var name = 'ascii-' + this.font.toLowerCase().replace(/\s+/g, '-') + '.txt';
            var blob = new Blob([this.output], { type: 'text/plain;charset=utf-8' });
            var url  = URL.createObjectURL(blob);
            var a    = document.createElement('a');
            a.href     = url;
            a.download = name;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        },

        reset() {
            clearTimeout(this._debounce);
            this.text        = '';
            this.font        = 'Standard';
            this.width       = 80;
            this.align       = 'left';
            this.fillChar    = '';
            this.output      = '';
            this.inputError  = '';
            this.renderError = '';
            this.fontSearch  = '';
            this.previewSize = 'sm';
        },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\ascii-art-generator.blade.php ENDPATH**/ ?>