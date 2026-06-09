<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ─────────────────────────────────────────
   CARD 3D FLIP
───────────────────────────────────────── */
.mc-card {
    perspective: 700px;
    cursor: pointer;
    aspect-ratio: 1 / 1;
    user-select: none;
}
.mc-card.is-idle { cursor: default; }

.mc-inner {
    position: relative;
    width: 100%; height: 100%;
    transform-style: preserve-3d;
    transition: transform .42s cubic-bezier(.4,0,.2,1);
    border-radius: .875rem;
}
.mc-card.is-flipped .mc-inner,
.mc-card.is-matched .mc-inner { transform: rotateY(180deg); }

.mc-face {
    position: absolute; inset: 0;
    border-radius: .875rem;
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
}

/* ── Back face (default) ── */
.mc-back {
    background:
        repeating-linear-gradient(-45deg, transparent, transparent 7px,
                                   rgba(255,255,255,.07) 7px, rgba(255,255,255,.07) 14px),
        linear-gradient(135deg, #4f46e5 0%, #6d28d9 100%);
    border: 2px solid #4338ca;
    box-shadow: inset 0 1px 3px rgba(255,255,255,.15);
}
.mc-back::before {
    content: '';
    position: absolute; inset: 6px;
    border: 2px solid rgba(255,255,255,.22);
    border-radius: 9px;
    pointer-events: none;
}
.mc-back::after {
    content: '?';
    font-size: 1.6rem;
    font-weight: 900;
    color: rgba(255,255,255,.22);
    letter-spacing: -.05em;
}

/* ── Front face (revealed) ── */
.mc-front {
    background: #fff;
    border: 2px solid #e5e7eb;
    transform: rotateY(180deg);
    font-size: clamp(1.5rem, 5.5vw, 2.4rem);
    line-height: 1;
    transition: background .2s, border-color .2s, box-shadow .2s;
}
.mc-card.is-matched .mc-front {
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    border-color: #86efac;
    box-shadow: 0 0 0 3px rgba(134,239,172,.25);
}

/* ── Match pop ── */
@keyframes matchPop {
    0%   { transform: rotateY(180deg) scale(1); }
    45%  { transform: rotateY(180deg) scale(1.16); }
    100% { transform: rotateY(180deg) scale(1); }
}
.mc-card.is-matched .mc-inner {
    animation: matchPop .3s cubic-bezier(.34,1.56,.64,1) .43s both;
}

/* ── Mismatch flash on front face ── */
@keyframes mismatchFlash {
    0%,100% { background: #fff; border-color: #e5e7eb; }
    40%     { background: #fee2e2; border-color: #fca5a5; }
}
.mc-card.is-mismatch .mc-front { animation: mismatchFlash .5s ease; }

/* ─────────────────────────────────────────
   GRID LAYOUTS (avoids Tailwind purge)
───────────────────────────────────────── */
.mm-grid       { display: grid; }
.mm-grid-easy  { grid-template-columns: repeat(4, 1fr); gap: 10px; }
.mm-grid-med   { grid-template-columns: repeat(4, 1fr); gap: 10px; }
.mm-grid-hard  { grid-template-columns: repeat(4, 1fr); gap: 8px; }
@media (min-width: 500px) {
    .mm-grid-hard { grid-template-columns: repeat(6, 1fr); }
}

/* ─────────────────────────────────────────
   OPTION BUTTONS (difficulty / theme)
───────────────────────────────────────── */
.opt-btn {
    display: flex; flex-direction: column; align-items: center;
    gap: 4px; padding: 14px 10px;
    border-radius: 14px; border: 2px solid #e5e7eb;
    background: #fff; cursor: pointer; transition: all .15s;
    font-weight: 600; font-size: .85rem; color: #4b5563;
    text-align: center;
}
.opt-btn:hover { border-color: #d1d5db; background: #f9fafb; }
.opt-btn.sel   { border-color: #4f46e5; background: #eef2ff; color: #3730a3; }

.theme-btn {
    display: flex; align-items: center; justify-content: center;
    gap: 6px; padding: 9px 12px;
    border-radius: 12px; border: 2px solid #e5e7eb;
    background: #fff; cursor: pointer; transition: all .15s;
    font-weight: 500; font-size: .82rem; color: #4b5563;
    white-space: nowrap;
}
.theme-btn:hover { border-color: #d1d5db; background: #f9fafb; }
.theme-btn.sel   { border-color: #4f46e5; background: #eef2ff; color: #3730a3; }

/* ─────────────────────────────────────────
   WON / ANIMATIONS
───────────────────────────────────────── */
@keyframes wonPop {
    0%   { opacity:0; transform:scale(.88) translateY(12px); }
    70%  { transform:scale(1.03) translateY(-2px); }
    100% { opacity:1; transform:scale(1) translateY(0); }
}
.won-pop { animation: wonPop .45s cubic-bezier(.34,1.36,.64,1) both; }

.star-on  { color:#f59e0b; }
.star-off { color:#e5e7eb; }

/* ─────────────────────────────────────────
   MISC
───────────────────────────────────────── */
@keyframes fadeSlideIn {
    from { opacity:0; transform:translateY(-4px); }
    to   { opacity:1; transform:translateY(0); }
}
.fade-in { animation: fadeSlideIn .2s ease both; }
</style>

<div class="min-h-screen bg-gray-50"
     x-data="memoryMatch()"
     x-init="init()">

    
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 py-10">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                <?php echo e($tool->icon); ?> <?php echo e($tool->name); ?>

            </h1>
            <p class="text-gray-500 mt-2"><?php echo e($tool->short_description); ?></p>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 py-8 space-y-4">

        
        <div x-show="phase === 'setup'"
             x-transition:enter="transition duration-200 ease-out"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition duration-150 ease-in"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="space-y-4">

            <div class="card p-6 space-y-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Game Settings</h2>
                    <p class="text-sm text-gray-400 mt-1">Choose your difficulty and theme, then start playing.</p>
                </div>

                
                <div>
                    <p class="form-label">Difficulty</p>
                    <div class="grid grid-cols-3 gap-3">
                        <template x-for="(cfg, key) in DIFFICULTIES" :key="key">
                            <button type="button"
                                    class="opt-btn"
                                    :class="difficulty === key ? 'sel' : ''"
                                    @click="difficulty = key">
                                <span class="text-2xl" x-text="cfg.icon"></span>
                                <span x-text="cfg.label"></span>
                                <span class="text-xs font-normal opacity-60"
                                      x-text="cfg.pairs + ' pairs'"></span>
                            </button>
                        </template>
                    </div>
                </div>

                
                <div>
                    <p class="form-label">Card Theme</p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        <template x-for="(t, key) in THEMES" :key="key">
                            <button type="button"
                                    class="theme-btn"
                                    :class="theme === key ? 'sel' : ''"
                                    @click="theme = key">
                                <span x-text="t.icon"></span>
                                <span x-text="t.label"></span>
                            </button>
                        </template>
                    </div>
                </div>

                
                <div class="flex flex-wrap gap-2 text-sm">
                    <span class="flex items-center gap-1.5 bg-gray-50 text-gray-500 rounded-xl px-3 py-2">
                        🃏 <span x-text="DIFFICULTIES[difficulty].pairs * 2 + ' cards'"></span>
                    </span>
                    <span class="flex items-center gap-1.5 bg-gray-50 text-gray-500 rounded-xl px-3 py-2">
                        🔗 <span x-text="DIFFICULTIES[difficulty].pairs + ' pairs'"></span>
                    </span>
                    <span class="flex items-center gap-1.5 bg-gray-50 text-gray-500 rounded-xl px-3 py-2">
                        📐 <span x-text="DIFFICULTIES[difficulty].gridDesc"></span>
                    </span>
                </div>

                
                <button type="button"
                        @click="startGame()"
                        class="btn btn-primary btn-lg w-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Start Game
                </button>
            </div>

            
            <div class="card p-5">
                <h3 class="font-semibold text-gray-800 mb-3">How to play</h3>
                <div class="grid sm:grid-cols-2 gap-2.5 text-sm text-gray-500">
                    <div class="flex gap-2.5">
                        <span class="text-indigo-400 font-bold shrink-0">1.</span>
                        <p>Click any face-down card to flip it and reveal the emoji.</p>
                    </div>
                    <div class="flex gap-2.5">
                        <span class="text-indigo-400 font-bold shrink-0">2.</span>
                        <p>Flip a second card — if both show the same emoji, they're a match!</p>
                    </div>
                    <div class="flex gap-2.5">
                        <span class="text-indigo-400 font-bold shrink-0">3.</span>
                        <p>Unmatched pairs flip back over. Remember where they were!</p>
                    </div>
                    <div class="flex gap-2.5">
                        <span class="text-indigo-400 font-bold shrink-0">4.</span>
                        <p>Match all pairs with the fewest moves for the highest score.</p>
                    </div>
                </div>
            </div>

        </div>

        
        <div x-show="phase === 'playing'"
             x-transition:enter="transition duration-200 ease-out"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="space-y-4">

            
            <div class="card p-4">
                <div class="flex items-center gap-2 flex-wrap">

                    
                    <div class="flex items-center gap-2.5 flex-1 min-w-[90px]">
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center shrink-0">⏱️</div>
                        <div>
                            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide leading-none">Time</p>
                            <p class="text-lg font-black text-gray-800 leading-tight font-mono"
                               x-text="formattedTime"></p>
                        </div>
                    </div>

                    <div class="w-px h-9 bg-gray-100 shrink-0"></div>

                    
                    <div class="flex items-center gap-2.5 flex-1 min-w-[70px]">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">🔄</div>
                        <div>
                            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide leading-none">Moves</p>
                            <p class="text-lg font-black text-gray-800 leading-tight" x-text="moves"></p>
                        </div>
                    </div>

                    <div class="w-px h-9 bg-gray-100 shrink-0"></div>

                    
                    <div class="flex items-center gap-2.5 flex-1 min-w-[90px]">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">✅</div>
                        <div>
                            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide leading-none">Matched</p>
                            <p class="text-lg font-black text-gray-800 leading-tight">
                                <span x-text="matches"></span><span class="text-gray-300 text-sm font-normal">/</span><span class="text-sm text-gray-400 font-semibold" x-text="totalPairs"></span>
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-2 ml-auto shrink-0">
                        <button type="button" @click="restartGame()"
                                class="btn btn-secondary btn-sm" title="Restart with same settings">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Restart
                        </button>
                        <button type="button" @click="goSetup()"
                                class="btn btn-secondary btn-sm">
                            ⚙️
                        </button>
                    </div>
                </div>

                
                <div class="mt-3 bg-gray-100 rounded-full h-1.5 overflow-hidden">
                    <div class="h-full bg-indigo-500 rounded-full transition-all duration-500"
                         :style="'width:' + (totalPairs > 0 ? Math.round(matches / totalPairs * 100) : 0) + '%'">
                    </div>
                </div>
            </div>

            
            <div class="card p-4 sm:p-5">
                <div class="mm-grid"
                     :class="DIFFICULTIES[difficulty].gridClass">
                    <template x-for="(card, idx) in cards" :key="card.uid">
                        <div class="mc-card"
                             :class="{
                                 'is-flipped':  card.state === 'flipped',
                                 'is-matched':  card.state === 'matched',
                                 'is-mismatch': card.mismatch,
                                 'is-idle':     locked || card.state !== 'hidden',
                             }"
                             @click="flipCard(idx)">
                            <div class="mc-inner">
                                <div class="mc-back mc-face"></div>
                                <div class="mc-front mc-face" x-text="card.emoji"></div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

        </div>

        
        <div x-show="phase === 'won'"
             x-transition:enter="transition duration-300 ease-out"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100">

            <div class="card p-8 text-center won-pop">

                <div class="text-6xl mb-3">🎉</div>
                <h2 class="text-2xl font-black text-gray-900 mb-1">You Did It!</h2>
                <p class="text-gray-400 mb-6">All <span class="font-semibold text-gray-600" x-text="totalPairs"></span> pairs matched. Excellent memory!</p>

                
                <div class="grid grid-cols-3 gap-3 mb-6 max-w-sm mx-auto">
                    <div class="bg-gray-50 rounded-2xl p-4">
                        <p class="text-xl font-black text-indigo-600 font-mono" x-text="formattedTime"></p>
                        <p class="text-xs text-gray-400 mt-0.5">Time</p>
                    </div>
                    <div class="bg-gray-50 rounded-2xl p-4">
                        <p class="text-xl font-black text-amber-500" x-text="moves"></p>
                        <p class="text-xs text-gray-400 mt-0.5">Moves</p>
                    </div>
                    <div class="bg-gray-50 rounded-2xl p-4">
                        <p class="text-xl font-black text-emerald-500" x-text="finalScore"></p>
                        <p class="text-xs text-gray-400 mt-0.5">Score</p>
                    </div>
                </div>

                
                <div class="flex justify-center gap-1.5 mb-1">
                    <template x-for="n in [1,2,3]" :key="n">
                        <span class="text-3xl transition-colors"
                              :class="n <= starRating ? 'star-on' : 'star-off'">★</span>
                    </template>
                </div>
                <p class="text-sm text-gray-400 mb-5" x-text="starLabel"></p>

                
                <div x-show="isNewBest"
                     class="inline-flex items-center gap-2 mb-5 px-4 py-2 bg-amber-50 border border-amber-200 rounded-xl text-amber-700 text-sm font-semibold">
                    🏆 New Best Time!
                </div>

                
                <div class="flex gap-3 justify-center flex-wrap">
                    <button type="button" @click="restartGame()"
                            class="btn btn-primary btn-lg">
                        🔄 Play Again
                    </button>
                    <button type="button" @click="goSetup()"
                            class="btn btn-secondary btn-lg">
                        ⚙️ Change Settings
                    </button>
                </div>
            </div>
        </div>

        
        <?php if($relatedTools->count()): ?>
        <div x-show="phase === 'setup'">
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
/* ═══════════════════════════════════════════════════════════
   MEMORY MATCH — Alpine.js component
═══════════════════════════════════════════════════════════ */
function memoryMatch() {
    return {

        /* ── Phase ── */
        phase: 'setup',   /* 'setup' | 'playing' | 'won' */

        /* ── Settings ── */
        difficulty: 'medium',
        theme:      'animals',

        /* ── Game state ── */
        cards:    [],    /* [{uid, pairId, emoji, state:'hidden'|'flipped'|'matched', mismatch:bool}] */
        flipped:  [],    /* indices of currently face-up cards */
        locked:   false,
        moves:    0,
        matches:  0,

        /* ── Timer ── */
        elapsedSeconds: 0,
        _timerHandle:   null,

        /* ── Result ── */
        finalScore: 0,
        isNewBest:  false,

        /* ── Static config ── */
        DIFFICULTIES: {
            easy:   { label:'Easy',   icon:'😊', pairs: 6,  gridClass:'mm-grid-easy', gridDesc:'4 cols × 3 rows' },
            medium: { label:'Medium', icon:'🧠', pairs: 8,  gridClass:'mm-grid-med',  gridDesc:'4 cols × 4 rows' },
            hard:   { label:'Hard',   icon:'🔥', pairs: 12, gridClass:'mm-grid-hard', gridDesc:'6 cols × 4 rows' },
        },
        THEMES: {
            animals: { icon:'🦁', label:'Animals',  emojis:['🦁','🐘','🦊','🐼','🐨','🦋','🐬','🦜','🐙','🦩','🐺','🦝'] },
            fruits:  { icon:'🍎', label:'Fruits',   emojis:['🍎','🍊','🍋','🍇','🍓','🫐','🍑','🥭','🍍','🥝','🍌','🍒'] },
            sports:  { icon:'⚽', label:'Sports',   emojis:['⚽','🏀','🎾','🏈','⚾','🎱','🏐','🏉','🥏','🎯','🏓','🥊'] },
            travel:  { icon:'✈️', label:'Travel',   emojis:['✈️','🚀','🚂','⛵','🚁','🚗','🏔️','🌊','🗺️','🌄','🏕️','🗼'] },
            food:    { icon:'🍕', label:'Food',     emojis:['🍕','🍔','🌮','🍜','🍣','🍦','🎂','🥗','🍩','☕','🍿','🥐'] },
            faces:   { icon:'😀', label:'Faces',    emojis:['😀','😎','🥳','😍','🤩','😂','🥰','😇','🤔','😴','🤗','😜'] },
            nature:  { icon:'🌸', label:'Nature',   emojis:['🌸','🌺','🌻','🍄','🌴','🌵','🌿','🍀','🌱','🌾','🍂','🌊'] },
            objects: { icon:'💡', label:'Objects',  emojis:['💡','🎸','📷','🎭','🏆','💎','🎩','🔮','📚','🎨','🎪','🎬'] },
        },

        /* ══════════════════════════════════════
           COMPUTED GETTERS
        ══════════════════════════════════════ */

        get totalPairs() {
            return this.DIFFICULTIES[this.difficulty].pairs;
        },

        get formattedTime() {
            var m = Math.floor(this.elapsedSeconds / 60);
            var s = this.elapsedSeconds % 60;
            return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
        },

        get starRating() {
            if (!this.moves) return 0;
            var p = this.totalPairs;
            if (this.moves <= p)        return 3;  /* perfect */
            if (this.moves <= p * 1.6)  return 2;  /* good */
            return 1;
        },

        get starLabel() {
            var labels = {
                1: '🙂 Keep practising — you\'ll do better next time!',
                2: '😊 Good job! Almost perfect.',
                3: '🌟 Flawless! Perfect memory!',
            };
            return labels[this.starRating] || '';
        },

        /* ══════════════════════════════════════
           INIT
        ══════════════════════════════════════ */

        init() { /* nothing needed on init */ },

        /* ══════════════════════════════════════
           START GAME
        ══════════════════════════════════════ */

        startGame() {
            var cfg    = this.DIFFICULTIES[this.difficulty];
            var emojis = this.THEMES[this.theme].emojis.slice(0, cfg.pairs);

            /* Build deck (two of each emoji) */
            var deck = [];
            var uid  = 0;
            for (var i = 0; i < emojis.length; i++) {
                deck.push({ uid: uid++, pairId: i, emoji: emojis[i], state: 'hidden', mismatch: false });
                deck.push({ uid: uid++, pairId: i, emoji: emojis[i], state: 'hidden', mismatch: false });
            }

            /* Fisher-Yates shuffle */
            for (var k = deck.length - 1; k > 0; k--) {
                var j = Math.floor(Math.random() * (k + 1));
                var tmp = deck[k]; deck[k] = deck[j]; deck[j] = tmp;
            }

            /* Reset state */
            this.cards          = deck;
            this.flipped        = [];
            this.locked         = false;
            this.moves          = 0;
            this.matches        = 0;
            this.elapsedSeconds = 0;
            this.finalScore     = 0;
            this.isNewBest      = false;
            this.phase          = 'playing';

            this._startTimer();
        },

        /* ══════════════════════════════════════
           CARD FLIP
        ══════════════════════════════════════ */

        flipCard(idx) {
            var card = this.cards[idx];

            /* Guards */
            if (this.locked)             return;
            if (card.state !== 'hidden') return;
            if (this.flipped.length >= 2) return;

            /* Flip the card */
            this._patch(idx, { state: 'flipped' });
            this.flipped = this.flipped.concat([idx]);

            /* Check for pair once two cards are up */
            if (this.flipped.length === 2) {
                this.moves++;
                this._checkPair();
            }
        },

        _checkPair() {
            var self = this;
            var a    = this.flipped[0];
            var b    = this.flipped[1];

            if (this.cards[a].pairId === this.cards[b].pairId) {
                /* ✅ Match */
                this._patch(a, { state: 'matched' });
                this._patch(b, { state: 'matched' });
                this.flipped = [];
                this.matches++;

                if (this.matches === this.totalPairs) {
                    this._stopTimer();
                    this.finalScore = this._calcScore();
                    this.isNewBest  = this._saveBest();
                    setTimeout(function() { self.phase = 'won'; }, 650);
                }

            } else {
                /* ❌ Mismatch — flash then flip back */
                this.locked = true;

                /* Show mismatch flash after flip animation settles (~450ms) */
                setTimeout(function() {
                    self._patch(a, { mismatch: true });
                    self._patch(b, { mismatch: true });
                }, 450);

                /* Flip back after 1.4 s total */
                setTimeout(function() {
                    self._patch(a, { state: 'hidden', mismatch: false });
                    self._patch(b, { state: 'hidden', mismatch: false });
                    self.flipped = [];
                    self.locked  = false;
                }, 1380);
            }
        },

        /* ══════════════════════════════════════
           GAME CONTROL
        ══════════════════════════════════════ */

        restartGame() {
            this._stopTimer();
            this.startGame();
        },

        goSetup() {
            this._stopTimer();
            this.cards = [];
            this.phase = 'setup';
        },

        /* ══════════════════════════════════════
           TIMER
        ══════════════════════════════════════ */

        _startTimer() {
            var self = this;
            this._stopTimer();
            this._timerHandle = setInterval(function() {
                self.elapsedSeconds++;
            }, 1000);
        },

        _stopTimer() {
            if (this._timerHandle) {
                clearInterval(this._timerHandle);
                this._timerHandle = null;
            }
        },

        /* ══════════════════════════════════════
           SCORE & BEST TIME
        ══════════════════════════════════════ */

        _calcScore() {
            var p     = this.totalPairs;
            var base  = p * 100;                                          /* base: 100 per pair     */
            var extra = Math.max(0, this.moves - p);                      /* wasted moves           */
            var bonus = Math.max(0, 180 - this.elapsedSeconds) * 2;      /* speed bonus            */
            return Math.max(0, base - extra * 8 + bonus);
        },

        _saveBest() {
            var key  = 'mm_best_' + this.difficulty + '_v1';
            var prev = localStorage.getItem(key);
            var best = prev !== null ? parseInt(prev, 10) : Infinity;
            if (this.elapsedSeconds < best) {
                try { localStorage.setItem(key, String(this.elapsedSeconds)); } catch(e) {}
                return true;
            }
            return false;
        },

        /* ══════════════════════════════════════
           HELPERS
        ══════════════════════════════════════ */

        /* Immutably update a single card — ensures Alpine reactivity */
        _patch(idx, patch) {
            var arr = this.cards.slice();
            arr[idx] = Object.assign({}, arr[idx], patch);
            this.cards = arr;
        },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\memory-match-game.blade.php ENDPATH**/ ?>