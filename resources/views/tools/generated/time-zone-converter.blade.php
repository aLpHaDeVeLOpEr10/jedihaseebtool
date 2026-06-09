@extends('layouts.public')

@section('title', $tool->name . ' - ' . config('app.name'))
@section('meta_description', $tool->description ?? 'Convert time between any two time zones with DST accuracy.')

@section('content')
<div class="min-h-screen bg-gray-50 py-8" x-data="tzCalc()" x-init="init()">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4"
           style="background:linear-gradient(135deg,#0d9488,#14b8a6)">
        <span class="text-3xl">🌍</span>
      </div>
      <h1 class="text-3xl font-bold text-gray-900">Time Zone Converter</h1>
      <p class="mt-2 text-gray-500">Convert times accurately across the world — DST-aware</p>
    </div>

    {{-- Error banner --}}
    <div x-show="errorMsg" x-transition
         class="mb-6 p-4 rounded-xl border border-red-200 bg-red-50 text-red-700 text-sm flex items-start gap-2">
      <span class="text-lg leading-none">⚠️</span>
      <span x-text="errorMsg"></span>
    </div>

    <div class="lg:grid lg:grid-cols-5 lg:gap-8 space-y-6 lg:space-y-0">

      {{-- ===== INPUT PANEL ===== --}}
      <div class="lg:col-span-2 space-y-5">

        {{-- Live Clock toggle --}}
        <div class="card p-5">
          <div class="flex items-center justify-between">
            <div>
              <p class="font-semibold text-gray-800">Live Clock Mode</p>
              <p class="text-xs text-gray-500 mt-0.5">Auto-updates every second</p>
            </div>
            <button @click="toggleLive()"
                    :class="liveClock ? 'bg-teal-600' : 'bg-gray-300'"
                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none">
              <span :class="liveClock ? 'translate-x-6' : 'translate-x-1'"
                    class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
            </button>
          </div>
        </div>

        {{-- Source Time --}}
        <div class="card p-5 space-y-4">
          <h2 class="font-semibold text-gray-800 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold text-white" style="background:#0d9488">S</span>
            Source Time
          </h2>

          <div>
            <label class="form-label">Date</label>
            <input type="date" x-model="srcDate" :disabled="liveClock"
                   :max="maxDate"
                   class="form-input w-full" :class="liveClock ? 'opacity-50 cursor-not-allowed' : ''" />
          </div>

          <div>
            <label class="form-label">Time</label>
            <input type="time" x-model="srcTime" :disabled="liveClock"
                   class="form-input w-full" :class="liveClock ? 'opacity-50 cursor-not-allowed' : ''" />
          </div>

          <div>
            <label class="form-label">Source Time Zone</label>
            <select x-model="srcTZ" @change="compute()" class="form-input w-full">
              <template x-for="grp in tzGroups" :key="grp.group">
                <optgroup :label="grp.group">
                  <template x-for="z in grp.zones" :key="z.v">
                    <option :value="z.v" x-text="z.l"></option>
                  </template>
                </optgroup>
              </template>
            </select>
          </div>

          <div class="flex gap-2">
            <button @click="useNow()" :disabled="liveClock"
                    class="btn btn-secondary btn-sm flex-1"
                    :class="liveClock ? 'opacity-50 cursor-not-allowed' : ''">
              🕐 Use Current Time
            </button>
            <button @click="swapFirstTarget()" class="btn btn-secondary btn-sm flex-1">
              ⇄ Swap
            </button>
          </div>
        </div>

        {{-- Quick Presets --}}
        <div class="card p-5">
          <h2 class="font-semibold text-gray-800 mb-3">Quick Presets</h2>
          <div class="grid grid-cols-2 gap-2">
            <template x-for="p in presets" :key="p.label">
              <button @click="applyPreset(p)"
                      class="text-xs px-2 py-2 rounded-lg border border-teal-200 bg-teal-50 text-teal-700 hover:bg-teal-100 transition-colors font-medium">
                <span x-text="p.label"></span>
              </button>
            </template>
          </div>
        </div>

        {{-- Target Time Zones --}}
        <div class="card p-5 space-y-4">
          <div class="flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">Target Time Zones</h2>
            <button @click="addTarget()" x-show="targets.length < 6"
                    class="text-xs px-3 py-1 rounded-lg text-white font-medium transition-colors"
                    style="background:#0d9488">
              + Add
            </button>
          </div>

          <template x-for="(t, i) in targets" :key="i">
            <div class="flex items-center gap-2">
              <select x-model="t.tz" @change="compute()"
                      class="form-input flex-1 text-sm">
                <template x-for="grp in tzGroups" :key="grp.group">
                  <optgroup :label="grp.group">
                    <template x-for="z in grp.zones" :key="z.v">
                      <option :value="z.v" x-text="z.l"></option>
                    </template>
                  </optgroup>
                </template>
              </select>
              <button x-show="targets.length > 1" @click="removeTarget(i)"
                      class="text-red-400 hover:text-red-600 text-lg leading-none flex-shrink-0">×</button>
            </div>
          </template>
        </div>

        <button @click="compute()"
                class="btn btn-primary w-full py-3 text-base font-semibold"
                style="background:linear-gradient(135deg,#0d9488,#0f766e)">
          Convert Time
        </button>

      </div>

      {{-- ===== RESULTS PANEL ===== --}}
      <div class="lg:col-span-3 space-y-5">

        {{-- Placeholder --}}
        <div x-show="phase==='idle'" class="card p-12 text-center text-gray-400">
          <div class="text-5xl mb-4">🌐</div>
          <p class="font-medium">Enter a date, time, and time zones to convert</p>
          <p class="text-sm mt-1">Results will appear here</p>
        </div>

        {{-- Loading --}}
        <div x-show="phase==='loading'" class="card p-12 text-center">
          <div class="inline-block w-8 h-8 border-4 border-teal-200 border-t-teal-600 rounded-full animate-spin mb-4"></div>
          <p class="text-gray-500">Converting...</p>
        </div>

        <template x-if="phase==='done'">
          <div class="space-y-5">

            {{-- Source summary card --}}
            <div class="rounded-2xl p-6 text-white" style="background:linear-gradient(135deg,#0d9488,#0f766e)">
              <div class="flex items-start justify-between">
                <div>
                  <p class="text-teal-100 text-sm font-medium mb-1">Source</p>
                  <p class="text-4xl font-bold tracking-tight" x-text="srcResult.timeStr"></p>
                  <p class="text-teal-200 mt-1 text-sm" x-text="srcResult.dateStr"></p>
                </div>
                <div class="text-right">
                  <p class="text-2xl font-semibold" x-text="srcResult.tzAbbr"></p>
                  <p class="text-teal-200 text-sm" x-text="srcResult.offsetStr"></p>
                  <span x-show="srcResult.isDST"
                        class="inline-block mt-1 text-xs px-2 py-0.5 rounded-full bg-yellow-400 text-yellow-900 font-bold">
                    DST Active
                  </span>
                </div>
              </div>
            </div>

            {{-- Target results --}}
            <template x-for="(r, i) in tgtResults" :key="i">
              <div class="card p-5">
                <div class="flex items-start justify-between flex-wrap gap-3">
                  <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                      <p class="text-xs font-medium text-gray-500 uppercase tracking-wide" x-text="r.tz"></p>
                      <span x-show="r.isDST"
                            class="text-xs px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700 font-bold">DST</span>
                      <span :class="{
                          'bg-green-100 text-green-700': r.bizStatus === 'open',
                          'bg-yellow-100 text-yellow-700': r.bizStatus === 'early' || r.bizStatus === 'evening',
                          'bg-red-100 text-red-700': r.bizStatus === 'closed'
                        }"
                        class="text-xs px-2 py-0.5 rounded-full font-medium" x-text="r.bizLabel"></span>
                    </div>
                    <p class="text-3xl font-bold text-gray-900" x-text="r.timeStr"></p>
                    <p class="text-gray-500 text-sm mt-0.5" x-text="r.dateStr"></p>
                  </div>
                  <div class="text-right flex-shrink-0">
                    <p class="text-xl font-semibold text-gray-700" x-text="r.tzAbbr"></p>
                    <p class="text-gray-400 text-sm" x-text="r.offsetStr"></p>
                    <p class="text-sm font-medium mt-1"
                       :class="r.diffMinutes > 0 ? 'text-teal-600' : r.diffMinutes < 0 ? 'text-red-500' : 'text-gray-500'"
                       x-text="r.diffLabel"></p>
                  </div>
                </div>

                {{-- Mini 24h bar --}}
                <div class="mt-4">
                  <div class="flex items-center justify-between text-xs text-gray-400 mb-1">
                    <span>12AM</span><span>6AM</span><span>12PM</span><span>6PM</span><span>12AM</span>
                  </div>
                  <div class="relative h-3 rounded-full overflow-hidden bg-gray-100">
                    {{-- Business hours band --}}
                    <div class="absolute top-0 h-full bg-green-100"
                         style="left:37.5%;width:33.33%"></div>
                    {{-- Current time needle --}}
                    <div class="absolute top-0 w-1 h-full rounded-full bg-teal-500 -translate-x-0.5"
                         :style="'left:' + r.dayPct + '%'"></div>
                  </div>
                </div>
              </div>
            </template>

            {{-- UTC reference --}}
            <div class="card p-4 flex items-center gap-4 bg-gray-50">
              <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-gray-200 flex-shrink-0">
                <span class="text-lg">🌐</span>
              </div>
              <div class="flex-1">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">UTC Reference</p>
                <p class="font-bold text-gray-800" x-text="utcResult.timeStr + '  ' + utcResult.dateStr"></p>
              </div>
              <div>
                <p class="text-sm text-gray-400">UTC+0:00</p>
              </div>
            </div>

            {{-- DST info --}}
            <div x-show="dstInfo.length > 0" class="card p-4 bg-yellow-50 border border-yellow-200">
              <p class="text-sm font-semibold text-yellow-800 mb-2">⚡ DST Information</p>
              <template x-for="d in dstInfo" :key="d">
                <p class="text-xs text-yellow-700" x-text="d"></p>
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
function tzCalc() {
  return {
    // --- State ---
    phase: 'idle',
    errorMsg: '',
    liveClock: false,
    _ticker: null,
    srcDate: '',
    srcTime: '',
    srcTZ: 'America/New_York',
    targets: [
      { tz: 'Europe/London' },
      { tz: 'Asia/Tokyo' },
      { tz: 'Asia/Kolkata' },
    ],
    maxDate: '2099-12-31',
    srcResult: {},
    tgtResults: [],
    utcResult: {},
    dstInfo: [],

    // --- Timezone groups ---
    tzGroups: [
      { group: '⭐ Common', zones: [
        {v:'UTC',                 l:'UTC — Coordinated Universal Time'},
        {v:'America/New_York',    l:'New York (EST/EDT) UTC-5/4'},
        {v:'America/Chicago',     l:'Chicago (CST/CDT) UTC-6/5'},
        {v:'America/Denver',      l:'Denver (MST/MDT) UTC-7/6'},
        {v:'America/Los_Angeles', l:'Los Angeles (PST/PDT) UTC-8/7'},
        {v:'Europe/London',       l:'London (GMT/BST) UTC+0/1'},
        {v:'Europe/Paris',        l:'Paris / Berlin (CET/CEST) UTC+1/2'},
        {v:'Europe/Moscow',       l:'Moscow (MSK) UTC+3'},
        {v:'Asia/Dubai',          l:'Dubai (GST) UTC+4'},
        {v:'Asia/Kolkata',        l:'India (IST) UTC+5:30'},
        {v:'Asia/Dhaka',          l:'Dhaka (BST) UTC+6'},
        {v:'Asia/Bangkok',        l:'Bangkok (ICT) UTC+7'},
        {v:'Asia/Shanghai',       l:'China / Singapore (CST/SGT) UTC+8'},
        {v:'Asia/Tokyo',          l:'Tokyo (JST) UTC+9'},
        {v:'Australia/Sydney',    l:'Sydney (AEST/AEDT) UTC+10/11'},
      ]},
      { group: '🌎 Americas', zones: [
        {v:'America/Anchorage',       l:'Anchorage (AKST/AKDT) UTC-9/8'},
        {v:'America/Adak',            l:'Adak (HST/HDT) UTC-10/9'},
        {v:'Pacific/Honolulu',        l:'Honolulu (HST) UTC-10'},
        {v:'America/Los_Angeles',     l:'Los Angeles / Vancouver (PST/PDT) UTC-8/7'},
        {v:'America/Phoenix',         l:'Phoenix (MST) UTC-7'},
        {v:'America/Denver',          l:'Denver (MST/MDT) UTC-7/6'},
        {v:'America/Chicago',         l:'Chicago (CST/CDT) UTC-6/5'},
        {v:'America/New_York',        l:'New York / Toronto (EST/EDT) UTC-5/4'},
        {v:'America/Halifax',         l:'Halifax (AST/ADT) UTC-4/3'},
        {v:'America/St_Johns',        l:'St. John\'s (NST/NDT) UTC-3:30/2:30'},
        {v:'America/Sao_Paulo',       l:'São Paulo (BRT/BRST) UTC-3/2'},
        {v:'America/Argentina/Buenos_Aires', l:'Buenos Aires (ART) UTC-3'},
        {v:'America/Bogota',          l:'Bogotá (COT) UTC-5'},
        {v:'America/Caracas',         l:'Caracas (VET) UTC-4'},
        {v:'America/Mexico_City',     l:'Mexico City (CST/CDT) UTC-6/5'},
        {v:'America/Lima',            l:'Lima (PET) UTC-5'},
      ]},
      { group: '🌍 Europe', zones: [
        {v:'Atlantic/Azores',     l:'Azores (AZOT/AZOST) UTC-1/0'},
        {v:'Europe/London',       l:'London / Dublin (GMT/BST) UTC+0/1'},
        {v:'Europe/Lisbon',       l:'Lisbon (WET/WEST) UTC+0/1'},
        {v:'Europe/Paris',        l:'Paris / Berlin / Rome (CET/CEST) UTC+1/2'},
        {v:'Europe/Amsterdam',    l:'Amsterdam / Brussels (CET/CEST) UTC+1/2'},
        {v:'Europe/Warsaw',       l:'Warsaw (CET/CEST) UTC+1/2'},
        {v:'Europe/Athens',       l:'Athens / Istanbul (EET/EEST) UTC+2/3'},
        {v:'Europe/Helsinki',     l:'Helsinki / Tallinn (EET/EEST) UTC+2/3'},
        {v:'Europe/Bucharest',    l:'Bucharest (EET/EEST) UTC+2/3'},
        {v:'Europe/Moscow',       l:'Moscow (MSK) UTC+3'},
        {v:'Europe/Istanbul',     l:'Istanbul (TRT) UTC+3'},
      ]},
      { group: '🌍 Africa & Middle East', zones: [
        {v:'Atlantic/Cape_Verde',     l:'Cape Verde (CVT) UTC-1'},
        {v:'Africa/Abidjan',          l:'Abidjan / Accra (GMT) UTC+0'},
        {v:'Africa/Lagos',            l:'Lagos / Kinshasa (WAT) UTC+1'},
        {v:'Africa/Cairo',            l:'Cairo (EET) UTC+2'},
        {v:'Africa/Johannesburg',     l:'Johannesburg (SAST) UTC+2'},
        {v:'Africa/Nairobi',          l:'Nairobi (EAT) UTC+3'},
        {v:'Asia/Riyadh',             l:'Riyadh (AST) UTC+3'},
        {v:'Asia/Baghdad',            l:'Baghdad (AST) UTC+3'},
        {v:'Asia/Tehran',             l:'Tehran (IRST/IRDT) UTC+3:30/4:30'},
        {v:'Asia/Dubai',              l:'Dubai / Abu Dhabi (GST) UTC+4'},
        {v:'Asia/Kabul',              l:'Kabul (AFT) UTC+4:30'},
        {v:'Indian/Mauritius',        l:'Mauritius (MUT) UTC+4'},
      ]},
      { group: '🌏 Asia', zones: [
        {v:'Asia/Karachi',        l:'Karachi (PKT) UTC+5'},
        {v:'Asia/Kolkata',        l:'India (IST) UTC+5:30'},
        {v:'Asia/Kathmandu',      l:'Kathmandu (NPT) UTC+5:45'},
        {v:'Asia/Dhaka',          l:'Dhaka (BST) UTC+6'},
        {v:'Asia/Colombo',        l:'Colombo (IST) UTC+5:30'},
        {v:'Asia/Yangon',         l:'Yangon (MMT) UTC+6:30'},
        {v:'Asia/Bangkok',        l:'Bangkok / Jakarta (ICT/WIB) UTC+7'},
        {v:'Asia/Ho_Chi_Minh',    l:'Ho Chi Minh City (ICT) UTC+7'},
        {v:'Asia/Kuala_Lumpur',   l:'Kuala Lumpur / Singapore (MYT/SGT) UTC+8'},
        {v:'Asia/Shanghai',       l:'Beijing / Shanghai (CST) UTC+8'},
        {v:'Asia/Manila',         l:'Manila (PHT) UTC+8'},
        {v:'Asia/Seoul',          l:'Seoul (KST) UTC+9'},
        {v:'Asia/Tokyo',          l:'Tokyo (JST) UTC+9'},
        {v:'Asia/Vladivostok',    l:'Vladivostok (VLAT) UTC+10'},
      ]},
      { group: '🌏 Pacific & Australia', zones: [
        {v:'Australia/Perth',         l:'Perth (AWST) UTC+8'},
        {v:'Australia/Darwin',        l:'Darwin (ACST) UTC+9:30'},
        {v:'Australia/Adelaide',      l:'Adelaide (ACST/ACDT) UTC+9:30/10:30'},
        {v:'Australia/Brisbane',      l:'Brisbane (AEST) UTC+10'},
        {v:'Australia/Sydney',        l:'Sydney / Melbourne (AEST/AEDT) UTC+10/11'},
        {v:'Pacific/Auckland',        l:'Auckland (NZST/NZDT) UTC+12/13'},
        {v:'Pacific/Fiji',            l:'Fiji (FJT) UTC+12'},
        {v:'Pacific/Guam',            l:'Guam (ChST) UTC+10'},
        {v:'Pacific/Tahiti',          l:'Tahiti (TAHT) UTC-10'},
        {v:'Pacific/Apia',            l:'Apia (WST) UTC+13'},
      ]},
    ],

    // --- Presets ---
    presets: [
      {label:'NYC ↔ London',  src:'America/New_York',    tgts:['Europe/London']},
      {label:'NYC ↔ Tokyo',   src:'America/New_York',    tgts:['Asia/Tokyo']},
      {label:'London ↔ Dubai',src:'Europe/London',       tgts:['Asia/Dubai']},
      {label:'London ↔ India',src:'Europe/London',       tgts:['Asia/Kolkata']},
      {label:'LA ↔ NYC',      src:'America/Los_Angeles', tgts:['America/New_York']},
      {label:'Dubai ↔ Tokyo', src:'Asia/Dubai',          tgts:['Asia/Tokyo']},
    ],

    // --- Init ---
    init() {
      var now = new Date();
      this.srcDate = this._fmtDate(now);
      this.srcTime = this._fmtTime(now);
      // Detect browser TZ
      try {
        var btz = Intl.DateTimeFormat().resolvedOptions().timeZone;
        if (btz) this.srcTZ = btz;
      } catch(e) {}
      this.$watch('srcDate', () => this.compute());
      this.$watch('srcTime', () => this.compute());
      this.$watch('srcTZ',   () => this.compute());
      this.compute();
    },

    // --- Core offset algorithm ---
    _getTZOffsetMinutes(tz, date) {
      var opts = {
        timeZone: tz,
        year: 'numeric', month: 'numeric', day: 'numeric',
        hour: 'numeric', minute: 'numeric', second: 'numeric',
        hourCycle: 'h23'
      };
      var parts = new Intl.DateTimeFormat('en-US', opts).formatToParts(date);
      var p = {};
      parts.forEach(function(x){ p[x.type] = parseInt(x.value, 10) || 0; });
      var h = p.hour === 24 ? 0 : p.hour;
      var tzAsUTCMs = Date.UTC(p.year, p.month - 1, p.day, h, p.minute, p.second);
      return (tzAsUTCMs - date.getTime()) / 60000;
    },

    // Two-pass DST-accurate parse: local datetime string → UTC Date
    _parseLocalToUTC(dateStr, timeStr, tz) {
      var naive = new Date(dateStr + 'T' + timeStr + ':00Z');
      var off1 = this._getTZOffsetMinutes(tz, naive);
      var pass1 = new Date(naive.getTime() - off1 * 60000);
      var off2 = this._getTZOffsetMinutes(tz, pass1);
      return new Date(naive.getTime() - off2 * 60000);
    },

    _isDST(tz, date) {
      try {
        var y = date.getFullYear();
        var janOff = this._getTZOffsetMinutes(tz, new Date(Date.UTC(y,0,15)));
        var julOff = this._getTZOffsetMinutes(tz, new Date(Date.UTC(y,6,15)));
        if (janOff === julOff) return false; // no DST
        var stdOff = Math.min(janOff, julOff);
        var curOff = this._getTZOffsetMinutes(tz, date);
        return curOff !== stdOff;
      } catch(e) { return false; }
    },

    _offsetStr(offsetMins) {
      var sign = offsetMins >= 0 ? '+' : '-';
      var abs = Math.abs(offsetMins);
      var h = Math.floor(abs / 60);
      var m = abs % 60;
      return 'UTC' + sign + h + (m ? ':' + String(m).padStart(2,'0') : ':00');
    },

    _tzAbbr(tz, date) {
      try {
        var s = new Intl.DateTimeFormat('en-US', {timeZone: tz, timeZoneName: 'short'})
                    .formatToParts(date)
                    .find(x => x.type === 'timeZoneName');
        return s ? s.value : tz.split('/').pop();
      } catch(e) { return ''; }
    },

    _formatInTZ(date, tz) {
      var opts = {timeZone: tz, hour:'2-digit', minute:'2-digit', second:'2-digit', hour12:true};
      return new Intl.DateTimeFormat('en-US', opts).format(date);
    },

    _formatDateInTZ(date, tz) {
      var opts = {timeZone: tz, weekday:'short', year:'numeric', month:'long', day:'numeric'};
      return new Intl.DateTimeFormat('en-US', opts).format(date);
    },

    _hourInTZ(date, tz) {
      var opts = {timeZone: tz, hour:'numeric', hour12:false};
      var parts = new Intl.DateTimeFormat('en-US', opts).formatToParts(date);
      var h = parts.find(x => x.type === 'hour');
      return h ? (parseInt(h.value) % 24) : 0;
    },

    _minuteInTZ(date, tz) {
      var opts = {timeZone: tz, minute:'numeric'};
      var parts = new Intl.DateTimeFormat('en-US', opts).formatToParts(date);
      var m = parts.find(x => x.type === 'minute');
      return m ? parseInt(m.value) : 0;
    },

    _bizStatus(h) {
      if (h >= 9 && h < 17)  return {status:'open',    label:'Business Hours'};
      if (h >= 7 && h < 9)   return {status:'early',   label:'Early Morning'};
      if (h >= 17 && h < 21) return {status:'evening', label:'Evening'};
      return {status:'closed', label:'Outside Hours'};
    },

    // --- Actions ---
    useNow() {
      var now = new Date();
      this.srcDate = this._fmtDate(now);
      this.srcTime = this._fmtTime(now);
      this.compute();
    },

    toggleLive() {
      this.liveClock = !this.liveClock;
      if (this.liveClock) {
        var self = this;
        self.useNow();
        self._ticker = setInterval(function(){
          var now = new Date();
          self.srcDate = self._fmtDate(now);
          self.srcTime = self._fmtTime(now);
          self.compute();
        }, 1000);
      } else {
        if (this._ticker) { clearInterval(this._ticker); this._ticker = null; }
      }
    },

    swapFirstTarget() {
      if (!this.targets.length) return;
      var tmp = this.srcTZ;
      this.srcTZ = this.targets[0].tz;
      this.targets[0].tz = tmp;
      this.compute();
    },

    addTarget() {
      if (this.targets.length >= 6) return;
      // Pick a TZ not already used
      var used = [this.srcTZ].concat(this.targets.map(t => t.tz));
      var allZones = this.tzGroups.flatMap(g => g.zones.map(z => z.v));
      var next = allZones.find(z => !used.includes(z)) || 'UTC';
      this.targets.push({tz: next});
    },

    removeTarget(i) {
      this.targets.splice(i, 1);
      this.compute();
    },

    applyPreset(p) {
      this.srcTZ = p.src;
      this.targets = p.tgts.map(t => ({tz: t}));
      this.compute();
    },

    // --- Compute ---
    compute() {
      this.errorMsg = '';
      if (!this.srcDate || !this.srcTime) { this.phase = 'idle'; return; }
      this.phase = 'loading';
      var self = this;
      setTimeout(function(){
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
      // Validate date
      if (!/^\d{4}-\d{2}-\d{2}$/.test(this.srcDate)) throw new Error('Please enter a valid date.');
      if (!/^\d{2}:\d{2}$/.test(this.srcTime))       throw new Error('Please enter a valid time.');

      var utcDate = this._parseLocalToUTC(this.srcDate, this.srcTime, this.srcTZ);
      if (isNaN(utcDate.getTime())) throw new Error('Could not parse the date/time in the selected timezone.');

      // Source result
      var srcOff = this._getTZOffsetMinutes(this.srcTZ, utcDate);
      this.srcResult = {
        timeStr:   this._formatInTZ(utcDate, this.srcTZ),
        dateStr:   this._formatDateInTZ(utcDate, this.srcTZ),
        tzAbbr:    this._tzAbbr(this.srcTZ, utcDate),
        offsetStr: this._offsetStr(srcOff),
        isDST:     this._isDST(this.srcTZ, utcDate),
      };

      // UTC result
      this.utcResult = {
        timeStr: this._formatInTZ(utcDate, 'UTC'),
        dateStr: this._formatDateInTZ(utcDate, 'UTC'),
      };

      // Target results
      var self = this;
      this.tgtResults = this.targets.map(function(t){
        var tOff  = self._getTZOffsetMinutes(t.tz, utcDate);
        var diffM = tOff - srcOff;
        var h     = self._hourInTZ(utcDate, t.tz);
        var m     = self._minuteInTZ(utcDate, t.tz);
        var biz   = self._bizStatus(h);
        var dayPct = ((h * 60 + m) / 1440) * 100;
        var absDiff = Math.abs(diffM);
        var dh = Math.floor(absDiff / 60), dm = absDiff % 60;
        var diffLabel = diffM === 0 ? 'Same time' :
                        (diffM > 0 ? '+' : '-') + dh + 'h' + (dm ? ' ' + dm + 'min' : '') + ' from source';
        return {
          tz:          t.tz,
          timeStr:     self._formatInTZ(utcDate, t.tz),
          dateStr:     self._formatDateInTZ(utcDate, t.tz),
          tzAbbr:      self._tzAbbr(t.tz, utcDate),
          offsetStr:   self._offsetStr(tOff),
          isDST:       self._isDST(t.tz, utcDate),
          diffMinutes: diffM,
          diffLabel:   diffLabel,
          bizStatus:   biz.status,
          bizLabel:    biz.label,
          dayPct:      dayPct,
        };
      });

      // DST info messages
      var info = [];
      if (this.srcResult.isDST) {
        info.push(this.srcTZ + ' is currently observing Daylight Saving Time.');
      }
      this.targets.forEach(function(t, i){
        if (self.tgtResults[i] && self.tgtResults[i].isDST) {
          info.push(t.tz + ' is currently observing Daylight Saving Time.');
        }
      });
      this.dstInfo = info;
    },

    // --- Helpers ---
    _fmtDate(d) {
      return d.getFullYear() + '-' +
             String(d.getMonth()+1).padStart(2,'0') + '-' +
             String(d.getDate()).padStart(2,'0');
    },
    _fmtTime(d) {
      return String(d.getHours()).padStart(2,'0') + ':' +
             String(d.getMinutes()).padStart(2,'0');
    },
  };
}
</script>
@endpush
