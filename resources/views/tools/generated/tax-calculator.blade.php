@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')
<style>
/* ══════════════════════════════════════════════
   Tax Calculator  —  prefix: tx-
══════════════════════════════════════════════ */
.tx-tab { padding:.5rem 1rem; border-bottom:2.5px solid transparent; font-size:.78rem; font-weight:600; color:#64748b; background:transparent; border-top:none; border-left:none; border-right:none; cursor:pointer; transition:color .12s,border-color .12s; white-space:nowrap; }
.tx-tab:hover { color:#d97706; }
.tx-tab-active { color:#d97706; border-bottom-color:#d97706; }

.tx-hero { font-size:clamp(2.4rem,5vw,3.8rem); font-weight:900; line-height:1; letter-spacing:-.03em; word-break:break-all; background:linear-gradient(135deg,#d97706 0%,#f59e0b 60%,#fbbf24 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }

.tx-stat { background:#fff; border:1.5px solid #e2e8f0; border-radius:1.125rem; padding:1rem .9rem; display:flex; flex-direction:column; align-items:center; gap:.3rem; text-align:center; transition:all .15s; }
.tx-stat:hover { border-color:#fde68a; box-shadow:0 4px 16px rgba(217,119,6,.07); transform:translateY(-1px); }
.tx-stat-lbl { font-size:.62rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.07em; }
.tx-stat-val { font-size:1.3rem; font-weight:800; word-break:break-all; }
.tx-stat-val.amber  { background:linear-gradient(135deg,#d97706,#f59e0b); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.tx-stat-val.rose   { background:linear-gradient(135deg,#e11d48,#f43f5e); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.tx-stat-val.green  { background:linear-gradient(135deg,#059669,#10b981); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.tx-stat-val.brand  { background:linear-gradient(135deg,#4f46e5,#7c3aed); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.tx-stat-sub { font-size:.65rem; color:#94a3b8; }

/* Breakdown bar */
.tx-bar-track { height:18px; border-radius:9999px; overflow:hidden; display:flex; background:#f1f5f9; }
.tx-bar-deduc  { background:#94a3b8; }
.tx-bar-tax    { background:#f43f5e; }
.tx-bar-addl   { background:#f97316; }
.tx-bar-home   { background:#10b981; }
.tx-legend-dot { width:10px; height:10px; border-radius:50%; flex-shrink:0; }

/* Bracket table */
.tx-bt { width:100%; border-collapse:collapse; font-size:.76rem; }
.tx-bt th { font-size:.62rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.07em; padding:.45rem .7rem; border-bottom:1.5px solid #e2e8f0; text-align:right; background:#f8fafc; }
.tx-bt th:first-child { text-align:center; }
.tx-bt td { padding:.45rem .7rem; border-bottom:1px solid #f1f5f9; text-align:right; font-variant-numeric:tabular-nums; }
.tx-bt td:first-child { text-align:center; font-weight:700; }
.tx-bt tr:last-child td { border-bottom:none; }
.tx-bt .tx-bt-active { background:#fffbeb; }
.tx-bt .tx-bt-marginal { background:#fef3c7; font-weight:700; }
.tx-bt .tx-bt-inactive { color:#d1d5db; }
.tx-bt-bar { height:6px; border-radius:9999px; min-width:2px; }

/* Bracket editor (custom mode) */
.tx-be-row { display:grid; grid-template-columns:1fr 1fr 1fr auto; gap:.5rem; align-items:center; }

/* Input helpers */
.tx-pre-wrap { display:flex; align-items:stretch; }
.tx-pre { display:flex; align-items:center; padding:0 .75rem; background:#f8fafc; border:1px solid #d1d5db; border-right:none; border-radius:.75rem 0 0 .75rem; font-size:.82rem; font-weight:700; color:#374151; white-space:nowrap; }
.tx-pre-wrap .form-input { border-radius:0 .75rem .75rem 0 !important; flex:1; min-width:0; }
.tx-suf-wrap { display:flex; align-items:stretch; }
.tx-suf-wrap .form-input { border-radius:.75rem 0 0 .75rem !important; flex:1; border-right:none; min-width:0; }
.tx-suf { display:flex; align-items:center; padding:0 .75rem; background:#f8fafc; border:1px solid #d1d5db; border-left:none; border-radius:0 .75rem .75rem 0; font-size:.78rem; font-weight:600; color:#64748b; }

/* Toggle group */
.tx-toggle { display:flex; background:#f1f5f9; border-radius:.5rem; padding:.15rem; gap:.1rem; }
.tx-toggle-btn { flex:1; padding:.28rem .6rem; border-radius:.35rem; font-size:.72rem; font-weight:600; color:#64748b; cursor:pointer; border:none; background:none; transition:all .15s; white-space:nowrap; }
.tx-toggle-btn.active { background:white; color:#d97706; box-shadow:0 1px 3px rgba(0,0,0,.1); }

/* Section divider */
.tx-div { display:flex; align-items:center; gap:.6rem; color:#94a3b8; font-size:.62rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; }
.tx-div::before,.tx-div::after { content:''; flex:1; height:1px; background:#f1f5f9; }

/* What-if card */
.tx-wi { padding:.85rem .75rem; border-radius:.9rem; border:1.5px solid #e2e8f0; text-align:center; background:#fff; transition:all .15s; }
.tx-wi:hover { border-color:#fde68a; }
.tx-wi.current { border-color:#f59e0b; background:#fffbeb; }
.tx-wi-val { font-size:1.1rem; font-weight:900; color:#d97706; }
.tx-wi-sub { font-size:.63rem; color:#6b7280; margin-top:.15rem; }

/* Monthly row */
.tx-mrow { display:flex; justify-content:space-between; align-items:center; padding:.5rem .75rem; border-radius:.5rem; }
.tx-mrow:hover { background:#f8fafc; }

/* Shimmer */
@keyframes txShim { 0%{background-position:-600px 0} 100%{background-position:600px 0} }
.tx-shim { height:5.5rem; border-radius:1.125rem; background:linear-gradient(90deg,#fffbeb 25%,#fef3c7 50%,#fffbeb 75%); background-size:1200px 100%; animation:txShim 1.4s infinite; }

@keyframes txIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
.tx-in { animation:txIn .3s ease-out; }

.tx-addl-row { display:flex; justify-content:space-between; align-items:center; padding:.45rem .75rem; border-radius:.5rem; font-size:.82rem; }
.tx-addl-row:hover { background:#fff7ed; }
</style>

<div class="min-h-screen bg-gray-50" x-data="taxCalc()" x-init="init()">

    <div class="bg-white border-b border-gray-100">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-8 md:py-10">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">{{ $tool->icon }} {{ $tool->name }}</h1>
                    <p class="text-gray-500 mt-2 max-w-2xl">Calculate <strong>income tax</strong> using 2024 brackets for the US, UK, Canada, Australia &amp; India — or use <strong>Sales/VAT</strong> mode for purchase tax, or build your own with <strong>Custom Brackets</strong>.</p>
                </div>
                <div class="flex flex-wrap gap-2 shrink-0">
                    <span class="badge badge-primary">Free</span>
                    <span class="badge badge-gray">2024 Rates</span>
                    <span class="badge badge-success">Multi-Country</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-6 md:py-8">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 items-start">

            {{-- LEFT --}}
            <div class="lg:col-span-2">
                <div class="card">
                    <div class="flex border-b border-gray-100 overflow-x-auto">
                        <button type="button" class="tx-tab" :class="{'tx-tab-active':mode==='income'}"  @click="switchMode('income')">🏦 Income Tax</button>
                        <button type="button" class="tx-tab" :class="{'tx-tab-active':mode==='sales'}"   @click="switchMode('sales')">🛒 Sales / VAT</button>
                        <button type="button" class="tx-tab" :class="{'tx-tab-active':mode==='custom'}"  @click="switchMode('custom')">⚙ Custom</button>
                    </div>

                    <div class="p-5 space-y-4">

                        {{-- ── INCOME TAX ── --}}
                        <div x-show="mode==='income'" class="space-y-4">

                            <div>
                                <label class="form-label">Country / Tax System</label>
                                <select x-model="country" @change="onCountryChange()" class="form-input text-sm">
                                    <option value="us">🇺🇸 United States (Federal 2024)</option>
                                    <option value="uk">🇬🇧 United Kingdom (2024/25)</option>
                                    <option value="ca">🇨🇦 Canada — Federal (2024)</option>
                                    <option value="au">🇦🇺 Australia (2024-25)</option>
                                    <option value="in_new">🇮🇳 India — New Regime (2024-25)</option>
                                    <option value="in_old">🇮🇳 India — Old Regime (2024-25)</option>
                                </select>
                            </div>

                            <div x-show="currentSystem && currentSystem.statuses && currentSystem.statuses.length > 1">
                                <label class="form-label">Filing Status</label>
                                <select x-model="filingStatus" @change="autoCalc()" class="form-input text-sm">
                                    <template x-for="s in currentSystem ? currentSystem.statuses : []" :key="s.value">
                                        <option :value="s.value" x-text="s.label"></option>
                                    </template>
                                </select>
                            </div>

                            <div>
                                <label class="form-label">Annual Gross Income</label>
                                <div class="tx-pre-wrap">
                                    <span class="tx-pre" x-text="currentSystem ? currentSystem.currency : '$'"></span>
                                    <input type="number" step="any" min="0" x-model="grossIncome"
                                           @input.debounce.350ms="autoCalc()"
                                           class="form-input" placeholder="e.g. 75000">
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Deductions</label>
                                <div class="tx-toggle">
                                    <button type="button" class="tx-toggle-btn" :class="{active:deductionMode==='standard'}" @click="deductionMode='standard'; autoCalc()">Standard</button>
                                    <button type="button" class="tx-toggle-btn" :class="{active:deductionMode==='itemized'}" @click="deductionMode='itemized'; autoCalc()">Itemized</button>
                                    <button type="button" class="tx-toggle-btn" :class="{active:deductionMode==='none'}"     @click="deductionMode='none';     autoCalc()">None</button>
                                </div>
                                <p class="form-help" x-show="deductionMode==='standard'" x-text="currentSystem && currentSystem.stdDed(filingStatus) > 0 ? 'Standard deduction: ' + fmtC(currentSystem.stdDed(filingStatus), currentSystem.currency) : 'No standard deduction for this system.'"></p>
                            </div>

                            <div x-show="deductionMode==='itemized'" x-transition>
                                <label class="form-label">Itemized Deduction Amount</label>
                                <div class="tx-pre-wrap">
                                    <span class="tx-pre" x-text="currentSystem ? currentSystem.currency : '$'"></span>
                                    <input type="number" step="any" min="0" x-model="itemizedDeduction"
                                           @input.debounce.350ms="autoCalc()"
                                           class="form-input" placeholder="e.g. 20000">
                                </div>
                            </div>

                            <div class="tx-div pt-1">Additional Taxes <span class="font-normal normal-case text-gray-400 tracking-normal">(optional)</span></div>

                            {{-- US FICA --}}
                            <div x-show="country==='us'" class="flex items-center justify-between py-1">
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Include FICA (Social Security + Medicare)</p>
                                    <p class="text-xs text-gray-400">6.2% SS + 1.45% Medicare on wage income</p>
                                </div>
                                <button type="button" class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 transition-colors duration-200"
                                        :class="includeFICA ? 'bg-amber-500 border-amber-500' : 'bg-gray-200 border-gray-200'"
                                        @click="includeFICA=!includeFICA; autoCalc()">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition duration-200"
                                          :class="includeFICA ? 'translate-x-4' : 'translate-x-0'"></span>
                                </button>
                            </div>

                            {{-- UK NI --}}
                            <div x-show="country==='uk'" class="flex items-center justify-between py-1">
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Include National Insurance</p>
                                    <p class="text-xs text-gray-400">Class 1: 8% (£12,570–£50,270), 2% above</p>
                                </div>
                                <button type="button" class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 transition-colors duration-200"
                                        :class="includeNI ? 'bg-amber-500 border-amber-500' : 'bg-gray-200 border-gray-200'"
                                        @click="includeNI=!includeNI; autoCalc()">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition duration-200"
                                          :class="includeNI ? 'translate-x-4' : 'translate-x-0'"></span>
                                </button>
                            </div>

                            {{-- AU Medicare --}}
                            <div x-show="country==='au'" class="flex items-center justify-between py-1">
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Include Medicare Levy</p>
                                    <p class="text-xs text-gray-400">2% of taxable income</p>
                                </div>
                                <button type="button" class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 transition-colors duration-200"
                                        :class="includeMedicare ? 'bg-amber-500 border-amber-500' : 'bg-gray-200 border-gray-200'"
                                        @click="includeMedicare=!includeMedicare; autoCalc()">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition duration-200"
                                          :class="includeMedicare ? 'translate-x-4' : 'translate-x-0'"></span>
                                </button>
                            </div>

                            {{-- IN Cess --}}
                            <div x-show="country==='in_new' || country==='in_old'" class="flex items-center justify-between py-1">
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Include Health &amp; Education Cess</p>
                                    <p class="text-xs text-gray-400">4% on income tax amount</p>
                                </div>
                                <button type="button" class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 transition-colors duration-200"
                                        :class="includeCess ? 'bg-amber-500 border-amber-500' : 'bg-gray-200 border-gray-200'"
                                        @click="includeCess=!includeCess; autoCalc()">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition duration-200"
                                          :class="includeCess ? 'translate-x-4' : 'translate-x-0'"></span>
                                </button>
                            </div>

                        </div>{{-- /income --}}

                        {{-- ── SALES / VAT ── --}}
                        <div x-show="mode==='sales'" class="space-y-4">
                            <div class="p-3 bg-amber-50 border border-amber-100 rounded-xl text-xs text-amber-800">
                                💡 Calculate sales tax, VAT, or GST on a purchase amount.
                            </div>
                            <div>
                                <label class="form-label">Currency &amp; Amount</label>
                                <div class="flex gap-2">
                                    <select x-model="saleCurrency" class="form-input w-20 py-2 text-sm shrink-0">
                                        <option value="$">$</option><option value="€">€</option>
                                        <option value="£">£</option><option value="¥">¥</option>
                                        <option value="₹">₹</option><option value="A$">A$</option>
                                        <option value="C$">C$</option>
                                    </select>
                                    <div class="tx-pre-wrap flex-1">
                                        <span class="tx-pre" x-text="saleCurrency"></span>
                                        <input type="number" step="any" min="0" x-model="salePrice"
                                               @input.debounce.350ms="autoCalc()"
                                               class="form-input" placeholder="e.g. 250.00">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Tax / VAT / GST Rate</label>
                                <div class="tx-suf-wrap">
                                    <input type="number" step="any" min="0" max="100" x-model="saleTaxRate"
                                           @input.debounce.350ms="autoCalc()"
                                           class="form-input" placeholder="e.g. 8.5">
                                    <span class="tx-suf">%</span>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Price entered is…</label>
                                <div class="tx-toggle">
                                    <button type="button" class="tx-toggle-btn" :class="{active:saleIncludes==='excludes'}" @click="saleIncludes='excludes'; autoCalc()">Excl. Tax</button>
                                    <button type="button" class="tx-toggle-btn" :class="{active:saleIncludes==='includes'}" @click="saleIncludes='includes'; autoCalc()">Incl. Tax</button>
                                </div>
                            </div>
                            <div x-show="saleIncludes==='excludes'" class="text-xs text-gray-400 bg-gray-50 rounded-xl px-3 py-2">
                                Tax calculated on top of the entered price: Total = Price + Tax.
                            </div>
                            <div x-show="saleIncludes==='includes'" class="text-xs text-gray-400 bg-gray-50 rounded-xl px-3 py-2">
                                Tax is already inside the price. Pre-tax = Price ÷ (1 + rate).
                            </div>
                        </div>{{-- /sales --}}

                        {{-- ── CUSTOM BRACKETS ── --}}
                        <div x-show="mode==='custom'" class="space-y-4">
                            <div class="p-3 bg-indigo-50 border border-indigo-100 rounded-xl text-xs text-indigo-800">
                                💡 Define your own progressive tax brackets. Enter the minimum income and rate for each bracket.
                            </div>
                            <div>
                                <label class="form-label">Currency &amp; Annual Income</label>
                                <div class="flex gap-2">
                                    <select x-model="customCurrency" class="form-input w-20 py-2 text-sm shrink-0">
                                        <option value="$">$</option><option value="€">€</option>
                                        <option value="£">£</option><option value="₹">₹</option>
                                        <option value="A$">A$</option><option value="C$">C$</option>
                                    </select>
                                    <div class="tx-pre-wrap flex-1">
                                        <span class="tx-pre" x-text="customCurrency"></span>
                                        <input type="number" step="any" min="0" x-model="customIncome"
                                               @input.debounce.350ms="autoCalc()"
                                               class="form-input" placeholder="e.g. 80000">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Standard Deduction <span class="text-gray-400 font-normal">(optional)</span></label>
                                <div class="tx-pre-wrap">
                                    <span class="tx-pre" x-text="customCurrency"></span>
                                    <input type="number" step="any" min="0" x-model="customDeduction"
                                           @input.debounce.350ms="autoCalc()"
                                           class="form-input" placeholder="0">
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Tax Brackets</label>
                                <div class="space-y-2">
                                    <div class="grid grid-cols-3 gap-1.5 text-center text-xs font-bold text-gray-400 uppercase tracking-wide px-1">
                                        <span>Rate (%)</span><span>From</span><span>To (blank = top)</span>
                                    </div>
                                    <template x-for="(br, idx) in customBrackets" :key="idx">
                                        <div class="flex items-center gap-1.5">
                                            <input type="number" step="any" min="0" max="100" x-model="br.rate"
                                                   @input.debounce.350ms="autoCalc()"
                                                   class="form-input text-center text-sm" placeholder="%">
                                            <input type="number" step="any" min="0" x-model="br.min"
                                                   @input.debounce.350ms="autoCalc()"
                                                   class="form-input text-right text-sm" placeholder="0">
                                            <input type="number" step="any" min="0" x-model="br.max"
                                                   @input.debounce.350ms="autoCalc()"
                                                   class="form-input text-right text-sm" placeholder="top">
                                            <button type="button" @click="removeBracket(idx)"
                                                    x-show="customBrackets.length > 1"
                                                    class="text-red-400 hover:text-red-600 text-lg leading-none flex-shrink-0 w-6 text-center">×</button>
                                        </div>
                                    </template>
                                    <button type="button" @click="addBracket()" class="btn btn-secondary btn-sm w-full">
                                        + Add Bracket
                                    </button>
                                </div>
                            </div>
                        </div>{{-- /custom --}}

                        {{-- Error --}}
                        <div x-show="error" x-transition class="flex items-start gap-2 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span x-text="error"></span>
                        </div>

                        <div class="flex flex-wrap gap-2 pt-1">
                            <button type="button" @click="calculate()" class="btn btn-primary flex-1 sm:flex-none btn-lg" style="background:#d97706;border-color:#d97706">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Calculate Tax
                            </button>
                            <button type="button" @click="loadSample()" class="btn btn-secondary">📋 Sample</button>
                            <button type="button" @click="clearAll()" x-show="phase==='done' || error" class="btn btn-secondary">✕ Clear</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT --}}
            <div class="lg:col-span-3 space-y-4" id="tx-results">

                {{-- Shimmer --}}
                <div x-show="phase==='loading'" class="grid grid-cols-2 gap-3">
                    <template x-for="i in 4" :key="i"><div class="tx-shim"></div></template>
                </div>

                {{-- ══ RESULTS ══ --}}
                <template x-if="phase==='done' && result">
                    <div class="space-y-4 tx-in">

                        {{-- Hero --}}
                        <div class="card overflow-hidden">
                            <div style="background:linear-gradient(135deg,#fffbeb 0%,#fef3c7 100%);" class="px-6 py-5">
                                <p class="text-xs font-bold text-amber-500 uppercase tracking-widest mb-1"
                                   x-text="mode==='sales' ? 'Tax Amount' : 'Total Tax Owed'"></p>
                                <p class="tx-hero" x-text="fmtC(result.totalTax, result.currency)"></p>
                                <p class="text-xs text-gray-500 mt-2">
                                    <span x-show="mode==='income' || mode==='custom'">
                                        Effective rate: <strong x-text="result.effectiveRate.toFixed(2) + '%'"></strong>
                                        &nbsp;·&nbsp; Marginal bracket: <strong x-text="result.marginalRate + '%'"></strong>
                                    </span>
                                    <span x-show="mode==='sales'">
                                        <strong x-text="result.saleTaxRate + '% tax'"></strong>
                                        on <strong x-text="fmtC(result.preTax, result.currency)"></strong>
                                    </span>
                                </p>
                            </div>
                        </div>

                        {{-- ── INCOME / CUSTOM results ── --}}
                        <template x-if="mode==='income' || mode==='custom'">
                            <div class="space-y-4">

                                {{-- Stat cards --}}
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    <div class="tx-stat">
                                        <div class="tx-stat-lbl">Gross Income</div>
                                        <div class="tx-stat-val brand" x-text="fmtC(result.grossIncome, result.currency)"></div>
                                        <div class="tx-stat-sub">before tax</div>
                                    </div>
                                    <div class="tx-stat">
                                        <div class="tx-stat-lbl">Effective Rate</div>
                                        <div class="tx-stat-val amber" x-text="result.effectiveRate.toFixed(2) + '%'"></div>
                                        <div class="tx-stat-sub">of gross income</div>
                                    </div>
                                    <div class="tx-stat">
                                        <div class="tx-stat-lbl">Marginal Rate</div>
                                        <div class="tx-stat-val rose" x-text="result.marginalRate + '%'"></div>
                                        <div class="tx-stat-sub">top bracket</div>
                                    </div>
                                    <div class="tx-stat">
                                        <div class="tx-stat-lbl">Take-Home</div>
                                        <div class="tx-stat-val green" x-text="fmtC(result.takeHome, result.currency)"></div>
                                        <div class="tx-stat-sub">per year</div>
                                    </div>
                                </div>

                                {{-- Income breakdown bar --}}
                                <div class="card p-4 space-y-3">
                                    <p class="text-sm font-semibold text-gray-700">Income Breakdown</p>
                                    <div class="tx-bar-track">
                                        <div class="tx-bar-deduc transition-all duration-500" :style="'width:'+result.pctDeduction+'%'" x-show="result.deduction > 0"></div>
                                        <div class="tx-bar-tax   transition-all duration-500" :style="'width:'+result.pctIncomeTax+'%'"></div>
                                        <div class="tx-bar-addl  transition-all duration-500" :style="'width:'+result.pctAddlTax+'%'"  x-show="result.additionalTax > 0"></div>
                                        <div class="tx-bar-home  transition-all duration-500" :style="'width:'+result.pctTakeHome+'%'"></div>
                                    </div>
                                    <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs">
                                        <span class="flex items-center gap-1.5" x-show="result.deduction > 0">
                                            <span class="tx-legend-dot" style="background:#94a3b8"></span>
                                            <span class="text-gray-500">Deduction <strong x-text="fmtC(result.deduction, result.currency)"></strong></span>
                                        </span>
                                        <span class="flex items-center gap-1.5">
                                            <span class="tx-legend-dot" style="background:#f43f5e"></span>
                                            <span class="text-gray-500">Income Tax <strong x-text="fmtC(result.incomeTax, result.currency)"></strong></span>
                                        </span>
                                        <span class="flex items-center gap-1.5" x-show="result.additionalTax > 0">
                                            <span class="tx-legend-dot" style="background:#f97316"></span>
                                            <span class="text-gray-500">Add'l Tax <strong x-text="fmtC(result.additionalTax, result.currency)"></strong></span>
                                        </span>
                                        <span class="flex items-center gap-1.5">
                                            <span class="tx-legend-dot" style="background:#10b981"></span>
                                            <span class="text-gray-500">Take-Home <strong x-text="fmtC(result.takeHome, result.currency)"></strong></span>
                                        </span>
                                    </div>
                                </div>

                                {{-- Tax Calculation Details --}}
                                <div class="card p-4 space-y-1">
                                    <p class="text-sm font-semibold text-gray-700 mb-2">Tax Calculation Details</p>
                                    <div class="tx-mrow"><span class="text-sm text-gray-600">Gross Income</span><span class="font-semibold text-gray-800" x-text="fmtC(result.grossIncome, result.currency)"></span></div>
                                    <div class="tx-mrow" x-show="result.deduction > 0"><span class="text-sm text-gray-600">Standard/Itemized Deduction</span><span class="font-semibold text-red-500" x-text="'− ' + fmtC(result.deduction, result.currency)"></span></div>
                                    <div class="tx-mrow border-t border-gray-100 mt-1 pt-1"><span class="text-sm font-semibold text-gray-700">Taxable Income</span><span class="font-bold text-gray-900" x-text="fmtC(result.taxableIncome, result.currency)"></span></div>
                                    <div class="tx-mrow"><span class="text-sm text-gray-600">Income Tax</span><span class="font-semibold text-red-500" x-text="fmtC(result.incomeTax, result.currency)"></span></div>
                                    <template x-if="result.additionalTaxRows && result.additionalTaxRows.length > 0">
                                        <div>
                                            <template x-for="row in result.additionalTaxRows" :key="row.label">
                                                <div class="tx-mrow"><span class="text-sm text-gray-600" x-text="row.label"></span><span class="font-semibold text-orange-500" x-text="fmtC(row.amount, result.currency)"></span></div>
                                            </template>
                                        </div>
                                    </template>
                                    <div class="tx-mrow border-t border-gray-100 mt-1 pt-1"><span class="text-sm font-bold text-gray-800">Total Tax</span><span class="font-black text-amber-600" x-text="fmtC(result.totalTax, result.currency)"></span></div>
                                    <div class="tx-mrow"><span class="text-sm font-bold text-gray-800">Take-Home Pay</span><span class="font-black text-emerald-600" x-text="fmtC(result.takeHome, result.currency)"></span></div>
                                </div>

                                {{-- Bracket Table --}}
                                <div class="card overflow-hidden">
                                    <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100 bg-gray-50">
                                        <span class="text-sm font-semibold text-gray-700">Tax Bracket Breakdown</span>
                                        <button type="button" @click="showBracketTable=!showBracketTable" class="btn btn-secondary btn-sm flex items-center gap-1">
                                            <span x-text="showBracketTable ? 'Hide' : 'Show'"></span>
                                            <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{'-rotate-180': showBracketTable}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                    </div>
                                    <div x-show="showBracketTable" x-transition>
                                        <table class="tx-bt">
                                            <thead><tr>
                                                <th>Rate</th><th>Income Range</th><th>Taxed Amount</th><th>Tax</th><th style="width:80px"></th>
                                            </tr></thead>
                                            <tbody>
                                                <template x-for="br in result.bracketRows" :key="br.rate + '-' + br.min">
                                                    <tr :class="{'tx-bt-marginal': br.isMarginal, 'tx-bt-active': br.isActive && !br.isMarginal, 'tx-bt-inactive': !br.isActive}">
                                                        <td x-text="br.rate + '%'"></td>
                                                        <td x-text="fmtC(br.min, result.currency) + (br.max ? ' – ' + fmtC(br.max, result.currency) : '+')"></td>
                                                        <td x-text="br.isActive ? fmtC(br.taxableAmt, result.currency) : '—'"></td>
                                                        <td x-text="br.isActive ? fmtC(br.taxAmt, result.currency) : '—'"></td>
                                                        <td>
                                                            <div class="tx-bt-bar" :style="'width:' + (br.isActive ? Math.max(4, br.barPct) : 0) + '%;background:' + br.barColor"></div>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                {{-- Monthly breakdown --}}
                                <div class="card p-4">
                                    <p class="text-sm font-semibold text-gray-700 mb-3">Monthly Breakdown</p>
                                    <div class="grid grid-cols-3 gap-3">
                                        <div class="tx-stat">
                                            <div class="tx-stat-lbl">Monthly Gross</div>
                                            <div class="tx-stat-val brand" style="font-size:1.1rem" x-text="fmtC(result.grossIncome/12, result.currency)"></div>
                                        </div>
                                        <div class="tx-stat">
                                            <div class="tx-stat-lbl">Monthly Tax</div>
                                            <div class="tx-stat-val rose" style="font-size:1.1rem" x-text="fmtC(result.totalTax/12, result.currency)"></div>
                                        </div>
                                        <div class="tx-stat">
                                            <div class="tx-stat-lbl">Monthly Take-Home</div>
                                            <div class="tx-stat-val green" style="font-size:1.1rem" x-text="fmtC(result.takeHome/12, result.currency)"></div>
                                        </div>
                                    </div>
                                </div>

                                {{-- What-if --}}
                                <div class="card p-4">
                                    <p class="text-sm font-semibold text-gray-700 mb-3">💡 What If Your Income Were Different?</p>
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                        <template x-for="wi in result.whatIf" :key="wi.pct">
                                            <div class="tx-wi" :class="{current: wi.isCurrent}">
                                                <div class="tx-wi-val" x-text="fmtC(wi.income, result.currency)"></div>
                                                <div style="font-size:.75rem;font-weight:700;color:#f43f5e;margin-top:.2rem" x-text="'Tax: ' + fmtC(wi.tax, result.currency)"></div>
                                                <div class="tx-wi-sub">
                                                    <div x-text="wi.rate.toFixed(1) + '% eff. rate'"></div>
                                                    <div x-text="wi.isCurrent ? 'current' : (wi.pct > 100 ? '+' : '') + (wi.pct - 100) + '%'"></div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                            </div>
                        </template>

                        {{-- ── SALES results ── --}}
                        <template x-if="mode==='sales'">
                            <div class="space-y-4">
                                <div class="grid grid-cols-3 gap-3">
                                    <div class="tx-stat">
                                        <div class="tx-stat-lbl">Pre-Tax Price</div>
                                        <div class="tx-stat-val brand" x-text="fmtC(result.preTax, result.currency)"></div>
                                    </div>
                                    <div class="tx-stat">
                                        <div class="tx-stat-lbl">Tax Amount</div>
                                        <div class="tx-stat-val rose" x-text="fmtC(result.totalTax, result.currency)"></div>
                                        <div class="tx-stat-sub" x-text="result.saleTaxRate + '%'"></div>
                                    </div>
                                    <div class="tx-stat">
                                        <div class="tx-stat-lbl">Total with Tax</div>
                                        <div class="tx-stat-val green" x-text="fmtC(result.totalWithTax, result.currency)"></div>
                                    </div>
                                </div>
                                <div class="card p-4 space-y-3">
                                    <p class="text-sm font-semibold text-gray-700">Breakdown</p>
                                    <div class="tx-bar-track">
                                        <div class="tx-bar-home  transition-all duration-500" :style="'width:'+result.preTaxPct+'%'"></div>
                                        <div class="tx-bar-tax   transition-all duration-500" :style="'width:'+result.taxPct+'%'"></div>
                                    </div>
                                    <div class="flex flex-wrap gap-4 text-xs">
                                        <span class="flex items-center gap-1.5"><span class="tx-legend-dot" style="background:#10b981"></span><span class="text-gray-500">Pre-Tax <strong x-text="fmtC(result.preTax,result.currency)"></strong> (<span x-text="result.preTaxPct.toFixed(1)+'%'"></span>)</span></span>
                                        <span class="flex items-center gap-1.5"><span class="tx-legend-dot" style="background:#f43f5e"></span><span class="text-gray-500">Tax <strong x-text="fmtC(result.totalTax,result.currency)"></strong> (<span x-text="result.taxPct.toFixed(1)+'%'"></span>)</span></span>
                                    </div>
                                </div>
                                <div class="card p-4 space-y-2">
                                    <p class="text-sm font-semibold text-gray-700 mb-1">Quick Reference</p>
                                    <template x-for="ref in result.saleRef" :key="ref.label">
                                        <div class="tx-mrow"><span class="text-sm text-gray-600" x-text="ref.label"></span><span class="font-bold text-gray-800" x-text="fmtC(ref.total, result.currency)"></span></div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        {{-- Export --}}
                        <div class="card p-4">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="text-sm font-medium text-gray-600">Export:</span>
                                <button type="button" @click="copySummary()" class="btn btn-secondary btn-sm"
                                        :class="summaryCopyFlash ? 'bg-amber-50 text-amber-700' : ''"
                                        x-text="summaryCopyFlash ? '✓ Copied!' : 'Copy Summary'"></button>
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
                            ['🏦','Income Tax','US, UK, Canada, Australia, India — 2024 brackets'],
                            ['🛒','Sales / VAT','Calculate tax on a purchase, include or exclude mode'],
                            ['⚙','Custom Brackets','Define your own progressive tax brackets'],
                            ['📊','Bracket Table','See exactly how much tax falls in each bracket'],
                            ['💡','What-If','Compare tax at different income levels'],
                            ['📥','Monthly View','Annual tax broken down to monthly amounts'],
                        ] as [$icon,$title,$desc])
                        <div class="card p-4 text-center hover:border-amber-200 transition-colors">
                            <p class="text-2xl mb-1.5">{{ $icon }}</p>
                            <p class="text-sm font-semibold text-gray-700">{{ $title }}</p>
                            <p class="text-xs text-gray-400 mt-1 leading-snug">{{ $desc }}</p>
                        </div>
                        @endforeach
                    </div>
                    <div class="card p-4" style="background:linear-gradient(135deg,#fffbeb,#fef3c7);border-color:#fde68a">
                        <p class="text-sm font-semibold text-amber-700 mb-2">📐 Income Tax Formulas</p>
                        <div class="space-y-1 text-xs text-gray-600 font-mono">
                            <p>Taxable Income  = Gross Income − Deductions</p>
                            <p>Tax             = Σ (rate × amount in each bracket)</p>
                            <p>Effective Rate  = Total Tax ÷ Gross Income × 100</p>
                            <p>Take-Home       = Gross Income − Total Tax</p>
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
function taxCalc() {
    return {
        /* ── Tax system data ── */
        _systems: {
            us: {
                name:'United States', currency:'$', year:'2024',
                statuses:[{value:'single',label:'Single'},{value:'mfj',label:'Married Filing Jointly'},{value:'mfs',label:'Married Filing Separately'},{value:'hoh',label:'Head of Household'}],
                stdDed: function(s){ return {single:14600,mfj:29200,mfs:14600,hoh:21900}[s]||14600; },
                brackets: {
                    single:[{r:10,min:0,max:11600},{r:12,min:11600,max:47150},{r:22,min:47150,max:100525},{r:24,min:100525,max:191950},{r:32,min:191950,max:243725},{r:35,min:243725,max:609350},{r:37,min:609350,max:null}],
                    mfj:   [{r:10,min:0,max:23200},{r:12,min:23200,max:94300},{r:22,min:94300,max:201050},{r:24,min:201050,max:383900},{r:32,min:383900,max:487450},{r:35,min:487450,max:731200},{r:37,min:731200,max:null}],
                    mfs:   [{r:10,min:0,max:11600},{r:12,min:11600,max:47150},{r:22,min:47150,max:100525},{r:24,min:100525,max:191950},{r:32,min:191950,max:243725},{r:35,min:243725,max:365600},{r:37,min:365600,max:null}],
                    hoh:   [{r:10,min:0,max:16550},{r:12,min:16550,max:63100},{r:22,min:63100,max:100500},{r:24,min:100500,max:191950},{r:32,min:191950,max:243700},{r:35,min:243700,max:609350},{r:37,min:609350,max:null}],
                },
            },
            uk: {
                name:'United Kingdom', currency:'£', year:'2024/25',
                statuses:[{value:'individual',label:'Individual'}],
                stdDed: function(){ return 0; }, // PA is in brackets as 0% band
                brackets: {
                    individual:[{r:0,min:0,max:12570},{r:20,min:12570,max:50270},{r:40,min:50270,max:125140},{r:45,min:125140,max:null}],
                },
            },
            ca: {
                name:'Canada (Federal)', currency:'C$', year:'2024',
                statuses:[{value:'individual',label:'Individual'}],
                stdDed: function(){ return 15705; },
                brackets: {
                    individual:[{r:15,min:0,max:55867},{r:20.5,min:55867,max:111733},{r:26,min:111733,max:154906},{r:29,min:154906,max:220000},{r:33,min:220000,max:null}],
                },
            },
            au: {
                name:'Australia', currency:'A$', year:'2024-25',
                statuses:[{value:'resident',label:'Australian Resident'}],
                stdDed: function(){ return 0; },
                brackets: {
                    resident:[{r:0,min:0,max:18200},{r:19,min:18200,max:45000},{r:32.5,min:45000,max:120000},{r:37,min:120000,max:180000},{r:45,min:180000,max:null}],
                },
            },
            in_new: {
                name:'India — New Regime', currency:'₹', year:'2024-25',
                statuses:[{value:'individual',label:'Individual'}],
                stdDed: function(){ return 75000; },
                brackets: {
                    individual:[{r:0,min:0,max:300000},{r:5,min:300000,max:700000},{r:10,min:700000,max:1000000},{r:15,min:1000000,max:1200000},{r:20,min:1200000,max:1500000},{r:30,min:1500000,max:null}],
                },
            },
            in_old: {
                name:'India — Old Regime', currency:'₹', year:'2024-25',
                statuses:[{value:'individual',label:'Individual (<60 yrs)'}],
                stdDed: function(){ return 50000; },
                brackets: {
                    individual:[{r:0,min:0,max:250000},{r:5,min:250000,max:500000},{r:20,min:500000,max:1000000},{r:30,min:1000000,max:null}],
                },
            },
        },

        /* ── Inputs ── */
        mode: 'income',
        country: 'us',
        filingStatus: 'single',
        grossIncome: '75000',
        deductionMode: 'standard',
        itemizedDeduction: '',
        includeFICA: false,
        includeNI: true,
        includeMedicare: true,
        includeCess: true,

        saleCurrency: '$',
        salePrice: '250',
        saleTaxRate: '8.5',
        saleIncludes: 'excludes',

        customCurrency: '$',
        customIncome: '80000',
        customDeduction: '0',
        customBrackets: [
            {rate:'10', min:'0',     max:'10000'},
            {rate:'20', min:'10000', max:'50000'},
            {rate:'30', min:'50000', max:''},
        ],

        phase: 'idle',
        error: '',
        result: null,
        showBracketTable: true,
        summaryCopyFlash: false,

        get currentSystem() {
            return this._systems[this.country] || null;
        },

        init() {
            var cs = this.currentSystem;
            if (cs && cs.statuses && cs.statuses.length > 0) {
                this.filingStatus = cs.statuses[0].value;
            }
        },

        switchMode(m) {
            this.mode = m; this.error = ''; this.result = null; this.phase = 'idle';
        },

        onCountryChange() {
            var cs = this.currentSystem;
            if (cs && cs.statuses && cs.statuses.length > 0) {
                this.filingStatus = cs.statuses[0].value;
            }
            this.autoCalc();
        },

        autoCalc() {
            var self = this;
            try {
                if (this.mode === 'income') {
                    if (isNaN(parseFloat(this.grossIncome)) || parseFloat(this.grossIncome) < 0) return;
                } else if (this.mode === 'sales') {
                    if (isNaN(parseFloat(this.salePrice)) || parseFloat(this.salePrice) < 0) return;
                    if (isNaN(parseFloat(this.saleTaxRate)) || parseFloat(this.saleTaxRate) < 0) return;
                } else {
                    if (isNaN(parseFloat(this.customIncome)) || parseFloat(this.customIncome) < 0) return;
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
                            var el = document.getElementById('tx-results');
                            if (el) setTimeout(function(){ el.scrollIntoView({behavior:'smooth',block:'start'}); }, 80);
                        }
                    } catch(e) {
                        self.error = String(e);
                        self.phase = 'idle';
                    }
                }, 120);
            } catch(e) {
                this.error = String(e); this.phase = 'idle';
            }
        },

        _validate() {
            var v = { mode: this.mode };
            if (this.mode === 'sales') {
                v.price = parseFloat(this.salePrice);
                v.rate  = parseFloat(this.saleTaxRate);
                v.incl  = this.saleIncludes === 'includes';
                v.currency = this.saleCurrency;
                if (isNaN(v.price) || v.price < 0) throw 'Enter a valid price (zero or positive).';
                if (isNaN(v.rate)  || v.rate < 0)  throw 'Enter a valid tax rate (zero or positive).';
                if (v.rate > 100) throw 'Tax rate cannot exceed 100%.';
            } else if (this.mode === 'custom') {
                v.income    = parseFloat(this.customIncome);
                v.deduction = parseFloat(this.customDeduction) || 0;
                v.currency  = this.customCurrency;
                if (isNaN(v.income) || v.income < 0) throw 'Enter a valid income amount.';
                if (v.deduction < 0) throw 'Deduction cannot be negative.';
                v.brackets = [];
                for (var i = 0; i < this.customBrackets.length; i++) {
                    var br = this.customBrackets[i];
                    var r = parseFloat(br.rate), mn = parseFloat(br.min);
                    if (isNaN(r) || r < 0 || r > 100) throw 'Bracket ' + (i+1) + ': rate must be 0–100%.';
                    if (isNaN(mn) || mn < 0)           throw 'Bracket ' + (i+1) + ': minimum must be zero or positive.';
                    var mx = br.max === '' || br.max === undefined ? null : parseFloat(br.max);
                    if (mx !== null && (isNaN(mx) || mx <= mn)) throw 'Bracket ' + (i+1) + ': upper limit must be greater than lower limit.';
                    v.brackets.push({ r: r, min: mn, max: mx });
                }
                if (v.brackets.length === 0) throw 'Add at least one tax bracket.';
            } else {
                v.grossIncome = parseFloat(this.grossIncome);
                v.country = this.country;
                v.status  = this.filingStatus;
                var sys = this.currentSystem;
                if (!sys) throw 'Select a valid country/tax system.';
                if (isNaN(v.grossIncome) || v.grossIncome < 0) throw 'Enter a valid gross income.';
                if (v.grossIncome > 1e10) throw 'Income value is too large.';
                v.currency = sys.currency;
                v.brackets = sys.brackets[v.status] || sys.brackets[Object.keys(sys.brackets)[0]];
                if (this.deductionMode === 'standard') {
                    v.deduction = sys.stdDed(v.status);
                } else if (this.deductionMode === 'itemized') {
                    v.deduction = parseFloat(this.itemizedDeduction) || 0;
                    if (v.deduction < 0) throw 'Itemized deduction cannot be negative.';
                } else {
                    v.deduction = 0;
                }
                v.includeFICA     = this.includeFICA     && v.country === 'us';
                v.includeNI       = this.includeNI       && v.country === 'uk';
                v.includeMedicare = this.includeMedicare && v.country === 'au';
                v.includeCess     = this.includeCess     && (v.country === 'in_new' || v.country === 'in_old');
            }
            return v;
        },

        /* ── Progressive tax core ── */
        _applyBrackets(taxableIncome, brackets) {
            var total = 0, marginal = 0;
            var rows = [];
            var maxTaxAmt = 0;
            for (var i = 0; i < brackets.length; i++) {
                var br = brackets[i];
                var upper = br.max !== null ? Math.min(taxableIncome, br.max) : taxableIncome;
                var isActive = taxableIncome > br.min;
                var amt = isActive ? Math.max(0, upper - br.min) : 0;
                var tax = amt * (br.r / 100);
                if (isActive) { marginal = br.r; maxTaxAmt = Math.max(maxTaxAmt, tax); }
                total += tax;
                rows.push({ rate: br.r, min: br.min, max: br.max, taxableAmt: amt, taxAmt: tax, isActive: isActive, isMarginal: false });
            }
            // Mark last active as marginal
            for (var j = rows.length - 1; j >= 0; j--) {
                if (rows[j].isActive) { rows[j].isMarginal = true; break; }
            }
            // Bar widths: relative to taxable income
            var maxAmt = taxableIncome > 0 ? taxableIncome : 1;
            var colors = ['#a3e635','#84cc16','#facc15','#fb923c','#f87171','#f43f5e','#be123c'];
            for (var k = 0; k < rows.length; k++) {
                rows[k].barPct   = (rows[k].taxableAmt / maxAmt) * 100;
                rows[k].barColor = colors[Math.min(k, colors.length - 1)];
            }
            return { total: total, marginal: marginal, rows: rows };
        },

        _compute(v) {
            if (v.mode === 'sales') {
                var preTax, tax, totalWithTax;
                if (v.incl) {
                    preTax       = v.price / (1 + v.rate / 100);
                    tax          = v.price - preTax;
                    totalWithTax = v.price;
                } else {
                    preTax       = v.price;
                    tax          = v.price * (v.rate / 100);
                    totalWithTax = v.price + tax;
                }
                var total = totalWithTax;
                var ref = [
                    {label:'× 2',  total: totalWithTax * 2},
                    {label:'× 5',  total: totalWithTax * 5},
                    {label:'× 10', total: totalWithTax * 10},
                    {label:'× 100',total: totalWithTax * 100},
                ];
                return {
                    totalTax: tax, currency: v.currency,
                    preTax: preTax, totalWithTax: totalWithTax,
                    saleTaxRate: v.rate,
                    preTaxPct: (preTax / totalWithTax) * 100,
                    taxPct:    (tax    / totalWithTax) * 100,
                    saleRef: ref,
                };
            }

            var grossIncome, deduction, brackets, currency;
            if (v.mode === 'custom') {
                grossIncome = v.income;
                deduction   = Math.min(v.deduction, grossIncome);
                brackets    = v.brackets;
                currency    = v.currency;
            } else {
                grossIncome = v.grossIncome;
                deduction   = Math.min(v.deduction, grossIncome);
                brackets    = v.brackets;
                currency    = v.currency;
            }

            var taxableIncome = Math.max(0, grossIncome - deduction);
            var br = this._applyBrackets(taxableIncome, brackets);
            var incomeTax    = br.total;
            var marginalRate = br.marginal;

            // Additional taxes
            var additionalTax = 0;
            var additionalTaxRows = [];
            if (v.includeFICA) {
                var ssCap = 168600;
                var ss       = Math.min(grossIncome, ssCap) * 0.062;
                var medicare = grossIncome * 0.0145 + (grossIncome > 200000 ? (grossIncome - 200000) * 0.009 : 0);
                additionalTaxRows.push({label:'Social Security (6.2%)', amount:ss});
                additionalTaxRows.push({label:'Medicare (1.45%+)', amount:medicare});
                additionalTax += ss + medicare;
            }
            if (v.includeNI) {
                var ni = 0;
                if (grossIncome > 50270) ni += (grossIncome - 50270) * 0.02;
                if (grossIncome > 12570) ni += (Math.min(grossIncome, 50270) - 12570) * 0.08;
                additionalTaxRows.push({label:'National Insurance', amount:ni});
                additionalTax += ni;
            }
            if (v.includeMedicare) {
                var medLevy = 0;
                if (grossIncome > 30345) {
                    medLevy = taxableIncome * 0.02;
                } else if (grossIncome > 24276) {
                    medLevy = (grossIncome - 24276) * 0.10;
                }
                additionalTaxRows.push({label:'Medicare Levy (2%)', amount:medLevy});
                additionalTax += medLevy;
            }
            if (v.includeCess) {
                var cess = incomeTax * 0.04;
                additionalTaxRows.push({label:'Health & Education Cess (4%)', amount:cess});
                additionalTax += cess;
            }

            var totalTax  = incomeTax + additionalTax;
            var takeHome  = grossIncome - totalTax;
            var effectiveRate = grossIncome > 0 ? (totalTax / grossIncome) * 100 : 0;

            // Stacked bar percentages
            var pctDeduction   = (deduction    / grossIncome) * 100;
            var pctIncomeTax   = (incomeTax    / grossIncome) * 100;
            var pctAddlTax     = (additionalTax/ grossIncome) * 100;
            var pctTakeHome    = Math.max(0, 100 - pctDeduction - pctIncomeTax - pctAddlTax);

            // What-if
            var self = this;
            var whatIf = [0.5, 0.75, 1, 1.25].map(function(mult) {
                var wi_income = grossIncome * mult;
                var wi_ded    = Math.min(deduction, wi_income);
                var wi_taxable = Math.max(0, wi_income - wi_ded);
                var wi_br  = self._applyBrackets(wi_taxable, brackets);
                var wi_rate = wi_income > 0 ? (wi_br.total / wi_income) * 100 : 0;
                return { pct: mult * 100, income: wi_income, tax: wi_br.total, rate: wi_rate, isCurrent: mult === 1 };
            });

            return {
                grossIncome: grossIncome, taxableIncome: taxableIncome,
                deduction: deduction, incomeTax: incomeTax,
                additionalTax: additionalTax, additionalTaxRows: additionalTaxRows,
                totalTax: totalTax, takeHome: takeHome,
                effectiveRate: effectiveRate, marginalRate: marginalRate,
                pctDeduction: pctDeduction, pctIncomeTax: pctIncomeTax,
                pctAddlTax: pctAddlTax, pctTakeHome: pctTakeHome,
                bracketRows: br.rows, whatIf: whatIf, currency: currency,
            };
        },

        addBracket()         { this.customBrackets.push({rate:'',min:'',max:''}); },
        removeBracket(idx)   { if (this.customBrackets.length > 1) this.customBrackets.splice(idx, 1); this.autoCalc(); },

        fmtC(v, cur) {
            if (v === null || isNaN(v) || !isFinite(v)) return '—';
            var neg = v < 0 ? '-' : '', abs = Math.abs(v), s;
            if (abs >= 1e9)      s = (abs/1e9).toFixed(2)+'B';
            else if (abs >= 1e6) s = (abs/1e6).toFixed(2)+'M';
            else if (abs >= 1e3) s = abs.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,',');
            else                 s = abs.toFixed(2);
            return neg + (cur || '$') + s;
        },

        loadSample() {
            if (this.mode === 'income') {
                this.country = 'us'; this.filingStatus = 'single';
                this.grossIncome = '85000'; this.deductionMode = 'standard';
                this.includeFICA = false;
                this.onCountryChange();
            } else if (this.mode === 'sales') {
                this.saleCurrency = '$'; this.salePrice = '349.99';
                this.saleTaxRate = '8.5'; this.saleIncludes = 'excludes';
            } else {
                this.customCurrency = '$'; this.customIncome = '90000';
                this.customDeduction = '14600';
                this.customBrackets = [
                    {rate:'10',min:'0',max:'11600'},
                    {rate:'12',min:'11600',max:'47150'},
                    {rate:'22',min:'47150',max:'100525'},
                    {rate:'24',min:'100525',max:''},
                ];
            }
            this.error = ''; this.result = null; this.phase = 'idle';
            var self = this; this.$nextTick(function(){ self.calculate(); });
        },

        clearAll() { this.error = ''; this.result = null; this.phase = 'idle'; },

        _buildSummary() {
            if (!this.result) return '';
            var r = this.result;
            if (this.mode === 'sales') {
                return ['Tax Calculator — Sales/VAT','=========================',
                    'Pre-Tax Price : ' + this.fmtC(r.preTax, r.currency),
                    'Tax (' + r.saleTaxRate + '%)    : ' + this.fmtC(r.totalTax, r.currency),
                    'Total with Tax: ' + this.fmtC(r.totalWithTax, r.currency),
                ].join('\n');
            }
            var lines = ['Tax Calculator — Income Tax','===========================',
                'Gross Income    : ' + this.fmtC(r.grossIncome, r.currency),
                'Deduction       : ' + this.fmtC(r.deduction, r.currency),
                'Taxable Income  : ' + this.fmtC(r.taxableIncome, r.currency),
                'Income Tax      : ' + this.fmtC(r.incomeTax, r.currency),
            ];
            if (r.additionalTaxRows && r.additionalTaxRows.length > 0) {
                r.additionalTaxRows.forEach(function(row){ lines.push(row.label + ': ' + this.fmtC(row.amount, r.currency)); }, this);
            }
            lines = lines.concat([
                'Total Tax       : ' + this.fmtC(r.totalTax, r.currency),
                'Effective Rate  : ' + r.effectiveRate.toFixed(2) + '%',
                'Marginal Rate   : ' + r.marginalRate + '%',
                'Take-Home (yr)  : ' + this.fmtC(r.takeHome, r.currency),
                'Take-Home (mo)  : ' + this.fmtC(r.takeHome/12, r.currency),
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
            var self = this; this.summaryCopyFlash = true;
            setTimeout(function(){ self.summaryCopyFlash = false; }, 1800);
        },

        downloadSummary() {
            var text = this._buildSummary(); if (!text) return;
            var blob = new Blob([text],{type:'text/plain;charset=utf-8'});
            var url = URL.createObjectURL(blob);
            var a = document.createElement('a'); a.href = url; a.download = 'tax-results.txt';
            document.body.appendChild(a); a.click(); document.body.removeChild(a); URL.revokeObjectURL(url);
        },
    };
}
</script>
@endpush
