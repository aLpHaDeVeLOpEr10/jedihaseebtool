@extends('layouts.public')

@section('title', $tool->name . ' - ' . config('app.name'))
@section('meta_description', $tool->description ?? 'Find your break-even point in units and revenue, plus contribution margin and profit scenarios.')

@section('content')
<style>
/* ══════════════════════════════════════════════
   Break-Even Calculator  —  prefix: be-
   Theme: Sky Blue / Cyan
══════════════════════════════════════════════ */

/* Hero number */
.be-hero { font-size:clamp(2.8rem,6vw,4.5rem); font-weight:900; line-height:1; letter-spacing:-.04em;
           background:linear-gradient(135deg,#075985,#0284c7,#0ea5e9);
           -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }

/* Stat tile */
.be-stat { background:#fff; border:1.5px solid #bae6fd; border-radius:1.125rem;
            padding:1rem .9rem; display:flex; flex-direction:column; align-items:center;
            gap:.3rem; text-align:center; transition:all .15s; }
.be-stat:hover { transform:translateY(-1px); box-shadow:0 4px 16px rgba(14,165,233,.09); }
.be-stat-lbl { font-size:.6rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.08em; }
.be-stat-val { font-size:1.15rem; font-weight:900; line-height:1.15; }
.be-stat-val.sky    { background:linear-gradient(135deg,#075985,#0ea5e9); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.be-stat-val.green  { background:linear-gradient(135deg,#14532d,#16a34a); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.be-stat-val.violet { background:linear-gradient(135deg,#4c1d95,#7c3aed); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.be-stat-val.amber  { background:linear-gradient(135deg,#78350f,#d97706); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.be-stat-val.slate  { background:linear-gradient(135deg,#1e293b,#475569); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.be-stat-sub { font-size:.62rem; color:#94a3b8; }

/* Currency input */
.be-cur-wrap { display:flex; align-items:stretch; }
.be-cur-pre  { display:flex; align-items:center; padding:0 .75rem; background:#f0f9ff;
                border:1px solid #d1d5db; border-right:none; border-radius:.75rem 0 0 .75rem;
                font-size:.9rem; font-weight:800; color:#0369a1; white-space:nowrap; min-width:2.1rem; justify-content:center; }
.be-cur-wrap .form-input { border-radius:0 .75rem .75rem 0 !important; }

/* Section divider */
.be-div { display:flex; align-items:center; gap:.6rem; color:#94a3b8; font-size:.62rem;
           font-weight:800; text-transform:uppercase; letter-spacing:.1em; }
.be-div::before,.be-div::after { content:''; flex:1; height:1px; background:#bae6fd; }

/* Scenario table */
.be-sc-row { display:grid; grid-template-columns:.9fr 1fr 1fr 1fr; gap:.5rem;
              align-items:center; padding:.45rem .75rem; border-radius:.625rem; font-size:.78rem; }
.be-sc-row:hover { background:#f0f9ff; }
.be-sc-row.head   { font-size:.6rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.07em; }
.be-sc-row.bep    { background:#fef3c7; border:1.5px solid #fcd34d; font-weight:700; }
.be-sc-row.profit { background:#f0fdf4; }
.be-sc-row.loss   { background:#fef2f2; }

/* Chart container */
.be-chart-wrap { background:linear-gradient(135deg,#f0f9ff,#e0f2fe); border:1.5px solid #bae6fd;
                  border-radius:1.25rem; overflow:hidden; }

/* Chart legend item */
.be-legend { display:inline-flex; align-items:center; gap:.35rem; font-size:.7rem; font-weight:600; color:#374151; }
.be-legend-dot { width:12px; height:3px; border-radius:9999px; flex-shrink:0; }

/* Target profit toggle */
.be-tp-toggle { display:flex; align-items:center; gap:.5rem; cursor:pointer; }

/* Metric row */
.be-metric-row { display:flex; align-items:center; justify-content:space-between; gap:.5rem;
                  padding:.45rem .75rem; border-radius:.625rem; font-size:.82rem; }
.be-metric-row:hover { background:#f0f9ff; }

/* Animate in */
@keyframes beIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
.be-in { animation:beIn .3s ease-out; }
</style>

<div class="min-h-screen bg-gray-50 py-8" x-data="beCalc()" x-init="init()">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4"
           style="background:linear-gradient(135deg,#0369a1,#0ea5e9)">
        <span class="text-3xl">📊</span>
      </div>
      <h1 class="text-3xl font-bold text-gray-900">Break-Even Calculator</h1>
      <p class="mt-2 text-gray-500">Find the exact point where total revenue equals total costs</p>
    </div>

    {{-- Error --}}
    <div x-show="errorMsg" x-transition
         class="mb-6 p-4 rounded-xl border border-red-200 bg-red-50 text-red-700 text-sm flex items-start gap-2">
      <span class="text-lg leading-none">⚠️</span>
      <span x-text="errorMsg"></span>
    </div>

    <div class="lg:grid lg:grid-cols-5 lg:gap-8 space-y-6 lg:space-y-0">

      {{-- ══════ INPUT PANEL ══════ --}}
      <div class="lg:col-span-2 space-y-5">

        {{-- Currency --}}
        <div class="card p-4">
          <label class="form-label">Currency</label>
          <select x-model="currency" class="form-input w-full">
            <template x-for="c in currencies" :key="c.id">
              <option :value="c.id" x-text="c.label"></option>
            </template>
          </select>
        </div>

        {{-- Core inputs --}}
        <div class="card p-5 space-y-4">
          <h2 class="font-semibold text-gray-800">Cost &amp; Price Inputs</h2>

          {{-- Fixed Cost --}}
          <div>
            <label class="form-label">Fixed Costs (Total)</label>
            <div class="be-cur-wrap">
              <span class="be-cur-pre" x-text="currencySymbol"></span>
              <input type="number" step="any" min="0"
                     x-model="fixedCost"
                     placeholder="e.g. 10000"
                     @input.debounce.600ms="autoCompute()"
                     class="form-input flex-1" />
            </div>
            <p class="text-xs text-gray-400 mt-1">Rent, salaries, insurance — costs that don't change with output</p>
          </div>

          {{-- Variable Cost per Unit --}}
          <div>
            <label class="form-label">Variable Cost Per Unit</label>
            <div class="be-cur-wrap">
              <span class="be-cur-pre" x-text="currencySymbol"></span>
              <input type="number" step="any" min="0"
                     x-model="varCostUnit"
                     placeholder="e.g. 25"
                     @input.debounce.600ms="autoCompute()"
                     class="form-input flex-1" />
            </div>
            <p class="text-xs text-gray-400 mt-1">Raw materials, direct labour — costs per unit produced</p>
          </div>

          {{-- Selling Price per Unit --}}
          <div>
            <label class="form-label">Selling Price Per Unit</label>
            <div class="be-cur-wrap">
              <span class="be-cur-pre" x-text="currencySymbol"></span>
              <input type="number" step="any" min="0"
                     x-model="sellPriceUnit"
                     placeholder="e.g. 50"
                     @input.debounce.600ms="autoCompute()"
                     class="form-input flex-1" />
            </div>
            <p class="text-xs text-gray-400 mt-1">Must be greater than variable cost per unit</p>
          </div>
        </div>

        {{-- Target profit (optional) --}}
        <div class="card p-5 space-y-3">
          <div class="be-tp-toggle" @click="showTargetProfit = !showTargetProfit">
            <button :class="showTargetProfit ? 'bg-sky-600' : 'bg-gray-300'"
                    class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors focus:outline-none flex-shrink-0">
              <span :class="showTargetProfit ? 'translate-x-5' : 'translate-x-1'"
                    class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition-transform"></span>
            </button>
            <div>
              <p class="text-sm font-semibold text-gray-700">Target Profit Analysis</p>
              <p class="text-xs text-gray-400">Units needed to achieve a profit goal</p>
            </div>
          </div>

          <div x-show="showTargetProfit" x-transition class="pt-1">
            <label class="form-label">Desired Profit</label>
            <div class="be-cur-wrap">
              <span class="be-cur-pre" x-text="currencySymbol"></span>
              <input type="number" step="any" min="0"
                     x-model="targetProfit"
                     placeholder="e.g. 5000"
                     @input.debounce.600ms="autoCompute()"
                     class="form-input flex-1" />
            </div>
          </div>
        </div>

        {{-- Buttons --}}
        <div class="flex gap-3">
          <button @click="compute()"
                  class="btn btn-primary flex-1 py-3 font-bold text-base"
                  style="background:linear-gradient(135deg,#0369a1,#0ea5e9)">
            Calculate
          </button>
          <button @click="reset()"
                  class="btn btn-secondary py-3 px-5 font-semibold">
            Reset
          </button>
        </div>

        {{-- Quick explainer --}}
        <div class="card p-4 bg-sky-50 border border-sky-100">
          <p class="text-xs font-bold text-sky-800 uppercase tracking-wide mb-2">📖 Key Formulas</p>
          <div class="space-y-1 text-xs text-sky-700">
            <p><strong>CM / Unit</strong> = Selling Price − Variable Cost</p>
            <p><strong>CM Ratio</strong> = CM / Selling Price × 100</p>
            <p><strong>BEP Units</strong> = Fixed Costs ÷ CM per Unit</p>
            <p><strong>BEP Revenue</strong> = BEP Units × Selling Price</p>
          </div>
        </div>

      </div>

      {{-- ══════ RESULTS PANEL ══════ --}}
      <div class="lg:col-span-3 space-y-5">

        {{-- Idle --}}
        <div x-show="phase==='idle'" class="card p-12 text-center text-gray-400">
          <div class="text-5xl mb-4">📊</div>
          <p class="font-medium">Enter your fixed costs, variable cost, and selling price</p>
          <p class="text-sm mt-1">Break-even point, chart, and scenarios will appear here</p>
        </div>

        {{-- Loading --}}
        <div x-show="phase==='loading'" class="card p-12 text-center">
          <div class="inline-block w-7 h-7 border-4 border-sky-200 border-t-sky-600 rounded-full animate-spin mb-3"></div>
          <p class="text-gray-500 text-sm">Calculating…</p>
        </div>

        <template x-if="phase==='done'">
          <div class="space-y-5 be-in">

            {{-- Hero BEP card --}}
            <div class="rounded-2xl p-6 text-white"
                 style="background:linear-gradient(135deg,#075985,#0284c7,#0ea5e9)">
              <div class="flex items-start justify-between flex-wrap gap-4">
                <div>
                  <p class="text-sky-200 text-sm font-medium mb-1">Break-Even Point</p>
                  <div class="flex items-end gap-2">
                    <span class="text-4xl font-black tracking-tight" x-text="fmtInt(result.bepUnitsCeil)"></span>
                    <span class="text-sky-200 font-semibold mb-1.5">units</span>
                  </div>
                  <p class="text-sky-100 text-sm mt-1">
                    Revenue needed:
                    <span class="font-bold text-white" x-text="currencySymbol + fmtMoney(result.bepRevenue)"></span>
                  </p>
                  <p class="text-sky-200 text-xs mt-0.5" x-show="result.bepUnits !== result.bepUnitsCeil">
                    Exact: <span x-text="fmtNum(result.bepUnits, 4)"></span> units → rounded up to next whole unit
                  </p>
                </div>
                <div class="text-right flex-shrink-0">
                  <p class="text-sky-200 text-xs mb-1">CM per Unit</p>
                  <p class="text-2xl font-black text-white" x-text="currencySymbol + fmtMoney(result.cmUnit)"></p>
                  <p class="text-sky-200 text-xs mt-0.5">CM Ratio</p>
                  <p class="text-xl font-bold text-white" x-text="fmtNum(result.cmRatio, 2) + '%'"></p>
                </div>
              </div>
            </div>

            {{-- 4 stat tiles --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
              <div class="be-stat">
                <span class="be-stat-lbl">Fixed Costs</span>
                <span class="be-stat-val slate" x-text="currencySymbol + fmtCompact(result.fc)"></span>
                <span class="be-stat-sub">total</span>
              </div>
              <div class="be-stat">
                <span class="be-stat-lbl">Var. Cost/Unit</span>
                <span class="be-stat-val amber" x-text="currencySymbol + fmtMoney(result.vc)"></span>
                <span class="be-stat-sub">per unit</span>
              </div>
              <div class="be-stat">
                <span class="be-stat-lbl">CM / Unit</span>
                <span class="be-stat-val green" x-text="currencySymbol + fmtMoney(result.cmUnit)"></span>
                <span class="be-stat-sub">contribution</span>
              </div>
              <div class="be-stat">
                <span class="be-stat-lbl">CM Ratio</span>
                <span class="be-stat-val sky" x-text="fmtNum(result.cmRatio, 2) + '%'"></span>
                <span class="be-stat-sub">of revenue</span>
              </div>
            </div>

            {{-- Break-Even Chart --}}
            <div class="card p-4">
              <div class="flex items-center justify-between mb-3 flex-wrap gap-2">
                <p class="font-semibold text-gray-700 text-sm">Break-Even Chart</p>
                <div class="flex flex-wrap gap-3">
                  <span class="be-legend">
                    <span class="be-legend-dot" style="background:#94a3b8;height:2px;border-top:2px dashed #94a3b8"></span>
                    Fixed Cost
                  </span>
                  <span class="be-legend">
                    <span class="be-legend-dot" style="background:#0ea5e9"></span>
                    Total Cost
                  </span>
                  <span class="be-legend">
                    <span class="be-legend-dot" style="background:#16a34a"></span>
                    Revenue
                  </span>
                  <span class="be-legend">
                    <span class="be-legend-dot" style="background:#f59e0b;width:8px;height:8px;border-radius:50%"></span>
                    BEP
                  </span>
                </div>
              </div>
              <div class="be-chart-wrap">
                <svg viewBox="0 0 400 270" class="w-full" style="max-height:260px">
                  <!-- Axes -->
                  <line x1="40" y1="20" x2="40" y2="225" stroke="#cbd5e1" stroke-width="1.5"/>
                  <line x1="40" y1="225" x2="385" y2="225" stroke="#cbd5e1" stroke-width="1.5"/>

                  <!-- Y-axis grid lines and labels -->
                  <template x-for="t in result.chart.yTicks" :key="t.label">
                    <g>
                      <line :x1="40" :y1="t.y" x2="382" :y2="t.y"
                            stroke="#e0f2fe" stroke-width="1" stroke-dasharray="4 3"/>
                      <text :x="36" :y="parseFloat(t.y)+4" text-anchor="end"
                            font-size="8" fill="#94a3b8" x-text="t.label"></text>
                    </g>
                  </template>

                  <!-- X-axis labels -->
                  <template x-for="t in result.chart.xTicks" :key="t.label">
                    <text :x="t.x" y="238" text-anchor="middle"
                          font-size="8" fill="#94a3b8" x-text="t.label"></text>
                  </template>

                  <!-- Axis labels -->
                  <text x="212" y="252" text-anchor="middle" font-size="9" fill="#64748b" font-weight="600">Units</text>
                  <text x="12" y="125" text-anchor="middle" font-size="9" fill="#64748b" font-weight="600"
                        transform="rotate(-90,12,125)" x-text="'Revenue / Cost (' + currencySymbol + ')'"></text>

                  <!-- Loss zone (shaded) -->
                  <path :d="result.chart.lossZone" fill="rgba(239,68,68,0.10)" />

                  <!-- Profit zone (shaded) -->
                  <path :d="result.chart.profitZone" fill="rgba(34,197,94,0.10)" />

                  <!-- Zone labels -->
                  <text :x="result.chart.lossLabelX" :y="result.chart.lossLabelY"
                        text-anchor="middle" font-size="8" fill="#dc2626" font-weight="700" opacity="0.7">LOSS</text>
                  <text :x="result.chart.profitLabelX" :y="result.chart.profitLabelY"
                        text-anchor="middle" font-size="8" fill="#16a34a" font-weight="700" opacity="0.7">PROFIT</text>

                  <!-- Fixed cost line (dashed) -->
                  <path :d="result.chart.fcLinePath" fill="none" stroke="#94a3b8"
                        stroke-width="1.5" stroke-dasharray="6 4"/>

                  <!-- Total cost line -->
                  <path :d="result.chart.tcLinePath" fill="none" stroke="#0ea5e9" stroke-width="2"/>

                  <!-- Revenue line -->
                  <path :d="result.chart.revLinePath" fill="none" stroke="#16a34a" stroke-width="2"/>

                  <!-- BEP crosshairs (dashed amber) -->
                  <path :d="result.chart.bepVLinePath" fill="none" stroke="#f59e0b"
                        stroke-width="1" stroke-dasharray="4 3"/>
                  <path :d="result.chart.bepHLinePath" fill="none" stroke="#f59e0b"
                        stroke-width="1" stroke-dasharray="4 3"/>

                  <!-- BEP dot -->
                  <circle :cx="result.chart.bepX" :cy="result.chart.bepY"
                          r="5" fill="#f59e0b" stroke="#fff" stroke-width="1.5"/>

                  <!-- BEP label -->
                  <text :x="parseFloat(result.chart.bepX)+8" :y="parseFloat(result.chart.bepY)-6"
                        font-size="8" fill="#92400e" font-weight="700"
                        x-text="'BEP: ' + fmtInt(result.bepUnitsCeil) + ' units'"></text>
                </svg>
              </div>
            </div>

            {{-- Key metrics --}}
            <div class="card p-5">
              <p class="be-div mb-3">Key Metrics</p>
              <div class="space-y-0.5">
                <div class="be-metric-row">
                  <span class="text-gray-600">Break-Even Units (exact)</span>
                  <span class="font-bold text-gray-800" x-text="fmtNum(result.bepUnits,4) + ' units'"></span>
                </div>
                <div class="be-metric-row">
                  <span class="text-gray-600">Break-Even Units (rounded up)</span>
                  <span class="font-bold text-sky-700" x-text="fmtInt(result.bepUnitsCeil) + ' units'"></span>
                </div>
                <div class="be-metric-row">
                  <span class="text-gray-600">Break-Even Revenue</span>
                  <span class="font-bold text-sky-700" x-text="currencySymbol + fmtMoney(result.bepRevenue)"></span>
                </div>
                <div class="be-metric-row">
                  <span class="text-gray-600">Total Variable Cost at BEP</span>
                  <span class="font-bold text-gray-700" x-text="currencySymbol + fmtMoney(result.bepUnitsCeil * result.vc)"></span>
                </div>
                <div class="be-metric-row">
                  <span class="text-gray-600">Total Cost at BEP</span>
                  <span class="font-bold text-gray-700" x-text="currencySymbol + fmtMoney(result.fc + result.bepUnitsCeil * result.vc)"></span>
                </div>
                <div class="be-metric-row">
                  <span class="text-gray-600">Contribution Margin per Unit</span>
                  <span class="font-bold text-green-700" x-text="currencySymbol + fmtMoney(result.cmUnit)"></span>
                </div>
                <div class="be-metric-row">
                  <span class="text-gray-600">Contribution Margin Ratio</span>
                  <span class="font-bold text-green-700" x-text="fmtNum(result.cmRatio,2) + '%'"></span>
                </div>
              </div>
            </div>

            {{-- Target profit results --}}
            <div x-show="result.tpUnits !== null" class="card p-5 border border-amber-200 bg-amber-50">
              <p class="be-div mb-3" style="color:#92400e">🎯 Target Profit Analysis</p>
              <div class="grid grid-cols-3 gap-3 mb-3">
                <div class="bg-white rounded-xl p-3 text-center border border-amber-200">
                  <p class="text-xs text-gray-400 font-bold uppercase tracking-wide mb-1">Target Profit</p>
                  <p class="font-black text-amber-700 text-lg" x-text="currencySymbol + fmtMoney(result.targetProfit)"></p>
                </div>
                <div class="bg-white rounded-xl p-3 text-center border border-amber-200">
                  <p class="text-xs text-gray-400 font-bold uppercase tracking-wide mb-1">Units Needed</p>
                  <p class="font-black text-amber-700 text-lg" x-text="fmtInt(result.tpUnits)"></p>
                </div>
                <div class="bg-white rounded-xl p-3 text-center border border-amber-200">
                  <p class="text-xs text-gray-400 font-bold uppercase tracking-wide mb-1">Revenue Needed</p>
                  <p class="font-black text-amber-700 text-lg" x-text="currencySymbol + fmtCompact(result.tpRevenue)"></p>
                </div>
              </div>
              <p class="text-xs text-amber-700 text-center">
                <span x-text="fmtInt(result.tpAboveBEP)"></span> units above break-even point
                (<span x-text="currencySymbol + fmtMoney(result.targetProfit / result.tpAboveBEP)"></span> profit per unit above BEP)
              </p>
            </div>

            {{-- Profit / Loss scenario table --}}
            <div class="card p-5">
              <p class="be-div mb-3">Profit / Loss Scenarios</p>
              <div class="be-sc-row head">
                <span>Units Sold</span>
                <span>Revenue</span>
                <span>Total Cost</span>
                <span>Profit / Loss</span>
              </div>
              <template x-for="row in result.scenarios" :key="row.units">
                <div class="be-sc-row"
                     :class="row.isBEP ? 'bep' : (row.profit > 0 ? 'profit' : (row.profit < 0 ? 'loss' : ''))">
                  <span class="font-semibold" :style="row.isBEP ? 'color:#92400e' : ''">
                    <span x-text="fmtInt(row.units)"></span>
                    <span x-show="row.isBEP" class="text-xs ml-1 text-amber-600">★ BEP</span>
                  </span>
                  <span x-text="currencySymbol + fmtMoney(row.rev)"></span>
                  <span x-text="currencySymbol + fmtMoney(row.tc)"></span>
                  <span :class="row.profit > 0 ? 'text-green-700 font-bold' : (row.profit < 0 ? 'text-red-600 font-bold' : 'text-gray-500')"
                        x-text="(row.profit >= 0 ? '+' : '') + currencySymbol + fmtMoney(row.profit)"></span>
                </div>
              </template>
            </div>

          </div>
        </template>

      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function beCalc() {
  return {
    // ── State ─────────────────────────────────
    currency:          'USD',
    fixedCost:         '',
    varCostUnit:       '',
    sellPriceUnit:     '',
    showTargetProfit:  false,
    targetProfit:      '',
    phase:             'idle',
    errorMsg:          '',
    result:            null,

    // ── Currencies ────────────────────────────
    currencies: [
      { id:'USD', symbol:'$',  label:'USD ($)'  },
      { id:'EUR', symbol:'€',  label:'EUR (€)'  },
      { id:'GBP', symbol:'£',  label:'GBP (£)'  },
      { id:'CAD', symbol:'C$', label:'CAD (C$)' },
      { id:'AUD', symbol:'A$', label:'AUD (A$)' },
      { id:'JPY', symbol:'¥',  label:'JPY (¥)'  },
      { id:'INR', symbol:'₹',  label:'INR (₹)'  },
    ],

    get currencySymbol() {
      var c = this.currencies.find(function(x){ return x.id === this.currency; }, this);
      return c ? c.symbol : '$';
    },

    // ── Init ─────────────────────────────────
    init() {},

    // ── Actions ──────────────────────────────
    reset() {
      this.fixedCost        = '';
      this.varCostUnit      = '';
      this.sellPriceUnit    = '';
      this.targetProfit     = '';
      this.phase            = 'idle';
      this.errorMsg         = '';
      this.result           = null;
    },

    autoCompute() {
      var fc = parseFloat(this.fixedCost);
      var vc = parseFloat(this.varCostUnit);
      var sp = parseFloat(this.sellPriceUnit);
      if (isNaN(fc) || fc < 0)   return;
      if (isNaN(vc) || vc <= 0)  return;
      if (isNaN(sp) || sp <= vc) return;
      this.compute();
    },

    compute() {
      this.errorMsg = '';

      var fc = parseFloat(this.fixedCost);
      var vc = parseFloat(this.varCostUnit);
      var sp = parseFloat(this.sellPriceUnit);

      if (isNaN(fc) || fc < 0)   { this.errorMsg = 'Fixed Costs must be a positive number (can be 0 for no fixed costs).'; return; }
      if (isNaN(vc) || vc <= 0)  { this.errorMsg = 'Variable Cost Per Unit must be greater than zero.'; return; }
      if (isNaN(sp) || sp <= 0)  { this.errorMsg = 'Selling Price Per Unit must be greater than zero.'; return; }
      if (sp <= vc)               { this.errorMsg = 'Selling Price (' + this.currencySymbol + sp.toFixed(2) + ') must be greater than Variable Cost Per Unit (' + this.currencySymbol + vc.toFixed(2) + ').'; return; }

      if (this.showTargetProfit && this.targetProfit !== '') {
        var tp = parseFloat(this.targetProfit);
        if (isNaN(tp) || tp < 0) { this.errorMsg = 'Target Profit must be zero or greater.'; return; }
      }

      this.phase = 'loading';
      var self = this;
      setTimeout(function() {
        try {
          self._doCompute(fc, vc, sp);
          self.phase = 'done';
        } catch(e) {
          self.errorMsg = e.message;
          self.phase = 'idle';
        }
      }, 80);
    },

    _doCompute(fc, vc, sp) {
      // ── Core BEP calculations ──────────────
      var cmUnit    = sp - vc;
      var cmRatio   = (cmUnit / sp) * 100;
      var bepUnits  = fc / cmUnit;           // exact (may be fractional)
      var bepUnitsCeil = Math.ceil(bepUnits); // always round up
      var bepRevenue   = bepUnitsCeil * sp;

      // ── Target profit ──────────────────────
      var tpUnits = null, tpRevenue = null, tpAboveBEP = null, tpVal = null;
      if (this.showTargetProfit && this.targetProfit !== '') {
        tpVal      = parseFloat(this.targetProfit);
        tpUnits    = Math.ceil((fc + tpVal) / cmUnit);
        tpRevenue  = tpUnits * sp;
        tpAboveBEP = Math.max(1, tpUnits - bepUnitsCeil);
      }

      // ── Scenario table: 10 rows around BEP ─
      var steps = [0, 0.25, 0.5, 0.75, 0.9, 1.0, 1.1, 1.25, 1.5, 2.0];
      var scenarios = steps.map(function(pct) {
        var units = Math.round(bepUnitsCeil * pct);
        var rev   = units * sp;
        var tc    = fc + units * vc;
        var profit = rev - tc;
        return { units: units, rev: rev, tc: tc, profit: profit,
                 isBEP: Math.abs(units - bepUnitsCeil) < 0.5 && pct === 1.0 };
      });

      // ── SVG chart ──────────────────────────
      var chartMaxUnits   = bepUnitsCeil * 2.2;
      var chartMaxRevenue = chartMaxUnits * sp;
      var cW = 340, cH = 200;
      var cxL = 40, cxR = 380, cyT = 20, cyB = 225;

      var cx = function(u) { return (cxL + (u / chartMaxUnits) * cW).toFixed(1); };
      var cy = function(v) { return (cyB  - (v / chartMaxRevenue) * cH).toFixed(1); };

      var fcLinePath  = 'M ' + cx(0) + ',' + cy(fc) + ' L ' + cx(chartMaxUnits) + ',' + cy(fc);
      var tcLinePath  = 'M ' + cx(0) + ',' + cy(fc) + ' L ' + cx(chartMaxUnits) + ',' + cy(fc + chartMaxUnits * vc);
      var revLinePath = 'M ' + cx(0) + ',' + cy(0)  + ' L ' + cx(chartMaxUnits) + ',' + cy(chartMaxRevenue);

      var bepChartX = cx(bepUnits);
      var bepChartY = cy(bepUnits * sp);

      var bepVLinePath = 'M ' + bepChartX + ',' + cyB + ' L ' + bepChartX + ',' + bepChartY;
      var bepHLinePath = 'M ' + cxL + ',' + bepChartY + ' L ' + bepChartX + ',' + bepChartY;

      // Loss zone: triangle from (0,0) → BEP → (0,FC)
      var lossZone = 'M ' + cx(0) + ',' + cy(0) +
                     ' L ' + bepChartX + ',' + bepChartY +
                     ' L ' + cx(0) + ',' + cy(fc) + ' Z';

      // Profit zone: BEP → end of revenue → end of TC
      var profitZone = 'M ' + bepChartX + ',' + bepChartY +
                       ' L ' + cx(chartMaxUnits) + ',' + cy(chartMaxRevenue) +
                       ' L ' + cx(chartMaxUnits) + ',' + cy(fc + chartMaxUnits * vc) + ' Z';

      // Zone label positions
      var lossLabelX  = (parseFloat(cx(0)) + parseFloat(bepChartX)) / 2;
      var lossLabelY  = (parseFloat(cy(0)) + parseFloat(cy(fc))) / 2;
      var profitLabelX = (parseFloat(bepChartX) + parseFloat(cx(chartMaxUnits))) / 2;
      var profitLabelY = (parseFloat(bepChartY) + parseFloat(cy(fc + chartMaxUnits * vc))) / 2;

      // Y-axis ticks: 5 evenly spaced
      var yTicks = [0, 0.25, 0.5, 0.75, 1.0].map(function(f) {
        var val = chartMaxRevenue * f;
        return { y: cy(val), label: beCalcFmtCompact(val) };
      });

      // X-axis ticks: 5 evenly spaced
      var xTicks = [0, 0.25, 0.5, 0.75, 1.0].map(function(f) {
        var val = chartMaxUnits * f;
        return { x: cx(val), label: beCalcFmtCompact(val) };
      });

      this.result = {
        fc: fc, vc: vc, sp: sp,
        cmUnit: cmUnit, cmRatio: cmRatio,
        bepUnits: bepUnits, bepUnitsCeil: bepUnitsCeil, bepRevenue: bepRevenue,
        tpUnits: tpUnits, tpRevenue: tpRevenue, tpAboveBEP: tpAboveBEP, targetProfit: tpVal,
        scenarios: scenarios,
        chart: {
          fcLinePath: fcLinePath,
          tcLinePath: tcLinePath,
          revLinePath: revLinePath,
          bepX: bepChartX, bepY: bepChartY,
          bepVLinePath: bepVLinePath,
          bepHLinePath: bepHLinePath,
          lossZone: lossZone,
          profitZone: profitZone,
          lossLabelX: lossLabelX, lossLabelY: lossLabelY,
          profitLabelX: profitLabelX, profitLabelY: profitLabelY,
          yTicks: yTicks,
          xTicks: xTicks,
        },
      };
    },

    // ── Formatters ────────────────────────────
    fmtMoney(v) {
      if (!isFinite(v)) return '0.00';
      return parseFloat(v.toFixed(2)).toLocaleString('en-US', { minimumFractionDigits:2, maximumFractionDigits:2 });
    },
    fmtNum(v, d) {
      if (!isFinite(v)) return '0';
      return parseFloat(v.toFixed(d || 2)).toLocaleString('en-US', { minimumFractionDigits:0, maximumFractionDigits: d || 2 });
    },
    fmtInt(v) {
      if (!isFinite(v)) return '0';
      return Math.round(v).toLocaleString('en-US');
    },
    fmtCompact(v) {
      return beCalcFmtCompact(v);
    },
  };
}

// Standalone compact formatter (used inside chart builder too)
function beCalcFmtCompact(v) {
  if (!isFinite(v) || v === 0) return '0';
  var abs = Math.abs(v);
  if (abs >= 1e9) return (v / 1e9).toFixed(1).replace(/\.0$/, '') + 'B';
  if (abs >= 1e6) return (v / 1e6).toFixed(1).replace(/\.0$/, '') + 'M';
  if (abs >= 1e3) return (v / 1e3).toFixed(1).replace(/\.0$/, '') + 'K';
  return parseFloat(v.toFixed(1)).toString();
}
</script>
@endpush
