<?php $__env->startSection('title', $tool->name . ' - ' . config('app.name')); ?>
<?php $__env->startSection('meta_description', $tool->description ?? 'Free online scientific calculator with trig, log, powers, constants and more.'); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ── Scientific Calculator ── */
.sc-wrap { display: grid; grid-template-columns: 3fr 2fr; gap: 1.5rem; align-items: start; }
@media(max-width:900px){ .sc-wrap { grid-template-columns: 1fr; } }

/* Display */
.sc-display {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border-radius: .75rem .75rem 0 0;
    padding: 1rem 1.25rem .75rem;
    min-height: 100px;
    position: relative;
    overflow: hidden;
}
.sc-display::after {
    content:'';
    position:absolute; inset:0;
    background: repeating-linear-gradient(0deg, transparent, transparent 24px, rgba(255,255,255,.02) 24px, rgba(255,255,255,.02) 25px);
    pointer-events:none;
}
.sc-expr-line { font-size:.78rem; color:#64748b; text-align:right; min-height:1.2em; word-break:break-all; font-family:'Courier New',monospace; letter-spacing:.02em; transition: color .2s; }
.sc-expr-line.has-preview { color:#94a3b8; }
.sc-main-line { font-size:2.4rem; font-weight:700; color:#f1f5f9; text-align:right; word-break:break-all; line-height:1.1; min-height:2.8rem; font-family:'Courier New',monospace; transition: color .2s; }
.sc-main-line.error { color:#f87171; font-size:1.1rem; display:flex; align-items:center; justify-content:flex-end; }
.sc-preview-badge { position:absolute; top:.5rem; left:.75rem; font-size:.65rem; background:rgba(79,70,229,.3); color:#a5b4fc; border-radius:9999px; padding:.1rem .5rem; }
.sc-mode-badge { position:absolute; top:.5rem; right:.75rem; font-size:.65rem; background:rgba(255,255,255,.08); color:#94a3b8; border-radius:9999px; padding:.1rem .5rem; font-family:monospace; }

/* Button grid */
.sc-pad { background:#1e293b; border-radius: 0 0 .75rem .75rem; padding: .6rem; }
.sc-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: .35rem; }
.sc-btn {
    padding: .65rem .2rem;
    border-radius: .45rem;
    font-size: .85rem;
    font-weight: 600;
    cursor: pointer;
    border: 1px solid transparent;
    transition: all .12s;
    text-align: center;
    user-select: none;
    line-height: 1;
}
.sc-btn:active { transform: scale(.93); }
.sc-num  { background:#334155; color:#f1f5f9; border-color:#475569; }
.sc-num:hover { background:#475569; }
.sc-op   { background:#1e40af; color:#bfdbfe; border-color:#3b82f6; }
.sc-op:hover { background:#1d4ed8; }
.sc-eq   { background:linear-gradient(135deg,#4f46e5,#7c3aed); color:#fff; border-color:#7c3aed; font-size:1.2rem; }
.sc-eq:hover { filter:brightness(1.15); }
.sc-fn   { background:#164e63; color:#a5f3fc; border-color:#0891b2; font-size:.78rem; }
.sc-fn:hover { background:#155e75; }
.sc-fn.inv-active { background:#713f12; color:#fde68a; border-color:#d97706; }
.sc-const { background:#1a2e05; color:#bbf7d0; border-color:#16a34a; font-size:.82rem; }
.sc-const:hover { background:#14532d; }
.sc-clear { background:#7f1d1d; color:#fca5a5; border-color:#dc2626; }
.sc-clear:hover { background:#991b1b; }
.sc-del  { background:#78350f; color:#fcd34d; border-color:#d97706; }
.sc-del:hover { background:#92400e; }
.sc-mem  { background:#312e81; color:#c7d2fe; border-color:#6366f1; font-size:.78rem; }
.sc-mem:hover { background:#3730a3; }
.sc-mode { background:#374151; color:#e5e7eb; border-color:#6b7280; font-size:.75rem; }
.sc-mode:hover { background:#4b5563; }
.sc-mode.active { background:#4338ca; color:#fff; border-color:#6366f1; }
.sc-paren { background:#1e3a5f; color:#93c5fd; border-color:#3b82f6; }
.sc-paren:hover { background:#1e40af; }

/* History & info panel */
.sc-panel-tab { display:flex; border-bottom:2px solid #e5e7eb; margin-bottom:.75rem; }
.sc-panel-tab button { padding:.4rem .9rem; font-size:.82rem; font-weight:600; color:#6b7280; border-bottom:2px solid transparent; margin-bottom:-2px; transition:all .15s; background:none; cursor:pointer; }
.sc-panel-tab button.active { color:#4f46e5; border-bottom-color:#4f46e5; }
.sc-hist-item { display:flex; flex-direction:column; padding:.5rem .6rem; border-radius:.4rem; cursor:pointer; transition:background .15s; border:1px solid #e5e7eb; margin-bottom:.4rem; }
.sc-hist-item:hover { background:#f5f3ff; border-color:#a5b4fc; }
.sc-hist-expr { font-size:.72rem; color:#6b7280; font-family:'Courier New',monospace; }
.sc-hist-result { font-size:1rem; font-weight:700; color:#1e1b4b; font-family:'Courier New',monospace; }
.sc-empty { text-align:center; color:#9ca3af; font-size:.82rem; padding:1.5rem 0; }
.sc-ref-row { display:flex; justify-content:space-between; align-items:center; padding:.3rem 0; border-bottom:1px solid #f3f4f6; font-size:.8rem; }
.sc-ref-key { color:#374151; font-weight:500; }
.sc-ref-val { color:#6b7280; font-family:'Courier New',monospace; font-size:.75rem; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8 max-w-6xl">

    
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <span class="text-4xl"><?php echo e($tool->icon ?? '🔬'); ?></span>
            <div>
                <h1 class="text-3xl font-bold text-gray-900"><?php echo e($tool->name); ?></h1>
                <p class="text-gray-500 text-sm mt-0.5"><?php echo e($tool->description ?? 'Full-featured scientific calculator with trig, log, powers, memory and more.'); ?></p>
            </div>
        </div>
    </div>

    <div class="sc-wrap" x-data="sciCalc()" x-init="init()">

        
        <div>
            
            <div class="sc-display">
                <span class="sc-preview-badge" x-show="liveResult !== '' && !justEvaluated">PREVIEW</span>
                <span class="sc-mode-badge" x-text="angleMode + (memVal !== 0 ? ' · M' : '')"></span>
                <div class="sc-expr-line" :class="{'has-preview': liveResult !== '' && !justEvaluated}" x-text="exprLine || ' '"></div>
                <div class="sc-main-line" :class="{error: isError}" x-text="mainLine || '0'"></div>
            </div>

            
            <div class="sc-pad">
                <div class="sc-grid">

                    
                    <button class="sc-btn sc-mode" :class="{active: angleMode==='DEG'}" @click="toggleAngle()" x-text="angleMode"></button>
                    <button class="sc-btn sc-mode" :class="{active: invMode}" @click="invMode=!invMode" x-text="invMode ? 'INV ✓' : 'INV'"></button>
                    <button class="sc-btn sc-paren" @click="press('(')">(</button>
                    <button class="sc-btn sc-paren" @click="press(')')">)</button>
                    <button class="sc-btn sc-clear" @click="allClear()">AC</button>

                    
                    <button class="sc-btn sc-fn" :class="{'inv-active':invMode}" @click="pressFn(invMode?'asin':'sin')" x-text="invMode?'sin⁻¹':'sin'"></button>
                    <button class="sc-btn sc-fn" :class="{'inv-active':invMode}" @click="pressFn(invMode?'acos':'cos')" x-text="invMode?'cos⁻¹':'cos'"></button>
                    <button class="sc-btn sc-fn" :class="{'inv-active':invMode}" @click="pressFn(invMode?'atan':'tan')" x-text="invMode?'tan⁻¹':'tan'"></button>
                    <button class="sc-btn sc-const" @click="pressConst('π')">π</button>
                    <button class="sc-btn sc-const" @click="pressConst('e')">e</button>

                    
                    <button class="sc-btn sc-fn" :class="{'inv-active':invMode}" @click="pressSpecial(invMode?'sq':'sqrt')" x-text="invMode?'x²':'√x'"></button>
                    <button class="sc-btn sc-fn" :class="{'inv-active':invMode}" @click="pressSpecial(invMode?'cbrt':'cube')" x-text="invMode?'∛x':'x³'"></button>
                    <button class="sc-btn sc-fn" :class="{'inv-active':invMode}" @click="pressSpecial(invMode?'yroot':'pow')" x-text="invMode?'ʸ√x':'xʸ'"></button>
                    <button class="sc-btn sc-fn" :class="{'inv-active':invMode}" @click="pressSpecial(invMode?'pow10':'log')" x-text="invMode?'10ˣ':'log'"></button>
                    <button class="sc-btn sc-fn" :class="{'inv-active':invMode}" @click="pressSpecial(invMode?'exp':'ln')"  x-text="invMode?'eˣ':'ln'"></button>

                    
                    <button class="sc-btn sc-fn" @click="pressSpecial('recip')">1/x</button>
                    <button class="sc-btn sc-fn" @click="pressSpecial('fact')">n!</button>
                    <button class="sc-btn sc-fn" @click="press('%')">%</button>
                    <button class="sc-btn sc-del" @click="backspace()">⌫</button>
                    <button class="sc-btn sc-mem" @click="memRecall()">MR</button>

                    
                    <button class="sc-btn sc-num" @click="press('7')">7</button>
                    <button class="sc-btn sc-num" @click="press('8')">8</button>
                    <button class="sc-btn sc-num" @click="press('9')">9</button>
                    <button class="sc-btn sc-op"  @click="press('÷')">÷</button>
                    <button class="sc-btn sc-mem" @click="memClear()">MC</button>

                    
                    <button class="sc-btn sc-num" @click="press('4')">4</button>
                    <button class="sc-btn sc-num" @click="press('5')">5</button>
                    <button class="sc-btn sc-num" @click="press('6')">6</button>
                    <button class="sc-btn sc-op"  @click="press('×')">×</button>
                    <button class="sc-btn sc-mem" @click="memAdd()">M+</button>

                    
                    <button class="sc-btn sc-num" @click="press('1')">1</button>
                    <button class="sc-btn sc-num" @click="press('2')">2</button>
                    <button class="sc-btn sc-num" @click="press('3')">3</button>
                    <button class="sc-btn sc-op"  @click="press('−')">−</button>
                    <button class="sc-btn sc-fn"  @click="pressSpecial('neg')">±</button>

                    
                    <button class="sc-btn sc-num" @click="press('0')">0</button>
                    <button class="sc-btn sc-num" @click="press('00')">00</button>
                    <button class="sc-btn sc-num" @click="press('.')">.</button>
                    <button class="sc-btn sc-op"  @click="press('+')">+</button>
                    <button class="sc-btn sc-eq"  @click="evaluate()">=</button>

                </div>
            </div>

            
            <p class="text-xs text-gray-400 mt-2 text-center">Keyboard supported · Enter = · Esc AC · Backspace ⌫</p>
        </div>

        
        <div class="card p-4">
            <div class="sc-panel-tab">
                <button :class="{active: panel==='history'}" @click="panel='history'">History</button>
                <button :class="{active: panel==='ref'}"     @click="panel='ref'">Reference</button>
            </div>

            
            <div x-show="panel==='history'">
                <div x-show="history.length===0" class="sc-empty">
                    <div class="text-2xl mb-1">🕐</div>
                    No calculations yet
                </div>
                <div style="max-height:420px;overflow-y:auto;">
                    <template x-for="(h,i) in history.slice().reverse()" :key="i">
                        <div class="sc-hist-item" @click="restoreHistory(h)">
                            <span class="sc-hist-expr" x-text="h.expr"></span>
                            <span class="sc-hist-result" x-text="'= ' + h.result"></span>
                        </div>
                    </template>
                </div>
                <button x-show="history.length>0" @click="history=[]" class="btn btn-secondary btn-sm w-full mt-2" style="font-size:.75rem;">Clear History</button>
            </div>

            
            <div x-show="panel==='ref'">
                <p class="text-xs text-gray-500 mb-2">Constants &amp; keyboard shortcuts</p>
                <template x-for="r in reference" :key="r.k">
                    <div class="sc-ref-row">
                        <span class="sc-ref-key" x-text="r.k"></span>
                        <span class="sc-ref-val" x-text="r.v"></span>
                    </div>
                </template>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function sciCalc() {
    return {
        expr: '',           // internal expression string
        mainLine: '0',      // big display line
        exprLine: '',       // small top line
        liveResult: '',     // preview of live eval
        isError: false,
        justEvaluated: false,
        angleMode: 'DEG',
        invMode: false,
        memVal: 0,
        history: [],
        panel: 'history',
        reference: [
            {k:'π', v:'3.14159265358979'},
            {k:'e', v:'2.71828182845904'},
            {k:'√2', v:'1.41421356237310'},
            {k:'√3', v:'1.73205080756888'},
            {k:'Enter / =', v:'Evaluate'},
            {k:'Escape', v:'All Clear'},
            {k:'Backspace', v:'Delete last'},
            {k:'^ or **', v:'Power (xʸ)'},
            {k:'( )', v:'Brackets'},
            {k:'%', v:'Divide by 100'},
        ],

        init() {
            document.addEventListener('keydown', e => this._onKey(e));
        },

        /* ── Input ── */
        press(ch) {
            if (this.justEvaluated) {
                // If user types an operator right after =, continue from result
                if (['+','−','×','÷','%','^'].includes(ch)) {
                    this.expr = this.mainLine;
                } else {
                    this.expr = '';
                    this.mainLine = '0';
                }
                this.justEvaluated = false;
                this.exprLine = '';
            }
            if (ch === '00') {
                if (this.expr === '' || this.expr === '0') return;
                this.expr += '00';
            } else if (ch === '.') {
                // Only add dot if last number segment doesn't already have one
                const segs = this.expr.split(/[+\-×÷^()%]/);
                const last = segs[segs.length - 1];
                if (last.includes('.')) return;
                if (last === '') this.expr += '0';
                this.expr += '.';
            } else {
                this.expr += ch;
            }
            this._updateDisplay();
            this._liveEval();
        },

        pressFn(name) {
            if (this.justEvaluated) { this.expr = ''; this.justEvaluated = false; }
            this.expr += name + '(';
            this._updateDisplay();
        },

        pressConst(name) {
            if (this.justEvaluated) { this.expr = ''; this.justEvaluated = false; }
            this.expr += name;
            this._updateDisplay();
            this._liveEval();
        },

        pressSpecial(op) {
            if (this.justEvaluated) {
                if (op === 'neg') {
                    // negate result
                    try {
                        const n = parseFloat(this.mainLine);
                        if (!isNaN(n)) { this.mainLine = this._fmt(-n); this.expr = this.mainLine; }
                    } catch(e) {}
                    return;
                }
                this.expr = this.mainLine;
                this.justEvaluated = false;
                this.exprLine = '';
            }
            switch(op) {
                case 'sqrt':  this.expr += 'sqrt('; break;
                case 'sq':    this.expr += '^2'; break;
                case 'cube':  this.expr += '^3'; break;
                case 'cbrt':  this.expr += 'cbrt('; break;
                case 'pow':   this.expr += '^'; break;
                case 'yroot': this.expr += '^(1/'; break;
                case 'log':   this.expr += 'log('; break;
                case 'ln':    this.expr += 'ln('; break;
                case 'pow10': this.expr += '10^('; break;
                case 'exp':   this.expr += 'e^('; break;
                case 'recip': this.expr = '1/(' + this.expr + ')'; break;
                case 'fact':  this.expr += '!'; break;
                case 'neg':
                    if (this.expr === '' || this.expr === '0') { this.expr = '-'; }
                    else { this.expr = '(-(' + this.expr + '))'; }
                    break;
            }
            this._updateDisplay();
            this._liveEval();
        },

        allClear() {
            this.expr = '';
            this.mainLine = '0';
            this.exprLine = '';
            this.liveResult = '';
            this.isError = false;
            this.justEvaluated = false;
        },

        backspace() {
            if (this.justEvaluated) { this.allClear(); return; }
            // Remove last character(s) — handle multi-char tokens
            const tokens = ['asin(','acos(','atan(','sqrt(','cbrt(','log(','ln(','sin(','cos(','tan('];
            for (const t of tokens) {
                if (this.expr.endsWith(t)) { this.expr = this.expr.slice(0, -t.length); this._updateDisplay(); this._liveEval(); return; }
            }
            this.expr = this.expr.slice(0, -1);
            this._updateDisplay();
            this._liveEval();
        },

        toggleAngle() { this.angleMode = this.angleMode === 'DEG' ? 'RAD' : 'DEG'; this._liveEval(); },

        /* ── Memory ── */
        memClear() { this.memVal = 0; },
        memRecall() {
            if (this.justEvaluated) { this.expr = ''; this.justEvaluated = false; }
            this.expr += this._fmt(this.memVal);
            this._updateDisplay();
            this._liveEval();
        },
        memAdd() {
            const r = this._eval(this.expr);
            if (r !== null && !isNaN(r)) this.memVal += r;
        },

        /* ── Evaluate ── */
        evaluate() {
            if (this.expr.trim() === '') return;
            const result = this._eval(this.expr);
            if (result === null || isNaN(result) || !isFinite(result)) {
                this.exprLine = this.expr;
                this.mainLine = isFinite(result) ? 'Not a number' : 'Infinity';
                this.isError = true;
            } else {
                const fmt = this._fmt(result);
                this.history.push({ expr: this._displayExpr(this.expr), result: fmt });
                if (this.history.length > 20) this.history.shift();
                this.exprLine = this._displayExpr(this.expr) + ' =';
                this.mainLine = fmt;
                this.expr = fmt;
                this.isError = false;
            }
            this.liveResult = '';
            this.justEvaluated = true;
            this.invMode = false;
        },

        restoreHistory(h) {
            this.expr = h.result;
            this.mainLine = h.result;
            this.exprLine = h.expr + ' =';
            this.justEvaluated = true;
            this.isError = false;
        },

        /* ── Internal ── */
        _updateDisplay() {
            this.isError = false;
            this.mainLine = this._displayExpr(this.expr) || '0';
        },

        _liveEval() {
            if (this.expr.trim() === '') { this.liveResult = ''; return; }
            try {
                const r = this._eval(this.expr);
                if (r !== null && isFinite(r) && !isNaN(r)) {
                    this.liveResult = this._fmt(r);
                    if (!this.justEvaluated) this.exprLine = this._displayExpr(this.expr);
                } else {
                    this.liveResult = '';
                }
            } catch(e) { this.liveResult = ''; }
        },

        _displayExpr(e) {
            return e.replace(/\*/g,'×').replace(/\//g,'÷')
                    .replace(/asin/g,'sin⁻¹').replace(/acos/g,'cos⁻¹').replace(/atan/g,'tan⁻¹')
                    .replace(/sqrt/g,'√').replace(/cbrt/g,'∛').replace(/Math\.PI/g,'π');
        },

        _fmt(n) {
            if (Math.abs(n) >= 1e15 || (Math.abs(n) < 1e-9 && n !== 0)) {
                return n.toExponential(6).replace(/\.?0+e/, 'e');
            }
            const s = parseFloat(n.toPrecision(12)).toString();
            return s;
        },

        _eval(raw) {
            if (!raw || raw.trim() === '') return null;
            try {
                // --- tokenise & sanitize ---
                let e = raw;

                // safety: allow only math-safe chars
                if (/[^0-9+\-×÷*/^().!%πe\s_a-zA-Z]/.test(e)) return null;

                // symbolic operators
                e = e.replace(/×/g, '*').replace(/÷/g, '/').replace(/−/g, '-');

                // protect function names that contain 'e' before we replace Euler's e
                // use a temp ASCII-safe token strategy
                const fnMap = [
                    ['asin', '__ASIN__'], ['acos','__ACOS__'], ['atan','__ATAN__'],
                    ['sqrt','__SQRT__'], ['cbrt','__CBRT__'],
                    ['sinh','__SINH__'], ['cosh','__COSH__'], ['tanh','__TANH__'],
                    ['sin', '__SIN__'],  ['cos', '__COS__'],  ['tan', '__TAN__'],
                    ['log', '__LOG__'],  ['ln',  '__LN__'],   ['exp', '__EXP__'],
                ];
                fnMap.forEach(([k,v]) => { e = e.split(k).join(v); });

                // π → (Math.PI)
                e = e.replace(/π/g, '(Math.PI)');

                // standalone e (Euler's) — not preceded/followed by letter or digit
                e = e.replace(/(?<![A-Z_a-z0-9])e(?![A-Z_a-z0-9(])/g, '(Math.E)');

                // restore function names
                fnMap.forEach(([k,v]) => { e = e.split(v).join(k); });

                // handle factorial: n! → _fact(n) — must do before ^ processing
                e = e.replace(/(\d+(?:\.\d+)?|\))\s*!/g, '_fact($1)');

                // ^ → **
                e = e.replace(/\^/g, '**');

                // % as /100 — only when standalone (not part of larger expr handled elsewhere)
                // We'll handle % at expression level
                e = e.replace(/(\d+(?:\.\d+)?)\s*%/g, '($1/100)');

                // handle implicit multiplication: 2π → 2*(Math.PI), 2( → 2*(, )2 → )*2
                e = e.replace(/(\d)\s*\(/g, '$1*(');
                e = e.replace(/\)\s*(\d)/g, ')*$1');
                e = e.replace(/\)\s*\(/g, ')*(');

                const DEG = this.angleMode === 'DEG';
                const toR = DEG ? (x) => x * Math.PI / 180 : (x) => x;
                const frR = DEG ? (x) => x * 180 / Math.PI : (x) => x;

                const fn = new Function(
                    'Math','_fact','sin','cos','tan','asin','acos','atan',
                    'sqrt','cbrt','log','ln','exp','sinh','cosh','tanh',
                    '"use strict"; return (' + e + ');',
                    // Note: new Function with named params below
                );

                const _fact = (n) => {
                    n = Math.round(n);
                    if (n < 0 || n > 170) return NaN;
                    let r = 1; for (let i=2; i<=n; i++) r *= i; return r;
                };

                return fn(
                    Math, _fact,
                    (x) => Math.sin(toR(x)),
                    (x) => Math.cos(toR(x)),
                    (x) => Math.tan(toR(x)),
                    (x) => frR(Math.asin(x)),
                    (x) => frR(Math.acos(x)),
                    (x) => frR(Math.atan(x)),
                    Math.sqrt,
                    Math.cbrt,
                    Math.log10,
                    Math.log,
                    Math.exp,
                    Math.sinh,
                    Math.cosh,
                    Math.tanh,
                );
            } catch(err) {
                return null;
            }
        },

        _onKey(e) {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
            const k = e.key;
            if (k >= '0' && k <= '9') { e.preventDefault(); this.press(k); }
            else if (k === '+') { e.preventDefault(); this.press('+'); }
            else if (k === '-') { e.preventDefault(); this.press('−'); }
            else if (k === '*') { e.preventDefault(); this.press('×'); }
            else if (k === '/') { e.preventDefault(); this.press('÷'); }
            else if (k === '.') { e.preventDefault(); this.press('.'); }
            else if (k === '%') { e.preventDefault(); this.press('%'); }
            else if (k === '^') { e.preventDefault(); this.pressSpecial('pow'); }
            else if (k === '(') { e.preventDefault(); this.press('('); }
            else if (k === ')') { e.preventDefault(); this.press(')'); }
            else if (k === 'Enter' || k === '=') { e.preventDefault(); this.evaluate(); }
            else if (k === 'Escape') { e.preventDefault(); this.allClear(); }
            else if (k === 'Backspace') { e.preventDefault(); this.backspace(); }
        },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\scientific-calculator.blade.php ENDPATH**/ ?>