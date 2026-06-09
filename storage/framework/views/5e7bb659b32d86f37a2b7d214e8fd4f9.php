<?php $__env->startSection('title', $tool->name . ' - ' . config('app.name')); ?>
<?php $__env->startSection('meta_description', $tool->description ?? 'Calculate profit margin, markup, and revenue instantly for any product or service.'); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ══════════════════════════════════════════════
   Profit Margin Calculator  —  prefix: pm-
   Theme: Emerald Green (money / profit)
══════════════════════════════════════════════ */

/* Hero profit number */
.pm-hero { font-size:clamp(2.8rem,6vw,4.5rem); font-weight:900; line-height:1; letter-spacing:-.04em; }
.pm-hero.profit { background:linear-gradient(135deg,#14532d,#16a34a,#22c55e);
                   -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.pm-hero.loss   { background:linear-gradient(135deg,#7f1d1d,#dc2626,#ef4444);
                   -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }

/* Mode tab */
.pm-tab      { flex:1; padding:.5rem .6rem; border-radius:.5rem; font-size:.75rem; font-weight:700;
                color:#374151; cursor:pointer; border:none; background:none; transition:all .15s; text-align:center; line-height:1.3; }
.pm-tab.active { background:#fff; color:#15803d; box-shadow:0 1px 4px rgba(0,0,0,.1); }
.pm-tab:not(.active):hover { background:#f0fdf4; }

/* Stat card */
.pm-stat { background:#fff; border:1.5px solid #bbf7d0; border-radius:1.125rem;
            padding:1rem .9rem; display:flex; flex-direction:column; align-items:center;
            gap:.3rem; text-align:center; transition:all .15s; }
.pm-stat:hover { transform:translateY(-1px); box-shadow:0 4px 16px rgba(22,163,74,.08); }
.pm-stat-lbl { font-size:.6rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.08em; }
.pm-stat-val { font-size:1.2rem; font-weight:900; line-height:1.1; }
.pm-stat-val.green  { background:linear-gradient(135deg,#14532d,#16a34a);
                       -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.pm-stat-val.red    { background:linear-gradient(135deg,#7f1d1d,#dc2626);
                       -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.pm-stat-val.slate  { background:linear-gradient(135deg,#1e293b,#475569);
                       -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.pm-stat-val.sky    { background:linear-gradient(135deg,#0369a1,#0ea5e9);
                       -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.pm-stat-val.violet { background:linear-gradient(135deg,#5b21b6,#7c3aed);
                       -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.pm-stat-sub { font-size:.62rem; color:#94a3b8; }

/* Revenue split bar */
.pm-split-bar   { height:28px; border-radius:9999px; overflow:hidden; display:flex; background:#f1f5f9; }
.pm-split-cost  { background:#94a3b8; transition:width .6s ease; display:flex; align-items:center; justify-content:center; }
.pm-split-profit { background:linear-gradient(90deg,#16a34a,#22c55e); transition:width .6s ease; display:flex; align-items:center; justify-content:center; }
.pm-split-loss  { background:linear-gradient(90deg,#dc2626,#ef4444); transition:width .6s ease; display:flex; align-items:center; justify-content:center; }
.pm-split-lbl   { font-size:.62rem; font-weight:700; color:#fff; white-space:nowrap; padding:0 .4rem; }

/* Margin zone badge */
.pm-zone { display:inline-flex; align-items:center; gap:.4rem; padding:.3rem .8rem;
            border-radius:9999px; font-size:.75rem; font-weight:800; letter-spacing:.02em; }
.pm-zone.low       { background:#fee2e2; color:#991b1b; }
.pm-zone.average   { background:#fef3c7; color:#92400e; }
.pm-zone.good      { background:#dcfce7; color:#14532d; }
.pm-zone.excellent { background:#166534; color:#bbf7d0; }

/* What-if table */
.pm-wi-row { display:grid; grid-template-columns:.8fr 1.1fr 1.1fr 1fr; gap:.5rem;
              align-items:center; padding:.45rem .75rem; border-radius:.625rem; font-size:.78rem; }
.pm-wi-row:hover { background:#f0fdf4; }
.pm-wi-row.head { font-size:.6rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.07em; }
.pm-wi-row.current { background:#dcfce7; border:1px solid #86efac; font-weight:700; }
.pm-wi-row.loss-row { background:#fef2f2; }

/* Input with currency prefix */
.pm-cur-wrap { display:flex; align-items:stretch; }
.pm-cur-pre  { display:flex; align-items:center; padding:0 .75rem; background:#f0fdf4;
                border:1px solid #d1d5db; border-right:none; border-radius:.75rem 0 0 .75rem;
                font-size:.9rem; font-weight:800; color:#15803d; white-space:nowrap; min-width:2rem; justify-content:center; }
.pm-cur-wrap .form-input { border-radius:0 .75rem .75rem 0 !important; }

/* Pct suffix */
.pm-pct-wrap { display:flex; align-items:stretch; }
.pm-pct-suf  { display:flex; align-items:center; padding:0 .75rem; background:#f0fdf4;
                border:1px solid #d1d5db; border-left:none; border-radius:0 .75rem .75rem 0;
                font-size:.85rem; font-weight:700; color:#15803d; }
.pm-pct-wrap .form-input { border-radius:.75rem 0 0 .75rem !important; }

/* Section divider */
.pm-div { display:flex; align-items:center; gap:.6rem; color:#94a3b8; font-size:.62rem;
           font-weight:800; text-transform:uppercase; letter-spacing:.1em; }
.pm-div::before,.pm-div::after { content:''; flex:1; height:1px; background:#d1fae5; }

/* Industry badge */
.pm-ind-row { display:flex; align-items:center; justify-content:space-between; gap:.5rem;
               padding:.4rem .6rem; border-radius:.5rem; font-size:.78rem; }
.pm-ind-row:hover { background:#f0fdf4; }

/* Animate in */
@keyframes pmIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
.pm-in { animation:pmIn .3s ease-out; }
</style>

<div class="min-h-screen bg-gray-50 py-8" x-data="pmCalc()" x-init="init()">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4"
           style="background:linear-gradient(135deg,#15803d,#16a34a)">
        <span class="text-3xl">💰</span>
      </div>
      <h1 class="text-3xl font-bold text-gray-900">Profit Margin Calculator</h1>
      <p class="mt-2 text-gray-500">Calculate profit, margin %, and markup % — with what-if pricing analysis</p>
    </div>

    
    <div x-show="errorMsg" x-transition
         class="mb-6 p-4 rounded-xl border border-red-200 bg-red-50 text-red-700 text-sm flex items-start gap-2">
      <span class="text-lg leading-none">⚠️</span>
      <span x-text="errorMsg"></span>
    </div>

    <div class="lg:grid lg:grid-cols-5 lg:gap-8 space-y-6 lg:space-y-0">

      
      <div class="lg:col-span-2 space-y-5">

        
        <div class="card p-4">
          <label class="form-label">Currency</label>
          <select x-model="currency" class="form-input w-full">
            <template x-for="c in currencies" :key="c.id">
              <option :value="c.id" x-text="c.label"></option>
            </template>
          </select>
        </div>

        
        <div class="card p-4">
          <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Calculation Mode</p>
          <div class="flex bg-green-50 p-1.5 rounded-xl gap-1">
            <button class="pm-tab" :class="mode==='cs'  ? 'active':''" @click="setMode('cs')">
              Cost &amp;<br>Revenue
            </button>
            <button class="pm-tab" :class="mode==='cm'  ? 'active':''" @click="setMode('cm')">
              Cost &amp;<br>Margin %
            </button>
            <button class="pm-tab" :class="mode==='ck'  ? 'active':''" @click="setMode('ck')">
              Cost &amp;<br>Markup %
            </button>
            <button class="pm-tab" :class="mode==='sm'  ? 'active':''" @click="setMode('sm')">
              Revenue &amp;<br>Margin %
            </button>
          </div>
          <p class="text-xs text-gray-400 mt-2 text-center" x-text="modeHint"></p>
        </div>

        
        <div class="card p-5 space-y-4">
          <h2 class="font-semibold text-gray-800">Enter Values</h2>

          
          <div x-show="mode !== 'sm'">
            <label class="form-label">Cost Price</label>
            <div class="pm-cur-wrap">
              <span class="pm-cur-pre" x-text="currencySymbol"></span>
              <input type="number" step="any" min="0"
                     x-model="cost"
                     placeholder="e.g. 50.00"
                     @input.debounce.500ms="autoCompute()"
                     class="form-input flex-1" />
            </div>
            <p class="text-xs text-gray-400 mt-1">The total cost to produce or acquire the item</p>
          </div>

          
          <div x-show="mode === 'cs'">
            <label class="form-label">Selling Price (Revenue)</label>
            <div class="pm-cur-wrap">
              <span class="pm-cur-pre" x-text="currencySymbol"></span>
              <input type="number" step="any" min="0"
                     x-model="sellPrice"
                     placeholder="e.g. 80.00"
                     @input.debounce.500ms="autoCompute()"
                     class="form-input flex-1" />
            </div>
          </div>

          
          <div x-show="mode === 'sm'">
            <label class="form-label">Selling Price (Revenue)</label>
            <div class="pm-cur-wrap">
              <span class="pm-cur-pre" x-text="currencySymbol"></span>
              <input type="number" step="any" min="0"
                     x-model="sellPrice"
                     placeholder="e.g. 100.00"
                     @input.debounce.500ms="autoCompute()"
                     class="form-input flex-1" />
            </div>
          </div>

          
          <div x-show="mode === 'cm' || mode === 'sm'">
            <label class="form-label" x-text="mode === 'sm' ? 'Target Profit Margin %' : 'Target Profit Margin %'"></label>
            <div class="pm-pct-wrap">
              <input type="number" step="any" min="0" max="99.99"
                     x-model="targetMargin"
                     placeholder="e.g. 30"
                     @input.debounce.500ms="autoCompute()"
                     class="form-input flex-1" />
              <span class="pm-pct-suf">%</span>
            </div>
            <p class="text-xs text-gray-400 mt-1">Must be between 0% and 100%</p>
          </div>

          
          <div x-show="mode === 'ck'">
            <label class="form-label">Target Markup %</label>
            <div class="pm-pct-wrap">
              <input type="number" step="any" min="0"
                     x-model="targetMarkup"
                     placeholder="e.g. 60"
                     @input.debounce.500ms="autoCompute()"
                     class="form-input flex-1" />
              <span class="pm-pct-suf">%</span>
            </div>
            <p class="text-xs text-gray-400 mt-1">Percentage above cost price</p>
          </div>
        </div>

        
        <div class="flex gap-3">
          <button @click="compute()"
                  class="btn btn-primary flex-1 py-3 font-bold text-base"
                  style="background:linear-gradient(135deg,#15803d,#16a34a)">
            Calculate
          </button>
          <button @click="reset()"
                  class="btn btn-secondary py-3 px-5 font-semibold">
            Reset
          </button>
        </div>

        
        <div class="card p-4 bg-green-50 border border-green-100">
          <p class="text-xs font-bold text-green-800 uppercase tracking-wide mb-2">📖 Margin vs Markup</p>
          <p class="text-xs text-green-700 mb-1.5">
            <strong>Profit Margin</strong> = Profit ÷ <em>Revenue</em> × 100<br>
            <span class="text-green-500">(% of selling price that is profit)</span>
          </p>
          <p class="text-xs text-green-700">
            <strong>Markup</strong> = Profit ÷ <em>Cost</em> × 100<br>
            <span class="text-green-500">(% added on top of cost price)</span>
          </p>
        </div>

      </div>

      
      <div class="lg:col-span-3 space-y-5">

        
        <div x-show="phase==='idle'" class="card p-12 text-center text-gray-400">
          <div class="text-5xl mb-4">💰</div>
          <p class="font-medium">Enter values and press Calculate</p>
          <p class="text-sm mt-1">Profit, margin %, markup % and more will appear here</p>
        </div>

        
        <div x-show="phase==='loading'" class="card p-12 text-center">
          <div class="inline-block w-7 h-7 border-4 border-green-200 border-t-green-600 rounded-full animate-spin mb-3"></div>
          <p class="text-gray-500 text-sm">Calculating…</p>
        </div>

        <template x-if="phase==='done'">
          <div class="space-y-5 pm-in">

            
            <div class="rounded-2xl p-6"
                 :style="result.profit >= 0
                   ? 'background:linear-gradient(135deg,#14532d,#15803d,#16a34a)'
                   : 'background:linear-gradient(135deg,#7f1d1d,#dc2626,#ef4444)'">
              <div class="flex items-start justify-between flex-wrap gap-4">
                <div>
                  <p class="text-sm font-medium mb-1" :class="result.profit >= 0 ? 'text-green-200' : 'text-red-200'"
                     x-text="result.profit >= 0 ? 'Gross Profit' : 'Loss'"></p>
                  <div class="flex items-end gap-2">
                    <span class="text-4xl font-black tracking-tight text-white"
                          x-text="currencySymbol + fmtMoney(Math.abs(result.profit))"></span>
                    <span class="font-semibold mb-1.5"
                          :class="result.profit >= 0 ? 'text-green-200' : 'text-red-200'"
                          x-text="result.profit < 0 ? '(net loss)' : ''"></span>
                  </div>
                  <p class="text-sm mt-1" :class="result.profit >= 0 ? 'text-green-200' : 'text-red-200'">
                    Selling Price − Cost = <span class="font-semibold text-white"
                      x-text="currencySymbol + fmtMoney(result.sellPrice) + ' − ' + currencySymbol + fmtMoney(result.cost)"></span>
                  </p>
                </div>
                <div class="text-right flex-shrink-0">
                  <div class="pm-zone" :class="result.zoneClass" x-text="result.zoneLabel"></div>
                  <p class="text-white text-2xl font-black mt-2" x-text="fmtPct(result.margin) + '%'"></p>
                  <p class="text-xs mt-0.5" :class="result.profit >= 0 ? 'text-green-200' : 'text-red-200'">profit margin</p>
                </div>
              </div>
            </div>

            
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
              <div class="pm-stat">
                <span class="pm-stat-lbl">Cost</span>
                <span class="pm-stat-val slate" x-text="currencySymbol + fmtMoney(result.cost)"></span>
                <span class="pm-stat-sub">cost price</span>
              </div>
              <div class="pm-stat">
                <span class="pm-stat-lbl">Revenue</span>
                <span class="pm-stat-val sky" x-text="currencySymbol + fmtMoney(result.sellPrice)"></span>
                <span class="pm-stat-sub">selling price</span>
              </div>
              <div class="pm-stat">
                <span class="pm-stat-lbl">Margin</span>
                <span class="pm-stat-val" :class="result.profit >= 0 ? 'green' : 'red'"
                      x-text="fmtPct(result.margin) + '%'"></span>
                <span class="pm-stat-sub">profit / revenue</span>
              </div>
              <div class="pm-stat">
                <span class="pm-stat-lbl">Markup</span>
                <span class="pm-stat-val violet" x-text="fmtPct(result.markup) + '%'"></span>
                <span class="pm-stat-sub">profit / cost</span>
              </div>
            </div>

            
            <div class="card p-5">
              <p class="pm-div mb-4">Revenue Breakdown</p>
              <div class="pm-split-bar mb-3">
                <div class="pm-split-cost" :style="'width:' + result.costPct + '%'">
                  <span class="pm-split-lbl" x-show="result.costPct > 12"
                        x-text="fmtPct(result.costPct) + '% cost'"></span>
                </div>
                <div :class="result.profit >= 0 ? 'pm-split-profit' : 'pm-split-loss'"
                     :style="'width:' + Math.abs(result.profitPct) + '%'">
                  <span class="pm-split-lbl" x-show="Math.abs(result.profitPct) > 10"
                        x-text="fmtPct(Math.abs(result.profitPct)) + '%'"></span>
                </div>
              </div>
              <div class="flex justify-between text-xs text-gray-500 font-medium">
                <span>
                  <span class="inline-block w-3 h-3 rounded-sm bg-slate-400 mr-1 align-middle"></span>
                  Cost: <strong x-text="currencySymbol + fmtMoney(result.cost)"></strong>
                  (<span x-text="fmtPct(result.costPct) + '%'"></span>)
                </span>
                <span>
                  <span class="inline-block w-3 h-3 rounded-sm mr-1 align-middle"
                        :class="result.profit >= 0 ? 'bg-green-500' : 'bg-red-500'"></span>
                  Profit: <strong x-text="currencySymbol + fmtMoney(Math.abs(result.profit))"></strong>
                  (<span x-text="fmtPct(Math.abs(result.profitPct)) + '%'"></span>)
                </span>
              </div>
            </div>

            
            <div class="card p-5">
              <p class="pm-div mb-3">Key Metrics</p>
              <div class="space-y-0.5">
                <div class="pm-ind-row">
                  <span class="text-gray-600">Revenue-to-Cost Ratio</span>
                  <span class="font-bold text-gray-800" x-text="fmtNum(result.revenueRatio) + '×'"></span>
                </div>
                <div class="pm-ind-row">
                  <span class="text-gray-600">Cost as % of Revenue</span>
                  <span class="font-bold text-gray-800" x-text="fmtPct(result.costPct) + '%'"></span>
                </div>
                <div class="pm-ind-row">
                  <span class="text-gray-600">Markup (on cost)</span>
                  <span class="font-bold text-green-700" x-text="fmtPct(result.markup) + '%'"></span>
                </div>
                <div class="pm-ind-row">
                  <span class="text-gray-600">Profit per unit</span>
                  <span class="font-bold" :class="result.profit >= 0 ? 'text-green-700':'text-red-600'"
                        x-text="currencySymbol + fmtMoney(result.profit)"></span>
                </div>
                <div class="pm-ind-row">
                  <span class="text-gray-600">Profit at 100 units</span>
                  <span class="font-bold" :class="result.profit >= 0 ? 'text-green-700':'text-red-600'"
                        x-text="currencySymbol + fmtMoney(result.profit * 100)"></span>
                </div>
                <div class="pm-ind-row">
                  <span class="text-gray-600">Profit at 1,000 units</span>
                  <span class="font-bold" :class="result.profit >= 0 ? 'text-green-700':'text-red-600'"
                        x-text="currencySymbol + fmtMoney(result.profit * 1000)"></span>
                </div>
              </div>
            </div>

            
            <div class="card p-5">
              <p class="pm-div mb-3">What-If Pricing Table</p>
              <p class="text-xs text-gray-400 mb-3">Required selling price for different margin targets (cost = <span x-text="currencySymbol + fmtMoney(result.cost)"></span>)</p>
              <div class="pm-wi-row head">
                <span>Margin</span>
                <span>Sell Price</span>
                <span>Profit</span>
                <span>Markup</span>
              </div>
              <template x-for="row in result.whatIf" :key="row.margin">
                <div class="pm-wi-row" :class="row.isCurrent ? 'current' : (row.profit < 0 ? 'loss-row' : '')">
                  <span class="font-semibold" :style="row.isCurrent ? 'color:#15803d' : ''">
                    <span x-text="row.margin + '%'"></span>
                    <span x-show="row.isCurrent" class="text-xs ml-1 text-green-600">◀</span>
                  </span>
                  <span x-text="currencySymbol + fmtMoney(row.sellPrice)"></span>
                  <span :class="row.profit >= 0 ? 'text-green-700' : 'text-red-600'"
                        x-text="currencySymbol + fmtMoney(row.profit)"></span>
                  <span x-text="fmtPct(row.markup) + '%'"></span>
                </div>
              </template>
            </div>

            
            <div class="card p-5">
              <button @click="showIndustry = !showIndustry"
                      class="w-full flex items-center justify-between text-left">
                <span class="font-semibold text-gray-700">🏭 Industry Margin Benchmarks</span>
                <span class="text-gray-400 text-lg" x-text="showIndustry ? '−' : '+'"></span>
              </button>
              <div x-show="showIndustry" x-transition class="mt-4 space-y-1">
                <template x-for="ind in industries" :key="ind.name">
                  <div class="pm-ind-row">
                    <span class="text-gray-700" x-text="ind.name"></span>
                    <div class="flex items-center gap-2">
                      <div class="w-20 h-2 rounded-full bg-gray-100 overflow-hidden">
                        <div class="h-full rounded-full"
                             :style="'width:' + (ind.max/80*100) + '%;background:' + ind.color"></div>
                      </div>
                      <span class="text-xs font-bold text-gray-600" x-text="ind.range"></span>
                    </div>
                  </div>
                </template>
                <p class="text-xs text-gray-400 pt-2 border-t border-gray-100 mt-2">
                  Benchmarks are gross profit margin ranges. Net margins will vary after operating expenses.
                </p>
              </div>
            </div>

          </div>
        </template>

      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function pmCalc() {
  return {
    // ── State ──────────────────────────────────
    currency:     'USD',
    mode:         'cs',   // cs | cm | ck | sm
    cost:         '',
    sellPrice:    '',
    targetMargin: '',
    targetMarkup: '',
    phase:        'idle',
    errorMsg:     '',
    result:       null,
    showIndustry: false,

    // ── Config ─────────────────────────────────
    currencies: [
      { id:'USD', symbol:'$',  label:'USD ($)' },
      { id:'EUR', symbol:'€',  label:'EUR (€)' },
      { id:'GBP', symbol:'£',  label:'GBP (£)' },
      { id:'CAD', symbol:'C$', label:'CAD (C$)' },
      { id:'AUD', symbol:'A$', label:'AUD (A$)' },
      { id:'JPY', symbol:'¥',  label:'JPY (¥)' },
      { id:'INR', symbol:'₹',  label:'INR (₹)' },
      { id:'CNY', symbol:'¥',  label:'CNY (¥)' },
    ],

    modeHints: {
      cs: 'Enter cost & selling price to get all metrics',
      cm: 'Enter cost & target margin to find the required price',
      ck: 'Enter cost & markup % to find the required price',
      sm: 'Enter selling price & margin to find the cost',
    },

    industries: [
      { name:'Software / SaaS',    range:'60–80%',   max:80,  color:'#16a34a' },
      { name:'Financial Services', range:'30–50%',   max:50,  color:'#0ea5e9' },
      { name:'Professional Serv.', range:'25–45%',   max:45,  color:'#8b5cf6' },
      { name:'Retail (General)',   range:'20–45%',   max:45,  color:'#f59e0b' },
      { name:'Manufacturing',      range:'10–25%',   max:25,  color:'#f97316' },
      { name:'Construction',       range:'5–15%',    max:15,  color:'#78716c' },
      { name:'Restaurants',        range:'3–9%',     max:9,   color:'#ef4444' },
      { name:'Grocery / Food',     range:'1–5%',     max:5,   color:'#94a3b8' },
    ],

    // ── Computed ───────────────────────────────
    get currencySymbol() {
      var c = this.currencies.find(function(x){ return x.id === this.currency; }, this);
      return c ? c.symbol : '$';
    },

    get modeHint() {
      return this.modeHints[this.mode] || '';
    },

    // ── Init ──────────────────────────────────
    init() {},

    // ── Actions ──────────────────────────────
    setMode(m) {
      this.mode = m;
      this.phase = 'idle';
      this.errorMsg = '';
    },

    reset() {
      this.cost         = '';
      this.sellPrice    = '';
      this.targetMargin = '';
      this.targetMarkup = '';
      this.phase        = 'idle';
      this.errorMsg     = '';
      this.result       = null;
    },

    autoCompute() {
      // Auto-compute only when all relevant fields are filled
      var self = this;
      var ready = false;
      if (self.mode === 'cs') {
        ready = self.cost !== '' && self.sellPrice !== '' &&
                parseFloat(self.cost) > 0 && parseFloat(self.sellPrice) > 0;
      } else if (self.mode === 'cm' || self.mode === 'sm') {
        var pctField = self.mode === 'sm' ? self.sellPrice : self.cost;
        ready = pctField !== '' && self.targetMargin !== '' &&
                parseFloat(pctField) > 0 && parseFloat(self.targetMargin) >= 0;
      } else if (self.mode === 'ck') {
        ready = self.cost !== '' && self.targetMarkup !== '' &&
                parseFloat(self.cost) > 0 && parseFloat(self.targetMarkup) >= 0;
      }
      if (ready) self.compute();
    },

    compute() {
      this.errorMsg = '';

      // ── Validation per mode ───────────────
      if (this.mode === 'cs') {
        var c = parseFloat(this.cost);
        var s = parseFloat(this.sellPrice);
        if (isNaN(c) || c <= 0) { this.errorMsg = 'Please enter a valid Cost Price greater than zero.'; return; }
        if (isNaN(s) || s <= 0) { this.errorMsg = 'Please enter a valid Selling Price greater than zero.'; return; }

      } else if (this.mode === 'cm') {
        var c = parseFloat(this.cost);
        var m = parseFloat(this.targetMargin);
        if (isNaN(c) || c <= 0)   { this.errorMsg = 'Please enter a valid Cost Price greater than zero.'; return; }
        if (isNaN(m) || m < 0)    { this.errorMsg = 'Margin % must be zero or greater.'; return; }
        if (m >= 100)              { this.errorMsg = 'Margin % must be less than 100%.'; return; }

      } else if (this.mode === 'ck') {
        var c = parseFloat(this.cost);
        var k = parseFloat(this.targetMarkup);
        if (isNaN(c) || c <= 0)  { this.errorMsg = 'Please enter a valid Cost Price greater than zero.'; return; }
        if (isNaN(k) || k < 0)   { this.errorMsg = 'Markup % must be zero or greater.'; return; }

      } else if (this.mode === 'sm') {
        var s = parseFloat(this.sellPrice);
        var m = parseFloat(this.targetMargin);
        if (isNaN(s) || s <= 0)  { this.errorMsg = 'Please enter a valid Selling Price greater than zero.'; return; }
        if (isNaN(m) || m < 0)   { this.errorMsg = 'Margin % must be zero or greater.'; return; }
        if (m >= 100)             { this.errorMsg = 'Margin % must be less than 100%.'; return; }
      }

      this.phase = 'loading';
      var self = this;
      setTimeout(function() {
        try {
          self._doCompute();
          self.phase = 'done';
        } catch(e) {
          self.errorMsg = e.message;
          self.phase = 'idle';
        }
      }, 80);
    },

    _doCompute() {
      var cost, sellPrice, profit, margin, markup;

      // ── Derive cost & sellPrice from mode ──
      if (this.mode === 'cs') {
        cost      = parseFloat(this.cost);
        sellPrice = parseFloat(this.sellPrice);

      } else if (this.mode === 'cm') {
        cost   = parseFloat(this.cost);
        margin = parseFloat(this.targetMargin);
        // Sell = Cost / (1 - Margin/100)
        sellPrice = cost / (1 - margin / 100);

      } else if (this.mode === 'ck') {
        cost   = parseFloat(this.cost);
        markup = parseFloat(this.targetMarkup);
        // Sell = Cost × (1 + Markup/100)
        sellPrice = cost * (1 + markup / 100);

      } else if (this.mode === 'sm') {
        sellPrice = parseFloat(this.sellPrice);
        margin    = parseFloat(this.targetMargin);
        // Cost = Sell × (1 - Margin/100)
        cost = sellPrice * (1 - margin / 100);
      }

      profit = sellPrice - cost;
      margin = (profit / sellPrice) * 100;
      markup = cost > 0 ? (profit / cost) * 100 : 0;

      // Revenue split percentages (of sell price)
      var costPct   = (cost / sellPrice) * 100;
      var profitPct = (profit / sellPrice) * 100;

      // Revenue-to-cost ratio
      var revenueRatio = cost > 0 ? sellPrice / cost : 0;

      // ── Zone classification ──────────────
      var zoneClass, zoneLabel;
      if (margin < 0)       { zoneClass = 'low';       zoneLabel = '📉 Loss';      }
      else if (margin < 5)  { zoneClass = 'low';       zoneLabel = '⚠ Very Low';   }
      else if (margin < 15) { zoneClass = 'average';   zoneLabel = '~ Average';    }
      else if (margin < 30) { zoneClass = 'good';      zoneLabel = '✓ Good';       }
      else                  { zoneClass = 'excellent'; zoneLabel = '★ Excellent';  }

      // ── What-if table ────────────────────
      // Show required sell price at various margin targets for the same cost
      var whatIfMargins = [-10, 0, 5, 10, 15, 20, 25, 30, 35, 40, 50, 60];
      var roundedCurrentMargin = Math.round(margin * 10) / 10;
      var currentInList = whatIfMargins.some(function(m) { return m === Math.round(roundedCurrentMargin); });
      if (!currentInList && isFinite(margin)) {
        whatIfMargins.push(Math.round(margin));
        whatIfMargins.sort(function(a, b) { return a - b; });
      }

      var self = this;
      var whatIf = whatIfMargins.map(function(m) {
        var sp, pr, mk;
        if (m >= 100) return null;
        if (m === 0) { sp = cost; pr = 0; mk = 0; }
        else { sp = cost / (1 - m / 100); pr = sp - cost; mk = cost > 0 ? (pr / cost) * 100 : 0; }
        var isCurrent = Math.abs(margin - m) < 0.05;
        return { margin: m, sellPrice: sp, profit: pr, markup: mk, isCurrent: isCurrent };
      }).filter(function(r) { return r !== null; });

      this.result = {
        cost, sellPrice, profit, margin, markup,
        costPct, profitPct,
        revenueRatio,
        zoneClass, zoneLabel,
        whatIf,
      };
    },

    // ── Formatters ────────────────────────────
    fmtMoney(v) {
      if (v === undefined || v === null || !isFinite(v)) return '0.00';
      return Math.abs(v) >= 1000
        ? v.toLocaleString('en-US', { minimumFractionDigits:2, maximumFractionDigits:2 })
        : parseFloat(v.toFixed(2)).toLocaleString('en-US', { minimumFractionDigits:2, maximumFractionDigits:2 });
    },

    fmtPct(v) {
      if (!isFinite(v)) return '0.00';
      return parseFloat(v.toFixed(2)).toLocaleString('en-US', { minimumFractionDigits:2, maximumFractionDigits:2 });
    },

    fmtNum(v) {
      if (!isFinite(v)) return '0.00';
      return parseFloat(v.toFixed(4)).toLocaleString('en-US', { minimumFractionDigits:2, maximumFractionDigits:4 });
    },
  };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\profit-margin-calculator.blade.php ENDPATH**/ ?>