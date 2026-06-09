<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ── Board cells ── */
.cell-base {
    border-radius: 1rem;
    border-width: 2px;
    border-style: solid;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background .12s, border-color .12s, opacity .2s;
    user-select: none;
    aspect-ratio: 1 / 1;
}
.cell-empty  { background: #f9fafb; border-color: #e5e7eb; cursor: pointer; }
.cell-empty:hover  { background: #eef2ff; border-color: #a5b4fc; }
.cell-empty-idle   { background: #f9fafb; border-color: #e5e7eb; cursor: default; }
.cell-x      { background: #eef2ff; border-color: #a5b4fc; cursor: default; }
.cell-o      { background: #fff1f2; border-color: #fda4af; cursor: default; }
.cell-win-x  { background: #e0e7ff; border-color: #6366f1; cursor: default;
               animation: winPulse .85s ease-in-out infinite; }
.cell-win-o  { background: #ffe4e6; border-color: #f43f5e; cursor: default;
               animation: winPulse .85s ease-in-out infinite; }
.cell-dim    { opacity: .3; }

@keyframes winPulse {
    0%,100% { box-shadow: 0 0 0 2px rgba(99,102,241,.25); }
    50%      { box-shadow: 0 0 0 8px rgba(99,102,241,.05); }
}

/* ── Symbol pop-in ── */
@keyframes symbolPop {
    0%   { transform: scale(.35); opacity: 0; }
    70%  { transform: scale(1.18); }
    100% { transform: scale(1);    opacity: 1; }
}
.symbol-pop { animation: symbolPop .22s cubic-bezier(.34,1.56,.64,1) both; }

/* ── Status slide-in ── */
@keyframes statusIn {
    from { opacity: 0; transform: translateY(-5px); }
    to   { opacity: 1; transform: translateY(0); }
}
.status-in { animation: statusIn .25s ease both; }

/* ── AI dots ── */
@keyframes dot { 0%,80%,100%{ opacity:.25 } 40%{ opacity:1 } }
.ai-dot:nth-child(1){ animation: dot 1.3s 0.0s infinite; }
.ai-dot:nth-child(2){ animation: dot 1.3s 0.2s infinite; }
.ai-dot:nth-child(3){ animation: dot 1.3s 0.4s infinite; }
</style>

<div class="min-h-screen bg-gray-50"
     x-data="ticTacToe()"
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

        
        <div class="card p-5 space-y-4">

            
            <div>
                <p class="form-label">Game Mode</p>
                <div class="flex gap-2">
                    <button type="button" @click="setMode('pvp')"
                            class="btn flex-1"
                            :class="mode === 'pvp' ? 'btn-primary' : 'btn-secondary'">
                        👥 Player vs Player
                    </button>
                    <button type="button" @click="setMode('pvc')"
                            class="btn flex-1"
                            :class="mode === 'pvc' ? 'btn-primary' : 'btn-secondary'">
                        🤖 vs Computer
                    </button>
                </div>
            </div>

            
            <div x-show="mode === 'pvc'" x-transition
                 class="grid sm:grid-cols-2 gap-4 pt-1">

                
                <div>
                    <p class="form-label">Difficulty</p>
                    <div class="flex gap-2">
                        <template x-for="d in difficulties" :key="d.val">
                            <button type="button"
                                    @click="setDifficulty(d.val)"
                                    class="btn flex-1 text-xs"
                                    :class="difficulty === d.val ? 'btn-primary' : 'btn-secondary'">
                                <span x-text="d.icon"></span>
                                <span x-text="d.label"></span>
                            </button>
                        </template>
                    </div>
                </div>

                
                <div>
                    <p class="form-label">You play as</p>
                    <div class="flex gap-2">
                        <button type="button"
                                @click="setPlayerSymbol('X')"
                                class="btn flex-1"
                                :class="playerSymbol === 'X'
                                    ? 'bg-indigo-600 text-white hover:bg-indigo-700 focus:ring-indigo-500'
                                    : 'btn-secondary'">
                            <svg viewBox="0 0 40 40" class="w-4 h-4" fill="none"
                                 stroke="currentColor" stroke-width="6.5" stroke-linecap="round">
                                <line x1="7" y1="7" x2="33" y2="33"/>
                                <line x1="33" y1="7" x2="7" y2="33"/>
                            </svg>
                            X (first)
                        </button>
                        <button type="button"
                                @click="setPlayerSymbol('O')"
                                class="btn flex-1"
                                :class="playerSymbol === 'O'
                                    ? 'bg-rose-500 text-white hover:bg-rose-600 focus:ring-rose-400'
                                    : 'btn-secondary'">
                            <svg viewBox="0 0 40 40" class="w-4 h-4" fill="none"
                                 stroke="currentColor" stroke-width="6.5">
                                <circle cx="20" cy="20" r="12"/>
                            </svg>
                            O (second)
                        </button>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="grid grid-cols-3 gap-3">

            
            <div class="card p-4 text-center transition-shadow"
                 :class="currentPlayer === 'X' && !gameOver ? 'ring-2 ring-indigo-300' : ''">
                <div class="flex justify-center mb-1.5">
                    <svg viewBox="0 0 40 40" class="w-7 h-7 text-indigo-600"
                         fill="none" stroke="currentColor" stroke-width="6.5" stroke-linecap="round">
                        <line x1="7" y1="7" x2="33" y2="33"/>
                        <line x1="33" y1="7" x2="7" y2="33"/>
                    </svg>
                </div>
                <p class="text-3xl font-black text-indigo-600 leading-none" x-text="scores.X"></p>
                <p class="text-xs text-gray-400 mt-1 font-medium truncate" x-text="nameX"></p>
            </div>

            
            <div class="card p-4 text-center">
                <p class="text-sm font-semibold text-gray-300 mb-1.5 mt-0.5">Draw</p>
                <p class="text-3xl font-black text-gray-400 leading-none" x-text="scores.draw"></p>
                <p class="text-xs text-gray-300 mt-1">ties</p>
            </div>

            
            <div class="card p-4 text-center transition-shadow"
                 :class="currentPlayer === 'O' && !gameOver ? 'ring-2 ring-rose-300' : ''">
                <div class="flex justify-center mb-1.5">
                    <svg viewBox="0 0 40 40" class="w-7 h-7 text-rose-500"
                         fill="none" stroke="currentColor" stroke-width="6.5">
                        <circle cx="20" cy="20" r="12"/>
                    </svg>
                </div>
                <p class="text-3xl font-black text-rose-500 leading-none" x-text="scores.O"></p>
                <p class="text-xs text-gray-400 mt-1 font-medium truncate" x-text="nameO"></p>
            </div>
        </div>

        
        <div class="card p-6">

            
            <div class="min-h-[48px] flex items-center justify-center mb-5">

                
                <div x-show="!gameOver && !aiThinking"
                     class="flex items-center gap-2.5 status-in">
                    <span class="w-5 h-5 rounded-full flex-shrink-0 transition-colors duration-200"
                          :style="currentPlayer === 'X'
                              ? 'background:#4f46e5'
                              : 'background:#f43f5e'">
                    </span>
                    <span class="font-semibold text-gray-700 text-lg" x-text="statusMsg"></span>
                </div>

                
                <div x-show="aiThinking"
                     class="flex items-center gap-2 text-gray-500 status-in">
                    <span class="font-medium">🤖 Computer is thinking</span>
                    <span class="flex items-end gap-0.5 h-4 ml-1">
                        <span class="ai-dot w-1.5 h-1.5 rounded-full bg-gray-400 inline-block"></span>
                        <span class="ai-dot w-1.5 h-1.5 rounded-full bg-gray-400 inline-block"></span>
                        <span class="ai-dot w-1.5 h-1.5 rounded-full bg-gray-400 inline-block"></span>
                    </span>
                </div>

                
                <div x-show="gameOver" class="status-in text-center">
                    <p class="text-xl font-bold"
                       :class="winner === 'X' ? 'text-indigo-600'
                             : winner === 'O' ? 'text-rose-500'
                             : 'text-amber-600'"
                       x-text="statusMsg">
                    </p>
                </div>
            </div>

            
            <div class="max-w-[340px] mx-auto">
                <div class="grid grid-cols-3 gap-3">
                    <template x-for="(cell, i) in board" :key="i">
                        <button type="button"
                                class="cell-base"
                                :class="cellClass(i)"
                                @click="cellClick(i)">

                            
                            <template x-if="cell === 'X'">
                                <svg viewBox="0 0 40 40"
                                     class="w-10 h-10 sm:w-12 sm:h-12 text-indigo-600 symbol-pop"
                                     fill="none" stroke="currentColor"
                                     stroke-width="5.5" stroke-linecap="round">
                                    <line x1="7" y1="7" x2="33" y2="33"/>
                                    <line x1="33" y1="7" x2="7" y2="33"/>
                                </svg>
                            </template>

                            
                            <template x-if="cell === 'O'">
                                <svg viewBox="0 0 40 40"
                                     class="w-10 h-10 sm:w-12 sm:h-12 text-rose-500 symbol-pop"
                                     fill="none" stroke="currentColor" stroke-width="5.5">
                                    <circle cx="20" cy="20" r="12"/>
                                </svg>
                            </template>

                        </button>
                    </template>
                </div>
            </div>

            
            <div class="flex gap-3 mt-6 max-w-[340px] mx-auto">
                <button type="button"
                        @click="newGame()"
                        class="btn btn-primary flex-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    New Game
                </button>
                <button type="button"
                        @click="resetScores()"
                        class="btn btn-secondary"
                        title="Reset all scores">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        </div>

        
        <div class="card p-5">
            <h3 class="font-semibold text-gray-800 mb-3">How to play</h3>
            <div class="grid sm:grid-cols-2 gap-2.5 text-sm text-gray-500">
                <div class="flex gap-2.5">
                    <span class="text-indigo-400 font-bold flex-shrink-0">1.</span>
                    <p>Take turns placing your mark on an empty square by clicking it.</p>
                </div>
                <div class="flex gap-2.5">
                    <span class="text-indigo-400 font-bold flex-shrink-0">2.</span>
                    <p>Get three in a row — horizontally, vertically, or diagonally — to win.</p>
                </div>
                <div class="flex gap-2.5">
                    <span class="text-indigo-400 font-bold flex-shrink-0">3.</span>
                    <p>In <strong class="text-gray-700">vs Computer</strong> mode: Easy is random, Medium is smart, Hard is unbeatable.</p>
                </div>
                <div class="flex gap-2.5">
                    <span class="text-indigo-400 font-bold flex-shrink-0">4.</span>
                    <p><strong class="text-gray-700">Hard</strong> uses the Minimax algorithm. Your best result against it is a draw!</p>
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
/* ─────────────────────────────────────────────
   TIC TAC TOE — Alpine.js component
───────────────────────────────────────────── */
function ticTacToe() {
    return {
        /* ── Board state ── */
        board:         Array(9).fill(null),   // null | 'X' | 'O'
        currentPlayer: 'X',
        winner:        null,                  // null | 'X' | 'O' | 'draw'
        winLine:       [],                    // e.g. [0, 1, 2]
        gameOver:      false,

        /* ── Mode / settings ── */
        mode:         'pvp',     // 'pvp' | 'pvc'
        difficulty:   'medium',  // 'easy' | 'medium' | 'hard'
        playerSymbol: 'X',       // human symbol in PvC
        aiThinking:   false,

        difficulties: [
            { val:'easy',   label:'Easy',   icon:'😄' },
            { val:'medium', label:'Medium', icon:'🧠' },
            { val:'hard',   label:'Hard',   icon:'🔥' },
        ],

        /* ── Names ── */
        nameX: 'Player X',
        nameO: 'Player O',

        /* ── Scores ── */
        scores: { X: 0, O: 0, draw: 0 },

        /* ══════════════════════════════════════
           COMPUTED
        ══════════════════════════════════════ */

        get aiSymbol() {
            return this.playerSymbol === 'X' ? 'O' : 'X';
        },

        get statusMsg() {
            if (this.aiThinking)        return 'Computer is thinking…';
            if (this.winner === 'draw') return "🤝 It's a draw!";
            if (this.winner) {
                var n = this.winner === 'X' ? this.nameX : this.nameO;
                return '🎉 ' + n + ' wins!';
            }
            var name = this.currentPlayer === 'X' ? this.nameX : this.nameO;
            return name + "'s turn";
        },

        /* ══════════════════════════════════════
           INIT
        ══════════════════════════════════════ */

        init() {
            try {
                var s = sessionStorage.getItem('ttt_scores_v1');
                if (s) this.scores = JSON.parse(s);
            } catch(e) {}
            this._updateNames();
        },

        /* ══════════════════════════════════════
           CELL INTERACTION
        ══════════════════════════════════════ */

        cellClick(i) {
            if (this.board[i] !== null) return;
            if (this.gameOver || this.aiThinking) return;
            if (this.mode === 'pvc' && this.currentPlayer === this.aiSymbol) return;

            this._makeMove(i, this.currentPlayer);

            if (!this.gameOver && this.mode === 'pvc') {
                this._scheduleAI();
            }
        },

        _makeMove(i, player) {
            var b    = this.board.slice();
            b[i]     = player;
            this.board = b;

            var result = this._checkResult(this.board);
            if (result) {
                this.gameOver = true;
                this.winner   = result;
                if (result !== 'draw') {
                    this.winLine = this._findWinLine(this.board, result);
                    this.scores[result]++;
                } else {
                    this.scores.draw++;
                }
                this._saveScores();
            } else {
                this.currentPlayer = this.currentPlayer === 'X' ? 'O' : 'X';
            }
        },

        /* ══════════════════════════════════════
           AI
        ══════════════════════════════════════ */

        _scheduleAI() {
            var self  = this;
            var delay = { easy: 380, medium: 550, hard: 720 }[this.difficulty] || 500;
            this.aiThinking = true;
            setTimeout(function() {
                if (!self.gameOver) {
                    var m = self._aiPick();
                    if (m !== -1) self._makeMove(m, self.aiSymbol);
                }
                self.aiThinking = false;
            }, delay);
        },

        _aiPick() {
            var self  = this;
            var empty = this.board
                            .map(function(c, i) { return c === null ? i : -1; })
                            .filter(function(i) { return i !== -1; });
            if (!empty.length) return -1;

            /* Easy: pure random */
            if (this.difficulty === 'easy') {
                return empty[Math.floor(Math.random() * empty.length)];
            }

            /* Medium: 30% random, else strategic (no minimax) */
            if (this.difficulty === 'medium' && Math.random() < 0.30) {
                return empty[Math.floor(Math.random() * empty.length)];
            }

            /* Try to win */
            var win = this._findWinOrBlock(this.board, this.aiSymbol);
            if (win !== null) return win;

            /* Block player's winning move */
            var block = this._findWinOrBlock(this.board, this.playerSymbol);
            if (block !== null) return block;

            /* Hard: full minimax with alpha-beta pruning */
            if (this.difficulty === 'hard') {
                return this._minimax(this.board.slice(), -100, 100, true).move;
            }

            /* Medium: center → corner → random */
            if (this.board[4] === null) return 4;
            var corners = [0, 2, 6, 8].filter(function(c) { return self.board[c] === null; });
            if (corners.length) return corners[Math.floor(Math.random() * corners.length)];
            return empty[Math.floor(Math.random() * empty.length)];
        },

        /* Find a line where sym has 2 cells and one is empty — return the empty cell index */
        _findWinOrBlock(board, sym) {
            var LINES = [[0,1,2],[3,4,5],[6,7,8],[0,3,6],[1,4,7],[2,5,8],[0,4,8],[2,4,6]];
            for (var k = 0; k < LINES.length; k++) {
                var l  = LINES[k];
                var v  = [board[l[0]], board[l[1]], board[l[2]]];
                var ei = v.indexOf(null);
                if (ei !== -1 && v.filter(function(x) { return x === sym; }).length === 2) {
                    return l[ei];
                }
            }
            return null;
        },

        /* Minimax with alpha-beta pruning — returns { score, move } */
        _minimax(board, alpha, beta, isMax) {
            var res = this._checkResult(board);
            if (res === this.aiSymbol)     return { score:  10, move: -1 };
            if (res === this.playerSymbol) return { score: -10, move: -1 };
            if (res === 'draw')            return { score:   0, move: -1 };

            var best     = isMax ? -100 : 100;
            var bestMove = -1;
            var sym      = isMax ? this.aiSymbol : this.playerSymbol;

            for (var i = 0; i < 9; i++) {
                if (board[i] !== null) continue;
                board[i]  = sym;
                var r     = this._minimax(board, alpha, beta, !isMax);
                board[i]  = null;

                if (isMax) {
                    if (r.score > best) { best = r.score; bestMove = i; }
                    alpha = Math.max(alpha, best);
                } else {
                    if (r.score < best) { best = r.score; bestMove = i; }
                    beta = Math.min(beta, best);
                }
                if (beta <= alpha) break;  /* prune */
            }
            return { score: best, move: bestMove };
        },

        /* ══════════════════════════════════════
           WIN DETECTION
        ══════════════════════════════════════ */

        _checkResult(board) {
            var LINES = [[0,1,2],[3,4,5],[6,7,8],[0,3,6],[1,4,7],[2,5,8],[0,4,8],[2,4,6]];
            for (var k = 0; k < LINES.length; k++) {
                var a = LINES[k][0], b = LINES[k][1], c = LINES[k][2];
                if (board[a] && board[a] === board[b] && board[a] === board[c]) return board[a];
            }
            if (board.every(function(c) { return c !== null; })) return 'draw';
            return null;
        },

        _findWinLine(board, sym) {
            var LINES = [[0,1,2],[3,4,5],[6,7,8],[0,3,6],[1,4,7],[2,5,8],[0,4,8],[2,4,6]];
            for (var k = 0; k < LINES.length; k++) {
                var a = LINES[k][0], b = LINES[k][1], c = LINES[k][2];
                if (board[a] === sym && board[b] === sym && board[c] === sym) return LINES[k];
            }
            return [];
        },

        /* ══════════════════════════════════════
           GAME CONTROL
        ══════════════════════════════════════ */

        newGame() {
            this.board         = Array(9).fill(null);
            this.currentPlayer = 'X';
            this.winner        = null;
            this.winLine       = [];
            this.gameOver      = false;
            this.aiThinking    = false;

            /* If PvC and AI plays X, it moves first */
            if (this.mode === 'pvc' && this.aiSymbol === 'X') {
                this._scheduleAI();
            }
        },

        setMode(m) {
            this.mode = m;
            this._updateNames();
            this.newGame();
        },

        setDifficulty(d) {
            this.difficulty = d;
            this.newGame();
        },

        setPlayerSymbol(s) {
            this.playerSymbol = s;
            this._updateNames();
            this.newGame();
        },

        resetScores() {
            if (!confirm('Reset all scores to zero?')) return;
            this.scores = { X: 0, O: 0, draw: 0 };
            this._saveScores();
        },

        /* ══════════════════════════════════════
           CELL CSS CLASS HELPER
        ══════════════════════════════════════ */

        cellClass(i) {
            var cell  = this.board[i];
            var isWin = this.winLine.includes(i);
            var isDim = this.gameOver && this.winLine.length > 0 && !isWin;

            var canAct = !cell && !this.gameOver && !this.aiThinking &&
                         !(this.mode === 'pvc' && this.currentPlayer === this.aiSymbol);

            if (isWin) {
                return cell === 'X' ? 'cell-win-x' : 'cell-win-o';
            }

            var cls = '';
            if (!cell) {
                cls = canAct ? 'cell-empty' : 'cell-empty-idle';
            } else {
                cls = cell === 'X' ? 'cell-x' : 'cell-o';
            }

            if (isDim) cls += ' cell-dim';
            return cls;
        },

        /* ══════════════════════════════════════
           HELPERS
        ══════════════════════════════════════ */

        _updateNames() {
            if (this.mode === 'pvp') {
                this.nameX = 'Player X';
                this.nameO = 'Player O';
            } else {
                this.nameX = this.playerSymbol === 'X' ? 'You (X)' : 'Computer (X)';
                this.nameO = this.playerSymbol === 'O' ? 'You (O)' : 'Computer (O)';
            }
        },

        _saveScores() {
            try { sessionStorage.setItem('ttt_scores_v1', JSON.stringify(this.scores)); } catch(e) {}
        },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\tic-tac-toe.blade.php ENDPATH**/ ?>