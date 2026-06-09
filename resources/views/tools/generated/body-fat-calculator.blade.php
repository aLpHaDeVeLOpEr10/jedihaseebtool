@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')
<style>
/* ══════════════════════════════════════════════
   Body Fat Calculator  —  prefix: bf-
   Theme: Orange / Amber
══════════════════════════════════════════════ */

/* Gender / unit toggle pill */
.bf-pill { display:flex; background:#f1f5f9; border-radius:.625rem; padding:.2rem; gap:.15rem; }
.bf-pill-btn { flex:1; padding:.35rem .75rem; border-radius:.45rem; font-size:.78rem; font-weight:600; color:#64748b; cursor:pointer; border:none; background:none; transition:all .15s; white-space:nowrap; text-align:center; }
.bf-pill-btn.active           { background:#fff; box-shadow:0 1px 4px rgba(0,0,0,.1); }
.bf-pill-btn.active.orange    { color:#ea580c; }
.bf-pill-btn.active.rose      { color:#e11d48; }
.bf-pill-btn.active.brand     { color:#4f46e5; }
.bf-pill-btn.active.emerald   { color:#059669; }

/* Hero */
.bf-hero { font-size:clamp(3rem,6vw,4.5rem); font-weight:900; line-height:1; letter-spacing:-.04em; background:linear-gradient(135deg,#ea580c 0%,#f97316 55%,#fb923c 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }

/* Stat card */
.bf-stat { background:#fff; border:1.5px solid #e2e8f0; border-radius:1.125rem; padding:1rem .9rem; display:flex; flex-direction:column; align-items:center; gap:.3rem; text-align:center; transition:all .15s; }
.bf-stat:hover { border-color:#fed7aa; box-shadow:0 4px 16px rgba(234,88,12,.07); transform:translateY(-1px); }
.bf-stat-lbl { font-size:.62rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.07em; }
.bf-stat-val { font-size:1.25rem; font-weight:800; line-height:1.1; }
.bf-stat-val.orange  { background:linear-gradient(135deg,#ea580c,#f97316); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.bf-stat-val.brand   { background:linear-gradient(135deg,#4f46e5,#7c3aed); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.bf-stat-val.emerald { background:linear-gradient(135deg,#059669,#10b981); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.bf-stat-val.rose    { background:linear-gradient(135deg,#e11d48,#f43f5e); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.bf-stat-sub { font-size:.65rem; color:#94a3b8; }

/* Category badge */
.bf-cat-badge { display:inline-flex; align-items:center; gap:.5rem; padding:.55rem 1.1rem; border-radius:9999px; font-size:.85rem; font-weight:800; letter-spacing:.01em; }

/* Pre wrapper */
.bf-pre-wrap { display:flex; align-items:stretch; }
.bf-pre { display:flex; align-items:center; padding:0 .75rem; background:#f8fafc; border:1px solid #d1d5db; border-right:none; border-radius:.75rem 0 0 .75rem; font-size:.82rem; font-weight:700; color:#374151; white-space:nowrap; }
.bf-pre-wrap .form-input { border-radius:0 .75rem .75rem 0 !important; }

/* Measurement input group */
.bf-measure-row { display:grid; grid-template-columns:1fr 1fr; gap:.75rem; }

/* Category scale bar */
.bf-scale { height:20px; border-radius:9999px; overflow:hidden; display:flex; position:relative; }
.bf-scale-seg { height:100%; flex-shrink:0; }
.bf-scale-needle { position:absolute; top:-3px; bottom:-3px; width:3px; border-radius:2px; background:#1e293b; box-shadow:0 0 0 2px white, 0 2px 4px rgba(0,0,0,.3); transform:translateX(-50%); transition:left .5s ease; }

/* What-if table */
.bf-wi-row { display:grid; grid-template-columns:1.2fr 1fr 1fr 1fr; gap:.5rem; align-items:center; padding:.5rem .75rem; border-radius:.625rem; font-size:.78rem; }
.bf-wi-row:hover { background:#fff7ed; }
.bf-wi-row.current { background:#fff7ed; border:1px solid #fed7aa; }
.bf-wi-head { font-size:.62rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.07em; }

/* Section divider */
.bf-div { display:flex; align-items:center; gap:.6rem; color:#94a3b8; font-size:.62rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; }
.bf-div::before,.bf-div::after { content:''; flex:1; height:1px; background:#f1f5f9; }

/* Measurement guide */
.bf-guide-item { display:flex; gap:.75rem; padding:.5rem 0; border-bottom:1px solid #f1f5f9; }
.bf-guide-item:last-child { border-bottom:none; }
.bf-guide-icon { width:28px; height:28px; border-radius:.5rem; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; background:#fff7ed; }

/* Fat/lean mass bar */
.bf-mass-bar { height:18px; border-radius:9999px; overflow:hidden; display:flex; }
.bf-mass-fat  { background:#f97316; }
.bf-mass-lean { background:#10b981; }

/* Shimmer */
@keyframes bfShim { 0%{background-position:-600px 0} 100%{background-position:600px 0} }
.bf-shim { height:5.5rem; border-radius:1.125rem; background:linear-gradient(90deg,#fff7ed 25%,#fed7aa 50%,#fff7ed 75%); background-size:1200px 100%; animation:bfShim 1.4s infinite; }

@keyframes bfIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
.bf-in { animation:bfIn .3s ease-out; }

/* Input tip */
.bf-tip { font-size:.68rem; color:#94a3b8; margin-top:.25rem; display:flex; align-items:flex-start; gap:.35rem; }
.bf-tip-icon { flex-shrink:0; color:#fb923c; }
</style>

<div class="min-h-screen bg-gray-50" x-data="bfCalc()" x-init="init()">

    {{-- Page header --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-8 md:py-10">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">{{ $tool->icon }} {{ $tool->name }}</h1>
                    <p class="text-gray-500 mt-2 max-w-2xl">Estimate your body fat percentage using the <strong>U.S. Navy circumference method</strong> — one of the most accessible and validated non-lab techniques. Enter neck, waist, and hip (women) measurements for an accurate result.</p>
                </div>
                <div class="flex flex-wrap gap-2 shrink-0">
                    <span class="badge badge-warning">Free</span>
                    <span class="badge badge-gray">U.S. Navy Method</span>
                    <span class="badge badge-success">Metric &amp; Imperial</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-6 md:py-8">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 items-start">

            {{-- ══ LEFT — Input Panel ══ --}}
            <div class="lg:col-span-2 space-y-4">
                <div class="card">
                    <div class="px-5 pt-5 pb-1">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Your Measurements</p>
                    </div>

                    <div class="px-5 pb-5 space-y-4">

                        {{-- Gender --}}
                        <div>
                            <label class="form-label">Biological Sex</label>
                            <div class="bf-pill">
                                <button type="button" class="bf-pill-btn" :class="{active:gender==='male', orange:gender==='male'}"   @click="gender='male';   autoCalc()">♂ Male</button>
                                <button type="button" class="bf-pill-btn" :class="{active:gender==='female', rose:gender==='female'}" @click="gender='female'; autoCalc()">♀ Female</button>
                            </div>
                            <p class="bf-tip mt-1.5">
                                <span class="bf-tip-icon">ℹ</span>
                                <span>Women need an additional hip measurement. The formula differs by sex.</span>
                            </p>
                        </div>

                        {{-- Unit system --}}
                        <div>
                            <label class="form-label">Unit System</label>
                            <div class="bf-pill">
                                <button type="button" class="bf-pill-btn" :class="{active:unitSystem==='imperial', brand:unitSystem==='imperial'}"   @click="switchUnit('imperial')">🇺🇸 Imperial (in / lbs)</button>
                                <button type="button" class="bf-pill-btn" :class="{active:unitSystem==='metric',   emerald:unitSystem==='metric'}"   @click="switchUnit('metric')">🌍 Metric (cm / kg)</button>
                            </div>
                        </div>

                        <div class="bf-div">Body Measurements</div>

                        {{-- Age --}}
                        <div>
                            <label class="form-label">Age <span class="font-normal text-gray-400">(for category reference)</span></label>
                            <div class="bf-pre-wrap">
                                <span class="bf-pre">yrs</span>
                                <input type="number" step="1" min="1" max="120" x-model="age"
                                       @input.debounce.350ms="autoCalc()"
                                       class="form-input" placeholder="e.g. 30">
                            </div>
                        </div>

                        {{-- Height --}}
                        <div>
                            <label class="form-label">Height</label>
                            <div x-show="unitSystem==='imperial'" class="flex gap-2">
                                <div class="bf-pre-wrap flex-1">
                                    <span class="bf-pre">ft</span>
                                    <input type="number" step="1" min="3" max="8" x-model="heightFt"
                                           @input.debounce.350ms="autoCalc()"
                                           class="form-input" placeholder="5">
                                </div>
                                <div class="bf-pre-wrap flex-1">
                                    <span class="bf-pre">in</span>
                                    <input type="number" step="any" min="0" max="11" x-model="heightIn"
                                           @input.debounce.350ms="autoCalc()"
                                           class="form-input" placeholder="10">
                                </div>
                            </div>
                            <div x-show="unitSystem==='metric'" class="bf-pre-wrap">
                                <span class="bf-pre">cm</span>
                                <input type="number" step="any" min="100" max="250" x-model="heightCm"
                                       @input.debounce.350ms="autoCalc()"
                                       class="form-input" placeholder="e.g. 178">
                            </div>
                        </div>

                        {{-- Weight --}}
                        <div>
                            <label class="form-label">Body Weight <span class="font-normal text-gray-400">(for fat/lean mass)</span></label>
                            <div x-show="unitSystem==='imperial'" class="bf-pre-wrap">
                                <span class="bf-pre">lbs</span>
                                <input type="number" step="any" min="1" x-model="weightLbs"
                                       @input.debounce.350ms="autoCalc()"
                                       class="form-input" placeholder="e.g. 180">
                            </div>
                            <div x-show="unitSystem==='metric'" class="bf-pre-wrap">
                                <span class="bf-pre">kg</span>
                                <input type="number" step="any" min="1" x-model="weightKg"
                                       @input.debounce.350ms="autoCalc()"
                                       class="form-input" placeholder="e.g. 82">
                            </div>
                        </div>

                        <div class="bf-div">Circumference Measurements</div>

                        {{-- Neck --}}
                        <div>
                            <label class="form-label">Neck Circumference</label>
                            <div class="bf-pre-wrap">
                                <span class="bf-pre" x-text="unitSystem==='imperial' ? 'in' : 'cm'"></span>
                                <input type="number" step="any" min="1" x-model="neck"
                                       @input.debounce.350ms="autoCalc()"
                                       class="form-input" placeholder="e.g. 15.5">
                            </div>
                            <p class="bf-tip">
                                <span class="bf-tip-icon">📍</span>
                                <span>Measure just below the larynx (Adam's apple), perpendicular to neck axis.</span>
                            </p>
                        </div>

                        {{-- Waist --}}
                        <div>
                            <label class="form-label" x-text="gender==='male' ? 'Abdomen Circumference' : 'Waist Circumference'"></label>
                            <div class="bf-pre-wrap">
                                <span class="bf-pre" x-text="unitSystem==='imperial' ? 'in' : 'cm'"></span>
                                <input type="number" step="any" min="1" x-model="waist"
                                       @input.debounce.350ms="autoCalc()"
                                       class="form-input" placeholder="e.g. 34">
                            </div>
                            <p class="bf-tip">
                                <span class="bf-tip-icon">📍</span>
                                <span x-text="gender==='male' ? 'Men: measure at the navel (belly button), parallel to floor.' : 'Women: measure at the narrowest point of the waist.'"></span>
                            </p>
                        </div>

                        {{-- Hip (females only) --}}
                        <div x-show="gender==='female'" x-transition>
                            <label class="form-label">Hip Circumference</label>
                            <div class="bf-pre-wrap">
                                <span class="bf-pre" x-text="unitSystem==='imperial' ? 'in' : 'cm'"></span>
                                <input type="number" step="any" min="1" x-model="hip"
                                       @input.debounce.350ms="autoCalc()"
                                       class="form-input" placeholder="e.g. 39">
                            </div>
                            <p class="bf-tip">
                                <span class="bf-tip-icon">📍</span>
                                <span>Measure at the widest point of the hips/buttocks, parallel to floor.</span>
                            </p>
                        </div>

                        {{-- Error --}}
                        <div x-show="error" x-transition class="flex items-start gap-2 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span x-text="error"></span>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex flex-wrap gap-2 pt-1">
                            <button type="button" @click="calculate()" class="btn flex-1 sm:flex-none btn-lg font-bold" style="background:linear-gradient(135deg,#ea580c,#f97316);color:white;border:none;box-shadow:0 4px 14px rgba(234,88,12,.3)">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                Calculate Body Fat
                            </button>
                            <button type="button" @click="loadSample()" class="btn btn-secondary">📋 Sample</button>
                            <button type="button" @click="clearAll()" x-show="phase==='done' || error" class="btn btn-secondary">✕ Clear</button>
                        </div>
                    </div>
                </div>

                {{-- Measurement guide accordion --}}
                <div class="card overflow-hidden">
                    <button type="button" @click="showGuide=!showGuide"
                            class="w-full flex items-center justify-between px-5 py-3.5 bg-orange-50 hover:bg-orange-100 transition-colors">
                        <span class="text-sm font-semibold text-orange-700">📏 How to Measure Correctly</span>
                        <svg class="w-4 h-4 text-orange-500 transition-transform" :class="{'-rotate-180':showGuide}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="showGuide" x-transition class="px-5 py-4 space-y-0">
                        <div class="bf-guide-item">
                            <div class="bf-guide-icon">📐</div>
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Use a flexible tape measure</p>
                                <p class="text-xs text-gray-400 mt-0.5">Keep it snug but not tight. Don't compress the skin.</p>
                            </div>
                        </div>
                        <div class="bf-guide-item">
                            <div class="bf-guide-icon">🔵</div>
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Neck — below the larynx</p>
                                <p class="text-xs text-gray-400 mt-0.5">Measure at or just below the Adam's apple, perpendicular to the neck axis. Tilt head straight forward.</p>
                            </div>
                        </div>
                        <div class="bf-guide-item">
                            <div class="bf-guide-icon">🟠</div>
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Waist — at the navel (men) / narrowest (women)</p>
                                <p class="text-xs text-gray-400 mt-0.5">Men: measure at the navel. Women: measure at the narrowest part of the torso. Exhale normally before measuring.</p>
                            </div>
                        </div>
                        <div class="bf-guide-item">
                            <div class="bf-guide-icon">🩷</div>
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Hip — widest point (women only)</p>
                                <p class="text-xs text-gray-400 mt-0.5">Measure around the widest part of the hips and buttocks, parallel to the floor.</p>
                            </div>
                        </div>
                        <div class="bf-guide-item">
                            <div class="bf-guide-icon">⚠️</div>
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Waist must be larger than neck</p>
                                <p class="text-xs text-gray-400 mt-0.5">The Navy formula requires waist &gt; neck (men) and waist + hip &gt; neck (women). If these aren't met the formula cannot compute.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══ RIGHT — Results Panel ══ --}}
            <div class="lg:col-span-3 space-y-4" id="bf-results">

                {{-- Shimmer --}}
                <div x-show="phase==='loading'" class="space-y-3">
                    <div class="bf-shim" style="height:8rem"></div>
                    <div class="grid grid-cols-2 gap-3">
                        <template x-for="i in 4" :key="i"><div class="bf-shim"></div></template>
                    </div>
                </div>

                {{-- ══ RESULTS ══ --}}
                <template x-if="phase==='done' && result">
                    <div class="space-y-4 bf-in">

                        {{-- Hero --}}
                        <div class="card overflow-hidden">
                            <div style="background:linear-gradient(135deg,#fff7ed 0%,#ffedd5 100%);" class="px-6 py-5">
                                <p class="text-xs font-bold text-orange-500 uppercase tracking-widest mb-1">Body Fat Percentage (U.S. Navy)</p>
                                <div class="flex flex-wrap items-end gap-4">
                                    <p class="bf-hero" x-text="result.bfPctDisplay"></p>
                                    <div class="bf-cat-badge mb-1" :style="'background:'+result.category.bgColor+';color:'+result.category.color" x-text="result.category.label"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">
                                    Height: <strong x-text="result.heightDisplay"></strong>
                                    &nbsp;·&nbsp;
                                    <span x-text="result.gender === 'male' ? '♂ Male' : '♀ Female'"></span>
                                    <template x-if="result.weightKg > 0">
                                        <span>&nbsp;·&nbsp; Weight: <strong x-text="result.weightDisplay"></strong></span>
                                    </template>
                                </p>
                            </div>
                        </div>

                        {{-- SVG Gauge --}}
                        <div class="card p-4">
                            <p class="text-sm font-semibold text-gray-700 mb-2 text-center">Body Fat Gauge</p>
                            <div class="flex justify-center">
                                <svg viewBox="0 0 200 115" width="240" height="138" style="overflow:visible">
                                    <!-- Background track -->
                                    <path d="M 20,100 A 80,80 0 0 1 180,100" fill="none" stroke="#f1f5f9" stroke-width="14" stroke-linecap="round"/>
                                    <!-- Colored fill arc -->
                                    <path :d="result.gaugeArcPath" fill="none" :stroke="result.category.color" stroke-width="14" stroke-linecap="round" x-show="result.gaugeArcPath"/>
                                    <!-- Center dot -->
                                    <circle cx="100" cy="100" r="5" :fill="result.category.color"/>
                                    <!-- Needle -->
                                    <line x1="100" y1="100" :x2="result.needleX" :y2="result.needleY" :stroke="result.category.color" stroke-width="2.5" stroke-linecap="round"/>
                                    <!-- Scale labels -->
                                    <text x="20"  y="115" text-anchor="middle" font-size="7" fill="#94a3b8">0%</text>
                                    <text x="100" y="15"  text-anchor="middle" font-size="7" fill="#94a3b8" x-text="result.gaugeMidLabel"></text>
                                    <text x="180" y="115" text-anchor="middle" font-size="7" fill="#94a3b8" x-text="result.gaugeMaxLabel"></text>
                                    <!-- Value label -->
                                    <text x="100" y="86" text-anchor="middle" font-size="17" font-weight="900" :fill="result.category.color" x-text="result.bfPctDisplay"></text>
                                </svg>
                            </div>
                            <div class="flex justify-center mt-1">
                                <span class="bf-cat-badge text-sm" :style="'background:'+result.category.bgColor+';color:'+result.category.color" x-text="result.category.label"></span>
                            </div>
                        </div>

                        {{-- Stat cards --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div class="bf-stat">
                                <div class="bf-stat-lbl">Body Fat</div>
                                <div class="bf-stat-val orange" x-text="result.bfPctDisplay"></div>
                                <div class="bf-stat-sub">of total body weight</div>
                            </div>
                            <div class="bf-stat">
                                <div class="bf-stat-lbl">Fat Mass</div>
                                <div class="bf-stat-val rose" x-text="result.fatMassDisplay"></div>
                                <div class="bf-stat-sub" x-text="result.weightKg > 0 ? result.fatMassAltDisplay : 'enter weight'"></div>
                            </div>
                            <div class="bf-stat">
                                <div class="bf-stat-lbl">Lean Mass</div>
                                <div class="bf-stat-val emerald" x-text="result.leanMassDisplay"></div>
                                <div class="bf-stat-sub" x-text="result.weightKg > 0 ? result.leanMassAltDisplay : 'enter weight'"></div>
                            </div>
                            <div class="bf-stat">
                                <div class="bf-stat-lbl">Category</div>
                                <div class="text-base font-black mt-1" :style="'color:'+result.category.color" x-text="result.category.label"></div>
                                <div class="bf-stat-sub" x-text="result.category.idealRange"></div>
                            </div>
                        </div>

                        {{-- Category scale bar --}}
                        <div class="card p-4">
                            <p class="text-sm font-semibold text-gray-700 mb-3">Body Fat Category Scale</p>
                            <div class="relative mb-1">
                                <div class="bf-scale">
                                    <template x-for="seg in result.categoryScale" :key="seg.label">
                                        <div class="bf-scale-seg" :style="'width:'+seg.pct+'%;background:'+seg.color"></div>
                                    </template>
                                </div>
                                <div class="bf-scale-needle" :style="'left:'+result.scaleNeedlePct+'%'"></div>
                            </div>
                            <div class="flex justify-between mt-2">
                                <template x-for="seg in result.categoryScale" :key="seg.label+'_lbl'">
                                    <div class="text-center" :style="'width:'+seg.pct+'%'">
                                        <div class="text-xs font-semibold truncate" :style="'color:'+seg.color" x-text="seg.label"></div>
                                        <div class="text-xs text-gray-400" x-text="seg.rangeText"></div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Fat / Lean mass breakdown (only if weight was entered) --}}
                        <template x-if="result.weightKg > 0">
                            <div class="card p-4">
                                <p class="text-sm font-semibold text-gray-700 mb-3">Body Composition Breakdown</p>
                                <div class="bf-mass-bar mb-3">
                                    <div class="bf-mass-fat  transition-all duration-500" :style="'width:'+result.bfPct+'%'"></div>
                                    <div class="bf-mass-lean transition-all duration-500" :style="'width:'+(100-result.bfPct)+'%'"></div>
                                </div>
                                <div class="flex flex-wrap gap-x-5 gap-y-1 text-xs">
                                    <span class="flex items-center gap-1.5">
                                        <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:#f97316"></span>
                                        <span class="text-gray-500">Fat Mass: <strong x-text="result.fatMassDisplay"></strong> (<span x-text="result.bfPctDisplay"></span>)</span>
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:#10b981"></span>
                                        <span class="text-gray-500">Lean Mass: <strong x-text="result.leanMassDisplay"></strong> (<span x-text="(100-result.bfPct).toFixed(1)+'%'"></span>)</span>
                                    </span>
                                </div>
                                <div class="mt-3 grid grid-cols-3 gap-3 text-center text-xs">
                                    <div class="p-2.5 rounded-xl" style="background:#fff7ed;border:1px solid #fed7aa">
                                        <p class="font-black text-lg text-orange-600" x-text="result.fatMassDisplay"></p>
                                        <p class="font-semibold text-orange-700">Fat Mass</p>
                                        <p class="text-gray-400 mt-0.5" x-text="result.fatMassAltDisplay"></p>
                                    </div>
                                    <div class="p-2.5 rounded-xl" style="background:#ecfdf5;border:1px solid #a7f3d0">
                                        <p class="font-black text-lg text-emerald-600" x-text="result.leanMassDisplay"></p>
                                        <p class="font-semibold text-emerald-700">Lean Mass</p>
                                        <p class="text-gray-400 mt-0.5" x-text="result.leanMassAltDisplay"></p>
                                    </div>
                                    <div class="p-2.5 rounded-xl" style="background:#eff6ff;border:1px solid #bfdbfe">
                                        <p class="font-black text-lg text-blue-600" x-text="result.bmiDisplay"></p>
                                        <p class="font-semibold text-blue-700">BMI</p>
                                        <p class="text-gray-400 mt-0.5" x-text="result.bmiCat"></p>
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- What-if (only if weight was entered) --}}
                        <template x-if="result.weightKg > 0 && result.whatIf && result.whatIf.length">
                            <div class="card overflow-hidden">
                                <div class="px-5 py-3 bg-gray-50 border-b border-gray-100">
                                    <p class="text-sm font-semibold text-gray-700">💡 What If You Reduced Body Fat?</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Assumes lean mass stays constant. Weight at target BF%.</p>
                                </div>
                                <div class="p-4">
                                    <div class="bf-wi-row bf-wi-head mb-1">
                                        <span>Target BF%</span><span class="text-right">Body Weight</span><span class="text-right">Fat Mass</span><span class="text-right">To Lose</span>
                                    </div>
                                    <template x-for="wi in result.whatIf" :key="wi.label">
                                        <div class="bf-wi-row" :class="{current: wi.isCurrent}">
                                            <div class="flex items-center gap-2">
                                                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" :style="'background:'+wi.catColor"></span>
                                                <span class="font-semibold" :class="wi.isCurrent ? 'text-orange-600' : 'text-gray-700'" x-text="wi.label"></span>
                                            </div>
                                            <span class="text-right font-semibold text-gray-800" x-text="wi.weightDisplay"></span>
                                            <span class="text-right text-gray-600" x-text="wi.fatMassDisplay"></span>
                                            <span class="text-right" :class="wi.toLose > 0 ? 'text-red-500 font-semibold' : 'text-emerald-500 font-semibold'" x-text="wi.toLoseDisplay"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        {{-- Formula details --}}
                        <div class="card p-4">
                            <p class="text-sm font-semibold text-gray-700 mb-3">U.S. Navy Formula Used</p>
                            <div class="space-y-2 text-xs font-mono text-gray-600 p-3 rounded-xl bg-gray-50 border border-gray-100">
                                <template x-if="result.gender === 'male'">
                                    <div>
                                        <p class="font-bold text-gray-700 mb-1 not-italic font-sans">♂ Male formula (inches):</p>
                                        <p>495 ÷ (1.0324 − 0.19077 × log₁₀(waist − neck) + 0.15456 × log₁₀(height)) − 450</p>
                                        <p class="mt-1 text-gray-400">
                                            = 495 ÷ (1.0324 − 0.19077 × log₁₀(<span x-text="result.waistIn.toFixed(2)"></span> − <span x-text="result.neckIn.toFixed(2)"></span>) + 0.15456 × log₁₀(<span x-text="result.heightIn.toFixed(2)"></span>)) − 450
                                        </p>
                                        <p class="mt-0.5 text-orange-600 font-bold not-italic font-sans">= <span x-text="result.bfPctDisplay"></span></p>
                                    </div>
                                </template>
                                <template x-if="result.gender === 'female'">
                                    <div>
                                        <p class="font-bold text-gray-700 mb-1 not-italic font-sans">♀ Female formula (inches):</p>
                                        <p>495 ÷ (1.29579 − 0.35004 × log₁₀(waist + hip − neck) + 0.22100 × log₁₀(height)) − 450</p>
                                        <p class="mt-1 text-gray-400">
                                            = 495 ÷ (1.29579 − 0.35004 × log₁₀(<span x-text="result.waistIn.toFixed(2)"></span> + <span x-text="result.hipIn.toFixed(2)"></span> − <span x-text="result.neckIn.toFixed(2)"></span>) + 0.22100 × log₁₀(<span x-text="result.heightIn.toFixed(2)"></span>)) − 450
                                        </p>
                                        <p class="mt-0.5 text-orange-600 font-bold not-italic font-sans">= <span x-text="result.bfPctDisplay"></span></p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Export --}}
                        <div class="card p-4">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="text-sm font-medium text-gray-600">Export:</span>
                                <button type="button" @click="copySummary()"
                                        class="btn btn-secondary btn-sm"
                                        :class="copyFlash ? 'bg-orange-50 text-orange-700' : ''"
                                        x-text="copyFlash ? '✓ Copied!' : 'Copy Summary'"></button>
                                <button type="button" @click="downloadSummary()" class="btn btn-secondary btn-sm">⬇ Download .txt</button>
                                <button type="button" @click="clearAll()" class="btn btn-secondary btn-sm ml-auto text-red-500 hover:text-red-600 hover:bg-red-50">✕ Reset</button>
                            </div>
                        </div>

                    </div>
                </template>

                {{-- Idle --}}
                <div x-show="phase==='idle'">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-4">
                        @foreach([
                            ['💪','U.S. Navy Method','Validated circumference-based formula used by the U.S. military'],
                            ['📊','BF% Categories','Essential / Athletes / Fitness / Average / Obese scale'],
                            ['⚖️','Fat & Lean Mass','See how much of your weight is fat vs lean tissue'],
                            ['📉','What-If Scenarios','Weight at 5%, 10%, 15% lower body fat targets'],
                            ['📐','BMI Included','Body Mass Index cross-reference with weight'],
                            ['📏','Measure Guide','Step-by-step instructions for accurate measurements'],
                        ] as [$icon,$title,$desc])
                        <div class="card p-4 text-center hover:border-orange-200 transition-colors">
                            <p class="text-2xl mb-1.5">{{ $icon }}</p>
                            <p class="text-sm font-semibold text-gray-700">{{ $title }}</p>
                            <p class="text-xs text-gray-400 mt-1 leading-snug">{{ $desc }}</p>
                        </div>
                        @endforeach
                    </div>
                    <div class="card p-4" style="background:linear-gradient(135deg,#fff7ed,#ffedd5);border-color:#fed7aa">
                        <p class="text-sm font-semibold text-orange-700 mb-2">📐 U.S. Navy Formula</p>
                        <div class="space-y-1.5 text-xs font-mono text-gray-600">
                            <p><strong>♂ Male:</strong>   495 ÷ (1.0324 − 0.19077×log(waist−neck) + 0.15456×log(height)) − 450</p>
                            <p><strong>♀ Female:</strong> 495 ÷ (1.29579 − 0.35004×log(waist+hip−neck) + 0.22100×log(height)) − 450</p>
                            <p class="text-gray-400 mt-1">All measurements in inches. log = log base 10.</p>
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

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function bfCalc() {
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
        neck:       '',
        waist:      '',
        hip:        '',

        /* ── UI state ── */
        phase:       'idle',
        error:       '',
        result:      null,
        showGuide:   false,
        copyFlash:   false,

        /* ── Category definitions ── */
        _maleCats: [
            { label:'Essential', min:2,  max:6,  color:'#3b82f6', bgColor:'#dbeafe' },
            { label:'Athletes',  min:6,  max:14, color:'#10b981', bgColor:'#d1fae5' },
            { label:'Fitness',   min:14, max:18, color:'#059669', bgColor:'#ecfdf5' },
            { label:'Average',   min:18, max:25, color:'#eab308', bgColor:'#fef9c3' },
            { label:'Obese',     min:25, max:null,color:'#ef4444', bgColor:'#fee2e2' },
        ],
        _femaleCats: [
            { label:'Essential', min:10, max:14, color:'#3b82f6', bgColor:'#dbeafe' },
            { label:'Athletes',  min:14, max:21, color:'#10b981', bgColor:'#d1fae5' },
            { label:'Fitness',   min:21, max:25, color:'#059669', bgColor:'#ecfdf5' },
            { label:'Average',   min:25, max:32, color:'#eab308', bgColor:'#fef9c3' },
            { label:'Obese',     min:32, max:null,color:'#ef4444', bgColor:'#fee2e2' },
        ],

        init() {},

        /* ── Converters ── */
        get _heightInches() {
            if (this.unitSystem === 'metric') return (parseFloat(this.heightCm) || 0) / 2.54;
            return (parseFloat(this.heightFt) || 0) * 12 + (parseFloat(this.heightIn) || 0);
        },
        get _weightKgComputed() {
            if (this.unitSystem === 'metric') return parseFloat(this.weightKg) || 0;
            return (parseFloat(this.weightLbs) || 0) * 0.453592;
        },
        /* Convert measurement input (in user's unit) to inches */
        _toInches(val) {
            var v = parseFloat(val) || 0;
            return this.unitSystem === 'metric' ? v / 2.54 : v;
        },

        switchUnit(u) {
            if (u === this.unitSystem) return;
            var CM_PER_IN = 2.54;
            if (u === 'metric') {
                var hIn = this._heightInches;
                if (hIn > 0) this.heightCm = (hIn * CM_PER_IN).toFixed(0);
                var w = this._weightKgComputed;
                if (w > 0) this.weightKg = w.toFixed(1);
                /* Convert measurements cm → same field */
                var fields = ['neck','waist','hip'];
                for (var i = 0; i < fields.length; i++) {
                    var v = parseFloat(this[fields[i]]) || 0;
                    if (v > 0) this[fields[i]] = (v * CM_PER_IN).toFixed(1);
                }
            } else {
                var hCm = (parseFloat(this.heightCm) || 0);
                if (hCm > 0) {
                    var totalIn = hCm / CM_PER_IN;
                    this.heightFt = String(Math.floor(totalIn / 12));
                    this.heightIn = (totalIn % 12).toFixed(1);
                }
                var wKg = parseFloat(this.weightKg) || 0;
                if (wKg > 0) this.weightLbs = (wKg * 2.20462).toFixed(1);
                var flds = ['neck','waist','hip'];
                for (var j = 0; j < flds.length; j++) {
                    var cv = parseFloat(this[flds[j]]) || 0;
                    if (cv > 0) this[flds[j]] = (cv / CM_PER_IN).toFixed(2);
                }
            }
            this.unitSystem = u;
            this.autoCalc();
        },

        autoCalc() {
            try {
                var h = this._heightInches;
                if (!h || h < 20 || h > 100) return;
                var n = this._toInches(this.neck);
                var w = this._toInches(this.waist);
                if (!n || n <= 0 || !w || w <= 0) return;
                if (this.gender === 'female') {
                    var hp = this._toInches(this.hip);
                    if (!hp || hp <= 0) return;
                }
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
                            var el = document.getElementById('bf-results');
                            if (el) setTimeout(function(){ el.scrollIntoView({behavior:'smooth', block:'start'}); }, 80);
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
            var h = this._heightInches;
            if (!h || isNaN(h) || h < 24) throw new Error('Enter a valid height (minimum 2 ft / 60 cm).');
            if (h > 96)                    throw new Error('Enter a valid height (maximum 8 ft / 244 cm).');

            var n = this._toInches(this.neck);
            if (!n || isNaN(n) || n <= 0) throw new Error('Enter a valid neck circumference (must be positive).');
            if (n > h * 0.6)              throw new Error('Neck measurement seems too large. Please double-check.');

            var w = this._toInches(this.waist);
            if (!w || isNaN(w) || w <= 0) throw new Error('Enter a valid waist circumference (must be positive).');

            if (this.gender === 'male') {
                if (w <= n) throw new Error('Waist must be greater than neck for the formula to work. Check your measurements.');
            }

            var hp = 0;
            if (this.gender === 'female') {
                hp = this._toInches(this.hip);
                if (!hp || isNaN(hp) || hp <= 0) throw new Error('Enter a valid hip circumference for female calculation.');
                if (hp < w * 0.5) throw new Error('Hip measurement seems too small. Please double-check.');
                if ((w + hp) <= n) throw new Error('Waist + Hip must be greater than Neck for the formula to work.');
            }

            var wKg  = this._weightKgComputed;
            var age  = parseInt(this.age) || 0;

            return { hIn: h, neckIn: n, waistIn: w, hipIn: hp, weightKg: wKg, age: age };
        },

        _getCategory(bfPct, gender) {
            var cats = gender === 'male' ? this._maleCats : this._femaleCats;
            for (var i = 0; i < cats.length; i++) {
                var c = cats[i];
                if (c.max === null || bfPct < c.max) {
                    var idealRangeStr = c.max ? (c.min + '–' + c.max + '%') : (c.min + '%+');
                    return Object.assign({}, c, { idealRange: idealRangeStr });
                }
            }
            return Object.assign({}, cats[cats.length - 1], { idealRange: cats[cats.length-1].min + '%+' });
        },

        _compute(v) {
            var self = this;
            var hIn = v.hIn, nIn = v.neckIn, wIn = v.waistIn, hipIn = v.hipIn;
            var isImperial = this.unitSystem === 'imperial';

            /* ── U.S. Navy formula (inches) ── */
            var bfPct;
            if (this.gender === 'male') {
                var logDiff = Math.log10(wIn - nIn);
                var logH    = Math.log10(hIn);
                bfPct = 495 / (1.0324 - 0.19077 * logDiff + 0.15456 * logH) - 450;
            } else {
                var logSum = Math.log10(wIn + hipIn - nIn);
                var logH2  = Math.log10(hIn);
                bfPct = 495 / (1.29579 - 0.35004 * logSum + 0.22100 * logH2) - 450;
            }
            bfPct = Math.max(0.1, Math.min(70, bfPct));

            var category = this._getCategory(bfPct, this.gender);

            /* ── Fat & Lean mass ── */
            var wKg      = v.weightKg;
            var fatKg    = wKg > 0 ? wKg * (bfPct / 100) : 0;
            var leanKg   = wKg > 0 ? wKg - fatKg : 0;
            var fatLbs   = fatKg  * 2.20462;
            var leanLbs  = leanKg * 2.20462;
            var wLbs     = wKg   * 2.20462;

            /* ── BMI ── */
            var hM = hIn * 0.0254;
            var bmi = wKg > 0 ? wKg / (hM * hM) : 0;
            var bmiCat = bmi === 0 ? '—' : bmi < 18.5 ? 'Underweight' : bmi < 25 ? 'Normal' : bmi < 30 ? 'Overweight' : 'Obese';

            /* ── SVG Gauge ── */
            var gaugeMax   = this.gender === 'male' ? 35 : 45;
            var normalized = Math.min(1, Math.max(0, bfPct / gaugeMax));
            var angle      = (180 + normalized * 180) * (Math.PI / 180);
            var R          = 80;
            var ex         = 100 + R * Math.cos(angle);
            var ey         = 100 + R * Math.sin(angle);
            var largeArc   = normalized > 0.5 ? 1 : 0;
            var gaugeArcPath = normalized > 0.005
                ? ('M 20,100 A ' + R + ',' + R + ' 0 ' + largeArc + ' 0 ' + ex.toFixed(2) + ',' + ey.toFixed(2))
                : '';
            var needleX = 100 + 65 * Math.cos(angle);
            var needleY = 100 + 65 * Math.sin(angle);

            /* ── Category scale bar ── */
            var cats = this.gender === 'male' ? this._maleCats : this._femaleCats;
            var scaleMax = this.gender === 'male' ? 40 : 48;
            var categoryScale = cats.map(function(c) {
                var segMax = c.max !== null ? c.max : scaleMax;
                var segMin = c.min;
                var pct    = ((segMax - segMin) / scaleMax) * 100;
                return { label: c.label, color: c.color, pct: pct, rangeText: c.max ? segMin + '–' + segMax + '%' : segMin + '%+' };
            });
            var scaleNeedlePct = Math.min(98, Math.max(2, (bfPct / scaleMax) * 100));

            /* ── What-if table ── */
            var whatIf = [];
            if (wKg > 0) {
                var targets = [];
                /* Reduction scenarios */
                var reductions = [0, -5, -10, -15];
                for (var i = 0; i < reductions.length; i++) {
                    var targetBfPct = bfPct + reductions[i];
                    if (targetBfPct > 0.5) targets.push({ targetBfPct: targetBfPct, isCurrent: reductions[i] === 0 });
                }
                /* Athlete target */
                var athleteTarget = this.gender === 'male' ? 12 : 18;
                if (bfPct > athleteTarget + 1) {
                    targets.push({ targetBfPct: athleteTarget, isCurrent: false, label: 'Athlete target' });
                }

                for (var j = 0; j < targets.length; j++) {
                    var t = targets[j];
                    var tbf = t.targetBfPct;
                    if (tbf <= 0) continue;
                    /* Weight at target (lean mass constant) */
                    var targetWeight = leanKg / (1 - tbf / 100);
                    var targetFat    = targetWeight * (tbf / 100);
                    var toLoseKg     = wKg - targetWeight;
                    var cat          = self._getCategory(tbf, self.gender);
                    var labelStr     = t.label || (t.isCurrent ? 'Current (' + self.fmt1(tbf) + '%)' : self.fmt1(tbf) + '% (' + (reductions[j] > 0 ? '+' : '') + reductions[j] + '%)');
                    whatIf.push({
                        label:          labelStr,
                        isCurrent:      t.isCurrent,
                        catColor:       cat.color,
                        weightDisplay:  isImperial ? self.fmt1(targetWeight * 2.20462) + ' lbs' : self.fmt1(targetWeight) + ' kg',
                        fatMassDisplay: isImperial ? self.fmt1(targetFat * 2.20462)   + ' lbs' : self.fmt1(targetFat)   + ' kg',
                        toLose:         toLoseKg,
                        toLoseDisplay:  toLoseKg <= 0.05 ? '—' : (isImperial ? self.fmt1(toLoseKg * 2.20462) + ' lbs' : self.fmt1(toLoseKg) + ' kg'),
                    });
                }
            }

            /* ── Height display ── */
            var heightDisplay;
            if (isImperial) {
                var ft  = Math.floor(hIn / 12);
                var inn = Math.round(hIn % 12);
                heightDisplay = ft + "'" + inn + '"';
            } else {
                heightDisplay = (hIn * 2.54).toFixed(0) + ' cm';
            }

            /* ── Weight display ── */
            var weightDisplay = wKg > 0 ? (isImperial ? this.fmt1(wLbs) + ' lbs' : this.fmt1(wKg) + ' kg') : '';

            /* ── Mass displays ── */
            var fatMassDisplay, leanMassDisplay, fatMassAltDisplay, leanMassAltDisplay;
            if (wKg > 0) {
                if (isImperial) {
                    fatMassDisplay  = this.fmt1(fatLbs)  + ' lbs';
                    leanMassDisplay = this.fmt1(leanLbs) + ' lbs';
                    fatMassAltDisplay  = this.fmt1(fatKg)  + ' kg';
                    leanMassAltDisplay = this.fmt1(leanKg) + ' kg';
                } else {
                    fatMassDisplay  = this.fmt1(fatKg)  + ' kg';
                    leanMassDisplay = this.fmt1(leanKg) + ' kg';
                    fatMassAltDisplay  = this.fmt1(fatLbs)  + ' lbs';
                    leanMassAltDisplay = this.fmt1(leanLbs) + ' lbs';
                }
            } else {
                fatMassDisplay = leanMassDisplay = fatMassAltDisplay = leanMassAltDisplay = '— (enter weight)';
            }

            return {
                gender:           this.gender,
                heightDisplay:    heightDisplay,
                weightKg:         wKg,
                weightDisplay:    weightDisplay,
                bfPct:            bfPct,
                bfPctDisplay:     this.fmt1(bfPct) + '%',
                category:         category,
                fatKg:            fatKg,
                leanKg:           leanKg,
                fatMassDisplay:   fatMassDisplay,
                leanMassDisplay:  leanMassDisplay,
                fatMassAltDisplay:  fatMassAltDisplay,
                leanMassAltDisplay: leanMassAltDisplay,
                bmiDisplay:       wKg > 0 ? this.fmt1(bmi) : '—',
                bmiCat:           bmiCat,
                gaugeArcPath:     gaugeArcPath,
                needleX:          needleX.toFixed(2),
                needleY:          needleY.toFixed(2),
                gaugeMidLabel:    (gaugeMax / 2) + '%',
                gaugeMaxLabel:    gaugeMax + '%',
                categoryScale:    categoryScale,
                scaleNeedlePct:   scaleNeedlePct,
                whatIf:           whatIf,
                /* formula raw values for display */
                heightIn:         hIn,
                neckIn:           nIn,
                waistIn:          wIn,
                hipIn:            hipIn,
            };
        },

        loadSample() {
            this.gender     = 'male';
            this.unitSystem = 'imperial';
            this.age        = '32';
            this.heightFt   = '5';
            this.heightIn   = '11';
            this.heightCm   = '';
            this.weightLbs  = '190';
            this.weightKg   = '';
            this.neck       = '16';
            this.waist      = '34';
            this.hip        = '';
            this.error      = '';
            this.result     = null;
            this.phase      = 'idle';
            var self = this;
            this.$nextTick(function(){ self.calculate(); });
        },

        clearAll() { this.error = ''; this.result = null; this.phase = 'idle'; },

        /* ── Formatters ── */
        fmt1(v) {
            if (v === null || v === undefined || isNaN(v) || !isFinite(v)) return '—';
            return parseFloat(Math.abs(v).toFixed(1)).toString();
        },

        /* ── Export ── */
        _buildSummary() {
            if (!this.result) return '';
            var r = this.result;
            var lines = [
                'Body Fat Calculator Results (U.S. Navy Method)',
                '================================================',
                'Gender          : ' + (r.gender === 'male' ? 'Male' : 'Female'),
                'Height          : ' + r.heightDisplay,
                r.weightKg > 0 ? 'Weight          : ' + r.weightDisplay : '',
                '',
                'RESULTS:',
                '  Body Fat %    : ' + r.bfPctDisplay,
                '  Category      : ' + r.category.label,
                r.weightKg > 0 ? '  Fat Mass      : ' + r.fatMassDisplay : '',
                r.weightKg > 0 ? '  Lean Mass     : ' + r.leanMassDisplay : '',
                r.weightKg > 0 ? '  BMI           : ' + r.bmiDisplay + ' (' + r.bmiCat + ')' : '',
            ].filter(function(l){ return l !== ''; });
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
            var a    = document.createElement('a'); a.href = url; a.download = 'body-fat-results.txt';
            document.body.appendChild(a); a.click(); document.body.removeChild(a); URL.revokeObjectURL(url);
        },
    };
}
</script>
@endpush
