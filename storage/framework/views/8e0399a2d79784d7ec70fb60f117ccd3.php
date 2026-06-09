<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ── Character highlight classes ── */
.char-correct  { color: #059669; }
.char-incorrect{ color: #fff; background: #ef4444; border-radius: 2px; }
.char-untyped  { color: #9ca3af; }
.char-cursor   { color: #9ca3af; border-left: 2px solid #4f46e5;
                 animation: blink 1s step-end infinite; }

@keyframes blink {
    0%, 100% { border-left-color: #4f46e5; }
    50%       { border-left-color: transparent; }
}

/* Passage container */
.passage-box {
    font-family: 'Courier New', Courier, monospace;
    font-size: 1.1rem;
    line-height: 1.9;
    letter-spacing: 0.02em;
    white-space: pre-wrap;
    word-break: break-word;
    cursor: text;
    min-height: 130px;
}

/* Countdown ring */
.timer-ring { transition: stroke-dashoffset 1s linear; }

/* Smooth number transitions */
.stat-num { transition: all 0.2s ease; }

/* Fade-in for result */
.result-animate { animation: slideUp 0.4s ease-out; }
@keyframes slideUp {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}

@media print { nav, header, footer { display: none !important; } }
</style>

<div class="min-h-screen bg-gray-50"
     x-data="typingTest()"
     x-init="init()">

    
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                        <?php echo e($tool->icon); ?> <?php echo e($tool->name); ?>

                    </h1>
                    <p class="text-gray-500 mt-1"><?php echo e($tool->short_description); ?></p>
                </div>
                <button type="button" @click="restart()"
                        class="btn btn-secondary no-print self-start sm:self-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Restart
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-6 space-y-4">

        
        <div class="card p-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider mr-1">Duration</span>
                    <template x-for="t in timeModes" :key="t">
                        <button type="button"
                                @click="setMode(t)"
                                :disabled="status === 'running'"
                                class="px-4 py-2 rounded-xl text-sm font-semibold border-2 transition-all disabled:cursor-not-allowed"
                                :class="selectedTime === t
                                    ? 'border-brand-500 bg-brand-50 text-brand-700'
                                    : 'border-gray-200 bg-white text-gray-500 hover:border-gray-300 disabled:opacity-50'">
                            <span x-text="t + 's'"></span>
                        </button>
                    </template>
                </div>

                
                <div class="flex items-center gap-3">
                    
                    <div class="relative w-14 h-14">
                        <svg class="w-14 h-14 -rotate-90" viewBox="0 0 56 56">
                            <circle cx="28" cy="28" r="24"
                                    fill="none" stroke="#e5e7eb" stroke-width="4"/>
                            <circle cx="28" cy="28" r="24"
                                    fill="none" stroke-width="4"
                                    stroke-linecap="round"
                                    :stroke="timerPct > 40 ? '#4f46e5' : timerPct > 20 ? '#f59e0b' : '#ef4444'"
                                    :stroke-dasharray="150.8"
                                    :stroke-dashoffset="150.8 * (1 - timerPct / 100)"
                                    class="timer-ring"/>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-sm font-bold"
                                  :class="timerPct > 40 ? 'text-brand-700' : timerPct > 20 ? 'text-amber-600' : 'text-red-600'"
                                  x-text="timeLeft"></span>
                        </div>
                    </div>

                    
                    <div>
                        <div x-show="status === 'idle'" class="badge badge-gray">Waiting…</div>
                        <div x-show="status === 'running'" class="badge badge-primary">
                            <span class="w-1.5 h-1.5 bg-brand-500 rounded-full animate-pulse"></span>
                            Running
                        </div>
                        <div x-show="status === 'done'" class="badge badge-success">Complete!</div>
                        <p class="text-xs text-gray-400 mt-0.5" x-show="status === 'idle'">Start typing to begin</p>
                        <p class="text-xs text-gray-400 mt-0.5" x-show="status === 'running'">
                            <span x-text="elapsedSeconds + 's elapsed'"></span>
                        </p>
                    </div>
                </div>
            </div>

            
            <div class="mt-3 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all duration-1000"
                     :class="timerPct > 40 ? 'bg-brand-500' : timerPct > 20 ? 'bg-amber-400' : 'bg-red-500'"
                     :style="'width:' + timerPct + '%'"></div>
            </div>
        </div>

        
        <div class="card p-5 sm:p-6"
             @click="$refs.inputArea.focus()">
            <div class="passage-box select-none" id="passageDisplay">
                <template x-for="(ch, idx) in passageChars" :key="idx">
                    <span :class="charClass(idx)"
                          x-text="ch === ' ' ? ' ' : ch"></span>
                </template>
            </div>
        </div>

        
        <div class="card p-4">
            <label class="form-label">
                <span x-show="status !== 'done'">Your input</span>
                <span x-show="status === 'done'" class="text-gray-400">Test ended — see results below</span>
            </label>
            <textarea
                x-ref="inputArea"
                x-model="typed"
                @input="onType()"
                @paste.prevent
                :disabled="status === 'done'"
                autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
                placeholder="Click here and start typing the passage above…"
                rows="3"
                class="form-input font-mono resize-none text-base leading-relaxed"
                :class="status === 'done' ? 'bg-gray-50 text-gray-400 cursor-not-allowed' : ''"
            ></textarea>
            <div class="flex items-center justify-between mt-2">
                <p class="text-xs text-gray-400">Paste is disabled — type the text manually.</p>
                
                <div class="flex items-center gap-1.5 text-xs text-gray-400" x-show="typed.length > 0">
                    <div class="w-20 h-1 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-brand-400 rounded-full transition-all"
                             :style="'width:' + progressPct + '%'"></div>
                    </div>
                    <span x-text="progressPct + '%'"></span>
                </div>
            </div>
        </div>

        
        <div class="grid grid-cols-3 sm:grid-cols-6 gap-3">
            <template x-for="stat in liveStats" :key="stat.label">
                <div class="card p-3 sm:p-4 text-center">
                    <p class="stat-num text-xl sm:text-2xl font-bold"
                       :class="stat.color"
                       x-text="stat.value"></p>
                    <p class="text-xs text-gray-400 mt-0.5 leading-tight" x-text="stat.label"></p>
                </div>
            </template>
        </div>

        
        <div x-show="status === 'done'" x-transition class="result-animate">
            <div class="card overflow-hidden">
                
                <div class="px-6 py-5 text-center"
                     style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%)">
                    <p class="text-4xl mb-1">
                        <span x-text="wpm >= 60 ? '🏆' : wpm >= 40 ? '🎉' : wpm >= 20 ? '👍' : '💪'"></span>
                    </p>
                    <h2 class="text-xl font-bold text-white">Test Complete!</h2>
                    <p class="text-indigo-200 text-sm mt-0.5" x-text="resultLabel"></p>
                </div>

                
                <div class="grid grid-cols-3 divide-x divide-gray-100 border-b border-gray-100">
                    <div class="p-5 text-center">
                        <p class="text-4xl font-extrabold text-brand-600 stat-num" x-text="wpm"></p>
                        <p class="text-xs font-semibold text-gray-400 mt-1 uppercase tracking-wider">WPM</p>
                    </div>
                    <div class="p-5 text-center">
                        <p class="text-4xl font-extrabold text-indigo-600 stat-num" x-text="cpm"></p>
                        <p class="text-xs font-semibold text-gray-400 mt-1 uppercase tracking-wider">CPM</p>
                    </div>
                    <div class="p-5 text-center">
                        <p class="text-4xl font-extrabold stat-num"
                           :class="accuracy >= 95 ? 'text-emerald-600' : accuracy >= 80 ? 'text-amber-500' : 'text-red-500'"
                           x-text="accuracy + '%'"></p>
                        <p class="text-xs font-semibold text-gray-400 mt-1 uppercase tracking-wider">Accuracy</p>
                    </div>
                </div>

                
                <div class="p-5 sm:p-6">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Breakdown</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
                        <div class="bg-emerald-50 rounded-xl p-3 text-center">
                            <p class="text-xl font-bold text-emerald-600" x-text="correctChars"></p>
                            <p class="text-xs text-emerald-600 mt-0.5">Correct chars</p>
                        </div>
                        <div class="bg-red-50 rounded-xl p-3 text-center">
                            <p class="text-xl font-bold text-red-500" x-text="incorrectChars"></p>
                            <p class="text-xs text-red-500 mt-0.5">Incorrect chars</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3 text-center">
                            <p class="text-xl font-bold text-gray-700" x-text="totalTyped"></p>
                            <p class="text-xs text-gray-500 mt-0.5">Total typed</p>
                        </div>
                        <div class="bg-brand-50 rounded-xl p-3 text-center">
                            <p class="text-xl font-bold text-brand-600" x-text="selectedTime + 's'"></p>
                            <p class="text-xs text-brand-600 mt-0.5">Duration</p>
                        </div>
                    </div>

                    
                    <div class="mb-5">
                        <div class="flex justify-between text-xs text-gray-500 mb-1.5">
                            <span>Accuracy</span>
                            <span x-text="accuracy + '%'"></span>
                        </div>
                        <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-700"
                                 :class="accuracy >= 95 ? 'bg-emerald-500' : accuracy >= 80 ? 'bg-amber-400' : 'bg-red-500'"
                                 :style="'width:' + accuracy + '%'"></div>
                        </div>
                    </div>

                    
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="button" @click="restart()"
                                class="btn btn-primary flex-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Try Again
                        </button>
                        <button type="button" @click="restartSame()"
                                class="btn btn-secondary flex-1">
                            🔁 Same Passage
                        </button>
                    </div>
                </div>
            </div>
        </div>

        
        <div x-show="status === 'idle'" x-transition class="card p-4 bg-gradient-to-br from-brand-50 to-indigo-50 border-brand-100">
            <h4 class="text-xs font-semibold text-brand-700 mb-2">💡 How to use</h4>
            <ul class="space-y-1 text-xs text-brand-600">
                <li>• Choose a duration (30s / 60s / 120s) above</li>
                <li>• Click the input box and start typing the passage</li>
                <li>• The timer starts automatically on your first keystroke</li>
                <li>• <span class="text-emerald-700 font-semibold">Green</span> = correct &nbsp;·&nbsp; <span class="text-red-600 font-semibold">Red</span> = incorrect</li>
                <li>• Your WPM, accuracy, and character stats update in real time</li>
            </ul>
        </div>

        <?php if($relatedTools->count()): ?>
        <div class="mt-2">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Related Tools</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
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
/* ══════════════════════════════════════════════════
   PASSAGE LIBRARY  – 12 diverse paragraphs (~80 words each)
   ══════════════════════════════════════════════════ */
var PASSAGES = [
    "The quick brown fox jumps over the lazy dog near the riverbank. This classic sentence has been used by typists for generations to warm up their fingers and test keyboard layouts. Regular practice is the single most effective way to improve your typing speed without sacrificing accuracy. Even fifteen minutes of focused daily practice can yield dramatic results within just a few weeks.",

    "Programming is the art of breaking complex problems into a series of small, logical steps that a computer can execute. It demands patience, creativity, and an eye for detail. Every experienced software developer once struggled with their first lines of code. The key is persistence and a willingness to learn from every mistake you make along the way.",

    "The internet has transformed how humans communicate, shop, learn, and entertain themselves. Within milliseconds, a message can travel from one side of the planet to the other. Billions of people are now connected through social networks, messaging apps, and video platforms. This unprecedented connectivity has unlocked enormous opportunities while also raising serious questions about privacy and digital wellbeing.",

    "Reading books remains one of the most rewarding habits a person can develop. It expands vocabulary, sharpens focus, and transports you to worlds that could never exist in real life. Whether you prefer gripping thrillers, sweeping historical epics, or insightful non-fiction, even thirty minutes of reading each day can profoundly improve your knowledge and mental clarity.",

    "Healthy habits are built through small, consistent choices made day after day. Drinking enough water, sleeping seven to nine hours each night, eating balanced meals, and moving your body regularly are the foundations of lasting wellbeing. No single habit transforms your health overnight, but the compound effect of many small improvements becomes remarkable over months and years.",

    "The universe is approximately thirteen point eight billion years old, a span of time almost impossible for the human mind to grasp. Our solar system formed about four and a half billion years ago from a vast cloud of gas and dust. On the pale blue dot we call Earth, life has evolved into millions of extraordinary forms over deep geological time.",

    "Artificial intelligence is rapidly reshaping industries ranging from healthcare to transportation to creative arts. Machines can now recognize faces, translate languages, compose music, and diagnose diseases with remarkable precision. As these capabilities grow, thoughtful conversations about ethics, fairness, and human oversight become more important than ever before in our shared technological future.",

    "Music is a universal language that transcends borders and connects people across vastly different cultures and generations. A melody can evoke a distant memory, comfort a grieving heart, or ignite the energy of an entire stadium. Learning to play an instrument trains patience, coordination, and creativity simultaneously, making it one of the most enriching skills a person can acquire.",

    "Traveling to new places changes the way you see the world and yourself. Immersing yourself in an unfamiliar culture, tasting new foods, and navigating streets where you cannot read the signs builds resilience and empathy. Even short journeys to nearby cities can reveal perspectives and ways of living that your everyday environment would never offer.",

    "Climate change represents one of the most urgent and complex challenges our civilization has ever faced. Rising average temperatures are causing glaciers to retreat, sea levels to rise, and weather patterns to become more extreme and unpredictable. Addressing this crisis requires coordinated action from governments, corporations, and individuals across every country on Earth without further delay.",

    "Cooking at home is both a practical skill and a deeply satisfying creative outlet. Understanding how heat changes the texture and flavor of ingredients, how acids brighten a dish, and how fat carries aroma gives you the power to create genuinely delicious meals. With patience and curiosity, anyone can learn to cook food that rivals their favorite restaurant.",

    "Physical exercise is one of the most powerful tools available for maintaining good mental and physical health. Regular movement strengthens the cardiovascular system, improves posture, boosts energy, and releases endorphins that reduce stress and anxiety. The best exercise is simply the one you enjoy enough to do consistently, whether that is cycling, dancing, swimming, or a brisk daily walk.",
];

/* ══════════════════════════════════════════════════
   ALPINE COMPONENT
   ══════════════════════════════════════════════════ */
function typingTest() {
    return {

        /* ── Config ── */
        timeModes:    [30, 60, 120],
        selectedTime: 60,

        /* ── State ── */
        status:   'idle',   /* 'idle' | 'running' | 'done' */
        timeLeft: 60,
        typed:    '',
        passage:  '',
        _savedPassage: '',
        _interval: null,

        /* ── Init ── */
        init() {
            this.selectedTime = 60;
            this.timeLeft     = 60;
            this._pickPassage();
        },

        _pickPassage() {
            var idx = Math.floor(Math.random() * PASSAGES.length);
            this.passage = this._savedPassage = PASSAGES[idx];
        },

        /* ── Timer mode ── */
        setMode(t) {
            if (this.status === 'running') return;
            this.selectedTime = t;
            this.timeLeft     = t;
            /* restart silently */
            this._clearTimer();
            this.typed  = '';
            this.status = 'idle';
        },

        /* ── Timer ── */
        _startTimer() {
            var self = this;
            this._interval = setInterval(function() {
                self.timeLeft = Math.max(0, self.timeLeft - 1);
                if (self.timeLeft === 0) {
                    self._clearTimer();
                    self.status = 'done';
                }
            }, 1000);
        },

        _clearTimer() {
            if (this._interval) { clearInterval(this._interval); this._interval = null; }
        },

        /* ── On type ── */
        onType() {
            /* Guard: test already over */
            if (this.status === 'done') { this.typed = this.typed; return; }

            /* Clamp input to passage length */
            if (this.typed.length > this.passage.length) {
                this.typed = this.typed.slice(0, this.passage.length);
            }

            /* Start timer on first character */
            if (this.status === 'idle' && this.typed.length > 0) {
                this.status = 'running';
                this._startTimer();
            }

            /* Auto-end when passage is fully typed */
            if (this.status === 'running' && this.typed.length >= this.passage.length) {
                this._clearTimer();
                this.status = 'done';
            }
        },

        /* ── Restart ── */
        restart() {
            this._clearTimer();
            this.status       = 'idle';
            this.typed        = '';
            this.timeLeft     = this.selectedTime;
            this._pickPassage();
            var self = this;
            this.$nextTick(function() { self.$refs.inputArea && self.$refs.inputArea.focus(); });
        },

        restartSame() {
            this._clearTimer();
            this.status   = 'idle';
            this.typed    = '';
            this.timeLeft = this.selectedTime;
            this.passage  = this._savedPassage;
            var self = this;
            this.$nextTick(function() { self.$refs.inputArea && self.$refs.inputArea.focus(); });
        },

        /* ═══════════ Computed getters ═══════════ */

        get passageChars() { return this.passage.split(''); },

        get elapsedSeconds() {
            return Math.max(1, this.selectedTime - this.timeLeft);
        },

        get correctChars() {
            var n = 0;
            for (var i = 0; i < this.typed.length; i++) {
                if (this.typed[i] === this.passage[i]) n++;
            }
            return n;
        },

        get incorrectChars() { return Math.max(0, this.typed.length - this.correctChars); },
        get totalTyped()     { return this.typed.length; },

        get wpm() {
            if (this.status === 'idle' || this.totalTyped === 0) return 0;
            return Math.max(0, Math.round((this.correctChars / 5) / (this.elapsedSeconds / 60)));
        },

        get cpm() {
            if (this.status === 'idle' || this.totalTyped === 0) return 0;
            return Math.max(0, Math.round(this.correctChars / (this.elapsedSeconds / 60)));
        },

        get accuracy() {
            if (this.typed.length === 0) return 100;
            return Math.round((this.correctChars / this.typed.length) * 100);
        },

        get progressPct() {
            if (!this.passage.length) return 0;
            return Math.min(100, Math.round((this.typed.length / this.passage.length) * 100));
        },

        get timerPct() {
            if (!this.selectedTime) return 100;
            return Math.round((this.timeLeft / this.selectedTime) * 100);
        },

        /* Live stat cards array */
        get liveStats() {
            return [
                { label: 'WPM',          value: this.wpm,            color: 'text-brand-600'   },
                { label: 'CPM',          value: this.cpm,            color: 'text-indigo-600'  },
                { label: 'Accuracy',     value: this.accuracy + '%', color: this.accuracy >= 95 ? 'text-emerald-600' : this.accuracy >= 80 ? 'text-amber-500' : 'text-red-500' },
                { label: 'Correct',      value: this.correctChars,   color: 'text-emerald-600' },
                { label: 'Incorrect',    value: this.incorrectChars, color: this.incorrectChars > 0 ? 'text-red-500' : 'text-gray-400' },
                { label: 'Total Typed',  value: this.totalTyped,     color: 'text-gray-700'    },
            ];
        },

        get resultLabel() {
            var w = this.wpm;
            if (w >= 80) return 'Outstanding speed — you\'re in the top tier!';
            if (w >= 60) return 'Great speed — above average!';
            if (w >= 40) return 'Good work — keep practicing!';
            if (w >= 20) return 'Nice start — you\'ll improve fast!';
            return 'Every expert was once a beginner. Keep going!';
        },

        /* ── Character class ── */
        charClass(idx) {
            if (idx < this.typed.length) {
                return this.typed[idx] === this.passage[idx] ? 'char-correct' : 'char-incorrect';
            }
            if (idx === this.typed.length && this.status !== 'done') return 'char-cursor';
            return 'char-untyped';
        },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\typing-speed-test.blade.php ENDPATH**/ ?>