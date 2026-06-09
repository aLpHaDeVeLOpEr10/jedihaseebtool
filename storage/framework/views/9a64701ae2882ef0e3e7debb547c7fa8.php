<?php $__env->startSection('title', $tool->name . ' - ' . config('app.name')); ?>
<?php $__env->startSection('meta_description', $tool->description ?? 'Calculate area of squares, rectangles, circles, triangles, trapezoids, ellipses and more.'); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ══════════════════════════════════════════════
   Area Calculator  —  prefix: ar-
   Theme: Indigo / Violet
══════════════════════════════════════════════ */

/* Hero */
.ar-hero { font-size:clamp(2.8rem,6vw,4.5rem); font-weight:900; line-height:1; letter-spacing:-.04em;
           background:linear-gradient(135deg,#3730a3 0%,#4f46e5 55%,#6366f1 100%);
           -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }

/* Shape selector */
.ar-shape-card { display:flex; flex-direction:column; align-items:center; gap:.3rem; padding:.65rem .3rem;
                  border:2px solid #e0e7ff; border-radius:.875rem; cursor:pointer; background:#fff;
                  transition:all .15s; text-align:center; min-width:0; }
.ar-shape-card:hover  { border-color:#a5b4fc; background:#eef2ff; transform:translateY(-1px); }
.ar-shape-card.active { border-color:#4f46e5; background:linear-gradient(135deg,#eef2ff,#e0e7ff);
                         box-shadow:0 4px 14px rgba(79,70,229,.18); }
.ar-shape-icon { font-size:1.4rem; line-height:1; }
.ar-shape-lbl  { font-size:.58rem; font-weight:700; color:#6b7280; text-transform:uppercase;
                  letter-spacing:.06em; line-height:1.2; }
.ar-shape-card.active .ar-shape-lbl { color:#3730a3; }

/* Unit pill */
.ar-unit-pill { display:flex; flex-wrap:wrap; background:#f5f3ff; border-radius:.625rem; padding:.2rem; gap:.1rem; }
.ar-unit-btn  { flex:1; min-width:40px; padding:.32rem .5rem; border-radius:.45rem; font-size:.75rem;
                font-weight:700; color:#6b7280; cursor:pointer; border:none; background:none;
                transition:all .15s; text-align:center; white-space:nowrap; }
.ar-unit-btn.active { background:#fff; color:#4f46e5; box-shadow:0 1px 4px rgba(0,0,0,.1); }

/* Method tabs (triangle) */
.ar-method-tab { flex:1; padding:.38rem .6rem; border-radius:.45rem; font-size:.72rem; font-weight:700;
                  color:#6b7280; cursor:pointer; border:none; background:none; transition:all .15s; text-align:center; }
.ar-method-tab.active { background:#fff; color:#4f46e5; box-shadow:0 1px 4px rgba(0,0,0,.1); }

/* Input with suffix */
.ar-inp-wrap   { display:flex; align-items:stretch; }
.ar-inp-suffix { display:flex; align-items:center; padding:0 .75rem; background:#eef2ff;
                  border:1px solid #d1d5db; border-left:none; border-radius:0 .75rem .75rem 0;
                  font-size:.8rem; font-weight:700; color:#4338ca; white-space:nowrap; }
.ar-inp-wrap .form-input { border-radius:.75rem 0 0 .75rem !important; }

/* Stat tile */
.ar-tile { background:#fff; border:1.5px solid #e0e7ff; border-radius:1.125rem;
            padding:.85rem .7rem; display:flex; flex-direction:column; align-items:center;
            gap:.2rem; text-align:center; transition:all .15s; min-width:0; }
.ar-tile:hover { border-color:#a5b4fc; box-shadow:0 4px 16px rgba(79,70,229,.07); transform:translateY(-1px); }
.ar-tile-lbl { font-size:.58rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.08em; }
.ar-tile-val { font-size:1rem; font-weight:900; line-height:1.15; word-break:break-all; }
.ar-tile-val.indigo { background:linear-gradient(135deg,#3730a3,#6366f1);
                       -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.ar-tile-val.violet { background:linear-gradient(135deg,#5b21b6,#7c3aed);
                       -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.ar-tile-val.sky    { background:linear-gradient(135deg,#0369a1,#0ea5e9);
                       -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.ar-tile-val.teal   { background:linear-gradient(135deg,#0f766e,#14b8a6);
                       -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.ar-tile-val.pink   { background:linear-gradient(135deg,#9d174d,#ec4899);
                       -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.ar-tile-val.orange { background:linear-gradient(135deg,#92400e,#f59e0b);
                       -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.ar-tile-sub  { font-size:.6rem; color:#94a3b8; }

/* Section divider */
.ar-div { display:flex; align-items:center; gap:.6rem; color:#94a3b8; font-size:.62rem;
           font-weight:800; text-transform:uppercase; letter-spacing:.1em; }
.ar-div::before,.ar-div::after { content:''; flex:1; height:1px; background:#e0e7ff; }

/* Formula code */
.ar-formula { font-family:monospace; font-size:.78rem; background:#eef2ff; color:#3730a3;
               padding:.3rem .7rem; border-radius:.5rem; display:inline-block; }

/* SVG diagram container */
.ar-diagram { background:linear-gradient(135deg,#fafaff,#f0f0ff); border:1.5px solid #e0e7ff;
               border-radius:1.25rem; overflow:hidden; }

/* Extra stat row */
.ar-extra-row { display:flex; align-items:center; justify-content:space-between; gap:.5rem;
                 padding:.45rem .75rem; border-radius:.625rem; font-size:.82rem; }
.ar-extra-row:hover { background:#eef2ff; }

/* Animate in */
@keyframes arIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
.ar-in { animation:arIn .3s ease-out; }
</style>

<div class="min-h-screen bg-gray-50 py-8" x-data="arCalc()" x-init="init()">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4"
           style="background:linear-gradient(135deg,#4338ca,#4f46e5)">
        <span class="text-3xl">📐</span>
      </div>
      <h1 class="text-3xl font-bold text-gray-900">Area Calculator</h1>
      <p class="mt-2 text-gray-500">Calculate area for 8 common shapes with unit conversion</p>
    </div>

    
    <div x-show="errorMsg" x-transition
         class="mb-6 p-4 rounded-xl border border-red-200 bg-red-50 text-red-700 text-sm flex items-start gap-2">
      <span class="text-lg leading-none">⚠️</span>
      <span x-text="errorMsg"></span>
    </div>

    <div class="lg:grid lg:grid-cols-5 lg:gap-8 space-y-6 lg:space-y-0">

      
      <div class="lg:col-span-2 space-y-5">

        
        <div class="card p-4">
          <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Length Unit</p>
          <div class="ar-unit-pill">
            <template x-for="u in unitConfig" :key="u.id">
              <button class="ar-unit-btn" :class="unit===u.id ? 'active':''" @click="setUnit(u.id)" x-text="u.label"></button>
            </template>
          </div>
        </div>

        
        <div class="card p-4">
          <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Select Shape</p>
          <div class="grid grid-cols-4 gap-2">
            <template x-for="s in shapeConfig" :key="s.id">
              <button class="ar-shape-card" :class="shape===s.id ? 'active':''" @click="selectShape(s.id)">
                <span class="ar-shape-icon" x-text="s.icon"></span>
                <span class="ar-shape-lbl"  x-text="s.label"></span>
              </button>
            </template>
          </div>
        </div>

        
        <div x-show="shape==='triangle'" class="card p-4">
          <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Triangle Method</p>
          <div class="flex bg-violet-50 rounded-xl p-1.5 gap-1">
            <button class="ar-method-tab" :class="triMethod==='bh'  ? 'active':''" @click="setTriMethod('bh')">Base &amp; Height</button>
            <button class="ar-method-tab" :class="triMethod==='3s'  ? 'active':''" @click="setTriMethod('3s')">3 Sides</button>
            <button class="ar-method-tab" :class="triMethod==='sas' ? 'active':''" @click="setTriMethod('sas')">SAS</button>
          </div>
        </div>

        
        <div class="card p-5 space-y-4">
          <h2 class="font-semibold text-gray-800">
            Dimensions
            <span class="text-xs font-normal text-gray-400 ml-1" x-text="'(in ' + unit + ')'"></span>
          </h2>

          <template x-for="f in activeFields" :key="f">
            <div>
              <label class="form-label" x-text="fieldMeta[f].label"></label>
              <div class="ar-inp-wrap">
                <input type="number" step="any"
                       :min="fieldMeta[f].isAngle ? '0' : '0'"
                       :max="fieldMeta[f].maxVal || ''"
                       x-model="dims[f]"
                       :placeholder="fieldMeta[f].placeholder"
                       @input.debounce.500ms="autoCompute()"
                       class="form-input flex-1" />
                <span class="ar-inp-suffix" x-text="fieldMeta[f].isAngle ? '°' : unit"></span>
              </div>
              <p class="text-xs text-gray-400 mt-1" x-show="fieldMeta[f].hint" x-text="fieldMeta[f].hint"></p>
            </div>
          </template>
        </div>

        
        <button @click="compute()"
                class="btn btn-primary w-full py-3 text-base font-bold"
                style="background:linear-gradient(135deg,#4338ca,#4f46e5)">
          Calculate Area
        </button>

      </div>

      
      <div class="lg:col-span-3 space-y-5">

        
        <div class="ar-diagram p-4">
          <p class="text-xs font-bold text-indigo-400 uppercase tracking-wide text-center mb-2">Shape Diagram</p>
          <div class="flex justify-center">

            
            <svg x-show="shape==='square'" viewBox="0 0 240 160" class="w-full max-w-xs h-40">
              <rect x="55" y="10" width="130" height="130" fill="#eef2ff" stroke="#4f46e5" stroke-width="2" rx="2"/>
              <text x="120" y="155" text-anchor="middle" font-size="13" fill="#3730a3" font-weight="700">s</text>
              <text x="194" y="80" text-anchor="start" font-size="13" fill="#3730a3" font-weight="700">s</text>
              <line x1="55" y1="150" x2="185" y2="150" stroke="#6366f1" stroke-width="1" stroke-dasharray="3 2"/>
              <line x1="198" y1="10" x2="198" y2="140" stroke="#6366f1" stroke-width="1" stroke-dasharray="3 2"/>
            </svg>

            
            <svg x-show="shape==='rectangle'" viewBox="0 0 240 160" class="w-full max-w-xs h-40">
              <rect x="15" y="25" width="210" height="110" fill="#eef2ff" stroke="#4f46e5" stroke-width="2" rx="2"/>
              <text x="120" y="155" text-anchor="middle" font-size="13" fill="#3730a3" font-weight="700">length (l)</text>
              <text x="236" y="82" text-anchor="start" font-size="13" fill="#3730a3" font-weight="700">w</text>
              <line x1="15" y1="148" x2="225" y2="148" stroke="#6366f1" stroke-width="1" stroke-dasharray="3 2"/>
              <line x1="233" y1="25" x2="233" y2="135" stroke="#6366f1" stroke-width="1" stroke-dasharray="3 2"/>
            </svg>

            
            <svg x-show="shape==='circle'" viewBox="0 0 240 160" class="w-full max-w-xs h-40">
              <circle cx="120" cy="78" r="68" fill="#eef2ff" stroke="#4f46e5" stroke-width="2"/>
              <circle cx="120" cy="78" r="3" fill="#4f46e5"/>
              <line x1="120" y1="78" x2="188" y2="78" stroke="#4f46e5" stroke-width="1.5" stroke-dasharray="4 3"/>
              <text x="157" y="70" text-anchor="middle" font-size="13" fill="#3730a3" font-weight="700">r</text>
            </svg>

            
            <svg x-show="shape==='triangle' && triMethod==='bh'" viewBox="0 0 240 160" class="w-full max-w-xs h-40">
              <polygon points="120,12 215,140 25,140" fill="#eef2ff" stroke="#4f46e5" stroke-width="2"/>
              <line x1="120" y1="12" x2="120" y2="140" stroke="#818cf8" stroke-width="1.5" stroke-dasharray="5 3"/>
              <text x="120" y="157" text-anchor="middle" font-size="13" fill="#3730a3" font-weight="700">b</text>
              <text x="132" y="78" text-anchor="start" font-size="13" fill="#3730a3" font-weight="700">h</text>
            </svg>

            
            <svg x-show="shape==='triangle' && triMethod==='3s'" viewBox="0 0 240 160" class="w-full max-w-xs h-40">
              <polygon points="95,12 220,140 20,140" fill="#eef2ff" stroke="#4f46e5" stroke-width="2"/>
              <text x="46" y="82" text-anchor="middle" font-size="13" fill="#3730a3" font-weight="700">a</text>
              <text x="170" y="82" text-anchor="middle" font-size="13" fill="#3730a3" font-weight="700">b</text>
              <text x="120" y="157" text-anchor="middle" font-size="13" fill="#3730a3" font-weight="700">c</text>
            </svg>

            
            <svg x-show="shape==='triangle' && triMethod==='sas'" viewBox="0 0 240 160" class="w-full max-w-xs h-40">
              <polygon points="30,135 210,135 80,20" fill="#eef2ff" stroke="#4f46e5" stroke-width="2"/>
              <path d="M 60,135 A 30,30 0 0 0 44,111" fill="none" stroke="#6366f1" stroke-width="1.5"/>
              <text x="30" y="157" text-anchor="start" font-size="11" fill="#3730a3" font-weight="700">C°</text>
              <text x="45" y="82" text-anchor="middle" font-size="13" fill="#3730a3" font-weight="700">a</text>
              <text x="165" y="88" text-anchor="middle" font-size="13" fill="#3730a3" font-weight="700">b</text>
            </svg>

            
            <svg x-show="shape==='parallelogram'" viewBox="0 0 240 160" class="w-full max-w-xs h-40">
              <polygon points="40,135 195,135 200,25 45,25" fill="#eef2ff" stroke="#4f46e5" stroke-width="2"/>
              <line x1="118" y1="25" x2="118" y2="135" stroke="#818cf8" stroke-width="1.5" stroke-dasharray="5 3"/>
              <text x="118" y="157" text-anchor="middle" font-size="13" fill="#3730a3" font-weight="700">b</text>
              <text x="130" y="84" text-anchor="start" font-size="13" fill="#3730a3" font-weight="700">h</text>
            </svg>

            
            <svg x-show="shape==='trapezoid'" viewBox="0 0 240 160" class="w-full max-w-xs h-40">
              <polygon points="25,135 215,135 175,25 65,25" fill="#eef2ff" stroke="#4f46e5" stroke-width="2"/>
              <line x1="120" y1="25" x2="120" y2="135" stroke="#818cf8" stroke-width="1.5" stroke-dasharray="5 3"/>
              <text x="120" y="18" text-anchor="middle" font-size="13" fill="#3730a3" font-weight="700">a</text>
              <text x="120" y="157" text-anchor="middle" font-size="13" fill="#3730a3" font-weight="700">b</text>
              <text x="131" y="86" text-anchor="start" font-size="13" fill="#3730a3" font-weight="700">h</text>
            </svg>

            
            <svg x-show="shape==='ellipse'" viewBox="0 0 240 160" class="w-full max-w-xs h-40">
              <ellipse cx="120" cy="78" rx="105" ry="62" fill="#eef2ff" stroke="#4f46e5" stroke-width="2"/>
              <circle cx="120" cy="78" r="3" fill="#4f46e5"/>
              <line x1="120" y1="78" x2="225" y2="78" stroke="#4f46e5" stroke-width="1.5" stroke-dasharray="4 3"/>
              <line x1="120" y1="78" x2="120" y2="16" stroke="#6366f1" stroke-width="1.5" stroke-dasharray="4 3"/>
              <text x="173" y="70" text-anchor="middle" font-size="13" fill="#3730a3" font-weight="700">a</text>
              <text x="131" y="46" text-anchor="start" font-size="13" fill="#3730a3" font-weight="700">b</text>
            </svg>

            
            <svg x-show="shape==='sector'" viewBox="0 0 240 160" class="w-full max-w-xs h-40">
              <path d="M 55,138 L 215,138 A 160,160 0 0 0 107,22 Z" fill="#eef2ff" stroke="#4f46e5" stroke-width="2"/>
              <path d="M 80,138 A 25,25 0 0 0 67,117" fill="none" stroke="#6366f1" stroke-width="1.5"/>
              <text x="55" y="157" text-anchor="start" font-size="11" fill="#3730a3" font-weight="700">θ°</text>
              <text x="140" y="148" text-anchor="middle" font-size="13" fill="#3730a3" font-weight="700">r</text>
              <text x="83" y="86" text-anchor="middle" font-size="13" fill="#3730a3" font-weight="700">r</text>
            </svg>

          </div>
        </div>

        
        <div x-show="phase==='idle'" class="card p-10 text-center text-gray-400">
          <div class="text-5xl mb-3">📐</div>
          <p class="font-medium">Enter dimensions above and press Calculate</p>
          <p class="text-sm mt-1">Results and unit conversions will appear here</p>
        </div>

        
        <div x-show="phase==='loading'" class="card p-10 text-center">
          <div class="inline-block w-7 h-7 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin mb-3"></div>
          <p class="text-gray-500 text-sm">Calculating…</p>
        </div>

        <template x-if="phase==='done'">
          <div class="space-y-5 ar-in">

            
            <div class="rounded-2xl p-6 text-white" style="background:linear-gradient(135deg,#3730a3,#4f46e5,#6366f1)">
              <div class="flex items-start justify-between flex-wrap gap-4">
                <div>
                  <p class="text-indigo-200 text-sm font-medium mb-1">Area</p>
                  <div class="flex items-end gap-2">
                    <span class="text-4xl font-black tracking-tight" x-text="fmtNum(result.area)"></span>
                    <span class="text-indigo-200 font-semibold mb-1.5" x-text="unit + '²'"></span>
                  </div>
                  <p class="text-indigo-200 text-sm mt-1">
                    = <span x-text="fmtNum(result.areaM2)"></span> m²
                    &nbsp;/&nbsp;
                    <span x-text="fmtNum(result.areaFt2)"></span> ft²
                  </p>
                </div>
                <div class="text-right flex-shrink-0">
                  <p class="text-indigo-200 text-xs mb-1">Formula Used</p>
                  <code class="text-sm font-bold bg-indigo-700 px-2 py-1 rounded-lg" x-text="result.formula"></code>
                </div>
              </div>
            </div>

            
            <div class="card p-5">
              <p class="ar-div mb-4">Area in All Units</p>
              <div class="grid grid-cols-3 sm:grid-cols-6 gap-3">
                <div class="ar-tile">
                  <span class="ar-tile-lbl">mm²</span>
                  <span class="ar-tile-val indigo text-sm" x-text="fmtSci(result.areaMm2)"></span>
                </div>
                <div class="ar-tile">
                  <span class="ar-tile-lbl">cm²</span>
                  <span class="ar-tile-val violet" x-text="fmtNum(result.areaCm2)"></span>
                </div>
                <div class="ar-tile">
                  <span class="ar-tile-lbl">m²</span>
                  <span class="ar-tile-val sky" x-text="fmtNum(result.areaM2)"></span>
                </div>
                <div class="ar-tile">
                  <span class="ar-tile-lbl">ft²</span>
                  <span class="ar-tile-val teal" x-text="fmtNum(result.areaFt2)"></span>
                </div>
                <div class="ar-tile">
                  <span class="ar-tile-lbl">yd²</span>
                  <span class="ar-tile-val pink" x-text="fmtNum(result.areaYd2)"></span>
                </div>
                <div class="ar-tile">
                  <span class="ar-tile-lbl">acre</span>
                  <span class="ar-tile-val orange" x-text="fmtSci(result.areaAcre)"></span>
                </div>
              </div>
              <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mt-3">
                <div class="ar-tile">
                  <span class="ar-tile-lbl">km²</span>
                  <span class="ar-tile-val indigo text-sm" x-text="fmtSci(result.areaKm2)"></span>
                </div>
                <div class="ar-tile">
                  <span class="ar-tile-lbl">in²</span>
                  <span class="ar-tile-val sky" x-text="fmtNum(result.areaIn2)"></span>
                </div>
                <div class="ar-tile">
                  <span class="ar-tile-lbl">hectare</span>
                  <span class="ar-tile-val teal text-sm" x-text="fmtSci(result.areaHa)"></span>
                </div>
              </div>
            </div>

            
            <div class="card p-5" x-show="result.extras && result.extras.length > 0">
              <p class="ar-div mb-3">Additional Properties</p>
              <template x-for="ex in result.extras" :key="ex.label">
                <div class="ar-extra-row">
                  <span class="text-gray-600" x-text="ex.label"></span>
                  <span class="font-bold text-indigo-700" x-text="ex.value"></span>
                </div>
              </template>
            </div>

            
            <div class="card p-5">
              <p class="ar-div mb-3">Worked Example</p>
              <div class="bg-indigo-50 rounded-xl p-4">
                <p class="text-sm font-semibold text-indigo-800 mb-2">
                  <span x-text="activeShapeLabel"></span> Formula
                </p>
                <code class="ar-formula block mb-2" x-text="result.formulaFull"></code>
                <code class="ar-formula block" x-text="result.formulaWorked"></code>
                <p class="text-xs text-indigo-500 mt-2 font-semibold">
                  = <span x-text="fmtNum(result.area) + ' ' + unit + '²'"></span>
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
function arCalc() {
  return {
    // ── State ──────────────────────────────────
    unit:      'm',
    shape:     'rectangle',
    triMethod: 'bh',   // bh | 3s | sas
    phase:     'idle',
    errorMsg:  '',
    result:    null,

    // ── Dimensions store ──────────────────────
    dims: {
      side:'', length:'', width:'',
      radius:'',
      base:'', height:'',
      sideA:'', sideB:'', sideC:'', angleCdeg:'',
      semiA:'', semiB:'',
      angleDeg:'',
    },

    // ── Configuration ─────────────────────────
    unitConfig: [
      { id:'mm', label:'mm' }, { id:'cm', label:'cm' }, { id:'m', label:'m' },
      { id:'km', label:'km' }, { id:'in', label:'in' }, { id:'ft', label:'ft' },
      { id:'yd', label:'yd' },
    ],

    unitToM: { mm:0.001, cm:0.01, m:1, km:1000, 'in':0.0254, ft:0.3048, yd:0.9144 },

    shapeConfig: [
      { id:'square',        icon:'⬛', label:'Square'       },
      { id:'rectangle',     icon:'▬',  label:'Rectangle'    },
      { id:'circle',        icon:'⭕', label:'Circle'        },
      { id:'triangle',      icon:'🔺', label:'Triangle'      },
      { id:'parallelogram', icon:'▱',  label:'Parallelogram' },
      { id:'trapezoid',     icon:'⏢',  label:'Trapezoid'     },
      { id:'ellipse',       icon:'⬬',  label:'Ellipse'       },
      { id:'sector',        icon:'🥧', label:'Sector'        },
    ],

    fieldSets: {
      square:           ['side'],
      rectangle:        ['length', 'width'],
      circle:           ['radius'],
      triangle_bh:      ['base', 'height'],
      triangle_3s:      ['sideA', 'sideB', 'sideC'],
      triangle_sas:     ['sideA', 'sideB', 'angleCdeg'],
      parallelogram:    ['base', 'height'],
      trapezoid:        ['sideA', 'sideB', 'height'],
      ellipse:          ['semiA', 'semiB'],
      sector:           ['radius', 'angleDeg'],
    },

    fieldMeta: {
      side:      { label:'Side',              placeholder:'e.g. 5',   isAngle:false, hint:'' },
      length:    { label:'Length',            placeholder:'e.g. 10',  isAngle:false, hint:'' },
      width:     { label:'Width',             placeholder:'e.g. 5',   isAngle:false, hint:'' },
      radius:    { label:'Radius',            placeholder:'e.g. 7',   isAngle:false, hint:'' },
      base:      { label:'Base',              placeholder:'e.g. 8',   isAngle:false, hint:'' },
      height:    { label:'Height',            placeholder:'e.g. 4',   isAngle:false, hint:'Perpendicular height' },
      sideA:     { label:'Side A',            placeholder:'e.g. 5',   isAngle:false, hint:'' },
      sideB:     { label:'Side B',            placeholder:'e.g. 7',   isAngle:false, hint:'' },
      sideC:     { label:'Side C (base)',      placeholder:'e.g. 6',   isAngle:false, hint:'' },
      angleCdeg: { label:'Included Angle C',  placeholder:'e.g. 60',  isAngle:true,  hint:'Angle between sides A and B (0°–180°)', maxVal:'180' },
      semiA:     { label:'Semi-major Axis (a)',placeholder:'e.g. 8',   isAngle:false, hint:'Longer radius' },
      semiB:     { label:'Semi-minor Axis (b)',placeholder:'e.g. 5',   isAngle:false, hint:'Shorter radius' },
      angleDeg:  { label:'Sector Angle (θ)',  placeholder:'e.g. 90',  isAngle:true,  hint:'Central angle in degrees (0°–360°)', maxVal:'360' },
    },

    // ── Getters ───────────────────────────────
    get activeFields() {
      var key = this.shape === 'triangle'
                ? 'triangle_' + this.triMethod
                : this.shape;
      return this.fieldSets[key] || [];
    },

    get activeShapeLabel() {
      var s = this.shapeConfig.find(function(x){ return x.id === this.shape; }, this);
      return s ? s.label : this.shape;
    },

    // ── Init ──────────────────────────────────
    init() {},

    // ── Actions ──────────────────────────────
    selectShape(id) {
      this.shape = id;
      this.phase = 'idle';
      this.errorMsg = '';
    },

    setTriMethod(m) {
      this.triMethod = m;
      this.phase = 'idle';
      this.errorMsg = '';
    },

    setUnit(u) {
      if (u === this.unit) return;
      var oldFactor = this.unitToM[this.unit];
      var newFactor = this.unitToM[u];
      var factor = oldFactor / newFactor;
      var lengthFields = ['side','length','width','radius','base','height','sideA','sideB','sideC','semiA','semiB'];
      var self = this;
      lengthFields.forEach(function(f) {
        var v = parseFloat(self.dims[f]);
        if (!isNaN(v) && v > 0) {
          self.dims[f] = parseFloat((v * factor).toFixed(8)).toString();
        }
      });
      this.unit = u;
      if (this.phase === 'done') this._doCompute();
    },

    autoCompute() {
      // Only auto-compute if all required fields have positive values
      var self = this;
      var ready = this.activeFields.every(function(f) {
        var v = parseFloat(self.dims[f]);
        return !isNaN(v) && v > 0;
      });
      if (ready) this.compute();
    },

    // ── Core compute ─────────────────────────
    compute() {
      this.errorMsg = '';
      // Validate required fields
      var self = this;
      for (var i = 0; i < this.activeFields.length; i++) {
        var f = this.activeFields[i];
        var v = parseFloat(self.dims[f]);
        if (isNaN(v) || v <= 0) {
          self.errorMsg = 'Please enter a positive value for "' + self.fieldMeta[f].label + '".';
          return;
        }
        if (f === 'angleCdeg' && v >= 180) { self.errorMsg = 'Angle C must be less than 180°.'; return; }
        if (f === 'angleDeg'  && v >= 360) { self.errorMsg = 'Sector angle must be less than 360°.'; return; }
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
      var g = function(f) { return parseFloat(this.dims[f]); }.bind(this);
      var PI = Math.PI;
      var area, formula, formulaFull, formulaWorked, extras = [];
      var unitLbl = this.unit;

      // ── Compute area in current length unit² ──
      if (this.shape === 'square') {
        var s = g('side');
        area = s * s;
        formula = 'A = s²';
        formulaFull  = 'A = s × s';
        formulaWorked = 'A = ' + s + ' × ' + s;
        extras.push({ label: 'Perimeter', value: this.fmtNum(4*s) + ' ' + unitLbl });
        extras.push({ label: 'Diagonal',  value: this.fmtNum(Math.SQRT2*s) + ' ' + unitLbl });

      } else if (this.shape === 'rectangle') {
        var l = g('length'), w = g('width');
        area = l * w;
        formula = 'A = l × w';
        formulaFull   = 'A = length × width';
        formulaWorked = 'A = ' + l + ' × ' + w;
        extras.push({ label: 'Perimeter', value: this.fmtNum(2*(l+w)) + ' ' + unitLbl });
        extras.push({ label: 'Diagonal',  value: this.fmtNum(Math.sqrt(l*l+w*w)) + ' ' + unitLbl });

      } else if (this.shape === 'circle') {
        var r = g('radius');
        area = PI * r * r;
        formula = 'A = π × r²';
        formulaFull   = 'A = π × radius²';
        formulaWorked = 'A = π × ' + r + '²';
        extras.push({ label: 'Circumference', value: this.fmtNum(2*PI*r) + ' ' + unitLbl });
        extras.push({ label: 'Diameter',      value: this.fmtNum(2*r)    + ' ' + unitLbl });

      } else if (this.shape === 'triangle' && this.triMethod === 'bh') {
        var b = g('base'), h = g('height');
        area = 0.5 * b * h;
        formula = 'A = ½ × b × h';
        formulaFull   = 'A = (base × height) ÷ 2';
        formulaWorked = 'A = (' + b + ' × ' + h + ') ÷ 2';

      } else if (this.shape === 'triangle' && this.triMethod === '3s') {
        var a = g('sideA'), b2 = g('sideB'), c = g('sideC');
        var s2 = (a + b2 + c) / 2;
        var disc = s2 * (s2-a) * (s2-b2) * (s2-c);
        if (disc < 0) throw new Error('These three sides cannot form a valid triangle (triangle inequality failed).');
        area = Math.sqrt(disc);
        formula = "A = √(s(s-a)(s-b)(s-c))";
        formulaFull   = "Heron's: s = (a+b+c)/2,  A = √(s(s-a)(s-b)(s-c))";
        formulaWorked = 's = ' + this.fmtNum(s2) + ',  A = √(' + this.fmtNum(s2) + '×' + this.fmtNum(s2-a) + '×' + this.fmtNum(s2-b2) + '×' + this.fmtNum(s2-c) + ')';
        extras.push({ label: 'Perimeter', value: this.fmtNum(a+b2+c) + ' ' + unitLbl });
        extras.push({ label: 'Semi-perimeter (s)', value: this.fmtNum(s2) + ' ' + unitLbl });

      } else if (this.shape === 'triangle' && this.triMethod === 'sas') {
        var a = g('sideA'), b3 = g('sideB'), C = g('angleCdeg');
        var Crad = C * PI / 180;
        area = 0.5 * a * b3 * Math.sin(Crad);
        if (area <= 0) throw new Error('The angle produces a degenerate triangle. Check your inputs.');
        // Third side via cosine rule for perimeter
        var c3 = Math.sqrt(a*a + b3*b3 - 2*a*b3*Math.cos(Crad));
        formula = 'A = ½ × a × b × sin(C)';
        formulaFull   = 'A = (side_a × side_b × sin(C°)) ÷ 2';
        formulaWorked = 'A = (' + a + ' × ' + b3 + ' × sin(' + C + '°)) ÷ 2';
        extras.push({ label: 'Perimeter', value: this.fmtNum(a + b3 + c3) + ' ' + unitLbl });
        extras.push({ label: 'Side C (cosine rule)', value: this.fmtNum(c3) + ' ' + unitLbl });

      } else if (this.shape === 'parallelogram') {
        var b4 = g('base'), h4 = g('height');
        area = b4 * h4;
        formula = 'A = b × h';
        formulaFull   = 'A = base × perpendicular height';
        formulaWorked = 'A = ' + b4 + ' × ' + h4;

      } else if (this.shape === 'trapezoid') {
        var a5 = g('sideA'), b5 = g('sideB'), h5 = g('height');
        area = 0.5 * (a5 + b5) * h5;
        formula = 'A = ½(a + b) × h';
        formulaFull   = 'A = (parallel_side_a + parallel_side_b) ÷ 2 × height';
        formulaWorked = 'A = (' + a5 + ' + ' + b5 + ') ÷ 2 × ' + h5;
        extras.push({ label: 'Avg parallel width', value: this.fmtNum((a5+b5)/2) + ' ' + unitLbl });

      } else if (this.shape === 'ellipse') {
        var a6 = g('semiA'), b6 = g('semiB');
        area = PI * a6 * b6;
        // Ramanujan perimeter approximation
        var h6 = Math.pow(a6 - b6, 2) / Math.pow(a6 + b6, 2);
        var perim = PI * (a6 + b6) * (1 + 3*h6/(10 + Math.sqrt(4 - 3*h6)));
        var ecc = Math.sqrt(1 - (b6*b6)/(a6*a6));
        formula = 'A = π × a × b';
        formulaFull   = 'A = π × semi_major × semi_minor';
        formulaWorked = 'A = π × ' + a6 + ' × ' + b6;
        extras.push({ label: 'Perimeter (approx.)', value: this.fmtNum(perim) + ' ' + unitLbl });
        extras.push({ label: 'Eccentricity', value: this.fmtNum(ecc) });
        extras.push({ label: 'Major axis', value: this.fmtNum(2*a6) + ' ' + unitLbl });
        extras.push({ label: 'Minor axis', value: this.fmtNum(2*b6) + ' ' + unitLbl });

      } else if (this.shape === 'sector') {
        var r7 = g('radius'), deg7 = g('angleDeg');
        var rad7 = deg7 * PI / 180;
        area = 0.5 * r7 * r7 * rad7;
        var arc = r7 * rad7;
        var chord = 2 * r7 * Math.sin(rad7 / 2);
        formula = 'A = ½ × r² × θ';
        formulaFull   = 'A = (radius² × angle_in_radians) ÷ 2';
        formulaWorked = 'A = (' + r7 + '² × ' + this.fmtNum(rad7) + ' rad) ÷ 2';
        extras.push({ label: 'Arc Length', value: this.fmtNum(arc) + ' ' + unitLbl });
        extras.push({ label: 'Chord Length', value: this.fmtNum(chord) + ' ' + unitLbl });
        extras.push({ label: 'Total Perimeter (arc + 2r)', value: this.fmtNum(arc + 2*r7) + ' ' + unitLbl });
        extras.push({ label: 'Angle (radians)', value: this.fmtNum(rad7) + ' rad' });

      } else {
        throw new Error('Unknown shape: ' + this.shape);
      }

      if (!isFinite(area) || area <= 0) {
        throw new Error('Calculated area is invalid. Please check your inputs.');
      }

      // ── Convert to m² ──────────────────────
      var f2m = this.unitToM[this.unit];
      var areaM2 = area * f2m * f2m;

      this.result = {
        area, formula, formulaFull, formulaWorked, extras,
        areaM2,
        areaMm2:  areaM2 * 1e6,
        areaCm2:  areaM2 * 1e4,
        areaKm2:  areaM2 / 1e6,
        areaIn2:  areaM2 / (0.0254 * 0.0254),
        areaFt2:  areaM2 / (0.3048 * 0.3048),
        areaYd2:  areaM2 / (0.9144 * 0.9144),
        areaAcre: areaM2 / 4046.856,
        areaHa:   areaM2 / 10000,
      };
    },

    // ── Formatters ────────────────────────────
    fmtNum(v, d) {
      if (v === undefined || v === null || !isFinite(v)) return '—';
      var dec = d !== undefined ? d : 4;
      // Auto-adjust decimals for very small numbers
      if (Math.abs(v) > 0 && Math.abs(v) < 0.001) return v.toExponential(3);
      var s = parseFloat(v.toFixed(dec));
      return s.toLocaleString('en-US', { minimumFractionDigits:0, maximumFractionDigits:dec });
    },

    fmtSci(v) {
      if (v === undefined || v === null || !isFinite(v)) return '—';
      if (Math.abs(v) >= 0.001 && Math.abs(v) < 1e9) return this.fmtNum(v, 4);
      return v.toExponential(3);
    },
  };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\area-calculator.blade.php ENDPATH**/ ?>