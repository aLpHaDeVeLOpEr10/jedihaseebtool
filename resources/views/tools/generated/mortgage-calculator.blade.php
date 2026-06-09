@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')
<style>
/* ══════════════════════════════════════════════
   Mortgage Calculator  —  prefix: mc-
══════════════════════════════════════════════ */

/* ── Hero payment ── */
.mc-hero-amount {
    font-size: clamp(2.2rem,5vw,3.2rem);
    font-weight: 900; line-height: 1; letter-spacing: -.025em;
    background: linear-gradient(135deg,#4f46e5 0%,#7c3aed 60%,#a78bfa 100%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    word-break: break-all;
}

/* ── Stat cards ── */
.mc-stat {
    background: #fff; border: 1.5px solid #e2e8f0; border-radius: 1.125rem;
    padding: 1rem .9rem; display: flex; flex-direction: column; align-items: center;
    gap: .3rem; text-align: center; transition: all .15s;
}
.mc-stat:hover { border-color: #c7d2fe; box-shadow: 0 4px 16px rgba(79,70,229,.07); transform: translateY(-1px); }
.mc-stat-lbl { font-size: .62rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .07em; }
.mc-stat-val { font-size: 1.3rem; font-weight: 800; word-break: break-all; }
.mc-stat-val.brand  { background: linear-gradient(135deg,#4f46e5,#7c3aed); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.mc-stat-val.cyan   { background: linear-gradient(135deg,#0891b2,#06b6d4); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.mc-stat-val.amber  { background: linear-gradient(135deg,#d97706,#f59e0b); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.mc-stat-val.rose   { background: linear-gradient(135deg,#e11d48,#f43f5e); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.mc-stat-val.emerald{ background: linear-gradient(135deg,#059669,#10b981); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.mc-stat-sub { font-size: .65rem; color: #94a3b8; }

/* ── Payment line items ── */
.mc-line { display: flex; justify-content: space-between; align-items: center;
    padding: .55rem .9rem; border-radius: .6rem; gap: .5rem; }
.mc-line:hover { background: #f8fafc; }
.mc-line-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.mc-line-lbl { font-size: .82rem; color: #4b5563; }
.mc-line-val { font-size: .88rem; font-weight: 700; color: #1f2937; font-variant-numeric: tabular-nums; }
.mc-line-total { border-top: 1.5px solid #e5e7eb; margin-top: .25rem; padding-top: .65rem; }
.mc-line-total .mc-line-lbl { font-weight: 700; color: #1f2937; font-size: .88rem; }
.mc-line-total .mc-line-val { font-size: 1rem; color: #4f46e5; }

/* ── Donut chart ── */
.mc-donut-wrap { position: relative; }
.mc-donut-center { position: absolute; inset: 0; display: flex; flex-direction: column;
    align-items: center; justify-content: center; pointer-events: none; }
.mc-donut-label { font-size: .6rem; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: .07em; }
.mc-donut-val { font-size: 1.1rem; font-weight: 900; color: #1f2937; }

/* ── Legend item ── */
.mc-legend-item { display: flex; align-items: center; gap: .55rem; padding: .45rem .6rem;
    border-radius: .55rem; cursor: default; transition: background .12s; }
.mc-legend-item:hover { background: #f8fafc; }
.mc-legend-dot { width: 11px; height: 11px; border-radius: 3px; flex-shrink: 0; }
.mc-legend-name { font-size: .78rem; color: #4b5563; flex: 1; }
.mc-legend-val  { font-size: .82rem; font-weight: 700; color: #1f2937; font-variant-numeric: tabular-nums; }
.mc-legend-pct  { font-size: .68rem; color: #9ca3af; margin-left: .3rem; }

/* ── Term pill buttons ── */
.mc-term-pill { padding: .35rem .8rem; border-radius: 9999px; font-size: .75rem; font-weight: 700;
    border: 1.5px solid #e5e7eb; background: #fff; color: #6b7280; cursor: pointer; transition: all .15s; }
.mc-term-pill:hover { border-color: #a5b4fc; color: #4f46e5; }
.mc-term-pill.active { background: #4f46e5; border-color: #4f46e5; color: #fff; box-shadow: 0 2px 8px rgba(79,70,229,.3); }

/* ── Toggle $/% ── */
.mc-toggle { display: flex; background: #f1f5f9; border-radius: .5rem; padding: .15rem; gap: .1rem; }
.mc-toggle-btn { padding: .25rem .6rem; border-radius: .35rem; font-size: .7rem; font-weight: 700; color: #64748b;
    cursor: pointer; border: none; background: none; transition: all .15s; }
.mc-toggle-btn.active { background: white; color: #4f46e5; box-shadow: 0 1px 3px rgba(0,0,0,.1); }

/* ── Section header ── */
.mc-section { font-size: .6rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: .12em;
    display: flex; align-items: center; gap: .5rem; }
.mc-section::after { content: ''; flex: 1; height: 1px; background: #f1f5f9; }

/* ── Input prefix / suffix ── */
.mc-pre-wrap { display: flex; align-items: stretch; }
.mc-pre { display: flex; align-items: center; padding: 0 .75rem; background: #f8fafc;
    border: 1px solid #d1d5db; border-right: none; border-radius: .75rem 0 0 .75rem;
    font-size: .82rem; font-weight: 700; color: #374151; white-space: nowrap; }
.mc-pre-wrap .form-input { border-radius: 0 .75rem .75rem 0 !important; flex: 1; min-width: 0; }
.mc-suf-wrap { display: flex; align-items: stretch; }
.mc-suf-wrap .form-input { border-radius: .75rem 0 0 .75rem !important; flex: 1; border-right: none; min-width: 0; }
.mc-suf { display: flex; align-items: center; padding: 0 .75rem; background: #f8fafc;
    border: 1px solid #d1d5db; border-left: none; border-radius: 0 .75rem .75rem 0;
    font-size: .78rem; font-weight: 600; color: #64748b; white-space: nowrap; }

/* ── Rate comparison table ── */
.mc-rate-table { width: 100%; border-collapse: collapse; font-size: .78rem; }
.mc-rate-table th { font-size: .63rem; font-weight: 700; color: #94a3b8; text-transform: uppercase;
    letter-spacing: .07em; padding: .5rem .75rem; border-bottom: 1.5px solid #e2e8f0; text-align: right; background: #f8fafc; }
.mc-rate-table th:first-child { text-align: center; }
.mc-rate-table td { padding: .5rem .75rem; border-bottom: 1px solid #f1f5f9; text-align: right; font-variant-numeric: tabular-nums; color: #374151; }
.mc-rate-table td:first-child { text-align: center; font-weight: 700; }
.mc-rate-table tr.mc-current td { background: #eef2ff; }
.mc-rate-table tr.mc-current td:first-child { color: #4f46e5; }
.mc-rate-table tr:last-child td { border-bottom: none; }

/* ── Amortization table ── */
.mc-amort-table { width: 100%; border-collapse: collapse; font-size: .76rem; }
.mc-amort-table th { font-size: .62rem; font-weight: 700; color: #94a3b8; text-transform: uppercase;
    letter-spacing: .07em; padding: .45rem .65rem; border-bottom: 1.5px solid #e2e8f0; text-align: right; background: #f8fafc; }
.mc-amort-table th:first-child { text-align: center; }
.mc-amort-table td { padding: .45rem .65rem; border-bottom: 1px solid #f1f5f9; text-align: right; font-variant-numeric: tabular-nums; color: #374151; }
.mc-amort-table td:first-child { text-align: center; font-weight: 600; color: #4f46e5; }
.mc-amort-table tr:hover td { background: #f5f3ff; }
.mc-amort-table tr:last-child td { border-bottom: none; font-weight: 700; }
.mc-amort-table .mc-td-int { color: #dc2626; }
.mc-amort-table .mc-td-eq  { color: #059669; font-weight: 600; }

/* ── Extra payment callout ── */
.mc-extra-callout { background: linear-gradient(135deg,#f0fdf4,#dcfce7); border: 1px solid #a7f3d0;
    border-radius: 1rem; padding: 1rem 1.25rem; }

/* ── Shimmer ── */
@keyframes mcShim { 0%{background-position:-600px 0} 100%{background-position:600px 0} }
.mc-shim { height: 5.5rem; border-radius: 1.125rem;
    background: linear-gradient(90deg,#f5f3ff 25%,#ede9fe 50%,#f5f3ff 75%);
    background-size: 1200px 100%; animation: mcShim 1.4s infinite; }

/* ── Entrance ── */
@keyframes mcIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
.mc-in { animation: mcIn .3s ease-out; }

/* ── PMI warning ── */
.mc-pmi-warn { background: #fef3c7; border: 1px solid #fcd34d; border-radius: .6rem;
    padding: .4rem .75rem; font-size: .72rem; color: #92400e; }
</style>

<div class="min-h-screen bg-gray-50"
     x-data="mortCalc()"
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
                        Calculate your <strong>monthly mortgage payment</strong>, see a full <strong>payment breakdown</strong>, compare interest rates, track your <strong>amortization schedule</strong>, and see the impact of extra payments.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2 shrink-0">
                    <span class="badge badge-primary">Free</span>
                    <span class="badge badge-gray">No Account</span>
                    <span class="badge badge-success">Extra Payment</span>
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
                    <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                        <p class="text-sm font-bold text-indigo-700">Loan Details</p>
                    </div>
                    <div class="p-5 space-y-4">

                        {{-- Currency + Home Price --}}
                        <div>
                            <label class="form-label">Currency &amp; Home Price</label>
                            <div class="flex gap-2">
                                <select x-model="currency" class="form-input w-20 py-2 text-sm shrink-0">
                                    <option value="$">$ USD</option><option value="€">€ EUR</option>
                                    <option value="£">£ GBP</option><option value="¥">¥ JPY</option>
                                    <option value="₹">₹ INR</option><option value="A$">A$</option>
                                    <option value="C$">C$</option><option value="₦">₦ NGN</option>
                                </select>
                                <div class="mc-pre-wrap flex-1">
                                    <span class="mc-pre" x-text="currency"></span>
                                    <input type="number" step="any" min="0" x-model="homePrice"
                                           @input="onHomePriceInput()"
                                           class="form-input" placeholder="e.g. 400000">
                                </div>
                            </div>
                        </div>

                        {{-- Down Payment --}}
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="form-label mb-0">Down Payment</label>
                                <div class="mc-toggle">
                                    <button type="button" class="mc-toggle-btn" :class="{active: dpMode==='$'}" @click="dpMode='$'">$</button>
                                    <button type="button" class="mc-toggle-btn" :class="{active: dpMode==='%'}" @click="dpMode='%'">%</button>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <div class="mc-pre-wrap flex-1" x-show="dpMode==='$'">
                                    <span class="mc-pre" x-text="currency"></span>
                                    <input type="number" step="any" min="0" x-model="downPayment"
                                           @input="onDownDollarInput()"
                                           class="form-input" placeholder="e.g. 80000">
                                </div>
                                <div class="mc-suf-wrap flex-1" x-show="dpMode==='%'">
                                    <input type="number" step="any" min="0" max="100" x-model="downPct"
                                           @input="onDownPctInput()"
                                           class="form-input" placeholder="e.g. 20">
                                    <span class="mc-suf">%</span>
                                </div>
                                <div class="bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-500 shrink-0 flex flex-col justify-center leading-tight">
                                    <span x-show="dpMode==='$'" x-text="(parseFloat(downPct)||0).toFixed(1) + '%'"></span>
                                    <span x-show="dpMode==='%'" x-text="fmtC(parseFloat(downPayment)||0)"></span>
                                    <span class="text-gray-400">of price</span>
                                </div>
                            </div>
                            <div class="mc-pmi-warn mt-1.5" x-show="showPMIWarning">
                                ⚠ Down payment below 20% — PMI applies until you reach 20% equity.
                            </div>
                        </div>

                        {{-- Loan Amount (read-only) --}}
                        <div>
                            <label class="form-label">Loan Amount</label>
                            <div class="mc-pre-wrap">
                                <span class="mc-pre" x-text="currency"></span>
                                <input type="text" readonly :value="fmtNum(loanAmount)"
                                       class="form-input bg-gray-50 cursor-default font-semibold"
                                       style="color:#4f46e5">
                            </div>
                            <p class="form-help">Home price minus down payment.</p>
                        </div>

                        {{-- Interest Rate --}}
                        <div>
                            <label class="form-label">Annual Interest Rate</label>
                            <div class="mc-suf-wrap">
                                <input type="number" step="0.01" min="0" max="50" x-model="interestRate"
                                       @input.debounce.350ms="autoCalc()"
                                       class="form-input" placeholder="e.g. 7.00">
                                <span class="mc-suf">% / year</span>
                            </div>
                        </div>

                        {{-- Loan Term --}}
                        <div>
                            <label class="form-label">Loan Term</label>
                            <div class="flex flex-wrap gap-1.5 mb-2">
                                <template x-for="yr in [10,15,20,30]" :key="yr">
                                    <button type="button" class="mc-term-pill"
                                            :class="{active: !useCustomTerm && termYears===String(yr)}"
                                            @click="useCustomTerm=false; termYears=String(yr); autoCalc()"
                                            x-text="yr + ' yr'"></button>
                                </template>
                                <button type="button" class="mc-term-pill" :class="{active: useCustomTerm}"
                                        @click="useCustomTerm=true">Custom</button>
                            </div>
                            <div x-show="useCustomTerm" x-transition>
                                <div class="mc-suf-wrap">
                                    <input type="number" step="1" min="1" max="50" x-model="customTerm"
                                           @input.debounce.350ms="autoCalc()"
                                           class="form-input" placeholder="e.g. 25">
                                    <span class="mc-suf">years</span>
                                </div>
                            </div>
                        </div>

                        {{-- Start Date --}}
                        <div>
                            <label class="form-label">Loan Start Date</label>
                            <div class="flex gap-2">
                                <select x-model="startMonth" @change="autoCalc()" class="form-input flex-1 text-sm">
                                    <option value="1">January</option><option value="2">February</option>
                                    <option value="3">March</option><option value="4">April</option>
                                    <option value="5">May</option><option value="6">June</option>
                                    <option value="7">July</option><option value="8">August</option>
                                    <option value="9">September</option><option value="10">October</option>
                                    <option value="11">November</option><option value="12">December</option>
                                </select>
                                <input type="number" step="1" min="1900" max="2100" x-model="startYear"
                                       @input.debounce.350ms="autoCalc()"
                                       class="form-input w-24" placeholder="2026">
                            </div>
                        </div>

                        {{-- Monthly Costs divider --}}
                        <div class="mc-section pt-1">Monthly Costs <span class="font-normal lowercase tracking-normal text-gray-400">(optional)</span></div>

                        {{-- Property Tax --}}
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="form-label mb-0">Property Tax</label>
                                <div class="mc-toggle">
                                    <button type="button" class="mc-toggle-btn" :class="{active: propTaxMode==='$'}" @click="propTaxMode='$'">$/yr</button>
                                    <button type="button" class="mc-toggle-btn" :class="{active: propTaxMode==='%'}" @click="propTaxMode='%'">%/yr</button>
                                </div>
                            </div>
                            <div class="mc-pre-wrap" x-show="propTaxMode==='$'">
                                <span class="mc-pre" x-text="currency"></span>
                                <input type="number" step="any" min="0" x-model="propTaxYr"
                                       @input.debounce.350ms="autoCalc()"
                                       class="form-input" placeholder="e.g. 4800">
                            </div>
                            <div class="mc-suf-wrap" x-show="propTaxMode==='%'">
                                <input type="number" step="any" min="0" x-model="propTaxPct"
                                       @input.debounce.350ms="autoCalc()"
                                       class="form-input" placeholder="e.g. 1.2">
                                <span class="mc-suf">%/yr</span>
                            </div>
                        </div>

                        {{-- Home Insurance --}}
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="form-label mb-0">Home Insurance</label>
                                <div class="mc-toggle">
                                    <button type="button" class="mc-toggle-btn" :class="{active: insMode==='$'}" @click="insMode='$'">$/yr</button>
                                    <button type="button" class="mc-toggle-btn" :class="{active: insMode==='%'}" @click="insMode='%'">%/yr</button>
                                </div>
                            </div>
                            <div class="mc-pre-wrap" x-show="insMode==='$'">
                                <span class="mc-pre" x-text="currency"></span>
                                <input type="number" step="any" min="0" x-model="insYr"
                                       @input.debounce.350ms="autoCalc()"
                                       class="form-input" placeholder="e.g. 1200">
                            </div>
                            <div class="mc-suf-wrap" x-show="insMode==='%'">
                                <input type="number" step="any" min="0" x-model="insPct"
                                       @input.debounce.350ms="autoCalc()"
                                       class="form-input" placeholder="e.g. 0.5">
                                <span class="mc-suf">%/yr</span>
                            </div>
                        </div>

                        {{-- PMI (auto-enabled) --}}
                        <div x-show="showPMIWarning">
                            <label class="form-label">PMI Rate</label>
                            <div class="mc-suf-wrap">
                                <input type="number" step="0.01" min="0" x-model="pmiRate"
                                       @input.debounce.350ms="autoCalc()"
                                       class="form-input" placeholder="0.5">
                                <span class="mc-suf">%/yr</span>
                            </div>
                            <p class="form-help">Applied until loan balance &lt; 80% of home price.</p>
                        </div>

                        {{-- HOA --}}
                        <div>
                            <label class="form-label">HOA Fee</label>
                            <div class="mc-pre-wrap">
                                <span class="mc-pre" x-text="currency"></span>
                                <input type="number" step="any" min="0" x-model="hoaMonthly"
                                       @input.debounce.350ms="autoCalc()"
                                       class="form-input" placeholder="0">
                            </div>
                            <p class="form-help">Monthly HOA or condo fee.</p>
                        </div>

                        {{-- Extra Payment divider --}}
                        <div class="mc-section pt-1">Extra Payment <span class="font-normal lowercase tracking-normal text-gray-400">(saves interest)</span></div>

                        <div>
                            <label class="form-label">Extra Monthly Principal</label>
                            <div class="mc-pre-wrap">
                                <span class="mc-pre" x-text="currency"></span>
                                <input type="number" step="any" min="0" x-model="extraPayment"
                                       @input.debounce.350ms="autoCalc()"
                                       class="form-input" placeholder="0">
                            </div>
                            <p class="form-help">Added to principal each month — reduces term &amp; interest.</p>
                        </div>

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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Calculate
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
            <div class="lg:col-span-3 space-y-4" id="mc-results">

                {{-- Shimmer --}}
                <div x-show="phase==='loading'" class="grid grid-cols-2 gap-3">
                    <template x-for="i in 4" :key="i"><div class="mc-shim"></div></template>
                </div>

                {{-- ══ RESULTS ══ --}}
                <template x-if="phase==='done' && result">
                    <div class="space-y-4 mc-in">

                        {{-- Hero total payment --}}
                        <div class="card overflow-hidden">
                            <div style="background:linear-gradient(135deg,#eef2ff 0%,#ede9fe 100%);" class="px-6 py-5">
                                <p class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-1">Total Monthly Payment</p>
                                <p class="mc-hero-amount" x-text="fmtC(result.totalMonthly)"></p>
                                <p class="text-xs text-gray-500 mt-2">
                                    P&amp;I: <strong x-text="fmtC(result.monthlyPI)"></strong>
                                    <template x-if="result.monthlyTax > 0">
                                        <span> · Tax: <strong x-text="fmtC(result.monthlyTax)"></strong></span>
                                    </template>
                                    <template x-if="result.monthlyIns > 0">
                                        <span> · Ins: <strong x-text="fmtC(result.monthlyIns)"></strong></span>
                                    </template>
                                    <template x-if="result.monthlyPMI > 0">
                                        <span> · PMI: <strong x-text="fmtC(result.monthlyPMI)"></strong></span>
                                    </template>
                                    <template x-if="result.monthlyHOA > 0">
                                        <span> · HOA: <strong x-text="fmtC(result.monthlyHOA)"></strong></span>
                                    </template>
                                </p>
                            </div>
                        </div>

                        {{-- Donut chart + Payment lines --}}
                        <div class="card p-4">
                            <p class="text-sm font-semibold text-gray-700 mb-4">Monthly Payment Breakdown</p>
                            <div class="flex flex-col sm:flex-row items-center gap-4">

                                {{-- Donut SVG --}}
                                <div class="mc-donut-wrap shrink-0" style="width:170px;height:170px;">
                                    <svg viewBox="0 0 170 170" width="170" height="170">
                                        <circle cx="85" cy="85" r="62" fill="none" stroke="#f1f5f9" stroke-width="24"/>
                                        <template x-for="seg in result.donutSegs" :key="seg.label">
                                            <circle cx="85" cy="85" r="62" fill="none"
                                                :stroke="seg.color" stroke-width="24"
                                                :stroke-dasharray="seg.dash"
                                                :stroke-dashoffset="seg.offset"
                                                transform="rotate(-90 85 85)"
                                                stroke-linecap="butt"/>
                                        </template>
                                    </svg>
                                    <div class="mc-donut-center">
                                        <span class="mc-donut-label">monthly</span>
                                        <span class="mc-donut-val" x-text="fmtC(result.totalMonthly)"></span>
                                    </div>
                                </div>

                                {{-- Legend --}}
                                <div class="flex-1 w-full space-y-0.5">
                                    <template x-for="seg in result.donutSegs" :key="'l'+seg.label">
                                        <div class="mc-legend-item">
                                            <span class="mc-legend-dot" :style="'background:'+seg.color"></span>
                                            <span class="mc-legend-name" x-text="seg.label"></span>
                                            <span class="mc-legend-val" x-text="fmtC(seg.value)"></span>
                                            <span class="mc-legend-pct" x-text="'(' + seg.pct.toFixed(1) + '%)'"></span>
                                        </div>
                                    </template>
                                    <div class="mc-legend-item border-t border-gray-100 mt-1 pt-1.5">
                                        <span class="mc-legend-dot" style="background:transparent;border:2px solid #9ca3af"></span>
                                        <span class="mc-legend-name font-bold text-gray-800">Total</span>
                                        <span class="mc-legend-val" style="color:#4f46e5;font-size:.92rem" x-text="fmtC(result.totalMonthly)"></span>
                                        <span class="mc-legend-pct">100%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Loan summary stats --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div class="mc-stat">
                                <div class="mc-stat-lbl">Loan Amount</div>
                                <div class="mc-stat-val brand" x-text="fmtC(result.loanAmount)"></div>
                                <div class="mc-stat-sub" x-text="fmtPct((result.loanAmount/result.homePrice)*100) + ' LTV'"></div>
                            </div>
                            <div class="mc-stat">
                                <div class="mc-stat-lbl">Interest Rate</div>
                                <div class="mc-stat-val amber" x-text="fmtPct(parseFloat(interestRate))"></div>
                                <div class="mc-stat-sub">annual</div>
                            </div>
                            <div class="mc-stat">
                                <div class="mc-stat-lbl">Loan Term</div>
                                <div class="mc-stat-val cyan" x-text="result.effectiveTerm + ' years'"></div>
                                <div class="mc-stat-sub" x-text="result.effectiveTerm * 12 + ' payments'"></div>
                            </div>
                            <div class="mc-stat">
                                <div class="mc-stat-lbl">Payoff Date</div>
                                <div class="mc-stat-val" style="font-size:1rem" x-text="result.payoffDate"></div>
                                <div class="mc-stat-sub" x-show="result.extraMonthsSaved > 0" x-text="result.extraMonthsSaved + ' mo. early'"></div>
                                <div class="mc-stat-sub" x-show="result.extraMonthsSaved <= 0">standard term</div>
                            </div>
                        </div>

                        {{-- Total cost summary --}}
                        <div class="card p-4">
                            <p class="text-sm font-semibold text-gray-700 mb-3">Total Cost Over Loan Life</p>
                            <div class="space-y-0.5">
                                <div class="mc-line">
                                    <span class="flex items-center gap-2">
                                        <span class="mc-line-dot" style="background:#4f46e5"></span>
                                        <span class="mc-line-lbl">Loan Principal</span>
                                    </span>
                                    <span class="mc-line-val" x-text="fmtC(result.loanAmount)"></span>
                                </div>
                                <div class="mc-line">
                                    <span class="flex items-center gap-2">
                                        <span class="mc-line-dot" style="background:#e11d48"></span>
                                        <span class="mc-line-lbl">Total Interest</span>
                                    </span>
                                    <span class="mc-line-val" style="color:#e11d48" x-text="fmtC(result.totalInterest)"></span>
                                </div>
                                <div class="mc-line" x-show="result.totalTax > 0">
                                    <span class="flex items-center gap-2">
                                        <span class="mc-line-dot" style="background:#0891b2"></span>
                                        <span class="mc-line-lbl">Property Taxes</span>
                                    </span>
                                    <span class="mc-line-val" x-text="fmtC(result.totalTax)"></span>
                                </div>
                                <div class="mc-line" x-show="result.totalIns > 0">
                                    <span class="flex items-center gap-2">
                                        <span class="mc-line-dot" style="background:#f59e0b"></span>
                                        <span class="mc-line-lbl">Home Insurance</span>
                                    </span>
                                    <span class="mc-line-val" x-text="fmtC(result.totalIns)"></span>
                                </div>
                                <div class="mc-line" x-show="result.totalPMI > 0">
                                    <span class="flex items-center gap-2">
                                        <span class="mc-line-dot" style="background:#ef4444"></span>
                                        <span class="mc-line-lbl">PMI <span class="text-gray-400 font-normal text-xs">(until 20% equity)</span></span>
                                    </span>
                                    <span class="mc-line-val" x-text="fmtC(result.totalPMI)"></span>
                                </div>
                                <div class="mc-line" x-show="result.totalHOA > 0">
                                    <span class="flex items-center gap-2">
                                        <span class="mc-line-dot" style="background:#8b5cf6"></span>
                                        <span class="mc-line-lbl">HOA Fees</span>
                                    </span>
                                    <span class="mc-line-val" x-text="fmtC(result.totalHOA)"></span>
                                </div>
                                <div class="mc-line mc-line-total">
                                    <span class="mc-line-lbl">Grand Total Cost</span>
                                    <span class="mc-line-val" x-text="fmtC(result.grandTotal)"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Extra Payment Impact --}}
                        <div class="mc-extra-callout" x-show="result.extraMonthsSaved > 0">
                            <p class="text-sm font-bold text-emerald-800 mb-2 flex items-center gap-2">
                                <span>💰</span> Extra Payment Impact
                            </p>
                            <div class="grid grid-cols-3 gap-3">
                                <div class="text-center">
                                    <p class="text-xs text-emerald-600">Time Saved</p>
                                    <p class="font-black text-emerald-900 text-lg" x-text="fmtMonths(result.extraMonthsSaved)"></p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-emerald-600">Interest Saved</p>
                                    <p class="font-black text-emerald-900 text-lg" x-text="fmtC(result.extraInterestSaved)"></p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-emerald-600">New Payoff</p>
                                    <p class="font-black text-emerald-900 text-base" x-text="result.payoffDate"></p>
                                </div>
                            </div>
                            <p class="text-xs text-emerald-700 mt-2.5 bg-emerald-100 rounded-lg px-3 py-2">
                                Paying <strong x-text="fmtC(parseFloat(extraPayment))"></strong> extra/month saves you
                                <strong x-text="fmtC(result.extraInterestSaved)"></strong> in interest
                                and pays off your mortgage <strong x-text="fmtMonths(result.extraMonthsSaved)"></strong> early.
                            </p>
                        </div>

                        {{-- Rate Comparison --}}
                        <div class="card overflow-hidden">
                            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                                <p class="text-sm font-semibold text-gray-700">Rate Comparison</p>
                            </div>
                            <div style="overflow-x:auto;">
                                <table class="mc-rate-table">
                                    <thead>
                                        <tr>
                                            <th>Rate</th>
                                            <th>Monthly P&amp;I</th>
                                            <th>Total Interest</th>
                                            <th>Total Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="row in result.rateComp" :key="row.rate">
                                            <tr :class="{'mc-current': row.isCurrent}">
                                                <td x-text="row.rate.toFixed(2) + '%'"></td>
                                                <td x-text="fmtC(row.monthlyPI)"></td>
                                                <td x-text="fmtC(row.totalInterest)"></td>
                                                <td x-text="fmtC(row.totalCost)"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Amortization Schedule --}}
                        <div class="card overflow-hidden">
                            <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100 bg-gray-50">
                                <span class="text-sm font-semibold text-gray-700">Amortization Schedule</span>
                                <button type="button" @click="showAmort=!showAmort" class="btn btn-secondary btn-sm flex items-center gap-1">
                                    <span x-text="showAmort ? 'Hide' : 'Show'"></span>
                                    <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{'-rotate-180': showAmort}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            </div>
                            <div x-show="showAmort" x-transition style="max-height:420px;overflow-y:auto;">
                                <table class="mc-amort-table">
                                    <thead>
                                        <tr>
                                            <th>Yr</th>
                                            <th>Opening Balance</th>
                                            <th>Principal</th>
                                            <th>Interest</th>
                                            <th>Closing Balance</th>
                                            <th>Equity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="row in result.amortYearly" :key="row.year">
                                            <tr>
                                                <td x-text="row.year"></td>
                                                <td x-text="fmtC(row.openBal)"></td>
                                                <td x-text="fmtC(row.principal)"></td>
                                                <td class="mc-td-int" x-text="fmtC(row.interest)"></td>
                                                <td x-text="fmtC(row.closeBal)"></td>
                                                <td class="mc-td-eq" x-text="row.equity.toFixed(1) + '%'"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
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
                            ['🏠','Monthly Payment','Principal, interest, taxes, insurance, PMI, and HOA combined'],
                            ['🍩','Payment Breakdown','Interactive donut chart showing every cost component'],
                            ['📉','Rate Comparison','See how ±2% in rate changes your payment and total cost'],
                            ['💰','Extra Payments','Calculate how extra principal reduces term and total interest'],
                            ['📊','Amortization','Year-by-year balance, equity, principal, and interest breakdown'],
                            ['📅','Payoff Date','Know exactly when you will own your home outright'],
                        ] as [$icon,$title,$desc])
                        <div class="card p-4 text-center hover:border-indigo-200 transition-colors">
                            <p class="text-2xl mb-1.5">{{ $icon }}</p>
                            <p class="text-sm font-semibold text-gray-700">{{ $title }}</p>
                            <p class="text-xs text-gray-400 mt-1 leading-snug">{{ $desc }}</p>
                        </div>
                        @endforeach
                    </div>
                    <div class="card p-4" style="background:linear-gradient(135deg,#eef2ff,#f5f3ff);border-color:#c7d2fe">
                        <p class="text-sm font-semibold text-indigo-700 mb-1">📐 Mortgage Payment Formula</p>
                        <p class="text-xs text-gray-600 font-mono">M = P × [r(1+r)ⁿ] / [(1+r)ⁿ – 1]</p>
                        <p class="text-xs text-gray-400 mt-1">P = loan · r = monthly rate · n = total payments (term × 12)</p>
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
   MORTGAGE CALCULATOR — pure client-side Alpine.js component
═══════════════════════════════════════════════════════════════ */
function mortCalc() {
    return {
        /* ── Inputs ── */
        currency:     '$',
        homePrice:    '400000',
        downPayment:  '80000',
        downPct:      '20',
        dpMode:       '$',
        interestRate: '7',
        termYears:    '30',
        useCustomTerm: false,
        customTerm:   '',
        startMonth:   6,
        startYear:    2026,

        propTaxYr:    '4800',
        propTaxPct:   '1.2',
        propTaxMode:  '$',
        insYr:        '1200',
        insPct:       '0.5',
        insMode:      '$',
        pmiRate:      '0.5',
        hoaMonthly:   '0',
        extraPayment: '0',

        /* ── State ── */
        phase: 'idle',
        error: '',
        result: null,
        showAmort: false,
        summaryCopyFlash: false,

        /* ── Computed ── */
        get loanAmount() {
            var hp = parseFloat(this.homePrice) || 0;
            var dp = parseFloat(this.downPayment) || 0;
            return Math.max(0, hp - dp);
        },
        get showPMIWarning() {
            var hp = parseFloat(this.homePrice) || 0;
            var dp = parseFloat(this.downPayment) || 0;
            return hp > 0 && dp / hp < 0.20 - 1e-9;
        },

        /* ── Lifecycle ── */
        init() {
            var now = new Date();
            this.startMonth = now.getMonth() + 1;
            this.startYear  = now.getFullYear();
        },

        /* ── Down payment sync ── */
        onHomePriceInput() {
            var hp = parseFloat(this.homePrice) || 0;
            if (this.dpMode === '%') {
                var pct = parseFloat(this.downPct) || 0;
                this.downPayment = (hp * pct / 100).toFixed(0);
            } else {
                var dp = parseFloat(this.downPayment) || 0;
                this.downPct = hp > 0 ? (dp / hp * 100).toFixed(2) : '0';
            }
            this.autoCalc();
        },
        onDownDollarInput() {
            var hp = parseFloat(this.homePrice) || 0;
            var dp = parseFloat(this.downPayment) || 0;
            this.downPct = hp > 0 ? (dp / hp * 100).toFixed(2) : '0';
            this.autoCalc();
        },
        onDownPctInput() {
            var hp = parseFloat(this.homePrice) || 0;
            var pct = parseFloat(this.downPct) || 0;
            this.downPayment = (hp * pct / 100).toFixed(0);
            this.autoCalc();
        },

        autoCalc() {
            var self = this;
            try {
                var hp = parseFloat(this.homePrice) || 0;
                var r  = parseFloat(this.interestRate);
                var tm = this._effectiveTerm();
                if (hp > 0 && !isNaN(r) && r >= 0 && tm > 0) {
                    self.calculate();
                }
            } catch(e) {}
        },

        _effectiveTerm() {
            if (this.useCustomTerm) {
                var ct = parseFloat(this.customTerm);
                return isNaN(ct) ? 0 : ct;
            }
            return parseFloat(this.termYears) || 0;
        },

        /* ── Calculate ── */
        calculate() {
            this.error = '';
            var self = this;
            try {
                var v = this._validate();
                self.phase = 'loading';
                setTimeout(function() {
                    try {
                        self.result = self._computeMortgage(v);
                        self.phase = 'done';
                        if (window.innerWidth < 1024) {
                            var el = document.getElementById('mc-results');
                            if (el) setTimeout(function(){ el.scrollIntoView({behavior:'smooth',block:'start'}); }, 80);
                        }
                    } catch(e) {
                        self.error = String(e);
                        self.phase = 'idle';
                    }
                }, 130);
            } catch(e) {
                this.error = String(e);
                this.phase = 'idle';
            }
        },

        /* ── Validate ── */
        _validate() {
            var v = {};
            v.homePrice = parseFloat(this.homePrice);
            v.downPayment = parseFloat(this.downPayment) || 0;
            v.rate = parseFloat(this.interestRate);
            v.term = this._effectiveTerm();

            if (isNaN(v.homePrice) || v.homePrice <= 0) throw 'Enter a valid home price.';
            if (v.downPayment < 0)                       throw 'Down payment cannot be negative.';
            if (v.downPayment >= v.homePrice)            throw 'Down payment must be less than the home price.';
            if (isNaN(v.rate) || v.rate < 0)             throw 'Enter a valid interest rate (0% or higher).';
            if (v.rate > 50)                             throw 'Interest rate seems unreasonably high (max 50%).';
            if (isNaN(v.term) || v.term <= 0)            throw 'Select or enter a valid loan term.';
            if (v.term > 50)                             throw 'Loan term cannot exceed 50 years.';

            v.loanAmount = v.homePrice - v.downPayment;
            v.n = Math.round(v.term * 12);
            v.r = v.rate / 100 / 12;

            // Monthly costs
            var hp = v.homePrice;
            if (this.propTaxMode === '$') {
                v.monthlyTax = (parseFloat(this.propTaxYr) || 0) / 12;
            } else {
                v.monthlyTax = hp * (parseFloat(this.propTaxPct) || 0) / 100 / 12;
            }
            if (this.insMode === '$') {
                v.monthlyIns = (parseFloat(this.insYr) || 0) / 12;
            } else {
                v.monthlyIns = hp * (parseFloat(this.insPct) || 0) / 100 / 12;
            }
            if (v.monthlyTax < 0) throw 'Property tax cannot be negative.';
            if (v.monthlyIns < 0) throw 'Insurance cannot be negative.';

            v.pmiRate = this.showPMIWarning ? (parseFloat(this.pmiRate) || 0) / 100 : 0;
            v.hoaMonthly = parseFloat(this.hoaMonthly) || 0;
            v.extraPayment = Math.max(0, parseFloat(this.extraPayment) || 0);

            v.startMonth = parseInt(this.startMonth, 10) || 1;
            v.startYear  = parseInt(this.startYear,  10) || 2026;

            return v;
        },

        /* ── Core computation ── */
        _computeMortgage(v) {
            // Monthly P&I
            var monthlyPI;
            if (v.r === 0) {
                monthlyPI = v.loanAmount / v.n;
            } else {
                var powRN = Math.pow(1 + v.r, v.n);
                monthlyPI = v.loanAmount * v.r * powRN / (powRN - 1);
            }

            // Standard amortization (no extra payment)
            var stdAmort = this._amortize(v.loanAmount, v.r, v.n, monthlyPI, 0, v.homePrice, v.pmiRate);

            // Extra-payment amortization
            var extAmort = v.extraPayment > 0
                ? this._amortize(v.loanAmount, v.r, v.n, monthlyPI, v.extraPayment, v.homePrice, v.pmiRate)
                : null;

            var stdMonths  = stdAmort.actualMonths;
            var extMonths  = extAmort ? extAmort.actualMonths : stdMonths;
            var monthsSaved = stdMonths - extMonths;
            var interestSaved = extAmort ? (stdAmort.totalInterest - extAmort.totalInterest) : 0;

            // PMI: initial monthly rate (on original loan; in practice it decreases as balance decreases — we use opening-month value)
            var monthlyPMI = v.pmiRate > 0 ? v.loanAmount * v.pmiRate / 12 : 0;
            var totalMonthly = monthlyPI + v.monthlyTax + v.monthlyIns + monthlyPMI + v.hoaMonthly;

            // Total costs over full loan life (using standard amort)
            var totalInterest = stdAmort.totalInterest;
            var totalTax = v.monthlyTax * stdMonths;
            var totalIns = v.monthlyIns * stdMonths;
            var totalPMI = stdAmort.totalPMI;
            var totalHOA = v.hoaMonthly * stdMonths;
            var grandTotal = v.loanAmount + totalInterest + totalTax + totalIns + totalPMI + totalHOA;

            // APY / effective rate
            var apy = v.r > 0 ? Math.pow(1 + v.r, 12) - 1 : 0;

            // Payoff date (with extra payment)
            var payoffDate = this._payoffDate(v.startMonth, v.startYear, extMonths);

            // Donut segments
            var donutSegs = this._buildDonut([
                { label: 'Principal & Interest', value: monthlyPI,     color: '#4f46e5' },
                { label: 'Property Tax',         value: v.monthlyTax,  color: '#0891b2' },
                { label: 'Home Insurance',       value: v.monthlyIns,  color: '#f59e0b' },
                { label: 'PMI',                  value: monthlyPMI,    color: '#ef4444' },
                { label: 'HOA',                  value: v.hoaMonthly,  color: '#8b5cf6' },
            ], totalMonthly);

            // Rate comparison
            var rateComp = this._rateComp(v);

            return {
                homePrice: v.homePrice,
                loanAmount: v.loanAmount,
                downPayment: v.downPayment,
                effectiveTerm: v.term,
                monthlyPI: monthlyPI,
                monthlyTax: v.monthlyTax,
                monthlyIns: v.monthlyIns,
                monthlyPMI: monthlyPMI,
                monthlyHOA: v.hoaMonthly,
                totalMonthly: totalMonthly,
                totalInterest: totalInterest,
                totalTax: totalTax,
                totalIns: totalIns,
                totalPMI: totalPMI,
                totalHOA: totalHOA,
                grandTotal: grandTotal,
                apy: apy,
                payoffDate: payoffDate,
                extraMonthsSaved: monthsSaved,
                extraInterestSaved: interestSaved,
                donutSegs: donutSegs,
                rateComp: rateComp,
                amortYearly: (extAmort || stdAmort).yearly,
            };
        },

        /* ── Month-by-month amortization ── */
        _amortize(P, r, n, monthlyPI, extra, homePrice, pmiRate) {
            var balance = P;
            var totalInterest = 0, totalPMI = 0;
            var yearlyData = [];
            var yearOpen = P, yearPrin = 0, yearInt = 0, yearPMI = 0;
            var pmiThreshold = homePrice * 0.8;
            var actualMonths = 0;

            for (var m = 1; m <= n && balance > 0.005; m++) {
                actualMonths = m;
                var interest = balance * r;
                var principal = monthlyPI - interest + extra;
                if (principal > balance) principal = balance;
                principal = Math.max(0, principal);
                balance -= principal;
                balance = Math.max(0, balance);

                var hasPMI = pmiRate > 0 && (balance + principal) > pmiThreshold;
                var pmiAmt = hasPMI ? (balance + principal) * pmiRate / 12 : 0;

                totalInterest += interest;
                totalPMI += pmiAmt;
                yearPrin += principal;
                yearInt  += interest;
                yearPMI  += pmiAmt;

                var isYearEnd = (m % 12 === 0);
                var isLast    = (balance <= 0.005 || m === n);

                if (isYearEnd || isLast) {
                    var yearNum = Math.ceil(m / 12);
                    var equity  = homePrice > 0 ? ((homePrice - balance) / homePrice * 100) : 100;
                    yearlyData.push({
                        year: yearNum,
                        openBal: yearOpen,
                        principal: yearPrin,
                        interest: yearInt,
                        closeBal: Math.max(0, balance),
                        equity: Math.min(100, equity),
                    });
                    yearOpen = balance;
                    yearPrin = 0; yearInt = 0; yearPMI = 0;
                }
            }
            return { yearly: yearlyData, actualMonths: actualMonths, totalInterest: totalInterest, totalPMI: totalPMI };
        },

        /* ── Build donut SVG segments ── */
        _buildDonut(items, total) {
            if (total <= 0) return [];
            var segs = items.filter(function(s){ return s.value > 0.005; });
            var C = 2 * Math.PI * 62; // r=62
            var offset = 0;
            return segs.map(function(s) {
                var pct = s.value / total;
                var len = pct * C;
                var seg = {
                    label: s.label, value: s.value, color: s.color,
                    pct: pct * 100,
                    dash: len.toFixed(3) + ' ' + (C - len).toFixed(3),
                    offset: (-offset).toFixed(3),
                };
                offset += len;
                return seg;
            });
        },

        /* ── Rate comparison ── */
        _rateComp(v) {
            var self = this;
            var rates = [
                v.rate - 2, v.rate - 1, v.rate, v.rate + 1, v.rate + 2
            ].filter(function(r){ return r >= 0; });
            return rates.map(function(rate) {
                var r = rate / 100 / 12;
                var PI;
                if (r === 0) {
                    PI = v.loanAmount / v.n;
                } else {
                    var pRN = Math.pow(1 + r, v.n);
                    PI = v.loanAmount * r * pRN / (pRN - 1);
                }
                var totalInt = PI * v.n - v.loanAmount;
                return {
                    rate: rate,
                    monthlyPI: PI,
                    totalInterest: totalInt,
                    totalCost: v.loanAmount + totalInt,
                    isCurrent: Math.abs(rate - v.rate) < 0.001,
                };
            });
        },

        /* ── Payoff date ── */
        _payoffDate(startMonth, startYear, months) {
            var d = new Date(startYear, startMonth - 1 + months, 1);
            return d.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        },

        /* ── Format helpers ── */
        fmtC(v) {
            if (v === null || v === undefined || isNaN(v) || !isFinite(v)) return '—';
            var abs = Math.abs(v);
            var s;
            if (abs >= 1e9)       s = (v/1e9).toFixed(2) + 'B';
            else if (abs >= 1e6)  s = (v/1e6).toFixed(2) + 'M';
            else                  s = v.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            return this.currency + s;
        },
        fmtNum(v) {
            if (!v || isNaN(v)) return '0';
            return Math.round(v).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        },
        fmtPct(v) {
            if (isNaN(v) || !isFinite(v)) return '—';
            return parseFloat(v.toFixed(2)) + '%';
        },
        fmtMonths(m) {
            if (!m || m <= 0) return '—';
            var y = Math.floor(m / 12), mo = m % 12;
            if (y === 0) return mo + ' month' + (mo !== 1 ? 's' : '');
            if (mo === 0) return y + ' year' + (y !== 1 ? 's' : '');
            return y + ' yr ' + mo + ' mo';
        },

        /* ── Sample ── */
        loadSample() {
            this.currency = '$'; this.homePrice = '400000';
            this.downPayment = '80000'; this.downPct = '20'; this.dpMode = '$';
            this.interestRate = '7'; this.termYears = '30'; this.useCustomTerm = false;
            this.propTaxYr = '4800'; this.propTaxMode = '$';
            this.insYr = '1200'; this.insMode = '$';
            this.pmiRate = '0.5'; this.hoaMonthly = '250'; this.extraPayment = '0';
            var now = new Date();
            this.startMonth = now.getMonth() + 1; this.startYear = now.getFullYear();
            this.error = ''; this.result = null; this.phase = 'idle';
            var self = this;
            this.$nextTick(function(){ self.calculate(); });
        },

        clearAll() {
            this.error = ''; this.result = null; this.phase = 'idle'; this.showAmort = false;
        },

        /* ── Summary text ── */
        _buildSummary() {
            if (!this.result) return '';
            var r = this.result;
            var lines = [
                'Mortgage Calculator Results',
                '===========================',
                'Home Price       : ' + this.fmtC(r.homePrice),
                'Down Payment     : ' + this.fmtC(r.downPayment) + ' (' + this.fmtPct(r.downPayment/r.homePrice*100) + ')',
                'Loan Amount      : ' + this.fmtC(r.loanAmount),
                'Interest Rate    : ' + this.interestRate + '%',
                'Loan Term        : ' + r.effectiveTerm + ' years',
                'Payoff Date      : ' + r.payoffDate,
                '',
                '── Monthly Payment ──',
                'Principal & Int  : ' + this.fmtC(r.monthlyPI),
                'Property Tax     : ' + this.fmtC(r.monthlyTax),
                'Home Insurance   : ' + this.fmtC(r.monthlyIns),
                'PMI              : ' + this.fmtC(r.monthlyPMI),
                'HOA              : ' + this.fmtC(r.monthlyHOA),
                'TOTAL MONTHLY    : ' + this.fmtC(r.totalMonthly),
                '',
                '── Total Over Loan Life ──',
                'Total Principal  : ' + this.fmtC(r.loanAmount),
                'Total Interest   : ' + this.fmtC(r.totalInterest),
                'Total Tax+Ins    : ' + this.fmtC(r.totalTax + r.totalIns),
                'Grand Total      : ' + this.fmtC(r.grandTotal),
            ];
            if (r.extraMonthsSaved > 0) {
                lines.push('', '── Extra Payment Impact ──',
                    'Time Saved       : ' + this.fmtMonths(r.extraMonthsSaved),
                    'Interest Saved   : ' + this.fmtC(r.extraInterestSaved));
            }
            return lines.join('\n');
        },

        async copySummary() {
            var text = this._buildSummary();
            if (!text) return;
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
            a.href = url; a.download = 'mortgage-results.txt';
            document.body.appendChild(a); a.click();
            document.body.removeChild(a); URL.revokeObjectURL(url);
        },
    };
}
</script>
@endpush
