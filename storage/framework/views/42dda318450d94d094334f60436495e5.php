<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ── Category tab ── */
.cat-tab {
    padding: .35rem .75rem;
    border-radius: .75rem;
    border: 1.5px solid;
    font-size: .72rem;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
    transition: all .12s;
}

/* ── Font pill ── */
.fp-pill {
    padding: .3rem .7rem;
    border-radius: .75rem;
    border: 1.5px solid;
    font-size: .75rem;
    font-weight: 500;
    cursor: pointer;
    transition: all .12s;
    white-space: nowrap;
    line-height: 1.6;
}

/* ── Weight cell ── */
.w-cell {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: .35rem .2rem;
    border-radius: .6rem;
    border: 1.5px solid;
    cursor: pointer;
    transition: all .12s;
    gap: .1rem;
}

/* ── Toggle group button ── */
.tg-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: .55rem .4rem;
    border: 1.5px solid;
    font-size: .78rem;
    font-weight: 600;
    cursor: pointer;
    transition: all .12s;
    gap: .3rem;
}
.tg-btn:first-child { border-radius: .875rem 0 0 .875rem; }
.tg-btn:last-child  { border-radius: 0 .875rem .875rem 0; }
.tg-btn:not(:first-child) { border-left-width: 0; }

/* ── Color swatch trigger ── */
.color-swatch {
    width: 2.4rem; height: 2.4rem;
    border-radius: .75rem;
    border: 2px solid #e5e7eb;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    flex-shrink: 0;
    transition: border-color .15s;
}
.color-swatch:hover { border-color: #4f46e5; }
.color-swatch input[type="color"] {
    position: absolute; inset: 0;
    width: 130%; height: 130%;
    top: -15%; left: -15%;
    opacity: 0; cursor: pointer;
    border: none; padding: 0;
}

/* ── Preset palette dot ── */
.palette-dot {
    width: 1.4rem; height: 1.4rem;
    border-radius: 50%;
    cursor: pointer;
    border: 2px solid transparent;
    transition: transform .12s, border-color .12s;
    flex-shrink: 0;
}
.palette-dot:hover { transform: scale(1.15); }

/* ── Live preview box ── */
.preview-box {
    min-height: 200px;
    padding: 2rem;
    border-radius: 1rem;
    overflow: auto;
    transition: all .2s ease;
    word-break: break-word;
    white-space: pre-wrap;
    max-height: 420px;
}

/* ── CSS code output ── */
.css-code {
    background: #0f172a;
    color: #e2e8f0;
    border-radius: .75rem;
    padding: 1.25rem 1.5rem;
    font-family: 'Courier New', Courier, monospace;
    font-size: .8rem;
    line-height: 1.8;
    overflow-x: auto;
    white-space: pre;
}
.css-prop  { color: #93c5fd; }   /* blue  — property name */
.css-colon { color: #94a3b8; }   /* slate — colon */
.css-val   { color: #a5f3fc; }   /* cyan  — value */
.css-semi  { color: #94a3b8; }   /* slate — semicolon */

/* ── Scrollbar on font list ── */
.font-scroll::-webkit-scrollbar { width: 4px; }
.font-scroll::-webkit-scrollbar-track { background: transparent; }
.font-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 9999px; }

/* ── Flash ── */
@keyframes flashGreen {
    0%,100% { background: #f0fdf4; color: #166534; border-color: #86efac; }
    50%      { background: #dcfce7; color: #14532d; border-color: #4ade80; }
}
.copy-flash { animation: flashGreen .6s ease 3; }

/* ── Range input ── */
input[type="range"] { accent-color: #4f46e5; cursor: pointer; }
</style>

<div class="min-h-screen bg-gray-50"
     x-data="fontPreview()"
     x-init="init()">

    
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                <?php echo e($tool->icon); ?> <?php echo e($tool->name); ?>

            </h1>
            <p class="text-gray-500 mt-2"><?php echo e($tool->short_description); ?></p>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
        <div class="grid grid-cols-1 xl:grid-cols-5 gap-6 items-start">

            
            <div class="xl:col-span-2 space-y-4">

                
                <div class="card p-5">
                    <label class="form-label">Preview Text</label>
                    <textarea x-model="previewText"
                              rows="3"
                              placeholder="Type your preview text…"
                              class="form-input font-medium resize-y"
                              maxlength="500"></textarea>
                    <div class="flex gap-1.5 flex-wrap mt-2">
                        <span class="text-xs text-gray-400 self-center shrink-0">Samples:</span>
                        <template x-for="s in SAMPLES" :key="s.label">
                            <button type="button"
                                    @click="previewText = s.text"
                                    class="px-2.5 py-1 rounded-lg border border-gray-200 text-xs text-gray-500 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all">
                                <span x-text="s.label"></span>
                            </button>
                        </template>
                    </div>
                </div>

                
                <div class="card p-5">
                    <div class="flex items-center justify-between mb-3">
                        <label class="form-label mb-0">Font Family</label>
                        <span class="badge badge-primary text-xs max-w-[130px] truncate" x-text="fontFamily"></span>
                    </div>

                    
                    <div class="flex gap-1.5 flex-wrap mb-3">
                        <template x-for="c in CATS" :key="c.id">
                            <button type="button"
                                    @click="activeCat = c.id; fontSearch = ''"
                                    class="cat-tab"
                                    :class="activeCat === c.id
                                        ? 'border-brand-500 bg-brand-50 text-brand-700'
                                        : 'border-gray-200 bg-white text-gray-500 hover:border-gray-300'">
                                <span x-text="c.label"></span>
                            </button>
                        </template>
                    </div>

                    
                    <input type="text"
                           x-model="fontSearch"
                           placeholder="Search fonts…"
                           class="form-input mb-3">

                    
                    <div class="font-scroll max-h-52 overflow-y-auto space-y-3 pr-0.5">
                        <template x-for="g in filteredGroups" :key="g.group">
                            <div x-show="g.fonts.length > 0">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1.5"
                                   x-text="g.group"></p>
                                <div class="flex flex-wrap gap-1.5">
                                    <template x-for="f in g.fonts" :key="f">
                                        <button type="button"
                                                @click="selectFont(f, g.system)"
                                                class="fp-pill"
                                                :class="fontFamily === f
                                                    ? 'border-brand-500 bg-brand-50 text-brand-700'
                                                    : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300 hover:bg-gray-50'">
                                            <span x-text="f"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                        <div x-show="totalVisible === 0"
                             class="py-6 text-center text-sm text-gray-400">
                            No fonts match "<span x-text="fontSearch"></span>"
                        </div>
                    </div>
                </div>

                
                <div class="card p-5 space-y-5">
                    <h3 class="text-sm font-semibold text-gray-800 -mb-1">Typography</h3>

                    
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="form-label mb-0">Font Size</label>
                            <div class="flex items-center gap-1.5">
                                <input type="number"
                                       x-model.number="fontSize"
                                       min="8" max="200"
                                       class="w-16 text-center form-input py-1 font-bold text-sm"
                                       @change="fontSize = Math.min(200, Math.max(8, fontSize || 16))">
                                <span class="text-xs text-gray-400 font-medium">px</span>
                            </div>
                        </div>
                        <input type="range" x-model.number="fontSize" min="8" max="120" step="1" class="w-full">
                        <div class="flex gap-1 flex-wrap mt-2">
                            <template x-for="s in SIZE_PRESETS" :key="s">
                                <button type="button"
                                        @click="fontSize = s"
                                        class="px-2 py-0.5 rounded-md border text-xs font-medium transition-all"
                                        :class="fontSize === s
                                            ? 'border-brand-400 bg-brand-50 text-brand-700'
                                            : 'border-gray-200 text-gray-500 hover:border-gray-300'">
                                    <span x-text="s"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    
                    <div>
                        <label class="form-label">Font Weight</label>
                        <div class="grid grid-cols-9 gap-1">
                            <template x-for="w in WEIGHTS" :key="w.val">
                                <button type="button"
                                        @click="fontWeight = w.val"
                                        class="w-cell"
                                        :class="fontWeight === w.val
                                            ? 'border-brand-500 bg-brand-50'
                                            : 'border-gray-200 bg-white hover:border-gray-300'">
                                    <span class="text-[11px] leading-none"
                                          :style="'font-weight:' + w.val"
                                          :class="fontWeight === w.val ? 'text-brand-700' : 'text-gray-700'"
                                          x-text="w.val"></span>
                                    <span class="text-[9px] leading-none text-gray-400"
                                          x-text="w.short"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Style</label>
                            <div class="flex">
                                <button type="button"
                                        @click="fontStyle = 'normal'"
                                        class="tg-btn"
                                        :class="fontStyle === 'normal'
                                            ? 'border-brand-500 bg-brand-50 text-brand-700'
                                            : 'border-gray-200 bg-white text-gray-600 hover:bg-gray-50'">
                                    N
                                </button>
                                <button type="button"
                                        @click="fontStyle = 'italic'"
                                        class="tg-btn"
                                        :class="fontStyle === 'italic'
                                            ? 'border-brand-500 bg-brand-50 text-brand-700'
                                            : 'border-gray-200 bg-white text-gray-600 hover:bg-gray-50'">
                                    <em>I</em>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Align</label>
                            <div class="flex">
                                <template x-for="a in ALIGNS" :key="a.val">
                                    <button type="button"
                                            @click="textAlign = a.val"
                                            class="tg-btn"
                                            :class="textAlign === a.val
                                                ? 'border-brand-500 bg-brand-50 text-brand-700'
                                                : 'border-gray-200 bg-white text-gray-600 hover:bg-gray-50'"
                                            :title="a.label">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <template x-if="a.val === 'left'">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8M4 18h12"/>
                                            </template>
                                            <template x-if="a.val === 'center'">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M7 12h10M5 18h14"/>
                                            </template>
                                            <template x-if="a.val === 'right'">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M12 12h8M8 18h12"/>
                                            </template>
                                            <template x-if="a.val === 'justify'">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                            </template>
                                        </svg>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Decoration</label>
                            <div class="flex flex-col gap-1.5">
                                <template x-for="d in DECORATIONS" :key="d.val">
                                    <button type="button"
                                            @click="textDecoration = d.val"
                                            class="px-2.5 py-1.5 rounded-xl border text-xs font-medium transition-all text-left"
                                            :class="textDecoration === d.val
                                                ? 'border-brand-500 bg-brand-50 text-brand-700'
                                                : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'">
                                        <span :style="d.val !== 'none' ? 'text-decoration:' + d.val : ''"
                                              x-text="d.label"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Transform</label>
                            <div class="flex flex-col gap-1.5">
                                <template x-for="t in TRANSFORMS" :key="t.val">
                                    <button type="button"
                                            @click="textTransform = t.val"
                                            class="px-2.5 py-1.5 rounded-xl border text-xs font-medium transition-all text-left"
                                            :class="textTransform === t.val
                                                ? 'border-brand-500 bg-brand-50 text-brand-700'
                                                : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'">
                                        <span :style="t.val !== 'none' ? 'text-transform:' + t.val : ''"
                                              x-text="t.label"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="card p-5 space-y-4">
                    <h3 class="text-sm font-semibold text-gray-800">Spacing</h3>

                    
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="form-label mb-0">Line Height</label>
                            <span class="text-sm font-bold text-indigo-600" x-text="lineHeight"></span>
                        </div>
                        <input type="range" x-model.number="lineHeight" min="0.8" max="3" step="0.1" class="w-full">
                        <div class="flex justify-between text-xs text-gray-400 mt-1">
                            <span>0.8 (tight)</span><span>3.0 (loose)</span>
                        </div>
                    </div>

                    
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="form-label mb-0">Letter Spacing</label>
                            <span class="text-sm font-bold text-indigo-600" x-text="letterSpacing + 'px'"></span>
                        </div>
                        <input type="range" x-model.number="letterSpacing" min="-5" max="20" step="0.5" class="w-full">
                        <div class="flex justify-between text-xs text-gray-400 mt-1">
                            <span>−5px (tight)</span><span>+20px (wide)</span>
                        </div>
                    </div>
                </div>

                
                <div class="card p-5 space-y-4">
                    <h3 class="text-sm font-semibold text-gray-800">Colors</h3>

                    
                    <div>
                        <label class="form-label">Text Color</label>
                        <div class="flex items-center gap-2.5">
                            <label class="color-swatch" :style="'background:' + textColor">
                                <input type="color" x-model="textColor">
                            </label>
                            <input type="text"
                                   x-model="textColor"
                                   @blur="textColor = sanitizeHex(textColor, '#111827')"
                                   maxlength="7"
                                   placeholder="#111827"
                                   class="form-input w-28 font-mono text-sm uppercase tracking-wider">
                        </div>
                        <div class="flex gap-1.5 flex-wrap mt-2">
                            <template x-for="c in TEXT_PALETTE" :key="c">
                                <button type="button"
                                        @click="textColor = c"
                                        class="palette-dot"
                                        :style="'background:' + c + ';' + (textColor === c ? 'border-color:#4f46e5;transform:scale(1.15)' : 'border-color:transparent')"
                                        :title="c">
                                </button>
                            </template>
                        </div>
                    </div>

                    
                    <div>
                        <label class="form-label">Background Color</label>
                        <div class="flex items-center gap-2.5">
                            <label class="color-swatch"
                                   style="background-image: linear-gradient(45deg, #ccc 25%, transparent 25%), linear-gradient(-45deg, #ccc 25%, transparent 25%), linear-gradient(45deg, transparent 75%, #ccc 75%), linear-gradient(-45deg, transparent 75%, #ccc 75%); background-size: 10px 10px; background-position: 0 0, 0 5px, 5px -5px, -5px 0px;">
                                <span class="absolute inset-0 rounded-xl" :style="'background:' + bgColor"></span>
                                <input type="color" x-model="bgColor">
                            </label>
                            <input type="text"
                                   x-model="bgColor"
                                   @blur="bgColor = sanitizeHex(bgColor, '#ffffff')"
                                   maxlength="7"
                                   placeholder="#ffffff"
                                   class="form-input w-28 font-mono text-sm uppercase tracking-wider">
                        </div>
                        <div class="flex gap-1.5 flex-wrap mt-2">
                            <template x-for="c in BG_PALETTE" :key="c">
                                <button type="button"
                                        @click="bgColor = c"
                                        class="palette-dot"
                                        :style="'background:' + c + ';box-shadow:inset 0 0 0 1px rgba(0,0,0,.1);' + (bgColor === c ? 'border-color:#4f46e5;transform:scale(1.15)' : 'border-color:transparent')"
                                        :title="c">
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                
                <div class="flex gap-3 flex-wrap">
                    <button type="button"
                            @click="copyCSS()"
                            :class="copiedCSS ? 'copy-flash' : ''"
                            class="btn btn-primary flex-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <span x-text="copiedCSS ? 'Copied!' : 'Copy CSS'"></span>
                    </button>
                    <button type="button"
                            @click="downloadCSS()"
                            class="btn btn-secondary flex-1">
                        ⬇ Download
                    </button>
                    <button type="button"
                            @click="resetAll()"
                            class="btn btn-secondary">
                        Reset
                    </button>
                </div>

            </div>

            
            <div class="xl:col-span-3 space-y-4 xl:sticky xl:top-6">

                
                <div class="card overflow-hidden">
                    <div class="flex items-center gap-3 px-5 py-3 border-b border-gray-100 flex-wrap gap-y-2">
                        <span class="text-sm font-semibold text-gray-700 flex-1">Live Preview</span>
                        <span class="badge badge-gray" x-text="fontFamily"></span>
                        <span class="badge badge-gray" x-text="fontSize + 'px'"></span>
                        <span class="badge badge-gray" x-text="'w' + fontWeight"></span>
                    </div>

                    
                    <div class="p-4" style="background-image:linear-gradient(45deg,#f1f1f1 25%,transparent 25%),linear-gradient(-45deg,#f1f1f1 25%,transparent 25%),linear-gradient(45deg,transparent 75%,#f1f1f1 75%),linear-gradient(-45deg,transparent 75%,#f1f1f1 75%);background-size:16px 16px;background-position:0 0,0 8px,8px -8px,-8px 0;">
                        <div class="preview-box"
                             :style="previewStyle"
                             x-text="previewText || 'The quick brown fox jumps over the lazy dog'">
                        </div>
                    </div>

                    
                    <div class="px-5 py-3 border-t border-gray-100 bg-gray-50 flex flex-wrap gap-x-5 gap-y-1 text-xs text-gray-500">
                        <span><strong class="text-gray-700">Size:</strong> <span x-text="fontSize + 'px'"></span></span>
                        <span><strong class="text-gray-700">Weight:</strong> <span x-text="fontWeight"></span></span>
                        <span><strong class="text-gray-700">Style:</strong> <span x-text="fontStyle"></span></span>
                        <span><strong class="text-gray-700">Line height:</strong> <span x-text="lineHeight"></span></span>
                        <span><strong class="text-gray-700">Spacing:</strong> <span x-text="letterSpacing + 'px'"></span></span>
                    </div>
                </div>

                
                <div class="card overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100">
                        <span class="text-sm font-semibold text-gray-700">Generated CSS</span>
                        <button type="button"
                                @click="copyCSS()"
                                class="btn btn-secondary btn-sm"
                                :class="copiedCSS ? 'copy-flash' : ''">
                            <span x-text="copiedCSS ? '✓ Copied' : '📋 Copy'"></span>
                        </button>
                    </div>

                    <div class="css-code" x-html="highlightedCSS"></div>
                </div>

                
                <div class="card p-5">
                    <h3 class="font-semibold text-gray-800 text-sm mb-3">💡 Tips</h3>
                    <div class="grid sm:grid-cols-2 gap-2 text-xs text-gray-500">
                        <div class="flex gap-2">
                            <span class="text-indigo-400 font-bold shrink-0">→</span>
                            Pick a <strong class="text-gray-700">font category</strong> to narrow the list, or search by name.
                        </div>
                        <div class="flex gap-2">
                            <span class="text-indigo-400 font-bold shrink-0">→</span>
                            <strong class="text-gray-700">Display fonts</strong> look great at large sizes (48px+).
                        </div>
                        <div class="flex gap-2">
                            <span class="text-indigo-400 font-bold shrink-0">→</span>
                            Increase <strong class="text-gray-700">letter spacing</strong> for headings, decrease it for body text.
                        </div>
                        <div class="flex gap-2">
                            <span class="text-indigo-400 font-bold shrink-0">→</span>
                            Body text looks best at <strong class="text-gray-700">line-height 1.5–1.8</strong>.
                        </div>
                        <div class="flex gap-2 sm:col-span-2">
                            <span class="text-indigo-400 font-bold shrink-0">→</span>
                            Click <strong class="text-gray-700">Copy CSS</strong> to grab the generated styles and paste into your project.
                        </div>
                    </div>
                </div>

                
                <?php if($relatedTools->count()): ?>
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Related Tools</h3>
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
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
/* ══════════════════════════════════════════════════════════
   FONT PREVIEW TOOL  —  Alpine.js component
══════════════════════════════════════════════════════════ */
function fontPreview() {
    return {
        /* ── Controls ── */
        previewText:    'The quick brown fox jumps over the lazy dog',
        fontFamily:     'Inter',
        fontSize:       32,
        fontWeight:     '400',
        fontStyle:      'normal',
        lineHeight:     1.5,
        letterSpacing:  0,
        textColor:      '#111827',
        bgColor:        '#ffffff',
        textAlign:      'left',
        textDecoration: 'none',
        textTransform:  'none',

        /* ── UI state ── */
        activeCat:   'all',
        fontSearch:  '',
        copiedCSS:   false,
        _loadedFonts: {},

        /* ── Static data ── */
        SAMPLES: [
            { label: 'Pangram',   text: 'The quick brown fox jumps over the lazy dog' },
            { label: 'Short',     text: 'Pack my box with five dozen liquor jugs' },
            { label: 'Numbers',   text: '0123456789 — À la carte: €49.95 / £39 / $52' },
            { label: 'Headline',  text: 'The Future of Typography' },
            { label: 'Paragraph', text: 'Great typography is invisible — it communicates without distraction, letting ideas shine through. Every typeface tells a story before a single word is read.' },
        ],

        CATS: [
            { id: 'all',         label: 'All' },
            { id: 'system',      label: 'System' },
            { id: 'sans',        label: 'Sans' },
            { id: 'serif',       label: 'Serif' },
            { id: 'mono',        label: 'Mono' },
            { id: 'display',     label: 'Display' },
            { id: 'handwriting', label: 'Script' },
        ],

        FONT_GROUPS: [
            {
                id: 'system', group: 'System Fonts', system: true,
                fonts: ['Arial', 'Verdana', 'Georgia', 'Times New Roman', 'Courier New', 'Trebuchet MS', 'Impact'],
            },
            {
                id: 'sans', group: 'Sans-Serif',
                fonts: ['Inter', 'Roboto', 'Open Sans', 'Lato', 'Montserrat', 'Poppins', 'Raleway', 'Nunito', 'Oswald', 'Rubik', 'Ubuntu', 'DM Sans', 'Manrope', 'Work Sans', 'Cabin', 'Karla', 'Mulish', 'Quicksand', 'Exo 2', 'Barlow'],
            },
            {
                id: 'serif', group: 'Serif',
                fonts: ['Playfair Display', 'Merriweather', 'Lora', 'EB Garamond', 'Libre Baskerville', 'PT Serif', 'Cormorant Garamond', 'Domine', 'Bitter', 'Zilla Slab'],
            },
            {
                id: 'mono', group: 'Monospace',
                fonts: ['Source Code Pro', 'JetBrains Mono', 'Fira Code', 'IBM Plex Mono', 'Space Mono', 'Roboto Mono', 'Inconsolata', 'PT Mono'],
            },
            {
                id: 'display', group: 'Display',
                fonts: ['Bebas Neue', 'Righteous', 'Alfa Slab One', 'Boogaloo', 'Fredoka One', 'Bangers', 'Passion One', 'Titan One', 'Russo One'],
            },
            {
                id: 'handwriting', group: 'Script / Handwriting',
                fonts: ['Dancing Script', 'Caveat', 'Pacifico', 'Lobster', 'Great Vibes', 'Kalam', 'Satisfy', 'Sacramento'],
            },
        ],

        WEIGHTS: [
            { val: '100', short: 'Thin'  },
            { val: '200', short: 'ExLt'  },
            { val: '300', short: 'Light' },
            { val: '400', short: 'Reg'   },
            { val: '500', short: 'Med'   },
            { val: '600', short: 'SBd'   },
            { val: '700', short: 'Bold'  },
            { val: '800', short: 'ExBd'  },
            { val: '900', short: 'Black' },
        ],

        ALIGNS: [
            { val: 'left',    label: 'Left'    },
            { val: 'center',  label: 'Center'  },
            { val: 'right',   label: 'Right'   },
            { val: 'justify', label: 'Justify' },
        ],

        DECORATIONS: [
            { val: 'none',         label: 'None'           },
            { val: 'underline',    label: 'Underline'      },
            { val: 'line-through', label: 'Strikethrough'  },
        ],

        TRANSFORMS: [
            { val: 'none',       label: 'None'       },
            { val: 'uppercase',  label: 'UPPERCASE'  },
            { val: 'lowercase',  label: 'lowercase'  },
            { val: 'capitalize', label: 'Capitalize' },
        ],

        SIZE_PRESETS: [12, 14, 16, 18, 24, 32, 48, 64, 96],

        TEXT_PALETTE: [
            '#111827', '#374151', '#4f46e5', '#1d4ed8', '#0f766e',
            '#15803d', '#b45309', '#dc2626', '#9333ea', '#ffffff',
        ],

        BG_PALETTE: [
            '#ffffff', '#f8fafc', '#f1f5f9', '#0f172a', '#111827',
            '#fef3c7', '#ecfdf5', '#eff6ff', '#fdf2f8', '#fafaf5',
        ],

        /* ══════════════════════════════════
           COMPUTED GETTERS
        ══════════════════════════════════ */

        get filteredGroups() {
            var q   = this.fontSearch.toLowerCase().trim();
            var cat = this.activeCat;
            return this.FONT_GROUPS
                .filter(function(g) { return cat === 'all' || g.id === cat; })
                .map(function(g) {
                    return {
                        group:  g.group,
                        system: g.system || false,
                        fonts:  q ? g.fonts.filter(function(f) {
                            return f.toLowerCase().indexOf(q) !== -1;
                        }) : g.fonts.slice(),
                    };
                })
                .filter(function(g) { return g.fonts.length > 0; });
        },

        get totalVisible() {
            return this.filteredGroups.reduce(function(s, g) { return s + g.fonts.length; }, 0);
        },

        get fontFamilyCSS() {
            var isSystem = this._isSystemFont(this.fontFamily);
            var name = isSystem ? this.fontFamily : "'" + this.fontFamily + "'";
            var fallback = this._getFallback(this.fontFamily);
            return name + ', ' + fallback;
        },

        get previewStyle() {
            return [
                'font-family:'     + this.fontFamilyCSS,
                'font-size:'       + this.fontSize        + 'px',
                'font-weight:'     + this.fontWeight,
                'font-style:'      + this.fontStyle,
                'line-height:'     + this.lineHeight,
                'letter-spacing:'  + this.letterSpacing   + 'px',
                'color:'           + this.textColor,
                'background-color:'+ this.bgColor,
                'text-align:'      + this.textAlign,
                'text-decoration:' + this.textDecoration,
                'text-transform:'  + this.textTransform,
            ].join(';');
        },

        get rawCSS() {
            return [
                'font-family: '      + this.fontFamilyCSS            + ';',
                'font-size: '        + this.fontSize        + 'px;',
                'font-weight: '      + this.fontWeight               + ';',
                'font-style: '       + this.fontStyle                + ';',
                'line-height: '      + this.lineHeight               + ';',
                'letter-spacing: '   + this.letterSpacing  + 'px;',
                'color: '            + this.textColor                + ';',
                'background-color: ' + this.bgColor                  + ';',
                'text-align: '       + this.textAlign                + ';',
                'text-decoration: '  + this.textDecoration           + ';',
                'text-transform: '   + this.textTransform            + ';',
            ].join('\n');
        },

        get highlightedCSS() {
            /* Syntax-highlight each property line for the code block */
            var lines = this.rawCSS.split('\n');
            return lines.map(function(line) {
                var m = line.match(/^([^:]+)(:)\s*(.+?)(;)$/);
                if (!m) return escHtml(line);
                return '<span class="css-prop">'  + escHtml(m[1]) + '</span>'
                     + '<span class="css-colon">' + m[2]          + ' </span>'
                     + '<span class="css-val">'   + escHtml(m[3]) + '</span>'
                     + '<span class="css-semi">'  + m[4]          + '</span>';
            }).join('\n');

            function escHtml(s) {
                return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
            }
        },

        /* ══════════════════════════════════
           INIT
        ══════════════════════════════════ */

        init() {
            /* Preload most common Google Fonts */
            var starter = ['Inter', 'Roboto', 'Poppins', 'Montserrat', 'Playfair Display'];
            var self = this;
            starter.forEach(function(f) { self._loadGoogleFont(f); });
        },

        /* ══════════════════════════════════
           FONT SELECTION
        ══════════════════════════════════ */

        selectFont(name, isSystem) {
            this.fontFamily = name;
            if (!isSystem) {
                this._loadGoogleFont(name);
            }
        },

        _loadGoogleFont(name) {
            if (this._loadedFonts[name]) return;
            this._loadedFonts[name] = true;

            var slug = name.replace(/ /g, '+');
            var url  = 'https://fonts.googleapis.com/css2?family='
                + slug
                + ':ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,700&display=swap';

            var link  = document.createElement('link');
            link.rel  = 'stylesheet';
            link.href = url;
            document.head.appendChild(link);
        },

        _isSystemFont(name) {
            var sysGroup = this.FONT_GROUPS.find(function(g) { return g.id === 'system'; });
            return sysGroup && sysGroup.fonts.indexOf(name) !== -1;
        },

        _getFallback(name) {
            var group = null;
            var self  = this;
            this.FONT_GROUPS.forEach(function(g) {
                if (g.fonts.indexOf(name) !== -1) group = g;
            });
            if (!group) return 'sans-serif';
            var map = {
                system:      name === 'Georgia' || name === 'Times New Roman' ? 'serif' : 'sans-serif',
                sans:        'sans-serif',
                serif:       'serif',
                mono:        'monospace',
                display:     'fantasy',
                handwriting: 'cursive',
            };
            return map[group.id] || 'sans-serif';
        },

        /* ══════════════════════════════════
           COPY / DOWNLOAD
        ══════════════════════════════════ */

        copyCSS() {
            var self = this;
            var done = function() {
                self.copiedCSS = true;
                setTimeout(function() { self.copiedCSS = false; }, 2400);
            };
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(this.rawCSS).then(done).catch(function() {
                    self._execCopy(self.rawCSS); done();
                });
            } else {
                this._execCopy(this.rawCSS); done();
            }
        },

        _execCopy(text) {
            var el        = document.createElement('textarea');
            el.value      = text;
            el.style.cssText = 'position:fixed;top:0;left:0;opacity:0;pointer-events:none';
            document.body.appendChild(el);
            el.focus(); el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
        },

        downloadCSS() {
            var selector = '.' + this.fontFamily.toLowerCase().replace(/\s+/g, '-');
            var content  = selector + ' {\n'
                + this.rawCSS.split('\n').map(function(l) { return '  ' + l; }).join('\n')
                + '\n}';
            var blob = new Blob([content], { type: 'text/css;charset=utf-8' });
            var url  = URL.createObjectURL(blob);
            var a    = document.createElement('a');
            a.href     = url;
            a.download = 'font-style.css';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        },

        /* ══════════════════════════════════
           UTILITIES
        ══════════════════════════════════ */

        sanitizeHex(val, fallback) {
            val = val.trim();
            if (!val.startsWith('#')) val = '#' + val;
            return /^#[0-9A-Fa-f]{6}$/.test(val) ? val.toLowerCase() : fallback;
        },

        resetAll() {
            this.previewText    = 'The quick brown fox jumps over the lazy dog';
            this.fontFamily     = 'Inter';
            this.fontSize       = 32;
            this.fontWeight     = '400';
            this.fontStyle      = 'normal';
            this.lineHeight     = 1.5;
            this.letterSpacing  = 0;
            this.textColor      = '#111827';
            this.bgColor        = '#ffffff';
            this.textAlign      = 'left';
            this.textDecoration = 'none';
            this.textTransform  = 'none';
            this.fontSearch     = '';
            this.activeCat      = 'all';
        },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\font-preview-tool.blade.php ENDPATH**/ ?>