@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')
<style>
/* ══════════════════════════════════════════════
   Calorie Calculator  —  prefix: cl-
══════════════════════════════════════════════ */

/* Gender / unit toggle pill */
.cl-pill { display:flex; background:#f1f5f9; border-radius:.625rem; padding:.2rem; gap:.15rem; }
.cl-pill-btn { flex:1; padding:.35rem .75rem; border-radius:.45rem; font-size:.78rem; font-weight:600; color:#64748b; cursor:pointer; border:none; background:none; transition:all .15s; white-space:nowrap; text-align:center; }
.cl-pill-btn.active { background:#fff; box-shadow:0 1px 4px rgba(0,0,0,.1); }
.cl-pill-btn.active.emerald { color:#059669; }
.cl-pill-btn.active.blue    { color:#2563eb; }
.cl-pill-btn.active.rose    { color:#e11d48; }
.cl-pill-btn.active.brand   { color:#4f46e5; }

/* Hero */
.cl-hero { font-size:clamp(2.6rem,5.5vw,4rem); font-weight:900; line-height:1; letter-spacing:-.03em; background:linear-gradient(135deg,#059669 0%,#10b981 55%,#34d399 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }

/* Stat card */
.cl-stat { background:#fff; border:1.5px solid #e2e8f0; border-radius:1.125rem; padding:1rem .9rem; display:flex; flex-direction:column; align-items:center; gap:.3rem; text-align:center; transition:all .15s; }
.cl-stat:hover { border-color:#a7f3d0; box-shadow:0 4px 16px rgba(5,150,105,.07); transform:translateY(-1px); }
.cl-stat-lbl { font-size:.62rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.07em; }
.cl-stat-val { font-size:1.3rem; font-weight:800; line-height:1.1; }
.cl-stat-val.emerald { background:linear-gradient(135deg,#059669,#10b981); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.cl-stat-val.brand   { background:linear-gradient(135deg,#4f46e5,#7c3aed); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.cl-stat-val.rose    { background:linear-gradient(135deg,#e11d48,#f43f5e); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.cl-stat-val.amber   { background:linear-gradient(135deg,#d97706,#f59e0b); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.cl-stat-sub { font-size:.65rem; color:#94a3b8; }

/* Goal cards */
.cl-goal { border-radius:1rem; border:1.5px solid #e2e8f0; background:#fff; padding:.9rem 1rem; display:flex; align-items:center; justify-content:space-between; gap:.75rem; transition:all .15s; cursor:default; }
.cl-goal:hover { box-shadow:0 4px 12px rgba(0,0,0,.06); transform:translateY(-1px); }
.cl-goal.maintain { border-color:#a7f3d0; background:#f0fdf4; }
.cl-goal-dot { width:10px; height:10px; border-radius:50%; flex-shrink:0; }
.cl-goal-cal { font-size:1.35rem; font-weight:900; font-variant-numeric:tabular-nums; line-height:1; }
.cl-goal-lbl { font-size:.75rem; font-weight:600; color:#374151; }
.cl-goal-note { font-size:.63rem; color:#94a3b8; }
.cl-goal-delta { font-size:.7rem; font-weight:700; padding:.15rem .5rem; border-radius:.35rem; white-space:nowrap; }

/* Activity option */
.cl-act { display:flex; align-items:flex-start; gap:.75rem; padding:.65rem .75rem; border-radius:.75rem; border:1.5px solid #e2e8f0; cursor:pointer; transition:all .12s; margin-bottom:.5rem; }
.cl-act:hover { border-color:#a7f3d0; background:#f0fdf4; }
.cl-act.selected { border-color:#10b981; background:#ecfdf5; }
.cl-act-radio { width:16px; height:16px; border-radius:50%; border:2px solid #d1d5db; flex-shrink:0; margin-top:.15rem; display:flex; align-items:center; justify-content:center; transition:border-color .12s; }
.cl-act.selected .cl-act-radio { border-color:#10b981; background:#10b981; }
.cl-act-radio-dot { width:6px; height:6px; border-radius:50%; background:white; }

/* Macro bar */
.cl-macro-bar { height:14px; border-radius:9999px; overflow:hidden; display:flex; }
.cl-macro-p { background:#6366f1; }
.cl-macro-c { background:#10b981; }
.cl-macro-f { background:#f59e0b; }
.cl-macro-legend-dot { width:9px; height:9px; border-radius:50%; flex-shrink:0; }

/* Meal card */
.cl-meal { border:1.5px solid #e2e8f0; border-radius:.9rem; padding:.85rem 1rem; background:#fff; transition:all .15s; }
.cl-meal:hover { border-color:#a7f3d0; }

/* BMI gauge bar */
.cl-bmi-track { height:12px; border-radius:9999px; background:linear-gradient(to right,#3b82f6 0%,#10b981 30%,#10b981 50%,#eab308 67%,#ef4444 83%,#7f1d1d 100%); position:relative; }
.cl-bmi-needle { width:2px; height:20px; background:#1e293b; border-radius:1px; position:absolute; top:-4px; transform:translateX(-50%); transition:left .5s ease; }

/* Pre/suf wrappers */
.cl-pre-wrap { display:flex; align-items:stretch; }
.cl-pre { display:flex; align-items:center; padding:0 .75rem; background:#f8fafc; border:1px solid #d1d5db; border-right:none; border-radius:.75rem 0 0 .75rem; font-size:.82rem; font-weight:700; color:#374151; white-space:nowrap; }
.cl-pre-wrap .form-input { border-radius:0 .75rem .75rem 0 !important; }

/* Section divider */
.cl-div { display:flex; align-items:center; gap:.6rem; color:#94a3b8; font-size:.62rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; }
.cl-div::before,.cl-div::after { content:''; flex:1; height:1px; background:#f1f5f9; }

/* Formula tab */
.cl-ftab { padding:.35rem .75rem; border-radius:.45rem; font-size:.72rem; font-weight:600; color:#64748b; cursor:pointer; border:1.5px solid transparent; background:#f1f5f9; transition:all .15s; }
.cl-ftab.active { border-color:#10b981; color:#059669; background:#ecfdf5; }

/* Row */
.cl-row { display:flex; justify-content:space-between; align-items:center; padding:.45rem .75rem; border-radius:.5rem; }
.cl-row:hover { background:#f8fafc; }

/* Shimmer */
@keyframes clShim { 0%{background-position:-600px 0} 100%{background-position:600px 0} }
.cl-shim { height:5.5rem; border-radius:1.125rem; background:linear-gradient(90deg,#ecfdf5 25%,#d1fae5 50%,#ecfdf5 75%); background-size:1200px 100%; animation:clShim 1.4s infinite; }

@keyframes clIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
.cl-in { animation:clIn .3s ease-out; }
</style>

<div class="min-h-screen bg-gray-50" x-data="calCalc()" x-init="init()">

    {{-- Page Header --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-8 md:py-10">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">{{ $tool->icon }} {{ $tool->name }}</h1>
                    <p class="text-gray-500 mt-2 max-w-2xl">Calculate your <strong>BMR</strong> and <strong>daily calorie needs</strong> using the Mifflin-St Jeor or Harris-Benedict formula. Get maintenance, weight loss, and weight gain targets with macro breakdowns.</p>
                </div>
                <div class="flex flex-wrap gap-2 shrink-0">
                    <span class="badge badge-success">Free</span>
                    <span class="badge badge-gray">Mifflin-St Jeor</span>
                    <span class="badge badge-primary">Metric &amp; Imperial</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-6 md:py-8">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 items-start">

            {{-- ═══════════════════════════════
                 LEFT — Input Panel
            ═══════════════════════════════ --}}
            <div class="lg:col-span-2">
                <div class="card">
                    <div class="px-5 pt-5 pb-1">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Your Details</p>
                    </div>

                    <div class="px-5 pb-5 space-y-4">

                        {{-- Gender --}}
                        <div>
                            <label class="form-label">Biological Sex</label>
                            <div class="cl-pill">
                                <button type="button" class="cl-pill-btn" :class="{active: gender==='male', blue: gender==='male'}" @click="gender='male'; autoCalc()">
                                    ♂ Male
                                </button>
                                <button type="button" class="cl-pill-btn" :class="{active: gender==='female', rose: gender==='female'}" @click="gender='female'; autoCalc()">
                                    ♀ Female
                                </button>
                            </div>
                        </div>

                        {{-- Unit system --}}
                        <div>
                            <label class="form-label">Unit System</label>
                            <div class="cl-pill">
                                <button type="button" class="cl-pill-btn" :class="{active: unitSystem==='imperial', brand: unitSystem==='imperial'}" @click="switchUnit('imperial')">
                                    🇺🇸 Imperial (lbs, ft)
                                </button>
                                <button type="button" class="cl-pill-btn" :class="{active: unitSystem==='metric', emerald: unitSystem==='metric'}" @click="switchUnit('metric')">
                                    🌍 Metric (kg, cm)
                                </button>
                            </div>
                        </div>

                        {{-- Age --}}
                        <div>
                            <label class="form-label">Age</label>
                            <div class="cl-pre-wrap">
                                <span class="cl-pre">yrs</span>
                                <input type="number" step="1" min="1" max="120" x-model="age"
                                       @input.debounce.350ms="autoCalc()"
                                       class="form-input" placeholder="e.g. 30">
                            </div>
                        </div>

                        {{-- Height --}}
                        <div>
                            <label class="form-label">Height</label>
                            <div x-show="unitSystem==='imperial'" class="flex gap-2">
                                <div class="cl-pre-wrap flex-1">
                                    <span class="cl-pre">ft</span>
                                    <input type="number" step="1" min="1" max="9" x-model="heightFt"
                                           @input.debounce.350ms="autoCalc()"
                                           class="form-input" placeholder="5">
                                </div>
                                <div class="cl-pre-wrap flex-1">
                                    <span class="cl-pre">in</span>
                                    <input type="number" step="1" min="0" max="11" x-model="heightIn"
                                           @input.debounce.350ms="autoCalc()"
                                           class="form-input" placeholder="10">
                                </div>
                            </div>
                            <div x-show="unitSystem==='metric'" class="cl-pre-wrap">
                                <span class="cl-pre">cm</span>
                                <input type="number" step="any" min="50" max="300" x-model="heightCm"
                                       @input.debounce.350ms="autoCalc()"
                                       class="form-input" placeholder="e.g. 178">
                            </div>
                            <p class="form-help" x-show="unitSystem==='imperial' && (heightFt || heightIn)" x-text="'≈ ' + imperialHeightToCm + ' cm'"></p>
                        </div>

                        {{-- Weight --}}
                        <div>
                            <label class="form-label">Weight</label>
                            <div x-show="unitSystem==='imperial'" class="cl-pre-wrap">
                                <span class="cl-pre">lbs</span>
                                <input type="number" step="any" min="1" x-model="weightLbs"
                                       @input.debounce.350ms="autoCalc()"
                                       class="form-input" placeholder="e.g. 165">
                            </div>
                            <div x-show="unitSystem==='metric'" class="cl-pre-wrap">
                                <span class="cl-pre">kg</span>
                                <input type="number" step="any" min="1" x-model="weightKg"
                                       @input.debounce.350ms="autoCalc()"
                                       class="form-input" placeholder="e.g. 75">
                            </div>
                            <p class="form-help" x-show="unitSystem==='imperial' && weightLbs" x-text="'≈ ' + (parseFloat(weightLbs)*0.453592).toFixed(1) + ' kg'"></p>
                            <p class="form-help" x-show="unitSystem==='metric' && weightKg"    x-text="'≈ ' + (parseFloat(weightKg)*2.20462).toFixed(1) + ' lbs'"></p>
                        </div>

                        <div class="cl-div pt-1">Activity Level</div>

                        {{-- Activity --}}
                        <div class="space-y-1.5" role="radiogroup" aria-label="Activity level">
                            <template x-for="act in activities" :key="act.value">
                                <div class="cl-act" :class="{selected: activity===act.value}" @click="activity=act.value; autoCalc()" role="radio" :aria-checked="activity===act.value" tabindex="0" @keydown.space.prevent="activity=act.value; autoCalc()">
                                    <div class="cl-act-radio">
                                        <div class="cl-act-radio-dot" x-show="activity===act.value"></div>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-800" x-text="act.label"></p>
                                        <p class="text-xs text-gray-400 leading-snug mt-0.5" x-text="act.desc"></p>
                                    </div>
                                    <span class="text-xs font-bold text-emerald-600 shrink-0 ml-auto" x-text="'×'+act.value"></span>
                                </div>
                            </template>
                        </div>

                        <div class="cl-div">Formula</div>

                        {{-- Formula --}}
                        <div class="flex gap-2">
                            <button type="button" class="cl-ftab flex-1" :class="{active: formula==='mifflin'}" @click="formula='mifflin'; autoCalc()">
                                Mifflin-St Jeor
                                <span class="block text-xs font-normal text-gray-400 mt-0.5">Most accurate (2005)</span>
                            </button>
                            <button type="button" class="cl-ftab flex-1" :class="{active: formula==='harris'}" @click="formula='harris'; autoCalc()">
                                Harris-Benedict
                                <span class="block text-xs font-normal text-gray-400 mt-0.5">Classic revised (1984)</span>
                            </button>
                        </div>

                        {{-- Error --}}
                        <div x-show="error" x-transition class="flex items-start gap-2 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span x-text="error"></span>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex flex-wrap gap-2 pt-1">
                            <button type="button" @click="calculate()" class="btn flex-1 sm:flex-none btn-lg font-bold" style="background:linear-gradient(135deg,#059669,#10b981);color:white;border:none;box-shadow:0 4px 14px rgba(5,150,105,.3)">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Calculate
                            </button>
                            <button type="button" @click="loadSample()" class="btn btn-secondary">📋 Sample</button>
                            <button type="button" @click="clearAll()" x-show="phase==='done' || error" class="btn btn-secondary">✕ Clear</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════
                 RIGHT — Results Panel
            ═══════════════════════════════ --}}
            <div class="lg:col-span-3 space-y-4" id="cl-results">

                {{-- Shimmer --}}
                <div x-show="phase==='loading'" class="grid grid-cols-2 gap-3">
                    <template x-for="i in 4" :key="i"><div class="cl-shim"></div></template>
                </div>

                {{-- ══ RESULTS ══ --}}
                <template x-if="phase==='done' && result">
                    <div class="space-y-4 cl-in">

                        {{-- Hero: TDEE --}}
                        <div class="card overflow-hidden">
                            <div style="background:linear-gradient(135deg,#ecfdf5 0%,#d1fae5 100%);" class="px-6 py-5">
                                <p class="text-xs font-bold text-emerald-500 uppercase tracking-widest mb-1">Daily Calorie Needs (TDEE)</p>
                                <p class="cl-hero" x-text="fmt0(result.tdee) + ' kcal'"></p>
                                <p class="text-xs text-gray-500 mt-2">
                                    BMR: <strong x-text="fmt0(result.bmr) + ' kcal'"></strong>
                                    &nbsp;·&nbsp;
                                    Activity multiplier: <strong x-text="'×' + result.actMult"></strong>
                                    &nbsp;·&nbsp;
                                    <span x-text="result.formulaLabel"></span>
                                </p>
                            </div>
                        </div>

                        {{-- Stat cards --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div class="cl-stat">
                                <div class="cl-stat-lbl">BMR</div>
                                <div class="cl-stat-val emerald" x-text="fmt0(result.bmr)"></div>
                                <div class="cl-stat-sub">kcal / day at rest</div>
                            </div>
                            <div class="cl-stat">
                                <div class="cl-stat-lbl">BMI</div>
                                <div class="cl-stat-val" :style="'color:'+result.bmiColor" x-text="result.bmi.toFixed(1)"></div>
                                <div class="cl-stat-sub" x-text="result.bmiCat"></div>
                            </div>
                            <div class="cl-stat">
                                <div class="cl-stat-lbl">Ideal Weight</div>
                                <div class="cl-stat-val brand" style="font-size:1.05rem" x-text="result.idealRange"></div>
                                <div class="cl-stat-sub">BMI 18.5 – 24.9</div>
                            </div>
                            <div class="cl-stat">
                                <div class="cl-stat-lbl">Body Weight</div>
                                <div class="cl-stat-val" :style="'color:'+result.bmiColor" style="font-size:1.05rem" x-text="result.weightDisplay"></div>
                                <div class="cl-stat-sub" x-text="result.weightAlt"></div>
                            </div>
                        </div>

                        {{-- BMI Visual --}}
                        <div class="card p-4">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-sm font-semibold text-gray-700">BMI Scale</p>
                                <span class="text-sm font-bold px-2.5 py-0.5 rounded-full text-white text-xs" :style="'background:'+result.bmiColor" x-text="result.bmiCat + ' (' + result.bmi.toFixed(1) + ')'"></span>
                            </div>
                            <div class="relative mb-4">
                                <div class="cl-bmi-track"></div>
                                <div class="cl-bmi-needle" :style="'left:'+result.bmiNeedlePct+'%'"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-400 mt-3">
                                <span>Under<br>weight<br>&lt;18.5</span>
                                <span class="text-center">Normal<br>18.5–25</span>
                                <span class="text-center">Over<br>weight<br>25–30</span>
                                <span class="text-right">Obese<br>30+</span>
                            </div>
                        </div>

                        {{-- Calorie Goals --}}
                        <div class="card p-4">
                            <p class="text-sm font-semibold text-gray-700 mb-3">Daily Calorie Targets by Goal</p>
                            <div class="space-y-2">
                                <template x-for="g in result.goals" :key="g.label">
                                    <div class="cl-goal" :class="{maintain: g.isMaintain}">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <span class="cl-goal-dot" :style="'background:'+g.color"></span>
                                            <div class="min-w-0">
                                                <div class="cl-goal-lbl" x-text="g.label"></div>
                                                <div class="cl-goal-note" x-text="g.note"></div>
                                            </div>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <div class="cl-goal-cal" :style="'color:'+g.color" x-text="fmt0(g.calories)"></div>
                                            <div class="text-xs text-gray-400">kcal/day</div>
                                        </div>
                                        <div>
                                            <span class="cl-goal-delta text-xs font-bold"
                                                  :style="g.change===0 ? 'background:#d1fae5;color:#065f46' : (g.change<0 ? 'background:#fef2f2;color:#991b1b' : 'background:#eff6ff;color:#1e40af')"
                                                  x-text="g.change===0 ? 'maintain' : (g.change>0 ? '+'+fmt0(g.change) : fmt0(g.change))">
                                            </span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <p class="text-xs text-gray-400 mt-3">* Minimum 1,200 kcal/day floor applied. Deficits above 500 kcal/day should be supervised.</p>
                        </div>

                        {{-- Macros --}}
                        <div class="card overflow-hidden">
                            <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100 bg-gray-50">
                                <span class="text-sm font-semibold text-gray-700">Macronutrient Breakdown</span>
                                <div class="flex gap-1.5">
                                    <button type="button" @click="macroGoal='cut'" class="cl-ftab btn-sm" :class="{active:macroGoal==='cut'}">Cut</button>
                                    <button type="button" @click="macroGoal='maintain'" class="cl-ftab btn-sm" :class="{active:macroGoal==='maintain'}">Maintain</button>
                                    <button type="button" @click="macroGoal='bulk'" class="cl-ftab btn-sm" :class="{active:macroGoal==='bulk'}">Bulk</button>
                                </div>
                            </div>
                            <div class="p-5 space-y-4">
                                <div>
                                    <div class="flex justify-between text-xs text-gray-500 mb-1.5">
                                        <span>Macronutrient split for <strong x-text="macroLabels[macroGoal]"></strong></span>
                                        <span x-text="fmt0(currentMacros.calories) + ' kcal target'"></span>
                                    </div>
                                    <div class="cl-macro-bar" style="height:18px;border-radius:9999px;overflow:hidden;">
                                        <div class="cl-macro-p transition-all duration-500" :style="'width:'+currentMacros.proteinPct+'%'"></div>
                                        <div class="cl-macro-c transition-all duration-500" :style="'width:'+currentMacros.carbPct+'%'"></div>
                                        <div class="cl-macro-f transition-all duration-500" :style="'width:'+currentMacros.fatPct+'%'"></div>
                                    </div>
                                    <div class="flex flex-wrap gap-x-4 gap-y-1 mt-2 text-xs">
                                        <span class="flex items-center gap-1.5"><span class="cl-macro-legend-dot" style="background:#6366f1"></span><span class="text-gray-500">Protein <strong x-text="fmt0(currentMacros.proteinG)+'g'"></strong> (<span x-text="currentMacros.proteinPct+'%'"></span>)</span></span>
                                        <span class="flex items-center gap-1.5"><span class="cl-macro-legend-dot" style="background:#10b981"></span><span class="text-gray-500">Carbs <strong x-text="fmt0(currentMacros.carbG)+'g'"></strong> (<span x-text="currentMacros.carbPct+'%'"></span>)</span></span>
                                        <span class="flex items-center gap-1.5"><span class="cl-macro-legend-dot" style="background:#f59e0b"></span><span class="text-gray-500">Fat <strong x-text="fmt0(currentMacros.fatG)+'g'"></strong> (<span x-text="currentMacros.fatPct+'%'"></span>)</span></span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 gap-3">
                                    <div class="text-center p-3 rounded-xl border-2" style="border-color:#e0e7ff;background:#eef2ff">
                                        <p class="text-2xl font-black" style="color:#4f46e5" x-text="fmt0(currentMacros.proteinG)+'g'"></p>
                                        <p class="text-xs font-semibold text-indigo-700">Protein</p>
                                        <p class="text-xs text-gray-400 mt-0.5" x-text="fmt0(currentMacros.proteinG*4)+' kcal'"></p>
                                    </div>
                                    <div class="text-center p-3 rounded-xl border-2" style="border-color:#a7f3d0;background:#ecfdf5">
                                        <p class="text-2xl font-black" style="color:#059669" x-text="fmt0(currentMacros.carbG)+'g'"></p>
                                        <p class="text-xs font-semibold text-emerald-700">Carbs</p>
                                        <p class="text-xs text-gray-400 mt-0.5" x-text="fmt0(currentMacros.carbG*4)+' kcal'"></p>
                                    </div>
                                    <div class="text-center p-3 rounded-xl border-2" style="border-color:#fde68a;background:#fffbeb">
                                        <p class="text-2xl font-black" style="color:#d97706" x-text="fmt0(currentMacros.fatG)+'g'"></p>
                                        <p class="text-xs font-semibold text-amber-700">Fat</p>
                                        <p class="text-xs text-gray-400 mt-0.5" x-text="fmt0(currentMacros.fatG*9)+' kcal'"></p>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-400">4 kcal/g protein &amp; carbs · 9 kcal/g fat · Values rounded to nearest gram.</p>
                            </div>
                        </div>

                        {{-- Meal planning --}}
                        <div class="card overflow-hidden">
                            <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100 bg-gray-50">
                                <span class="text-sm font-semibold text-gray-700">Meal Planning</span>
                                <div class="flex gap-1.5">
                                    <button type="button" @click="mealCount=3" class="cl-ftab btn-sm" :class="{active:mealCount===3}">3 meals</button>
                                    <button type="button" @click="mealCount=5" class="cl-ftab btn-sm" :class="{active:mealCount===5}">5 meals</button>
                                    <button type="button" @click="mealCount=6" class="cl-ftab btn-sm" :class="{active:mealCount===6}">6 meals</button>
                                </div>
                            </div>
                            <div class="p-5">
                                <p class="text-xs text-gray-500 mb-3">
                                    Based on <strong x-text="fmt0(currentMacros.calories) + ' kcal'"></strong> target
                                    (<span x-text="macroLabels[macroGoal]"></span>) split across <span x-text="mealCount"></span> meals.
                                </p>
                                <div class="space-y-2">
                                    <template x-for="meal in mealPlan" :key="meal.name">
                                        <div class="cl-meal flex items-center justify-between gap-3">
                                            <div class="flex items-center gap-3">
                                                <span class="text-xl" x-text="meal.icon"></span>
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-800" x-text="meal.name"></p>
                                                    <p class="text-xs text-gray-400" x-text="meal.time"></p>
                                                </div>
                                            </div>
                                            <div class="text-right shrink-0">
                                                <p class="text-lg font-black text-emerald-600" x-text="fmt0(meal.calories) + ' kcal'"></p>
                                                <p class="text-xs text-gray-400" x-text="'P:'+fmt0(meal.protein)+'g C:'+fmt0(meal.carbs)+'g F:'+fmt0(meal.fat)+'g'"></p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- Formula comparison --}}
                        <div class="card p-4">
                            <p class="text-sm font-semibold text-gray-700 mb-3">Formula Comparison</p>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="p-3 rounded-xl border-2" :class="formula==='mifflin' ? 'border-emerald-300 bg-emerald-50' : 'border-gray-100'">
                                    <p class="text-xs font-bold text-gray-500 mb-1">Mifflin-St Jeor</p>
                                    <p class="text-xl font-black text-emerald-600" x-text="fmt0(result.mifflinTdee) + ' kcal'"></p>
                                    <p class="text-xs text-gray-400 mt-0.5">BMR: <span x-text="fmt0(result.mifflinBmr)"></span></p>
                                    <p class="text-xs text-emerald-600 font-medium mt-1">✓ Most accurate</p>
                                </div>
                                <div class="p-3 rounded-xl border-2" :class="formula==='harris' ? 'border-indigo-300 bg-indigo-50' : 'border-gray-100'">
                                    <p class="text-xs font-bold text-gray-500 mb-1">Harris-Benedict</p>
                                    <p class="text-xl font-black text-indigo-600" x-text="fmt0(result.harrisTdee) + ' kcal'"></p>
                                    <p class="text-xs text-gray-400 mt-0.5">BMR: <span x-text="fmt0(result.harrisBmr)"></span></p>
                                    <p class="text-xs text-indigo-500 font-medium mt-1">Classic revised</p>
                                </div>
                            </div>
                        </div>

                        {{-- Activity comparison --}}
                        <div class="card p-4">
                            <p class="text-sm font-semibold text-gray-700 mb-3">Activity Level Comparison</p>
                            <div class="space-y-2">
                                <template x-for="act in result.activityComparison" :key="act.value">
                                    <div class="cl-row">
                                        <span class="text-sm text-gray-600 flex items-center gap-2">
                                            <span :class="act.value===activity ? 'text-emerald-500 font-bold' : ''" x-text="act.isCurrent ? '▶ ' + act.label : act.label"></span>
                                        </span>
                                        <div class="flex items-center gap-2">
                                            <div class="hidden sm:block w-20 h-1.5 rounded-full bg-gray-100 overflow-hidden">
                                                <div class="h-full rounded-full transition-all duration-500" style="background:#10b981" :style="'width:'+act.barPct+'%'"></div>
                                            </div>
                                            <span class="font-bold text-sm" :class="act.isCurrent ? 'text-emerald-600' : 'text-gray-700'" x-text="fmt0(act.tdee) + ' kcal'"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Export --}}
                        <div class="card p-4">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="text-sm font-medium text-gray-600">Export:</span>
                                <button type="button" @click="copySummary()" class="btn btn-secondary btn-sm"
                                        :class="copyFlash ? 'bg-emerald-50 text-emerald-700' : ''"
                                        x-text="copyFlash ? '✓ Copied!' : 'Copy Summary'"></button>
                                <button type="button" @click="downloadSummary()" class="btn btn-secondary btn-sm">⬇ Download .txt</button>
                                <button type="button" @click="clearAll()" class="btn btn-secondary btn-sm ml-auto text-red-500 hover:text-red-600 hover:bg-red-50">✕ Reset</button>
                            </div>
                        </div>

                    </div>
                </template>

                {{-- Idle state --}}
                <div x-show="phase==='idle'">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-4">
                        @foreach([
                            ['🔥','BMR','Your resting metabolic rate — calories burned at complete rest'],
                            ['⚡','TDEE','Total Daily Energy Expenditure — actual daily calorie burn'],
                            ['🎯','7 Goals','From extreme weight loss to fast muscle gain'],
                            ['🥗','Macros','Protein, carbs & fat split for cutting, maintaining, bulking'],
                            ['🍽','Meal Plans','3, 5 or 6 meal-per-day distribution'],
                            ['📐','BMI + Ideal Wt','Body Mass Index & healthy weight range for your height'],
                        ] as [$icon,$title,$desc])
                        <div class="card p-4 text-center hover:border-emerald-200 transition-colors">
                            <p class="text-2xl mb-1.5">{{ $icon }}</p>
                            <p class="text-sm font-semibold text-gray-700">{{ $title }}</p>
                            <p class="text-xs text-gray-400 mt-1 leading-snug">{{ $desc }}</p>
                        </div>
                        @endforeach
                    </div>
                    <div class="card p-4" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);border-color:#a7f3d0">
                        <p class="text-sm font-semibold text-emerald-700 mb-2">📐 How Calorie Needs Are Calculated</p>
                        <div class="space-y-1 text-xs font-mono text-gray-600">
                            <p><strong>Mifflin-St Jeor (♂):</strong> BMR = 10×W + 6.25×H − 5×A + 5</p>
                            <p><strong>Mifflin-St Jeor (♀):</strong> BMR = 10×W + 6.25×H − 5×A − 161</p>
                            <p><strong>TDEE</strong> = BMR × Activity Multiplier (1.2 – 1.9)</p>
                            <p class="text-gray-400">W=weight(kg), H=height(cm), A=age(yrs)</p>
                        </div>
                    </div>
                </div>

                @if($relatedTools->count())
                <div x-show="phase==='idle'">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Related Tools</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($relatedTools as $related)
                        <a href="{{ route('tools.show', $related->slug) }}" class="card-hover p-4 flex items-center gap-3 no-underline">
                            <span class="text-xl">{{ $related->icon }}</span>
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $related->name }}</p>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>{{-- /right --}}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function calCalc() {
    return {
        /* ── Inputs ── */
        gender:     'male',
        unitSystem: 'imperial',
        age:        '',
        heightFt:   '',
        heightIn:   '',
        heightCm:   '',
        weightLbs:  '',
        weightKg:   '',
        activity:   '1.55',
        formula:    'mifflin',

        /* ── UI state ── */
        phase:       'idle',
        error:       '',
        result:      null,
        macroGoal:   'maintain',
        mealCount:   3,
        copyFlash:   false,

        macroLabels: { cut:'cutting', maintain:'maintenance', bulk:'bulking' },

        activities: [
            { value:'1.2',   label:'Sedentary',         desc:'Little or no exercise, desk job' },
            { value:'1.375', label:'Lightly Active',     desc:'Light exercise 1–3 days/week' },
            { value:'1.55',  label:'Moderately Active',  desc:'Moderate exercise 3–5 days/week' },
            { value:'1.725', label:'Very Active',        desc:'Hard exercise 6–7 days/week' },
            { value:'1.9',   label:'Extra Active',       desc:'Very hard exercise + physical job' },
        ],

        /* ── Computed helpers ── */
        get imperialHeightToCm() {
            var ft = parseFloat(this.heightFt) || 0;
            var inn = parseFloat(this.heightIn) || 0;
            var cm = ft * 30.48 + inn * 2.54;
            return cm > 0 ? cm.toFixed(1) : '';
        },

        get _heightCm() {
            if (this.unitSystem === 'metric') return parseFloat(this.heightCm) || 0;
            var ft = parseFloat(this.heightFt) || 0;
            var inn = parseFloat(this.heightIn) || 0;
            return ft * 30.48 + inn * 2.54;
        },

        get _weightKg() {
            if (this.unitSystem === 'metric') return parseFloat(this.weightKg) || 0;
            return (parseFloat(this.weightLbs) || 0) * 0.453592;
        },

        /* Macros for current macroGoal */
        get currentMacros() {
            if (!this.result) return { calories:0, proteinG:0, carbG:0, fatG:0, proteinPct:30, carbPct:40, fatPct:30 };
            return this._getMacros(this.result.tdee, this.macroGoal);
        },

        /* Meal plan based on current macros + mealCount */
        get mealPlan() {
            if (!this.result) return [];
            var macros = this.currentMacros;
            var cal   = macros.calories;
            var prot  = macros.proteinG;
            var carb  = macros.carbG;
            var fat   = macros.fatG;
            var n     = this.mealCount;

            var templates3 = [
                { name:'Breakfast', icon:'🌅', time:'7:00 – 8:00 AM',  split:.25 },
                { name:'Lunch',     icon:'☀️',  time:'12:00 – 1:00 PM', split:.40 },
                { name:'Dinner',    icon:'🌙',  time:'6:00 – 7:00 PM',  split:.35 },
            ];
            var templates5 = [
                { name:'Breakfast',      icon:'🌅', time:'7:00 AM',  split:.22 },
                { name:'Morning Snack',  icon:'🍎', time:'10:00 AM', split:.12 },
                { name:'Lunch',          icon:'☀️',  time:'1:00 PM',  split:.28 },
                { name:'Afternoon Snack',icon:'🥜', time:'4:00 PM',  split:.10 },
                { name:'Dinner',         icon:'🌙', time:'7:00 PM',  split:.28 },
            ];
            var templates6 = [
                { name:'Breakfast',      icon:'🌅', time:'7:00 AM',  split:.20 },
                { name:'Morning Snack',  icon:'🍎', time:'9:30 AM',  split:.10 },
                { name:'Lunch',          icon:'☀️',  time:'12:30 PM', split:.25 },
                { name:'Pre-Workout',    icon:'⚡', time:'3:30 PM',  split:.12 },
                { name:'Dinner',         icon:'🌙', time:'6:30 PM',  split:.25 },
                { name:'Evening Snack',  icon:'🌿', time:'9:00 PM',  split:.08 },
            ];
            var tpls = n === 6 ? templates6 : (n === 5 ? templates5 : templates3);
            return tpls.map(function(t) {
                return {
                    name:     t.name,
                    icon:     t.icon,
                    time:     t.time,
                    calories: cal  * t.split,
                    protein:  prot * t.split,
                    carbs:    carb * t.split,
                    fat:      fat  * t.split,
                };
            });
        },

        init() {},

        switchUnit(u) {
            if (u === this.unitSystem) return;
            // Convert current values when switching
            if (u === 'metric') {
                var cm = this._heightCm;
                if (cm > 0) this.heightCm = cm.toFixed(1);
                var kg = this._weightKg;
                if (kg > 0) this.weightKg = kg.toFixed(1);
            } else {
                var cm2 = parseFloat(this.heightCm) || 0;
                if (cm2 > 0) {
                    var totalIn = cm2 / 2.54;
                    this.heightFt = String(Math.floor(totalIn / 12));
                    this.heightIn = String(Math.round(totalIn % 12));
                }
                var kg2 = parseFloat(this.weightKg) || 0;
                if (kg2 > 0) this.weightLbs = (kg2 * 2.20462).toFixed(1);
            }
            this.unitSystem = u;
            this.autoCalc();
        },

        autoCalc() {
            try {
                var age = parseInt(this.age);
                var h = this._heightCm;
                var w = this._weightKg;
                if (!age || age < 1 || age > 120) return;
                if (!h || h < 50 || h > 300) return;
                if (!w || w < 10 || w > 700) return;
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
                            var el = document.getElementById('cl-results');
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
            var age = parseInt(this.age);
            if (!age || age < 1 || age > 120) throw new Error('Enter a valid age between 1 and 120 years.');

            var h = this._heightCm;
            if (!h || isNaN(h) || h < 50 || h > 280) throw new Error('Enter a valid height (e.g. 5\'0\" – 7\'0\" or 150–215 cm).');

            var w = this._weightKg;
            if (!w || isNaN(w) || w < 10 || w > 650) throw new Error('Enter a valid weight.');

            return { age: age, h: h, w: w };
        },

        /* ── BMR calculators ── */
        _mifflinBmr(w, h, age, gender) {
            if (gender === 'male')   return 10*w + 6.25*h - 5*age + 5;
            else                     return 10*w + 6.25*h - 5*age - 161;
        },
        _harrisBmr(w, h, age, gender) {
            if (gender === 'male')   return 88.362 + 13.397*w + 4.799*h - 5.677*age;
            else                     return 447.593 + 9.247*w + 3.098*h - 4.330*age;
        },

        _compute(v) {
            var age = v.age, h = v.h, w = v.w;
            var actMult = parseFloat(this.activity);

            /* Both formulas for comparison card */
            var mifflinBmr = this._mifflinBmr(w, h, age, this.gender);
            var harrisBmr  = this._harrisBmr(w, h, age, this.gender);
            var mifflinTdee = mifflinBmr * actMult;
            var harrisTdee  = harrisBmr  * actMult;

            /* Active formula */
            var bmr  = this.formula === 'mifflin' ? mifflinBmr : harrisBmr;
            var tdee = bmr * actMult;

            /* BMI */
            var hm  = h / 100;
            var bmi = w / (hm * hm);
            var bmiCat, bmiColor;
            if (bmi < 18.5)      { bmiCat = 'Underweight';   bmiColor = '#3b82f6'; }
            else if (bmi < 25.0) { bmiCat = 'Normal Weight'; bmiColor = '#10b981'; }
            else if (bmi < 30.0) { bmiCat = 'Overweight';    bmiColor = '#eab308'; }
            else                 { bmiCat = 'Obese';          bmiColor = '#ef4444'; }

            /* BMI needle position (gauge spans ~10 to 40+) */
            var bmiMin = 10, bmiMax = 40;
            var bmiNeedlePct = Math.min(100, Math.max(0, ((bmi - bmiMin) / (bmiMax - bmiMin)) * 100));

            /* Ideal weight range (BMI 18.5–24.9) */
            var idealMinKg  = 18.5 * hm * hm;
            var idealMaxKg  = 24.9 * hm * hm;
            var idealMinLbs = idealMinKg * 2.20462;
            var idealMaxLbs = idealMaxKg * 2.20462;
            var idealRange, weightDisplay, weightAlt;
            if (this.unitSystem === 'imperial') {
                idealRange    = this.fmt0(idealMinLbs) + '–' + this.fmt0(idealMaxLbs) + ' lbs';
                weightDisplay = this.fmt1(w * 2.20462) + ' lbs';
                weightAlt     = this.fmt1(w) + ' kg';
            } else {
                idealRange    = this.fmt1(idealMinKg) + '–' + this.fmt1(idealMaxKg) + ' kg';
                weightDisplay = this.fmt1(w) + ' kg';
                weightAlt     = this.fmt1(w * 2.20462) + ' lbs';
            }

            /* Goal targets */
            var self = this;
            var goals = [
                { label:'Extreme Weight Loss', change:-1000, note:'~2 lbs/week',  color:'#ef4444' },
                { label:'Weight Loss',          change:-500,  note:'~1 lb/week',   color:'#f97316' },
                { label:'Mild Weight Loss',     change:-250,  note:'~½ lb/week',   color:'#eab308' },
                { label:'Maintain Weight',      change:0,     note:'No change',     color:'#10b981', isMaintain:true },
                { label:'Mild Weight Gain',     change:250,   note:'~½ lb/week',   color:'#3b82f6' },
                { label:'Weight Gain',          change:500,   note:'~1 lb/week',   color:'#6366f1' },
                { label:'Fast Weight Gain',     change:1000,  note:'~2 lbs/week',  color:'#8b5cf6' },
            ].map(function(g) {
                return Object.assign({}, g, { calories: Math.max(1200, Math.round(tdee + g.change)) });
            });

            /* Activity comparison */
            var actMax = self.activities[self.activities.length - 1];
            var maxTdee = bmr * parseFloat(actMax.value);
            var activityComparison = this.activities.map(function(act) {
                var aTdee = bmr * parseFloat(act.value);
                return {
                    value:     act.value,
                    label:     act.label,
                    tdee:      aTdee,
                    barPct:    (aTdee / maxTdee) * 100,
                    isCurrent: act.value === self.activity,
                };
            });

            /* Formula label */
            var formulaLabel = this.formula === 'mifflin' ? 'Mifflin-St Jeor formula' : 'Harris-Benedict formula';

            return {
                bmr: bmr, tdee: tdee, actMult: actMult, formulaLabel: formulaLabel,
                bmi: bmi, bmiCat: bmiCat, bmiColor: bmiColor, bmiNeedlePct: bmiNeedlePct,
                idealRange: idealRange, weightDisplay: weightDisplay, weightAlt: weightAlt,
                goals: goals, activityComparison: activityComparison,
                mifflinBmr: mifflinBmr, mifflinTdee: mifflinTdee,
                harrisBmr:  harrisBmr,  harrisTdee:  harrisTdee,
            };
        },

        _getMacros(tdee, goal) {
            var targetCals, proteinPct, carbPct, fatPct;
            if (goal === 'cut') {
                targetCals = Math.max(1200, Math.round(tdee - 500));
                proteinPct = 35; carbPct = 40; fatPct = 25;
            } else if (goal === 'bulk') {
                targetCals = Math.round(tdee + 500);
                proteinPct = 30; carbPct = 45; fatPct = 25;
            } else {
                targetCals = Math.round(tdee);
                proteinPct = 30; carbPct = 40; fatPct = 30;
            }
            return {
                calories:   targetCals,
                proteinPct: proteinPct,
                carbPct:    carbPct,
                fatPct:     fatPct,
                proteinG:   Math.round(targetCals * (proteinPct / 100) / 4),
                carbG:      Math.round(targetCals * (carbPct    / 100) / 4),
                fatG:       Math.round(targetCals * (fatPct     / 100) / 9),
            };
        },

        loadSample() {
            this.gender     = 'male';
            this.unitSystem = 'imperial';
            this.age        = '30';
            this.heightFt   = '5';
            this.heightIn   = '10';
            this.heightCm   = '';
            this.weightLbs  = '180';
            this.weightKg   = '';
            this.activity   = '1.55';
            this.formula    = 'mifflin';
            this.error      = '';
            this.result     = null;
            this.phase      = 'idle';
            var self = this;
            this.$nextTick(function(){ self.calculate(); });
        },

        clearAll() {
            this.error = ''; this.result = null; this.phase = 'idle';
        },

        /* ── Formatters ── */
        fmt0(v) {
            if (v === null || v === undefined || isNaN(v) || !isFinite(v)) return '—';
            return Math.round(v).toLocaleString();
        },
        fmt1(v) {
            if (v === null || v === undefined || isNaN(v) || !isFinite(v)) return '—';
            return parseFloat(v.toFixed(1)).toLocaleString(undefined, {minimumFractionDigits:0, maximumFractionDigits:1});
        },

        /* ── Export ── */
        _buildSummary() {
            if (!this.result) return '';
            var r = this.result;
            var m = this.currentMacros;
            var lines = [
                'Calorie Calculator Results',
                '==========================',
                'BMR (Basal Metabolic Rate) : ' + this.fmt0(r.bmr) + ' kcal/day',
                'TDEE (Daily Calorie Needs) : ' + this.fmt0(r.tdee) + ' kcal/day',
                'Formula                    : ' + r.formulaLabel,
                'BMI                        : ' + r.bmi.toFixed(1) + ' (' + r.bmiCat + ')',
                'Ideal Weight Range         : ' + r.idealRange,
                '',
                'CALORIE TARGETS BY GOAL:',
            ];
            r.goals.forEach(function(g) {
                lines.push('  ' + g.label.padEnd(22) + ': ' + g.calories + ' kcal/day');
            });
            lines = lines.concat([
                '',
                'MACROS (' + this.macroLabels[this.macroGoal] + ' — ' + this.fmt0(m.calories) + ' kcal):',
                '  Protein : ' + this.fmt0(m.proteinG) + 'g (' + m.proteinPct + '%)',
                '  Carbs   : ' + this.fmt0(m.carbG)    + 'g (' + m.carbPct    + '%)',
                '  Fat     : ' + this.fmt0(m.fatG)     + 'g (' + m.fatPct     + '%)',
            ]);
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
            var a    = document.createElement('a'); a.href = url; a.download = 'calorie-results.txt';
            document.body.appendChild(a); a.click(); document.body.removeChild(a); URL.revokeObjectURL(url);
        },
    };
}
</script>
@endpush
