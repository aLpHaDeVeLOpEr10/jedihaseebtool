@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('head')
{{-- marked.js: fast, battle-tested Markdown → HTML parser (runs client-side only) --}}
<script src="https://cdn.jsdelivr.net/npm/marked@12/marked.min.js"></script>
<style>
    /* Prevent the prose preview from overflowing its container */
    .md-preview { overflow-y: auto; }
    .md-preview img { max-width: 100%; }

    /* Thin, branded scrollbar inside editor / preview panels */
    .panel-scroll::-webkit-scrollbar { width: 4px; }
    .panel-scroll::-webkit-scrollbar-track { background: transparent; }
    .panel-scroll::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 9999px; }
    .panel-scroll::-webkit-scrollbar-thumb:hover { background: #c7d2fe; }

    /* Toolbar button active / hover state */
    .tb-btn {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 0.25rem 0.45rem; border-radius: 0.5rem; font-size: 0.75rem;
        font-weight: 600; color: #6b7280; cursor: pointer; user-select: none;
        transition: background 0.15s, color 0.15s;
        background: transparent; border: none; line-height: 1.2;
        font-family: inherit;
    }
    .tb-btn:hover  { background: #e0e7ff; color: #4f46e5; }
    .tb-btn:active { background: #c7d2fe; color: #3730a3; }
    .tb-sep { width: 1px; height: 20px; background: #e5e7eb; flex-shrink: 0; }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col">

    {{-- Page Header --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                {{ $tool->icon }} {{ $tool->name }}
            </h1>
            <p class="text-gray-500 mt-1">{{ $tool->short_description }}</p>
        </div>
    </div>

    {{-- Tool Body --}}
    <div class="flex-1 flex flex-col max-w-6xl w-full mx-auto px-4 sm:px-6 py-5 gap-4"
         x-data="markdownEditor()"
         x-init="boot()">

        {{-- ── Toolbar ──────────────────────────────────────────────── --}}
        <div class="card px-3 py-2 flex flex-wrap items-center gap-1">

            {{-- Headings --}}
            <button type="button" @click="insert('# ','',$event)"      class="tb-btn" title="Heading 1">H1</button>
            <button type="button" @click="insert('## ','',$event)"     class="tb-btn" title="Heading 2">H2</button>
            <button type="button" @click="insert('### ','',$event)"    class="tb-btn" title="Heading 3">H3</button>
            <div class="tb-sep mx-0.5"></div>

            {{-- Inline formatting --}}
            <button type="button" @click="wrap('**','**',$event)"      class="tb-btn font-bold"   title="Bold (Ctrl+B)"><span class="text-xs">B</span></button>
            <button type="button" @click="wrap('*','*',$event)"        class="tb-btn italic"      title="Italic (Ctrl+I)"><span class="text-xs">I</span></button>
            <button type="button" @click="wrap('~~','~~',$event)"      class="tb-btn line-through" title="Strikethrough"><span class="text-xs">S</span></button>
            <button type="button" @click="wrap('`','`',$event)"        class="tb-btn font-mono"   title="Inline code"><span class="text-xs">{'`'}</span></button>
            <div class="tb-sep mx-0.5"></div>

            {{-- Block-level --}}
            <button type="button" @click="prependLine('> ',$event)"    class="tb-btn" title="Blockquote">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </button>
            <button type="button" @click="insertBlock('```\n','```',$event)" class="tb-btn font-mono text-xs" title="Code block">{ }</button>
            <button type="button" @click="insertLine('\n---\n',$event)" class="tb-btn text-xs" title="Horizontal rule">—</button>
            <div class="tb-sep mx-0.5"></div>

            {{-- Lists --}}
            <button type="button" @click="prependLine('- ',$event)"    class="tb-btn" title="Unordered list">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <button type="button" @click="prependLine('1. ',$event)"   class="tb-btn" title="Ordered list">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </button>
            <div class="tb-sep mx-0.5"></div>

            {{-- Link / Image --}}
            <button type="button" @click="wrapLink($event)"  class="tb-btn" title="Link (Ctrl+K)">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
            </button>
            <button type="button" @click="wrapImage($event)" class="tb-btn" title="Image">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </button>
            <button type="button" @click="insertTable($event)" class="tb-btn text-xs" title="Table">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18M10 3v18M6 3h12a3 3 0 013 3v12a3 3 0 01-3 3H6a3 3 0 01-3-3V6a3 3 0 013-3z"/></svg>
            </button>

            {{-- Spacer --}}
            <div class="flex-1"></div>

            {{-- View toggles (mobile) --}}
            <div class="flex lg:hidden gap-1">
                <button type="button"
                        @click="view='write'"
                        :class="view==='write' ? 'bg-brand-100 text-brand-700' : ''"
                        class="tb-btn">Write</button>
                <button type="button"
                        @click="view='preview'"
                        :class="view==='preview' ? 'bg-brand-100 text-brand-700' : ''"
                        class="tb-btn">Preview</button>
            </div>
        </div>

        {{-- ── Editor / Preview split ───────────────────────────────── --}}
        <div class="flex-1 grid grid-cols-1 lg:grid-cols-2 gap-4" style="min-height:480px; height:calc(100vh - 320px); max-height:800px;">

            {{-- Editor panel --}}
            <div class="card flex flex-col overflow-hidden"
                 :class="view==='preview' ? 'hidden lg:flex' : 'flex'">

                <div class="flex items-center justify-between px-4 py-2 border-b border-gray-100 flex-shrink-0">
                    <span class="text-xs font-semibold text-gray-400 tracking-wider uppercase">Markdown</span>
                    <span class="text-xs text-gray-400" x-text="statsLabel"></span>
                </div>

                {{-- The textarea — @keydown handlers for Tab + Ctrl shortcuts --}}
                <textarea
                    x-ref="editor"
                    x-model="md"
                    @input="update()"
                    @keydown.tab.prevent="insertTab()"
                    @keydown.ctrl.b.prevent="wrap('**','**')"
                    @keydown.ctrl.i.prevent="wrap('*','*')"
                    @keydown.ctrl.k.prevent="wrapLink()"
                    class="flex-1 p-4 resize-none border-0 outline-none focus:ring-0 font-mono text-sm text-gray-800 leading-relaxed panel-scroll bg-transparent"
                    placeholder="Start writing Markdown here…

# Heading 1
## Heading 2

**Bold**, *italic*, ~~strikethrough~~, `inline code`

- List item
1. Numbered item

> Blockquote

[Link text](https://example.com)"
                    spellcheck="false"
                ></textarea>
            </div>

            {{-- Preview panel --}}
            <div class="card flex flex-col overflow-hidden"
                 :class="view==='write' ? 'hidden lg:flex' : 'flex'">

                <div class="flex items-center justify-between px-4 py-2 border-b border-gray-100 flex-shrink-0">
                    <span class="text-xs font-semibold text-gray-400 tracking-wider uppercase">Preview</span>
                    <span x-show="md" class="badge badge-success text-xs">Live</span>
                </div>

                {{-- Live HTML preview --}}
                <div class="flex-1 overflow-y-auto panel-scroll">
                    {{-- Empty state --}}
                    <div x-show="!md" class="flex flex-col items-center justify-center h-full text-gray-300 gap-3 p-8">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <p class="text-sm text-center">Your formatted preview will<br>appear here as you type.</p>
                    </div>

                    {{-- Rendered markdown --}}
                    <div
                        x-show="md"
                        x-html="html"
                        class="md-preview prose prose-gray prose-sm max-w-none p-5
                               prose-headings:font-semibold prose-headings:text-gray-900
                               prose-a:text-brand-600 prose-a:no-underline hover:prose-a:underline
                               prose-code:text-brand-700 prose-code:bg-brand-50 prose-code:px-1 prose-code:py-0.5 prose-code:rounded prose-code:before:content-none prose-code:after:content-none
                               prose-pre:bg-gray-900 prose-pre:text-gray-100
                               prose-blockquote:border-brand-300 prose-blockquote:text-gray-500
                               prose-img:rounded-xl prose-img:shadow-sm
                               prose-table:text-sm"
                    ></div>
                </div>
            </div>
        </div>

        {{-- ── Bottom Action Bar ────────────────────────────────────── --}}
        <div class="flex flex-wrap items-center justify-between gap-3">

            {{-- Stats --}}
            <p class="text-xs text-gray-400" x-text="statsLabel"></p>

            {{-- Action buttons --}}
            <div class="flex flex-wrap gap-2">

                {{-- Copy Markdown --}}
                <button type="button" @click="copyMD()" :disabled="!md" class="btn btn-secondary btn-sm">
                    <svg x-show="copied !== 'md'" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <svg x-show="copied === 'md'" class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span x-text="copied === 'md' ? 'Copied!' : 'Copy MD'"></span>
                </button>

                {{-- Copy HTML --}}
                <button type="button" @click="copyHTML()" :disabled="!md" class="btn btn-secondary btn-sm">
                    <svg x-show="copied !== 'html'" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                    </svg>
                    <svg x-show="copied === 'html'" class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span x-text="copied === 'html' ? 'Copied!' : 'Copy HTML'"></span>
                </button>

                {{-- Download .md --}}
                <button type="button" @click="downloadMD()" :disabled="!md" class="btn btn-secondary btn-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    .md
                </button>

                {{-- Download .html --}}
                <button type="button" @click="downloadHTML()" :disabled="!md" class="btn btn-secondary btn-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    .html
                </button>

                {{-- Clear --}}
                <button type="button" @click="clearAll()" :disabled="!md" class="btn btn-secondary btn-sm text-red-500 hover:text-red-700 hover:bg-red-50">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Clear
                </button>
            </div>
        </div>

        {{-- Auto-save notice --}}
        <p class="text-xs text-gray-400 text-right -mt-2">
            ✓ Draft auto-saved in your browser
        </p>

    </div>{{-- /tool body --}}
</div>
@endsection

@push('scripts')
<script>
/* ── Configure marked.js ────────────────────────────────────────────── */
if (typeof marked !== 'undefined') {
    marked.use({
        gfm: true,      // GitHub Flavoured Markdown (tables, strikethrough, task lists)
        breaks: true,   // Convert \n to <br> inside paragraphs
    });
}

/* ── Alpine.js component ─────────────────────────────────────────────── */
function markdownEditor() {
    return {
        md:     '',       // raw Markdown text
        html:   '',       // rendered HTML string (bound with x-html)
        view:   'write',  // 'write' | 'preview'  (mobile tabs)
        copied: null,     // 'md' | 'html' | null
        _timer: null,

        /* Word / char / line stats label */
        get statsLabel() {
            if (!this.md) return 'No content';
            const words = this.md.trim().split(/\s+/).filter(Boolean).length;
            const chars = this.md.length;
            const lines = this.md.split('\n').length;
            return `${words} words · ${chars} chars · ${lines} lines`;
        },

        /* ── Lifecycle ──────────────────────────────────────────────── */
        boot() {
            /* Restore draft from localStorage */
            const saved = localStorage.getItem('jedisebi_markdown');
            if (saved !== null) {
                this.md = saved;
            } else {
                /* First-run example so the preview looks alive */
                this.md = this.STARTER;
            }
            this.update();

            /* Auto-save on every change */
            this.$watch('md', val => {
                localStorage.setItem('jedisebi_markdown', val);
                this.update();
            });
        },

        update() {
            if (typeof marked === 'undefined') {
                this.html = '<p class="text-amber-600">Markdown parser loading…</p>';
                return;
            }
            this.html = marked.parse(this.md || '');
        },

        /* ── Toolbar helpers ─────────────────────────────────────────── */

        /*
         * Wrap selected text (or insert at cursor) with before/after markers.
         * Passing $event is optional — allows skipping when called from keyboard shortcuts.
         */
        wrap(before, after) {
            const ta = this.$refs.editor;
            const s  = ta.selectionStart;
            const e  = ta.selectionEnd;
            const sel = this.md.substring(s, e);
            const replacement = before + sel + after;
            this.md = this.md.substring(0, s) + replacement + this.md.substring(e);
            this.$nextTick(() => {
                ta.selectionStart = s + before.length;
                ta.selectionEnd   = s + before.length + sel.length;
                ta.focus();
            });
        },

        /* Insert a block-level element (e.g. fenced code block) */
        insertBlock(before, after) {
            const ta  = this.$refs.editor;
            const pos = ta.selectionEnd;
            const sel = this.md.substring(ta.selectionStart, pos);
            const prefix = this.md.slice(-1) === '\n' || this.md === '' ? '' : '\n';
            const snip   = prefix + before + sel + after + '\n';
            this.md = this.md.substring(0, ta.selectionStart) + snip + this.md.substring(pos);
            this.$nextTick(() => {
                const cur = ta.selectionStart + snip.length - after.length - 1;
                ta.selectionStart = ta.selectionEnd = cur;
                ta.focus();
            });
        },

        /* Prepend a prefix to the current line (headings, blockquote, lists) */
        insert(prefix) {
            const ta        = this.$refs.editor;
            const s         = ta.selectionStart;
            const lineStart = this.md.lastIndexOf('\n', s - 1) + 1;
            /* Toggle: remove prefix if already present */
            if (this.md.substring(lineStart).startsWith(prefix)) {
                this.md = this.md.substring(0, lineStart) + this.md.substring(lineStart + prefix.length);
                this.$nextTick(() => { ta.selectionStart = ta.selectionEnd = s - prefix.length; ta.focus(); });
            } else {
                this.md = this.md.substring(0, lineStart) + prefix + this.md.substring(lineStart);
                this.$nextTick(() => { ta.selectionStart = ta.selectionEnd = s + prefix.length; ta.focus(); });
            }
        },

        prependLine(prefix) { this.insert(prefix); },

        /* Insert arbitrary text at cursor */
        insertLine(text) {
            const ta  = this.$refs.editor;
            const pos = ta.selectionEnd;
            this.md   = this.md.substring(0, pos) + text + this.md.substring(pos);
            this.$nextTick(() => { ta.selectionStart = ta.selectionEnd = pos + text.length; ta.focus(); });
        },

        /* Link helper: [selected text](url) */
        wrapLink() {
            const ta  = this.$refs.editor;
            const sel = this.md.substring(ta.selectionStart, ta.selectionEnd);
            this.wrap('[' + (sel || 'link text'), '](https://)');
        },

        /* Image helper: ![alt text](url) */
        wrapImage() {
            const ta  = this.$refs.editor;
            const sel = this.md.substring(ta.selectionStart, ta.selectionEnd);
            this.wrap('![' + (sel || 'alt text'), '](https://)');
        },

        /* Table snippet */
        insertTable() {
            const snip = '\n| Column 1 | Column 2 | Column 3 |\n| --- | --- | --- |\n| Cell | Cell | Cell |\n';
            this.insertLine(snip);
        },

        /* Tab key → 4 spaces */
        insertTab() {
            const ta = this.$refs.editor;
            const s  = ta.selectionStart;
            this.md  = this.md.substring(0, s) + '    ' + this.md.substring(ta.selectionEnd);
            this.$nextTick(() => { ta.selectionStart = ta.selectionEnd = s + 4; });
        },

        /* ── Clipboard ───────────────────────────────────────────────── */
        async copyMD() {
            if (!this.md) return;
            await this._clip(this.md);
            this._flash('md');
        },

        async copyHTML() {
            if (!this.html) return;
            await this._clip(this.html);
            this._flash('html');
        },

        async _clip(text) {
            try {
                await navigator.clipboard.writeText(text);
            } catch {
                const el = Object.assign(document.createElement('textarea'), {
                    value: text, style: 'position:fixed;opacity:0;pointer-events:none'
                });
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
            }
        },

        _flash(type) {
            this.copied = type;
            clearTimeout(this._timer);
            this._timer = setTimeout(() => { this.copied = null; }, 2000);
        },

        /* ── Download ────────────────────────────────────────────────── */
        downloadMD() {
            this._save('document.md', this.md, 'text/markdown;charset=utf-8');
        },

        downloadHTML() {
            const full = [
                '<!DOCTYPE html>',
                '<html lang="en">',
                '<head>',
                '  <meta charset="utf-8">',
                '  <meta name="viewport" content="width=device-width,initial-scale=1">',
                '  <title>Document</title>',
                '  <style>',
                '    body{font-family:system-ui,sans-serif;max-width:800px;margin:40px auto;padding:0 20px;color:#1f2937;line-height:1.6}',
                '    pre{background:#111827;color:#f3f4f6;padding:1rem;border-radius:.5rem;overflow-x:auto}',
                '    code{background:#eff6ff;color:#1d4ed8;padding:.1em .3em;border-radius:.25rem}',
                '    pre code{background:transparent;color:inherit;padding:0}',
                '    blockquote{border-left:4px solid #818cf8;margin:0;padding:0 1rem;color:#6b7280}',
                '    table{border-collapse:collapse;width:100%}',
                '    th,td{border:1px solid #e5e7eb;padding:.5rem .75rem}',
                '    th{background:#f9fafb}',
                '  </style>',
                '</head>',
                '<body>',
                this.html,
                '</body>',
                '</html>'
            ].join('\n');
            this._save('document.html', full, 'text/html;charset=utf-8');
        },

        _save(name, content, type) {
            const blob = new Blob([content], { type });
            const url  = URL.createObjectURL(blob);
            Object.assign(document.createElement('a'), { href: url, download: name }).click();
            URL.revokeObjectURL(url);
        },

        /* ── Clear ───────────────────────────────────────────────────── */
        clearAll() {
            if (!this.md || !confirm('Clear all content? (This cannot be undone.)')) return;
            this.md   = '';
            this.html = '';
            localStorage.removeItem('jedisebi_markdown');
            this.$refs.editor.focus();
        },

        /* ── Starter content (shown on first visit) ──────────────────── */
        STARTER: `# Welcome to Markdown Editor

Type your **markdown** on the left and see the **live preview** on the right.

## Text Formatting

You can write *italic*, **bold**, ~~strikethrough~~, and \`inline code\`.

## Lists

**Unordered:**
- Item one
- Item two
  - Nested item

**Ordered:**
1. First item
2. Second item
3. Third item

## Links & Images

[Visit OpenAI](https://openai.com)

## Code Blocks

\`\`\`javascript
function greet(name) {
  return \`Hello, \${name}!\`;
}
console.log(greet('World'));
\`\`\`

## Blockquote

> Great things are not done by impulse, but by a series of small things brought together.

## Table

| Feature        | Supported |
| -------------- | --------- |
| Live preview   | ✅        |
| Export .md     | ✅        |
| Export .html   | ✅        |
| Auto-save      | ✅        |
| GFM tables     | ✅        |

---

*Start editing to try it out!*
`
    };
}
</script>
@endpush
