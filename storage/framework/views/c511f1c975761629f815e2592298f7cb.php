<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50">

    
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                <?php echo e($tool->icon); ?> <?php echo e($tool->name); ?>

            </h1>
            <p class="text-gray-500 mt-2"><?php echo e($tool->short_description); ?></p>
        </div>
    </div>

    
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10" x-data="sqlFormatterTool()">

        
        <div class="card p-4 mb-5 flex flex-wrap items-center gap-4">
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700">Keywords:</label>
                <select x-model="keywordCase" class="form-input py-1.5 text-sm w-auto">
                    <option value="upper">UPPERCASE</option>
                    <option value="lower">lowercase</option>
                    <option value="preserve">Preserve</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700">Indent:</label>
                <select x-model="indentSize" class="form-input py-1.5 text-sm w-auto">
                    <option value="2">2 spaces</option>
                    <option value="4">4 spaces</option>
                    <option value="tab">Tab</option>
                </select>
            </div>
        </div>

        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">

            
            <div class="card p-5">
                <div class="flex items-center justify-between mb-2">
                    <label class="form-label mb-0">SQL Input</label>
                    <span class="text-xs text-gray-400" x-text="inputLines + ' lines'"></span>
                </div>
                <textarea
                    x-model="input"
                    @input="onInput()"
                    placeholder="Paste your SQL query here…"
                    rows="14"
                    class="form-input resize-y font-mono text-sm"
                    spellcheck="false"
                ></textarea>
                <p x-show="error" x-text="error" class="form-error"></p>
            </div>

            
            <div class="card p-5">
                <div class="flex items-center justify-between mb-2">
                    <label class="form-label mb-0">SQL Output</label>
                    <span class="text-xs text-gray-400" x-text="outputLines + ' lines'"></span>
                </div>
                <textarea
                    x-ref="output"
                    :value="output"
                    readonly
                    placeholder="Formatted SQL will appear here…"
                    rows="14"
                    class="form-input resize-y font-mono text-sm bg-gray-50 cursor-default"
                    spellcheck="false"
                ></textarea>
            </div>
        </div>

        
        <div class="flex flex-wrap gap-3 mb-5">
            <button @click="formatSQL()" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 10h16M4 14h10M4 18h6"/>
                </svg>
                Format SQL
            </button>

            <button @click="minifySQL()" class="btn btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                </svg>
                Minify SQL
            </button>

            <button @click="loadSample()" class="btn btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Sample SQL
            </button>

            <button @click="copyOutput()" x-show="output" class="btn btn-secondary">
                <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                <svg x-show="copied" class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                <span x-text="copied ? 'Copied!' : 'Copy Output'"></span>
            </button>

            <button @click="clearAll()" class="btn btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Clear
            </button>
        </div>

        
        <div x-show="output" x-transition class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-5">
            <div class="card p-4 text-center">
                <div class="text-2xl font-bold text-gray-900" x-text="stats.inputChars"></div>
                <div class="text-xs text-gray-500 mt-1">Input chars</div>
            </div>
            <div class="card p-4 text-center">
                <div class="text-2xl font-bold text-brand-600" x-text="stats.outputChars"></div>
                <div class="text-xs text-gray-500 mt-1">Output chars</div>
            </div>
            <div class="card p-4 text-center">
                <div class="text-2xl font-bold text-gray-900" x-text="stats.inputLines"></div>
                <div class="text-xs text-gray-500 mt-1">Input lines</div>
            </div>
            <div class="card p-4 text-center">
                <div class="text-2xl font-bold text-brand-600" x-text="stats.outputLines"></div>
                <div class="text-xs text-gray-500 mt-1">Output lines</div>
            </div>
        </div>

        
        <div class="card p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">About SQL Formatter</h3>
            <ul class="space-y-2 text-sm text-gray-500">
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    Formats SQL by adding proper indentation, line breaks, and consistent keyword casing.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    Supports SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, and more.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5">•</span>
                    Minify mode removes all unnecessary whitespace for compact storage or transfer.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-amber-500 mt-0.5">🔒</span>
                    Your SQL is processed entirely in your browser — nothing is sent to any server.
                </li>
            </ul>
        </div>

        
        <?php if($relatedTools->count()): ?>
        <div class="mt-8">
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
/* ─────────────────────────────────────────────────────────────────────────
 * Pure-JavaScript SQL Formatter & Minifier
 * No external dependencies — runs entirely client-side.
 * ───────────────────────────────────────────────────────────────────────── */

/* ── Tokenizer ─────────────────────────────────────────────────────────── */
function tokenize(sql) {
    const tokens = [];
    let i = 0;
    const len = sql.length;

    while (i < len) {
        // Whitespace
        if (/\s/.test(sql[i])) {
            let s = '';
            while (i < len && /\s/.test(sql[i])) s += sql[i++];
            tokens.push({ type: 'ws', val: s });
            continue;
        }

        // Line comment  --
        if (sql[i] === '-' && sql[i+1] === '-') {
            let s = '';
            while (i < len && sql[i] !== '\n') s += sql[i++];
            tokens.push({ type: 'comment', val: s });
            continue;
        }

        // Block comment  /* … */
        if (sql[i] === '/' && sql[i+1] === '*') {
            let s = '/*';
            i += 2;
            while (i < len && !(sql[i-1] === '*' && sql[i] === '/')) s += sql[i++];
            s += sql[i++]; // closing /
            tokens.push({ type: 'comment', val: s });
            continue;
        }

        // String literals  ' … '  (handles '' escape)
        if (sql[i] === "'") {
            let s = "'";
            i++;
            while (i < len) {
                if (sql[i] === "'" && sql[i+1] === "'") { s += "''"; i += 2; }
                else if (sql[i] === "'") { s += "'"; i++; break; }
                else s += sql[i++];
            }
            tokens.push({ type: 'str', val: s });
            continue;
        }

        // Quoted identifiers  " … "  or  ` … `
        if (sql[i] === '"' || sql[i] === '`') {
            const q = sql[i];
            let s = q;
            i++;
            while (i < len && sql[i] !== q) s += sql[i++];
            s += q; i++;
            tokens.push({ type: 'qid', val: s });
            continue;
        }

        // Numbers
        if (/[0-9]/.test(sql[i]) || (sql[i] === '.' && /[0-9]/.test(sql[i+1]))) {
            let s = '';
            while (i < len && /[0-9.eE+\-]/.test(sql[i])) s += sql[i++];
            tokens.push({ type: 'num', val: s });
            continue;
        }

        // Words / keywords / identifiers
        if (/[a-zA-Z_$#@]/.test(sql[i])) {
            let s = '';
            while (i < len && /[a-zA-Z0-9_$#@]/.test(sql[i])) s += sql[i++];
            tokens.push({ type: 'word', val: s });
            continue;
        }

        // Single-character tokens
        if (sql[i] === '(') { tokens.push({ type: 'open',  val: '(' }); i++; continue; }
        if (sql[i] === ')') { tokens.push({ type: 'close', val: ')' }); i++; continue; }
        if (sql[i] === ',') { tokens.push({ type: 'comma', val: ',' }); i++; continue; }
        if (sql[i] === ';') { tokens.push({ type: 'semi',  val: ';' }); i++; continue; }
        if (sql[i] === '.') { tokens.push({ type: 'dot',   val: '.' }); i++; continue; }

        // Operators and everything else
        tokens.push({ type: 'op', val: sql[i++] });
    }

    return tokens;
}

/* ── Merge compound keywords ───────────────────────────────────────────── */
const COMPOUNDS = [
    'GROUP BY','ORDER BY','PARTITION BY','UNION ALL','UNION DISTINCT',
    'LEFT JOIN','RIGHT JOIN','INNER JOIN','OUTER JOIN','FULL JOIN',
    'CROSS JOIN','LEFT OUTER JOIN','RIGHT OUTER JOIN','FULL OUTER JOIN',
    'NOT IN','NOT LIKE','NOT EXISTS','NOT BETWEEN','IS NOT','IS NULL','IS NOT NULL',
    'INSERT INTO','DELETE FROM','CREATE TABLE','CREATE INDEX','CREATE VIEW',
    'ALTER TABLE','DROP TABLE','DROP INDEX','ON DUPLICATE KEY UPDATE',
    'PRIMARY KEY','FOREIGN KEY','UNIQUE KEY','REFERENCES',
    'CASE WHEN','ELSE','END','WITH ROLLUP','WITH CUBE',
];

function mergeCompound(tokens) {
    // Work only on non-ws tokens for compound detection, then rebuild
    const out = [];
    const words = tokens.filter(t => t.type === 'word');
    // Flatten to array, try greedy matching of compounds
    let i = 0;
    const flat = tokens.filter(t => t.type !== 'ws');
    const wsMap = []; // positions of ws tokens between flat tokens
    // Simpler approach: build flat list, try compound merges on words
    const merged = [];
    let j = 0;
    while (j < flat.length) {
        if (flat[j].type === 'word') {
            let matched = false;
            // Try longest compound first
            for (const c of COMPOUNDS) {
                const parts = c.split(' ');
                if (parts.length <= flat.length - j) {
                    let ok = true;
                    for (let k = 0; k < parts.length; k++) {
                        if (flat[j+k].type !== 'word' || flat[j+k].val.toUpperCase() !== parts[k]) {
                            ok = false; break;
                        }
                    }
                    if (ok) {
                        merged.push({ type: 'word', val: c, compound: true });
                        j += parts.length;
                        matched = true;
                        break;
                    }
                }
            }
            if (!matched) { merged.push(flat[j]); j++; }
        } else { merged.push(flat[j]); j++; }
    }
    return merged;
}

/* ── Keyword sets ──────────────────────────────────────────────────────── */
// Top-level clauses: go on their own line at base indent
const TOP_CLAUSE = new Set([
    'SELECT','FROM','WHERE','GROUP BY','HAVING','ORDER BY','LIMIT','OFFSET',
    'UNION','UNION ALL','UNION DISTINCT','EXCEPT','INTERSECT',
    'INSERT INTO','INTO','VALUES','UPDATE','SET',
    'DELETE','DELETE FROM','RETURNING',
    'CREATE TABLE','CREATE INDEX','CREATE VIEW','CREATE OR REPLACE VIEW',
    'ALTER TABLE','DROP TABLE','DROP INDEX',
    'WITH',
]);

// Sub-clauses: indented within a clause
const SUB_CLAUSE = new Set([
    'LEFT JOIN','RIGHT JOIN','INNER JOIN','OUTER JOIN','FULL JOIN',
    'CROSS JOIN','LEFT OUTER JOIN','RIGHT OUTER JOIN','FULL OUTER JOIN',
    'JOIN','ON','USING',
    'CASE','WHEN','THEN','ELSE','END',
    'NOT IN','IN','EXISTS','NOT EXISTS',
]);

// Logical operators at depth 0: new line same indent as previous clause
const LOGICAL = new Set(['AND','OR','NOT','XOR']);

/* ── Formatter ─────────────────────────────────────────────────────────── */
function formatSQL(sql, opts) {
    opts = opts || {};
    const kwCase   = opts.keywordCase || 'upper';
    const indentCh = opts.indentSize === 'tab' ? '\t' : ' '.repeat(parseInt(opts.indentSize) || 4);

    const applyCase = (w) => {
        if (kwCase === 'upper')    return w.toUpperCase();
        if (kwCase === 'lower')    return w.toLowerCase();
        return w;
    };

    const tokens = mergeCompound(tokenize(sql));

    let out    = '';
    let depth  = 0; // paren depth for sub-expressions
    let col    = 0; // column position (for newline decisions)

    const indent = (d) => indentCh.repeat(Math.max(0, d));

    const newLine = (d) => {
        out += '\n' + indent(d);
        col = indent(d).length;
    };

    const append = (s) => {
        out += s;
        col += s.length;
    };

    let prevType = '';
    let prevVal  = '';
    let i = 0;

    while (i < tokens.length) {
        const tok = tokens[i];

        if (tok.type === 'comment') {
            // Comments appear on their own line
            if (out.length && !out.endsWith('\n')) newLine(depth);
            append(tok.val);
            newLine(depth);
            i++; continue;
        }

        if (tok.type === 'semi') {
            append(';');
            newLine(0);
            depth = 0;
            prevType = 'semi'; prevVal = ';';
            i++; continue;
        }

        if (tok.type === 'open') {
            // Check if next meaningful token is SELECT → subquery
            let next = i + 1;
            while (next < tokens.length && tokens[next].type === 'ws') next++;
            const nextIsSelect = next < tokens.length &&
                tokens[next].type === 'word' &&
                tokens[next].val.toUpperCase() === 'SELECT';

            if (nextIsSelect) {
                append('(');
                depth++;
                newLine(depth);
            } else {
                append('(');
                depth++;
            }
            prevType = 'open'; prevVal = '(';
            i++; continue;
        }

        if (tok.type === 'close') {
            depth = Math.max(0, depth - 1);
            // If subquery closing paren, put on new line
            if (out.length && !out.endsWith('\n') && out.endsWith('\n' + indent(depth + 1))) {
                // already at right indent
            }
            // Check if previous line was a subquery
            if (out.trimEnd().endsWith(')') || /\S/.test(out[out.length-1])) {
                // just append
            }
            append(')');
            prevType = 'close'; prevVal = ')';
            i++; continue;
        }

        if (tok.type === 'comma') {
            if (depth === 0) {
                append(',');
                newLine(depth + 1);
            } else {
                append(', ');
            }
            prevType = 'comma'; prevVal = ',';
            i++; continue;
        }

        if (tok.type === 'word') {
            const upper = tok.val.toUpperCase();
            const cased = applyCase(tok.val);

            if (depth === 0) {
                if (TOP_CLAUSE.has(upper)) {
                    if (out.length && !out.endsWith('\n\n') && out !== '') {
                        // Add blank line before major clause (except first)
                        if (prevType !== 'semi' && prevVal !== '') {
                            if (!out.endsWith('\n')) newLine(0);
                        }
                    }
                    append(cased);
                    newLine(1);
                    prevType = 'clause'; prevVal = upper;
                    i++; continue;
                }

                if (SUB_CLAUSE.has(upper)) {
                    if (!out.endsWith('\n')) newLine(0);
                    append(cased);
                    newLine(1);
                    prevType = 'clause'; prevVal = upper;
                    i++; continue;
                }

                if (LOGICAL.has(upper)) {
                    if (!out.endsWith('\n')) newLine(1);
                    append(cased + ' ');
                    prevType = 'logical'; prevVal = upper;
                    i++; continue;
                }
            }

            // Inside parens or normal word
            if (prevType === 'open') {
                append(cased);
            } else if (prevType === 'dot') {
                append(cased);
            } else if (prevType === 'comma' && depth > 0) {
                // already added space in comma handler
                append(cased);
            } else if (prevType === 'clause' || prevType === 'logical') {
                append(cased);
            } else {
                if (out.length && !out.endsWith(' ') && !out.endsWith('\n')
                    && prevType !== 'dot') {
                    append(' ');
                }
                append(cased);
            }
            prevType = 'word'; prevVal = upper;
            i++; continue;
        }

        if (tok.type === 'dot') {
            append('.');
            prevType = 'dot'; prevVal = '.';
            i++; continue;
        }

        if (tok.type === 'num' || tok.type === 'str' || tok.type === 'qid') {
            if (prevType !== 'open' && prevType !== 'dot' && prevType !== 'comma'
                && !out.endsWith(' ') && !out.endsWith('\n') && !out.endsWith('(')) {
                append(' ');
            }
            append(tok.val);
            prevType = tok.type; prevVal = tok.val;
            i++; continue;
        }

        if (tok.type === 'op') {
            const v = tok.val;
            // Comparison / arithmetic operators get spaces
            if ('=<>!+-*/%^&|'.includes(v) && v !== '.' ) {
                if (!out.endsWith(' ') && !out.endsWith('\n')) append(' ');
                append(v);
                // peek for two-char ops: >=, <=, !=, <>
                if (i + 1 < tokens.length && tokens[i+1].type === 'op'
                    && '=><!'.includes(tokens[i+1].val)) {
                    append(tokens[i+1].val);
                    i++;
                }
                append(' ');
            } else {
                append(v);
            }
            prevType = 'op'; prevVal = v;
            i++; continue;
        }

        i++;
    }

    // Clean up: collapse multiple blank lines, trim
    return out
        .replace(/\n{3,}/g, '\n\n')
        .replace(/[ \t]+\n/g, '\n')
        .replace(/ +/g, ' ')
        .trim();
}

/* ── Minifier ──────────────────────────────────────────────────────────── */
function minifySQL(sql) {
    const placeholders = [];
    let ph = 0;

    // Protect string literals
    let s = sql.replace(/'(?:''|[^'])*'/g, (m) => {
        placeholders.push(m);
        return '\x00S' + ph++ + '\x00';
    });

    // Protect quoted identifiers
    s = s.replace(/`[^`]*`|"[^"]*"/g, (m) => {
        placeholders.push(m);
        return '\x00S' + ph++ + '\x00';
    });

    // Remove block comments (keep /*! ... */ MySQL hints)
    s = s.replace(/\/\*(?!!)[\s\S]*?\*\//g, ' ');
    // Remove line comments
    s = s.replace(/--[^\n]*/g, ' ');

    // Collapse whitespace
    s = s.replace(/\s+/g, ' ').trim();

    // Restore placeholders
    s = s.replace(/\x00S(\d+)\x00/g, (_, i) => placeholders[parseInt(i)]);

    return s;
}

/* ── Alpine.js Component ───────────────────────────────────────────────── */
function sqlFormatterTool() {
    const SAMPLE = `SELECT u.id, u.name, u.email, COUNT(o.id) AS order_count, SUM(o.total) AS total_spent FROM users u LEFT JOIN orders o ON u.id = o.user_id LEFT JOIN user_profiles p ON u.id = p.user_id WHERE u.created_at >= '2024-01-01' AND u.status = 'active' AND (u.role = 'customer' OR u.role = 'vip') GROUP BY u.id, u.name, u.email HAVING COUNT(o.id) > 0 ORDER BY total_spent DESC LIMIT 50;`;

    return {
        input:       '',
        output:      '',
        error:       '',
        keywordCase: 'upper',
        indentSize:  '4',
        copied:      false,
        _copyTimer:  null,

        get inputLines()  { return this.input  ? this.input.split('\n').length  : 0; },
        get outputLines() { return this.output ? this.output.split('\n').length : 0; },

        get stats() {
            return {
                inputChars:  this.input.length,
                outputChars: this.output.length,
                inputLines:  this.inputLines,
                outputLines: this.outputLines,
            };
        },

        onInput() {
            this.error = '';
        },

        formatSQL() {
            if (!this.input.trim()) {
                this.error = 'Please enter a SQL query to format.';
                return;
            }
            this.error = '';
            try {
                this.output = formatSQL(this.input, {
                    keywordCase: this.keywordCase,
                    indentSize:  this.indentSize,
                });
            } catch (e) {
                this.error = 'Failed to format SQL: ' + e.message;
            }
        },

        minifySQL() {
            if (!this.input.trim()) {
                this.error = 'Please enter a SQL query to minify.';
                return;
            }
            this.error = '';
            try {
                this.output = minifySQL(this.input);
            } catch (e) {
                this.error = 'Failed to minify SQL: ' + e.message;
            }
        },

        loadSample() {
            this.input  = SAMPLE;
            this.output = '';
            this.error  = '';
        },

        async copyOutput() {
            if (!this.output) return;
            try {
                await navigator.clipboard.writeText(this.output);
            } catch {
                const el = Object.assign(document.createElement('textarea'), {
                    value: this.output,
                    style: 'position:fixed;opacity:0;pointer-events:none'
                });
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
            }
            this.copied = true;
            clearTimeout(this._copyTimer);
            this._copyTimer = setTimeout(() => { this.copied = false; }, 2000);
        },

        clearAll() {
            this.input  = '';
            this.output = '';
            this.error  = '';
            this.copied = false;
        },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\sql-formatter.blade.php ENDPATH**/ ?>