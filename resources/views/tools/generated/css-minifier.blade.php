@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')
<div class="min-h-screen bg-gray-50">

    {{-- Page Header --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                {{ $tool->icon }} {{ $tool->name }}
            </h1>
            <p class="text-gray-500 mt-2">{{ $tool->short_description }}</p>
        </div>
    </div>

    {{-- Tool Body --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10 space-y-5"
         x-data="cssMinifier()">

        {{-- ── Input Card ───────────────────────────────────────────── --}}
        <div class="card p-6">

            {{-- Header row --}}
            <div class="flex items-center justify-between mb-2">
                <label class="form-label mb-0">CSS Input</label>
                <span class="text-xs text-gray-400"
                      x-text="input.length ? input.length.toLocaleString() + ' chars' : ''"></span>
            </div>

            <textarea
                x-model="input"
                @input="error = ''; output = ''; stats = null"
                placeholder="Paste your CSS here…

body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif; /* base font */
    background-color: #fff;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
}"
                rows="14"
                class="form-input font-mono text-sm resize-y"
                spellcheck="false"
            ></textarea>

            {{-- Validation error --}}
            <p x-show="error" x-text="error" class="form-error mt-1"></p>

            {{-- ── Options row ──────────────────────────────────────── --}}
            <div class="flex flex-wrap items-center gap-x-6 gap-y-3 mt-4 pt-4 border-t border-gray-100">

                {{-- Remove comments toggle --}}
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <button
                        type="button"
                        role="switch"
                        :aria-checked="!keepComments"
                        @click="keepComments = !keepComments"
                        class="relative inline-flex h-5 w-9 flex-shrink-0 rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1"
                        :class="!keepComments ? 'bg-brand-600' : 'bg-gray-200'"
                    >
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform duration-200"
                              :class="!keepComments ? 'translate-x-4' : 'translate-x-0'"></span>
                    </button>
                    <span class="text-xs text-gray-600">Remove comments</span>
                    <span class="badge badge-gray text-xs">Default</span>
                </label>

                {{-- Preserve important comments note --}}
                <span class="text-xs text-gray-400">
                    <code class="text-brand-600">/*!</code> licence comments always kept
                </span>
            </div>

            {{-- ── Action Buttons ───────────────────────────────────── --}}
            <div class="flex flex-wrap gap-3 mt-4">
                <button type="button" @click="minify()" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Minify CSS
                </button>

                <button type="button" @click="clearAll()" class="btn btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Clear
                </button>
            </div>
        </div>

        {{-- ── Stats Bar ────────────────────────────────────────────── --}}
        <div x-show="stats" x-transition class="grid grid-cols-2 sm:grid-cols-4 gap-3">

            <div class="card p-4 text-center">
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Original</p>
                <p class="text-lg font-bold text-gray-900" x-text="stats ? formatBytes(stats.original) : ''"></p>
            </div>

            <div class="card p-4 text-center">
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Minified</p>
                <p class="text-lg font-bold text-brand-600" x-text="stats ? formatBytes(stats.minified) : ''"></p>
            </div>

            <div class="card p-4 text-center">
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Saved</p>
                <p class="text-lg font-bold text-emerald-600" x-text="stats ? formatBytes(stats.saved) : ''"></p>
            </div>

            <div class="card p-4 text-center">
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Reduction</p>
                <p class="text-lg font-bold"
                   :class="stats && stats.percent >= 30 ? 'text-emerald-600' : 'text-amber-500'"
                   x-text="stats ? stats.percent + '%' : ''"></p>
            </div>
        </div>

        {{-- ── Output Card ──────────────────────────────────────────── --}}
        <div x-show="output !== ''" x-transition class="card p-6">

            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-semibold text-gray-700">Minified Output</span>
                <div class="flex items-center gap-2">
                    <span class="badge badge-success">Minified</span>
                    <button type="button" @click="copyOutput()" class="btn btn-secondary btn-sm">
                        <svg x-show="!copied" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <svg x-show="copied" class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span x-text="copied ? 'Copied!' : 'Copy'"></span>
                    </button>
                    <button type="button" @click="download()" class="btn btn-secondary btn-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        .min.css
                    </button>
                </div>
            </div>

            {{-- Output textarea (read-only, selectable) --}}
            <textarea
                x-model="output"
                readonly
                rows="8"
                class="form-input font-mono text-sm resize-y bg-gray-50 text-gray-700 select-all cursor-text"
                spellcheck="false"
            ></textarea>

            <p class="text-xs text-gray-400 mt-2">
                Click inside the output box to select all, or use the Copy button.
            </p>
        </div>

        {{-- ── Info Card ────────────────────────────────────────────── --}}
        <div class="card p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">How CSS Minification Works</h3>
            <ul class="space-y-2 text-sm text-gray-500">
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5 flex-shrink-0">•</span>
                    Removes comments, newlines, tabs, and redundant whitespace.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5 flex-shrink-0">•</span>
                    Strips spaces around <code class="text-brand-600 bg-brand-50 px-1 rounded">{ } ; : , &gt; + ~</code>
                    and the final semicolon before <code class="text-brand-600 bg-brand-50 px-1 rounded">}</code>.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5 flex-shrink-0">•</span>
                    Protects string literals and <code class="text-brand-600 bg-brand-50 px-1 rounded">calc()</code>
                    expressions — spaces inside <code class="text-brand-600 bg-brand-50 px-1 rounded">calc()</code>
                    are required by the CSS spec.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5 flex-shrink-0">•</span>
                    <code class="text-brand-600 bg-brand-50 px-1 rounded">/*!</code> licence/important
                    comments are always preserved regardless of the toggle setting.
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-brand-400 mt-0.5 flex-shrink-0">•</span>
                    Everything runs in your browser — your CSS is never sent to any server.
                </li>
            </ul>
        </div>

        {{-- Related Tools --}}
        @if($relatedTools->count())
        <div>
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

    </div>{{-- /tool body --}}
</div>
@endsection

@push('scripts')
<script>
/*
 * Pure JavaScript CSS Minifier.
 *
 * Strategy:
 *  1. Protect quoted strings and calc() so their content is not altered.
 *  2. Protect important comments (/ *! ... * /) — always kept.
 *  3. Optionally remove or protect regular comments.
 *  4. Collapse whitespace → single spaces.
 *  5. Strip spaces around CSS structural tokens.
 *  6. Remove the final semicolon before every }.
 *  7. Restore all protected regions.
 *
 * Everything runs client-side; nothing is transmitted.
 */
function cssMinify(input, opts) {
    const keepComments = opts && opts.keepComments;

    let css = input;

    /* ── Placeholder helpers ────────────────────────────────────────── */
    const store   = [];                              // protected originals
    const mark    = n  => `\x00M${n}M\x00`;         // unique placeholder
    const protect = fn =>
        css.replace(fn, m => { store.push(m); return mark(store.length - 1); });

    /* ── 1. Protect double-quoted strings ───────────────────────────── */
    css = protect(/"(?:[^"\\]|\\.)*"/g);

    /* ── 2. Protect single-quoted strings ───────────────────────────── */
    css = protect(/'(?:[^'\\]|\\.)*'/g);

    /* ── 3. Always protect important comments  / *! ... * / ─────────── */
    css = protect(/\/\*![\s\S]*?\*\//g);

    /* ── 4. Regular comments ─────────────────────────────────────────── */
    if (keepComments) {
        css = protect(/\/\*[\s\S]*?\*\//g);
    } else {
        css = css.replace(/\/\*[\s\S]*?\*\//g, '');
    }

    /* ── 5. Protect calc() — + and - need surrounding whitespace ─────── */
    /*      Handles nested parens like calc(100% - (20px + 5px))          */
    css = protect(/calc\((?:[^()]*|\((?:[^()]*|\([^()]*\))*\))*\)/gi);

    /* ── 6. Collapse all whitespace runs to a single space ───────────── */
    css = css.replace(/\s+/g, ' ');

    /* ── 7. Strip spaces around CSS structural characters ────────────── */
    css = css
        .replace(/ *\{ */g, '{')
        .replace(/ *\} */g, '}')
        .replace(/ *; */g, ';')
        .replace(/ *: */g, ':')
        .replace(/ *, */g, ',')
        .replace(/ *> */g, '>')
        .replace(/ *\+ */g, '+')
        .replace(/ *~ */g, '~');

    /* ── 8. Remove trailing semicolon before every } ─────────────────── */
    css = css.replace(/;}/g, '}');

    /* ── 9. Trim leading / trailing whitespace ───────────────────────── */
    css = css.trim();

    /* ── 10. Restore all protected regions ───────────────────────────── */
    store.forEach((original, i) => {
        /*
         * Use a replacer function to avoid special-character issues
         * with $ in replacement strings (e.g. $& in content values).
         */
        css = css.replace(mark(i), () => original);
    });

    return css;
}

/* ── Alpine.js component ─────────────────────────────────────────────── */
function cssMinifier() {
    return {
        input:        '',
        output:       '',
        error:        '',
        stats:        null,
        keepComments: false,   // "Remove comments" toggle is ON by default
        copied:       false,
        _copyTimer:   null,

        minify() {
            const raw = this.input.trim();

            if (!raw) {
                this.error  = 'Please paste some CSS before minifying.';
                this.output = '';
                this.stats  = null;
                return;
            }

            this.error = '';

            try {
                const result = cssMinify(raw, { keepComments: this.keepComments });

                this.output = result;

                /* Byte sizes (UTF-8 via TextEncoder for accuracy) */
                const enc      = new TextEncoder();
                const origSize = enc.encode(raw).length;
                const minSize  = enc.encode(result).length;
                const saved    = origSize - minSize;
                const pct      = origSize > 0
                    ? Math.round((saved / origSize) * 100)
                    : 0;

                this.stats = {
                    original: origSize,
                    minified: minSize,
                    saved:    Math.max(0, saved),
                    percent:  Math.max(0, pct),
                };

            } catch (err) {
                this.error  = 'Minification error: ' + err.message;
                this.output = '';
                this.stats  = null;
            }
        },

        async copyOutput() {
            if (!this.output) return;
            try {
                await navigator.clipboard.writeText(this.output);
            } catch {
                const el = Object.assign(document.createElement('textarea'), {
                    value: this.output,
                    style: 'position:fixed;opacity:0;pointer-events:none',
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

        download() {
            if (!this.output) return;
            const blob = new Blob([this.output], { type: 'text/css;charset=utf-8' });
            const url  = URL.createObjectURL(blob);
            Object.assign(document.createElement('a'), {
                href: url, download: 'styles.min.css'
            }).click();
            URL.revokeObjectURL(url);
        },

        clearAll() {
            this.input        = '';
            this.output       = '';
            this.error        = '';
            this.stats        = null;
            this.copied       = false;
            this.keepComments = false;
        },

        /* Human-readable byte size */
        formatBytes(bytes) {
            if (bytes === 0)    return '0 B';
            if (bytes < 1024)   return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
        },
    };
}
</script>
@endpush
