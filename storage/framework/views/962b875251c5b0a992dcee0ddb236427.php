<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ── Result text box ── */
.result-box {
    background: #f8faff;
    border: 2px solid #e0e7ff;
    border-radius: 1rem;
    padding: 1.25rem 1.5rem;
    font-family: 'Inter', ui-sans-serif, sans-serif;
    font-size: .9rem;
    line-height: 1.75;
    color: #1e1b4b;
    word-break: break-word;
    white-space: pre-wrap;
    min-height: 80px;
}
.result-box.multi { border-left: 4px solid #4f46e5; }

/* ── Scramble-in animation ── */
@keyframes scrambleIn {
    from { opacity: 0; transform: translateY(6px); }
    to   { opacity: 1; transform: translateY(0); }
}
.scramble-in { animation: scrambleIn .25s ease both; }

/* ── Toggle switch ── */
.toggle-track {
    position: relative; display: inline-flex;
    width: 2.75rem; height: 1.5rem;
    border-radius: 9999px;
    transition: background .2s;
    flex-shrink: 0;
    cursor: pointer;
}
.toggle-thumb {
    position: absolute; top: .2rem; left: .2rem;
    width: 1.1rem; height: 1.1rem;
    border-radius: 50%; background: white;
    box-shadow: 0 1px 3px rgba(0,0,0,.2);
    transition: transform .2s;
}
</style>

<div class="min-h-screen bg-gray-50"
     x-data="wordScramble()"
     x-init="init()">

    
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 py-10">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                <?php echo e($tool->icon); ?> <?php echo e($tool->name); ?>

            </h1>
            <p class="text-gray-500 mt-2"><?php echo e($tool->short_description); ?></p>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8 space-y-5">

        
        <div class="card p-6 space-y-5">

            
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="form-label mb-0">Text or Words to Scramble</label>
                    <span class="text-xs text-gray-400">
                        <span x-text="wordCount"></span> words ·
                        <span x-text="charCount"></span> chars
                    </span>
                </div>
                <textarea
                    x-model="inputText"
                    @input="error = ''; results = []"
                    rows="5"
                    placeholder="Type or paste your text here, e.g.: The quick brown fox jumps over the lazy dog"
                    maxlength="5000"
                    class="form-input resize-none leading-relaxed"
                    :class="error ? 'border-red-400 focus:border-red-400' : ''">
                </textarea>
                <p x-show="error" x-transition class="form-error mt-1" x-text="error"></p>
            </div>

            
            <div>
                <p class="form-label">Quick Load</p>
                <div class="flex flex-wrap gap-2">
                    <template x-for="s in samples" :key="s.label">
                        <button type="button"
                                @click="loadSample(s.words)"
                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl border-2 border-gray-200 bg-white text-sm font-medium text-gray-600 transition-all hover:border-gray-300 hover:bg-gray-50">
                            <span x-text="s.icon"></span>
                            <span x-text="s.label"></span>
                        </button>
                    </template>
                </div>
            </div>

            <hr class="border-gray-100">

            
            <div>
                <label class="form-label">Scramble Mode</label>
                <div class="flex flex-wrap gap-2">
                    <template x-for="m in modes" :key="m.value">
                        <button type="button"
                                @click="mode = m.value; results = []"
                                class="flex items-center gap-1.5 px-4 py-2 rounded-xl border-2 text-sm font-medium transition-all"
                                :class="mode === m.value
                                    ? 'border-brand-500 bg-brand-50 text-brand-700'
                                    : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'">
                            <span x-text="m.icon"></span>
                            <span x-text="m.label"></span>
                        </button>
                    </template>
                </div>
                <p class="form-help mt-1" x-text="modeHint"></p>
            </div>

            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                
                <div>
                    <label class="form-label">Letter Case</label>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="c in caseOptions" :key="c.value">
                            <button type="button"
                                    @click="caseMode = c.value; results = []"
                                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl border-2 text-sm font-medium transition-all"
                                    :class="caseMode === c.value
                                        ? 'border-brand-500 bg-brand-50 text-brand-700'
                                        : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'">
                                <span x-text="c.icon"></span>
                                <span x-text="c.label"></span>
                            </button>
                        </template>
                    </div>
                </div>

                
                <div>
                    <label class="form-label">
                        Versions to Generate
                        <span class="font-normal text-gray-400">(scramble N times)</span>
                    </label>
                    <div class="flex gap-2">
                        <template x-for="v in [1,2,3,5]" :key="v">
                            <button type="button"
                                    @click="versions = v; results = []"
                                    class="flex-1 py-2 rounded-xl border-2 text-sm font-semibold transition-all"
                                    :class="versions === v
                                        ? 'border-brand-500 bg-brand-50 text-brand-700'
                                        : 'border-gray-200 bg-white text-gray-500 hover:border-gray-300'"
                                    x-text="v">
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            
            <div class="flex flex-wrap gap-x-6 gap-y-3">

                
                <label class="flex items-center gap-3 cursor-pointer select-none">
                    <span class="toggle-track"
                          :style="skipShort ? 'background:#4f46e5' : 'background:#d1d5db'"
                          @click="skipShort = !skipShort; results = []">
                        <span class="toggle-thumb"
                              :style="skipShort ? 'transform:translateX(1.25rem)' : ''"></span>
                    </span>
                    <span class="text-sm font-medium text-gray-700">Skip words ≤ 2 letters</span>
                    <span class="text-xs text-gray-400 hidden sm:inline">(e.g. "a", "is", "to")</span>
                </label>

                
                <label class="flex items-center gap-3 cursor-pointer select-none">
                    <span class="toggle-track"
                          :style="avoidSame ? 'background:#4f46e5' : 'background:#d1d5db'"
                          @click="avoidSame = !avoidSame; results = []">
                        <span class="toggle-thumb"
                              :style="avoidSame ? 'transform:translateX(1.25rem)' : ''"></span>
                    </span>
                    <span class="text-sm font-medium text-gray-700">Avoid identical output</span>
                    <span class="text-xs text-gray-400 hidden sm:inline">(retry if unchanged)</span>
                </label>
            </div>

            
            <div class="flex gap-3 pt-1">
                <button type="button"
                        @click="scramble()"
                        class="btn btn-primary btn-lg flex-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    🔀 Scramble!
                </button>
                <button type="button"
                        @click="clear()"
                        :disabled="!inputText && !results.length"
                        class="btn btn-secondary btn-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Clear
                </button>
            </div>
        </div>

        
        <div x-show="results.length > 0"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="card p-6">

            
            <div class="flex items-center justify-between mb-4 flex-wrap gap-3">
                <div>
                    <h3 class="font-semibold text-gray-900">
                        Scrambled Output
                        <span class="ml-1.5 badge badge-primary"
                              x-text="results.length === 1 ? '1 version' : results.length + ' versions'">
                        </span>
                    </h3>
                    
                    <p class="text-xs text-gray-400 mt-0.5">
                        <span x-text="inWordCount + ' words in'"></span> ·
                        <span x-text="scrambledCount + ' scrambled'"></span>
                        <template x-if="unchangedCount > 0">
                            <span> · <span x-text="unchangedCount + ' unchanged'"></span></span>
                        </template>
                    </p>
                </div>
                <div class="flex gap-2">
                    <button type="button"
                            @click="scrambleAgain()"
                            class="btn btn-secondary btn-sm">
                        🔀 Again
                    </button>
                    <button type="button"
                            @click="copyAll()"
                            class="btn btn-secondary btn-sm"
                            :class="copiedAll ? 'text-emerald-600' : ''">
                        <svg x-show="!copiedAll" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <svg x-show="copiedAll" class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span x-text="copiedAll ? 'Copied!' : 'Copy All'"></span>
                    </button>
                </div>
            </div>

            
            <div class="space-y-3">
                <template x-for="(res, idx) in results" :key="res.id">
                    <div class="scramble-in">
                        <div class="flex items-start gap-3">

                            
                            <div x-show="results.length > 1"
                                 class="shrink-0 mt-1 w-6 h-6 rounded-lg bg-brand-100 text-brand-700 text-xs font-black flex items-center justify-center"
                                 x-text="idx + 1">
                            </div>

                            
                            <div class="flex-1 min-w-0">
                                <div class="result-box" :class="results.length > 1 ? 'multi' : ''"
                                     x-text="res.text"></div>
                            </div>

                            
                            <button type="button"
                                    @click="copyResult(idx)"
                                    class="shrink-0 mt-1 w-9 h-9 flex items-center justify-center rounded-xl border-2 border-gray-200 text-gray-400 hover:text-brand-600 hover:border-brand-300 transition-all"
                                    :class="copiedMap[idx] ? 'border-emerald-300 text-emerald-500' : ''"
                                    :title="copiedMap[idx] ? 'Copied!' : 'Copy'">
                                <svg x-show="!copiedMap[idx]" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <svg x-show="copiedMap[idx]" class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            
            <div x-show="results.length === 1" class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Original</p>
                <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-500 leading-relaxed break-words"
                     x-text="inputText.trim()">
                </div>
            </div>
        </div>

        
        <div x-show="results.length === 0" class="card p-5">
            <h3 class="font-semibold text-gray-800 mb-3">Scramble Modes Explained</h3>
            <div class="grid sm:grid-cols-3 gap-3">
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-sm font-semibold text-gray-700 mb-1">🔡 Per Word</p>
                    <p class="text-xs text-gray-500">Each word's letters are independently shuffled. Spaces and punctuation stay in place.</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-sm font-semibold text-gray-700 mb-1">🌀 Full Text</p>
                    <p class="text-xs text-gray-500">All letters across the entire text are pooled and reshuffled. Non-letters stay put.</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-sm font-semibold text-gray-700 mb-1">🔒 First & Last</p>
                    <p class="text-xs text-gray-500">First and last letters of each word are fixed. Only the middle letters shuffle.</p>
                </div>
            </div>
        </div>

        
        <?php if($relatedTools->count()): ?>
        <div>
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Related Tools</h3>
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
/* ════════════════════════════════════════════════
   WORD SCRAMBLE — Alpine.js component
════════════════════════════════════════════════ */
function wordScramble() {
    return {

        /* ── Input ── */
        inputText: '',

        /* ── Settings ── */
        mode:       'perword',   /* 'perword' | 'fulltext' | 'keepends' */
        caseMode:   'original',  /* 'original' | 'upper' | 'lower' */
        versions:   1,
        skipShort:  true,
        avoidSame:  true,

        /* ── Results ── */
        results:    [],          /* [{id, text}] */
        copiedMap:  {},          /* {index: bool} */
        copiedAll:  false,
        _cpTimer:   null,

        /* ── Validation ── */
        error: '',

        /* ── Stats (populated after scramble) ── */
        inWordCount:    0,
        scrambledCount: 0,
        unchangedCount: 0,

        /* ── Static config ── */
        samples: [
            { icon:'🐾', label:'Animals',   words:'Lion Tiger Elephant Giraffe Dolphin Penguin Cheetah Gorilla Crocodile Flamingo' },
            { icon:'🍎', label:'Fruits',    words:'Apple Banana Cherry Mango Peach Strawberry Blueberry Pineapple Watermelon Grape' },
            { icon:'🌍', label:'Countries', words:'France Germany Italy Japan Brazil Canada Australia Argentina Netherlands Portugal' },
            { icon:'🎬', label:'Movies',    words:'Inception Titanic Avatar Interstellar Gladiator Parasite Joker Gravity Dunkirk Braveheart' },
            { icon:'💡', label:'Random',    words:'Mountain Thunder Eclipse Symphony Horizon Galaxy Labyrinth Catalyst Serenity Phantom' },
        ],

        modes: [
            { value:'perword',  icon:'🔡', label:'Per Word' },
            { value:'fulltext', icon:'🌀', label:'Full Text' },
            { value:'keepends', icon:'🔒', label:'First & Last' },
        ],

        caseOptions: [
            { value:'original', icon:'Aa', label:'Original' },
            { value:'lower',    icon:'aa', label:'Lowercase' },
            { value:'upper',    icon:'AA', label:'Uppercase' },
        ],

        /* ══════════════════════════════════
           COMPUTED GETTERS
        ══════════════════════════════════ */

        get charCount() {
            return this.inputText.length;
        },

        get wordCount() {
            var m = this.inputText.match(/[a-zA-Z]+/g);
            return m ? m.length : 0;
        },

        get modeHint() {
            var hints = {
                perword:  'Each word is shuffled independently. Spaces and punctuation stay in position.',
                fulltext: 'All letters from the whole text are pooled and reshuffled together.',
                keepends: 'First and last letters of every word are locked. Only middle letters shuffle.',
            };
            return hints[this.mode] || '';
        },

        /* ══════════════════════════════════
           INIT
        ══════════════════════════════════ */

        init() { /* nothing to pre-load */ },

        /* ══════════════════════════════════
           SAMPLE LOADER
        ══════════════════════════════════ */

        loadSample(words) {
            this.inputText = words;
            this.results   = [];
            this.error     = '';
        },

        /* ══════════════════════════════════
           SCRAMBLE ENTRY POINT
        ══════════════════════════════════ */

        scramble() {
            var text = this.inputText;

            /* Validation */
            if (!text.trim()) {
                this.error = 'Please enter some text or words to scramble.';
                return;
            }
            if (text.trim().replace(/[^a-zA-Z]/g, '').length < 2) {
                this.error = 'Your text needs at least 2 letters to scramble.';
                return;
            }

            this.error          = '';
            this.results        = [];
            this.inWordCount    = 0;
            this.scrambledCount = 0;
            this.unchangedCount = 0;

            /* Count input words once */
            var wm = text.match(/[a-zA-Z]+/g);
            this.inWordCount = wm ? wm.length : 0;

            /* Generate requested versions */
            for (var v = 0; v < this.versions; v++) {
                var out = this._doScramble(text);
                this.results.push({ id: v, text: out });
            }

            /* Count changed / unchanged (based on version 0) */
            this._calcStats(text, this.results[0].text);
        },

        /* ══════════════════════════════════
           SCRAMBLE IMPLEMENTATION
        ══════════════════════════════════ */

        _doScramble(text) {
            if (this.mode === 'fulltext') {
                return this._scrambleFullText(text);
            }
            /* perword or keepends — split by letter-runs vs non-letter-runs */
            return this._scrambleByWord(text);
        },

        /* Shuffle ALL letters across the whole text, non-letters stay in place */
        _scrambleFullText(text) {
            var chars        = text.split('');
            var letterIdxs   = [];
            var letters      = [];

            for (var i = 0; i < chars.length; i++) {
                if (/[a-zA-Z]/.test(chars[i])) {
                    letterIdxs.push(i);
                    letters.push(chars[i]);
                }
            }

            this._shuffle(letters);

            for (var k = 0; k < letterIdxs.length; k++) {
                chars[letterIdxs[k]] = letters[k];
            }

            var result = chars.join('');
            return this._applyGlobalCase(result);
        },

        /* Split text into letter-word tokens and separator tokens, scramble each word */
        _scrambleByWord(text) {
            var self   = this;
            /* Tokenise: letter sequences and everything else */
            var tokens = [];
            var re     = /([a-zA-Z]+)|([^a-zA-Z]+)/g;
            var m;
            while ((m = re.exec(text)) !== null) {
                tokens.push(m[1] !== undefined
                    ? { word: true,  val: m[1] }
                    : { word: false, val: m[2] });
            }

            return tokens.map(function(tok) {
                return tok.word ? self._scrambleWord(tok.val) : tok.val;
            }).join('');
        },

        /* Scramble a single word token */
        _scrambleWord(word) {
            /* Skip very short words if toggle on */
            if (this.skipShort && word.length <= 2) {
                return this._applyWordCase(word);
            }

            var letters = word.split('');

            if (this.mode === 'keepends' && word.length >= 4) {
                /* Lock first and last, shuffle middle */
                var first  = letters[0];
                var last   = letters[letters.length - 1];
                var middle = letters.slice(1, -1);

                this._shuffle(middle);

                /* Avoid identical middle (up to 5 attempts) */
                if (this.avoidSame) {
                    var origMid = word.slice(1, -1).toLowerCase();
                    var attempts = 0;
                    while (middle.join('').toLowerCase() === origMid && middle.length > 1 && attempts < 6) {
                        this._shuffle(middle);
                        attempts++;
                    }
                }

                letters = [first].concat(middle).concat([last]);

            } else {
                /* Full shuffle */
                this._shuffle(letters);

                /* Avoid identical output (up to 6 attempts) */
                if (this.avoidSame && word.length > 2) {
                    var orig     = word.toLowerCase();
                    var attempts = 0;
                    while (letters.join('').toLowerCase() === orig && attempts < 6) {
                        this._shuffle(letters);
                        attempts++;
                    }
                }
            }

            return this._applyWordCase(letters.join(''));
        },

        /* ══════════════════════════════════
           CASE HELPERS
        ══════════════════════════════════ */

        _applyWordCase(word) {
            if (this.caseMode === 'upper') return word.toUpperCase();
            if (this.caseMode === 'lower') return word.toLowerCase();
            return word; /* original — case travels with letters */
        },

        _applyGlobalCase(text) {
            if (this.caseMode === 'upper') return text.toUpperCase();
            if (this.caseMode === 'lower') return text.toLowerCase();
            return text;
        },

        /* ══════════════════════════════════
           STATS
        ══════════════════════════════════ */

        _calcStats(original, scrambled) {
            var origWords = original.match(/[a-zA-Z]+/g)  || [];
            var scrWords  = scrambled.match(/[a-zA-Z]+/g) || [];
            var changed   = 0;
            var unchanged = 0;
            var len       = Math.min(origWords.length, scrWords.length);
            for (var i = 0; i < len; i++) {
                if (origWords[i].toLowerCase() === scrWords[i].toLowerCase()) {
                    unchanged++;
                } else {
                    changed++;
                }
            }
            this.scrambledCount = changed;
            this.unchangedCount = unchanged;
        },

        /* ══════════════════════════════════
           FISHER-YATES SHUFFLE
        ══════════════════════════════════ */

        _shuffle(arr) {
            for (var i = arr.length - 1; i > 0; i--) {
                var j = Math.floor(Math.random() * (i + 1));
                var t = arr[i]; arr[i] = arr[j]; arr[j] = t;
            }
        },

        /* ══════════════════════════════════
           CONTROLS
        ══════════════════════════════════ */

        scrambleAgain() {
            this.scramble();
        },

        clear() {
            this.inputText      = '';
            this.results        = [];
            this.error          = '';
            this.inWordCount    = 0;
            this.scrambledCount = 0;
            this.unchangedCount = 0;
        },

        /* ══════════════════════════════════
           CLIPBOARD
        ══════════════════════════════════ */

        copyResult(idx) {
            var self = this;
            var text = this.results[idx].text;
            clearTimeout(this._cpTimer);

            var done = function() {
                var m = {};
                m[idx] = true;
                self.copiedMap = Object.assign({}, self.copiedMap, m);
                self._cpTimer = setTimeout(function() {
                    var c = {};
                    c[idx] = false;
                    self.copiedMap = Object.assign({}, self.copiedMap, c);
                }, 2000);
            };

            navigator.clipboard.writeText(text).then(done).catch(function() {
                var ta = document.createElement('textarea');
                ta.value = text;
                ta.style.cssText = 'position:fixed;opacity:0;top:0;left:0';
                document.body.appendChild(ta);
                ta.select();
                try { document.execCommand('copy'); } catch(e) {}
                document.body.removeChild(ta);
                done();
            });
        },

        copyAll() {
            var self  = this;
            var multi = this.results.length > 1;
            var text  = this.results.map(function(r, i) {
                return multi ? ('— Version ' + (i + 1) + ' —\n' + r.text) : r.text;
            }).join('\n\n');

            var done = function() {
                self.copiedAll = true;
                setTimeout(function() { self.copiedAll = false; }, 2000);
            };

            navigator.clipboard.writeText(text).then(done).catch(function() {
                var ta = document.createElement('textarea');
                ta.value = text;
                ta.style.cssText = 'position:fixed;opacity:0;top:0;left:0';
                document.body.appendChild(ta);
                ta.select();
                try { document.execCommand('copy'); } catch(e) {}
                document.body.removeChild(ta);
                done();
            });
        },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\word-scramble.blade.php ENDPATH**/ ?>