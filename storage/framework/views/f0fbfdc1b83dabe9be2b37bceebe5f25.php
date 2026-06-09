<?php $__env->startSection('title', $tool->name . ' - ' . config('app.name')); ?>
<?php $__env->startSection('meta_description', $tool->seo_description ?? 'Calculate how long it takes to download any file at any internet speed. Instant results in days, hours, minutes, and seconds.'); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ══════════════════════════════════════════════
   Download Time Calculator  —  prefix: dt-
   Theme: Purple (#7e22ce / #9333ea / #a855f7)
══════════════════════════════════════════════ */

/* Hero gradient text */
.dt-hero-text {
  font-size: clamp(2.6rem, 6vw, 4.2rem);
  font-weight: 900;
  line-height: 1;
  letter-spacing: -.04em;
  background: linear-gradient(135deg, #3b0764, #7e22ce, #9333ea);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* Mode tab pill */
.dt-unit-btn {
  padding: .3rem .75rem;
  border-radius: 9999px;
  font-size: .72rem;
  font-weight: 700;
  cursor: pointer;
  border: 1.5px solid #e9d5ff;
  background: #fff;
  color: #6b21a8;
  transition: all .15s;
  white-space: nowrap;
}
.dt-unit-btn:hover  { background: #f3e8ff; border-color: #c084fc; }
.dt-unit-btn.active { background: #7e22ce; color: #fff; border-color: #7e22ce; }

/* Stat tile */
.dt-stat {
  background: #fff;
  border: 1.5px solid #e9d5ff;
  border-radius: 1.125rem;
  padding: 1rem .75rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: .3rem;
  text-align: center;
  transition: all .15s;
}
.dt-stat:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(147,51,234,.1); }
.dt-stat-lbl { font-size: .6rem; font-weight: 800; color: #a855f7; text-transform: uppercase; letter-spacing: .1em; }
.dt-stat-val { font-size: 1.9rem; font-weight: 900; line-height: 1; color: #4c1d95; }
.dt-stat-unit { font-size: .65rem; color: #94a3b8; font-weight: 600; }

/* Comparison row */
.dt-cmp-row {
  display: grid;
  grid-template-columns: 1.6fr .9fr 1.4fr 1fr;
  gap: .4rem;
  align-items: center;
  padding: .45rem .65rem;
  border-radius: .6rem;
  font-size: .77rem;
}
.dt-cmp-row:hover { background: #faf5ff; }
.dt-cmp-row.dt-head { font-size: .58rem; font-weight: 800; color: #a855f7; text-transform: uppercase; letter-spacing: .08em; }
.dt-cmp-row.dt-current { background: #f3e8ff; border: 1.5px solid #d8b4fe; font-weight: 700; }

/* Speed bar inside comparison */
.dt-speed-bar-wrap { display: flex; align-items: center; gap: .4rem; }
.dt-speed-bar { height: 6px; border-radius: 9999px; background: linear-gradient(90deg, #9333ea, #c084fc); min-width: 2px; transition: width .5s ease; }

/* Quick preset pill */
.dt-preset {
  padding: .3rem .7rem;
  border-radius: 9999px;
  font-size: .7rem;
  font-weight: 700;
  background: #f3e8ff;
  color: #7e22ce;
  border: 1px solid #e9d5ff;
  cursor: pointer;
  white-space: nowrap;
  transition: all .15s;
}
.dt-preset:hover { background: #7e22ce; color: #fff; border-color: #7e22ce; }

/* Conversion list row */
.dt-conv-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: .35rem .65rem;
  border-radius: .5rem;
  font-size: .78rem;
  gap: .5rem;
}
.dt-conv-row:hover { background: #faf5ff; }
.dt-conv-row.dt-hl { background: #f3e8ff; border: 1px solid #d8b4fe; font-weight: 700; }

/* Section divider */
.dt-div {
  display: flex;
  align-items: center;
  gap: .6rem;
  color: #a855f7;
  font-size: .6rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: .1em;
}
.dt-div::before, .dt-div::after { content: ''; flex: 1; height: 1px; background: #e9d5ff; }

/* Animate in */
@keyframes dtFadeUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.dt-in { animation: dtFadeUp .32s ease-out; }

/* Info tip box */
.dt-tip {
  background: linear-gradient(135deg, #faf5ff, #f3e8ff);
  border: 1.5px solid #e9d5ff;
  border-radius: 1rem;
  padding: .75rem 1rem;
  font-size: .72rem;
  color: #6b21a8;
}
</style>

<div class="min-h-screen bg-gray-50 py-8" x-data="dtCalc()" x-init="init()">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4"
           style="background: linear-gradient(135deg, #6b21a8, #9333ea);">
        <span class="text-3xl">⬇️</span>
      </div>
      <h1 class="text-3xl font-bold text-gray-900">Download Time Calculator</h1>
      <p class="mt-2 text-gray-500 max-w-xl mx-auto">
        Enter a file size and connection speed to see exactly how long a download will take.
      </p>
    </div>

    
    <div x-show="errorMsg" x-transition
         class="mb-6 p-4 rounded-xl border border-red-200 bg-red-50 text-red-700 text-sm flex items-start gap-2">
      <span class="text-base leading-none flex-shrink-0">⚠️</span>
      <span x-text="errorMsg"></span>
    </div>

    
    <div class="lg:grid lg:grid-cols-5 lg:gap-8 space-y-6 lg:space-y-0">

      
      <div class="lg:col-span-2 space-y-5">

        
        <div class="card p-5 space-y-3">
          <h2 class="font-semibold text-gray-800 flex items-center gap-2">
            <span class="text-lg">💾</span> File Size
          </h2>

          <div>
            <label class="form-label">Value</label>
            <input
              type="number"
              step="any"
              min="0"
              placeholder="e.g. 4.7"
              x-model="fileSize"
              @input.debounce.500ms="autoCompute()"
              class="form-input w-full"
            />
          </div>

          <div>
            <label class="form-label">Unit</label>
            <div class="flex flex-wrap gap-1.5">
              <template x-for="u in fileSizeUnits" :key="u.id">
                <button
                  @click="fileSizeUnit = u.id; autoCompute()"
                  :class="fileSizeUnit === u.id ? 'active' : ''"
                  class="dt-unit-btn"
                  x-text="u.label">
                </button>
              </template>
            </div>
          </div>

          
          <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Quick Presets</p>
            <div class="flex flex-wrap gap-1.5">
              <template x-for="p in filePresets" :key="p.label">
                <button @click="applyFilePreset(p)" class="dt-preset" x-text="p.label"></button>
              </template>
            </div>
          </div>
        </div>

        
        <div class="card p-5 space-y-3">
          <h2 class="font-semibold text-gray-800 flex items-center gap-2">
            <span class="text-lg">📡</span> Download Speed
          </h2>

          <div>
            <label class="form-label">Speed</label>
            <input
              type="number"
              step="any"
              min="0"
              placeholder="e.g. 50"
              x-model="speed"
              @input.debounce.500ms="autoCompute()"
              class="form-input w-full"
            />
          </div>

          <div>
            <label class="form-label">Unit</label>
            <div class="flex flex-wrap gap-1.5">
              <template x-for="u in speedUnits" :key="u.id">
                <button
                  @click="speedUnit = u.id; autoCompute()"
                  :class="speedUnit === u.id ? 'active' : ''"
                  class="dt-unit-btn"
                  x-text="u.label">
                </button>
              </template>
            </div>
          </div>

          
          <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Common Speeds</p>
            <div class="flex flex-wrap gap-1.5">
              <template x-for="p in speedPresets" :key="p.label">
                <button @click="applySpeedPreset(p)" class="dt-preset" x-text="p.label"></button>
              </template>
            </div>
          </div>
        </div>

        
        <div class="flex gap-3">
          <button
            @click="compute()"
            class="btn btn-primary flex-1 py-3 text-base font-bold"
            style="background: linear-gradient(135deg, #6b21a8, #9333ea);">
            Calculate
          </button>
          <button @click="reset()" class="btn btn-secondary py-3 px-5 font-semibold">
            Reset
          </button>
        </div>

        
        <div class="dt-tip">
          <p class="font-bold mb-1">💡 ISP vs Actual Speed</p>
          <p>ISPs advertise in <strong>Mbps (megabits/s)</strong> but download managers show <strong>MB/s (megabytes/s)</strong>. Your 100 Mbps plan delivers ~12.5 MB/s actual throughput. Real-world speeds are typically 60–90% of advertised due to network congestion.</p>
        </div>

      </div>

      
      <div class="lg:col-span-3 space-y-5">

        
        <div x-show="phase === 'idle'" class="card p-12 text-center text-gray-400">
          <div class="text-5xl mb-4">⬇️</div>
          <p class="font-medium text-gray-500">Enter a file size and speed above</p>
          <p class="text-sm mt-1">Results and speed comparisons will appear here</p>
        </div>

        
        <div x-show="phase === 'loading'" class="card p-12 text-center">
          <div class="inline-block w-7 h-7 border-4 border-purple-200 border-t-purple-600 rounded-full animate-spin mb-3"></div>
          <p class="text-gray-500 text-sm">Calculating…</p>
        </div>

        
        <template x-if="phase === 'done'">
          <div class="space-y-5 dt-in">

            
            <div class="rounded-2xl p-6 text-white"
                 style="background: linear-gradient(135deg, #3b0764, #6b21a8, #9333ea);">
              <p class="text-purple-200 text-sm font-medium mb-2">⬇️ Download Time</p>
              <div class="dt-hero-text" style="-webkit-text-fill-color:#fff;" x-text="result.heroTime"></div>
              <div class="mt-3 flex flex-wrap gap-x-6 gap-y-1 text-sm">
                <span class="text-purple-200">
                  <span class="text-white font-semibold" x-text="result.fileSizeLabel"></span>
                  file
                </span>
                <span class="text-purple-200">at
                  <span class="text-white font-semibold" x-text="result.speedLabel"></span>
                </span>
                <span class="text-purple-200">
                  Throughput:
                  <span class="text-white font-semibold" x-text="result.throughputLabel"></span>
                </span>
              </div>
            </div>

            
            <div class="grid grid-cols-4 gap-3">
              <div class="dt-stat">
                <span class="dt-stat-lbl">Days</span>
                <span class="dt-stat-val" x-text="result.days"></span>
                <span class="dt-stat-unit">days</span>
              </div>
              <div class="dt-stat">
                <span class="dt-stat-lbl">Hours</span>
                <span class="dt-stat-val" x-text="result.hours"></span>
                <span class="dt-stat-unit">hrs</span>
              </div>
              <div class="dt-stat">
                <span class="dt-stat-lbl">Minutes</span>
                <span class="dt-stat-val" x-text="result.minutes"></span>
                <span class="dt-stat-unit">min</span>
              </div>
              <div class="dt-stat">
                <span class="dt-stat-lbl">Seconds</span>
                <span class="dt-stat-val" x-text="result.seconds"></span>
                <span class="dt-stat-unit">sec</span>
              </div>
            </div>

            
            <div class="card p-5">
              <p class="dt-div mb-3">File Size &amp; Speed in All Units</p>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-0.5">
                <div>
                  <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1 mt-1">File Size</p>
                  <template x-for="row in result.sizeRows" :key="row.unit">
                    <div class="dt-conv-row" :class="row.hl ? 'dt-hl' : ''">
                      <span class="text-gray-500 font-semibold text-xs" x-text="row.unit"></span>
                      <span class="font-bold" :class="row.hl ? 'text-purple-700' : 'text-gray-800'"
                            x-text="row.value"></span>
                    </div>
                  </template>
                </div>
                <div>
                  <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1 mt-1">Speed</p>
                  <template x-for="row in result.speedRows" :key="row.unit">
                    <div class="dt-conv-row" :class="row.hl ? 'dt-hl' : ''">
                      <span class="text-gray-500 font-semibold text-xs" x-text="row.unit"></span>
                      <span class="font-bold" :class="row.hl ? 'text-purple-700' : 'text-gray-800'"
                            x-text="row.value"></span>
                    </div>
                  </template>
                </div>
              </div>
            </div>

            
            <div class="card p-5">
              <p class="dt-div mb-3">Download Time at Common Speeds</p>
              <div class="dt-cmp-row dt-head">
                <span>Connection</span>
                <span>Speed</span>
                <span>Time for This File</span>
                <span>Relative</span>
              </div>
              <template x-for="row in result.comparison" :key="row.name">
                <div class="dt-cmp-row" :class="row.isCurrent ? 'dt-current' : ''">
                  <span class="font-medium text-gray-700">
                    <span x-text="row.icon + ' ' + row.name"></span>
                    <span x-show="row.isCurrent" class="text-xs text-purple-600 ml-1 font-bold">← you</span>
                  </span>
                  <span class="text-gray-400 text-xs font-semibold" x-text="row.speedStr"></span>
                  <span :class="row.isCurrent ? 'text-purple-700 font-bold' : 'text-gray-700 font-semibold'"
                        x-text="row.timeStr"></span>
                  <div class="dt-speed-bar-wrap">
                    <div class="flex-1 h-1.5 bg-purple-100 rounded-full overflow-hidden">
                      <div class="dt-speed-bar" :style="'width:' + row.barPct + '%'"></div>
                    </div>
                    <span class="text-xs text-gray-400 flex-shrink-0" x-text="row.relStr"></span>
                  </div>
                </div>
              </template>
            </div>

            
            <div class="card p-5" x-show="result.equivalents.length > 0">
              <p class="dt-div mb-3">At This Speed, Per Hour You Can Download</p>
              <div class="grid grid-cols-2 gap-2">
                <template x-for="eq in result.equivalents" :key="eq.name">
                  <div class="flex items-center justify-between px-3 py-2 rounded-lg hover:bg-purple-50 text-sm">
                    <span class="text-gray-600">
                      <span x-text="eq.icon + ' ' + eq.name"></span>
                    </span>
                    <span class="font-bold text-purple-700" x-text="eq.count"></span>
                  </div>
                </template>
              </div>
              <p class="text-xs text-gray-400 mt-2 border-t border-gray-100 pt-2">
                Estimated at your stated speed. Actual download rates vary by server and network load.
              </p>
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
/* ───────────────────────────────────────────────────
   Download Time Calculator — Alpine.js component
   Prefix: dt-   Theme: Purple
─────────────────────────────────────────────────── */
function dtCalc() {
  return {

    // ── Inputs ──────────────────────────────────
    fileSize:     '',
    fileSizeUnit: 'MB',
    speed:        '',
    speedUnit:    'Mbps',

    // ── State ────────────────────────────────────
    phase:    'idle',  // idle | loading | done
    errorMsg: '',
    result:   null,

    // ── Unit definitions ──────────────────────────
    fileSizeUnits: [
      { id: 'b',   label: 'b'   },   // bit
      { id: 'B',   label: 'B'   },   // byte
      { id: 'KB',  label: 'KB'  },
      { id: 'MB',  label: 'MB'  },
      { id: 'GB',  label: 'GB'  },
      { id: 'TB',  label: 'TB'  },
      { id: 'Kib', label: 'Kb'  },   // kilobit
      { id: 'Mib', label: 'Mb'  },   // megabit
      { id: 'Gib', label: 'Gb'  },   // gigabit
    ],

    speedUnits: [
      { id: 'Kbps', label: 'Kbps'  },
      { id: 'Mbps', label: 'Mbps'  },
      { id: 'Gbps', label: 'Gbps'  },
      { id: 'KB_s', label: 'KB/s'  },
      { id: 'MB_s', label: 'MB/s'  },
      { id: 'GB_s', label: 'GB/s'  },
    ],

    // ── Conversion factors (all → bits) ──────────
    sizeToBits: {
      b:    1,
      B:    8,
      KB:   8e3,
      MB:   8e6,
      GB:   8e9,
      TB:   8e12,
      Kib:  1e3,       // kilobit
      Mib:  1e6,       // megabit
      Gib:  1e9,       // gigabit
    },

    // Conversion factors (all → bps)
    speedToBps: {
      Kbps:  1e3,
      Mbps:  1e6,
      Gbps:  1e9,
      KB_s:  8e3,
      MB_s:  8e6,
      GB_s:  8e9,
    },

    // ── Presets ───────────────────────────────────
    filePresets: [
      { label: 'MP3 (4 MB)',       size: 4,    unit: 'MB' },
      { label: 'Photo (3 MB)',     size: 3,    unit: 'MB' },
      { label: 'App (150 MB)',     size: 150,  unit: 'MB' },
      { label: 'SD Movie (700 MB)',size: 700,  unit: 'MB' },
      { label: 'HD Movie (4 GB)', size: 4,    unit: 'GB' },
      { label: '4K Movie (60 GB)',size: 60,   unit: 'GB' },
      { label: 'PC Game (50 GB)', size: 50,   unit: 'GB' },
      { label: 'Blu-ray (25 GB)', size: 25,   unit: 'GB' },
    ],

    speedPresets: [
      { label: 'Dial-up 56K',  speed: 56,   unit: 'Kbps' },
      { label: '3G (3 Mbps)',  speed: 3,    unit: 'Mbps' },
      { label: '4G (20 Mbps)', speed: 20,   unit: 'Mbps' },
      { label: '5G (200 Mbps)',speed: 200,  unit: 'Mbps' },
      { label: '25 Mbps',      speed: 25,   unit: 'Mbps' },
      { label: '50 Mbps',      speed: 50,   unit: 'Mbps' },
      { label: '100 Mbps',     speed: 100,  unit: 'Mbps' },
      { label: '1 Gbps',       speed: 1,    unit: 'Gbps' },
    ],

    // Common speeds for comparison table (in Mbps)
    comparisonSpeeds: [
      { name: 'Dial-up',        mbps: 0.056,  icon: '📞' },
      { name: 'ADSL Basic',     mbps: 1,      icon: '📡' },
      { name: '3G Mobile',      mbps: 3,      icon: '📱' },
      { name: 'ADSL2+',         mbps: 8,      icon: '📡' },
      { name: '4G / LTE',       mbps: 20,     icon: '📱' },
      { name: '25 Mbps Cable',  mbps: 25,     icon: '🔌' },
      { name: '50 Mbps',        mbps: 50,     icon: '🔌' },
      { name: '100 Mbps',       mbps: 100,    icon: '🔌' },
      { name: '5G',             mbps: 200,    icon: '📱' },
      { name: 'WiFi 5 (5GHz)',  mbps: 300,    icon: '📶' },
      { name: 'Gigabit',        mbps: 1000,   icon: '⚡' },
      { name: 'WiFi 6',         mbps: 1200,   icon: '📶' },
    ],

    // ── Lifecycle ─────────────────────────────────
    init() { /* nothing on mount */ },

    // ── Actions ──────────────────────────────────
    applyFilePreset(p) {
      this.fileSize     = String(p.size);
      this.fileSizeUnit = p.unit;
    },

    applySpeedPreset(p) {
      this.speed     = String(p.speed);
      this.speedUnit = p.unit;
    },

    reset() {
      this.fileSize     = '';
      this.fileSizeUnit = 'MB';
      this.speed        = '';
      this.speedUnit    = 'Mbps';
      this.phase        = 'idle';
      this.errorMsg     = '';
      this.result       = null;
    },

    autoCompute() {
      var fs = parseFloat(this.fileSize), sp = parseFloat(this.speed);
      if (fs > 0 && sp > 0) this.compute();
    },

    compute() {
      this.errorMsg = '';

      var fs = parseFloat(this.fileSize);
      var sp = parseFloat(this.speed);

      // ── Validation ──────────────────────────────
      if (this.fileSize === '' || isNaN(fs)) {
        this.errorMsg = 'Please enter a file size.';
        return;
      }
      if (fs <= 0) {
        this.errorMsg = 'File size must be greater than zero.';
        return;
      }
      if (this.speed === '' || isNaN(sp)) {
        this.errorMsg = 'Please enter a download speed.';
        return;
      }
      if (sp <= 0) {
        this.errorMsg = 'Download speed must be greater than zero.';
        return;
      }

      this.phase = 'loading';
      var self = this;
      setTimeout(function () {
        try {
          self._doCompute(fs, sp);
          self.phase = 'done';
        } catch (e) {
          self.errorMsg = e.message;
          self.phase = 'idle';
        }
      }, 80);
    },

    _doCompute(fs, sp) {
      var self = this;

      // ── Convert to base units ─────────────────
      var fileBits = fs * self.sizeToBits[self.fileSizeUnit];
      var bps      = sp * self.speedToBps[self.speedUnit];
      var fileBytes = fileBits / 8;

      // ── Transfer time in seconds ─────────────
      var totalSecs = fileBits / bps;

      // ── d / h / m / s breakdown ──────────────
      var days    = Math.floor(totalSecs / 86400);
      var hours   = Math.floor((totalSecs % 86400) / 3600);
      var minutes = Math.floor((totalSecs % 3600) / 60);
      var seconds = Math.floor(totalSecs % 60);

      // ── Hero time string ─────────────────────
      function fmtTime(s) {
        if (!isFinite(s)) return '—';
        if (s < 0.001)  return (s * 1000).toFixed(2) + ' ms';
        if (s < 1)      return s.toFixed(3) + ' s';
        var d = Math.floor(s / 86400);
        var h = Math.floor((s % 86400) / 3600);
        var m = Math.floor((s % 3600) / 60);
        var sec = Math.floor(s % 60);
        if (d > 365) return (s / 86400 / 365.25).toFixed(1) + ' years';
        var parts = [];
        if (d)   parts.push(d + 'd');
        if (h)   parts.push(h + 'h');
        if (m)   parts.push(m + 'm');
        if (sec || parts.length === 0) parts.push(sec + 's');
        return parts.join(' ');
      }

      function smartBytes(b) {
        if (b < 1e3)  return b.toFixed(2) + ' B';
        if (b < 1e6)  return (b / 1e3).toFixed(2) + ' KB';
        if (b < 1e9)  return (b / 1e6).toFixed(2) + ' MB';
        if (b < 1e12) return (b / 1e9).toFixed(2) + ' GB';
        return (b / 1e12).toFixed(2) + ' TB';
      }

      function smartBps(b) {
        if (b < 1e3)  return b.toFixed(2) + ' bps';
        if (b < 1e6)  return (b / 1e3).toFixed(2) + ' Kbps';
        if (b < 1e9)  return (b / 1e6).toFixed(2) + ' Mbps';
        if (b < 1e12) return (b / 1e9).toFixed(2) + ' Gbps';
        return (b / 1e12).toFixed(2) + ' Tbps';
      }

      function fmtNum(v, d) {
        return parseFloat(v.toFixed(d)).toLocaleString('en-US', {
          minimumFractionDigits: 0, maximumFractionDigits: d
        });
      }

      // ── File size in all units ───────────────
      var sizeRows = [
        { unit: 'bits',  value: fmtNum(fileBytes * 8, 2),     hl: false },
        { unit: 'bytes', value: fmtNum(fileBytes, 2),          hl: false },
        { unit: 'KB',    value: fmtNum(fileBytes / 1e3, 4),    hl: false },
        { unit: 'MB',    value: fmtNum(fileBytes / 1e6, 4),    hl: false },
        { unit: 'GB',    value: fmtNum(fileBytes / 1e9, 6),    hl: false },
        { unit: 'TB',    value: fmtNum(fileBytes / 1e12, 9),   hl: false },
      ];
      var bestSz = fileBytes < 1e3 ? 'bytes' : fileBytes < 1e6 ? 'KB' : fileBytes < 1e9 ? 'MB' : fileBytes < 1e12 ? 'GB' : 'TB';
      sizeRows.forEach(function (r) { if (r.unit === bestSz) r.hl = true; });

      // ── Speed in all units ───────────────────
      var speedRows = [
        { unit: 'Kbps',  value: fmtNum(bps / 1e3, 3),   hl: false },
        { unit: 'Mbps',  value: fmtNum(bps / 1e6, 4),   hl: false },
        { unit: 'Gbps',  value: fmtNum(bps / 1e9, 6),   hl: false },
        { unit: 'KB/s',  value: fmtNum(bps / 8e3, 3),   hl: false },
        { unit: 'MB/s',  value: fmtNum(bps / 8e6, 4),   hl: false },
        { unit: 'GB/s',  value: fmtNum(bps / 8e9, 6),   hl: false },
      ];
      var bestSpd = bps < 1e3 ? 'Kbps' : bps < 1e6 ? 'Kbps' : bps < 1e9 ? 'Mbps' : 'Gbps';
      speedRows.forEach(function (r) { if (r.unit === bestSpd) r.hl = true; });

      // ── Speed comparison table ───────────────
      var myMbps     = bps / 1e6;
      var maxMbps    = Math.max(myMbps, 1200);
      var comparison = self.comparisonSpeeds.map(function (s) {
        var sBps       = s.mbps * 1e6;
        var t          = fileBits / sBps;
        var isCurrent  = Math.abs(sBps - bps) / bps < 0.02;

        // Speed string
        var spStr = s.mbps < 1
          ? (s.mbps * 1000).toFixed(0) + ' Kbps'
          : s.mbps < 1000
            ? s.mbps.toFixed(0) + ' Mbps'
            : (s.mbps / 1000).toFixed(0) + ' Gbps';

        // Relative to user's speed
        var ratio  = sBps / bps;
        var relStr = ratio >= 2 ? ratio.toFixed(0) + '×' : ratio >= 0.95 ? '≈' : '';

        return {
          name:      s.name,
          icon:      s.icon,
          speedStr:  spStr,
          timeStr:   fmtTime(t),
          isCurrent: isCurrent,
          barPct:    Math.min(100, (s.mbps / maxMbps) * 100),
          relStr:    relStr,
        };
      });

      // ── Per-hour equivalents ─────────────────
      var bytesPerHour = (bps * 3600) / 8;
      var rawEquivs = [
        { name: 'MP3 songs (4 MB)',     size: 4e6,  icon: '🎵' },
        { name: 'JPEG photos (3 MB)',   size: 3e6,  icon: '📷' },
        { name: 'RAW photos (25 MB)',   size: 25e6, icon: '📸' },
        { name: 'HD movies (4 GB)',     size: 4e9,  icon: '🎬' },
        { name: '4K movies (60 GB)',    size: 60e9, icon: '🎥' },
        { name: 'PC games (50 GB)',     size: 50e9, icon: '🎮' },
      ];
      var equivalents = rawEquivs.map(function (e) {
        var n = Math.floor(bytesPerHour / e.size);
        return { name: e.name, icon: e.icon, count: n >= 1 ? n.toLocaleString() : null };
      }).filter(function (e) { return e.count !== null; });

      // ── Throughput label ─────────────────────
      var throughputBytesPerSec = bps / 8;
      var throughputLabel = smartBytes(throughputBytesPerSec) + '/s';

      // ── Assign result ────────────────────────
      self.result = {
        heroTime:       fmtTime(totalSecs),
        days:           fmtNum(days, 0),
        hours:          fmtNum(hours, 0),
        minutes:        fmtNum(minutes, 0),
        seconds:        fmtNum(seconds, 0),
        fileSizeLabel:  smartBytes(fileBytes),
        speedLabel:     smartBps(bps),
        throughputLabel:throughputLabel,
        sizeRows:       sizeRows,
        speedRows:      speedRows,
        comparison:     comparison,
        equivalents:    equivalents,
      };
    },
  };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\download-time-calculator.blade.php ENDPATH**/ ?>