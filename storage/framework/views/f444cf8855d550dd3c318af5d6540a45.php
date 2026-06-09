<?php $__env->startSection('title', $tool->name . ' - ' . config('app.name')); ?>
<?php $__env->startSection('meta_description', $tool->description ?? 'Calculate file transfer time, required bandwidth, or data amount for any connection speed.'); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ══════════════════════════════════════════════
   Bandwidth Calculator  —  prefix: bw-
   Theme: Blue (tech / network)
══════════════════════════════════════════════ */

/* Hero */
.bw-hero { font-size:clamp(2.4rem,5.5vw,4rem); font-weight:900; line-height:1; letter-spacing:-.04em;
           background:linear-gradient(135deg,#1e3a8a,#2563eb,#3b82f6);
           -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }

/* Mode tab */
.bw-tab { flex:1; padding:.5rem .5rem; border-radius:.5rem; font-size:.74rem; font-weight:700;
           color:#374151; cursor:pointer; border:none; background:none; transition:all .15s;
           text-align:center; line-height:1.3; }
.bw-tab.active { background:#fff; color:#1d4ed8; box-shadow:0 1px 4px rgba(0,0,0,.1); }
.bw-tab:not(.active):hover { background:#eff6ff; }

/* Stat tile */
.bw-stat { background:#fff; border:1.5px solid #bfdbfe; border-radius:1.125rem;
            padding:.9rem .75rem; display:flex; flex-direction:column; align-items:center;
            gap:.25rem; text-align:center; transition:all .15s; min-width:0; }
.bw-stat:hover { transform:translateY(-1px); box-shadow:0 4px 16px rgba(37,99,235,.09); }
.bw-stat-lbl { font-size:.58rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.08em; }
.bw-stat-val { font-size:1.05rem; font-weight:900; line-height:1.2; word-break:break-all; }
.bw-stat-val.blue   { background:linear-gradient(135deg,#1e3a8a,#2563eb); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.bw-stat-val.sky    { background:linear-gradient(135deg,#075985,#0ea5e9); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.bw-stat-val.violet { background:linear-gradient(135deg,#4c1d95,#7c3aed); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.bw-stat-val.green  { background:linear-gradient(135deg,#14532d,#16a34a); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.bw-stat-val.amber  { background:linear-gradient(135deg,#78350f,#d97706); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.bw-stat-sub { font-size:.6rem; color:#94a3b8; }

/* Select+input compound field */
.bw-field-row { display:flex; align-items:stretch; gap:0; }
.bw-field-row .form-input:first-child { border-radius:.75rem 0 0 .75rem !important; flex:1; }
.bw-field-row select.form-input       { border-radius:0 .75rem .75rem 0 !important; border-left:none; width:auto; min-width:72px; font-weight:700; color:#1d4ed8; background:#eff6ff; }

/* Section divider */
.bw-div { display:flex; align-items:center; gap:.6rem; color:#94a3b8; font-size:.62rem;
           font-weight:800; text-transform:uppercase; letter-spacing:.1em; }
.bw-div::before,.bw-div::after { content:''; flex:1; height:1px; background:#bfdbfe; }

/* Speed comparison row */
.bw-speed-row { display:grid; grid-template-columns:1.4fr .9fr 1.2fr 1.3fr; gap:.4rem;
                 align-items:center; padding:.4rem .6rem; border-radius:.5rem; font-size:.76rem; }
.bw-speed-row:hover { background:#eff6ff; }
.bw-speed-row.head  { font-size:.58rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.07em; }
.bw-speed-row.current { background:#dbeafe; border:1px solid #93c5fd; font-weight:700; }
.bw-speed-bar { height:6px; border-radius:9999px; background:#2563eb; transition:width .5s ease; }

/* Preset pill */
.bw-preset { padding:.3rem .65rem; border-radius:9999px; font-size:.7rem; font-weight:700;
              background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; cursor:pointer;
              white-space:nowrap; transition:all .15s; }
.bw-preset:hover { background:#1d4ed8; color:#fff; border-color:#1d4ed8; }

/* Data-equivalent row */
.bw-eq-row { display:flex; align-items:center; justify-content:space-between; gap:.5rem;
              padding:.4rem .6rem; border-radius:.5rem; font-size:.78rem; }
.bw-eq-row:hover { background:#eff6ff; }

/* Conversion table */
.bw-conv-row { display:flex; justify-content:space-between; padding:.35rem .6rem;
                border-radius:.5rem; font-size:.78rem; }
.bw-conv-row:hover { background:#eff6ff; }
.bw-conv-row.highlight { background:#dbeafe; font-weight:700; border:1px solid #93c5fd; }

/* Animate in */
@keyframes bwIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
.bw-in { animation:bwIn .3s ease-out; }

/* Warning banner */
.bw-warn { background:#fef3c7; border:1.5px solid #fcd34d; border-radius:.875rem;
            padding:.6rem 1rem; font-size:.75rem; color:#92400e; display:flex; gap:.5rem; }
</style>

<div class="min-h-screen bg-gray-50 py-8" x-data="bwCalc()" x-init="init()">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4"
           style="background:linear-gradient(135deg,#1e40af,#2563eb)">
        <span class="text-3xl">📡</span>
      </div>
      <h1 class="text-3xl font-bold text-gray-900">Bandwidth Calculator</h1>
      <p class="mt-2 text-gray-500">Calculate transfer time, required speed, or data volume for any connection</p>
    </div>

    
    <div x-show="errorMsg" x-transition
         class="mb-6 p-4 rounded-xl border border-red-200 bg-red-50 text-red-700 text-sm flex items-start gap-2">
      <span class="text-lg leading-none">⚠️</span>
      <span x-text="errorMsg"></span>
    </div>

    <div class="lg:grid lg:grid-cols-5 lg:gap-8 space-y-6 lg:space-y-0">

      
      <div class="lg:col-span-2 space-y-5">

        
        <div class="card p-4">
          <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Calculation Mode</p>
          <div class="flex bg-blue-50 p-1.5 rounded-xl gap-1">
            <button class="bw-tab" :class="mode==='time'  ? 'active':''" @click="setMode('time')">
              ⏱ Transfer<br>Time
            </button>
            <button class="bw-tab" :class="mode==='speed' ? 'active':''" @click="setMode('speed')">
              📡 Speed<br>Required
            </button>
            <button class="bw-tab" :class="mode==='data'  ? 'active':''" @click="setMode('data')">
              💾 Data<br>Amount
            </button>
          </div>
        </div>

        
        <div class="card p-5 space-y-4">
          <h2 class="font-semibold text-gray-800">Enter Values</h2>

          
          <div x-show="mode !== 'data'">
            <label class="form-label">File Size</label>
            <div class="bw-field-row">
              <input type="number" step="any" min="0"
                     x-model="fileSize"
                     placeholder="e.g. 700"
                     @input.debounce.600ms="autoCompute()"
                     class="form-input" />
              <select x-model="fileSizeUnit" @change="autoCompute()" class="form-input">
                <template x-for="u in fileSizeUnits" :key="u.id">
                  <option :value="u.id" x-text="u.label"></option>
                </template>
              </select>
            </div>
          </div>

          
          <div x-show="mode !== 'data'" class="flex items-center gap-3">
            <button @click="multiFile = !multiFile"
                    :class="multiFile ? 'bg-blue-600':'bg-gray-300'"
                    class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors flex-shrink-0">
              <span :class="multiFile ? 'translate-x-5':'translate-x-1'"
                    class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition-transform"></span>
            </button>
            <div x-show="!multiFile" class="text-xs text-gray-400 cursor-pointer" @click="multiFile=true">
              Multiple files? Click to enable
            </div>
            <div x-show="multiFile" class="flex items-center gap-2 flex-1">
              <span class="text-xs text-gray-600 whitespace-nowrap">×</span>
              <input type="number" step="1" min="1" x-model.number="fileCount"
                     class="form-input flex-1 text-sm py-1" />
              <span class="text-xs text-gray-400 whitespace-nowrap">files</span>
            </div>
          </div>

          
          <div x-show="mode !== 'speed'">
            <label class="form-label">Connection Speed</label>
            <div class="bw-field-row">
              <input type="number" step="any" min="0"
                     x-model="bandwidth"
                     placeholder="e.g. 100"
                     @input.debounce.600ms="autoCompute()"
                     class="form-input" />
              <select x-model="bandwidthUnit" @change="autoCompute()" class="form-input">
                <template x-for="u in bandwidthUnits" :key="u.id">
                  <option :value="u.id" x-text="u.label"></option>
                </template>
              </select>
            </div>
          </div>

          
          <div x-show="mode !== 'time'">
            <label class="form-label" x-text="mode==='speed' ? 'Required Transfer Time' : 'Transfer Duration'"></label>
            <div class="bw-field-row">
              <input type="number" step="any" min="0"
                     x-model="transferTime"
                     placeholder="e.g. 30"
                     @input.debounce.600ms="autoCompute()"
                     class="form-input" />
              <select x-model="transferTimeUnit" @change="autoCompute()" class="form-input">
                <template x-for="u in timeUnits" :key="u.id">
                  <option :value="u.id" x-text="u.label"></option>
                </template>
              </select>
            </div>
          </div>

          
          <div>
            <div class="flex items-center justify-between mb-1">
              <label class="form-label mb-0 text-xs">Network Overhead</label>
              <span class="text-xs font-bold text-blue-600" x-text="overhead + '%'"></span>
            </div>
            <input type="range" min="0" max="20" step="1" x-model.number="overhead"
                   class="w-full accent-blue-600" />
            <div class="flex justify-between text-xs text-gray-400 mt-0.5">
              <span>0%</span><span>TCP/IP headers (~5% typical)</span><span>20%</span>
            </div>
          </div>
        </div>

        
        <div x-show="mode !== 'data'" class="card p-4">
          <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Quick File Presets</p>
          <div class="flex flex-wrap gap-1.5">
            <template x-for="p in filePresets" :key="p.label">
              <button @click="applyFilePreset(p)" class="bw-preset" x-text="p.label"></button>
            </template>
          </div>
        </div>

        
        <div x-show="mode !== 'speed'" class="card p-4">
          <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Quick Speed Presets</p>
          <div class="flex flex-wrap gap-1.5">
            <template x-for="p in speedPresets" :key="p.label">
              <button @click="applySpeedPreset(p)" class="bw-preset" x-text="p.label"></button>
            </template>
          </div>
        </div>

        
        <div class="flex gap-3">
          <button @click="compute()"
                  class="btn btn-primary flex-1 py-3 font-bold text-base"
                  style="background:linear-gradient(135deg,#1e40af,#2563eb)">
            Calculate
          </button>
          <button @click="reset()" class="btn btn-secondary py-3 px-5 font-semibold">
            Reset
          </button>
        </div>

        
        <div class="card p-4 bg-blue-50 border border-blue-100">
          <p class="text-xs font-bold text-blue-800 uppercase tracking-wide mb-1.5">💡 Bits vs Bytes</p>
          <p class="text-xs text-blue-700">
            ISPs advertise speeds in <strong>Mbps</strong> (megabits/sec). Files are measured in
            <strong>MB</strong> (megabytes). Since 1 byte = 8 bits, a 100 Mbps connection
            downloads at ~12.5 MB/s.
          </p>
        </div>

      </div>

      
      <div class="lg:col-span-3 space-y-5">

        
        <div x-show="phase==='idle'" class="card p-12 text-center text-gray-400">
          <div class="text-5xl mb-4">📡</div>
          <p class="font-medium">Enter values and press Calculate</p>
          <p class="text-sm mt-1">Transfer time, speed breakdowns, and comparisons will appear here</p>
        </div>

        
        <div x-show="phase==='loading'" class="card p-12 text-center">
          <div class="inline-block w-7 h-7 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mb-3"></div>
          <p class="text-gray-500 text-sm">Calculating…</p>
        </div>

        <template x-if="phase==='done'">
          <div class="space-y-5 bw-in">

            
            <div class="rounded-2xl p-6 text-white"
                 style="background:linear-gradient(135deg,#1e3a8a,#1d4ed8,#2563eb)">
              <div class="flex items-start justify-between flex-wrap gap-4">
                <div>
                  <p class="text-blue-200 text-sm font-medium mb-1" x-text="result.heroLabel"></p>
                  <div class="text-4xl font-black tracking-tight" x-text="result.heroValue"></div>
                  <p class="text-blue-200 text-sm mt-1.5" x-text="result.heroSub"></p>
                </div>
                <div class="text-right flex-shrink-0">
                  <p class="text-blue-200 text-xs mb-0.5" x-text="result.metaLabel1"></p>
                  <p class="text-lg font-bold text-white" x-text="result.metaValue1"></p>
                  <p class="text-blue-200 text-xs mt-1 mb-0.5" x-text="result.metaLabel2"></p>
                  <p class="text-lg font-bold text-white" x-text="result.metaValue2"></p>
                </div>
              </div>
              <div x-show="result.overheadNote" class="mt-3 pt-3 border-t border-blue-700 text-xs text-blue-200">
                <span x-text="result.overheadNote"></span>
              </div>
            </div>

            
            <div x-show="mode==='time'" class="grid grid-cols-2 sm:grid-cols-4 gap-3">
              <div class="bw-stat">
                <span class="bw-stat-lbl">Days</span>
                <span class="bw-stat-val blue"   x-text="result.time.days"></span>
              </div>
              <div class="bw-stat">
                <span class="bw-stat-lbl">Hours</span>
                <span class="bw-stat-val sky"    x-text="result.time.hours"></span>
              </div>
              <div class="bw-stat">
                <span class="bw-stat-lbl">Minutes</span>
                <span class="bw-stat-val violet" x-text="result.time.mins"></span>
              </div>
              <div class="bw-stat">
                <span class="bw-stat-lbl">Seconds</span>
                <span class="bw-stat-val green"  x-text="result.time.secs"></span>
              </div>
            </div>

            
            <div class="card p-5">
              <p class="bw-div mb-3" x-text="mode==='data' ? 'Data Volume' : 'File Size'"></p>
              <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                <template x-for="row in result.sizeRows" :key="row.unit">
                  <div class="bw-conv-row rounded-lg" :class="row.highlight ? 'highlight' : ''">
                    <span class="text-gray-500 text-xs font-bold uppercase" x-text="row.unit"></span>
                    <span class="font-bold" :class="row.highlight ? 'text-blue-700':'text-gray-800'"
                          x-text="row.value"></span>
                  </div>
                </template>
              </div>
            </div>

            
            <div class="card p-5">
              <p class="bw-div mb-3" x-text="mode==='speed' ? 'Required Speed' : 'Connection Speed'"></p>
              <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                <template x-for="row in result.speedRows" :key="row.unit">
                  <div class="bw-conv-row rounded-lg" :class="row.highlight ? 'highlight' : ''">
                    <span class="text-gray-500 text-xs font-bold uppercase" x-text="row.unit"></span>
                    <span class="font-bold" :class="row.highlight ? 'text-blue-700':'text-gray-800'"
                          x-text="row.value"></span>
                  </div>
                </template>
              </div>
            </div>

            
            <div class="card p-5" x-show="mode !== 'speed'">
              <p class="bw-div mb-3">
                <span x-text="mode==='time' ? 'Transfer Time at Common Speeds' : 'Data Transferred in Your Time'"></span>
              </p>
              <div class="bw-speed-row head">
                <span>Connection</span>
                <span>Speed</span>
                <span x-text="mode==='time' ? 'Transfer Time' : 'Data Amount'"></span>
                <span>vs Your Speed</span>
              </div>
              <template x-for="row in result.speedComp" :key="row.name">
                <div class="bw-speed-row" :class="row.isCurrent ? 'current':''">
                  <span class="font-medium text-gray-700">
                    <span x-text="row.icon + ' ' + row.name"></span>
                    <span x-show="row.isCurrent" class="text-xs text-blue-600 ml-1">← you</span>
                  </span>
                  <span class="text-gray-500 text-xs" x-text="row.speedLabel"></span>
                  <span class="font-semibold" :class="row.isCurrent ? 'text-blue-700' : 'text-gray-700'"
                        x-text="row.result"></span>
                  <div class="flex items-center gap-1">
                    <div class="flex-1 h-1.5 bg-blue-100 rounded-full overflow-hidden">
                      <div class="bw-speed-bar" :style="'width:' + Math.min(100, row.barPct) + '%'"></div>
                    </div>
                    <span class="text-xs text-gray-400" x-text="row.relLabel"></span>
                  </div>
                </div>
              </template>
            </div>

            
            <div class="card p-5" x-show="result.equivalents && result.equivalents.length > 0">
              <p class="bw-div mb-3">What You Can Transfer</p>
              <template x-for="eq in result.equivalents" :key="eq.name">
                <div class="bw-eq-row">
                  <span class="text-gray-600">
                    <span x-text="eq.icon"></span>
                    <span class="ml-1" x-text="eq.name"></span>
                  </span>
                  <span class="font-bold text-blue-700" x-text="eq.count"></span>
                </div>
              </template>
              <p class="text-xs text-gray-400 mt-2 border-t border-gray-100 pt-2">
                Based on typical file sizes (MP3: 4 MB, JPEG: 3 MB, HD Movie: 4 GB, 4K Movie: 60 GB)
              </p>
            </div>

            
            <div class="card p-5">
              <p class="bw-div mb-3">Effective Throughput</p>
              <div class="space-y-0.5">
                <template x-for="row in result.throughputRows" :key="row.label">
                  <div class="bw-eq-row">
                    <span class="text-gray-600" x-text="row.label"></span>
                    <span class="font-bold text-gray-800" x-text="row.value"></span>
                  </div>
                </template>
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
function bwCalc() {
  return {
    // ── State ─────────────────────────────────
    mode:             'time',   // time | speed | data
    fileSize:         '',
    fileSizeUnit:     'MB',
    fileCount:        1,
    multiFile:        false,
    bandwidth:        '',
    bandwidthUnit:    'Mbps',
    transferTime:     '',
    transferTimeUnit: 'min',
    overhead:         5,
    phase:            'idle',
    errorMsg:         '',
    result:           null,

    // ── Unit tables ───────────────────────────
    fileSizeUnits: [
      { id:'b',   label:'b  (bit)'   },
      { id:'B',   label:'B  (byte)'  },
      { id:'KB',  label:'KB'         },
      { id:'MB',  label:'MB'         },
      { id:'GB',  label:'GB'         },
      { id:'TB',  label:'TB'         },
      { id:'KiB', label:'KiB'        },
      { id:'MiB', label:'MiB'        },
      { id:'GiB', label:'GiB'        },
      { id:'TiB', label:'TiB'        },
    ],

    bandwidthUnits: [
      { id:'bps',   label:'bps'   },
      { id:'Kbps',  label:'Kbps'  },
      { id:'Mbps',  label:'Mbps'  },
      { id:'Gbps',  label:'Gbps'  },
      { id:'Tbps',  label:'Tbps'  },
      { id:'KB_s',  label:'KB/s'  },
      { id:'MB_s',  label:'MB/s'  },
      { id:'GB_s',  label:'GB/s'  },
    ],

    timeUnits: [
      { id:'ms',  label:'ms (millisec)' },
      { id:'s',   label:'s (seconds)'   },
      { id:'min', label:'min (minutes)' },
      { id:'hr',  label:'hr (hours)'    },
      { id:'day', label:'days'          },
    ],

    // factors: file size unit → bytes
    sizeToBytes: {
      b:   1/8, B:1, KB:1e3, MB:1e6, GB:1e9, TB:1e12,
      KiB:1024, MiB:1048576, GiB:1073741824, TiB:1099511627776,
    },

    // factors: bandwidth unit → bps
    bwToBps: {
      bps:1, Kbps:1e3, Mbps:1e6, Gbps:1e9, Tbps:1e12,
      KB_s:8e3, MB_s:8e6, GB_s:8e9,
    },

    // factors: time unit → seconds
    timeToSec: { ms:0.001, s:1, min:60, hr:3600, day:86400 },

    // ── Presets ───────────────────────────────
    filePresets: [
      { label:'MP3 (4 MB)',       size:4,    unit:'MB'  },
      { label:'Photo (3 MB)',     size:3,    unit:'MB'  },
      { label:'RAW (25 MB)',      size:25,   unit:'MB'  },
      { label:'App (150 MB)',     size:150,  unit:'MB'  },
      { label:'SD Movie (700 MB)',size:700,  unit:'MB'  },
      { label:'HD Movie (4 GB)', size:4,    unit:'GB'  },
      { label:'4K Movie (60 GB)',size:60,   unit:'GB'  },
      { label:'Game (50 GB)',     size:50,   unit:'GB'  },
    ],

    speedPresets: [
      { label:'3G (3 Mbps)',      bw:3,    unit:'Mbps' },
      { label:'4G (20 Mbps)',     bw:20,   unit:'Mbps' },
      { label:'5G (200 Mbps)',    bw:200,  unit:'Mbps' },
      { label:'25 Mbps Cable',    bw:25,   unit:'Mbps' },
      { label:'100 Mbps',         bw:100,  unit:'Mbps' },
      { label:'1 Gbps',           bw:1,    unit:'Gbps' },
      { label:'WiFi 5 (300 Mbps)',bw:300,  unit:'Mbps' },
      { label:'WiFi 6 (1.2 Gbps)',bw:1.2,  unit:'Gbps' },
    ],

    speedCompTable: [
      { name:'Dial-up',       mbps:0.056,   icon:'📞' },
      { name:'ADSL Basic',    mbps:1,       icon:'📡' },
      { name:'ADSL2+',        mbps:8,       icon:'📡' },
      { name:'3G',            mbps:3,       icon:'📱' },
      { name:'4G / LTE',      mbps:20,      icon:'📱' },
      { name:'5G',            mbps:200,     icon:'📱' },
      { name:'25 Mbps Cable', mbps:25,      icon:'🔌' },
      { name:'100 Mbps',      mbps:100,     icon:'🔌' },
      { name:'Gigabit',       mbps:1000,    icon:'⚡' },
      { name:'WiFi 5 (5GHz)', mbps:300,     icon:'📶' },
      { name:'WiFi 6',        mbps:1200,    icon:'📶' },
      { name:'10 Gbps Fiber', mbps:10000,   icon:'🌐' },
    ],

    // ── Init ─────────────────────────────────
    init() {},

    // ── Actions ──────────────────────────────
    setMode(m) {
      this.mode  = m;
      this.phase = 'idle';
      this.errorMsg = '';
    },

    reset() {
      this.fileSize = ''; this.bandwidth = ''; this.transferTime = '';
      this.fileCount = 1; this.multiFile = false; this.overhead = 5;
      this.phase = 'idle'; this.errorMsg = ''; this.result = null;
    },

    applyFilePreset(p) {
      this.fileSize     = String(p.size);
      this.fileSizeUnit = p.unit;
    },

    applySpeedPreset(p) {
      this.bandwidth     = String(p.bw);
      this.bandwidthUnit = p.unit;
    },

    autoCompute() {
      var fs = parseFloat(this.fileSize), bw = parseFloat(this.bandwidth), tt = parseFloat(this.transferTime);
      if (this.mode === 'time'  && fs > 0 && bw > 0) return this.compute();
      if (this.mode === 'speed' && fs > 0 && tt > 0) return this.compute();
      if (this.mode === 'data'  && bw > 0 && tt > 0) return this.compute();
    },

    compute() {
      this.errorMsg = '';
      var fs = parseFloat(this.fileSize), bw = parseFloat(this.bandwidth), tt = parseFloat(this.transferTime);

      if (this.mode === 'time') {
        if (isNaN(fs) || fs <= 0) { this.errorMsg = 'Please enter a valid File Size greater than zero.'; return; }
        if (isNaN(bw) || bw <= 0) { this.errorMsg = 'Please enter a valid Connection Speed greater than zero.'; return; }
      } else if (this.mode === 'speed') {
        if (isNaN(fs) || fs <= 0) { this.errorMsg = 'Please enter a valid File Size greater than zero.'; return; }
        if (isNaN(tt) || tt <= 0) { this.errorMsg = 'Please enter a valid Transfer Time greater than zero.'; return; }
      } else {
        if (isNaN(bw) || bw <= 0) { this.errorMsg = 'Please enter a valid Connection Speed greater than zero.'; return; }
        if (isNaN(tt) || tt <= 0) { this.errorMsg = 'Please enter a valid Transfer Duration greater than zero.'; return; }
      }

      this.phase = 'loading';
      var self = this;
      setTimeout(function() {
        try {
          self._doCompute(fs, bw, tt);
          self.phase = 'done';
        } catch(e) {
          self.errorMsg = e.message;
          self.phase = 'idle';
        }
      }, 80);
    },

    _doCompute(fs, bw, tt) {
      var self       = this;
      var ovFactor   = 1 + self.overhead / 100;
      var count      = self.multiFile ? Math.max(1, Math.round(self.fileCount)) : 1;

      // ── Convert to base units ──────────────
      var totalBytes, bps, seconds;

      if (self.mode === 'time' || self.mode === 'speed') {
        totalBytes = fs * self.sizeToBytes[self.fileSizeUnit] * count;
      }
      if (self.mode !== 'speed') {
        bps = bw * self.bwToBps[self.bandwidthUnit];
      }
      if (self.mode !== 'time') {
        seconds = tt * self.timeToSec[self.transferTimeUnit];
      }

      // ── Core calculation ───────────────────
      var totalBits, resultSecs, resultBps, resultBytes;

      if (self.mode === 'time') {
        totalBits  = totalBytes * 8 * ovFactor;
        resultSecs = totalBits / bps;           // transfer time in seconds
      } else if (self.mode === 'speed') {
        totalBits  = totalBytes * 8 * ovFactor;
        resultBps  = totalBits / seconds;       // required speed in bps
      } else {
        resultBytes = (bps * seconds) / 8 / ovFactor;  // data amount in bytes
        totalBytes  = resultBytes;
      }

      // ── Helpers ────────────────────────────
      function fmtTime(secs) {
        if (!isFinite(secs) || secs < 0) return '—';
        if (secs < 0.001) return (secs * 1000).toFixed(2) + ' ms';
        if (secs < 1)     return secs.toFixed(3) + ' s';
        var d = Math.floor(secs / 86400);
        var h = Math.floor((secs % 86400) / 3600);
        var m = Math.floor((secs % 3600) / 60);
        var s = Math.floor(secs % 60);
        if (d > 365) return (secs / 86400 / 365.25).toFixed(1) + ' years';
        var parts = [];
        if (d) parts.push(d + 'd');
        if (h) parts.push(h + 'h');
        if (m) parts.push(m + 'm');
        if (s || !parts.length) parts.push(s + 's');
        return parts.join(' ');
      }

      function smartBytes(b) {
        if (b < 1e3)  return b.toFixed(2) + ' B';
        if (b < 1e6)  return (b/1e3).toFixed(2) + ' KB';
        if (b < 1e9)  return (b/1e6).toFixed(2) + ' MB';
        if (b < 1e12) return (b/1e9).toFixed(2) + ' GB';
        return (b/1e12).toFixed(2) + ' TB';
      }

      function smartBps(bits) {
        if (bits < 1e3)  return bits.toFixed(2) + ' bps';
        if (bits < 1e6)  return (bits/1e3).toFixed(2) + ' Kbps';
        if (bits < 1e9)  return (bits/1e6).toFixed(2) + ' Mbps';
        if (bits < 1e12) return (bits/1e9).toFixed(2) + ' Gbps';
        return (bits/1e12).toFixed(2) + ' Tbps';
      }

      function fmtNum(v, d) {
        return parseFloat(v.toFixed(d)).toLocaleString('en-US', {minimumFractionDigits:0, maximumFractionDigits:d});
      }

      // ── Hero values ────────────────────────
      var heroValue, heroLabel, heroSub, metaLabel1, metaValue1, metaLabel2, metaValue2, overheadNote;

      if (self.mode === 'time') {
        heroLabel  = count > 1 ? 'Transfer Time (' + count + ' files)' : 'Transfer Time';
        heroValue  = fmtTime(resultSecs);
        heroSub    = fmtNum(resultSecs, 2) + ' seconds total';
        metaLabel1 = 'File Size';
        metaValue1 = smartBytes(totalBytes);
        metaLabel2 = 'Speed';
        metaValue2 = smartBps(bps);
        if (self.overhead > 0) overheadNote = 'Includes ' + self.overhead + '% network overhead. Raw time: ' + fmtTime(totalBytes * 8 / bps);

      } else if (self.mode === 'speed') {
        heroLabel  = 'Required Speed';
        heroValue  = smartBps(resultBps);
        heroSub    = fmtNum(resultBps / 1e6, 4) + ' Mbps  /  ' + fmtNum(resultBps / 8e6, 4) + ' MB/s';
        metaLabel1 = 'File Size';
        metaValue1 = smartBytes(totalBytes);
        metaLabel2 = 'In';
        metaValue2 = fmtTime(seconds);
        if (self.overhead > 0) overheadNote = 'Includes ' + self.overhead + '% overhead. Without overhead: ' + smartBps(totalBytes * 8 / seconds);
        bps = resultBps;

      } else {
        heroLabel  = 'Data Transferred';
        heroValue  = smartBytes(resultBytes);
        heroSub    = smartBytes(resultBytes / count) + ' per second × ' + fmtTime(seconds);
        metaLabel1 = 'Speed';
        metaValue1 = smartBps(bps);
        metaLabel2 = 'Duration';
        metaValue2 = fmtTime(seconds);
        if (self.overhead > 0) overheadNote = 'After ' + self.overhead + '% overhead deduction. Gross capacity: ' + smartBytes(resultBytes * ovFactor);
      }

      // ── Time breakdown (mode: time) ─────────
      var timeBreakdown = { days:'—', hours:'—', mins:'—', secs:'—' };
      if (self.mode === 'time') {
        var d = Math.floor(resultSecs / 86400);
        var h = Math.floor((resultSecs % 86400) / 3600);
        var m = Math.floor((resultSecs % 3600) / 60);
        var s = Math.floor(resultSecs % 60);
        timeBreakdown = { days: fmtNum(d,0), hours: fmtNum(h,0), mins: fmtNum(m,0), secs: fmtNum(s,0) };
      }

      // ── Size rows (all units) ───────────────
      var bytesVal = totalBytes;
      var sizeRows = [
        { unit:'bits',  value: fmtNum(bytesVal * 8, 2),    highlight: false },
        { unit:'bytes', value: fmtNum(bytesVal, 2),        highlight: false },
        { unit:'KB',    value: fmtNum(bytesVal / 1e3, 4),  highlight: false },
        { unit:'MB',    value: fmtNum(bytesVal / 1e6, 4),  highlight: false },
        { unit:'GB',    value: fmtNum(bytesVal / 1e9, 6),  highlight: false },
        { unit:'KiB',   value: fmtNum(bytesVal / 1024, 4), highlight: false },
        { unit:'MiB',   value: fmtNum(bytesVal / 1048576, 4), highlight: false },
        { unit:'GiB',   value: fmtNum(bytesVal / 1073741824, 6), highlight: false },
      ];
      // Highlight most readable row
      var bestSizeUnit = bytesVal < 1e3 ? 'bytes' : bytesVal < 1e6 ? 'KB' : bytesVal < 1e9 ? 'MB' : 'GB';
      sizeRows.forEach(function(r){ if (r.unit === bestSizeUnit) r.highlight = true; });

      // ── Speed rows (all units) ──────────────
      var speedRows = [
        { unit:'bps',   value: fmtNum(bps, 2),          highlight: false },
        { unit:'Kbps',  value: fmtNum(bps / 1e3, 3),    highlight: false },
        { unit:'Mbps',  value: fmtNum(bps / 1e6, 4),    highlight: false },
        { unit:'Gbps',  value: fmtNum(bps / 1e9, 6),    highlight: false },
        { unit:'KB/s',  value: fmtNum(bps / 8e3, 3),    highlight: false },
        { unit:'MB/s',  value: fmtNum(bps / 8e6, 4),    highlight: false },
        { unit:'GB/s',  value: fmtNum(bps / 8e9, 6),    highlight: false },
      ];
      var bestSpeedUnit = bps < 1e3 ? 'bps' : bps < 1e6 ? 'Kbps' : bps < 1e9 ? 'Mbps' : 'Gbps';
      speedRows.forEach(function(r){ if (r.unit === bestSpeedUnit) r.highlight = true; });

      // ── Speed comparison ───────────────────
      var currentBpsMbps = bps / 1e6;
      var maxBpsMbps     = Math.max(currentBpsMbps, 10000);

      var speedComp = self.speedCompTable.map(function(s) {
        var sBps    = s.mbps * 1e6;
        var isCurr  = Math.abs(sBps - bps) / bps < 0.01;
        var relLabel = sBps >= bps ? (sBps / bps >= 2 ? (sBps/bps).toFixed(0) + '×' : 'similar') : '—';
        var barPct  = Math.min(100, (s.mbps / maxBpsMbps) * 100);
        var resultVal, speedLabel;

        speedLabel = s.mbps < 1 ? (s.mbps * 1000).toFixed(0) + ' Kbps' :
                     s.mbps < 1000 ? s.mbps.toFixed(0) + ' Mbps' : (s.mbps/1000).toFixed(0) + ' Gbps';

        if (self.mode === 'time' || self.mode === 'speed') {
          var t = (totalBytes * 8 * ovFactor) / sBps;
          resultVal = fmtTime(t);
        } else {
          var dataBytes = (sBps * seconds) / 8 / ovFactor;
          resultVal = smartBytes(dataBytes);
        }

        return {
          name: s.name, icon: s.icon,
          speedLabel: speedLabel,
          result: resultVal,
          isCurrent: isCurr,
          barPct: barPct,
          relLabel: relLabel,
        };
      });

      // ── Data equivalents ───────────────────
      var eqBytes = totalBytes;
      var equivRaw = [
        { name:'MP3 Songs (4 MB)',      size:4e6,   icon:'🎵' },
        { name:'JPEG Photos (3 MB)',     size:3e6,   icon:'📷' },
        { name:'RAW Photos (25 MB)',     size:25e6,  icon:'📸' },
        { name:'eBooks (1 MB)',          size:1e6,   icon:'📚' },
        { name:'HD Movies (4 GB)',       size:4e9,   icon:'🎬' },
        { name:'4K Movies (60 GB)',      size:60e9,  icon:'🎥' },
        { name:'PC Games (50 GB)',       size:50e9,  icon:'🎮' },
      ];
      var equivalents = equivRaw.map(function(e) {
        var n = Math.floor(eqBytes / e.size);
        return { name: e.name, icon: e.icon, count: n >= 1000 ? n.toLocaleString() : (n > 0 ? n.toLocaleString() : null) };
      }).filter(function(e) { return e.count !== null; });

      // ── Throughput rows ────────────────────
      var throughputRows = [
        { label: 'Per Second',  value: smartBytes(bps / 8)          },
        { label: 'Per Minute',  value: smartBytes(bps * 60 / 8)     },
        { label: 'Per Hour',    value: smartBytes(bps * 3600 / 8)   },
        { label: 'Per Day',     value: smartBytes(bps * 86400 / 8)  },
      ];

      self.result = {
        heroLabel, heroValue, heroSub, metaLabel1, metaValue1, metaLabel2, metaValue2, overheadNote,
        time: timeBreakdown,
        sizeRows, speedRows, speedComp, equivalents, throughputRows,
      };
    },
  };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\bandwidth-calculator.blade.php ENDPATH**/ ?>