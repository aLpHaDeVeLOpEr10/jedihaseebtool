@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')
<div class="min-h-screen bg-gray-50">

    {{-- Page Header --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-lg mx-auto px-4 sm:px-6 py-10">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                {{ $tool->icon }} {{ $tool->name }}
            </h1>
            <p class="text-gray-500 mt-2">{{ $tool->short_description }}</p>
        </div>
    </div>

    {{-- Tool Body --}}
    <div class="max-w-lg mx-auto px-4 sm:px-6 py-10"
         x-data="pomodoroTimer()"
         x-init="init()">

        {{-- ── Timer Card ── --}}
        <div class="card p-6 text-center mb-4">

            {{-- Mode tabs --}}
            <div role="tablist" class="flex bg-gray-100 rounded-2xl p-1 gap-0.5 mb-7">
                <button role="tab" type="button"
                        :aria-selected="mode === 'work'"
                        @click="setMode('work')"
                        class="flex-1 py-2 px-2 rounded-xl text-sm font-medium transition-all duration-150"
                        :class="mode === 'work' ? 'bg-brand-600 text-white shadow-sm' : 'text-gray-500 hover:text-gray-700'">
                    🍅 Focus
                </button>
                <button role="tab" type="button"
                        :aria-selected="mode === 'short'"
                        @click="setMode('short')"
                        class="flex-1 py-2 px-2 rounded-xl text-sm font-medium transition-all duration-150"
                        :class="mode === 'short' ? 'bg-emerald-500 text-white shadow-sm' : 'text-gray-500 hover:text-gray-700'">
                    ☕ Short Break
                </button>
                <button role="tab" type="button"
                        :aria-selected="mode === 'long'"
                        @click="setMode('long')"
                        class="flex-1 py-2 px-2 rounded-xl text-sm font-medium transition-all duration-150"
                        :class="mode === 'long' ? 'bg-sky-500 text-white shadow-sm' : 'text-gray-500 hover:text-gray-700'">
                    🛌 Long Break
                </button>
            </div>

            {{-- SVG Ring Timer --}}
            <div class="relative mx-auto mb-5"
                 style="width:220px;height:220px;">

                <svg viewBox="0 0 220 220" class="w-full h-full -rotate-90"
                     aria-hidden="true">
                    {{-- Track --}}
                    <circle cx="110" cy="110" r="90"
                            fill="none" stroke="#e5e7eb" stroke-width="11"/>
                    {{-- Progress --}}
                    <circle cx="110" cy="110" r="90"
                            fill="none"
                            :stroke="modeColor"
                            stroke-width="11"
                            stroke-linecap="round"
                            :stroke-dasharray="circumference"
                            :stroke-dashoffset="dashOffset"
                            :style="`transition:stroke-dashoffset ${isRunning ? '0.95s linear' : '0s'}`"/>
                </svg>

                {{-- Centered time display (upright — parent rotated, this is absolute) --}}
                <div class="absolute inset-0 flex flex-col items-center justify-center"
                     aria-live="polite" aria-atomic="true">
                    <span class="text-5xl font-bold tabular-nums text-gray-900 leading-none tracking-tight"
                          x-text="timeDisplay"></span>
                    <span class="text-xs font-semibold uppercase tracking-widest mt-2.5"
                          :class="modeTextClass"
                          x-text="modeLabel"></span>
                </div>
            </div>

            {{-- Session cycle dots --}}
            <div class="flex gap-2 items-center justify-center mb-6" title="Sessions in current cycle">
                <template x-for="i in sessionGoal" :key="i">
                    <div class="rounded-full transition-all duration-300"
                         :class="i <= dotsFilled ? 'w-3 h-3 bg-brand-500' : 'w-2.5 h-2.5 bg-gray-200'">
                    </div>
                </template>
                <span class="text-xs text-emerald-600 font-medium ml-1"
                      x-show="dotsFilled > 0 && dotsFilled === sessionGoal - 1 && mode === 'work'"
                      x-transition>
                    Long break next!
                </span>
            </div>

            {{-- Controls --}}
            <div class="flex items-center justify-center gap-3 mb-5">
                {{-- Reset --}}
                <button type="button" @click="reset()"
                        class="btn btn-secondary"
                        title="Reset current session">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset
                </button>

                {{-- Start / Pause --}}
                <button type="button"
                        @click="isRunning ? pause() : start()"
                        class="btn btn-lg text-white font-semibold px-10 shadow-sm rounded-xl"
                        :style="`background-color:${isRunning ? '#6b7280' : modeColor}`"
                        :aria-label="isRunning ? 'Pause timer' : (timeLeft < totalTime && timeLeft > 0 ? 'Resume timer' : 'Start timer')">
                    <template x-if="!isRunning">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </template>
                    <template x-if="isRunning">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                        </svg>
                    </template>
                    <span x-text="isRunning ? 'Pause' : (timeLeft < totalTime && timeLeft > 0 ? 'Resume' : 'Start')"></span>
                </button>
            </div>

            {{-- Next up hint --}}
            <p class="text-xs text-gray-400">
                Next up: <span class="font-medium text-gray-600" x-text="nextLabel"></span>
            </p>
        </div>

        {{-- ── Stats Row ── --}}
        <div class="grid grid-cols-3 gap-3 mb-4">
            <div class="card p-4 text-center">
                <div class="text-2xl font-bold text-gray-900" x-text="todaySessions"></div>
                <div class="text-xs text-gray-500 mt-1">Sessions today</div>
            </div>
            <div class="card p-4 text-center">
                <div class="text-2xl font-bold text-gray-900">
                    <span x-text="dotsFilled"></span><span class="text-base text-gray-400">/<span x-text="sessionGoal"></span></span>
                </div>
                <div class="text-xs text-gray-500 mt-1">Cycle progress</div>
            </div>
            <div class="card p-4 text-center">
                <div class="text-xl font-bold text-gray-900"
                     x-text="focusMinutesToday >= 60
                         ? Math.floor(focusMinutesToday/60) + 'h ' + (focusMinutesToday % 60) + 'm'
                         : focusMinutesToday + 'm'"></div>
                <div class="text-xs text-gray-500 mt-1">Focus today</div>
            </div>
        </div>

        {{-- ── Settings Card (collapsible) ── --}}
        <div class="card mb-4">
            {{-- Header toggle --}}
            <div class="p-5 flex items-center justify-between cursor-pointer select-none"
                 @click="showSettings = !showSettings">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="text-sm font-semibold text-gray-700">Settings</span>
                </div>
                <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                     :class="showSettings ? 'rotate-180' : ''"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>

            {{-- Settings body --}}
            <div x-show="showSettings"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">

                <div class="px-5 pb-5 border-t border-gray-100 pt-4 space-y-5">

                    {{-- Durations --}}
                    <div>
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Timer Durations (minutes)</h4>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="form-label">🍅 Focus</label>
                                <input type="number" x-model.number="settings.work"
                                       min="1" max="120" @change="applySettings()"
                                       class="form-input">
                            </div>
                            <div>
                                <label class="form-label">☕ Short Break</label>
                                <input type="number" x-model.number="settings.short"
                                       min="1" max="60" @change="applySettings()"
                                       class="form-input">
                            </div>
                            <div>
                                <label class="form-label">🛌 Long Break</label>
                                <input type="number" x-model.number="settings.long"
                                       min="1" max="120" @change="applySettings()"
                                       class="form-input">
                            </div>
                        </div>
                    </div>

                    {{-- Session goal --}}
                    <div>
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Cycle Goal</h4>
                        <div class="flex items-center gap-3">
                            <input type="number" x-model.number="sessionGoal"
                                   min="1" max="10" @change="applySettings()"
                                   class="form-input w-20">
                            <span class="text-sm text-gray-500">focus sessions before long break</span>
                        </div>
                    </div>

                    {{-- Automation toggles --}}
                    <div>
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Automation</h4>
                        <div class="space-y-3">

                            <div class="flex items-start gap-3">
                                <button type="button" role="switch" :aria-checked="autoStartBreaks"
                                    @click="autoStartBreaks = !autoStartBreaks; save()"
                                    class="relative mt-0.5 inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1"
                                    :class="autoStartBreaks ? 'bg-brand-600' : 'bg-gray-200'">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform"
                                          :class="autoStartBreaks ? 'translate-x-4' : 'translate-x-0'"></span>
                                </button>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Auto-start breaks</p>
                                    <p class="text-xs text-gray-400">Automatically begin the break when a work session ends</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <button type="button" role="switch" :aria-checked="autoStartWork"
                                    @click="autoStartWork = !autoStartWork; save()"
                                    class="relative mt-0.5 inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1"
                                    :class="autoStartWork ? 'bg-brand-600' : 'bg-gray-200'">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform"
                                          :class="autoStartWork ? 'translate-x-4' : 'translate-x-0'"></span>
                                </button>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Auto-start work sessions</p>
                                    <p class="text-xs text-gray-400">Automatically begin the next focus session after a break</p>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Alert toggles --}}
                    <div>
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Alerts</h4>
                        <div class="space-y-3">

                            <div class="flex items-start gap-3">
                                <button type="button" role="switch" :aria-checked="soundEnabled"
                                    @click="soundEnabled = !soundEnabled; save()"
                                    class="relative mt-0.5 inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1"
                                    :class="soundEnabled ? 'bg-brand-600' : 'bg-gray-200'">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform"
                                          :class="soundEnabled ? 'translate-x-4' : 'translate-x-0'"></span>
                                </button>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Sound alert</p>
                                    <p class="text-xs text-gray-400">Play a chime when a session completes</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <button type="button" role="switch" :aria-checked="notifEnabled"
                                    @click="toggleNotif()"
                                    class="relative mt-0.5 inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1"
                                    :class="notifEnabled ? 'bg-brand-600' : 'bg-gray-200'">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform"
                                          :class="notifEnabled ? 'translate-x-4' : 'translate-x-0'"></span>
                                </button>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Browser notifications</p>
                                    <p class="text-xs text-gray-400">Show a desktop notification when a session ends (requires permission)</p>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="pt-2 border-t border-gray-100 flex items-center justify-between">
                        <p class="text-xs text-gray-400">Settings and stats are saved locally in your browser</p>
                        <button type="button" @click="resetStats()"
                                class="text-xs text-red-500 hover:text-red-700 font-medium transition-colors">
                            Reset today's stats
                        </button>
                    </div>

                </div>
            </div>
        </div>

        {{-- ── How It Works ── --}}
        <div class="card p-6 mb-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">How the Pomodoro Technique Works</h3>
            <ol class="space-y-2 text-sm text-gray-500">
                <li class="flex items-start gap-2">
                    <span class="text-brand-500 font-bold mt-0.5 flex-shrink-0">1.</span>
                    Choose your focus duration (default 25 minutes) and click <strong class="text-gray-700">Start</strong>.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-500 font-bold mt-0.5 flex-shrink-0">2.</span>
                    Work deeply until the chime sounds — no distractions.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-500 font-bold mt-0.5 flex-shrink-0">3.</span>
                    Take a short break (5 min), then start the next focus session.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-500 font-bold mt-0.5 flex-shrink-0">4.</span>
                    After every 4 sessions, take a longer break (15 min) to recharge.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-500 font-bold mt-0.5 flex-shrink-0">●</span>
                    Each dot represents one completed focus session in your current cycle.
                </li>
            </ol>
        </div>

        {{-- Related Tools --}}
        @if($relatedTools->count())
        <div class="mt-2">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Related Tools</h3>
            <div class="grid grid-cols-2 gap-3">
                @foreach($relatedTools as $related)
                <a href="{{ route('tools.show', $related->slug) }}"
                   class="card-hover p-4 flex items-center gap-3 no-underline">
                    <span class="text-2xl">{{ $related->icon }}</span>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $related->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $related->short_description }}</p>
                    </div>
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
function pomodoroTimer() {
    return {
        /* ── State ── */
        mode:      'work',      // 'work' | 'short' | 'long'
        timeLeft:  25 * 60,    // seconds
        isRunning: false,

        durations: { work: 25*60, short: 5*60, long: 15*60 },  // seconds
        settings:  { work: 25,    short: 5,     long: 15    },  // minutes (input bindings)

        sessionGoal:       4,
        sessionsCompleted: 0,   // total sessions (restored from today)
        todaySessions:     0,   // persisted per-day

        autoStartBreaks: false,
        autoStartWork:   false,
        soundEnabled:    true,
        notifEnabled:    false,

        showSettings: false,
        _interval:    null,

        /* ── Computed ── */
        get totalTime()     { return this.durations[this.mode]; },
        get progress()      { return this.totalTime > 0 ? (this.totalTime - this.timeLeft) / this.totalTime : 0; },
        get circumference() { return 2 * Math.PI * 90; },        // r=90 → ≈565.49
        get dashOffset()    { return this.circumference * this.progress; },

        get minutes()     { return Math.floor(this.timeLeft / 60); },
        get seconds()     { return this.timeLeft % 60; },
        get timeDisplay() {
            return String(this.minutes).padStart(2,'0') + ':' + String(this.seconds).padStart(2,'0');
        },

        get modeLabel() {
            return { work:'Focus', short:'Short Break', long:'Long Break' }[this.mode];
        },
        get modeColor() {
            return { work:'#4f46e5', short:'#10b981', long:'#0ea5e9' }[this.mode];
        },
        get modeTextClass() {
            return { work:'text-brand-600', short:'text-emerald-600', long:'text-sky-600' }[this.mode];
        },

        /* How many sessions filled in the current cycle (0 … sessionGoal-1) */
        get dotsFilled() { return this.sessionsCompleted % this.sessionGoal; },

        get nextMode() {
            if (this.mode !== 'work') return 'work';
            return (this.sessionsCompleted + 1) % this.sessionGoal === 0 ? 'long' : 'short';
        },
        get nextLabel() {
            const map = {
                work:  '🍅 Focus ('  + this.settings.work  + 'm)',
                short: '☕ Short Break (' + this.settings.short + 'm)',
                long:  '🛌 Long Break ('  + this.settings.long  + 'm)',
            };
            return map[this.nextMode];
        },

        get focusMinutesToday() { return this.todaySessions * this.settings.work; },

        /* ── Lifecycle ── */
        init() {
            this.load();
            this.timeLeft = this.durations[this.mode];

            /* Sync browser tab title with live timer */
            this.$watch('timeLeft', () => {
                document.title = this.timeDisplay + ' — ' + this.modeLabel + ' | Pomodoro Timer';
            });
            this.$watch('mode', () => {
                document.title = this.timeDisplay + ' — ' + this.modeLabel + ' | Pomodoro Timer';
            });
        },

        /* ── Persistence ── */
        load() {
            const s = JSON.parse(localStorage.getItem('pomo_settings') || '{}');
            if (s.work  > 0) this.settings.work  = s.work;
            if (s.short > 0) this.settings.short = s.short;
            if (s.long  > 0) this.settings.long  = s.long;
            if (s.goal  > 0) this.sessionGoal    = s.goal;
            if (s.sound      !== undefined) this.soundEnabled    = s.sound;
            if (s.notif      !== undefined) this.notifEnabled    = s.notif;
            if (s.autoBreaks !== undefined) this.autoStartBreaks = s.autoBreaks;
            if (s.autoWork   !== undefined) this.autoStartWork   = s.autoWork;

            this.durations.work  = this.settings.work  * 60;
            this.durations.short = this.settings.short * 60;
            this.durations.long  = this.settings.long  * 60;

            /* Restore today's count */
            const td = JSON.parse(localStorage.getItem('pomo_today') || '{}');
            if (td.date === new Date().toDateString()) {
                this.todaySessions     = td.sessions || 0;
                this.sessionsCompleted = td.sessions || 0;
            }
        },

        save() {
            localStorage.setItem('pomo_settings', JSON.stringify({
                work:       this.settings.work,
                short:      this.settings.short,
                long:       this.settings.long,
                goal:       this.sessionGoal,
                sound:      this.soundEnabled,
                notif:      this.notifEnabled,
                autoBreaks: this.autoStartBreaks,
                autoWork:   this.autoStartWork,
            }));
        },

        saveToday() {
            localStorage.setItem('pomo_today', JSON.stringify({
                date:     new Date().toDateString(),
                sessions: this.todaySessions,
            }));
        },

        applySettings() {
            this.settings.work  = Math.max(1,  Math.min(120, parseInt(this.settings.work)  || 25));
            this.settings.short = Math.max(1,  Math.min(60,  parseInt(this.settings.short) || 5));
            this.settings.long  = Math.max(1,  Math.min(120, parseInt(this.settings.long)  || 15));
            this.sessionGoal    = Math.max(1,  Math.min(10,  parseInt(this.sessionGoal)    || 4));

            this.durations.work  = this.settings.work  * 60;
            this.durations.short = this.settings.short * 60;
            this.durations.long  = this.settings.long  * 60;

            if (!this.isRunning) this.timeLeft = this.durations[this.mode];
            this.save();
        },

        /* ── Mode switching ── */
        setMode(m) {
            if (this.isRunning) {
                if (!confirm('Stop the current session and switch mode?')) return;
            }
            this._stop();
            this.mode     = m;
            this.timeLeft = this.durations[m];
        },

        /* ── Timer controls ── */
        start() {
            if (this.isRunning || this.timeLeft <= 0) return;
            this.isRunning = true;
            this._interval = setInterval(() => {
                this.timeLeft--;
                if (this.timeLeft <= 0) {
                    this.timeLeft = 0;
                    this.onComplete();
                }
            }, 1000);
        },

        pause() {
            if (!this.isRunning) return;
            this._stop();
        },

        reset() {
            this._stop();
            this.timeLeft = this.durations[this.mode];
        },

        _stop() {
            clearInterval(this._interval);
            this._interval = null;
            this.isRunning = false;
        },

        /* ── Session completion ── */
        onComplete() {
            this._stop();
            this.playSound();

            /* Record work session BEFORE switching mode (notification reads old mode) */
            if (this.mode === 'work') {
                this.sessionsCompleted++;
                this.todaySessions++;
                this.saveToday();
            }

            this.sendNotification();    // reads this.mode (still the completed mode)

            const next = this.nextMode; // computed from new sessionsCompleted
            this.mode     = next;
            this.timeLeft = this.durations[next];

            const auto = next !== 'work' ? this.autoStartBreaks : this.autoStartWork;
            if (auto) setTimeout(() => this.start(), 1500);
        },

        /* ── Sound (Web Audio API — no external files) ── */
        playSound() {
            if (!this.soundEnabled) return;
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                /* Three ascending chimes: G5 → A5 → C6 */
                [[0, 783.99], [0.35, 880], [0.7, 1046.50]].forEach(function(pair) {
                    var t = pair[0], freq = pair[1];
                    var osc  = ctx.createOscillator();
                    var gain = ctx.createGain();
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.type           = 'sine';
                    osc.frequency.value = freq;
                    gain.gain.setValueAtTime(0.22, ctx.currentTime + t);
                    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + t + 0.45);
                    osc.start(ctx.currentTime + t);
                    osc.stop(ctx.currentTime + t + 0.5);
                });
            } catch(e) { /* AudioContext unavailable (non-interactive context, etc.) */ }
        },

        /* ── Browser notifications ── */
        sendNotification() {
            if (!this.notifEnabled) return;
            if (!('Notification' in window)) return;
            if (Notification.permission !== 'granted') return;
            var msgs = {
                work:  { title:'🍅 Focus Complete!',   body:'Time for a well-earned break.' },
                short: { title:'☕ Short Break Over!',  body:'Ready to focus again?' },
                long:  { title:'🛌 Long Break Over!',   body:"Refreshed? Let's get back to work." },
            };
            var m = msgs[this.mode] || msgs.work;
            try { new Notification(m.title, { body: m.body, icon: '/favicon.svg' }); } catch(e) {}
        },

        toggleNotif() {
            if (!('Notification' in window)) {
                alert('Your browser does not support desktop notifications.');
                return;
            }
            if (Notification.permission === 'denied') {
                alert('Notifications are blocked. Please enable them in your browser settings, then refresh the page.');
                return;
            }
            if (Notification.permission === 'granted') {
                this.notifEnabled = !this.notifEnabled;
                this.save();
                return;
            }
            /* Default — request permission */
            var self = this;
            Notification.requestPermission().then(function(p) {
                self.notifEnabled = p === 'granted';
                if (p === 'denied') alert('Permission denied. You can enable notifications in your browser settings.');
                self.save();
            });
        },

        resetStats() {
            if (!confirm("Reset today's session count to zero?")) return;
            this.todaySessions     = 0;
            this.sessionsCompleted = 0;
            this.saveToday();
        },
    };
}
</script>
@endpush
