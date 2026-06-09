@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')
<style>
/* ══════════════════════════════════════════════
   ROI Calculator  —  prefix: ri-
══════════════════════════════════════════════ */

/* ── Mode tabs ── */
.ri-tab {
    padding: .5rem 1rem; border-bottom: 2.5px solid transparent;
    font-size: .78rem; font-weight: 600; color: #64748b;
    background: transparent; border-top: none; border-left: none; border-right: none;
    cursor: pointer; transition: color .12s, border-color .12s; white-space: nowrap;
}
.ri-tab:hover { color: #4f46e5; }
.ri-tab-active { color: #4f46e5; border-bottom-color: #4f46e5; }

/* ── Hero ROI ── */
.ri-hero-pct {
    font-size: clamp(2.8rem, 6vw, 4.5rem);
    font-weight: 900; line-height: 1; letter-spacing: -.03em; word-break: break-all;
    transition: color .3s;
}
.ri-hero-pct.positive {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 60%, #a78bfa 100%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
}
.ri-hero-pct.negative {
    background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
}

/* ── Stat cards ── */
.ri-stat {
    background: #fff; border: 1.5px solid #e2e8f0; border-radius: 1.125rem;
    padding: 1rem .9rem; display: flex; flex-direction: column; align-items: center;
    gap: .3rem; text-align: center; transition: all .15s;
}
.ri-stat:hover { border-color: #c7d2fe; box-shadow: 0 4px 16px rgba(79,70,229,.07); transform: translateY(-1px); }
.ri-stat-lbl { font-size: .62rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .07em; }
.ri-stat-val { font-size: 1.3rem; font-weight: 800; word-break: break-all; }
.ri-stat-val.brand  { background: linear-gradient(135deg,#4f46e5,#7c3aed); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.ri-stat-val.green  { background: linear-gradient(135deg,#059669,#10b981); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.ri-stat-val.red    { background: linear-gradient(135deg,#dc2626,#ef4444); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.ri-stat-val.amber  { background: linear-gradient(135deg,#d97706,#f59e0b); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.ri-stat-val.cyan   { background: linear-gradient(135deg,#0891b2,#06b6d4); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.ri-stat-sub { font-size: .65rem; color: #94a3b8; }

/* ── ROI quality scale ── */
.ri-scale { display: flex; gap: 2px; border-radius: .6rem; overflow: hidden; }
.ri-scale-cell {
    flex: 1; padding: .35rem .1rem; text-align: center; font-size: .6rem; font-weight: 700;
    color: rgba(255,255,255,.85); transition: transform .2s; white-space: nowrap; overflow: hidden;
}
.ri-scale-cell.active {
    transform: scaleY(1.15); color: #fff; box-shadow: 0 2px 8px rgba(0,0,0,.2);
    font-size: .65rem;
}

/* ── Benchmark bars ── */
.ri-bench-row { display: flex; align-items: center; gap: .75rem; padding: .4rem 0; }
.ri-bench-name { font-size: .72rem; color: #4b5563; width: 160px; flex-shrink: 0; }
.ri-bench-bar-track { flex: 1; height: 8px; background: #f1f5f9; border-radius: 9999px; overflow: hidden; }
.ri-bench-bar-fill { height: 100%; border-radius: 9999px; transition: width .5s ease; }
.ri-bench-val { font-size: .72rem; font-weight: 700; color: #374151; width: 50px; text-align: right; font-variant-numeric: tabular-nums; }

/* ── What-If cards ── */
.ri-whatif {
    padding: .9rem .75rem; border-radius: .9rem; border: 1.5px solid #e2e8f0;
    text-align: center; background: #fff; transition: all .15s;
}
.ri-whatif:hover { border-color: #c7d2fe; box-shadow: 0 2px 10px rgba(79,70,229,.07); }
.ri-whatif.current { border-color: #4f46e5; background: #eef2ff; }
.ri-whatif-roi { font-size: 1.15rem; font-weight: 900; }
.ri-whatif-sub { font-size: .63rem; color: #6b7280; margin-top: .15rem; }

/* ── Year table ── */
.ri-yt { width: 100%; border-collapse: collapse; font-size: .76rem; }
.ri-yt th { font-size: .62rem; font-weight: 700; color: #94a3b8; text-transform: uppercase;
    letter-spacing: .07em; padding: .45rem .7rem; border-bottom: 1.5px solid #e2e8f0;
    text-align: right; background: #f8fafc; }
.ri-yt th:first-child { text-align: center; }
.ri-yt td { padding: .45rem .7rem; border-bottom: 1px solid #f1f5f9; text-align: right;
    font-variant-numeric: tabular-nums; color: #374151; }
.ri-yt td:first-child { text-align: center; font-weight: 600; color: #4f46e5; }
.ri-yt tr:hover td { background: #f5f3ff; }
.ri-yt tr:last-child td { border-bottom: none; font-weight: 700; }
.ri-yt .ri-td-g { color: #059669; font-weight: 600; }

/* ── Input helpers ── */
.ri-pre-wrap { display: flex; align-items: stretch; }
.ri-pre { display: flex; align-items: center; padding: 0 .75rem; background: #f8fafc;
    border: 1px solid #d1d5db; border-right: none; border-radius: .75rem 0 0 .75rem;
    font-size: .82rem; font-weight: 700; color: #374151; white-space: nowrap; }
.ri-pre-wrap .form-input { border-radius: 0 .75rem .75rem 0 !important; flex: 1; min-width: 0; }
.ri-suf-wrap { display: flex; align-items: stretch; }
.ri-suf-wrap .form-input { border-radius: .75rem 0 0 .75rem !important; flex: 1; border-right: none; min-width: 0; }
.ri-suf { display: flex; align-items: center; padding: 0 .75rem; background: #f8fafc;
    border: 1px solid #d1d5db; border-left: none; border-radius: 0 .75rem .75rem 0;
    font-size: .78rem; font-weight: 600; color: #64748b; white-space: nowrap; }

/* ── Toggle group ── */
.ri-toggle { display: flex; background: #f1f5f9; border-radius: .5rem; padding: .15rem; gap: .1rem; }
.ri-toggle-btn { flex: 1; padding: .28rem .6rem; border-radius: .35rem; font-size: .72rem; font-weight: 600;
    color: #64748b; cursor: pointer; border: none; background: none; transition: all .15s; white-space: nowrap; }
.ri-toggle-btn.active { background: white; color: #4f46e5; box-shadow: 0 1px 3px rgba(0,0,0,.1); }

/* ── Section divider ── */
.ri-div { display: flex; align-items: center; gap: .6rem;
    color: #94a3b8; font-size: .62rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; }
.ri-div::before,.ri-div::after { content: ''; flex: 1; height: 1px; background: #f1f5f9; }

/* ── Gauge SVG ── */
.ri-gauge-wrap { position: relative; display: flex; justify-content: center; }

/* ── Metric row (marketing) ── */
.ri-metric { display: flex; justify-content: space-between; align-items: center;
    padding: .55rem .9rem; border-radius: .6rem; gap: .5rem; }
.ri-metric:hover { background: #f8fafc; }
.ri-metric-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
.ri-metric-lbl { font-size: .82rem; color: #4b5563; }
.ri-metric-val { font-size: .88rem; font-weight: 700; color: #1f2937; font-variant-numeric: tabular-nums; }
.ri-metric-divider { border-top: 1.5px solid #e5e7eb; padding-top: .6rem; margin-top: .2rem; }
.ri-metric-divider .ri-metric-lbl { font-weight: 700; color: #1f2937; }
.ri-metric-divider .ri-metric-val { color: #4f46e5; font-size: .95rem; }

/* ── Shimmer ── */
@keyframes riShim { 0%{background-position:-600px 0} 100%{background-position:600px 0} }
.ri-shim { height: 5.5rem; border-radius: 1.125rem;
    background: linear-gradient(90deg,#f5f3ff 25%,#ede9fe 50%,#f5f3ff 75%);
    background-size: 1200px 100%; animation: riShim 1.4s infinite; }

/* ── Entrance ── */
@keyframes riIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
.ri-in { animation: riIn .3s ease-out; }

/* ── Insight box ── */
.ri-insight { display: flex; align-items: flex-start; gap: .75rem; padding: .7rem .9rem;
    border-radius: .75rem; background: #f5f3ff; border: 1px solid #e0e7ff; }
.ri-insight-val { font-weight: 800; color: #4f46e5; }
</style>

<div class="min-h-screen bg-gray-50"
     x-data="roiCalc()"
     x-init="init()">

    {{-- ── Page Header ── --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-8 md:py-10">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                        {{ $tool->icon }} {{ $tool->name }}
                    </h1>
                    <p class="text-gray-500 mt-2 max-w-2xl">
                        Calculate <strong>Return on Investment</strong> — simple ROI, annualized (CAGR), or marketing ROI. Get an instant visual gauge, benchmark comparison, and what-if analysis.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2 shrink-0">
                    <span class="badge badge-primary">Free</span>
                    <span class="badge badge-gray">No Account</span>
                    <span class="badge badge-success">3 Modes</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-6 md:py-8">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 items-start">

            {{-- ══════════════════════════
                 LEFT — Input Card
                 ══════════════════════════ --}}
            <div class="lg:col-span-2">
                <div class="card">
                    {{-- Mode tabs --}}
                    <div class="flex border-b border-gray-100 overflow-x-auto">
                        <button type="button" class="ri-tab" :class="{'ri-tab-active': mode==='standard'}"   @click="switchMode('standard')">📊 Standard ROI</button>
                        <button type="button" class="ri-tab" :class="{'ri-tab-active': mode==='annualized'}" @click="switchMode('annualized')">📅 Annualized</button>
                        <button type="button" class="ri-tab" :class="{'ri-tab-active': mode==='marketing'}"  @click="switchMode('marketing')">📣 Marketing</button>
                    </div>

                    <div class="p-5 space-y-4">

                        {{-- ── STANDARD + ANNUALIZED ── --}}
                        <div x-show="mode!=='marketing'" class="space-y-4">

                            {{-- Gain input mode --}}
                            <div>
                                <label class="form-label">How will you enter the return?</label>
                                <div class="ri-toggle">
                                    <button type="button" class="ri-toggle-btn" :class="{active: gainMode==='finalValue'}"
                                            @click="gainMode='finalValue'; autoCalc()">Final Value</button>
                                    <button type="button" class="ri-toggle-btn" :class="{active: gainMode==='netProfit'}"
                                            @click="gainMode='netProfit'; autoCalc()">Net Profit</button>
                                </div>
                                <p class="form-help" x-show="gainMode==='finalValue'">E.g. you invested $10 000 and it's now worth $13 500.</p>
                                <p class="form-help" x-show="gainMode==='netProfit'">E.g. you invested $10 000 and earned $3 500 profit.</p>
                            </div>

                            {{-- Currency + Investment --}}
                            <div>
                                <label class="form-label">Currency &amp; Investment Cost</label>
                                <div class="flex gap-2">
                                    <select x-model="currency" class="form-input w-20 py-2 text-sm shrink-0">
                                        <option value="$">$ USD</option><option value="€">€ EUR</option>
                                        <option value="£">£ GBP</option><option value="¥">¥ JPY</option>
                                        <option value="₹">₹ INR</option><option value="A$">A$</option>
                                        <option value="C$">C$</option><option value="₦">₦ NGN</option>
                                    </select>
                                    <div class="ri-pre-wrap flex-1">
                                        <span class="ri-pre" x-text="currency"></span>
                                        <input type="number" step="any" min="0" x-model="investment"
                                               @input.debounce.350ms="autoCalc()"
                                               class="form-input" placeholder="e.g. 10000">
                                    </div>
                                </div>
                                <p class="form-help">Total amount you invested (initial cost).</p>
                            </div>

                            {{-- Final Value --}}
                            <div x-show="gainMode==='finalValue'">
                                <label class="form-label">Final / Current Value</label>
                                <div class="ri-pre-wrap">
                                    <span class="ri-pre" x-text="currency"></span>
                                    <input type="number" step="any" min="0" x-model="finalValue"
                                           @input.debounce.350ms="autoCalc()"
                                           class="form-input" placeholder="e.g. 13500">
                                </div>
                                <p class="form-help">What the investment is worth now (or what you received back).</p>
                            </div>

                            {{-- Net Profit --}}
                            <div x-show="gainMode==='netProfit'">
                                <label class="form-label">Net Profit / Gain</label>
                                <div class="ri-pre-wrap">
                                    <span class="ri-pre" x-text="currency"></span>
                                    <input type="number" step="any" x-model="netProfit"
                                           @input.debounce.350ms="autoCalc()"
                                           class="form-input" placeholder="e.g. 3500">
                                </div>
                                <p class="form-help">Profit earned. Use a negative number for a loss.</p>
                            </div>

                            {{-- Optional ongoing costs --}}
                            <div>
                                <label class="form-label">Additional Costs <span class="text-gray-400 font-normal">(optional)</span></label>
                                <div class="ri-pre-wrap">
                                    <span class="ri-pre" x-text="currency"></span>
                                    <input type="number" step="any" min="0" x-model="addlCosts"
                                           @input.debounce.350ms="autoCalc()"
                                           class="form-input" placeholder="0">
                                </div>
                                <p class="form-help">Fees, taxes, or other costs to subtract from profit.</p>
                            </div>

                            {{-- Time Period (annualized mode) --}}
                            <div x-show="mode==='annualized'" x-transition>
                                <div class="ri-div pt-1">Time Period</div>
                                <div class="mt-3 flex gap-2">
                                    <input type="number" step="any" min="0" x-model="timePeriod"
                                           @input.debounce.350ms="autoCalc()"
                                           class="form-input flex-1" placeholder="e.g. 5">
                                    <div class="ri-toggle" style="min-width:110px">
                                        <button type="button" class="ri-toggle-btn" :class="{active: timeUnit==='years'}"
                                                @click="timeUnit='years'; autoCalc()">Years</button>
                                        <button type="button" class="ri-toggle-btn" :class="{active: timeUnit==='months'}"
                                                @click="timeUnit='months'; autoCalc()">Months</button>
                                    </div>
                                </div>
                                <p class="form-help">How long you held the investment.</p>
                            </div>

                        </div>{{-- /standard+annualized inputs --}}

                        {{-- ── MARKETING ROI ── --}}
                        <div x-show="mode==='marketing'" class="space-y-4">

                            <div class="p-3 bg-indigo-50 border border-indigo-100 rounded-xl text-xs text-indigo-700">
                                💡 Marketing ROI = (Revenue − Costs − Ad Spend) ÷ Ad Spend × 100
                            </div>

                            {{-- Currency + Ad Spend --}}
                            <div>
                                <label class="form-label">Currency &amp; Ad / Marketing Spend</label>
                                <div class="flex gap-2">
                                    <select x-model="currency" class="form-input w-20 py-2 text-sm shrink-0">
                                        <option value="$">$ USD</option><option value="€">€ EUR</option>
                                        <option value="£">£ GBP</option><option value="¥">¥ JPY</option>
                                        <option value="₹">₹ INR</option><option value="A$">A$</option>
                                    </select>
                                    <div class="ri-pre-wrap flex-1">
                                        <span class="ri-pre" x-text="currency"></span>
                                        <input type="number" step="any" min="0" x-model="adSpend"
                                               @input.debounce.350ms="autoCalc()"
                                               class="form-input" placeholder="e.g. 5000">
                                    </div>
                                </div>
                            </div>

                            {{-- Revenue --}}
                            <div>
                                <label class="form-label">Revenue Generated</label>
                                <div class="ri-pre-wrap">
                                    <span class="ri-pre" x-text="currency"></span>
                                    <input type="number" step="any" min="0" x-model="mktRevenue"
                                           @input.debounce.350ms="autoCalc()"
                                           class="form-input" placeholder="e.g. 20000">
                                </div>
                            </div>

                            {{-- COGS toggle --}}
                            <div class="flex items-center gap-3">
                                <button type="button"
                                        class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 transition-colors duration-200 ease-in-out focus:outline-none"
                                        :class="hasCOGS ? 'bg-brand-600 border-brand-600' : 'bg-gray-200 border-gray-200'"
                                        @click="hasCOGS=!hasCOGS; autoCalc()">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition duration-200 ease-in-out"
                                          :class="hasCOGS ? 'translate-x-4' : 'translate-x-0'"></span>
                                </button>
                                <label class="text-sm font-medium text-gray-700">Include Cost of Goods Sold (COGS)</label>
                            </div>

                            <div x-show="hasCOGS" x-transition>
                                <label class="form-label">Cost of Goods Sold</label>
                                <div class="ri-pre-wrap">
                                    <span class="ri-pre" x-text="currency"></span>
                                    <input type="number" step="any" min="0" x-model="mktCOGS"
                                           @input.debounce.350ms="autoCalc()"
                                           class="form-input" placeholder="e.g. 8000">
                                </div>
                                <p class="form-help">Direct cost of the products/services sold.</p>
                            </div>

                            {{-- Campaign duration for annualized --}}
                            <div>
                                <label class="form-label">Campaign Duration <span class="text-gray-400 font-normal">(optional)</span></label>
                                <div class="flex gap-2">
                                    <input type="number" step="any" min="0" x-model="mktDuration"
                                           @input.debounce.350ms="autoCalc()"
                                           class="form-input flex-1" placeholder="e.g. 3">
                                    <div class="ri-toggle" style="min-width:110px">
                                        <button type="button" class="ri-toggle-btn" :class="{active: mktDurUnit==='months'}"
                                                @click="mktDurUnit='months'; autoCalc()">Months</button>
                                        <button type="button" class="ri-toggle-btn" :class="{active: mktDurUnit==='years'}"
                                                @click="mktDurUnit='years'; autoCalc()">Years</button>
                                    </div>
                                </div>
                            </div>

                        </div>{{-- /marketing inputs --}}

                        {{-- Error --}}
                        <div x-show="error" x-transition
                             class="flex items-start gap-2 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span x-text="error"></span>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex flex-wrap gap-2 pt-1">
                            <button type="button" @click="calculate()" class="btn btn-primary flex-1 sm:flex-none btn-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                Calculate ROI
                            </button>
                            <button type="button" @click="loadSample()" class="btn btn-secondary">📋 Sample</button>
                            <button type="button" @click="clearAll()" x-show="phase==='done' || error" class="btn btn-secondary">✕ Clear</button>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ══════════════════════════
                 RIGHT — Results
                 ══════════════════════════ --}}
            <div class="lg:col-span-3 space-y-4" id="ri-results">

                {{-- Shimmer --}}
                <div x-show="phase==='loading'" class="grid grid-cols-2 gap-3">
                    <template x-for="i in 4" :key="i"><div class="ri-shim"></div></template>
                </div>

                {{-- ══ RESULTS ══ --}}
                <template x-if="phase==='done' && result">
                    <div class="space-y-4 ri-in">

                        {{-- Hero card: ROI % + gauge --}}
                        <div class="card overflow-hidden">
                            <div :style="result.roi >= 0
                                    ? 'background:linear-gradient(135deg,#eef2ff 0%,#ede9fe 100%)'
                                    : 'background:linear-gradient(135deg,#fef2f2 0%,#fee2e2 100%)'"
                                 class="px-6 py-5">
                                <div class="flex flex-col sm:flex-row items-center gap-4">
                                    {{-- Gauge --}}
                                    <div class="ri-gauge-wrap shrink-0" style="width:200px">
                                        <svg viewBox="0 0 200 115" width="200" height="115">
                                            <defs>
                                                <linearGradient id="riGaugeGrad" x1="0" y1="0" x2="1" y2="0">
                                                    <stop offset="0%"   stop-color="#ef4444"/>
                                                    <stop offset="17%"  stop-color="#f59e0b"/>
                                                    <stop offset="40%"  stop-color="#84cc16"/>
                                                    <stop offset="70%"  stop-color="#10b981"/>
                                                    <stop offset="100%" stop-color="#4f46e5"/>
                                                </linearGradient>
                                            </defs>
                                            {{-- Background arc --}}
                                            <path d="M 20,100 A 80,80 0 1 0 180,100" fill="none" stroke="#e2e8f0" stroke-width="14" stroke-linecap="round"/>
                                            {{-- Colored fill arc --}}
                                            <path :d="result.gaugeArcPath" fill="none" :stroke="result.gaugeColor"
                                                  stroke-width="14" stroke-linecap="round"
                                                  x-show="result.gaugeArcPath"/>
                                            {{-- Needle --}}
                                            <line x1="100" y1="100"
                                                  :x2="result.needleX" :y2="result.needleY"
                                                  stroke="#374151" stroke-width="2.5" stroke-linecap="round"/>
                                            <circle cx="100" cy="100" r="5" fill="#374151"/>
                                            <circle cx="100" cy="100" r="2.5" fill="white"/>
                                            {{-- Range labels --}}
                                            <text x="14" y="113" text-anchor="middle" font-size="8" fill="#94a3b8">-100%</text>
                                            <text x="100" y="18"  text-anchor="middle" font-size="8" fill="#94a3b8">+200%</text>
                                            <text x="186" y="113" text-anchor="middle" font-size="8" fill="#94a3b8">+500%</text>
                                        </svg>
                                    </div>
                                    {{-- Value --}}
                                    <div class="text-center sm:text-left">
                                        <p class="text-xs font-bold uppercase tracking-widest mb-1"
                                           :class="result.roi >= 0 ? 'text-indigo-400' : 'text-red-400'"
                                           x-text="mode==='marketing' ? 'Marketing ROI' : (mode==='annualized' ? 'Total ROI' : 'Return on Investment')"></p>
                                        <p class="ri-hero-pct" :class="result.roi >= 0 ? 'positive' : 'negative'"
                                           x-text="(result.roi >= 0 ? '+' : '') + result.roi.toFixed(2) + '%'"></p>
                                        <p class="text-sm font-semibold mt-1.5" :class="result.roi >= 0 ? 'text-indigo-700' : 'text-red-600'"
                                           x-text="result.roiLevel"></p>
                                        <p class="text-xs text-gray-400 mt-0.5"
                                           x-text="result.roi >= 0 ? 'Every ' + currency + '1 invested returned ' + currency + result.profitFactor.toFixed(2) : 'Net loss on investment'"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ROI Quality Scale --}}
                        <div class="card p-4">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5">ROI Quality Scale</p>
                            <div class="ri-scale">
                                <div class="ri-scale-cell" :class="{active: result.levelIdx===0}"
                                     style="background:#ef4444">Loss<br><span style="font-size:.55rem;opacity:.8">below 0%</span></div>
                                <div class="ri-scale-cell" :class="{active: result.levelIdx===1}"
                                     style="background:#f59e0b">Poor<br><span style="font-size:.55rem;opacity:.8">0–5%</span></div>
                                <div class="ri-scale-cell" :class="{active: result.levelIdx===2}"
                                     style="background:#84cc16">Average<br><span style="font-size:.55rem;opacity:.8">5–15%</span></div>
                                <div class="ri-scale-cell" :class="{active: result.levelIdx===3}"
                                     style="background:#10b981">Good<br><span style="font-size:.55rem;opacity:.8">15–50%</span></div>
                                <div class="ri-scale-cell" :class="{active: result.levelIdx===4}"
                                     style="background:#059669">Strong<br><span style="font-size:.55rem;opacity:.8">50–100%</span></div>
                                <div class="ri-scale-cell" :class="{active: result.levelIdx===5}"
                                     style="background:#4f46e5">Excellent<br><span style="font-size:.55rem;opacity:.8">100%+</span></div>
                            </div>
                        </div>

                        {{-- Key stat cards --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            {{-- Common stats --}}
                            <div class="ri-stat" x-show="mode!=='marketing'">
                                <div class="ri-stat-lbl">Investment</div>
                                <div class="ri-stat-val brand" x-text="fmtC(result.investment)"></div>
                                <div class="ri-stat-sub">initial cost</div>
                            </div>
                            <div class="ri-stat" x-show="mode==='marketing'">
                                <div class="ri-stat-lbl">Ad Spend</div>
                                <div class="ri-stat-val brand" x-text="fmtC(result.investment)"></div>
                                <div class="ri-stat-sub">marketing cost</div>
                            </div>
                            <div class="ri-stat">
                                <div class="ri-stat-lbl">Net Profit</div>
                                <div class="ri-stat-val" :class="result.netProfit >= 0 ? 'green' : 'red'"
                                     x-text="(result.netProfit >= 0 ? '+' : '') + fmtC(result.netProfit)"></div>
                                <div class="ri-stat-sub">gain / loss</div>
                            </div>
                            <div class="ri-stat" x-show="mode!=='marketing'">
                                <div class="ri-stat-lbl">Total Return</div>
                                <div class="ri-stat-val cyan" x-text="fmtC(result.totalReturn)"></div>
                                <div class="ri-stat-sub">investment + profit</div>
                            </div>
                            <div class="ri-stat" x-show="mode==='marketing'">
                                <div class="ri-stat-lbl">ROAS</div>
                                <div class="ri-stat-val cyan" x-text="result.roas ? result.roas.toFixed(2) + 'x' : '—'"></div>
                                <div class="ri-stat-sub">revenue / ad spend</div>
                            </div>
                            <div class="ri-stat" x-show="mode==='annualized' && result.cagr !== null">
                                <div class="ri-stat-lbl">CAGR (Annual)</div>
                                <div class="ri-stat-val amber" x-text="result.cagr !== null ? (result.cagr >= 0 ? '+' : '') + result.cagr.toFixed(2) + '%' : '—'"></div>
                                <div class="ri-stat-sub">per year</div>
                            </div>
                            <div class="ri-stat" x-show="mode!=='annualized' || result.cagr === null">
                                <div class="ri-stat-lbl">Profit Factor</div>
                                <div class="ri-stat-val amber" x-text="result.profitFactor.toFixed(3) + '×'"></div>
                                <div class="ri-stat-sub">return multiple</div>
                            </div>
                        </div>

                        {{-- ANNUALIZED extra stats --}}
                        <div x-show="mode==='annualized' && result.cagr !== null">
                            <div class="grid grid-cols-3 gap-3">
                                <div class="ri-stat">
                                    <div class="ri-stat-lbl">Annualized ROI</div>
                                    <div class="ri-stat-val amber" x-text="result.cagr !== null ? (result.cagr >= 0 ? '+' : '') + result.cagr.toFixed(2) + '%' : '—'"></div>
                                    <div class="ri-stat-sub">CAGR / year</div>
                                </div>
                                <div class="ri-stat">
                                    <div class="ri-stat-lbl">Monthly Return</div>
                                    <div class="ri-stat-val brand" x-text="result.monthlyROI !== null ? (result.monthlyROI >= 0 ? '+' : '') + result.monthlyROI.toFixed(3) + '%' : '—'"></div>
                                    <div class="ri-stat-sub">equiv. per month</div>
                                </div>
                                <div class="ri-stat">
                                    <div class="ri-stat-lbl">Doubling Time</div>
                                    <div class="ri-stat-val cyan"
                                         x-text="result.doublingYears !== null ? (result.doublingYears < 1000 ? result.doublingYears.toFixed(1) + ' yr' : '∞') : '—'"></div>
                                    <div class="ri-stat-sub">at this CAGR</div>
                                </div>
                            </div>
                        </div>

                        {{-- MARKETING extra metrics --}}
                        <div class="card p-4" x-show="mode==='marketing'">
                            <p class="text-sm font-semibold text-gray-700 mb-2">Campaign Metrics</p>
                            <div class="space-y-0.5">
                                <div class="ri-metric">
                                    <span class="flex items-center gap-2"><span class="ri-metric-dot" style="background:#4f46e5"></span><span class="ri-metric-lbl">Ad Spend</span></span>
                                    <span class="ri-metric-val" x-text="fmtC(result.investment)"></span>
                                </div>
                                <div class="ri-metric">
                                    <span class="flex items-center gap-2"><span class="ri-metric-dot" style="background:#06b6d4"></span><span class="ri-metric-lbl">Revenue</span></span>
                                    <span class="ri-metric-val" x-text="fmtC(result.mktRevenue)"></span>
                                </div>
                                <div class="ri-metric" x-show="result.mktCOGS > 0">
                                    <span class="flex items-center gap-2"><span class="ri-metric-dot" style="background:#f59e0b"></span><span class="ri-metric-lbl">COGS</span></span>
                                    <span class="ri-metric-val" x-text="fmtC(result.mktCOGS)"></span>
                                </div>
                                <div class="ri-metric" x-show="result.mktCOGS > 0">
                                    <span class="flex items-center gap-2"><span class="ri-metric-dot" style="background:#10b981"></span><span class="ri-metric-lbl">Gross Profit</span></span>
                                    <span class="ri-metric-val" x-text="fmtC(result.grossProfit)"></span>
                                </div>
                                <div class="ri-metric">
                                    <span class="flex items-center gap-2"><span class="ri-metric-dot" style="background:#ef4444"></span><span class="ri-metric-lbl">Break-even Revenue</span></span>
                                    <span class="ri-metric-val" x-text="fmtC(result.breakEvenRevenue)"></span>
                                </div>
                                <div class="ri-metric ri-metric-divider">
                                    <span class="ri-metric-lbl">Marketing ROI</span>
                                    <span class="ri-metric-val" x-text="(result.roi >= 0 ? '+' : '') + result.roi.toFixed(2) + '%'"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Insights --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" x-show="mode!=='marketing'">
                            <div class="ri-insight">
                                <span style="font-size:1.2rem">💰</span>
                                <div class="text-sm">
                                    <p class="text-gray-500 text-xs">For every dollar invested you received</p>
                                    <p class="font-semibold text-gray-800">
                                        <span class="ri-insight-val" x-text="currency + result.profitFactor.toFixed(4)"></span>
                                        <span class="text-gray-400 text-xs"> back</span>
                                    </p>
                                </div>
                            </div>
                            <div class="ri-insight" x-show="mode==='annualized' && result.doublingYears !== null && result.doublingYears < 1000 && result.doublingYears > 0">
                                <span style="font-size:1.2rem">⏱</span>
                                <div class="text-sm">
                                    <p class="text-gray-500 text-xs">At this annual rate, money doubles in</p>
                                    <p class="font-semibold text-gray-800">
                                        <span class="ri-insight-val" x-text="result.doublingYears ? result.doublingYears.toFixed(1) + ' years' : '—'"></span>
                                    </p>
                                </div>
                            </div>
                            <div class="ri-insight" x-show="mode!=='annualized' || result.doublingYears === null || result.doublingYears >= 1000">
                                <span style="font-size:1.2rem">📉</span>
                                <div class="text-sm">
                                    <p class="text-gray-500 text-xs">Break-even (0% ROI) requires a return of</p>
                                    <p class="font-semibold text-gray-800">
                                        <span class="ri-insight-val" x-text="fmtC(result.investment)"></span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Benchmark Comparison (annualized mode only) --}}
                        <div class="card p-4" x-show="mode==='annualized' && result.cagr !== null">
                            <p class="text-sm font-semibold text-gray-700 mb-1">How does it compare?</p>
                            <p class="text-xs text-gray-400 mb-3">Annualized ROI vs common investment benchmarks</p>
                            <div class="space-y-2.5">
                                <template x-for="bm in result.benchmarks" :key="bm.name">
                                    <div class="ri-bench-row">
                                        <span class="ri-bench-name" :class="bm.isUser ? 'font-bold text-brand-700' : ''"
                                              x-text="bm.isUser ? '★ Your ROI' : bm.name"></span>
                                        <div class="ri-bench-bar-track">
                                            <div class="ri-bench-bar-fill" :style="'width:' + bm.barPct + '%;background:' + bm.color"></div>
                                        </div>
                                        <span class="ri-bench-val" x-text="bm.roi.toFixed(1) + '%/yr'"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Year-by-Year Table (annualized mode) --}}
                        <div class="card overflow-hidden" x-show="mode==='annualized' && result.yearTable && result.yearTable.length > 0">
                            <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100 bg-gray-50">
                                <span class="text-sm font-semibold text-gray-700">Year-by-Year Growth</span>
                                <button type="button" @click="showYearTable=!showYearTable" class="btn btn-secondary btn-sm flex items-center gap-1">
                                    <span x-text="showYearTable ? 'Hide' : 'Show'"></span>
                                    <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{'-rotate-180': showYearTable}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            </div>
                            <div x-show="showYearTable" x-transition style="max-height:340px;overflow-y:auto;">
                                <table class="ri-yt">
                                    <thead>
                                        <tr>
                                            <th>Year</th>
                                            <th>Start Value</th>
                                            <th>Annual Gain</th>
                                            <th>End Value</th>
                                            <th>Running ROI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="row in result.yearTable" :key="row.year">
                                            <tr>
                                                <td x-text="row.year"></td>
                                                <td x-text="fmtC(row.startVal)"></td>
                                                <td class="ri-td-g" x-text="'+' + fmtC(row.gain)"></td>
                                                <td x-text="fmtC(row.endVal)"></td>
                                                <td class="ri-td-g" x-text="'+' + row.runROI.toFixed(2) + '%'"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- What-If Comparison --}}
                        <div class="card p-4" x-show="mode!=='marketing'">
                            <p class="text-sm font-semibold text-gray-700 mb-3">💡 What-If: Different Return Scenarios</p>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                <template x-for="wi in result.whatIf" :key="wi.multiplier">
                                    <div class="ri-whatif" :class="{current: wi.isCurrent}">
                                        <div class="ri-whatif-roi" :style="'color:' + (wi.roi >= 0 ? '#4f46e5' : '#ef4444')"
                                             x-text="(wi.roi >= 0 ? '+' : '') + wi.roi.toFixed(1) + '%'"></div>
                                        <div class="ri-whatif-sub">
                                            <div x-text="fmtC(wi.gain) + ' profit'"></div>
                                            <div x-text="wi.isCurrent ? 'current' : (wi.multiplier > 0 ? '+' : '') + fmtPct(wi.multiplier * 100) + ' gain'"></div>
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
                                        :class="summaryCopyFlash ? 'bg-indigo-50 text-indigo-700' : ''"
                                        x-text="summaryCopyFlash ? '✓ Copied!' : 'Copy Summary'"></button>
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
                            ['📊','Standard ROI','Investment cost and return value — instant ROI percentage'],
                            ['📅','Annualized (CAGR)','Add a time period for annual return rate and doubling time'],
                            ['📣','Marketing ROI','Ad spend vs revenue — ROAS, break-even, gross profit'],
                            ['🎯','ROI Gauge','Visual speedometer showing where your ROI falls'],
                            ['📈','Benchmarks','Compare your return to savings, bonds, and S&P 500'],
                            ['💡','What-If','See how different returns change your ROI'],
                        ] as [$icon,$title,$desc])
                        <div class="card p-4 text-center hover:border-indigo-200 transition-colors">
                            <p class="text-2xl mb-1.5">{{ $icon }}</p>
                            <p class="text-sm font-semibold text-gray-700">{{ $title }}</p>
                            <p class="text-xs text-gray-400 mt-1 leading-snug">{{ $desc }}</p>
                        </div>
                        @endforeach
                    </div>
                    <div class="card p-4" style="background:linear-gradient(135deg,#eef2ff,#f5f3ff);border-color:#c7d2fe">
                        <p class="text-sm font-semibold text-indigo-700 mb-2">📐 ROI Formulas</p>
                        <div class="space-y-1 text-xs text-gray-600 font-mono">
                            <p>Standard ROI  = (Net Profit ÷ Investment) × 100</p>
                            <p>Annualized     = (FV ÷ PV)^(1÷years) − 1 &nbsp;(CAGR)</p>
                            <p>Marketing ROI  = (Revenue − Costs − Spend) ÷ Spend × 100</p>
                            <p>ROAS           = Revenue ÷ Ad Spend</p>
                        </div>
                    </div>
                </div>

                {{-- Related tools --}}
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
/* ═══════════════════════════════════════════════════════════════
   ROI CALCULATOR — pure client-side Alpine.js component
   Prefix: ri-   Component: roiCalc()
═══════════════════════════════════════════════════════════════ */
function roiCalc() {
    return {
        /* ── Mode ── */
        mode: 'standard',   // 'standard' | 'annualized' | 'marketing'
        gainMode: 'finalValue',   // 'finalValue' | 'netProfit'

        /* ── Standard / Annualized inputs ── */
        currency:    '$',
        investment:  '10000',
        finalValue:  '13500',
        netProfit:   '3500',
        addlCosts:   '0',
        timePeriod:  '3',
        timeUnit:    'years',

        /* ── Marketing inputs ── */
        adSpend:     '5000',
        mktRevenue:  '20000',
        mktCOGS:     '8000',
        hasCOGS:     true,
        mktDuration: '',
        mktDurUnit:  'months',

        /* ── State ── */
        phase: 'idle',
        error: '',
        result: null,
        showYearTable: false,
        summaryCopyFlash: false,

        /* ── Lifecycle ── */
        init() {},

        switchMode(m) {
            this.mode = m;
            this.error = '';
            this.result = null;
            this.phase = 'idle';
        },

        autoCalc() {
            var self = this;
            try {
                if (this.mode === 'standard' || this.mode === 'annualized') {
                    var inv = parseFloat(this.investment);
                    if (isNaN(inv) || inv <= 0) return;
                    if (this.gainMode === 'finalValue') {
                        if (isNaN(parseFloat(this.finalValue))) return;
                    } else {
                        if (isNaN(parseFloat(this.netProfit))) return;
                    }
                } else {
                    if (isNaN(parseFloat(this.adSpend))   || parseFloat(this.adSpend)   <= 0) return;
                    if (isNaN(parseFloat(this.mktRevenue)) || parseFloat(this.mktRevenue) < 0) return;
                }
                self.calculate();
            } catch(e) {}
        },

        calculate() {
            this.error = '';
            var self = this;
            try {
                var v = this._validate();
                self.phase = 'loading';
                setTimeout(function() {
                    try {
                        self.result = self._compute(v);
                        self.phase = 'done';
                        if (window.innerWidth < 1024) {
                            var el = document.getElementById('ri-results');
                            if (el) setTimeout(function(){ el.scrollIntoView({behavior:'smooth',block:'start'}); }, 80);
                        }
                    } catch(e) {
                        self.error = String(e);
                        self.phase = 'idle';
                    }
                }, 110);
            } catch(e) {
                this.error = String(e);
                this.phase = 'idle';
            }
        },

        /* ── Validate inputs ── */
        _validate() {
            var v = {};
            if (this.mode === 'marketing') {
                v.investment = parseFloat(this.adSpend);
                v.revenue    = parseFloat(this.mktRevenue);
                v.cogs       = this.hasCOGS ? (parseFloat(this.mktCOGS) || 0) : 0;
                if (isNaN(v.investment) || v.investment <= 0) throw 'Enter a valid marketing spend amount.';
                if (isNaN(v.revenue) || v.revenue < 0)       throw 'Enter a valid revenue amount.';
                if (v.cogs < 0)                              throw 'COGS cannot be negative.';
                if (this.hasCOGS && v.cogs >= v.revenue)     throw 'COGS cannot exceed revenue.';
                var dur = parseFloat(this.mktDuration);
                v.months = (!isNaN(dur) && dur > 0)
                    ? Math.max(1, Math.round(this.mktDurUnit === 'years' ? dur * 12 : dur))
                    : null;

            } else {
                v.investment = parseFloat(this.investment);
                if (isNaN(v.investment) || v.investment <= 0) throw 'Investment cost must be a positive number.';
                if (v.investment > 1e15)                      throw 'Investment value is too large.';

                var addl = parseFloat(this.addlCosts) || 0;
                if (addl < 0) throw 'Additional costs cannot be negative.';

                if (this.gainMode === 'finalValue') {
                    var fv = parseFloat(this.finalValue);
                    if (isNaN(fv) || fv < 0) throw 'Final value must be zero or a positive number.';
                    v.gain = fv - v.investment - addl;
                    v.totalReturn = fv;
                } else {
                    var np = parseFloat(this.netProfit);
                    if (isNaN(np)) throw 'Enter a valid net profit (use a negative number for a loss).';
                    v.gain = np - addl;
                    v.totalReturn = v.investment + v.gain;
                }

                if (this.mode === 'annualized') {
                    var tp = parseFloat(this.timePeriod);
                    if (isNaN(tp) || tp <= 0) throw 'Time period must be a positive number.';
                    v.years = this.timeUnit === 'months' ? tp / 12 : tp;
                    if (v.years > 200) throw 'Time period cannot exceed 200 years.';
                }
            }
            return v;
        },

        /* ── Main computation ── */
        _compute(v) {
            var roi, netProfit, totalReturn, investment, cagr=null, monthlyROI=null, doublingYears=null;
            var mktRevenue=0, mktCOGS=0, grossProfit=0, roas=null, breakEvenRevenue=0;

            if (this.mode === 'marketing') {
                investment      = v.investment;
                mktRevenue      = v.revenue;
                mktCOGS         = v.cogs;
                grossProfit     = v.revenue - v.cogs;
                netProfit       = grossProfit - v.investment;
                roi             = netProfit / v.investment * 100;
                totalReturn     = v.revenue - v.cogs;
                roas            = v.revenue / v.investment;
                breakEvenRevenue = v.investment + v.cogs;

                // Annualized if duration given
                if (v.months !== null && v.months > 0) {
                    var years = v.months / 12;
                    if (totalReturn > 0 && investment > 0) {
                        cagr = (Math.pow(totalReturn / investment, 1 / years) - 1) * 100;
                    }
                }

            } else {
                investment  = v.investment;
                netProfit   = v.gain;
                totalReturn = v.totalReturn;
                roi         = (netProfit / investment) * 100;

                if (this.mode === 'annualized' && v.years) {
                    var fv = totalReturn;
                    var pv = investment;
                    if (fv > 0 && pv > 0) {
                        cagr = (Math.pow(fv / pv, 1 / v.years) - 1) * 100;
                        monthlyROI = (Math.pow(1 + cagr / 100, 1 / 12) - 1) * 100;
                    }
                    if (cagr !== null && cagr > 0) {
                        doublingYears = Math.LN2 / Math.log(1 + cagr / 100);
                    } else if (cagr !== null && cagr === 0) {
                        doublingYears = Infinity;
                    }
                }
            }

            var profitFactor = investment > 0 ? totalReturn / investment : 1;

            // Gauge
            var gauge = this._buildGauge(roi);

            // ROI level
            var levelInfo = this._roiLevel(roi);

            // What-if (modes 1 & 2)
            var whatIf = [];
            if (this.mode !== 'marketing') {
                var self = this;
                [-0.5, 0, 0.5, 1].forEach(function(mult) {
                    var altGain = netProfit + netProfit * mult;
                    var altROI  = investment > 0 ? (altGain / investment) * 100 : 0;
                    whatIf.push({
                        multiplier: mult,
                        gain: altGain,
                        roi: altROI,
                        isCurrent: mult === 0,
                    });
                });
            }

            // Benchmarks (annualized mode)
            var benchmarks = null;
            if (this.mode === 'annualized' && cagr !== null) {
                benchmarks = this._buildBenchmarks(cagr);
            }

            // Year-by-year table
            var yearTable = null;
            if (this.mode === 'annualized' && cagr !== null && v.years) {
                yearTable = this._buildYearTable(investment, cagr / 100, Math.round(v.years));
            }

            return {
                investment: investment,
                netProfit: netProfit,
                totalReturn: totalReturn,
                roi: roi,
                profitFactor: profitFactor > 0 ? profitFactor : 0,
                cagr: cagr,
                monthlyROI: monthlyROI,
                doublingYears: doublingYears,
                roiLevel: levelInfo.label,
                levelIdx: levelInfo.idx,
                gaugeArcPath: gauge.arcPath,
                gaugeColor: gauge.color,
                needleX: gauge.needleX,
                needleY: gauge.needleY,
                whatIf: whatIf,
                benchmarks: benchmarks,
                yearTable: yearTable,
                mktRevenue: mktRevenue,
                mktCOGS: mktCOGS,
                grossProfit: grossProfit,
                roas: roas,
                breakEvenRevenue: breakEvenRevenue,
            };
        },

        /* ── SVG Gauge ── */
        _buildGauge(roi) {
            var minROI = -100, maxROI = 500;
            var normalized = Math.max(0, Math.min(1, (roi - minROI) / (maxROI - minROI)));

            // Needle angle: 180° (left) to 360° (right) through top
            var svgAngleDeg = 180 + normalized * 180;
            var svgAngleRad = svgAngleDeg * Math.PI / 180;
            var r = 80, cx = 100, cy = 100;

            var needleLen = 62;
            var needleX = (cx + needleLen * Math.cos(svgAngleRad)).toFixed(2);
            var needleY = (cy + needleLen * Math.sin(svgAngleRad)).toFixed(2);

            // Arc path from (20, 100) counterclockwise to current position
            var arcPath = '';
            if (normalized > 0.001) {
                var ax = (cx + r * Math.cos(svgAngleRad)).toFixed(2);
                var ay = (cy + r * Math.sin(svgAngleRad)).toFixed(2);
                var largeArc = normalized >= 1 ? 1 : 0;
                arcPath = 'M 20,100 A ' + r + ',' + r + ' 0 ' + largeArc + ' 0 ' + ax + ',' + ay;
            }

            // Color
            var color;
            if (roi < 0)    color = '#ef4444';
            else if (roi < 10)  color = '#f59e0b';
            else if (roi < 50)  color = '#84cc16';
            else if (roi < 100) color = '#10b981';
            else                color = '#4f46e5';

            return { arcPath: arcPath, needleX: needleX, needleY: needleY, color: color };
        },

        /* ── ROI Quality Level ── */
        _roiLevel(roi) {
            if (roi < 0)   return { label: 'Net Loss',     idx: 0 };
            if (roi < 5)   return { label: 'Poor Return',  idx: 1 };
            if (roi < 15)  return { label: 'Average',      idx: 2 };
            if (roi < 50)  return { label: 'Good Return',  idx: 3 };
            if (roi < 100) return { label: 'Strong Return',idx: 4 };
            return             { label: 'Excellent!',      idx: 5 };
        },

        /* ── Benchmark comparison ── */
        _buildBenchmarks(userCAGR) {
            var data = [
                { name: 'High-Yield Savings',  roi: 4.5,  color: '#94a3b8' },
                { name: 'Gov\'t Bonds (10yr)', roi: 4.8,  color: '#64748b' },
                { name: 'Real Estate (avg)',   roi: 8.6,  color: '#f59e0b' },
                { name: 'S&P 500 (10yr avg)', roi: 10.2,  color: '#10b981' },
                { name: 'Your ROI (CAGR)',     roi: userCAGR, color: '#4f46e5', isUser: true },
            ];
            var maxROI = Math.max(15, Math.max.apply(null, data.map(function(d){ return Math.abs(d.roi); })));
            return data.map(function(d) {
                return Object.assign({}, d, {
                    barPct: Math.max(0, Math.min(100, (d.roi / maxROI) * 100)),
                });
            });
        },

        /* ── Year-by-year table ── */
        _buildYearTable(investment, cagr_dec, years) {
            var table = [];
            var val = investment;
            var clampedYears = Math.min(years, 50);
            for (var y = 1; y <= clampedYears; y++) {
                var newVal = val * (1 + cagr_dec);
                var gain   = newVal - val;
                var runROI = ((newVal - investment) / investment) * 100;
                table.push({ year: y, startVal: val, gain: gain, endVal: newVal, runROI: runROI });
                val = newVal;
            }
            return table;
        },

        /* ── Format helpers ── */
        fmtC(v) {
            if (v === null || v === undefined || isNaN(v) || !isFinite(v)) return '—';
            var neg = v < 0 ? '-' : '';
            var abs = Math.abs(v);
            var s;
            if (abs >= 1e9)       s = (abs/1e9).toFixed(2) + 'B';
            else if (abs >= 1e6)  s = (abs/1e6).toFixed(2) + 'M';
            else if (abs >= 1e3)  s = abs.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            else                  s = abs.toFixed(2);
            return neg + this.currency + s;
        },
        fmtPct(v) {
            if (isNaN(v) || !isFinite(v)) return '—';
            return parseFloat(v.toFixed(2)) + '%';
        },

        /* ── Sample data ── */
        loadSample() {
            if (this.mode === 'standard') {
                this.currency = '$'; this.investment = '10000'; this.finalValue = '13500';
                this.gainMode = 'finalValue'; this.addlCosts = '200';
            } else if (this.mode === 'annualized') {
                this.currency = '$'; this.investment = '50000'; this.finalValue = '89543';
                this.gainMode = 'finalValue'; this.addlCosts = '0';
                this.timePeriod = '5'; this.timeUnit = 'years';
            } else {
                this.adSpend = '5000'; this.mktRevenue = '20000'; this.mktCOGS = '8000';
                this.hasCOGS = true; this.mktDuration = '3'; this.mktDurUnit = 'months';
            }
            this.error = ''; this.result = null; this.phase = 'idle';
            var self = this;
            this.$nextTick(function(){ self.calculate(); });
        },

        clearAll() {
            this.error = ''; this.result = null; this.phase = 'idle'; this.showYearTable = false;
        },

        /* ── Summary text ── */
        _buildSummary() {
            if (!this.result) return '';
            var r = this.result;
            var lines = ['ROI Calculator Results', '======================'];
            if (this.mode === 'marketing') {
                lines = lines.concat([
                    'Mode             : Marketing ROI',
                    'Ad Spend         : ' + this.fmtC(r.investment),
                    'Revenue          : ' + this.fmtC(r.mktRevenue),
                    'COGS             : ' + this.fmtC(r.mktCOGS),
                    'Gross Profit     : ' + this.fmtC(r.grossProfit),
                    'Net Profit       : ' + this.fmtC(r.netProfit),
                    'Marketing ROI    : ' + (r.roi >= 0 ? '+' : '') + r.roi.toFixed(2) + '%',
                    'ROAS             : ' + (r.roas ? r.roas.toFixed(2) + 'x' : '—'),
                    'Break-even Rev   : ' + this.fmtC(r.breakEvenRevenue),
                ]);
            } else {
                lines = lines.concat([
                    'Mode             : ' + (this.mode === 'annualized' ? 'Annualized ROI' : 'Standard ROI'),
                    'Investment       : ' + this.fmtC(r.investment),
                    'Net Profit       : ' + this.fmtC(r.netProfit),
                    'Total Return     : ' + this.fmtC(r.totalReturn),
                    'ROI              : ' + (r.roi >= 0 ? '+' : '') + r.roi.toFixed(2) + '%',
                    'Quality Level    : ' + r.roiLevel,
                ]);
                if (r.cagr !== null) {
                    lines = lines.concat([
                        'CAGR (Annual)    : ' + r.cagr.toFixed(2) + '%',
                        'Monthly Return   : ' + (r.monthlyROI ? r.monthlyROI.toFixed(3) + '%' : '—'),
                        'Doubling Time    : ' + (r.doublingYears && r.doublingYears < 1000 ? r.doublingYears.toFixed(1) + ' yr' : '—'),
                    ]);
                }
            }
            return lines.join('\n');
        },

        async copySummary() {
            var text = this._buildSummary(); if (!text) return;
            try { await navigator.clipboard.writeText(text); }
            catch(e) {
                var ta = document.createElement('textarea');
                ta.value = text; ta.style.cssText = 'position:fixed;opacity:0;';
                document.body.appendChild(ta); ta.select();
                document.execCommand('copy'); document.body.removeChild(ta);
            }
            var self = this; this.summaryCopyFlash = true;
            setTimeout(function(){ self.summaryCopyFlash = false; }, 1800);
        },

        downloadSummary() {
            var text = this._buildSummary(); if (!text) return;
            var blob = new Blob([text], {type:'text/plain;charset=utf-8'});
            var url  = URL.createObjectURL(blob);
            var a    = document.createElement('a');
            a.href = url; a.download = 'roi-results.txt';
            document.body.appendChild(a); a.click();
            document.body.removeChild(a); URL.revokeObjectURL(url);
        },
    };
}
</script>
@endpush
