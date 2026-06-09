@extends('layouts.public')

@section('title', $tool->name . ' - ' . config('app.name'))
@section('meta_description', $tool->description ?? 'Set a countdown timer for any duration or target date.')

@section('content')
<style>
/* ══════════════════════════════════════════════
   Countdown Timer  —  prefix: ct-
   Theme: Rose / Red
══════════════════════════════════════════════ */

/* Digit boxes */
.ct-digit-wrap   { display:flex; flex-direction:column; align-items:center; gap:.3rem; flex:1; min-width:0; }
.ct-digit-box    { background:#fff; border:2px solid #fecdd3; border-radius:1rem; width:100%; aspect-ratio:1/1; max-width:90px; display:flex; align-items:center; justify-content:center; font-size:clamp(1.6rem,5vw,2.6rem); font-weight:900; color:#be123c; box-shadow:0 2px 10px rgba(190,18,60,.08); transition:border-color .2s, transform .1s; position:relative; overflow:hidden; }
.ct-digit-box.pulse { animation:ctFlip .15s ease; }
@keyframes ctFlip { 0%{transform:scaleY(.85)} 50%{transform:scaleY(1.05)} 100%{transform:scaleY(1)} }
.ct-digit-label  { font-size:.62rem; font-weight:700; color:#9f1239; text-transform:uppercase; letter-spacing:.1em; }
.ct-separator    { font-size:clamp(1.4rem,4vw,2.2rem); font-weight:900; color:#fda4af; align-self:center; padding:0 .1rem; margin-bottom:1.4rem; line-height:1; }

/* State colours */
.ct-digit-box.running { border-color:#fda4af; background:linear-gradient(135deg,#fff,#fff1f2); }
.ct-digit-box.paused  { border-color:#fde68a; background:linear-gradient(135deg,#fff,#fefce8); color:#b45309; }
.ct-digit-box.done    { border-color:#bbf7d0; background:linear-gradient(135deg,#fff,#f0fdf4); color:#15803d; }

/* Progress bar */
.ct-progress-track { height:8px; border-radius:9999px; background:#ffe4e6; overflow:hidden; }
.ct-progress-fill  { height:100%; border-radius:9999px; background:linear-gradient(90deg,#f43f5e,#e11d48); transition:width .9s linear; }
.ct-progress-fill.paused { background:linear-gradient(90deg,#fbbf24,#d97706); }
.ct-progress-fill.done   { background:linear-gradient(90deg,#4ade80,#16a34a); width:100% !important; }

/* Time's Up overlay */
.ct-timesup { background:linear-gradient(135deg,#fff1f2,#ffe4e6); border:2px solid #fecdd3; border-radius:1.5rem; padding:2rem; text-align:center; }
.ct-timesup-emoji { font-size:3.5rem; animation:ctBounce 0.6s ease infinite alternate; display:inline-block; }
@keyframes ctBounce { from{transform:translateY(0)} to{transform:translateY(-10px)} }

/* Mode tab */
.ct-tab       { flex:1; padding:.55rem 1rem; border-radius:.625rem; font-size:.8rem; font-weight:700; text-align:center; cursor:pointer; transition:all .15s; color:#9f1239; border:1.5px solid transparent; }
.ct-tab.active { background:#be123c; color:#fff; box-shadow:0 2px 8px rgba(190,18,60,.25); }
.ct-tab:not(.active):hover { background:#fff1f2; border-color:#fecdd3; }

/* Preset pills */
.ct-preset { padding:.35rem .8rem; border-radius:9999px; font-size:.75rem; font-weight:700; background:#fff1f2; color:#be123c; border:1.5px solid #fecdd3; cursor:pointer; transition:all .15s; white-space:nowrap; }
.ct-preset:hover { background:#be123c; color:#fff; border-color:#be123c; }

/* Duration input group */
.ct-dur-lbl  { font-size:.6rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.08em; text-align:center; display:block; margin-top:.25rem; }
.ct-dur-inp  { text-align:center; font-size:1.1rem; font-weight:700; color:#be123c; padding:.5rem .2rem; }

/* History list */
.ct-hist-row { display:flex; align-items:center; justify-content:space-between; gap:.5rem; padding:.4rem .6rem; border-radius:.5rem; font-size:.8rem; }
.ct-hist-row:hover { background:#fff1f2; }

/* Circular ring */
.ct-ring-wrap { position:relative; display:inline-flex; align-items:center; justify-content:center; }
.ct-ring-svg  { transform:rotate(-90deg); }
.ct-ring-bg   { fill:none; stroke:#ffe4e6; stroke-width:8; }
.ct-ring-fg   { fill:none; stroke-width:8; stroke-linecap:round; transition:stroke-dashoffset .9s linear; }
</style>

<div class="min-h-screen bg-gray-50 py-8" x-data="ctCalc()" x-init="init()">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4"
           style="background:linear-gradient(135deg,#e11d48,#be123c)">
        <span class="text-3xl">⏱️</span>
      </div>
      <h1 class="text-3xl font-bold text-gray-900">Countdown Timer</h1>
      <p class="mt-2 text-gray-500">Set a timer for any duration or count down to a future date</p>
    </div>

    {{-- Error --}}
    <div x-show="errorMsg" x-transition
         class="mb-6 p-4 rounded-xl border border-red-200 bg-red-50 text-red-700 text-sm flex items-start gap-2">
      <span class="text-lg leading-none">⚠️</span>
      <span x-text="errorMsg"></span>
    </div>

    {{-- Time's Up --}}
    <div x-show="timerState==='done'" x-transition class="ct-timesup mb-6">
      <span class="ct-timesup-emoji">🎉</span>
      <h2 class="text-2xl font-black text-rose-700 mt-3">Time's Up!</h2>
      <p class="text-rose-500 mt-1 text-sm" x-text="timerLabel ? '\"' + timerLabel + '\" has ended.' : 'Your countdown has ended.'"></p>
      <div class="flex justify-center gap-3 mt-4">
        <button @click="reset()" class="btn btn-secondary btn-sm">↺ Restart</button>
        <button @click="clear()" class="btn btn-primary btn-sm" style="background:#be123c">✕ Clear</button>
      </div>
    </div>

    <div class="lg:grid lg:grid-cols-5 lg:gap-8 space-y-6 lg:space-y-0">

      {{-- ===== LEFT: Input Panel ===== --}}
      <div class="lg:col-span-2 space-y-5">

        {{-- Mode tabs --}}
        <div class="card p-4">
          <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Timer Mode</p>
          <div class="flex gap-2 bg-rose-50 p-1.5 rounded-xl">
            <div class="ct-tab" :class="mode==='duration' ? 'active' : ''" @click="setMode('duration')">⏱ Duration</div>
            <div class="ct-tab" :class="mode==='target' ? 'active' : ''"   @click="setMode('target')">📅 Target Date</div>
          </div>
        </div>

        {{-- Duration mode --}}
        <div x-show="mode==='duration'" class="card p-5 space-y-4">
          <h2 class="font-semibold text-gray-800">Set Duration</h2>

          <div class="grid grid-cols-4 gap-2">
            <div>
              <input type="number" x-model.number="inputDays" min="0" max="999"
                     placeholder="0" class="form-input w-full ct-dur-inp"
                     :disabled="timerState==='running'" />
              <span class="ct-dur-lbl">Days</span>
            </div>
            <div>
              <input type="number" x-model.number="inputHours" min="0" max="23"
                     placeholder="0" class="form-input w-full ct-dur-inp"
                     :disabled="timerState==='running'" />
              <span class="ct-dur-lbl">Hours</span>
            </div>
            <div>
              <input type="number" x-model.number="inputMins" min="0" max="59"
                     placeholder="0" class="form-input w-full ct-dur-inp"
                     :disabled="timerState==='running'" />
              <span class="ct-dur-lbl">Mins</span>
            </div>
            <div>
              <input type="number" x-model.number="inputSecs" min="0" max="59"
                     placeholder="0" class="form-input w-full ct-dur-inp"
                     :disabled="timerState==='running'" />
              <span class="ct-dur-lbl">Secs</span>
            </div>
          </div>

          {{-- Quick presets --}}
          <div>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Quick Presets</p>
            <div class="flex flex-wrap gap-2">
              <button @click="applyPreset(60)"     :disabled="timerState==='running'" class="ct-preset">1 min</button>
              <button @click="applyPreset(300)"    :disabled="timerState==='running'" class="ct-preset">5 min</button>
              <button @click="applyPreset(600)"    :disabled="timerState==='running'" class="ct-preset">10 min</button>
              <button @click="applyPreset(900)"    :disabled="timerState==='running'" class="ct-preset">15 min</button>
              <button @click="applyPreset(1800)"   :disabled="timerState==='running'" class="ct-preset">30 min</button>
              <button @click="applyPreset(3600)"   :disabled="timerState==='running'" class="ct-preset">1 hr</button>
              <button @click="applyPreset(7200)"   :disabled="timerState==='running'" class="ct-preset">2 hr</button>
              <button @click="applyPreset(86400)"  :disabled="timerState==='running'" class="ct-preset">1 day</button>
            </div>
          </div>
        </div>

        {{-- Target mode --}}
        <div x-show="mode==='target'" class="card p-5 space-y-4">
          <h2 class="font-semibold text-gray-800">Set Target Date & Time</h2>
          <div>
            <label class="form-label">Target Date & Time</label>
            <input type="datetime-local" x-model="targetDatetime"
                   :min="minDatetime"
                   class="form-input w-full"
                   :disabled="timerState==='running'" />
          </div>
          <p class="text-xs text-gray-400">The countdown will reflect the live difference to this moment.</p>
        </div>

        {{-- Label (optional) --}}
        <div class="card p-5">
          <label class="form-label">Timer Label <span class="text-gray-400 font-normal">(optional)</span></label>
          <input type="text" x-model="timerLabel" maxlength="60"
                 placeholder="e.g. Meeting starts, Lunch break…"
                 class="form-input w-full" />
        </div>

        {{-- Controls --}}
        <div class="card p-5 space-y-3">
          <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Controls</p>

          <button @click="start()" x-show="timerState==='idle'"
                  class="btn btn-primary w-full py-3 font-bold text-base"
                  style="background:linear-gradient(135deg,#e11d48,#be123c)">
            ▶ Start Timer
          </button>

          <div x-show="timerState==='running' || timerState==='paused'" class="flex gap-2">
            <button @click="pause()" class="btn btn-secondary flex-1 py-2.5 font-bold">
              <span x-text="timerState==='running' ? '⏸ Pause' : '▶ Resume'"></span>
            </button>
            <button @click="reset()" class="btn btn-secondary flex-1 py-2.5 font-bold">
              ↺ Reset
            </button>
          </div>

          <button @click="clear()"
                  x-show="timerState !== 'idle'"
                  class="btn w-full py-2 text-sm font-semibold text-rose-600 border border-rose-200 bg-rose-50 hover:bg-rose-100 rounded-xl transition-colors">
            ✕ Clear
          </button>

          {{-- Sound toggle --}}
          <div class="flex items-center justify-between pt-1 border-t border-gray-100">
            <span class="text-sm text-gray-600">Alert sound when done</span>
            <button @click="soundEnabled = !soundEnabled"
                    :class="soundEnabled ? 'bg-rose-600' : 'bg-gray-300'"
                    class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors focus:outline-none">
              <span :class="soundEnabled ? 'translate-x-5' : 'translate-x-1'"
                    class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition-transform"></span>
            </button>
          </div>
        </div>

      </div>

      {{-- ===== RIGHT: Live Display ===== --}}
      <div class="lg:col-span-3 space-y-5">

        {{-- Main countdown display --}}
        <div class="card p-6">

          {{-- Label --}}
          <div class="text-center mb-4" x-show="timerLabel">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700" x-text="timerLabel"></span>
          </div>

          {{-- State badge --}}
          <div class="flex justify-center mb-4">
            <span x-show="timerState==='idle'"    class="badge text-xs font-bold px-3 py-1 rounded-full bg-gray-100 text-gray-500">READY</span>
            <span x-show="timerState==='running'" class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-full bg-green-100 text-green-700">
              <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse inline-block"></span> RUNNING
            </span>
            <span x-show="timerState==='paused'"  class="text-xs font-bold px-3 py-1 rounded-full bg-yellow-100 text-yellow-700">⏸ PAUSED</span>
            <span x-show="timerState==='done'"    class="text-xs font-bold px-3 py-1 rounded-full bg-green-100 text-green-700">✓ DONE</span>
          </div>

          {{-- Digit display --}}
          <div class="flex items-end justify-center gap-1 sm:gap-2 py-2">

            {{-- Days --}}
            <div class="ct-digit-wrap" x-show="dDays > 0 || mode==='target' || inputDays > 0">
              <div class="ct-digit-box" :class="timerState" x-text="String(dDays).padStart(2,'0')"></div>
              <span class="ct-digit-label">days</span>
            </div>
            <span class="ct-separator" x-show="dDays > 0 || mode==='target' || inputDays > 0">:</span>

            {{-- Hours --}}
            <div class="ct-digit-wrap">
              <div class="ct-digit-box" :class="timerState" x-text="String(dHours).padStart(2,'0')"></div>
              <span class="ct-digit-label">hours</span>
            </div>
            <span class="ct-separator">:</span>

            {{-- Minutes --}}
            <div class="ct-digit-wrap">
              <div class="ct-digit-box" :class="timerState" x-text="String(dMins).padStart(2,'0')"></div>
              <span class="ct-digit-label">min</span>
            </div>
            <span class="ct-separator">:</span>

            {{-- Seconds --}}
            <div class="ct-digit-wrap">
              <div class="ct-digit-box" :class="timerState" x-text="String(dSecs).padStart(2,'0')"></div>
              <span class="ct-digit-label">sec</span>
            </div>

          </div>

          {{-- Progress bar --}}
          <div class="mt-6">
            <div class="flex justify-between text-xs text-gray-400 mb-1">
              <span>Progress</span>
              <span x-text="progressPct.toFixed(0) + '%'"></span>
            </div>
            <div class="ct-progress-track">
              <div class="ct-progress-fill" :class="timerState"
                   :style="'width:' + progressPct + '%'"></div>
            </div>
          </div>

          {{-- Time remaining text --}}
          <div class="mt-4 text-center" x-show="timerState !== 'idle' && timerState !== 'done'">
            <p class="text-sm text-gray-500">
              <span class="font-semibold text-rose-600" x-text="totalRemainStr"></span> remaining
            </p>
          </div>

        </div>

        {{-- Circular visual (shown when running/paused) --}}
        <div x-show="timerState==='running' || timerState==='paused'" x-transition class="card p-6">
          <div class="flex items-center justify-center">
            <div class="ct-ring-wrap">
              <svg class="ct-ring-svg" width="160" height="160" viewBox="0 0 160 160">
                <circle class="ct-ring-bg" cx="80" cy="80" r="68"></circle>
                <circle class="ct-ring-fg"
                        :stroke="timerState==='paused' ? '#f59e0b' : '#e11d48'"
                        cx="80" cy="80" r="68"
                        :stroke-dasharray="ringCirc"
                        :stroke-dashoffset="ringOffset"></circle>
              </svg>
              <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span class="text-3xl font-black text-rose-700" x-text="String(dMins).padStart(2,'0') + ':' + String(dSecs).padStart(2,'0')"></span>
                <span class="text-xs text-gray-400 font-medium">mm : ss</span>
              </div>
            </div>
          </div>
          <p class="text-center text-xs text-gray-400 mt-3">
            <span x-show="timerState==='running'">Timer is running…</span>
            <span x-show="timerState==='paused'">Timer is paused</span>
          </p>
        </div>

        {{-- Stats row (once started) --}}
        <div x-show="startSecs > 0 && timerState !== 'idle'" x-transition
             class="grid grid-cols-3 gap-4">
          <div class="card p-4 text-center">
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-1">Total</p>
            <p class="font-bold text-gray-800 text-sm" x-text="fmtDuration(startSecs)"></p>
          </div>
          <div class="card p-4 text-center">
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-1">Elapsed</p>
            <p class="font-bold text-rose-600 text-sm" x-text="fmtDuration(startSecs - remainSecs)"></p>
          </div>
          <div class="card p-4 text-center">
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-1">Left</p>
            <p class="font-bold text-gray-800 text-sm" x-text="fmtDuration(remainSecs)"></p>
          </div>
        </div>

        {{-- Target date info --}}
        <div x-show="mode==='target' && targetDatetime && timerState !== 'idle'" x-transition
             class="card p-4 bg-rose-50 border border-rose-100">
          <p class="text-sm font-semibold text-rose-800 mb-1">📅 Target</p>
          <p class="text-sm text-rose-700" x-text="fmtTargetDate()"></p>
        </div>

        {{-- Tips --}}
        <div x-show="timerState==='idle'" class="card p-5 bg-gray-50">
          <p class="text-sm font-semibold text-gray-700 mb-2">💡 Tips</p>
          <ul class="text-xs text-gray-500 space-y-1.5 list-disc list-inside">
            <li>Use <strong>Duration</strong> mode to count down from a fixed time like 25 minutes.</li>
            <li>Use <strong>Target Date</strong> mode to count down to an event (e.g. New Year).</li>
            <li>Add a label so you remember what the timer is for.</li>
            <li>Quick presets let you start common timers in one click.</li>
            <li>Toggle the alert sound to hear a beep when the timer ends.</li>
          </ul>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function ctCalc() {
  return {
    // --- Mode & inputs ---
    mode: 'duration',
    inputDays:  0,
    inputHours: 0,
    inputMins:  5,
    inputSecs:  0,
    targetDatetime: '',
    timerLabel: '',
    minDatetime: '',

    // --- Timer state ---
    timerState: 'idle',   // idle | running | paused | done
    remainSecs: 0,
    startSecs:  0,
    _ticker:    null,
    errorMsg:   '',
    soundEnabled: true,

    // --- Circular ring ---
    ringCirc: 2 * Math.PI * 68,   // circumference for r=68

    // --- Computed display ---
    get dDays()  { return Math.floor(this.remainSecs / 86400); },
    get dHours() { return Math.floor((this.remainSecs % 86400) / 3600); },
    get dMins()  { return Math.floor((this.remainSecs % 3600) / 60); },
    get dSecs()  { return this.remainSecs % 60; },

    get progressPct() {
      if (this.startSecs <= 0) return 0;
      if (this.timerState === 'done') return 100;
      return (1 - this.remainSecs / this.startSecs) * 100;
    },

    get ringOffset() {
      if (this.startSecs <= 0) return this.ringCirc;
      var frac = this.remainSecs / this.startSecs;
      return this.ringCirc * frac;
    },

    get totalRemainStr() {
      return this.fmtDuration(this.remainSecs);
    },

    // --- Init ---
    init() {
      var now = new Date();
      now.setMinutes(now.getMinutes() + 1);
      this.minDatetime = this._toLocalISOString(new Date());
      this.targetDatetime = this._toLocalISOString(now);
    },

    // --- Mode switch ---
    setMode(m) {
      if (this.timerState === 'running') return;
      this.mode = m;
      if (this.timerState !== 'idle') this.reset();
    },

    // --- Actions ---
    start() {
      this.errorMsg = '';
      var secs = 0;

      if (this.mode === 'duration') {
        var d = Math.max(0, parseInt(this.inputDays)  || 0);
        var h = Math.max(0, parseInt(this.inputHours) || 0);
        var m = Math.max(0, parseInt(this.inputMins)  || 0);
        var s = Math.max(0, parseInt(this.inputSecs)  || 0);
        if (h > 23) { this.errorMsg = 'Hours must be 0–23.'; return; }
        if (m > 59) { this.errorMsg = 'Minutes must be 0–59.'; return; }
        if (s > 59) { this.errorMsg = 'Seconds must be 0–59.'; return; }
        secs = d * 86400 + h * 3600 + m * 60 + s;
        if (secs <= 0) { this.errorMsg = 'Please enter a duration greater than zero.'; return; }
        this.remainSecs = secs;
        this.startSecs  = secs;

      } else {
        if (!this.targetDatetime) { this.errorMsg = 'Please pick a target date and time.'; return; }
        var target = new Date(this.targetDatetime);
        var diff   = Math.floor((target - new Date()) / 1000);
        if (diff <= 0) { this.errorMsg = 'Target time must be in the future.'; return; }
        this.remainSecs = diff;
        this.startSecs  = diff;
      }

      this.timerState = 'running';
      this._startTick();
    },

    _startTick() {
      var self = this;
      self._ticker = setInterval(function() {
        if (self.timerState !== 'running') return;

        if (self.mode === 'target') {
          var diff = Math.floor((new Date(self.targetDatetime) - new Date()) / 1000);
          self.remainSecs = Math.max(0, diff);
        } else {
          self.remainSecs = Math.max(0, self.remainSecs - 1);
        }

        if (self.remainSecs <= 0) {
          self.remainSecs  = 0;
          self.timerState  = 'done';
          clearInterval(self._ticker);
          self._ticker = null;
          if (self.soundEnabled) self._playAlert();
        }
      }, 1000);
    },

    pause() {
      if (this.timerState === 'running') {
        this.timerState = 'paused';
        clearInterval(this._ticker);
        this._ticker = null;
      } else if (this.timerState === 'paused') {
        this.timerState = 'running';
        this._startTick();
      }
    },

    reset() {
      clearInterval(this._ticker);
      this._ticker    = null;
      this.remainSecs = this.startSecs;
      this.timerState = 'idle';
      this.errorMsg   = '';
    },

    clear() {
      clearInterval(this._ticker);
      this._ticker       = null;
      this.remainSecs    = 0;
      this.startSecs     = 0;
      this.timerState    = 'idle';
      this.errorMsg      = '';
      this.inputDays     = 0;
      this.inputHours    = 0;
      this.inputMins     = 5;
      this.inputSecs     = 0;
      this.timerLabel    = '';
      var now = new Date();
      now.setMinutes(now.getMinutes() + 1);
      this.targetDatetime = this._toLocalISOString(now);
    },

    applyPreset(secs) {
      if (this.timerState === 'running') return;
      clearInterval(this._ticker);
      this._ticker     = null;
      this.timerState  = 'idle';
      this.mode        = 'duration';
      this.inputDays   = Math.floor(secs / 86400);
      this.inputHours  = Math.floor((secs % 86400) / 3600);
      this.inputMins   = Math.floor((secs % 3600) / 60);
      this.inputSecs   = secs % 60;
      this.remainSecs  = secs;
      this.startSecs   = secs;
      this.errorMsg    = '';
    },

    // --- Alert sound (Web Audio API — no external file) ---
    _playAlert() {
      try {
        var ctx = new (window.AudioContext || window.webkitAudioContext)();
        var times = [0, 0.35, 0.7];
        times.forEach(function(t) {
          var osc  = ctx.createOscillator();
          var gain = ctx.createGain();
          osc.connect(gain);
          gain.connect(ctx.destination);
          osc.type = 'sine';
          osc.frequency.value = 880;
          gain.gain.setValueAtTime(0, ctx.currentTime + t);
          gain.gain.linearRampToValueAtTime(0.4, ctx.currentTime + t + 0.05);
          gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + t + 0.3);
          osc.start(ctx.currentTime + t);
          osc.stop(ctx.currentTime + t + 0.32);
        });
        // Low boom at end
        var boom = ctx.createOscillator();
        var bGain = ctx.createGain();
        boom.connect(bGain);
        bGain.connect(ctx.destination);
        boom.type = 'sine';
        boom.frequency.value = 220;
        bGain.gain.setValueAtTime(0.5, ctx.currentTime + 0.85);
        bGain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 1.6);
        boom.start(ctx.currentTime + 0.85);
        boom.stop(ctx.currentTime + 1.65);
      } catch(e) {}
    },

    // --- Formatters ---
    fmtDuration(secs) {
      if (secs <= 0) return '0s';
      var d = Math.floor(secs / 86400);
      var h = Math.floor((secs % 86400) / 3600);
      var m = Math.floor((secs % 3600) / 60);
      var s = secs % 60;
      var parts = [];
      if (d) parts.push(d + 'd');
      if (h) parts.push(h + 'h');
      if (m) parts.push(m + 'm');
      if (s || !parts.length) parts.push(s + 's');
      return parts.join(' ');
    },

    fmtTargetDate() {
      if (!this.targetDatetime) return '';
      try {
        return new Date(this.targetDatetime).toLocaleString('en-US', {
          weekday:'short', year:'numeric', month:'long', day:'numeric',
          hour:'2-digit', minute:'2-digit'
        });
      } catch(e) { return this.targetDatetime; }
    },

    // --- Helpers ---
    _toLocalISOString(d) {
      var pad = function(n){ return String(n).padStart(2,'0'); };
      return d.getFullYear() + '-' + pad(d.getMonth()+1) + '-' + pad(d.getDate()) +
             'T' + pad(d.getHours()) + ':' + pad(d.getMinutes());
    },
  };
}
</script>
@endpush
