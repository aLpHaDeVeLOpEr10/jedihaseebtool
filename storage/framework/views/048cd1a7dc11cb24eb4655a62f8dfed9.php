<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50">

    
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-xl mx-auto px-4 sm:px-6 py-10">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                <?php echo e($tool->icon); ?> <?php echo e($tool->name); ?>

            </h1>
            <p class="text-gray-500 mt-2"><?php echo e($tool->short_description); ?></p>
        </div>
    </div>

    
    <div class="max-w-xl mx-auto px-4 sm:px-6 py-10"
         x-data="studyTimer()"
         x-init="init()">

        
        <div class="card p-6 text-center mb-4">

            
            <div class="mb-5">
                <p class="text-sm font-semibold text-gray-700" x-text="sessionLabel"></p>
                <p class="text-xs text-gray-400 mt-0.5" x-show="phase === 'idle'"
                   x-text="`${totalSessions} sessions · ${formatMins(totalPlanMins)} total`"></p>
            </div>

            
            <div class="relative mx-auto mb-5" style="width:220px;height:220px;">

                <svg viewBox="0 0 220 220" class="w-full h-full -rotate-90" aria-hidden="true">
                    
                    <circle cx="110" cy="110" r="90"
                            fill="none" stroke="#e5e7eb" stroke-width="11"/>
                    
                    <circle cx="110" cy="110" r="90"
                            fill="none"
                            :stroke="phaseColor"
                            stroke-width="11"
                            stroke-linecap="round"
                            :stroke-dasharray="circumference"
                            :stroke-dashoffset="dashOffset"
                            :style="`transition:stroke-dashoffset ${isRunning ? '0.95s linear' : '0s'}`"/>
                </svg>

                
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">

                    
                    <div x-show="phase !== 'complete'">
                        <div class="font-bold tabular-nums text-gray-900 leading-none tracking-tight text-center"
                             :class="(phase === 'idle' ? studyMins : minutes) >= 100 ? 'text-3xl' : 'text-5xl'"
                             x-text="timeDisplay"
                             aria-live="polite" aria-atomic="true"></div>
                        <div class="text-xs font-semibold uppercase tracking-widest mt-2 text-center"
                             :class="phaseTextClass"
                             x-text="phaseLabel"></div>
                    </div>

                    
                    <div x-show="phase === 'complete'" class="text-center">
                        <div class="text-5xl mb-1">🎉</div>
                        <div class="text-xs font-semibold uppercase tracking-widest text-emerald-600">Complete!</div>
                    </div>
                </div>
            </div>

            
            <div x-show="phase === 'complete'" x-transition class="mb-5 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-sm text-emerald-800">
                🎉 You completed all <strong x-text="totalSessions"></strong> study sessions!
                Total focus time: <strong x-text="studiedDisplay"></strong>. Great work!
            </div>

            
            <div x-show="phase !== 'complete'">
                <div class="flex items-center justify-center gap-3 mb-4">
                    
                    <button type="button" @click="reset()"
                            class="btn btn-secondary"
                            x-show="phase !== 'idle'"
                            title="Reset all sessions">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset
                    </button>

                    
                    <button type="button"
                            @click="isRunning ? pause() : start()"
                            class="btn btn-lg text-white font-semibold shadow-sm rounded-xl"
                            :class="phase === 'idle' ? 'px-14' : 'px-10'"
                            :style="`background-color:${isRunning ? '#6b7280' : phaseColor}`"
                            :aria-label="isRunning ? 'Pause' : (phase === 'idle' ? 'Start studying' : 'Resume')">
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
                        <span x-text="isRunning ? 'Pause' : (phase === 'idle' ? 'Start Studying' : 'Resume')"></span>
                    </button>
                </div>

                
                <p class="text-xs text-gray-400" x-show="phase !== 'idle'">
                    Next:
                    <span class="font-medium text-gray-600" x-text="nextUpLabel"></span>
                </p>
            </div>

            
            <div x-show="phase === 'complete'" x-transition>
                <button type="button" @click="reset()"
                        class="btn btn-primary btn-lg px-10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Start New Session
                </button>
            </div>
        </div>

        
        <div class="grid grid-cols-3 gap-3 mb-4">
            <div class="card p-4 text-center">
                <div class="text-2xl font-bold text-gray-900">
                    <span x-text="sessionsComplete"></span><span class="text-base text-gray-400">/<span x-text="totalSessions"></span></span>
                </div>
                <div class="text-xs text-gray-500 mt-1">Sessions done</div>
            </div>
            <div class="card p-4 text-center">
                <div class="text-xl font-bold text-brand-600" x-text="studiedDisplay"></div>
                <div class="text-xs text-gray-500 mt-1">Time studied</div>
            </div>
            <div class="card p-4 text-center">
                <div class="text-xl font-bold text-gray-900" x-text="formatMins(totalPlanMins)"></div>
                <div class="text-xs text-gray-500 mt-1">Planned total</div>
            </div>
        </div>

        
        <div class="card mb-4">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-700">Session Schedule</h3>
                <span class="badge badge-gray text-xs" x-text="totalSessions + ' sessions · ' + formatMins(totalPlanMins)"></span>
            </div>
            <div class="p-4 space-y-2" :class="schedule.length > 7 ? 'max-h-80 overflow-y-auto' : ''">
                <template x-for="(item, idx) in schedule" :key="item.id">
                    <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl border transition-all duration-200"
                         :class="{
                             'bg-brand-50 border-brand-300 shadow-sm': idx === currentScheduleIdx && item.type === 'study',
                             'bg-amber-50 border-amber-300 shadow-sm': idx === currentScheduleIdx && item.type === 'break',
                             'bg-emerald-50 border-emerald-100':       idx < currentScheduleIdx,
                             'bg-white border-gray-100':               idx > currentScheduleIdx,
                         }">

                        
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-sm font-bold transition-all"
                             :class="{
                                 'bg-brand-500 text-white':   idx === currentScheduleIdx && item.type === 'study',
                                 'bg-amber-400 text-white':   idx === currentScheduleIdx && item.type === 'break',
                                 'bg-emerald-500 text-white': idx < currentScheduleIdx,
                                 'bg-gray-100 text-gray-400': idx > currentScheduleIdx,
                             }">
                            <span x-show="idx < currentScheduleIdx">✓</span>
                            <span x-show="idx === currentScheduleIdx && item.type === 'study'">📚</span>
                            <span x-show="idx === currentScheduleIdx && item.type === 'break'">☕</span>
                            <span x-show="idx > currentScheduleIdx && item.type === 'study'" class="text-base">📖</span>
                            <span x-show="idx > currentScheduleIdx && item.type === 'break'" class="text-base">💤</span>
                        </div>

                        
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium transition-all"
                               :class="{
                                   'text-brand-700':   idx === currentScheduleIdx && item.type === 'study',
                                   'text-amber-700':   idx === currentScheduleIdx && item.type === 'break',
                                   'text-emerald-700': idx < currentScheduleIdx,
                                   'text-gray-400':    idx > currentScheduleIdx,
                                   'text-gray-700':    idx === currentScheduleIdx,
                               }"
                               x-text="item.label"></p>
                            
                            <p x-show="idx === currentScheduleIdx" class="text-xs mt-0.5 font-medium"
                               :class="item.type === 'study' ? 'text-brand-500' : 'text-amber-500'">
                                <span x-text="timeDisplay"></span> remaining
                                <span x-show="!isRunning && phase !== 'idle'" class="text-gray-400 font-normal"> · paused</span>
                            </p>
                        </div>

                        
                        <span class="badge flex-shrink-0 transition-all"
                              :class="{
                                  'bg-brand-100 text-brand-700': idx === currentScheduleIdx && item.type === 'study',
                                  'bg-amber-100 text-amber-700': idx === currentScheduleIdx && item.type === 'break',
                                  'bg-emerald-100 text-emerald-700': idx < currentScheduleIdx,
                                  'bg-gray-100 text-gray-500':       idx > currentScheduleIdx,
                              }"
                              x-text="item.mins + 'm'"></span>
                    </div>
                </template>
            </div>
        </div>

        
        <div class="card mb-4">
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

            <div x-show="showSettings"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">

                <div class="px-5 pb-5 border-t border-gray-100 pt-4 space-y-5">

                    
                    <div x-show="isRunning" class="alert alert-info text-xs">
                        ℹ Changes to duration apply from the next session. Reset to apply immediately.
                    </div>

                    
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="form-label mb-0">📚 Study Duration</label>
                            <span class="text-sm font-semibold text-brand-600" x-text="studyMins + ' min'"></span>
                        </div>
                        <input type="range" x-model.number="studyMins"
                               min="5" max="180" step="5"
                               @change="applySettings()"
                               class="w-full h-2 rounded-full appearance-none cursor-pointer bg-gray-200"
                               style="accent-color:#4f46e5">
                        <div class="flex justify-between text-xs text-gray-400 mt-1">
                            <span>5 min</span><span>3 hours</span>
                        </div>
                    </div>

                    
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="form-label mb-0">☕ Break Duration</label>
                            <span class="text-sm font-semibold text-amber-600" x-text="breakMins + ' min'"></span>
                        </div>
                        <input type="range" x-model.number="breakMins"
                               min="1" max="60" step="1"
                               @change="applySettings()"
                               class="w-full h-2 rounded-full appearance-none cursor-pointer bg-gray-200"
                               style="accent-color:#f59e0b">
                        <div class="flex justify-between text-xs text-gray-400 mt-1">
                            <span>1 min</span><span>1 hour</span>
                        </div>
                    </div>

                    
                    <div>
                        <label class="form-label">Number of Study Sessions</label>
                        <div class="flex items-center gap-2">
                            <button type="button"
                                    @click="totalSessions = Math.max(1, totalSessions - 1); applySettings()"
                                    class="btn btn-secondary btn-sm w-9 h-9 p-0 flex items-center justify-center text-lg font-bold">−</button>
                            <input type="number" x-model.number="totalSessions"
                                   min="1" max="10" @change="applySettings()"
                                   class="form-input w-20 text-center font-semibold text-lg">
                            <button type="button"
                                    @click="totalSessions = Math.min(10, totalSessions + 1); applySettings()"
                                    class="btn btn-secondary btn-sm w-9 h-9 p-0 flex items-center justify-center text-lg font-bold">+</button>
                            <span class="text-sm text-gray-500">sessions (max 10)</span>
                        </div>
                    </div>

                    
                    <div class="bg-gray-50 rounded-xl p-3 text-xs text-gray-500 text-center">
                        <span x-text="totalSessions + ' × ' + studyMins + 'm study'"></span>
                        <span x-show="totalSessions > 1"> + <span x-text="(totalSessions - 1) + ' × ' + breakMins + 'm break'"></span></span>
                        <span> = </span>
                        <span class="font-semibold text-gray-700" x-text="formatMins(totalPlanMins)"></span>
                        <span> total</span>
                    </div>

                    
                    <div>
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Automation</h4>
                        <div class="flex items-start gap-3">
                            <button type="button" role="switch" :aria-checked="autoStart"
                                @click="autoStart = !autoStart; save()"
                                class="relative mt-0.5 inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1"
                                :class="autoStart ? 'bg-brand-600' : 'bg-gray-200'">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform"
                                      :class="autoStart ? 'translate-x-4' : 'translate-x-0'"></span>
                            </button>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Auto-start next phase</p>
                                <p class="text-xs text-gray-400">Automatically begin breaks and next study sessions</p>
                            </div>
                        </div>
                    </div>

                    
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
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-700">Sound alert</p>
                                        <button type="button" @click="playSound('study')"
                                                class="text-xs text-brand-600 hover:text-brand-800 font-medium transition-colors">
                                            🔔 Test
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-400">Play a chime when a session or break ends</p>
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
                                    <p class="text-xs text-gray-400">Desktop pop-up when sessions end (requires permission)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        
        <div class="card p-6 mb-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">How to Use</h3>
            <ol class="space-y-2 text-sm text-gray-500">
                <li class="flex items-start gap-2">
                    <span class="text-brand-500 font-bold mt-0.5 flex-shrink-0">1.</span>
                    Set your study duration, break length, and number of sessions in Settings.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-500 font-bold mt-0.5 flex-shrink-0">2.</span>
                    Click <strong class="text-gray-700">Start Studying</strong> — the countdown begins.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-500 font-bold mt-0.5 flex-shrink-0">3.</span>
                    A chime sounds when each study session ends. Take your break, then continue.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-500 font-bold mt-0.5 flex-shrink-0">4.</span>
                    Track progress in the schedule — completed sessions turn green automatically.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-500 font-bold mt-0.5 flex-shrink-0">5.</span>
                    Once all sessions are complete, a celebration screen appears with your total study time.
                </li>
            </ol>
        </div>

        
        <?php if($relatedTools->count()): ?>
        <div>
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Related Tools</h3>
            <div class="grid grid-cols-2 gap-3">
                <?php $__currentLoopData = $relatedTools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('tools.show', $related->slug)); ?>"
                   class="card-hover p-4 flex items-center gap-3 no-underline">
                    <span class="text-2xl"><?php echo e($related->icon); ?></span>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate"><?php echo e($related->name); ?></p>
                        <p class="text-xs text-gray-400 truncate"><?php echo e($related->short_description); ?></p>
                    </div>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function studyTimer() {
    return {
        /* ── Settings ── */
        studyMins:     25,
        breakMins:     5,
        totalSessions: 3,

        /* ── Timer state ── */
        phase:          'idle',   // 'idle' | 'studying' | 'break' | 'complete'
        currentSession: 1,
        timeLeft:       0,        // initialised in init()
        isRunning:      false,

        /* ── Accumulated stats ── */
        _studiedSecs: 0,
        _restedSecs:  0,

        /* ── Options ── */
        soundEnabled: true,
        notifEnabled: false,
        autoStart:    false,

        /* ── UI ── */
        showSettings: false,
        _interval:    null,

        /* ── Computed ── */

        get totalTime() {
            return this.phase === 'break' ? this.breakMins * 60 : this.studyMins * 60;
        },

        /* Ring progress: 0 = full ring, 1 = empty ring */
        get progress() {
            if (this.phase === 'complete') return 0;   // full emerald ring (celebratory)
            if (this.phase === 'idle')     return 0;   // full gray ring (ready)
            const total = this.phase === 'break' ? this.breakMins * 60 : this.studyMins * 60;
            return total > 0 ? (total - this.timeLeft) / total : 0;
        },
        get circumference() { return 2 * Math.PI * 90; },  // r = 90 → ≈ 565.49
        get dashOffset()    { return this.circumference * this.progress; },

        get minutes() { return Math.floor(this.timeLeft / 60); },
        get seconds()  { return this.timeLeft % 60; },

        get timeDisplay() {
            if (this.phase === 'complete') return '00:00';
            const secs = this.phase === 'idle' ? this.studyMins * 60 : this.timeLeft;
            const m    = Math.floor(secs / 60);
            const s    = secs % 60;
            return String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
        },

        get phaseLabel() {
            return { idle:'Ready', studying:'Studying', break:'Break', complete:'Done!' }[this.phase] || '';
        },
        get phaseColor() {
            return { idle:'#d1d5db', studying:'#4f46e5', break:'#f59e0b', complete:'#059669' }[this.phase];
        },
        get phaseTextClass() {
            return { idle:'text-gray-400', studying:'text-brand-600', break:'text-amber-600', complete:'text-emerald-600' }[this.phase];
        },

        get sessionLabel() {
            if (this.phase === 'idle')     return 'Ready to study';
            if (this.phase === 'complete') return 'All sessions complete!';
            if (this.phase === 'break')    return 'Break — Session ' + this.currentSession + ' complete';
            return 'Study Session ' + this.currentSession + ' of ' + this.totalSessions;
        },

        get nextUpLabel() {
            if (this.phase === 'studying') {
                if (this.currentSession >= this.totalSessions) return 'Completion 🎉 after this';
                return '☕ Break (' + this.breakMins + 'm)';
            }
            if (this.phase === 'break') {
                return '📚 Study Session ' + (this.currentSession + 1) + ' of ' + this.totalSessions + ' (' + this.studyMins + 'm)';
            }
            return '';
        },

        get totalPlanMins() {
            return this.totalSessions * this.studyMins + Math.max(0, this.totalSessions - 1) * this.breakMins;
        },

        /* Build ordered list of study + break blocks */
        get schedule() {
            var s = [];
            for (var i = 1; i <= this.totalSessions; i++) {
                s.push({ id:'s' + i, type:'study', session:i, label:'Study Session ' + i, mins:this.studyMins });
                if (i < this.totalSessions) {
                    s.push({ id:'b' + i, type:'break', session:i, label:'Break ' + i, mins:this.breakMins });
                }
            }
            return s;
        },

        /* Index in schedule[] that is currently active */
        get currentScheduleIdx() {
            if (this.phase === 'idle')     return -1;
            if (this.phase === 'complete') return Infinity;
            var base = (this.currentSession - 1) * 2;
            return this.phase === 'break' ? base + 1 : base;
        },

        /* Sessions fully completed (not counting the one in progress) */
        get sessionsComplete() {
            if (this.phase === 'complete') return this.totalSessions;
            if (this.phase === 'idle')     return 0;
            return this.phase === 'break' ? this.currentSession : Math.max(0, this.currentSession - 1);
        },

        get studiedDisplay() {
            var t = this._studiedSecs;
            var h = Math.floor(t / 3600);
            var m = Math.floor((t % 3600) / 60);
            var s = t % 60;
            if (h > 0) return h + 'h ' + m + 'm';
            if (m > 0) return m + 'm ' + s + 's';
            return s + 's';
        },

        /* ── Lifecycle ── */
        init() {
            this.load();
            this.timeLeft = this.studyMins * 60;

            /* Keep browser tab in sync */
            this.$watch('timeLeft', function() {
                if (this.phase !== 'idle' && this.phase !== 'complete') {
                    document.title = this.timeDisplay + ' — ' + this.phaseLabel + ' | Study Timer';
                }
            }.bind(this));
        },

        /* ── Persistence ── */
        load() {
            var s = JSON.parse(localStorage.getItem('study_timer_cfg') || '{}');
            if (s.studyMins     > 0) this.studyMins     = s.studyMins;
            if (s.breakMins     > 0) this.breakMins     = s.breakMins;
            if (s.totalSessions > 0) this.totalSessions = s.totalSessions;
            if (s.sound     !== undefined) this.soundEnabled = s.sound;
            if (s.notif     !== undefined) this.notifEnabled = s.notif;
            if (s.autoStart !== undefined) this.autoStart    = s.autoStart;
        },

        save() {
            localStorage.setItem('study_timer_cfg', JSON.stringify({
                studyMins:     this.studyMins,
                breakMins:     this.breakMins,
                totalSessions: this.totalSessions,
                sound:         this.soundEnabled,
                notif:         this.notifEnabled,
                autoStart:     this.autoStart,
            }));
        },

        applySettings() {
            this.studyMins     = Math.max(1,  Math.min(180, parseInt(this.studyMins)     || 25));
            this.breakMins     = Math.max(1,  Math.min(60,  parseInt(this.breakMins)     || 5));
            this.totalSessions = Math.max(1,  Math.min(10,  parseInt(this.totalSessions) || 3));
            /* Only reset the display time if timer is idle */
            if (this.phase === 'idle') this.timeLeft = this.studyMins * 60;
            this.save();
        },

        /* ── Controls ── */
        start() {
            if (this.isRunning || this.phase === 'complete') return;

            if (this.phase === 'idle') {
                /* Fresh start */
                this.phase          = 'studying';
                this.currentSession = 1;
                this.timeLeft       = this.studyMins * 60;
                this._studiedSecs   = 0;
                this._restedSecs    = 0;
            }

            this.isRunning = true;
            var self = this;
            this._interval = setInterval(function() {
                if (self.phase === 'studying') self._studiedSecs++;
                else if (self.phase === 'break') self._restedSecs++;
                self.timeLeft--;
                if (self.timeLeft <= 0) {
                    self.timeLeft = 0;
                    self.onPhaseComplete();
                }
            }, 1000);
        },

        pause() {
            if (!this.isRunning) return;
            clearInterval(this._interval);
            this._interval = null;
            this.isRunning = false;
        },

        reset() {
            clearInterval(this._interval);
            this._interval    = null;
            this.isRunning    = false;
            this.phase        = 'idle';
            this.currentSession = 1;
            this.timeLeft     = this.studyMins * 60;
            this._studiedSecs = 0;
            this._restedSecs  = 0;
            document.title    = 'Study Timer';
        },

        /* ── Phase completion logic ── */
        onPhaseComplete() {
            clearInterval(this._interval);
            this._interval = null;
            this.isRunning = false;

            if (this.phase === 'studying') {
                this.playSound('study');
                this.sendNotification(
                    '📚 Study Session ' + this.currentSession + ' Complete!',
                    this.currentSession < this.totalSessions
                        ? 'Take a ' + this.breakMins + '-minute break.'
                        : 'All ' + this.totalSessions + ' sessions done! Amazing work!'
                );

                if (this.currentSession >= this.totalSessions) {
                    /* All sessions finished */
                    setTimeout(function() { }, 100);
                    this.phase = 'complete';
                    this.playSound('complete');
                    document.title = 'Done! | Study Timer';
                    return;
                }

                /* Transition to break */
                this.phase    = 'break';
                this.timeLeft = this.breakMins * 60;

            } else if (this.phase === 'break') {
                this.playSound('break');
                this.sendNotification(
                    '☕ Break Over!',
                    'Ready for Study Session ' + (this.currentSession + 1) + '?'
                );
                this.currentSession++;
                this.phase    = 'studying';
                this.timeLeft = this.studyMins * 60;
            }

            if (this.autoStart) {
                var self = this;
                setTimeout(function() { self.start(); }, 1500);
            }
        },

        /* ── Sound (Web Audio API — three distinct patterns) ── */
        playSound(type) {
            if (!this.soundEnabled) return;
            try {
                var ctx   = new (window.AudioContext || window.webkitAudioContext)();
                var notes = {
                    /* Study done → descending (relax now) */
                    study:    [[0, 1046.5], [0.3, 880], [0.6, 783.99]],
                    /* Break done → ascending (energise) */
                    break:    [[0, 783.99], [0.3, 880], [0.6, 1046.5]],
                    /* All done → triumphant C–E–G–C chord */
                    complete: [[0, 523.25], [0.2, 659.25], [0.4, 783.99], [0.65, 1046.5]],
                };
                (notes[type] || notes.study).forEach(function(pair) {
                    var t    = pair[0];
                    var freq = pair[1];
                    var osc  = ctx.createOscillator();
                    var gain = ctx.createGain();
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.type            = 'sine';
                    osc.frequency.value = freq;
                    gain.gain.setValueAtTime(0.20, ctx.currentTime + t);
                    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + t + 0.45);
                    osc.start(ctx.currentTime + t);
                    osc.stop(ctx.currentTime + t + 0.5);
                });
            } catch(e) { /* AudioContext unavailable */ }
        },

        /* ── Browser notifications ── */
        sendNotification(title, body) {
            if (!this.notifEnabled) return;
            if (!('Notification' in window) || Notification.permission !== 'granted') return;
            try { new Notification(title, { body:body, icon:'/favicon.svg' }); } catch(e) {}
        },

        toggleNotif() {
            if (!('Notification' in window)) {
                alert('Your browser does not support desktop notifications.');
                return;
            }
            if (Notification.permission === 'denied') {
                alert('Notifications are blocked. Please enable them in your browser settings and refresh the page.');
                return;
            }
            if (Notification.permission === 'granted') {
                this.notifEnabled = !this.notifEnabled;
                this.save();
                return;
            }
            var self = this;
            Notification.requestPermission().then(function(p) {
                self.notifEnabled = p === 'granted';
                if (p === 'denied') alert('Permission denied. You can change this in your browser settings.');
                self.save();
            });
        },

        /* ── Utilities ── */
        formatMins(mins) {
            var h = Math.floor(mins / 60);
            var m = mins % 60;
            if (h > 0) return h + 'h ' + (m > 0 ? m + 'm' : '');
            return m + 'm';
        },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\study-timer.blade.php ENDPATH**/ ?>