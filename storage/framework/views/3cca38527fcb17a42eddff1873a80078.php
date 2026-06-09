<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ══════════════════════════════════════
   Statistics Calculator  —  prefix: st-
══════════════════════════════════════ */

/* Big gradient number */
.st-big {
    font-size: 2rem; font-weight: 900; line-height: 1;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text; word-break: break-all;
}
.st-big.green  { background: linear-gradient(135deg,#059669,#10b981); -webkit-background-clip:text; background-clip:text; }
.st-big.amber  { background: linear-gradient(135deg,#d97706,#f59e0b); -webkit-background-clip:text; background-clip:text; }
.st-big.rose   { background: linear-gradient(135deg,#e11d48,#f43f5e); -webkit-background-clip:text; background-clip:text; }
.st-big.cyan   { background: linear-gradient(135deg,#0891b2,#06b6d4); -webkit-background-clip:text; background-clip:text; }
.st-big.slate  { background: linear-gradient(135deg,#475569,#64748b); -webkit-background-clip:text; background-clip:text; }
.st-big.violet { background: linear-gradient(135deg,#7c3aed,#a855f7); -webkit-background-clip:text; background-clip:text; }
.st-big.teal   { background: linear-gradient(135deg,#0d9488,#14b8a6); -webkit-background-clip:text; background-clip:text; }
.st-big.orange { background: linear-gradient(135deg,#ea580c,#f97316); -webkit-background-clip:text; background-clip:text; }

/* Stat card */
.st-stat {
    background: #fff; border: 1.5px solid #e2e8f0; border-radius: 1.125rem;
    padding: 1.25rem 1rem; display: flex; flex-direction: column;
    align-items: center; gap: .4rem; text-align: center;
    transition: border-color .15s, box-shadow .15s, transform .15s;
}
.st-stat:hover { border-color:#a5b4fc; box-shadow:0 4px 16px rgba(79,70,229,.08); transform:translateY(-1px); }
.st-label  { font-size:.68rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.07em; }
.st-sublabel { font-size:.7rem; color:#94a3b8; }
.st-mode-text { font-size:1rem; font-weight:700; color:#475569; line-height:1.2; word-break:break-all; }

/* Number pill (sorted list) */
.st-pill {
    display:inline-flex; align-items:center; padding:.25rem .65rem; border-radius:9999px;
    font-size:.75rem; font-weight:500; font-variant-numeric:tabular-nums;
    background:#f1f5f9; color:#334155; border:1px solid #e2e8f0; transition:background .12s;
}
.st-pill:hover { background:#e0e9ff; border-color:#a5b4fc; color:#3730a3; }
.st-pill.is-min { background:#fef3c7; border-color:#fcd34d; color:#92400e; }
.st-pill.is-max { background:#dcfce7; border-color:#86efac; color:#14532d; }
.st-pill.is-q1  { background:#e0f2fe; border-color:#7dd3fc; color:#075985; }
.st-pill.is-q3  { background:#fae8ff; border-color:#e879f9; color:#701a75; }
.st-pill.is-med { background:#ede9fe; border-color:#a78bfa; color:#3730a3; font-weight:700; }

/* Invalid token */
.st-invalid-token {
    display:inline-flex; align-items:center; padding:.2rem .55rem; border-radius:.5rem;
    background:#fef2f2; border:1px solid #fca5a5; color:#b91c1c; font-size:.75rem;
    font-weight:600; font-family:monospace;
}

/* Shimmer */
@keyframes stShimmer { 0%{background-position:-600px 0} 100%{background-position:600px 0} }
.st-shimmer {
    height:5rem; border-radius:1.125rem;
    background:linear-gradient(90deg,#f0f4f8 25%,#e2e8f0 50%,#f0f4f8 75%);
    background-size:1200px 100%; animation:stShimmer 1.4s infinite;
}

/* Entrance animation */
@keyframes stIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
.st-in { animation: stIn .28s ease-out; }

/* Section divider */
.st-divider {
    display:flex; align-items:center; gap:.75rem;
    color:#94a3b8; font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.09em;
}
.st-divider::before, .st-divider::after { content:''; flex:1; height:1px; background:#e2e8f0; }

/* ── Box Plot ── */
.st-bp-wrap    { position:relative; padding:2rem 0 1.75rem; user-select:none; }
.st-bp-track   { position:relative; height:28px; }
.st-bp-axis    { position:absolute; top:50%; left:0; right:0; height:2px; background:#e2e8f0; transform:translateY(-50%); border-radius:9999px; }
.st-bp-whisker { position:absolute; top:50%; height:2px; background:#94a3b8; transform:translateY(-50%); }
.st-bp-box     { position:absolute; top:0; bottom:0; background:rgba(79,70,229,.12); border:2px solid #4f46e5; border-radius:4px; }
.st-bp-med     { position:absolute; top:-2px; bottom:-2px; width:3px; background:#4f46e5; border-radius:2px; transform:translateX(-50%); }
.st-bp-dot     { position:absolute; top:50%; width:10px; height:10px; border-radius:9999px; transform:translate(-50%,-50%); }
.st-bp-dot.min { background:#f59e0b; border:2px solid #fff; box-shadow:0 0 0 1.5px #f59e0b; }
.st-bp-dot.max { background:#10b981; border:2px solid #fff; box-shadow:0 0 0 1.5px #10b981; }
.st-bp-dot.med { background:#4f46e5; border:2px solid #fff; box-shadow:0 0 0 1.5px #4f46e5; z-index:2; }
/* labels */
.st-bp-labels  { position:relative; height:1.6rem; margin-top:.3rem; }
.st-bp-lbl     { position:absolute; font-size:.65rem; font-weight:600; transform:translateX(-50%); white-space:nowrap; }
.st-bp-lbl.min { color:#d97706; }
.st-bp-lbl.q1  { color:#0891b2; }
.st-bp-lbl.med { color:#4f46e5; }
.st-bp-lbl.q3  { color:#7c3aed; }
.st-bp-lbl.max { color:#059669; }

/* ── Frequency table ── */
.st-freq-bar  { height:8px; border-radius:9999px; background:linear-gradient(90deg,#4f46e5,#7c3aed); transition:width .4s; }
.st-freq-row  { display:grid; grid-template-columns:auto 1fr auto; gap:.75rem; align-items:center; padding:.35rem 0; border-bottom:1px solid #f3f4f6; }
.st-freq-val  { font-size:.78rem; font-weight:700; color:#374151; font-variant-numeric:tabular-nums; min-width:3.5rem; }
.st-freq-pct  { font-size:.72rem; color:#6b7280; min-width:3rem; text-align:right; }

/* Textarea */
.st-textarea { font-family:'Inter',ui-monospace,monospace; font-size:.92rem; line-height:1.7; resize:vertical; }

/* Toggle switch */
.st-toggle { position:relative; display:inline-flex; align-items:center; height:20px; width:36px; border-radius:9999px; transition:background .2s; cursor:pointer; flex-shrink:0; }
.st-toggle-knob { position:absolute; width:14px; height:14px; background:#fff; border-radius:9999px; box-shadow:0 1px 3px rgba(0,0,0,.2); transition:transform .2s; }
</style>

<div class="min-h-screen bg-gray-50"
     x-data="statCalc()"
     x-init="init()">

    
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-8 md:py-10">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                        <?php echo e($tool->icon); ?> <?php echo e($tool->name); ?>

                    </h1>
                    <p class="text-gray-500 mt-2 max-w-2xl">
                        Enter numbers to instantly compute <strong>mean, median, mode, quartiles, IQR, standard deviation, variance</strong>, and more — separated by commas, spaces, or new lines.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2 shrink-0">
                    <span class="badge badge-success">Free</span>
                    <span class="badge badge-gray">No Account</span>
                    <span class="badge badge-primary">15+ Statistics</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-6 md:py-8 space-y-5">

        
        <div class="card">
            <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-brand-400"></span>
                    <span class="font-semibold text-gray-800 text-sm">Enter Numbers</span>
                </div>
                <div class="flex items-center gap-2" x-show="parsed.valid.length > 0">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-brand-100 text-brand-700">
                        <span x-text="parsed.valid.length"></span>&nbsp;number<span x-show="parsed.valid.length !== 1">s</span>
                    </span>
                </div>
            </div>

            <div class="p-5 space-y-4">

                <div>
                    <label class="form-label">Numbers <span class="text-gray-400 font-normal">(comma, space, or newline separated)</span></label>
                    <textarea
                        x-model="raw"
                        @input.debounce.200ms="parseInput()"
                        @keydown.ctrl.enter.prevent="calculate()"
                        @keydown.meta.enter.prevent="calculate()"
                        rows="5"
                        placeholder="Examples:
4, 7, 13, 2, 1, 7, 8, 11, 5
1 2 3 4 5 6 7 8 9 10
84.5
90.0
76.3"
                        class="form-input st-textarea"
                        :class="error ? 'border-red-300 focus:border-red-400 focus:ring-red-200' : ''">
                    </textarea>
                    <p class="form-help">Commas, spaces, or new lines. Decimals (3.14) and negatives (−5) are supported.</p>
                </div>

                
                <div class="flex flex-wrap items-center gap-5">
                    <div class="flex items-center gap-2">
                        <label class="form-label mb-0 whitespace-nowrap text-sm">Decimal places</label>
                        <select x-model="decimals" class="form-input py-1.5 pr-8 w-20 text-sm">
                            <option value="0">0</option>
                            <option value="2" selected>2</option>
                            <option value="4">4</option>
                            <option value="6">6</option>
                            <option value="auto">Auto</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="form-label mb-0 whitespace-nowrap text-sm">Sorted list</label>
                        <button type="button" @click="showSorted=!showSorted"
                                class="st-toggle" :class="showSorted ? 'bg-brand-600' : 'bg-gray-300'">
                            <span class="st-toggle-knob" :class="showSorted ? 'translate-x-[18px]' : 'translate-x-[3px]'"></span>
                        </button>
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="form-label mb-0 whitespace-nowrap text-sm">Frequency table</label>
                        <button type="button" @click="showFreq=!showFreq"
                                class="st-toggle" :class="showFreq ? 'bg-brand-600' : 'bg-gray-300'">
                            <span class="st-toggle-knob" :class="showFreq ? 'translate-x-[18px]' : 'translate-x-[3px]'"></span>
                        </button>
                    </div>
                </div>

                
                <div x-show="error" x-transition
                     class="flex items-start gap-2 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                    <svg class="w-4 h-4 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span x-text="error"></span>
                </div>

                
                <div x-show="parsed.invalid.length > 0" x-transition
                     class="flex items-start gap-2 p-3 bg-amber-50 border border-amber-200 rounded-xl text-sm text-amber-800">
                    <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="font-medium">
                            <span x-text="parsed.invalid.length"></span> invalid value<span x-show="parsed.invalid.length !== 1">s</span> skipped:
                        </p>
                        <div class="flex flex-wrap gap-1.5 mt-1.5">
                            <template x-for="inv in parsed.invalid" :key="inv">
                                <span class="st-invalid-token" x-text="inv"></span>
                            </template>
                        </div>
                    </div>
                </div>

                
                <div class="flex flex-wrap gap-2 pt-1">
                    <button type="button" @click="calculate()"
                            :disabled="parsed.valid.length === 0"
                            class="btn btn-primary flex-1 sm:flex-none btn-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Calculate
                    </button>
                    <button type="button" @click="loadSample()" class="btn btn-secondary">📋 Sample</button>
                    <button type="button" @click="clearAll()"
                            x-show="raw.length > 0 || phase === 'done'"
                            class="btn btn-secondary">✕ Clear</button>
                </div>
                <p class="text-xs text-gray-400 text-center">
                    <kbd class="px-1.5 py-0.5 bg-gray-100 rounded text-gray-600 font-mono text-[10px]">Ctrl</kbd>+<kbd class="px-1.5 py-0.5 bg-gray-100 rounded text-gray-600 font-mono text-[10px]">Enter</kbd> to calculate
                </p>
            </div>
        </div>

        
        <div x-show="phase === 'loading'" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <template x-for="i in 6" :key="i"><div class="st-shimmer"></div></template>
        </div>

        
        <div x-show="phase === 'done' && result"
             x-transition:enter="transition ease-out duration-250"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="space-y-5 st-in"
             id="st-results">

            
            <div>
                <div class="st-divider mb-3">Central Tendency</div>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">

                    <div class="st-stat lg:col-span-2">
                        <div class="st-label">Mean / Average</div>
                        <div class="st-big" x-text="result ? fmt(result.mean) : '—'"></div>
                        <div class="st-sublabel">Σx ÷ n</div>
                    </div>

                    <div class="st-stat">
                        <div class="st-label">Median</div>
                        <div class="st-big violet" x-text="result ? fmt(result.median) : '—'"></div>
                        <div class="st-sublabel">middle value</div>
                    </div>

                    <div class="st-stat">
                        <div class="st-label">Mode</div>
                        <div class="st-mode-text" x-text="result ? result.modeLabel : '—'"></div>
                        <div class="st-sublabel" x-text="result && result.modeFreq ? 'freq = ' + result.modeFreq : 'all unique'"></div>
                    </div>

                    <div class="st-stat">
                        <div class="st-label">Sum</div>
                        <div class="st-big green" x-text="result ? fmt(result.sum) : '—'"></div>
                        <div class="st-sublabel">total</div>
                    </div>

                    <div class="st-stat">
                        <div class="st-label">Count</div>
                        <div class="st-big cyan" x-text="result ? result.count : '—'"></div>
                        <div class="st-sublabel">n</div>
                    </div>

                </div>
            </div>

            
            <div>
                <div class="st-divider mb-3">Dispersion</div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">

                    <div class="st-stat">
                        <div class="st-label">Std Dev (Sample)</div>
                        <div class="st-big teal" x-text="result && result.count > 1 ? fmt(result.sampleStdDev) : 'N/A'"></div>
                        <div class="st-sublabel">s &nbsp;(÷ n−1)</div>
                    </div>

                    <div class="st-stat">
                        <div class="st-label">Std Dev (Pop)</div>
                        <div class="st-big slate" x-text="result ? fmt(result.popStdDev) : '—'"></div>
                        <div class="st-sublabel">σ &nbsp;(÷ n)</div>
                    </div>

                    <div class="st-stat">
                        <div class="st-label">Variance (Sample)</div>
                        <div class="st-big orange" x-text="result && result.count > 1 ? fmt(result.sampleVariance) : 'N/A'"></div>
                        <div class="st-sublabel">s²</div>
                    </div>

                    <div class="st-stat">
                        <div class="st-label">Variance (Pop)</div>
                        <div class="st-big slate" x-text="result ? fmt(result.popVariance) : '—'"></div>
                        <div class="st-sublabel">σ²</div>
                    </div>

                </div>
            </div>

            
            <div>
                <div class="st-divider mb-3">Five-Number Summary</div>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">

                    <div class="st-stat">
                        <div class="st-label">Minimum</div>
                        <div class="st-big amber" x-text="result ? fmt(result.min) : '—'"></div>
                        <div class="st-sublabel">smallest</div>
                    </div>

                    <div class="st-stat">
                        <div class="st-label">Q1 · 25th pct</div>
                        <div class="st-big cyan" x-text="result ? fmt(result.q1) : '—'"></div>
                        <div class="st-sublabel">lower quartile</div>
                    </div>

                    <div class="st-stat">
                        <div class="st-label">Median · Q2</div>
                        <div class="st-big violet" x-text="result ? fmt(result.median) : '—'"></div>
                        <div class="st-sublabel">50th pct</div>
                    </div>

                    <div class="st-stat">
                        <div class="st-label">Q3 · 75th pct</div>
                        <div class="st-big" style="background:linear-gradient(135deg,#7c3aed,#a855f7);-webkit-background-clip:text;background-clip:text;" x-text="result ? fmt(result.q3) : '—'"></div>
                        <div class="st-sublabel">upper quartile</div>
                    </div>

                    <div class="st-stat">
                        <div class="st-label">Maximum</div>
                        <div class="st-big rose" x-text="result ? fmt(result.max) : '—'"></div>
                        <div class="st-sublabel">largest</div>
                    </div>

                </div>
            </div>

            
            <div>
                <div class="st-divider mb-3">Additional</div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">

                    <div class="st-stat">
                        <div class="st-label">Range</div>
                        <div class="st-big slate" x-text="result ? fmt(result.range) : '—'"></div>
                        <div class="st-sublabel">max − min</div>
                    </div>

                    <div class="st-stat">
                        <div class="st-label">IQR</div>
                        <div class="st-big teal" x-text="result ? fmt(result.iqr) : '—'"></div>
                        <div class="st-sublabel">Q3 − Q1</div>
                    </div>

                    <div class="st-stat">
                        <div class="st-label">Coeff. of Variation</div>
                        <div class="st-big orange" x-text="result && !isNaN(result.cv) ? fmt(result.cv) + '%' : 'N/A'"></div>
                        <div class="st-sublabel">σ ÷ |mean| × 100</div>
                    </div>

                    <div class="st-stat">
                        <div class="st-label">Skewness</div>
                        <div class="st-big slate" x-text="result && result.skewness !== null ? fmt(result.skewness) : 'N/A'"></div>
                        <div class="st-sublabel" x-text="result && result.skewness !== null ? (result.skewness > 0.5 ? 'right-skewed' : result.skewness < -0.5 ? 'left-skewed' : 'approx. symmetric') : ''"></div>
                    </div>

                </div>
            </div>

            
            <div x-show="result && (result.geoMean !== null || result.harmMean !== null)">
                <div class="st-divider mb-3">Mean Variants <span class="font-normal normal-case text-gray-400">(positive numbers only)</span></div>
                <div class="grid grid-cols-2 gap-3">

                    <div class="st-stat" x-show="result && result.geoMean !== null">
                        <div class="st-label">Geometric Mean</div>
                        <div class="st-big green" x-text="result && result.geoMean !== null ? fmt(result.geoMean) : '—'"></div>
                        <div class="st-sublabel">(x₁·x₂·…·xₙ)^(1/n)</div>
                    </div>

                    <div class="st-stat" x-show="result && result.harmMean !== null">
                        <div class="st-label">Harmonic Mean</div>
                        <div class="st-big teal" x-text="result && result.harmMean !== null ? fmt(result.harmMean) : '—'"></div>
                        <div class="st-sublabel">n ÷ Σ(1/xᵢ)</div>
                    </div>

                </div>
            </div>

            
            <div class="card p-5" x-show="result && result.count > 1 && result.range > 0">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-sm font-semibold text-gray-700">Box Plot (Five-Number Summary)</p>
                    <span class="text-xs text-gray-400">IQR: <strong class="text-brand-600" x-text="result ? fmt(result.iqr) : ''"></strong></span>
                </div>

                <div class="st-bp-wrap px-3">
                    <div class="st-bp-track">
                        <div class="st-bp-axis"></div>

                        
                        <div class="st-bp-whisker"
                             :style="'left:0; width:' + (result ? result.q1Pct : 0) + '%'"></div>

                        
                        <div class="st-bp-whisker"
                             :style="'left:' + (result ? result.q3Pct : 0) + '%; right:0'"></div>

                        
                        <div class="st-bp-box"
                             :style="'left:' + (result ? result.q1Pct : 0) + '%; width:' + (result ? (result.q3Pct - result.q1Pct) : 0) + '%'">
                            
                            <div class="st-bp-med"
                                 :style="'left:' + (result ? result.medInBox : 50) + '%'"></div>
                        </div>

                        
                        <div class="st-bp-dot min" style="left:0"></div>
                        
                        <div class="st-bp-dot" style="background:#0891b2;border:2px solid #fff;box-shadow:0 0 0 1.5px #0891b2;"
                             :style="'left:' + (result ? result.q1Pct : 0) + '%'"></div>
                        
                        <div class="st-bp-dot med"
                             :style="'left:' + (result ? result.q2Pct : 0) + '%'"></div>
                        
                        <div class="st-bp-dot" style="background:#7c3aed;border:2px solid #fff;box-shadow:0 0 0 1.5px #7c3aed;"
                             :style="'left:' + (result ? result.q3Pct : 0) + '%'"></div>
                        
                        <div class="st-bp-dot max" style="left:100%"></div>
                    </div>

                    
                    <div class="st-bp-labels">
                        <span class="st-bp-lbl min" style="left:0; transform:translateX(0)">Min<br><span x-text="result ? fmt(result.min) : ''"></span></span>
                        <span class="st-bp-lbl q1"  :style="'left:' + (result ? result.q1Pct : 0) + '%'">Q1<br><span x-text="result ? fmt(result.q1) : ''"></span></span>
                        <span class="st-bp-lbl med" :style="'left:' + (result ? result.q2Pct : 0) + '%'">Med<br><span x-text="result ? fmt(result.median) : ''"></span></span>
                        <span class="st-bp-lbl q3"  :style="'left:' + (result ? result.q3Pct : 0) + '%'">Q3<br><span x-text="result ? fmt(result.q3) : ''"></span></span>
                        <span class="st-bp-lbl max" style="left:100%; transform:translateX(-100%)">Max<br><span x-text="result ? fmt(result.max) : ''"></span></span>
                    </div>
                </div>

                <div class="flex flex-wrap justify-center gap-4 mt-1 text-xs text-gray-400">
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-amber-400 inline-block"></span>Min</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-cyan-500 inline-block"></span>Q1</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-brand-600 inline-block"></span>Median</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-violet-600 inline-block"></span>Q3</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>Max</span>
                </div>
            </div>

            
            <div class="card overflow-hidden" x-show="showSorted && result && result.count > 0">
                <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100 bg-gray-50">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                        </svg>
                        <span class="text-sm font-semibold text-gray-700">
                            Sorted Numbers
                            <span class="ml-1 badge badge-gray" x-text="result ? result.count : 0"></span>
                        </span>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" @click="sortDir = sortDir === 'asc' ? 'desc' : 'asc'"
                                class="btn btn-secondary btn-sm" x-text="sortDir === 'asc' ? '↑ Asc' : '↓ Desc'"></button>
                        <button type="button" @click="copyList()"
                                class="btn btn-sm border border-gray-200"
                                :class="listCopyFlash ? 'bg-emerald-50 text-emerald-700 border-emerald-300' : 'bg-white text-gray-600 hover:bg-gray-50'"
                                x-text="listCopyFlash ? '✓ Copied' : 'Copy'"></button>
                    </div>
                </div>
                <div class="p-4">
                    <div class="flex flex-wrap gap-2">
                        <template x-for="(n, idx) in displayedNumbers" :key="idx">
                            <span class="st-pill"
                                  :class="{
                                      'is-min': result && n === result.min,
                                      'is-max': result && n === result.max,
                                      'is-med': result && n === result.median && result.count % 2 !== 0,
                                      'is-q1' : result && Math.abs(n - result.q1) < 1e-9,
                                      'is-q3' : result && Math.abs(n - result.q3) < 1e-9
                                  }"
                                  x-text="fmt(n)">
                            </span>
                        </template>
                    </div>
                    <div class="flex flex-wrap items-center justify-center gap-3 mt-3 pt-3 border-t border-gray-100 text-xs text-gray-400">
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full border border-amber-400 bg-amber-50 inline-block"></span>Min</span>
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full border border-cyan-400 bg-cyan-50 inline-block"></span>Q1</span>
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full border border-violet-400 bg-violet-50 inline-block"></span>Median (odd n)</span>
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full border border-fuchsia-400 bg-fuchsia-50 inline-block"></span>Q3</span>
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full border border-emerald-400 bg-emerald-50 inline-block"></span>Max</span>
                    </div>
                </div>
            </div>

            
            <div class="card overflow-hidden" x-show="showFreq && result && result.count > 0">
                <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100 bg-gray-50">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span class="text-sm font-semibold text-gray-700">Frequency Distribution
                            <span class="ml-1 badge badge-gray" x-text="result ? result.freqTable.length + ' unique' : ''"></span>
                        </span>
                    </div>
                </div>
                <div class="p-4" style="max-height:320px; overflow-y:auto;">
                    <div class="grid grid-cols-3 gap-1 text-xs font-semibold text-gray-400 uppercase tracking-wide pb-1 border-b border-gray-100 mb-1">
                        <span>Value</span><span>Bar</span><span class="text-right">Freq · %</span>
                    </div>
                    <template x-for="row in result.freqTable" :key="row.val">
                        <div class="st-freq-row">
                            <span class="st-freq-val" x-text="fmt(row.val)"></span>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="st-freq-bar" :style="'width:' + row.pct + '%'"></div>
                            </div>
                            <span class="st-freq-pct" x-text="row.freq + ' · ' + row.pct.toFixed(1) + '%'"></span>
                        </div>
                    </template>
                </div>
            </div>

            
            <div class="card p-4">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-sm font-medium text-gray-600">Export results:</span>
                    <button type="button" @click="copySummary()"
                            class="btn btn-secondary btn-sm"
                            :class="summaryCopyFlash ? 'bg-emerald-50 text-emerald-700' : ''">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <span x-text="summaryCopyFlash ? '✓ Copied!' : 'Copy Summary'"></span>
                    </button>
                    <button type="button" @click="downloadSummary()" class="btn btn-secondary btn-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download .txt
                    </button>
                    <button type="button" @click="clearAll()"
                            class="btn btn-secondary btn-sm ml-auto text-red-500 hover:text-red-600 hover:bg-red-50">
                        ✕ Reset
                    </button>
                </div>
            </div>

        </div>

        
        <div x-show="phase === 'idle'">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-3">
                <?php $__currentLoopData = [
                    ['icon'=>'📐','label'=>'Mean','desc'=>'Sum of all values divided by count'],
                    ['icon'=>'📍','label'=>'Median','desc'=>'Middle value when data is sorted'],
                    ['icon'=>'🔁','label'=>'Mode','desc'=>'Most frequently occurring value(s)'],
                    ['icon'=>'📏','label'=>'Std Dev','desc'=>'Spread of data around the mean'],
                    ['icon'=>'⬇️','label'=>'Min / Max','desc'=>'Smallest and largest values'],
                    ['icon'=>'📊','label'=>'Quartiles','desc'=>'Q1, Q2, Q3 and IQR (box plot)'],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card p-4 text-center hover:border-brand-200 transition-colors">
                    <p class="text-2xl mb-1.5"><?php echo e($info['icon']); ?></p>
                    <p class="text-sm font-semibold text-gray-700"><?php echo e($info['label']); ?></p>
                    <p class="text-xs text-gray-400 mt-1 leading-snug"><?php echo e($info['desc']); ?></p>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <?php $__currentLoopData = [
                    ['icon'=>'📉','label'=>'Variance','desc'=>'Square of the standard deviation'],
                    ['icon'=>'↔️','label'=>'Range & IQR','desc'=>'max−min and Q3−Q1 spread'],
                    ['icon'=>'〰️','label'=>'Skewness','desc'=>'Symmetry of the distribution'],
                    ['icon'=>'📈','label'=>'CV %','desc'=>'Relative variability as a percentage'],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card p-4 text-center hover:border-brand-200 transition-colors">
                    <p class="text-2xl mb-1.5"><?php echo e($info['icon']); ?></p>
                    <p class="text-sm font-semibold text-gray-700"><?php echo e($info['label']); ?></p>
                    <p class="text-xs text-gray-400 mt-1 leading-snug"><?php echo e($info['desc']); ?></p>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <?php if($relatedTools->count()): ?>
        <div x-show="phase === 'idle'">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Related Calculators</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                <?php $__currentLoopData = $relatedTools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('tools.show', $related->slug)); ?>"
                   class="card-hover p-4 flex items-center gap-3 no-underline">
                    <span class="text-xl"><?php echo e($related->icon); ?></span>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate"><?php echo e($related->name); ?></p>
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
/* ═══════════════════════════════════════════════════════════════
   STATISTICS CALCULATOR — pure client-side Alpine.js component
═══════════════════════════════════════════════════════════════ */
function statCalc() {
    return {
        /* ── State ── */
        raw:        '',
        decimals:   '2',
        showSorted: true,
        showFreq:   false,
        sortDir:    'asc',
        parsed:     { valid: [], invalid: [] },
        phase:      'idle',
        error:      '',
        result:     null,
        summaryCopyFlash: false,
        listCopyFlash:    false,

        /* ── Lifecycle ── */
        init() { /* ready */ },

        /* ── Computed ── */
        get displayedNumbers() {
            if (!this.result) return [];
            var nums = this.result.sorted.slice();
            return this.sortDir === 'desc' ? nums.reverse() : nums;
        },

        /* ── Parse raw input ── */
        parseInput() {
            this.error = '';
            var raw = this.raw;
            if (!raw.trim()) { this.parsed = { valid: [], invalid: [] }; return; }
            var tokens = raw.split(/[\s,;]+/).filter(function(t) { return t.trim() !== ''; });
            var valid = [], invalid = [];
            tokens.forEach(function(tok) {
                var t = tok.trim();
                if (t === '') return;
                var n = parseFloat(t);
                if (!isNaN(n) && isFinite(n) && /^-?(\d+\.?\d*|\.\d+)([eE][+-]?\d+)?$/.test(t)) {
                    valid.push(n);
                } else {
                    if (invalid.indexOf(t) === -1) invalid.push(t);
                }
            });
            this.parsed = { valid: valid, invalid: invalid };
        },

        /* ── Calculate ── */
        calculate() {
            this.error = '';
            this.parseInput();
            if (this.parsed.valid.length === 0) {
                this.error = this.raw.trim() === ''
                    ? 'Please enter at least one number.'
                    : 'No valid numbers found. Check your input for typos.';
                this.result = null;
                this.phase  = 'idle';
                return;
            }
            var self = this;
            self.phase = 'loading';
            setTimeout(function() {
                self.result = self._compute(self.parsed.valid);
                self.phase  = 'done';
                if (window.innerWidth < 1024) {
                    var el = document.getElementById('st-results');
                    if (el) setTimeout(function() { el.scrollIntoView({ behavior: 'smooth', block: 'start' }); }, 80);
                }
            }, 180);
        },

        /* ── Linear interpolation percentile (Excel PERCENTILE.INC / numpy default) ── */
        _percentile: function(sorted, p) {
            var n = sorted.length;
            if (n === 1) return sorted[0];
            var idx = p * (n - 1);
            var lo  = Math.floor(idx);
            var hi  = Math.ceil(idx);
            if (lo === hi) return sorted[lo];
            return sorted[lo] + (sorted[hi] - sorted[lo]) * (idx - lo);
        },

        /* ── Core statistics engine ── */
        _compute: function(nums) {
            var self = this;
            var n    = nums.length;
            var sorted = nums.slice().sort(function(a, b) { return a - b; });

            /* Basic */
            var sum  = nums.reduce(function(a, b) { return a + b; }, 0);
            var mean = sum / n;
            var min  = sorted[0];
            var max  = sorted[n - 1];
            var range = max - min;

            /* Quartiles */
            var q1     = self._percentile(sorted, 0.25);
            var median = self._percentile(sorted, 0.50);
            var q3     = self._percentile(sorted, 0.75);
            var iqr    = q3 - q1;

            /* Variance & Std Dev */
            var sumSqDiff = nums.reduce(function(acc, x) { return acc + Math.pow(x - mean, 2); }, 0);
            var popVariance    = sumSqDiff / n;
            var sampleVariance = n > 1 ? sumSqDiff / (n - 1) : NaN;
            var popStdDev      = Math.sqrt(popVariance);
            var sampleStdDev   = n > 1 ? Math.sqrt(sampleVariance) : NaN;

            /* CV */
            var cv = (mean !== 0 && !isNaN(popStdDev)) ? (popStdDev / Math.abs(mean)) * 100 : NaN;

            /* Skewness (population) */
            var skewness = null;
            if (popStdDev > 0) {
                skewness = nums.reduce(function(a, x) {
                    return a + Math.pow((x - mean) / popStdDev, 3);
                }, 0) / n;
            }

            /* Mode */
            var freq = {};
            nums.forEach(function(x) { var k = String(x); freq[k] = (freq[k] || 0) + 1; });
            var maxFreq = Math.max.apply(null, Object.keys(freq).map(function(k){ return freq[k]; }));
            var modes   = Object.keys(freq)
                .filter(function(k) { return freq[k] === maxFreq; })
                .map(Number)
                .sort(function(a, b) { return a - b; });
            var modeLabel, modeFreq;
            if (maxFreq === 1) {
                modeLabel = 'No mode'; modeFreq = null;
            } else if (modes.length > 5) {
                modeLabel = modes.slice(0, 5).map(function(m){ return self.fmt(m); }).join(', ') + '…'; modeFreq = maxFreq;
            } else {
                modeLabel = modes.map(function(m){ return self.fmt(m); }).join(', '); modeFreq = maxFreq;
            }

            /* Geometric mean (all positive) */
            var geoMean = null;
            if (sorted[0] > 0) {
                var logSum = nums.reduce(function(a, x) { return a + Math.log(x); }, 0);
                geoMean = Math.exp(logSum / n);
            }

            /* Harmonic mean (all positive) */
            var harmMean = null;
            if (sorted[0] > 0) {
                var recSum = nums.reduce(function(a, x) { return a + 1 / x; }, 0);
                harmMean = n / recSum;
            }

            /* Box plot percentages */
            var q1Pct  = range === 0 ? 25 : Math.max(0, Math.min(100, ((q1     - min) / range) * 100));
            var q2Pct  = range === 0 ? 50 : Math.max(0, Math.min(100, ((median - min) / range) * 100));
            var q3Pct  = range === 0 ? 75 : Math.max(0, Math.min(100, ((q3     - min) / range) * 100));
            var meanPct= range === 0 ? 50 : Math.max(0, Math.min(100, ((mean   - min) / range) * 100));

            /* Median marker inside IQR box (% within box) */
            var boxW   = q3Pct - q1Pct;
            var medInBox = boxW > 0 ? ((q2Pct - q1Pct) / boxW) * 100 : 50;

            /* Frequency table */
            var freqTable = Object.keys(freq).map(function(k) {
                return { val: Number(k), freq: freq[k], pct: (freq[k] / n) * 100 };
            }).sort(function(a, b) { return a.val - b.val; });

            return {
                count: n, n: n,
                sum: sum, mean: mean, median: median,
                min: min, max: max, range: range,
                q1: q1, q2: median, q3: q3, iqr: iqr,
                popVariance: popVariance, sampleVariance: sampleVariance,
                popStdDev: popStdDev, sampleStdDev: sampleStdDev,
                cv: cv, skewness: skewness,
                modes: modes, modeLabel: modeLabel, modeFreq: modeFreq, maxFreq: maxFreq,
                geoMean: geoMean, harmMean: harmMean,
                q1Pct: q1Pct, q2Pct: q2Pct, q3Pct: q3Pct, meanPct: meanPct, medInBox: medInBox,
                sorted: sorted, freqTable: freqTable,
            };
        },

        /* ── Format number ── */
        fmt: function(val) {
            if (val === null || val === undefined || isNaN(val)) return '—';
            if (this.decimals === 'auto') return parseFloat(val.toPrecision(6)).toString();
            var d = parseInt(this.decimals, 10);
            var s = val.toFixed(d);
            if (d > 0) s = s.replace(/(\.\d*?)0+$/, '$1').replace(/\.$/, '');
            return s;
        },

        /* ── Sample data ── */
        loadSample: function() {
            this.raw = '4, 7, 13, 2, 1, 7, 8, 11, 5, 7, 3, 6, 9, 12, 4';
            this.error = '';
            this.parseInput();
        },

        clearAll: function() {
            this.raw = ''; this.parsed = { valid: [], invalid: [] };
            this.result = null; this.phase = 'idle'; this.error = '';
        },

        /* ── Export helpers ── */
        _buildSummary: function() {
            if (!this.result) return '';
            var r = this.result, f = this.fmt.bind(this);
            var naIf = function(v) { return (v === null || isNaN(v)) ? 'N/A' : f(v); };
            return [
                'Statistics Calculator Results',
                '==============================',
                'Numbers   : ' + this.parsed.valid.join(', '),
                '',
                '── Central Tendency ──',
                'Mean       : ' + f(r.mean),
                'Median     : ' + f(r.median),
                'Mode       : ' + r.modeLabel + (r.modeFreq ? ' (freq=' + r.modeFreq + ')' : ''),
                'Sum        : ' + f(r.sum),
                'Count (n)  : ' + r.count,
                '',
                '── Dispersion ──',
                'Std Dev (s): ' + naIf(r.sampleStdDev) + ' (sample)',
                'Std Dev (σ): ' + f(r.popStdDev) + ' (population)',
                'Variance s²: ' + naIf(r.sampleVariance),
                'Variance σ²: ' + f(r.popVariance),
                'CV %       : ' + naIf(r.cv) + '%',
                'Skewness   : ' + naIf(r.skewness),
                '',
                '── Five-Number Summary ──',
                'Minimum    : ' + f(r.min),
                'Q1 (25th)  : ' + f(r.q1),
                'Median Q2  : ' + f(r.median),
                'Q3 (75th)  : ' + f(r.q3),
                'Maximum    : ' + f(r.max),
                '',
                '── Additional ──',
                'Range      : ' + f(r.range),
                'IQR        : ' + f(r.iqr),
                (r.geoMean  !== null ? 'Geo Mean   : ' + f(r.geoMean) : ''),
                (r.harmMean !== null ? 'Harm Mean  : ' + f(r.harmMean) : ''),
            ].filter(function(l){ return l !== ''; }).join('\n');
        },

        copySummary: async function() {
            var text = this._buildSummary(); if (!text) return;
            try { await navigator.clipboard.writeText(text); } catch(e) {
                var ta = document.createElement('textarea');
                ta.value = text; ta.style.cssText = 'position:fixed;opacity:0;';
                document.body.appendChild(ta); ta.select();
                document.execCommand('copy'); document.body.removeChild(ta);
            }
            var self = this; this.summaryCopyFlash = true;
            setTimeout(function(){ self.summaryCopyFlash = false; }, 1800);
        },

        copyList: async function() {
            var nums = this.displayedNumbers; if (!nums.length) return;
            var self = this;
            var text = nums.map(function(n){ return self.fmt(n); }).join(', ');
            try { await navigator.clipboard.writeText(text); } catch(e) {
                var ta = document.createElement('textarea');
                ta.value = text; ta.style.cssText = 'position:fixed;opacity:0;';
                document.body.appendChild(ta); ta.select();
                document.execCommand('copy'); document.body.removeChild(ta);
            }
            this.listCopyFlash = true;
            setTimeout(function(){ self.listCopyFlash = false; }, 1800);
        },

        downloadSummary: function() {
            var text = this._buildSummary(); if (!text) return;
            var blob = new Blob([text], { type: 'text/plain;charset=utf-8' });
            var url  = URL.createObjectURL(blob);
            var a    = document.createElement('a');
            a.href = url; a.download = 'statistics-results.txt';
            document.body.appendChild(a); a.click();
            document.body.removeChild(a); URL.revokeObjectURL(url);
        },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\statistics-calculator.blade.php ENDPATH**/ ?>