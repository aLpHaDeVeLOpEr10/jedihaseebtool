<?php $__env->startSection('title', $tool->name . ' - ' . config('app.name')); ?>
<?php $__env->startSection('meta_description', $tool->description ?? 'Calculate concrete volume for slabs, columns, walls, footings and more.'); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ══════════════════════════════════════════════
   Concrete Calculator  —  prefix: cc-
   Theme: Amber / Stone (construction)
══════════════════════════════════════════════ */

/* Hero */
.cc-hero-vol { font-size:clamp(2.8rem,6vw,4.5rem); font-weight:900; line-height:1; letter-spacing:-.04em;
               background:linear-gradient(135deg,#92400e 0%,#b45309 50%,#d97706 100%);
               -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.cc-hero-sub { font-size:.95rem; font-weight:700; color:#b45309; }

/* Shape selector card */
.cc-shape-card { display:flex; flex-direction:column; align-items:center; gap:.35rem; padding:.7rem .4rem;
                  border:2px solid #e7e5e4; border-radius:.875rem; cursor:pointer; background:#fff;
                  transition:all .15s; text-align:center; }
.cc-shape-card:hover  { border-color:#fcd34d; background:#fffbeb; transform:translateY(-1px); }
.cc-shape-card.active { border-color:#d97706; background:linear-gradient(135deg,#fffbeb,#fef3c7);
                         box-shadow:0 4px 14px rgba(217,119,6,.18); }
.cc-shape-icon { font-size:1.5rem; line-height:1; }
.cc-shape-lbl  { font-size:.62rem; font-weight:700; color:#6b7280; text-transform:uppercase;
                  letter-spacing:.06em; line-height:1.2; }
.cc-shape-card.active .cc-shape-lbl { color:#92400e; }

/* Unit pill */
.cc-unit-pill    { display:flex; background:#f5f5f4; border-radius:.625rem; padding:.2rem; gap:.15rem; }
.cc-unit-btn     { flex:1; padding:.38rem .9rem; border-radius:.45rem; font-size:.78rem; font-weight:700;
                   color:#6b7280; cursor:pointer; border:none; background:none; transition:all .15s; }
.cc-unit-btn.active { background:#fff; color:#b45309; box-shadow:0 1px 4px rgba(0,0,0,.1); }

/* Input with unit suffix */
.cc-inp-wrap   { display:flex; align-items:stretch; }
.cc-inp-suffix { display:flex; align-items:center; padding:0 .75rem; background:#fef3c7;
                  border:1px solid #d1d5db; border-left:none;
                  border-radius:0 .75rem .75rem 0; font-size:.8rem; font-weight:700;
                  color:#92400e; white-space:nowrap; }
.cc-inp-wrap .form-input { border-radius:.75rem 0 0 .75rem !important; }

/* Result stat tile */
.cc-tile { background:#fff; border:1.5px solid #e7e5e4; border-radius:1.125rem;
            padding:1rem .9rem; display:flex; flex-direction:column; align-items:center;
            gap:.3rem; text-align:center; transition:all .15s; }
.cc-tile:hover { border-color:#fcd34d; box-shadow:0 4px 16px rgba(217,119,6,.07); transform:translateY(-1px); }
.cc-tile-lbl { font-size:.6rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.08em; }
.cc-tile-val { font-size:1.2rem; font-weight:900; line-height:1.1; }
.cc-tile-val.amber  { background:linear-gradient(135deg,#92400e,#d97706);
                       -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.cc-tile-val.stone  { background:linear-gradient(135deg,#44403c,#78716c);
                       -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.cc-tile-val.green  { background:linear-gradient(135deg,#15803d,#16a34a);
                       -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.cc-tile-sub  { font-size:.65rem; color:#94a3b8; }

/* Bag cards */
.cc-bag { border-radius:1.125rem; padding:1rem 1.1rem; border:1.5px solid; transition:all .15s; }
.cc-bag:hover { transform:translateY(-1px); }
.cc-bag-amber { border-color:#fcd34d; background:linear-gradient(135deg,#fffbeb,#fef3c7); }
.cc-bag-stone { border-color:#d6d3d1; background:linear-gradient(135deg,#fafaf9,#f5f5f4); }
.cc-bag-slate { border-color:#cbd5e1; background:linear-gradient(135deg,#f8fafc,#f1f5f9); }
.cc-bag-cnt  { font-size:1.6rem; font-weight:900; line-height:1; }
.cc-bag-lbl  { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; margin-top:.2rem; }

/* Breakdown table */
.cc-row { display:grid; grid-template-columns:1.6fr 1fr 1fr 1fr; gap:.5rem;
           align-items:center; padding:.5rem .75rem; border-radius:.625rem; font-size:.8rem; }
.cc-row:hover { background:#fffbeb; }
.cc-row.head  { font-size:.6rem; font-weight:700; color:#94a3b8; text-transform:uppercase;
                 letter-spacing:.07em; padding-bottom:.3rem; }
.cc-row.total { background:#fef3c7; border:1px solid #fcd34d; font-weight:700; }

/* What-if table */
.cc-wi-row { display:grid; grid-template-columns:.7fr 1fr 1fr 1fr; gap:.5rem;
              align-items:center; padding:.45rem .75rem; border-radius:.625rem; font-size:.78rem; }
.cc-wi-row:hover { background:#fffbeb; }
.cc-wi-row.head  { font-size:.6rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.07em; }
.cc-wi-row.current { background:#fef3c7; border:1px solid #fcd34d; font-weight:700; }

/* Divider */
.cc-div { display:flex; align-items:center; gap:.6rem; color:#94a3b8; font-size:.62rem;
           font-weight:800; text-transform:uppercase; letter-spacing:.1em; }
.cc-div::before,.cc-div::after { content:''; flex:1; height:1px; background:#e7e5e4; }

/* Hint text */
.cc-hint { font-size:.68rem; color:#94a3b8; margin-top:.2rem; display:flex; align-items:flex-start; gap:.3rem; }

/* Formula ref accordion */
.cc-formula-row { display:flex; align-items:flex-start; justify-content:space-between;
                   gap:.5rem; padding:.5rem .75rem; border-radius:.625rem; font-size:.78rem; }
.cc-formula-row:hover { background:#fffbeb; }
.cc-formula-code { font-family:monospace; font-size:.72rem; background:#fef3c7; color:#92400e;
                    padding:.15rem .45rem; border-radius:.35rem; white-space:nowrap; }

/* Animate in */
@keyframes ccIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
.cc-in { animation:ccIn .3s ease-out; }

/* Conversion quick-ref */
.cc-conv-row { display:flex; justify-content:space-between; font-size:.72rem; padding:.25rem 0;
                border-bottom:1px solid #f5f5f4; color:#6b7280; }
.cc-conv-row:last-child { border-bottom:none; }
</style>

<div class="min-h-screen bg-gray-50 py-8" x-data="ccCalc()" x-init="init()">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4"
           style="background:linear-gradient(135deg,#b45309,#d97706)">
        <span class="text-3xl">🏗️</span>
      </div>
      <h1 class="text-3xl font-bold text-gray-900">Concrete Calculator</h1>
      <p class="mt-2 text-gray-500">Calculate concrete volume for slabs, walls, columns, footings &amp; more</p>
    </div>

    
    <div x-show="errorMsg" x-transition
         class="mb-6 p-4 rounded-xl border border-red-200 bg-red-50 text-red-700 text-sm flex items-start gap-2">
      <span class="text-lg leading-none">⚠️</span>
      <span x-text="errorMsg"></span>
    </div>

    <div class="lg:grid lg:grid-cols-5 lg:gap-8 space-y-6 lg:space-y-0">

      
      <div class="lg:col-span-2 space-y-5">

        
        <div class="card p-4">
          <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Measurement Unit</p>
          <div class="cc-unit-pill">
            <button class="cc-unit-btn" :class="unit==='metric'   ? 'active' : ''" @click="setUnit('metric')">Metric (m)</button>
            <button class="cc-unit-btn" :class="unit==='imperial' ? 'active' : ''" @click="setUnit('imperial')">Imperial (ft)</button>
          </div>
        </div>

        
        <div class="card p-4">
          <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Select Shape</p>
          <div class="grid grid-cols-3 gap-2">
            <template x-for="s in shapeConfig" :key="s.id">
              <button class="cc-shape-card" :class="shape===s.id ? 'active':''" @click="selectShape(s.id)">
                <span class="cc-shape-icon" x-text="s.icon"></span>
                <span class="cc-shape-lbl"  x-text="s.label"></span>
              </button>
            </template>
          </div>
        </div>

        
        <div class="card p-5 space-y-4">
          <h2 class="font-semibold text-gray-800">
            Dimensions
            <span class="text-xs font-normal text-gray-400 ml-1" x-text="unit==='metric' ? '(meters)' : '(feet)'"></span>
          </h2>

          <template x-for="f in activeFields" :key="f">
            <div>
              <label class="form-label" x-text="fieldMeta[f].label"></label>
              <template x-if="f !== 'steps'">
                <div class="cc-inp-wrap">
                  <input type="number" step="any" min="0"
                         x-model="dims[f]"
                         :placeholder="fieldMeta[f].placeholder"
                         class="form-input flex-1" />
                  <span class="cc-inp-suffix" x-text="unit==='metric' ? 'm' : 'ft'"></span>
                </div>
              </template>
              <template x-if="f === 'steps'">
                <div class="cc-inp-wrap">
                  <input type="number" step="1" min="1" max="50"
                         x-model="dims.steps"
                         placeholder="e.g. 5"
                         class="form-input flex-1" />
                  <span class="cc-inp-suffix">steps</span>
                </div>
              </template>
              <p class="cc-hint" x-show="unit==='imperial' && fieldMeta[f].hintImp">
                <span>💡</span>
                <span x-text="fieldMeta[f].hintImp"></span>
              </p>
              <p class="cc-hint" x-show="unit==='metric' && fieldMeta[f].hintM">
                <span>💡</span>
                <span x-text="fieldMeta[f].hintM"></span>
              </p>
            </div>
          </template>
        </div>

        
        <div class="card p-5 space-y-4">
          <h2 class="font-semibold text-gray-800">Quantity &amp; Waste</h2>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="form-label">Number of Units</label>
              <div class="cc-inp-wrap">
                <input type="number" step="1" min="1" max="9999"
                       x-model.number="qty"
                       placeholder="1"
                       class="form-input flex-1" />
                <span class="cc-inp-suffix">× qty</span>
              </div>
              <p class="cc-hint"><span>💡</span><span>e.g. 4 columns, 6 footings</span></p>
            </div>
            <div>
              <label class="form-label">Waste Factor</label>
              <div class="cc-inp-wrap">
                <input type="number" step="1" min="0" max="50"
                       x-model.number="waste"
                       placeholder="10"
                       class="form-input flex-1" />
                <span class="cc-inp-suffix">%</span>
              </div>
              <p class="cc-hint"><span>💡</span><span>Typically 5–15%</span></p>
            </div>
          </div>
        </div>

        
        <button @click="compute()"
                class="btn btn-primary w-full py-3 text-base font-bold"
                style="background:linear-gradient(135deg,#b45309,#d97706)">
          Calculate Concrete Volume
        </button>

        
        <div class="card p-4 bg-amber-50 border border-amber-100">
          <p class="text-xs font-bold text-amber-800 uppercase tracking-wide mb-2">📐 Quick Conversions</p>
          <div>
            <div class="cc-conv-row"><span>1 meter</span><span class="font-semibold">= 3.281 feet</span></div>
            <div class="cc-conv-row"><span>1 foot</span><span class="font-semibold">= 0.305 m</span></div>
            <div class="cc-conv-row"><span>4 inches</span><span class="font-semibold">= 0.333 ft = 0.102 m</span></div>
            <div class="cc-conv-row"><span>6 inches</span><span class="font-semibold">= 0.500 ft = 0.152 m</span></div>
            <div class="cc-conv-row"><span>1 ft³</span><span class="font-semibold">= 0.0283 m³</span></div>
            <div class="cc-conv-row"><span>1 yd³</span><span class="font-semibold">= 27 ft³ = 0.765 m³</span></div>
          </div>
        </div>

      </div>

      
      <div class="lg:col-span-3 space-y-5">

        
        <div x-show="phase==='idle'" class="card p-12 text-center text-gray-400">
          <div class="text-5xl mb-4">🏗️</div>
          <p class="font-medium">Select a shape and enter dimensions to calculate</p>
          <p class="text-sm mt-1">Results will appear here</p>
        </div>

        
        <div x-show="phase==='loading'" class="card p-12 text-center">
          <div class="inline-block w-8 h-8 border-4 border-amber-200 border-t-amber-600 rounded-full animate-spin mb-4"></div>
          <p class="text-gray-500">Calculating…</p>
        </div>

        <template x-if="phase==='done'">
          <div class="space-y-5 cc-in">

            
            <div class="rounded-2xl p-6 text-white" style="background:linear-gradient(135deg,#92400e,#b45309,#d97706)">
              <div class="flex items-start justify-between flex-wrap gap-4">
                <div>
                  <p class="text-amber-200 text-sm font-medium mb-1">Total Concrete Volume</p>
                  <div class="flex items-end gap-2">
                    <span class="text-4xl font-black tracking-tight" x-text="fmt(result.totalM3, 4)"></span>
                    <span class="text-amber-200 font-semibold mb-1">m³</span>
                  </div>
                  <p class="text-amber-200 text-sm mt-1">
                    <span x-text="fmt(result.totalFt3, 3)"></span> ft³ &nbsp;/&nbsp;
                    <span x-text="fmt(result.totalYd3, 3)"></span> yd³
                  </p>
                </div>
                <div class="text-right">
                  <p class="text-amber-200 text-xs mb-1">Est. Weight</p>
                  <p class="text-2xl font-bold" x-text="fmtWt(result.totalM3 * 2400)"></p>
                  <p class="text-amber-200 text-xs">@ 2,400 kg/m³</p>
                </div>
              </div>
              <div class="mt-4 pt-4 border-t border-amber-600 flex flex-wrap gap-x-4 gap-y-1 text-amber-200 text-xs">
                <span><span class="font-semibold text-white" x-text="result.qty"></span> unit(s)</span>
                <span>+<span class="font-semibold text-white" x-text="result.waste"></span>% waste</span>
                <span>Shape: <span class="font-semibold text-white capitalize" x-text="activeShapeLabel"></span></span>
              </div>
            </div>

            
            <div class="grid grid-cols-3 gap-3">
              <div class="cc-tile">
                <span class="cc-tile-lbl">Cubic Meters</span>
                <span class="cc-tile-val amber" x-text="fmt(result.totalM3, 4)"></span>
                <span class="cc-tile-sub">m³</span>
              </div>
              <div class="cc-tile">
                <span class="cc-tile-lbl">Cubic Feet</span>
                <span class="cc-tile-val stone" x-text="fmt(result.totalFt3, 3)"></span>
                <span class="cc-tile-sub">ft³</span>
              </div>
              <div class="cc-tile">
                <span class="cc-tile-lbl">Cubic Yards</span>
                <span class="cc-tile-val green" x-text="fmt(result.totalYd3, 3)"></span>
                <span class="cc-tile-sub">yd³</span>
              </div>
            </div>

            
            <div class="card p-5">
              <p class="cc-div mb-4">Premix Bags Needed</p>
              <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="cc-bag cc-bag-amber">
                  <p class="cc-bag-cnt" style="color:#92400e" x-text="result.bags25kg"></p>
                  <p class="cc-bag-lbl" style="color:#b45309">25 kg bags</p>
                  <p style="font-size:.62rem;color:#b45309;margin-top:.15rem">≈ 0.011 m³ each</p>
                </div>
                <div class="cc-bag cc-bag-amber">
                  <p class="cc-bag-cnt" style="color:#92400e" x-text="result.bags40kg"></p>
                  <p class="cc-bag-lbl" style="color:#b45309">40 kg bags</p>
                  <p style="font-size:.62rem;color:#b45309;margin-top:.15rem">≈ 0.017 m³ each</p>
                </div>
                <div class="cc-bag cc-bag-stone">
                  <p class="cc-bag-cnt" style="color:#44403c" x-text="result.bags60lb"></p>
                  <p class="cc-bag-lbl" style="color:#78716c">60 lb bags</p>
                  <p style="font-size:.62rem;color:#78716c;margin-top:.15rem">≈ 0.45 ft³ each</p>
                </div>
                <div class="cc-bag cc-bag-slate">
                  <p class="cc-bag-cnt" style="color:#334155" x-text="result.bags80lb"></p>
                  <p class="cc-bag-lbl" style="color:#475569">80 lb bags</p>
                  <p style="font-size:.62rem;color:#475569;margin-top:.15rem">≈ 0.60 ft³ each</p>
                </div>
              </div>
              <p class="text-xs text-gray-400 mt-3">* Bag counts are rounded up. Actual yield may vary slightly by brand.</p>
            </div>

            
            <div class="card p-5">
              <p class="cc-div mb-3">Volume Breakdown</p>
              <div class="cc-row head">
                <span>Description</span>
                <span>m³</span>
                <span>ft³</span>
                <span>yd³</span>
              </div>
              <div class="cc-row">
                <span class="text-gray-600">Single unit (no waste)</span>
                <span class="font-semibold" x-text="fmt(result.singleM3, 4)"></span>
                <span class="font-semibold" x-text="fmt(result.singleFt3, 3)"></span>
                <span class="font-semibold" x-text="fmt(result.singleYd3, 3)"></span>
              </div>
              <div class="cc-row" x-show="result.waste > 0">
                <span class="text-gray-600">+ Waste (<span x-text="result.waste"></span>%)</span>
                <span class="font-semibold text-amber-700" x-text="fmt(result.singleM3 * result.waste/100, 4)"></span>
                <span class="font-semibold text-amber-700" x-text="fmt(result.singleFt3 * result.waste/100, 3)"></span>
                <span class="font-semibold text-amber-700" x-text="fmt(result.singleYd3 * result.waste/100, 3)"></span>
              </div>
              <div class="cc-row" x-show="result.qty > 1">
                <span class="text-gray-600">× <span x-text="result.qty"></span> units</span>
                <span class="font-semibold" x-text="fmt(result.singleM3 * (1 + result.waste/100) * result.qty, 4)"></span>
                <span class="font-semibold" x-text="fmt(result.singleFt3 * (1 + result.waste/100) * result.qty, 3)"></span>
                <span class="font-semibold" x-text="fmt(result.singleYd3 * (1 + result.waste/100) * result.qty, 3)"></span>
              </div>
              <div class="cc-row total">
                <span style="color:#92400e">Total Required</span>
                <span style="color:#92400e" x-text="fmt(result.totalM3, 4)"></span>
                <span style="color:#92400e" x-text="fmt(result.totalFt3, 3)"></span>
                <span style="color:#92400e" x-text="fmt(result.totalYd3, 3)"></span>
              </div>
            </div>

            
            <div class="card p-5">
              <p class="cc-div mb-3">What-If: Volume by Quantity</p>
              <div class="cc-wi-row head">
                <span>Qty</span>
                <span>m³</span>
                <span>ft³</span>
                <span>yd³</span>
              </div>
              <template x-for="n in [1,2,3,4,5,6,8,10,15,20]" :key="n">
                <div class="cc-wi-row" :class="n===result.qty ? 'current' : ''">
                  <span class="font-semibold" :style="n===result.qty ? 'color:#92400e' : ''">
                    <span x-text="n"></span>
                    <span x-show="n===result.qty" class="text-xs ml-1">(you)</span>
                  </span>
                  <span x-text="fmt(result.singleM3 * (1 + result.waste/100) * n, 4)"></span>
                  <span x-text="fmt(result.singleFt3 * (1 + result.waste/100) * n, 3)"></span>
                  <span x-text="fmt(result.singleYd3 * (1 + result.waste/100) * n, 3)"></span>
                </div>
              </template>
            </div>

            
            <div class="card p-5">
              <button @click="showFormulas = !showFormulas"
                      class="w-full flex items-center justify-between text-left">
                <span class="font-semibold text-gray-700">📐 Formula Reference</span>
                <span class="text-gray-400 text-lg" x-text="showFormulas ? '−' : '+'"></span>
              </button>
              <div x-show="showFormulas" x-transition class="mt-4 space-y-1">
                <div class="cc-formula-row">
                  <span class="font-medium text-gray-700 w-28 flex-shrink-0">Slab / Wall</span>
                  <span class="cc-formula-code">L × W × T</span>
                </div>
                <div class="cc-formula-row">
                  <span class="font-medium text-gray-700 w-28 flex-shrink-0">Footing / Pad</span>
                  <span class="cc-formula-code">L × W × D</span>
                </div>
                <div class="cc-formula-row">
                  <span class="font-medium text-gray-700 w-28 flex-shrink-0">Sq. Column</span>
                  <span class="cc-formula-code">W² × H</span>
                </div>
                <div class="cc-formula-row">
                  <span class="font-medium text-gray-700 w-28 flex-shrink-0">Round Column</span>
                  <span class="cc-formula-code">π × (D/2)² × H</span>
                </div>
                <div class="cc-formula-row">
                  <span class="font-medium text-gray-700 w-28 flex-shrink-0">Staircase</span>
                  <span class="cc-formula-code">Width × Rise × Tread × Steps</span>
                </div>
                <p class="text-xs text-gray-400 pt-2 border-t border-gray-100 mt-2">
                  All volumes are calculated in the selected unit system then converted.
                  Concrete density assumed at 2,400 kg/m³ (150 lb/ft³) for weight estimate.
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
function ccCalc() {
  return {
    // ── State ──────────────────────────────────
    unit:   'metric',   // 'metric' | 'imperial'
    shape:  'slab',
    qty:    1,
    waste:  10,
    phase:  'idle',     // idle | loading | done
    errorMsg: '',
    showFormulas: false,

    // ── Dimension store ────────────────────────
    dims: {
      length: '', width: '', thickness: '',
      height: '', depth: '', diameter: '',
      stairWidth: '', rise: '', run: '', steps: '5',
    },

    // ── Shape definitions ──────────────────────
    shapeConfig: [
      { id:'slab',     icon:'⬛', label:'Slab',          fields:['length','width','thickness'] },
      { id:'footing',  icon:'🏗️',  label:'Footing / Pad', fields:['length','width','depth']     },
      { id:'wall',     icon:'🧱', label:'Wall',           fields:['length','height','thickness'] },
      { id:'column',   icon:'🏛️',  label:'Sq. Column',    fields:['width','height']             },
      { id:'cylinder', icon:'⭕', label:'Round Column',   fields:['diameter','height']           },
      { id:'stair',    icon:'🪜', label:'Staircase',      fields:['stairWidth','rise','run','steps'] },
    ],

    // ── Field metadata ─────────────────────────
    fieldMeta: {
      length:     { label:'Length',               placeholder:'e.g. 5',    hintImp:'e.g. 15 ft  (divide inches by 12)',       hintM:'e.g. 5 m' },
      width:      { label:'Width',                placeholder:'e.g. 4',    hintImp:'e.g. 12 ft',                              hintM:'e.g. 4 m' },
      thickness:  { label:'Thickness',            placeholder:'e.g. 0.15', hintImp:'4 in = 0.333 ft  |  6 in = 0.5 ft',      hintM:'15 cm = 0.15 m  |  20 cm = 0.20 m' },
      height:     { label:'Height',               placeholder:'e.g. 3',    hintImp:'e.g. 10 ft',                              hintM:'e.g. 3 m' },
      depth:      { label:'Depth',                placeholder:'e.g. 0.3',  hintImp:'12 in = 1 ft  |  18 in = 1.5 ft',        hintM:'30 cm = 0.30 m' },
      diameter:   { label:'Diameter',             placeholder:'e.g. 0.3',  hintImp:'8 in = 0.667 ft  |  12 in = 1 ft',       hintM:'30 cm = 0.30 m' },
      stairWidth: { label:'Stair Width',          placeholder:'e.g. 1.2',  hintImp:'e.g. 4 ft',                              hintM:'e.g. 1.2 m' },
      rise:       { label:'Rise (per step)',       placeholder:'e.g. 0.18', hintImp:'7 in = 0.583 ft  |  8 in = 0.667 ft',   hintM:'18 cm = 0.18 m  |  20 cm = 0.20 m' },
      run:        { label:'Run / Tread (per step)',placeholder:'e.g. 0.28', hintImp:'10 in = 0.833 ft  |  12 in = 1 ft',     hintM:'28 cm = 0.28 m  |  30 cm = 0.30 m' },
      steps:      { label:'Number of Steps',       placeholder:'e.g. 5',   hintImp:null, hintM:null },
    },

    // ── Result store ───────────────────────────
    result: null,

    // ── Getters ────────────────────────────────
    get activeFields() {
      var s = this.shapeConfig.find(function(x){ return x.id === this.shape; }, this);
      return s ? s.fields : [];
    },

    get activeShapeLabel() {
      var s = this.shapeConfig.find(function(x){ return x.id === this.shape; }, this);
      return s ? s.label : this.shape;
    },

    // ── Init ───────────────────────────────────
    init() {
      // nothing special needed
    },

    // ── Actions ────────────────────────────────
    selectShape(id) {
      this.shape = id;
      this.phase = 'idle';
      this.errorMsg = '';
    },

    setUnit(u) {
      if (u === this.unit) return;
      // Convert existing filled-in values between m and ft
      var factor = (u === 'imperial') ? 3.28084 : 0.3048;
      var self = this;
      ['length','width','thickness','height','depth','diameter','stairWidth','rise','run'].forEach(function(f) {
        var v = parseFloat(self.dims[f]);
        if (!isNaN(v) && v > 0) {
          self.dims[f] = parseFloat((v * factor).toFixed(4)).toString();
        }
      });
      this.unit = u;
      if (this.phase === 'done') this.compute();
    },

    // ── Core computation ───────────────────────
    compute() {
      this.errorMsg = '';

      // Validate required fields
      var self = this;
      var fields = this.activeFields;
      for (var i = 0; i < fields.length; i++) {
        var f = fields[i];
        if (f === 'steps') {
          var sv = parseInt(self.dims.steps);
          if (isNaN(sv) || sv < 1) { self.errorMsg = 'Number of steps must be at least 1.'; return; }
        } else {
          var fv = parseFloat(self.dims[f]);
          if (isNaN(fv) || fv <= 0) {
            self.errorMsg = 'Please fill in all dimension fields with positive values. Check "' + self.fieldMeta[f].label + '".';
            return;
          }
        }
      }

      var qty = Math.max(1, parseInt(this.qty) || 1);
      if (qty > 9999) { this.errorMsg = 'Quantity cannot exceed 9,999.'; return; }

      var wastePct = Math.max(0, Math.min(50, parseFloat(this.waste) || 0));

      this.phase = 'loading';
      var self = this;
      setTimeout(function() {
        try {
          self._doCompute(qty, wastePct);
          self.phase = 'done';
        } catch(e) {
          self.errorMsg = e.message;
          self.phase = 'idle';
        }
      }, 100);
    },

    _doCompute(qty, wastePct) {
      var g = function(f) { return parseFloat(this.dims[f]); }.bind(this);

      // ── Calculate raw volume in current unit³ (m³ if metric, ft³ if imperial) ──
      var volUnit;  // m³ or ft³ for one unit
      var sh = this.shape;

      if (sh === 'slab') {
        volUnit = g('length') * g('width') * g('thickness');
      } else if (sh === 'footing') {
        volUnit = g('length') * g('width') * g('depth');
      } else if (sh === 'wall') {
        volUnit = g('length') * g('height') * g('thickness');
      } else if (sh === 'column') {
        // Square column: width² × height
        volUnit = g('width') * g('width') * g('height');
      } else if (sh === 'cylinder') {
        // Round column: π × r² × h
        var r = g('diameter') / 2;
        volUnit = Math.PI * r * r * g('height');
      } else if (sh === 'stair') {
        // Per-step approach: each step = stairWidth × rise × run
        // Total = steps × stairWidth × rise × run
        var steps = parseInt(this.dims.steps) || 1;
        volUnit = steps * g('stairWidth') * g('rise') * g('run');
      } else {
        throw new Error('Unknown shape: ' + sh);
      }

      if (!isFinite(volUnit) || volUnit <= 0) {
        throw new Error('Calculated volume is zero or invalid. Please check your dimensions.');
      }

      // ── Convert to both m³ and ft³ ────────────────
      var singleM3, singleFt3;
      if (this.unit === 'metric') {
        singleM3  = volUnit;
        singleFt3 = volUnit / 0.028317;
      } else {
        singleFt3 = volUnit;
        singleM3  = volUnit * 0.028317;
      }
      var singleYd3 = singleFt3 / 27;

      var wasteFactor = 1 + wastePct / 100;
      var totalM3  = singleM3  * qty * wasteFactor;
      var totalFt3 = singleFt3 * qty * wasteFactor;
      var totalYd3 = singleYd3 * qty * wasteFactor;

      // ── Bags of premix concrete ──────────────────
      var bags25kg = Math.ceil(totalM3  / 0.011);   // 25 kg bag ≈ 0.011 m³
      var bags40kg = Math.ceil(totalM3  / 0.017);   // 40 kg bag ≈ 0.017 m³
      var bags60lb = Math.ceil(totalFt3 / 0.45);    // 60 lb bag ≈ 0.45 ft³
      var bags80lb = Math.ceil(totalFt3 / 0.60);    // 80 lb bag ≈ 0.60 ft³

      this.result = {
        singleM3, singleFt3, singleYd3,
        totalM3, totalFt3, totalYd3,
        bags25kg, bags40kg, bags60lb, bags80lb,
        qty, waste: wastePct,
      };
    },

    // ── Formatters ────────────────────────────
    fmt(val, decimals) {
      if (val === undefined || val === null || isNaN(val)) return '—';
      return parseFloat(val.toFixed(decimals)).toLocaleString('en-US', {
        minimumFractionDigits: 0,
        maximumFractionDigits: decimals,
      });
    },

    fmtWt(kg) {
      if (kg >= 1000) return (kg / 1000).toFixed(2) + ' t';
      return Math.round(kg).toLocaleString() + ' kg';
    },
  };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\concrete-calculator.blade.php ENDPATH**/ ?>