<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ══════════════════════════════════════════════
   Ideal Weight Calculator  —  prefix: iw-
   Theme: Sky / Cyan
══════════════════════════════════════════════ */

/* Gender / unit toggle pill */
.iw-pill { display:flex; background:#f1f5f9; border-radius:.625rem; padding:.2rem; gap:.15rem; }
.iw-pill-btn { flex:1; padding:.35rem .75rem; border-radius:.45rem; font-size:.78rem; font-weight:600; color:#64748b; cursor:pointer; border:none; background:none; transition:all .15s; white-space:nowrap; text-align:center; }
.iw-pill-btn.active         { background:#fff; box-shadow:0 1px 4px rgba(0,0,0,.1); }
.iw-pill-btn.active.sky     { color:#0284c7; }
.iw-pill-btn.active.rose    { color:#e11d48; }
.iw-pill-btn.active.brand   { color:#4f46e5; }
.iw-pill-btn.active.emerald { color:#059669; }

/* Hero */
.iw-hero { font-size:clamp(2.6rem,5.5vw,4rem); font-weight:900; line-height:1; letter-spacing:-.03em; background:linear-gradient(135deg,#0284c7 0%,#0ea5e9 55%,#38bdf8 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }

/* Stat card */
.iw-stat { background:#fff; border:1.5px solid #e2e8f0; border-radius:1.125rem; padding:1rem .9rem; display:flex; flex-direction:column; align-items:center; gap:.3rem; text-align:center; transition:all .15s; }
.iw-stat:hover { border-color:#bae6fd; box-shadow:0 4px 16px rgba(14,165,233,.08); transform:translateY(-1px); }
.iw-stat-lbl { font-size:.62rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.07em; }
.iw-stat-val { font-size:1.25rem; font-weight:800; line-height:1.1; }
.iw-stat-val.sky     { background:linear-gradient(135deg,#0284c7,#0ea5e9); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.iw-stat-val.brand   { background:linear-gradient(135deg,#4f46e5,#7c3aed); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.iw-stat-val.emerald { background:linear-gradient(135deg,#059669,#10b981); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.iw-stat-val.rose    { background:linear-gradient(135deg,#e11d48,#f43f5e); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.iw-stat-val.amber   { background:linear-gradient(135deg,#d97706,#f59e0b); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.iw-stat-sub { font-size:.65rem; color:#94a3b8; }

/* Formula result card */
.iw-fc { border-radius:1rem; border:1.5px solid #e2e8f0; background:#fff; padding:1rem; transition:all .15s; }
.iw-fc:hover { border-color:#bae6fd; box-shadow:0 4px 12px rgba(14,165,233,.09); transform:translateY(-1px); }
.iw-fc.best  { border-color:#0ea5e9; background:linear-gradient(135deg,#f0f9ff,#e0f2fe); }
.iw-fc-name  { font-size:.7rem; font-weight:800; color:#0284c7; text-transform:uppercase; letter-spacing:.08em; }
.iw-fc-year  { font-size:.65rem; color:#94a3b8; }
.iw-fc-val   { font-size:1.6rem; font-weight:900; color:#0c4a6e; line-height:1.1; margin:.4rem 0 .1rem; }
.iw-fc-alt   { font-size:.72rem; color:#64748b; }
.iw-fc-badge { font-size:.6rem; font-weight:700; padding:.15rem .55rem; border-radius:9999px; white-space:nowrap; }
.iw-fc-desc  { font-size:.68rem; color:#64748b; margin-top:.5rem; line-height:1.4; }

/* Comparison bar */
.iw-cbar-track { height:10px; border-radius:9999px; background:#e0f2fe; overflow:hidden; }
.iw-cbar-fill  { height:100%; border-radius:9999px; transition:width .5s ease; }

/* Pre wrapper */
.iw-pre-wrap { display:flex; align-items:stretch; }
.iw-pre { display:flex; align-items:center; padding:0 .75rem; background:#f8fafc; border:1px solid #d1d5db; border-right:none; border-radius:.75rem 0 0 .75rem; font-size:.82rem; font-weight:700; color:#374151; white-space:nowrap; }
.iw-pre-wrap .form-input { border-radius:0 .75rem .75rem 0 !important; }

/* Section divider */
.iw-div { display:flex; align-items:center; gap:.6rem; color:#94a3b8; font-size:.62rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; }
.iw-div::before,.iw-div::after { content:''; flex:1; height:1px; background:#f1f5f9; }

/* Row */
.iw-row { display:flex; justify-content:space-between; align-items:center; padding:.45rem .75rem; border-radius:.5rem; }
.iw-row:hover { background:#f0f9ff; }

/* Weight range track */
.iw-range-track { position:relative; height:24px; border-radius:9999px; background:linear-gradient(to right,#bae6fd 0%,#0ea5e9 40%,#0ea5e9 60%,#bae6fd 100%); }
.iw-range-thumb { position:absolute; top:50%; transform:translate(-50%,-50%); width:16px; height:16px; border-radius:50%; background:#0c4a6e; border:2.5px solid #fff; box-shadow:0 2px 6px rgba(0,0,0,.25); transition:left .5s ease; }
.iw-range-labels { display:flex; justify-content:space-between; font-size:.65rem; color:#94a3b8; margin-top:.35rem; }

/* Shimmer */
@keyframes iwShim { 0%{background-position:-600px 0} 100%{background-position:600px 0} }
.iw-shim { height:5.5rem; border-radius:1.125rem; background:linear-gradient(90deg,#f0f9ff 25%,#e0f2fe 50%,#f0f9ff 75%); background-size:1200px 100%; animation:iwShim 1.4s infinite; }

@keyframes iwIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
.iw-in { animation:iwIn .3s ease-out; }

/* Current weight gauge */
.iw-gauge-track { height:20px; border-radius:9999px; position:relative; overflow:visible; }
.iw-gauge-segment { height:100%; border-radius:9999px; }
</style>

<div class="min-h-screen bg-gray-50" x-data="iwCalc()" x-init="init()">

    
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-8 md:py-10">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3"><?php echo e($tool->icon); ?> <?php echo e($tool->name); ?></h1>
                    <p class="text-gray-500 mt-2 max-w-2xl">Find your ideal body weight using <strong>four clinically established formulas</strong> — Devine, Robinson, Miller &amp; Hamwi — plus the healthy BMI weight range for your height.</p>
                </div>
                <div class="flex flex-wrap gap-2 shrink-0">
                    <span class="badge badge-primary">Free</span>
                    <span class="badge badge-gray">4 Formulas</span>
                    <span class="badge badge-success">Metric &amp; Imperial</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-6 md:py-8">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 items-start">

            
            <div class="lg:col-span-2">
                <div class="card">
                    <div class="px-5 pt-5 pb-1">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Your Details</p>
                    </div>

                    <div class="px-5 pb-5 space-y-4">

                        
                        <div>
                            <label class="form-label">Biological Sex</label>
                            <div class="iw-pill">
                                <button type="button" class="iw-pill-btn" :class="{active:gender==='male', sky:gender==='male'}" @click="gender='male'; autoCalc()">
                                    ♂ Male
                                </button>
                                <button type="button" class="iw-pill-btn" :class="{active:gender==='female', rose:gender==='female'}" @click="gender='female'; autoCalc()">
                                    ♀ Female
                                </button>
                            </div>
                        </div>

                        
                        <div>
                            <label class="form-label">Unit System</label>
                            <div class="iw-pill">
                                <button type="button" class="iw-pill-btn" :class="{active:unitSystem==='imperial', brand:unitSystem==='imperial'}" @click="switchUnit('imperial')">
                                    🇺🇸 Imperial (ft / lbs)
                                </button>
                                <button type="button" class="iw-pill-btn" :class="{active:unitSystem==='metric', emerald:unitSystem==='metric'}" @click="switchUnit('metric')">
                                    🌍 Metric (cm / kg)
                                </button>
                            </div>
                        </div>

                        
                        <div>
                            <label class="form-label">Height</label>
                            <div x-show="unitSystem==='imperial'" class="flex gap-2">
                                <div class="iw-pre-wrap flex-1">
                                    <span class="iw-pre">ft</span>
                                    <input type="number" step="1" min="3" max="8" x-model="heightFt"
                                           @input.debounce.300ms="autoCalc()"
                                           class="form-input" placeholder="5">
                                </div>
                                <div class="iw-pre-wrap flex-1">
                                    <span class="iw-pre">in</span>
                                    <input type="number" step="1" min="0" max="11" x-model="heightIn"
                                           @input.debounce.300ms="autoCalc()"
                                           class="form-input" placeholder="10">
                                </div>
                            </div>
                            <div x-show="unitSystem==='metric'" class="iw-pre-wrap">
                                <span class="iw-pre">cm</span>
                                <input type="number" step="any" min="100" max="250" x-model="heightCm"
                                       @input.debounce.300ms="autoCalc()"
                                       class="form-input" placeholder="e.g. 178">
                            </div>
                            <p class="form-help" x-show="unitSystem==='imperial' && (heightFt || heightIn)" x-text="'≈ ' + _imperialToCm().toFixed(0) + ' cm'"></p>
                            <p class="form-help" x-show="unitSystem==='metric' && heightCm" x-text="'≈ ' + _metricToFtIn()"></p>
                        </div>

                        <div class="iw-div pt-1">Current Weight <span class="font-normal normal-case tracking-normal text-gray-400">(optional)</span></div>

                        
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Compare with current weight</p>
                                <p class="text-xs text-gray-400">See how far you are from ideal</p>
                            </div>
                            <button type="button"
                                    class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 transition-colors duration-200"
                                    :class="showCurrentWeight ? 'bg-sky-500 border-sky-500' : 'bg-gray-200 border-gray-200'"
                                    @click="showCurrentWeight=!showCurrentWeight; if(!showCurrentWeight){currentWeight='';} autoCalc()">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition duration-200"
                                      :class="showCurrentWeight ? 'translate-x-4' : 'translate-x-0'"></span>
                            </button>
                        </div>

                        <div x-show="showCurrentWeight" x-transition class="space-y-1">
                            <label class="form-label">Current Weight</label>
                            <div x-show="unitSystem==='imperial'" class="iw-pre-wrap">
                                <span class="iw-pre">lbs</span>
                                <input type="number" step="any" min="1" x-model="currentWeight"
                                       @input.debounce.300ms="autoCalc()"
                                       class="form-input" placeholder="e.g. 185">
                            </div>
                            <div x-show="unitSystem==='metric'" class="iw-pre-wrap">
                                <span class="iw-pre">kg</span>
                                <input type="number" step="any" min="1" x-model="currentWeight"
                                       @input.debounce.300ms="autoCalc()"
                                       class="form-input" placeholder="e.g. 84">
                            </div>
                        </div>

                        
                        <div x-show="error" x-transition class="flex items-start gap-2 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span x-text="error"></span>
                        </div>

                        
                        <div class="flex flex-wrap gap-2 pt-1">
                            <button type="button" @click="calculate()" class="btn flex-1 sm:flex-none btn-lg font-bold" style="background:linear-gradient(135deg,#0284c7,#0ea5e9);color:white;border:none;box-shadow:0 4px 14px rgba(14,165,233,.3)">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-1m6 1l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-1m0-1v1m0 1l-3 9"/></svg>
                                Calculate
                            </button>
                            <button type="button" @click="loadSample()" class="btn btn-secondary">📋 Sample</button>
                            <button type="button" @click="clearAll()" x-show="phase==='done' || error" class="btn btn-secondary">✕ Clear</button>
                        </div>

                        
                        <div class="p-3 bg-sky-50 border border-sky-100 rounded-xl text-xs text-sky-800 leading-relaxed">
                            💡 These formulas were developed for adults and work best for heights above 5&nbsp;ft&nbsp;(152&nbsp;cm). Results are reference ranges — not medical advice.
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="lg:col-span-3 space-y-4" id="iw-results">

                
                <div x-show="phase==='loading'" class="space-y-3">
                    <div class="iw-shim" style="height:7rem"></div>
                    <div class="grid grid-cols-2 gap-3">
                        <template x-for="i in 4" :key="i"><div class="iw-shim"></div></template>
                    </div>
                </div>

                
                <template x-if="phase==='done' && result">
                    <div class="space-y-4 iw-in">

                        
                        <div class="card overflow-hidden">
                            <div style="background:linear-gradient(135deg,#f0f9ff 0%,#e0f2fe 100%);" class="px-6 py-5">
                                <p class="text-xs font-bold text-sky-500 uppercase tracking-widest mb-1">Average Ideal Weight (all formulas)</p>
                                <p class="iw-hero" x-text="result.avgDisplay"></p>
                                <p class="text-xs text-gray-500 mt-2">
                                    <span x-text="'Height: ' + result.heightDisplay"></span>
                                    &nbsp;·&nbsp;
                                    <span x-text="result.gender === 'male' ? '♂ Male' : '♀ Female'"></span>
                                    &nbsp;·&nbsp;
                                    Range: <strong x-text="result.formulaMinDisplay + ' – ' + result.formulaMaxDisplay"></strong>
                                </p>
                            </div>
                        </div>

                        
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div class="iw-stat">
                                <div class="iw-stat-lbl">Lowest Est.</div>
                                <div class="iw-stat-val emerald" x-text="result.formulaMinDisplay"></div>
                                <div class="iw-stat-sub" x-text="result.formulaMinFormula"></div>
                            </div>
                            <div class="iw-stat">
                                <div class="iw-stat-lbl">Average</div>
                                <div class="iw-stat-val sky" x-text="result.avgDisplay"></div>
                                <div class="iw-stat-sub">all 4 formulas</div>
                            </div>
                            <div class="iw-stat">
                                <div class="iw-stat-lbl">Highest Est.</div>
                                <div class="iw-stat-val rose" x-text="result.formulaMaxDisplay"></div>
                                <div class="iw-stat-sub" x-text="result.formulaMaxFormula"></div>
                            </div>
                            <div class="iw-stat">
                                <div class="iw-stat-lbl">BMI Range</div>
                                <div class="iw-stat-val brand" style="font-size:1rem" x-text="result.bmiRangeDisplay"></div>
                                <div class="iw-stat-sub">BMI 18.5–24.9</div>
                            </div>
                        </div>

                        
                        <div>
                            <p class="text-sm font-semibold text-gray-700 mb-3">Formula Results</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <template x-for="f in result.formulas" :key="f.key">
                                    <div class="iw-fc" :class="{best: f.isBest}">
                                        <div class="flex items-start justify-between gap-2 mb-1">
                                            <div>
                                                <span class="iw-fc-name" x-text="f.name"></span>
                                                <span class="iw-fc-year ml-1.5" x-text="'(' + f.year + ')'"></span>
                                            </div>
                                            <span x-show="f.isBest" class="iw-fc-badge" style="background:#e0f2fe;color:#0284c7">Most Used</span>
                                        </div>
                                        <div class="iw-fc-val" x-text="f.display"></div>
                                        <div class="iw-fc-alt" x-text="f.altDisplay"></div>
                                        <div class="mt-2">
                                            <div class="iw-cbar-track">
                                                <div class="iw-cbar-fill" :style="'width:'+f.barPct+'%;background:'+f.barColor"></div>
                                            </div>
                                        </div>
                                        <p class="iw-fc-desc" x-text="f.desc"></p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        
                        <div class="card p-4">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-sm font-semibold text-gray-700">Healthy Weight Range (BMI 18.5 – 24.9)</p>
                                <span class="badge badge-success">For your height</span>
                            </div>
                            <div class="flex items-center justify-between text-sm font-semibold text-gray-700 mb-2">
                                <span x-text="result.bmiMinDisplay"></span>
                                <span class="text-xs text-gray-400">← healthy range →</span>
                                <span x-text="result.bmiMaxDisplay"></span>
                            </div>
                            <div class="iw-range-track mb-1" x-show="result.currentWeightKg > 0">
                                <div class="iw-range-thumb" :style="'left:' + result.currentWeightPct + '%'"></div>
                            </div>
                            <div class="iw-range-track" x-show="result.currentWeightKg <= 0"></div>
                            <div class="iw-range-labels">
                                <span x-text="result.bmiMinDisplay"></span>
                                <span class="text-sky-600 font-medium" x-show="result.currentWeightKg > 0" x-text="'You: ' + result.currentWeightDisplay"></span>
                                <span x-text="result.bmiMaxDisplay"></span>
                            </div>
                            <div class="mt-3 grid grid-cols-3 gap-2 text-center text-xs">
                                <div class="p-2 rounded-xl bg-blue-50 border border-blue-100">
                                    <p class="font-bold text-blue-600">Underweight</p>
                                    <p class="text-gray-500" x-text="'Below ' + result.bmiMinDisplay"></p>
                                </div>
                                <div class="p-2 rounded-xl bg-emerald-50 border border-emerald-100">
                                    <p class="font-bold text-emerald-600">Healthy</p>
                                    <p class="text-gray-500" x-text="result.bmiRangeDisplay"></p>
                                </div>
                                <div class="p-2 rounded-xl bg-amber-50 border border-amber-100">
                                    <p class="font-bold text-amber-600">Overweight</p>
                                    <p class="text-gray-500" x-text="'Above ' + result.bmiMaxDisplay"></p>
                                </div>
                            </div>
                        </div>

                        
                        <template x-if="result.currentWeightKg > 0">
                            <div class="card p-4">
                                <p class="text-sm font-semibold text-gray-700 mb-3">Your Weight vs. Ideal</p>
                                <div class="grid grid-cols-3 gap-3 mb-4">
                                    <div class="iw-stat">
                                        <div class="iw-stat-lbl">Current</div>
                                        <div class="iw-stat-val" :style="'color:'+result.weightStatusColor" x-text="result.currentWeightDisplay"></div>
                                        <div class="iw-stat-sub">your weight</div>
                                    </div>
                                    <div class="iw-stat">
                                        <div class="iw-stat-lbl">Difference</div>
                                        <div class="iw-stat-val" :style="'color:'+result.weightStatusColor" x-text="result.diffDisplay"></div>
                                        <div class="iw-stat-sub" x-text="result.diffNote"></div>
                                    </div>
                                    <div class="iw-stat">
                                        <div class="iw-stat-lbl">Status</div>
                                        <div class="text-lg font-black mt-1" :style="'color:'+result.weightStatusColor" x-text="result.weightStatusLabel"></div>
                                        <div class="iw-stat-sub">by BMI</div>
                                    </div>
                                </div>
                                <div x-show="result.diffKg !== 0">
                                    <p class="text-xs text-gray-500 mb-2">To reach the <strong>average ideal weight</strong>:</p>
                                    <div class="p-3 rounded-xl border text-sm" :style="result.diffKg > 0 ? 'background:#fef2f2;border-color:#fecaca' : 'background:#f0fdf4;border-color:#bbf7d0'">
                                        <span x-show="result.diffKg > 0">
                                            💡 You are <strong x-text="result.diffDisplay"></strong> above the average ideal weight.
                                            At a healthy deficit of 500 kcal/day (~1 lb/week), it would take approximately
                                            <strong x-text="result.weeksTo500 + ' weeks'"></strong> to reach it.
                                        </span>
                                        <span x-show="result.diffKg < 0">
                                            💡 You are <strong x-text="result.diffDisplay"></strong> below the average ideal weight.
                                            A gradual surplus of 300–500 kcal/day can help you gain weight healthily.
                                        </span>
                                    </div>
                                </div>
                                <div x-show="result.diffKg === 0" class="p-3 rounded-xl border bg-emerald-50 border-emerald-200 text-sm text-emerald-700">
                                    🎉 Your current weight matches the average ideal weight perfectly!
                                </div>
                            </div>
                        </template>

                        
                        <div class="card p-4">
                            <p class="text-sm font-semibold text-gray-700 mb-3">Formula Comparison</p>
                            <div class="space-y-3">
                                <template x-for="f in result.formulas" :key="f.key+'_bar'">
                                    <div>
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-sm font-medium text-gray-700" x-text="f.name"></span>
                                            <span class="text-sm font-bold" :style="'color:'+f.barColor" x-text="f.display"></span>
                                        </div>
                                        <div class="iw-cbar-track" style="height:12px">
                                            <div class="iw-cbar-fill" :style="'width:'+f.barPct+'%;background:'+f.barColor"></div>
                                        </div>
                                    </div>
                                </template>
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm font-medium text-gray-700">BMI Range Midpoint</span>
                                        <span class="text-sm font-bold text-indigo-600" x-text="result.bmiMidDisplay"></span>
                                    </div>
                                    <div class="iw-cbar-track" style="height:12px">
                                        <div class="iw-cbar-fill" :style="'width:'+result.bmiMidBarPct+'%;background:#6366f1'"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="card overflow-hidden">
                            <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100 bg-gray-50">
                                <span class="text-sm font-semibold text-gray-700">Formula Reference</span>
                                <button type="button" @click="showFormulaTable=!showFormulaTable" class="btn btn-secondary btn-sm flex items-center gap-1">
                                    <span x-text="showFormulaTable ? 'Hide' : 'Show'"></span>
                                    <svg class="w-3.5 h-3.5 transition-transform" :class="{'-rotate-180':showFormulaTable}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                            </div>
                            <div x-show="showFormulaTable" x-transition>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-xs border-collapse">
                                        <thead>
                                            <tr class="bg-gray-50">
                                                <th class="px-4 py-2.5 text-left font-bold text-gray-500 uppercase tracking-wide border-b border-gray-100">Formula</th>
                                                <th class="px-4 py-2.5 text-left font-bold text-gray-500 uppercase tracking-wide border-b border-gray-100">Year</th>
                                                <th class="px-4 py-2.5 text-left font-bold text-gray-500 uppercase tracking-wide border-b border-gray-100">Male (kg)</th>
                                                <th class="px-4 py-2.5 text-left font-bold text-gray-500 uppercase tracking-wide border-b border-gray-100">Female (kg)</th>
                                                <th class="px-4 py-2.5 text-left font-bold text-gray-500 uppercase tracking-wide border-b border-gray-100">Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="border-b border-gray-50 hover:bg-sky-50">
                                                <td class="px-4 py-2.5 font-semibold text-sky-700">Devine</td>
                                                <td class="px-4 py-2.5 text-gray-500">1974</td>
                                                <td class="px-4 py-2.5 font-mono text-gray-700">50 + 2.3×(H−60)</td>
                                                <td class="px-4 py-2.5 font-mono text-gray-700">45.5 + 2.3×(H−60)</td>
                                                <td class="px-4 py-2.5 text-gray-400">H in inches over 5&nbsp;ft</td>
                                            </tr>
                                            <tr class="border-b border-gray-50 hover:bg-sky-50">
                                                <td class="px-4 py-2.5 font-semibold text-sky-700">Robinson</td>
                                                <td class="px-4 py-2.5 text-gray-500">1983</td>
                                                <td class="px-4 py-2.5 font-mono text-gray-700">52 + 1.9×(H−60)</td>
                                                <td class="px-4 py-2.5 font-mono text-gray-700">49 + 1.7×(H−60)</td>
                                                <td class="px-4 py-2.5 text-gray-400">Revised Devine</td>
                                            </tr>
                                            <tr class="border-b border-gray-50 hover:bg-sky-50">
                                                <td class="px-4 py-2.5 font-semibold text-sky-700">Miller</td>
                                                <td class="px-4 py-2.5 text-gray-500">1983</td>
                                                <td class="px-4 py-2.5 font-mono text-gray-700">56.2 + 1.41×(H−60)</td>
                                                <td class="px-4 py-2.5 font-mono text-gray-700">53.1 + 1.36×(H−60)</td>
                                                <td class="px-4 py-2.5 text-gray-400">Revised Devine</td>
                                            </tr>
                                            <tr class="hover:bg-sky-50">
                                                <td class="px-4 py-2.5 font-semibold text-sky-700">Hamwi</td>
                                                <td class="px-4 py-2.5 text-gray-500">1964</td>
                                                <td class="px-4 py-2.5 font-mono text-gray-700">48 + 2.7×(H−60)</td>
                                                <td class="px-4 py-2.5 font-mono text-gray-700">45.5 + 2.2×(H−60)</td>
                                                <td class="px-4 py-2.5 text-gray-400">Oldest formula</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        
                        <div class="card p-4">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="text-sm font-medium text-gray-600">Export:</span>
                                <button type="button" @click="copySummary()"
                                        class="btn btn-secondary btn-sm"
                                        :class="copyFlash ? 'bg-sky-50 text-sky-700' : ''"
                                        x-text="copyFlash ? '✓ Copied!' : 'Copy Summary'"></button>
                                <button type="button" @click="downloadSummary()" class="btn btn-secondary btn-sm">⬇ Download .txt</button>
                                <button type="button" @click="clearAll()" class="btn btn-secondary btn-sm ml-auto text-red-500 hover:text-red-600 hover:bg-red-50">✕ Reset</button>
                            </div>
                        </div>

                    </div>
                </template>

                
                <div x-show="phase==='idle'">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-4">
                        <?php $__currentLoopData = [
                            ['⚖️','4 Formulas','Devine, Robinson, Miller & Hamwi with side-by-side comparison'],
                            ['📊','BMI Range','Healthy weight window based on BMI 18.5–24.9'],
                            ['🎯','Average','Average across all formulas for a balanced estimate'],
                            ['📏','Any Height','Works for metric (cm) and imperial (ft/in) inputs'],
                            ['🔄','Current Weight','Optional: compare your weight to the ideal range'],
                            ['📋','Reference Table','All formula equations explained clearly'],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$icon,$title,$desc]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card p-4 text-center hover:border-sky-200 transition-colors">
                            <p class="text-2xl mb-1.5"><?php echo e($icon); ?></p>
                            <p class="text-sm font-semibold text-gray-700"><?php echo e($title); ?></p>
                            <p class="text-xs text-gray-400 mt-1 leading-snug"><?php echo e($desc); ?></p>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="card p-4" style="background:linear-gradient(135deg,#f0f9ff,#e0f2fe);border-color:#bae6fd">
                        <p class="text-sm font-semibold text-sky-700 mb-2">📐 Devine Formula (most widely used)</p>
                        <div class="space-y-1 text-xs font-mono text-gray-600">
                            <p><strong>Male:</strong>   IBW = 50 kg + 2.3 kg × (each inch over 5 ft)</p>
                            <p><strong>Female:</strong> IBW = 45.5 kg + 2.3 kg × (each inch over 5 ft)</p>
                            <p class="text-gray-400 mt-1">Robinson, Miller & Hamwi use the same structure with different constants.</p>
                        </div>
                    </div>
                </div>

                <?php if($relatedTools->count()): ?>
                <div x-show="phase==='idle'">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Related Tools</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <?php $__currentLoopData = $relatedTools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('tools.show', $related->slug)); ?>" class="card-hover p-4 flex items-center gap-3 no-underline">
                            <span class="text-xl"><?php echo e($related->icon); ?></span>
                            <p class="text-sm font-medium text-gray-800 truncate"><?php echo e($related->name); ?></p>
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
function iwCalc() {
    return {
        /* ── Inputs ── */
        gender:            'male',
        unitSystem:        'imperial',
        heightFt:          '',
        heightIn:          '',
        heightCm:          '',
        showCurrentWeight: false,
        currentWeight:     '',

        /* ── UI state ── */
        phase:            'idle',
        error:            '',
        result:           null,
        showFormulaTable: false,
        copyFlash:        false,

        /* ── Formula definitions ── */
        _formulaDefs: [
            {
                key: 'devine', name: 'Devine', year: '1974',
                desc: 'Most widely used in clinical settings, especially for drug dosing calculations.',
                isBest: true,
                barColor: '#0ea5e9',
                calc: function(h60, gender) {
                    return gender === 'male' ? 50 + 2.3 * h60 : 45.5 + 2.3 * h60;
                }
            },
            {
                key: 'robinson', name: 'Robinson', year: '1983',
                desc: 'A revised version of Devine — tends to give slightly higher estimates for males.',
                barColor: '#8b5cf6',
                calc: function(h60, gender) {
                    return gender === 'male' ? 52 + 1.9 * h60 : 49 + 1.7 * h60;
                }
            },
            {
                key: 'miller', name: 'Miller', year: '1983',
                desc: 'Another Devine revision — gives the highest estimates, especially for taller individuals.',
                barColor: '#10b981',
                calc: function(h60, gender) {
                    return gender === 'male' ? 56.2 + 1.41 * h60 : 53.1 + 1.36 * h60;
                }
            },
            {
                key: 'hamwi', name: 'Hamwi', year: '1964',
                desc: 'The oldest formula — commonly used as a quick clinical reference and tends to give slightly lower values.',
                barColor: '#f59e0b',
                calc: function(h60, gender) {
                    return gender === 'male' ? 48 + 2.7 * h60 : 45.5 + 2.2 * h60;
                }
            },
        ],

        init() {},

        /* ── Height converters ── */
        _imperialToCm() {
            var ft  = parseFloat(this.heightFt) || 0;
            var inn = parseFloat(this.heightIn) || 0;
            return ft * 30.48 + inn * 2.54;
        },
        _metricToFtIn() {
            var cm = parseFloat(this.heightCm) || 0;
            if (!cm) return '';
            var totalIn = cm / 2.54;
            var ft = Math.floor(totalIn / 12);
            var inches = Math.round(totalIn % 12);
            return ft + "'" + inches + '"';
        },
        get _heightCm() {
            if (this.unitSystem === 'metric') return parseFloat(this.heightCm) || 0;
            return this._imperialToCm();
        },
        get _heightInches() {
            return this._heightCm / 2.54;
        },
        get _currentWeightKg() {
            if (!this.showCurrentWeight || !this.currentWeight) return 0;
            var v = parseFloat(this.currentWeight) || 0;
            return this.unitSystem === 'metric' ? v : v * 0.453592;
        },

        switchUnit(u) {
            if (u === this.unitSystem) return;
            if (u === 'metric') {
                var cm = this._heightCm;
                if (cm > 0) this.heightCm = cm.toFixed(0);
                if (this.showCurrentWeight && this.currentWeight) {
                    var lbs = parseFloat(this.currentWeight) || 0;
                    if (lbs > 0) this.currentWeight = (lbs * 0.453592).toFixed(1);
                }
            } else {
                var cm2 = parseFloat(this.heightCm) || 0;
                if (cm2 > 0) {
                    var ti = cm2 / 2.54;
                    this.heightFt = String(Math.floor(ti / 12));
                    this.heightIn = String(Math.round(ti % 12));
                }
                if (this.showCurrentWeight && this.currentWeight) {
                    var kg = parseFloat(this.currentWeight) || 0;
                    if (kg > 0) this.currentWeight = (kg * 2.20462).toFixed(1);
                }
            }
            this.unitSystem = u;
            this.autoCalc();
        },

        autoCalc() {
            try {
                var h = this._heightCm;
                if (!h || isNaN(h) || h < 100 || h > 250) return;
                this.calculate();
            } catch(e) {}
        },

        calculate() {
            this.error = '';
            var self = this;
            try {
                var v = this._validate();
                this.phase = 'loading';
                setTimeout(function() {
                    try {
                        self.result = self._compute(v);
                        self.phase = 'done';
                        if (window.innerWidth < 1024) {
                            var el = document.getElementById('iw-results');
                            if (el) setTimeout(function(){ el.scrollIntoView({behavior:'smooth',block:'start'}); }, 80);
                        }
                    } catch(e) {
                        self.error = e.message || String(e);
                        self.phase = 'idle';
                    }
                }, 130);
            } catch(e) {
                this.error = e.message || String(e);
                this.phase = 'idle';
            }
        },

        _validate() {
            var h = this._heightCm;
            if (!h || isNaN(h) || h < 100) throw new Error('Enter a valid height (minimum 100 cm / 3\'3\").');
            if (h > 250) throw new Error('Enter a valid height (maximum 250 cm / 8\'2\").');

            var cw = this._currentWeightKg;
            if (this.showCurrentWeight && this.currentWeight && cw <= 0) {
                throw new Error('Enter a valid current weight (must be positive).');
            }
            if (cw > 600) throw new Error('Current weight seems too high. Please check your input.');

            return { hCm: h, hIn: h / 2.54, currentWeightKg: cw };
        },

        _compute(v) {
            var self       = this;
            var hIn        = v.hIn;
            var h60        = hIn - 60;       /* inches above 5 ft — can be negative */
            var hM         = v.hCm / 100;
            var gender     = this.gender;
            var isImperial = this.unitSystem === 'imperial';
            var belowFiveFt = hIn < 60;

            /* 4 formula results in kg */
            var vals = this._formulaDefs.map(function(fd) {
                return { key: fd.key, kg: fd.calc(h60, gender) };
            });

            /* Average */
            var totalKg = vals.reduce(function(s, v) { return s + v.kg; }, 0);
            var avgKg   = totalKg / vals.length;

            /* Min / max formula result */
            var minVal = vals.reduce(function(a, b) { return a.kg < b.kg ? a : b; });
            var maxVal = vals.reduce(function(a, b) { return a.kg > b.kg ? a : b; });

            /* BMI range */
            var bmiMinKg  = 18.5 * hM * hM;
            var bmiMaxKg  = 24.9 * hM * hM;
            var bmiMidKg  = (bmiMinKg + bmiMaxKg) / 2;

            /* Bar scale: use max of all values + bmiMaxKg */
            var allKgs    = vals.map(function(v){ return v.kg; }).concat([bmiMaxKg]);
            var barMaxKg  = Math.max.apply(null, allKgs) * 1.1;

            /* Build formula objects */
            var formulas = this._formulaDefs.map(function(fd, i) {
                var kg  = vals[i].kg;
                var lbs = kg * 2.20462;
                return {
                    key:        fd.key,
                    name:       fd.name,
                    year:       fd.year,
                    desc:       fd.desc,
                    isBest:     fd.isBest || false,
                    barColor:   fd.barColor,
                    kg:         kg,
                    display:    isImperial ? self.fmtWt(lbs, 'lbs') : self.fmtWt(kg, 'kg'),
                    altDisplay: isImperial ? self.fmtWt(kg, 'kg') + ' / ' + self.fmtWt(lbs, 'lbs') + ' lbs'
                                           : self.fmtWt(lbs, 'lbs') + ' lbs',
                    barPct:     Math.min(100, (kg / barMaxKg) * 100),
                };
            });

            /* Current weight analysis */
            var cwKg           = v.currentWeightKg;
            var diffKg         = cwKg > 0 ? cwKg - avgKg : 0;
            var diffLbs        = diffKg * 2.20462;
            var weeksTo500     = cwKg > 0 && diffKg > 0 ? Math.round(Math.abs(diffKg) / 0.4536) : 0;
            var weightStatusColor, weightStatusLabel;
            if (cwKg <= 0) {
                weightStatusColor = '#64748b'; weightStatusLabel = '—';
            } else {
                var bmi = cwKg / (hM * hM);
                if (bmi < 18.5)      { weightStatusColor = '#3b82f6'; weightStatusLabel = 'Underweight'; }
                else if (bmi < 25)   { weightStatusColor = '#10b981'; weightStatusLabel = 'Normal'; }
                else if (bmi < 30)   { weightStatusColor = '#eab308'; weightStatusLabel = 'Overweight'; }
                else                 { weightStatusColor = '#ef4444'; weightStatusLabel = 'Obese'; }
            }

            /* Range track thumb position: map cwKg between bmiMin-1 and bmiMax+1 */
            var trackMin = bmiMinKg * 0.8, trackMax = bmiMaxKg * 1.2;
            var currentWeightPct = cwKg > 0
                ? Math.min(97, Math.max(3, ((cwKg - trackMin) / (trackMax - trackMin)) * 100))
                : 50;

            /* BMI mid bar pct */
            var bmiMidBarPct = Math.min(100, (bmiMidKg / barMaxKg) * 100);

            /* Displays */
            var fmtKgLbs = function(kg) {
                if (isImperial) return self.fmtWt(kg * 2.20462, 'lbs');
                return self.fmtWt(kg, 'kg');
            };
            var fmtRange = function(minKg, maxKg) {
                if (isImperial) return self.fmtWt(minKg*2.20462,'lbs') + ' – ' + self.fmtWt(maxKg*2.20462,'lbs') + ' lbs';
                return self.fmtWt(minKg,'kg') + ' – ' + self.fmtWt(maxKg,'kg') + ' kg';
            };

            var heightDisplay;
            if (isImperial) {
                var ft  = Math.floor(hIn / 12);
                var inn = Math.round(hIn % 12);
                heightDisplay = ft + "'" + inn + '"  (' + v.hCm.toFixed(0) + ' cm)';
            } else {
                heightDisplay = v.hCm.toFixed(0) + ' cm (' + this._metricToFtIn() + ')';
            }

            var cwDisplay = cwKg > 0 ? fmtKgLbs(cwKg) : '';
            var diffDisplay;
            if (cwKg > 0) {
                if (isImperial) diffDisplay = (diffLbs >= 0 ? '+' : '') + this.fmt1(diffLbs) + ' lbs';
                else            diffDisplay = (diffKg  >= 0 ? '+' : '') + this.fmt1(diffKg)  + ' kg';
            } else diffDisplay = '';

            return {
                gender:            gender,
                heightDisplay:     heightDisplay,
                formulas:          formulas,
                avgKg:             avgKg,
                avgDisplay:        fmtKgLbs(avgKg),
                formulaMinDisplay: fmtKgLbs(minVal.kg),
                formulaMaxDisplay: fmtKgLbs(maxVal.kg),
                formulaMinFormula: this._formulaDefs.find(function(fd){ return fd.key===minVal.key; }).name,
                formulaMaxFormula: this._formulaDefs.find(function(fd){ return fd.key===maxVal.key; }).name,
                bmiMinKg:          bmiMinKg,
                bmiMaxKg:          bmiMaxKg,
                bmiMidKg:          bmiMidKg,
                bmiRangeDisplay:   fmtRange(bmiMinKg, bmiMaxKg),
                bmiMinDisplay:     fmtKgLbs(bmiMinKg),
                bmiMaxDisplay:     fmtKgLbs(bmiMaxKg),
                bmiMidDisplay:     fmtKgLbs(bmiMidKg),
                bmiMidBarPct:      bmiMidBarPct,
                currentWeightKg:   cwKg,
                currentWeightDisplay: cwDisplay,
                currentWeightPct:  currentWeightPct,
                diffKg:            diffKg,
                diffDisplay:       diffDisplay,
                diffNote:          diffKg > 0 ? 'above average ideal' : (diffKg < 0 ? 'below average ideal' : 'on target'),
                weeksTo500:        weeksTo500,
                weightStatusColor: weightStatusColor,
                weightStatusLabel: weightStatusLabel,
                belowFiveFt:       belowFiveFt,
                isImperial:        isImperial,
            };
        },

        /* ── Formatters ── */
        fmt1(v) {
            if (v === null || isNaN(v) || !isFinite(v)) return '—';
            return Math.abs(v).toFixed(1).replace(/\.0$/, '');
        },
        fmtWt(v, unit) {
            if (v === null || isNaN(v) || !isFinite(v)) return '—';
            var rounded = parseFloat(v.toFixed(1));
            return rounded.toLocaleString(undefined, {minimumFractionDigits:0, maximumFractionDigits:1}) + ' ' + unit;
        },

        loadSample() {
            this.gender      = 'male';
            this.unitSystem  = 'imperial';
            this.heightFt    = '5';
            this.heightIn    = '10';
            this.heightCm    = '';
            this.showCurrentWeight = true;
            this.currentWeight     = '195';
            this.error  = '';
            this.result = null;
            this.phase  = 'idle';
            var self = this;
            this.$nextTick(function(){ self.calculate(); });
        },

        clearAll() {
            this.error  = '';
            this.result = null;
            this.phase  = 'idle';
        },

        /* ── Export ── */
        _buildSummary() {
            if (!this.result) return '';
            var r = this.result;
            var lines = [
                'Ideal Weight Calculator Results',
                '================================',
                'Height  : ' + r.heightDisplay,
                'Gender  : ' + (r.gender === 'male' ? 'Male' : 'Female'),
                '',
                'FORMULA RESULTS:',
            ];
            r.formulas.forEach(function(f) {
                lines.push('  ' + f.name.padEnd(10) + ' (' + f.year + ')  :  ' + f.display + '  (' + f.altDisplay + ')');
            });
            lines = lines.concat([
                '',
                'Average (all formulas) : ' + r.avgDisplay,
                'Healthy BMI range      : ' + r.bmiRangeDisplay,
            ]);
            if (r.currentWeightKg > 0) {
                lines = lines.concat([
                    '',
                    'CURRENT WEIGHT ANALYSIS:',
                    '  Current weight  : ' + r.currentWeightDisplay,
                    '  Difference      : ' + r.diffDisplay + ' (' + r.diffNote + ')',
                    '  BMI Status      : ' + r.weightStatusLabel,
                ]);
            }
            return lines.join('\n');
        },

        async copySummary() {
            var text = this._buildSummary(); if (!text) return;
            try { await navigator.clipboard.writeText(text); } catch(e) {
                var ta = document.createElement('textarea'); ta.value = text;
                ta.style.cssText = 'position:fixed;opacity:0;'; document.body.appendChild(ta);
                ta.select(); document.execCommand('copy'); document.body.removeChild(ta);
            }
            var self = this; this.copyFlash = true;
            setTimeout(function(){ self.copyFlash = false; }, 1800);
        },

        downloadSummary() {
            var text = this._buildSummary(); if (!text) return;
            var blob = new Blob([text], { type:'text/plain;charset=utf-8' });
            var url  = URL.createObjectURL(blob);
            var a    = document.createElement('a'); a.href = url; a.download = 'ideal-weight-results.txt';
            document.body.appendChild(a); a.click(); document.body.removeChild(a); URL.revokeObjectURL(url);
        },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\ideal-weight-calculator.blade.php ENDPATH**/ ?>