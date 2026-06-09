@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')
<style>
/* ══════════════════════════════════════════════
   DISCOUNT CALCULATOR — component styles
══════════════════════════════════════════════ */

/* ── Mode tabs ── */
.dc-tab {
    padding: .55rem 1.1rem;
    border-bottom: 2.5px solid transparent;
    font-size: .82rem; font-weight: 600; color: #64748b;
    background: transparent; border-top: none; border-left: none; border-right: none;
    cursor: pointer; transition: color .12s, border-color .12s; white-space: nowrap;
}
.dc-tab:hover { color: #4f46e5; }
.dc-tab-active { color: #4f46e5; border-bottom-color: #4f46e5; }

/* ── Price input with currency prefix ── */
.dc-field-wrap {
    display: flex; align-items: stretch;
}
.dc-currency-prefix {
    display: flex; align-items: center; padding: 0 .85rem;
    background: #f8fafc; border: 1px solid #d1d5db; border-right: none;
    border-radius: .75rem 0 0 .75rem;
    font-size: .92rem; font-weight: 700; color: #374151; white-space: nowrap;
    user-select: none;
}
.dc-field-wrap .form-input {
    border-radius: 0 .75rem .75rem 0 !important; flex: 1; min-width: 0;
}
.dc-field-suffix {
    display: flex; align-items: center; padding: 0 .85rem;
    background: #f8fafc; border: 1px solid #d1d5db; border-left: none;
    border-radius: 0 .75rem .75rem 0;
    font-size: .88rem; font-weight: 600; color: #64748b; white-space: nowrap;
}
.dc-field-wrap.suffix .form-input {
    border-radius: .75rem 0 0 .75rem !important; border-right: none;
}

/* ── Quick % pills ── */
.dc-qpct {
    display: inline-flex; align-items: center;
    padding: .28rem .7rem; border-radius: 9999px;
    font-size: .73rem; font-weight: 700;
    border: 1.5px solid #e2e8f0; background: white; color: #475569;
    cursor: pointer; transition: all .12s; line-height: 1;
}
.dc-qpct:hover  { border-color: #a5b4fc; color: #4f46e5; background: #f0f4ff; }
.dc-qpct.active { border-color: #4f46e5; background: #4f46e5; color: white; box-shadow: 0 1px 6px rgba(79,70,229,.3); }

/* ── YOU SAVE banner ── */
.dc-save-banner {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    border-radius: 1.25rem; padding: 1.75rem 1.5rem; color: white; text-align: center;
    position: relative; overflow: hidden;
}
.dc-save-banner::before {
    content: ''; position: absolute; top: -40%; right: -10%;
    width: 14rem; height: 14rem; border-radius: 50%;
    background: rgba(255,255,255,.06); pointer-events: none;
}
.dc-save-banner::after {
    content: ''; position: absolute; bottom: -30%; left: -5%;
    width: 10rem; height: 10rem; border-radius: 50%;
    background: rgba(255,255,255,.05); pointer-events: none;
}
.dc-save-amount {
    font-size: 3.2rem; font-weight: 900; line-height: 1;
    letter-spacing: -.02em; position: relative; z-index: 1;
}
.dc-save-label  { font-size: .78rem; font-weight: 600; opacity: .8; text-transform: uppercase; letter-spacing: .08em; margin-bottom: .35rem; }
.dc-save-pct    { font-size: 1rem; font-weight: 600; opacity: .85; margin-top: .3rem; }

/* ── Stat cards ── */
.dc-stat {
    background: white; border: 1.5px solid #e2e8f0; border-radius: 1.125rem;
    padding: 1.15rem 1rem; text-align: center;
    transition: border-color .15s, box-shadow .15s, transform .15s;
}
.dc-stat:hover { border-color: #a5b4fc; box-shadow: 0 4px 16px rgba(79,70,229,.08); transform: translateY(-1px); }

/* ── Gradient stat number ── */
.dc-num {
    font-size: 1.85rem; font-weight: 900; line-height: 1; word-break: break-all;
}
.dc-num.indigo { background: linear-gradient(135deg,#4f46e5,#7c3aed); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.dc-num.rose   { background: linear-gradient(135deg,#e11d48,#f43f5e); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.dc-num.green  { background: linear-gradient(135deg,#059669,#10b981); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.dc-num.amber  { background: linear-gradient(135deg,#d97706,#f59e0b); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.dc-num.cyan   { background: linear-gradient(135deg,#0891b2,#06b6d4); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }

/* ── Price receipt breakdown ── */
.dc-receipt {
    background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 1.125rem; overflow: hidden;
}
.dc-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: .65rem 1rem; font-size: .87rem; color: #334155;
}
.dc-row + .dc-row { border-top: 1px dashed #e2e8f0; }
.dc-row.dc-discount { color: #16a34a; }
.dc-row.dc-tax-row  { color: #92400e; }
.dc-row.dc-total {
    background: #eef2ff; border-top: 2px solid #c7d2fe !important;
    font-size: .92rem; font-weight: 700; color: #3730a3;
}

/* ── Savings bar ── */
.dc-bar-wrap  { width:100%; height:8px; background:#e2e8f0; border-radius:9999px; overflow:hidden; }
.dc-bar-fill  { height:100%; background:linear-gradient(90deg,#4f46e5,#7c3aed); border-radius:9999px; transition:width .5s ease; }

/* ── Entrance animation ── */
@keyframes dcIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
.dc-in { animation: dcIn .3s ease-out; }

/* ── Toggle switch ── */
.dc-toggle {
    position: relative; display: inline-block; width: 2.25rem; height: 1.25rem;
    cursor: pointer; flex-shrink: 0;
}
.dc-toggle input { opacity:0; width:0; height:0; }
.dc-toggle-slider {
    position: absolute; top:0; left:0; right:0; bottom:0;
    background: #d1d5db; border-radius: 9999px;
    transition: background .2s;
}
.dc-toggle-slider::before {
    content:''; position:absolute; height:.9rem; width:.9rem; left:.18rem; bottom:.17rem;
    background: white; border-radius: 9999px; transition: transform .2s;
    box-shadow: 0 1px 3px rgba(0,0,0,.2);
}
.dc-toggle input:checked + .dc-toggle-slider { background: #4f46e5; }
.dc-toggle input:checked + .dc-toggle-slider::before { transform: translateX(1rem); }
</style>

<div class="min-h-screen bg-gray-50" x-data="discCalc()" x-init="init()">

    {{-- ── Page Header ── --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-8 md:py-10">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                        {{ $tool->icon }} {{ $tool->name }}
                    </h1>
                    <p class="text-gray-500 mt-2 max-w-2xl">
                        Calculate your savings instantly — enter a price and discount percentage to see the discounted price, amount saved, and final cost with optional sales tax.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2 shrink-0">
                    <span class="badge badge-success">Free</span>
                    <span class="badge badge-gray">Instant</span>
                    <span class="badge badge-primary">No Sign-up</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-6 md:py-8">

        {{-- ══════════════════════════════════════════════════
             TWO-COLUMN LAYOUT: Input left, Results right
        ══════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 items-start">

            {{-- ════════════════════════════════
                 LEFT — Input Card
            ════════════════════════════════ --}}
            <div class="card">

                {{-- Mode tabs --}}
                <div class="border-b border-gray-100 px-5 flex overflow-x-auto">
                    <button type="button" @click="setMode('pct')"      class="dc-tab" :class="mode==='pct'      ? 'dc-tab-active':''">% Off</button>
                    <button type="button" @click="setMode('fixed')"    class="dc-tab" :class="mode==='fixed'    ? 'dc-tab-active':''">Fixed Amount</button>
                    <button type="button" @click="setMode('findOrig')" class="dc-tab" :class="mode==='findOrig' ? 'dc-tab-active':''">Find Original</button>
                </div>

                <div class="p-5 space-y-5">

                    {{-- ── Currency selector row ── --}}
                    <div class="flex items-center gap-3">
                        <label class="form-label mb-0 whitespace-nowrap shrink-0">Currency</label>
                        <select x-model="currency" class="form-input py-2 w-28 text-sm">
                            <option value="$">$ USD</option>
                            <option value="€">€ EUR</option>
                            <option value="£">£ GBP</option>
                            <option value="¥">¥ JPY</option>
                            <option value="₹">₹ INR</option>
                            <option value="₦">₦ NGN</option>
                            <option value="A$">A$ AUD</option>
                            <option value="C$">C$ CAD</option>
                        </select>
                        <span class="text-xs text-gray-400 hidden sm:block">Symbol used in results display only</span>
                    </div>

                    {{-- ══ MODE: % Off ══ --}}
                    <div x-show="mode === 'pct'" class="space-y-4">
                        <p class="text-sm text-gray-500">Enter the original price and the percentage discount to see how much you save.</p>

                        {{-- Original price --}}
                        <div>
                            <label class="form-label">Original Price</label>
                            <div class="dc-field-wrap">
                                <span class="dc-currency-prefix" x-text="currency"></span>
                                <input type="number" x-model="origPrice" min="0" step="0.01"
                                       placeholder="100.00" class="form-input"
                                       @input="autoCalc()" @keydown.enter="calculate()">
                            </div>
                        </div>

                        {{-- Discount % --}}
                        <div>
                            <label class="form-label">Discount Percentage</label>
                            <div class="dc-field-wrap suffix">
                                <input type="number" x-model="discPct" min="0" max="100" step="0.01"
                                       placeholder="20" class="form-input"
                                       @input="autoCalc()" @keydown.enter="calculate()">
                                <span class="dc-field-suffix">%</span>
                            </div>
                            {{-- Quick % buttons --}}
                            <div class="flex flex-wrap gap-1.5 mt-2.5">
                                <template x-for="q in quickPcts" :key="q">
                                    <button type="button" @click="setQuickPct(q)"
                                            class="dc-qpct"
                                            :class="discPct == q ? 'active' : ''"
                                            x-text="q + '%'">
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- ══ MODE: Fixed Amount ══ --}}
                    <div x-show="mode === 'fixed'" class="space-y-4">
                        <p class="text-sm text-gray-500">Enter the original price and the exact discount amount in your currency.</p>

                        <div>
                            <label class="form-label">Original Price</label>
                            <div class="dc-field-wrap">
                                <span class="dc-currency-prefix" x-text="currency"></span>
                                <input type="number" x-model="origPrice" min="0" step="0.01"
                                       placeholder="89.99" class="form-input"
                                       @input="autoCalc()" @keydown.enter="calculate()">
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Discount Amount</label>
                            <div class="dc-field-wrap">
                                <span class="dc-currency-prefix" x-text="currency"></span>
                                <input type="number" x-model="discAmt" min="0" step="0.01"
                                       placeholder="15.00" class="form-input"
                                       @input="autoCalc()" @keydown.enter="calculate()">
                            </div>
                        </div>
                    </div>

                    {{-- ══ MODE: Find Original ══ --}}
                    <div x-show="mode === 'findOrig'" class="space-y-4">
                        <p class="text-sm text-gray-500">Know the sale price and discount %? Work out what the original price was.</p>

                        <div>
                            <label class="form-label">Sale Price (after discount)</label>
                            <div class="dc-field-wrap">
                                <span class="dc-currency-prefix" x-text="currency"></span>
                                <input type="number" x-model="salePrice" min="0" step="0.01"
                                       placeholder="75.00" class="form-input"
                                       @input="autoCalc()" @keydown.enter="calculate()">
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Discount Percentage Applied</label>
                            <div class="dc-field-wrap suffix">
                                <input type="number" x-model="discPct" min="0.01" max="99.99" step="0.01"
                                       placeholder="25" class="form-input"
                                       @input="autoCalc()" @keydown.enter="calculate()">
                                <span class="dc-field-suffix">%</span>
                            </div>
                            <div class="flex flex-wrap gap-1.5 mt-2.5">
                                <template x-for="q in quickPcts" :key="q">
                                    <button type="button" @click="setQuickPct(q)"
                                            class="dc-qpct" :class="discPct == q ? 'active' : ''"
                                            x-text="q + '%'">
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- ── Sales Tax toggle (not shown in findOrig mode) ── --}}
                    <div x-show="mode !== 'findOrig'" class="space-y-3">
                        <div class="flex items-center gap-3">
                            <label class="dc-toggle">
                                <input type="checkbox" x-model="taxEnabled">
                                <span class="dc-toggle-slider"></span>
                            </label>
                            <span class="text-sm font-medium text-gray-700">Add Sales Tax</span>
                            <span x-show="taxEnabled" class="text-xs text-gray-400">(applied to discounted price)</span>
                        </div>
                        <div x-show="taxEnabled" x-transition class="pl-0 sm:pl-1">
                            <label class="form-label">Tax Rate</label>
                            <div class="dc-field-wrap suffix" style="max-width:14rem;">
                                <input type="number" x-model="taxPct" min="0" max="100" step="0.01"
                                       placeholder="8.5" class="form-input"
                                       @input="autoCalc()" @keydown.enter="calculate()">
                                <span class="dc-field-suffix">%</span>
                            </div>
                        </div>
                    </div>

                    {{-- ── Error message ── --}}
                    <div x-show="errMsg" x-transition
                         class="flex items-center gap-2 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span x-text="errMsg"></span>
                    </div>

                    {{-- ── Action buttons ── --}}
                    <div class="flex flex-wrap gap-2 pt-1">
                        <button type="button" @click="calculate()" class="btn btn-primary flex-1 sm:flex-none btn-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Calculate
                        </button>
                        <button type="button" @click="loadSample()" class="btn btn-secondary">📋 Sample</button>
                        <button type="button" @click="clearAll()" x-show="hasInput || phase==='done'" class="btn btn-secondary">✕ Clear</button>
                    </div>
                    <p class="text-xs text-gray-400 text-center">
                        Results update automatically as you type &nbsp;·&nbsp;
                        <kbd class="px-1.5 py-0.5 bg-gray-100 rounded text-gray-600 font-mono text-[10px]">Enter</kbd> to confirm
                    </p>
                </div>
            </div>

            {{-- ════════════════════════════════
                 RIGHT — Results Panel
            ════════════════════════════════ --}}
            <div class="space-y-4" id="dc-results">

                {{-- IDLE placeholder --}}
                <div x-show="phase === 'idle'" class="card">
                    <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                        <span class="text-6xl mb-4">🏷️</span>
                        <p class="text-sm font-semibold text-gray-600 mb-1">No calculation yet</p>
                        <p class="text-xs text-gray-400 max-w-xs mb-5">
                            Enter a price and discount on the left. Results appear here instantly as you type.
                        </p>
                        <button type="button" @click="loadSample(); $nextTick(() => calculate())" class="btn btn-outline text-sm">
                            Try a sample →
                        </button>
                    </div>
                </div>

                {{-- RESULTS --}}
                <div x-show="phase === 'done' && result"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     class="space-y-4 dc-in">

                    {{-- ── YOU SAVE banner ── --}}
                    <div class="dc-save-banner">
                        <p class="dc-save-label">You Save</p>
                        <p class="dc-save-amount">
                            <span x-text="currency"></span><span x-text="result ? fmt(result.discAmt) : ''"></span>
                        </p>
                        <p class="dc-save-pct">
                            <span x-text="result ? fmtPct(result.savings) : ''"></span> off the original price
                        </p>

                        {{-- Savings bar --}}
                        <div class="mt-4 px-2">
                            <div class="dc-bar-wrap bg-white/20">
                                <div class="dc-bar-fill bg-white/70"
                                     :style="'width:' + (result ? Math.min(result.savings, 100) : 0) + '%'">
                                </div>
                            </div>
                            <div class="flex justify-between mt-1.5 text-[10px] font-semibold opacity-70">
                                <span>0%</span>
                                <span>50%</span>
                                <span>100%</span>
                            </div>
                        </div>
                    </div>

                    {{-- ── Stat cards grid ── --}}
                    <div class="grid grid-cols-2 gap-3">

                        {{-- Original Price --}}
                        <div class="dc-stat" x-show="result && result.mode !== 'pct' || true">
                            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Original Price</div>
                            <div class="dc-num cyan">
                                <span x-text="currency"></span><span x-text="result ? fmt(result.origPrice) : ''"></span>
                            </div>
                        </div>

                        {{-- Discount Amount --}}
                        <div class="dc-stat">
                            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">You Save</div>
                            <div class="dc-num rose">
                                −<span x-text="currency"></span><span x-text="result ? fmt(result.discAmt) : ''"></span>
                            </div>
                            <div class="text-xs text-gray-400 mt-1" x-text="result ? '(' + fmtPct(result.savings) + ')' : ''"></div>
                        </div>

                        {{-- Price After Discount --}}
                        <div class="dc-stat" :class="result && !result.hasTax ? 'col-span-2' : ''">
                            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"
                                 x-text="result && result.hasTax ? 'Price After Discount' : 'Final Price'"></div>
                            <div class="dc-num green">
                                <span x-text="currency"></span><span x-text="result ? fmt(result.salePrice) : ''"></span>
                            </div>
                            <div class="text-xs text-gray-400 mt-1" x-show="result && result.hasTax">before tax</div>
                        </div>

                        {{-- Tax Amount (shown only when tax enabled) --}}
                        <div class="dc-stat" x-show="result && result.hasTax">
                            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Tax Added</div>
                            <div class="dc-num amber">
                                +<span x-text="currency"></span><span x-text="result ? fmt(result.taxAmt) : ''"></span>
                            </div>
                            <div class="text-xs text-gray-400 mt-1" x-text="result ? fmtPct(result.taxPct) + ' rate' : ''"></div>
                        </div>

                        {{-- Final Price (only when tax is on) --}}
                        <div class="dc-stat col-span-2" x-show="result && result.hasTax">
                            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Final Payable Amount</div>
                            <div class="dc-num indigo" style="font-size:2.4rem;">
                                <span x-text="currency"></span><span x-text="result ? fmt(result.finalPrice) : ''"></span>
                            </div>
                            <div class="text-xs text-gray-400 mt-1">including tax</div>
                        </div>

                    </div>

                    {{-- ── Price Breakdown (receipt-style) ── --}}
                    <div class="card overflow-hidden">
                        <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <span class="text-sm font-semibold text-gray-700">Price Breakdown</span>
                            </div>
                            <button type="button" @click="copySummary()"
                                    class="btn btn-sm border border-gray-200"
                                    :class="copyFlash ? 'bg-emerald-50 text-emerald-700 border-emerald-300' : 'bg-white text-gray-600 hover:bg-gray-50'">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                <span x-text="copyFlash ? '✓ Copied!' : 'Copy'"></span>
                            </button>
                        </div>
                        <div class="dc-receipt" style="border:none;border-radius:0;">
                            {{-- Original price row --}}
                            <div class="dc-row">
                                <span class="text-gray-500">Original Price</span>
                                <span class="font-semibold font-mono"
                                      x-text="currency + fmt(result ? result.origPrice : 0)"></span>
                            </div>
                            {{-- Discount row --}}
                            <div class="dc-row dc-discount">
                                <span class="flex items-center gap-1.5">
                                    <span class="badge" style="background:#dcfce7;color:#166534;font-size:.65rem;"
                                          x-text="result ? '−' + fmtPct(result.savings) : ''"></span>
                                    Discount
                                </span>
                                <span class="font-semibold font-mono"
                                      x-text="'−' + currency + fmt(result ? result.discAmt : 0)"></span>
                            </div>
                            {{-- Sale price row --}}
                            <div class="dc-row">
                                <span class="text-gray-500">Price After Discount</span>
                                <span class="font-semibold font-mono"
                                      x-text="currency + fmt(result ? result.salePrice : 0)"></span>
                            </div>
                            {{-- Tax row (conditional) --}}
                            <div class="dc-row dc-tax-row" x-show="result && result.hasTax">
                                <span class="flex items-center gap-1.5">
                                    <span class="badge badge-warning" style="font-size:.65rem;"
                                          x-text="result ? '+' + fmtPct(result.taxPct) + ' tax' : ''"></span>
                                    Sales Tax
                                </span>
                                <span class="font-semibold font-mono"
                                      x-text="'+' + currency + fmt(result ? result.taxAmt : 0)"></span>
                            </div>
                            {{-- Total row --}}
                            <div class="dc-row dc-total">
                                <span>Total You Pay</span>
                                <span class="font-mono"
                                      x-text="currency + fmt(result ? result.finalPrice : 0)"></span>
                            </div>
                        </div>
                    </div>

                    {{-- ── Find-original extra info ── --}}
                    <div x-show="result && result.mode === 'findOrig'"
                         class="flex items-start gap-3 p-4 bg-blue-50 border border-blue-200 rounded-xl text-sm text-blue-800">
                        <svg class="w-4 h-4 mt-0.5 shrink-0 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p>
                            The item was originally priced at <strong x-text="currency + fmt(result ? result.origPrice : 0)"></strong>
                            before the <strong x-text="result ? fmtPct(result.savings) : ''"></strong> discount was applied,
                            saving you <strong x-text="currency + fmt(result ? result.discAmt : 0)"></strong>.
                        </p>
                    </div>

                    {{-- ── Recalculate / reset row ── --}}
                    <div class="flex flex-wrap gap-2">
                        <button type="button" @click="calculate()" class="btn btn-secondary btn-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Recalculate
                        </button>
                        <button type="button" @click="clearAll()" class="btn btn-secondary btn-sm text-red-500 hover:text-red-600 hover:bg-red-50">
                            ✕ Reset
                        </button>
                    </div>

                </div>{{-- /results --}}

            </div>{{-- /right panel --}}
        </div>{{-- /two-col grid --}}

        {{-- ══════════════════════════════════════════════
             IDLE INFO CARDS (below both columns, idle only)
        ══════════════════════════════════════════════ --}}
        <div x-show="phase === 'idle'" class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-5">
            @foreach([
                ['icon'=>'%',   'label'=>'% Off',          'desc'=>'Enter price and % discount to get savings and final cost'],
                ['icon'=>'💵',  'label'=>'Fixed Discount',  'desc'=>'Know the exact amount off? Get the percentage and final price'],
                ['icon'=>'🔍',  'label'=>'Find Original',   'desc'=>'Given a sale price and % off, reverse-calculate the original price'],
                ['icon'=>'🧾',  'label'=>'With Tax',        'desc'=>'Add a sales tax rate to get the true final payable amount'],
            ] as $f)
            <div class="card p-4 text-center hover:border-brand-200 transition-colors">
                <p class="text-2xl mb-1.5">{{ $f['icon'] }}</p>
                <p class="text-sm font-semibold text-gray-700">{{ $f['label'] }}</p>
                <p class="text-xs text-gray-400 mt-1 leading-snug">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Related tools --}}
        @if($relatedTools->count())
        <div class="mt-8" x-show="phase === 'idle'">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Related Calculators</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
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
@endsection

@push('scripts')
<script>
/* ═══════════════════════════════════════════════════════════
   DISCOUNT CALCULATOR — pure client-side Alpine.js component
═══════════════════════════════════════════════════════════ */
function discCalc() {
    return {
        /* ── Mode & currency ── */
        mode:     'pct',   /* pct | fixed | findOrig */
        currency: '$',

        /* ── Inputs ── */
        origPrice: '',
        discPct:   '',
        discAmt:   '',
        salePrice: '',
        taxPct:    '',
        taxEnabled: false,

        /* ── Quick-select percentages ── */
        quickPcts: [5, 10, 15, 20, 25, 30, 40, 50, 70, 75],

        /* ── UI state ── */
        phase:     'idle',   /* idle | done */
        result:    null,
        errMsg:    '',
        copyFlash: false,

        /* ── Computed ── */
        get hasInput() {
            if (this.mode === 'pct')      return this.origPrice !== '' || this.discPct !== '';
            if (this.mode === 'fixed')    return this.origPrice !== '' || this.discAmt !== '';
            if (this.mode === 'findOrig') return this.salePrice !== '' || this.discPct !== '';
            return false;
        },

        init() { /* nothing */ },

        /* Change mode, reset results */
        setMode(m) {
            this.mode   = m;
            this.errMsg = '';
            this.result = null;
            this.phase  = 'idle';
        },

        /* Click a quick-% button */
        setQuickPct(pct) {
            this.discPct = String(pct);
            this.errMsg  = '';
            this.autoCalc();
        },

        /* Auto-calculate on every keystroke (only when both key fields filled) */
        autoCalc() {
            this.errMsg = '';
            if (this.mode === 'pct' && this.origPrice !== '' && this.discPct !== '') {
                this._tryCalc();
            } else if (this.mode === 'fixed' && this.origPrice !== '' && this.discAmt !== '') {
                this._tryCalc();
            } else if (this.mode === 'findOrig' && this.salePrice !== '' && this.discPct !== '') {
                this._tryCalc();
            }
        },

        /* Silent try (suppresses error display during typing) */
        _tryCalc() {
            try {
                var res = null;
                if (this.mode === 'pct')      res = this._calcPct();
                else if (this.mode === 'fixed')    res = this._calcFixed();
                else                              res = this._calcFindOrig();
                this.result = res;
                this.phase  = 'done';
            } catch(e) {
                /* Don't show error during auto-calc; only show on explicit button press */
            }
        },

        /* Explicit calculate (shows errors) */
        calculate() {
            this.errMsg = '';
            this.result = null;
            try {
                if      (this.mode === 'pct')      this.result = this._calcPct();
                else if (this.mode === 'fixed')    this.result = this._calcFixed();
                else                               this.result = this._calcFindOrig();
                this.phase = 'done';

                /* Scroll to results on small screens */
                if (window.innerWidth < 1024) {
                    var el = document.getElementById('dc-results');
                    if (el) setTimeout(function() {
                        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 80);
                }
            } catch(e) {
                this.errMsg = typeof e === 'string' ? e : 'Please check your inputs and try again.';
                this.phase  = 'idle';
            }
        },

        /* ════════════════════════════════════════════════
           MATH — three calculation modes
        ════════════════════════════════════════════════ */

        _calcPct() {
            var price  = this._num(this.origPrice, 'Original price',    { gt0: true });
            var pct    = this._num(this.discPct,   'Discount %',        { min: 0, max: 100 });
            var taxR   = this.taxEnabled ? this._num(this.taxPct, 'Tax %', { min: 0, max: 100 }) : 0;

            var disc   = price * pct / 100;
            var sale   = price - disc;
            var tax    = sale  * taxR / 100;
            var final  = sale + tax;

            return {
                mode: 'pct', origPrice: price, savings: pct, discAmt: disc,
                discPct: pct, salePrice: sale, taxPct: taxR, taxAmt: tax,
                finalPrice: final, hasTax: this.taxEnabled && taxR > 0,
            };
        },

        _calcFixed() {
            var price  = this._num(this.origPrice, 'Original price',    { gt0: true });
            var disc   = this._num(this.discAmt,   'Discount amount',   { min: 0 });
            if (disc > price) throw 'Discount amount (' + this.currency + this.fmt(disc) + ') cannot exceed the original price (' + this.currency + this.fmt(price) + ').';
            var taxR   = this.taxEnabled ? this._num(this.taxPct, 'Tax %', { min: 0, max: 100 }) : 0;

            var pct    = (disc / price) * 100;
            var sale   = price - disc;
            var tax    = sale  * taxR / 100;
            var final  = sale + tax;

            return {
                mode: 'fixed', origPrice: price, savings: pct, discAmt: disc,
                discPct: pct, salePrice: sale, taxPct: taxR, taxAmt: tax,
                finalPrice: final, hasTax: this.taxEnabled && taxR > 0,
            };
        },

        _calcFindOrig() {
            var sale   = this._num(this.salePrice, 'Sale price',   { gt0: true });
            var pct    = this._num(this.discPct,   'Discount %',   { min: 0.01, max: 99.99 });

            var orig   = sale / (1 - pct / 100);
            var disc   = orig - sale;

            return {
                mode: 'findOrig', origPrice: orig, savings: pct, discAmt: disc,
                discPct: pct, salePrice: sale, taxPct: 0, taxAmt: 0,
                finalPrice: sale, hasTax: false,
            };
        },

        /* ════════════════════════════════════════════════
           VALIDATION HELPER
        ════════════════════════════════════════════════ */

        _num(v, label, opts) {
            opts = opts || {};
            if (v === '' || v === null || v === undefined)
                throw label + ' is required.';
            var n = parseFloat(v);
            if (isNaN(n) || !isFinite(n))
                throw label + ' must be a valid number.';
            if (opts.gt0  && n <= 0)   throw label + ' must be greater than zero.';
            if (opts.min  !== undefined && n < opts.min)
                throw label + ' must be at least ' + opts.min + '.';
            if (opts.max  !== undefined && n > opts.max)
                throw label + ' cannot exceed ' + opts.max + '.';
            return n;
        },

        /* ════════════════════════════════════════════════
           FORMATTING
        ════════════════════════════════════════════════ */

        /* Format number with 2 decimal places + thousands separator */
        fmt(n) {
            if (n === null || n === undefined || isNaN(n)) return '—';
            return parseFloat(n.toFixed(2))
                .toFixed(2)
                .replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        },

        /* Format percentage — strip trailing zeros */
        fmtPct(n) {
            if (n === null || n === undefined || isNaN(n)) return '—';
            var s = parseFloat(n.toFixed(4));
            /* Show up to 2 dp, drop trailing zeros */
            var str = s.toFixed(2).replace(/\.?0+$/, '');
            return str + '%';
        },

        /* ════════════════════════════════════════════════
           SAMPLE & CLEAR
        ════════════════════════════════════════════════ */

        loadSample() {
            this.errMsg = '';
            if      (this.mode === 'pct')      { this.origPrice = '120'; this.discPct = '25'; }
            else if (this.mode === 'fixed')    { this.origPrice = '89.99'; this.discAmt = '15'; }
            else                               { this.salePrice = '75'; this.discPct = '25'; }
        },

        clearAll() {
            this.origPrice = ''; this.discPct = ''; this.discAmt = '';
            this.salePrice = ''; this.taxPct = '';
            this.taxEnabled = false;
            this.errMsg = ''; this.result = null; this.phase = 'idle';
        },

        /* ════════════════════════════════════════════════
           COPY SUMMARY
        ════════════════════════════════════════════════ */

        async copySummary() {
            if (!this.result) return;
            var r = this.result, c = this.currency;
            var lines = [
                'Discount Calculator Results',
                '============================',
                'Original Price : ' + c + this.fmt(r.origPrice),
                'Discount       : ' + this.fmtPct(r.discPct) + ' (−' + c + this.fmt(r.discAmt) + ')',
                'Sale Price     : ' + c + this.fmt(r.salePrice),
            ];
            if (r.hasTax) {
                lines.push('Tax (' + this.fmtPct(r.taxPct) + ') : +' + c + this.fmt(r.taxAmt));
                lines.push('Final Price    : ' + c + this.fmt(r.finalPrice));
            }
            lines.push('You Save       : ' + c + this.fmt(r.discAmt) + ' (' + this.fmtPct(r.savings) + ')');

            var text = lines.join('\n');
            try { await navigator.clipboard.writeText(text); }
            catch(e) {
                var ta = document.createElement('textarea');
                ta.value = text; ta.style.cssText = 'position:fixed;opacity:0;';
                document.body.appendChild(ta); ta.select(); document.execCommand('copy');
                document.body.removeChild(ta);
            }
            var self = this;
            this.copyFlash = true;
            setTimeout(function() { self.copyFlash = false; }, 1800);
        },
    };
}
</script>
@endpush
