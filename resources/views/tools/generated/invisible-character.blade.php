@extends('layouts.public')
@section('renders_own_breadcrumb', '1')

@section('title', $tool->seo_title)
@section('meta_description', $tool->seo_description)
@section('renders_own_content_sections', '1')
@section('renders_own_faqs', '1')

{{-- Tool-specific share image, unless an admin has set one on the tool --}}
@if(!$tool->getRawOriginal('og_image'))
@section('og_image', asset('images/og/invisible-character-copy-paste.png'))
@endif

@section('content')
<style>
/* ══════════════════════════════════════════════════════════
   Invisible Character  —  prefix: ic-
   Brand: indigo (site palette)
   Copy, generate, test and detect invisible Unicode characters.
   100 % client-side — nothing is sent to the server.
══════════════════════════════════════════════════════════ */

/* Section divider */
.ic-div { display:flex; align-items:center; gap:.6rem; color:#94a3b8; font-size:.62rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; }
.ic-div::before,.ic-div::after { content:''; flex:1; height:1px; background:#f1f5f9; }

/* Quick-copy cards */
.ic-quick { display:grid; grid-template-columns:1fr; gap:.75rem; }
@media (min-width:640px) { .ic-quick { grid-template-columns:repeat(3,1fr); } }
.ic-qcard { display:flex; flex-direction:column; align-items:stretch; gap:.6rem; padding:1rem; border:1.5px solid #E9EAF7; border-radius:1rem; background:#fff; text-align:left; cursor:pointer; transition:all .15s; }
.ic-qcard:hover { border-color:#7B81D3; background:#F4F5FA; transform:translateY(-1px); box-shadow:0 4px 14px rgba(67,76,182,.12); }
.ic-qcard.ic-done { border-color:#86efac; background:#f0fdf4; }
.ic-qcard-top { display:flex; align-items:center; justify-content:space-between; gap:.5rem; }
.ic-qcard-name { font-size:.88rem; font-weight:800; color:#262A5E; }
.ic-qcard-code { font-family:'JetBrains Mono','Fira Code',ui-monospace,monospace; font-size:.68rem; font-weight:700; color:#434CB6; background:#E9EAF7; border-radius:9999px; padding:.12rem .5rem; }
.ic-qcard-desc { font-size:.74rem; color:#64748b; line-height:1.45; }
.ic-qcard-box { display:flex; align-items:center; justify-content:center; height:2.4rem; border:1.5px dashed #AAAEE4; border-radius:.6rem; background:#F4F5FA; font-size:1.2rem; color:#262A5E; white-space:pre; }
.ic-qcard-cta { font-size:.72rem; font-weight:800; text-transform:uppercase; letter-spacing:.06em; color:#434CB6; text-align:center; }
.ic-qcard.ic-done .ic-qcard-cta { color:#15803d; }
.ic-badge-rec { font-size:.58rem; font-weight:800; text-transform:uppercase; letter-spacing:.06em; color:#fff; background:#434CB6; border-radius:9999px; padding:.1rem .45rem; }

/* Generator */
.ic-stepper { display:flex; align-items:stretch; }
.ic-stepper button { width:2.6rem; border:1px solid #d1d5db; background:#f8fafc; font-size:1.1rem; font-weight:800; color:#434CB6; cursor:pointer; transition:background .13s; }
.ic-stepper button:hover { background:#E9EAF7; }
.ic-stepper button:first-child { border-radius:.75rem 0 0 .75rem; border-right:none; }
.ic-stepper button:last-child  { border-radius:0 .75rem .75rem 0; border-left:none; }
.ic-stepper input { border-radius:0 !important; text-align:center; font-weight:700; min-width:0; }
.ic-presets { display:flex; flex-wrap:wrap; gap:.35rem; }
.ic-preset { padding:.25rem .65rem; border-radius:9999px; border:1.5px solid #E9EAF7; background:#fff; font-size:.72rem; font-weight:700; color:#383F99; cursor:pointer; transition:all .13s; }
.ic-preset:hover { border-color:#7B81D3; }
.ic-preset.ic-on { background:#434CB6; border-color:#434CB6; color:#fff; }
.ic-select { width:100%; padding:.55rem .85rem; border:1px solid #d1d5db; border-radius:.75rem; font-size:.85rem; color:#374151; background:#fff; outline:none; cursor:pointer; }
.ic-select:focus { border-color:#5B62C5; box-shadow:0 0 0 3px rgba(91,98,197,.18); }
.ic-gen-preview { display:flex; flex-wrap:wrap; align-items:center; gap:3px; min-height:3rem; max-height:8rem; overflow:auto; padding:.6rem .8rem; border:1.5px dashed #AAAEE4; border-radius:.75rem; background:#F4F5FA; font-size:1rem; line-height:1.4; color:#262A5E; }
/* One highlighted cell per generated character, so blank text is visible */
.ic-cell { display:inline-block; min-width:.35rem; height:1.6rem; white-space:pre; background:rgba(91,98,197,.28); border-radius:3px; box-shadow:inset 0 0 0 1px rgba(67,76,182,.45); }
.ic-cell-zero { width:3px; min-width:3px; background:#434CB6; }
.ic-more { font-size:.72rem; font-weight:700; color:#434CB6; margin-left:.3rem; white-space:nowrap; }

/* Buttons */
.ic-btn { display:inline-flex; align-items:center; justify-content:center; gap:.4rem; padding:.7rem 1.3rem; border-radius:.8rem; font-weight:800; font-size:.9rem; color:#fff; border:none; cursor:pointer; background:linear-gradient(135deg,#2F3479,#434CB6,#5B62C5); box-shadow:0 4px 14px rgba(67,76,182,.3); transition:all .15s; }
.ic-btn:hover { box-shadow:0 6px 20px rgba(67,76,182,.42); transform:translateY(-1px); }
.ic-btn.ic-done { background:linear-gradient(135deg,#15803d,#16a34a); box-shadow:0 4px 14px rgba(22,163,74,.3); }
.ic-act { display:inline-flex; align-items:center; gap:.35rem; padding:.42rem .8rem; border-radius:.7rem; font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; cursor:pointer; transition:all .15s; border:1.5px solid #E9EAF7; background:#fff; color:#383F99; white-space:nowrap; }
.ic-act:hover:not(:disabled) { background:#434CB6; border-color:#434CB6; color:#fff; }
.ic-act:disabled { opacity:.35; cursor:not-allowed; }
.ic-act.ic-done { background:#dcfce7; color:#15803d; border-color:#86efac; }
.ic-act-danger { color:#dc2626; border-color:#fecaca; background:#fef2f2; }
.ic-act-danger:hover:not(:disabled) { background:#dc2626; border-color:#dc2626; color:#fff; }

/* Tester */
.ic-textarea { width:100%; min-height:150px; resize:vertical; font-size:.95rem; line-height:1.7; color:#1e293b; border:none; outline:none; padding:1rem 1.15rem; background:transparent; }
.ic-textarea::placeholder { color:#cbd5e1; }
.ic-textarea:focus { box-shadow:none; }
.ic-panel-hdr { display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:.5rem; padding:.6rem 1rem; border-bottom:1px solid #f3f4f6; }
.ic-stat { font-size:.68rem; font-weight:700; color:#383F99; background:#F4F5FA; padding:.15rem .55rem; border-radius:9999px; border:1px solid #E9EAF7; white-space:nowrap; }
.ic-stat.ic-warn { color:#b45309; background:#fffbeb; border-color:#fde68a; }
.ic-reveal { padding:1rem 1.15rem; font-size:.92rem; line-height:2; color:#1e293b; white-space:pre-wrap; word-break:break-word; background:#fcfcfe; border-top:1px solid #f3f4f6; max-height:16rem; overflow:auto; }
.ic-tag { display:inline-block; margin:0 .08rem; padding:0 .3rem; border-radius:.3rem; background:#fef3c7; border:1px solid #fcd34d; color:#92400e; font-family:'JetBrains Mono','Fira Code',ui-monospace,monospace; font-size:.62rem; font-weight:700; line-height:1.5; vertical-align:middle; white-space:nowrap; }
.ic-found { display:flex; flex-wrap:wrap; gap:.35rem; }
.ic-found span { font-size:.7rem; padding:.18rem .55rem; border-radius:9999px; background:#fffbeb; border:1px solid #fde68a; color:#92400e; font-weight:600; }

/* Reference table */
.ic-tabs { display:flex; flex-wrap:wrap; gap:.35rem; }
.ic-tab { padding:.32rem .75rem; border-radius:9999px; border:1.5px solid #E9EAF7; background:#fff; font-size:.74rem; font-weight:700; color:#383F99; cursor:pointer; transition:all .13s; white-space:nowrap; }
.ic-tab:hover { border-color:#7B81D3; }
.ic-tab.ic-on { background:#434CB6; border-color:#434CB6; color:#fff; }
.ic-table-wrap { overflow-x:auto; }
.ic-table { width:100%; border-collapse:collapse; font-size:.82rem; }
.ic-table th { text-align:left; font-size:.6rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:#94a3b8; padding:.6rem .75rem; border-bottom:1px solid #f1f5f9; white-space:nowrap; }
.ic-table td { padding:.55rem .75rem; border-bottom:1px solid #f8fafc; vertical-align:middle; }
.ic-table tr:hover td { background:#fafaff; }
.ic-mono { font-family:'JetBrains Mono','Fira Code',ui-monospace,monospace; font-size:.74rem; color:#434CB6; white-space:nowrap; }
.ic-sample { display:inline-block; min-width:2.2rem; text-align:center; padding:.1rem .3rem; border:1px dashed #AAAEE4; border-radius:.35rem; background:#F4F5FA; white-space:pre; color:#262A5E; }
.ic-copy-sm { padding:.3rem .65rem; border-radius:.55rem; border:1.5px solid #E9EAF7; background:#fff; font-size:.7rem; font-weight:700; color:#434CB6; cursor:pointer; transition:all .13s; white-space:nowrap; }
.ic-copy-sm:hover { background:#434CB6; border-color:#434CB6; color:#fff; }
.ic-copy-sm.ic-done { background:#dcfce7; border-color:#86efac; color:#15803d; }

/* Use-case cards */
.ic-uses { display:grid; grid-template-columns:1fr; gap:.75rem; }
@media (min-width:640px) { .ic-uses { grid-template-columns:repeat(2,1fr); } }
@media (min-width:1024px) { .ic-uses { grid-template-columns:repeat(4,1fr); } }
.ic-use { padding:1rem; border:1.5px solid #f1f5f9; border-radius:1rem; background:#fff; }
.ic-use-t { font-size:.85rem; font-weight:800; color:#262A5E; margin-top:.35rem; }
.ic-use-d { font-size:.75rem; color:#64748b; margin-top:.25rem; line-height:1.5; }

.ic-note { padding:.7rem .9rem; border-radius:.75rem; background:#F4F5FA; border:1px solid #E9EAF7; font-size:.75rem; color:#2F3479; }

/* Toast */
.ic-toast { position:fixed; left:50%; bottom:1.5rem; transform:translateX(-50%); z-index:60; padding:.6rem 1.1rem; border-radius:9999px; background:#181B3E; color:#fff; font-size:.82rem; font-weight:600; box-shadow:0 8px 24px rgba(0,0,0,.25); white-space:nowrap; }

/* Utilities used on this page that are not in the prebuilt Tailwind bundle */
.max-w-screen-xl { max-width:1280px; }
.w-14 { width:3.5rem; } .h-14 { height:3.5rem; }
.no-underline { text-decoration-line:none; }
@media (min-width:640px) {
  .sm\:max-w-xs { max-width:20rem; }
}
@media (min-width:768px) {
  .md\:py-8 { padding-top:2rem; padding-bottom:2rem; }
  .md\:py-10 { padding-top:2.5rem; padding-bottom:2.5rem; }
}
@media (min-width:1024px) {
  .lg\:grid-cols-5 { grid-template-columns:repeat(5,minmax(0,1fr)); }
  .lg\:col-span-2 { grid-column:span 2 / span 2; }
  .lg\:col-span-3 { grid-column:span 3 / span 3; }
}

@keyframes icIn { from{opacity:0;transform:translate(-50%,8px)} to{opacity:1;transform:translate(-50%,0)} }
.ic-in { animation:icIn .2s ease-out; }
</style>

<div class="min-h-screen bg-gray-50">

  {{-- ── Hero header ── --}}
  <div class="bg-white border-b border-gray-100">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-8 md:py-10">
      <div class="flex items-center gap-4 mb-4">
        <div class="text-3xl w-14 h-14 flex items-center justify-center rounded-xl flex-shrink-0" style="background:#E9EAF7">
          {{ $tool->icon ?? '👻' }}
        </div>
        <div>
          <h1 class="text-3xl font-bold text-gray-900">{{ $tool->name }}</h1>
          <p class="text-gray-500 mt-1">Copy and paste an invisible character (ㅤ) in one click, generate as many blank characters as you need, and find hidden characters in any text.</p>
        </div>
      </div>
      <x-breadcrumb :items="[
          ['label' => 'Home',                'url' => url('/')],
          ['label' => $tool->category->name, 'url' => route('categories.show', $tool->category)],
          ['label' => $tool->name]
      ]"/>
    </div>
  </div>

  <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-6 md:py-8 space-y-5" x-data="icTool()">

    {{-- ═══════════════ Quick copy ═══════════════ --}}
    <div class="card p-5">
      <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">One-Click Copy</p>
      <div class="ic-quick">
        <template x-for="q in quick" :key="q.cp">
          <button type="button" class="ic-qcard" :class="{ 'ic-done': copiedKey === 'q' + q.cp }"
                  @click="copy(chr(q.cp), 'q' + q.cp, q.name + ' copied')">
            <span class="ic-qcard-top">
              <span class="ic-qcard-name" x-text="q.name"></span>
              <span class="flex items-center gap-1">
                <span class="ic-badge-rec" x-show="q.recommended">Best</span>
                <span class="ic-qcard-code" x-text="hex(q.cp)"></span>
              </span>
            </span>
            <span class="ic-qcard-desc" x-text="q.desc"></span>
            <span class="ic-qcard-box" x-text="'[' + chr(q.cp) + ']'"></span>
            <span class="ic-qcard-cta" x-text="copiedKey === 'q' + q.cp ? '✓ Copied' : 'Click to copy'"></span>
          </button>
        </template>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 items-start">

      {{-- ═══════════════ Generator ═══════════════ --}}
      <div class="lg:col-span-2 card p-5 space-y-4">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Blank Text Generator</p>

        <div>
          <label class="form-label" for="ic-char">Character</label>
          <select id="ic-char" class="ic-select" x-model.number="genCp">
            <template x-for="c in generatorChars" :key="c.cp">
              <option :value="c.cp" x-text="c.name + ' (' + hex(c.cp) + ')'" :selected="c.cp === genCp"></option>
            </template>
          </select>
        </div>

        <div>
          <label class="form-label" for="ic-count">How many characters?</label>
          <div class="ic-stepper">
            <button type="button" @click="step(-1)" aria-label="Fewer">−</button>
            <input id="ic-count" type="number" inputmode="numeric" min="1" :max="MAX_COUNT" step="1"
                   class="form-input" x-model.number="genCount" @change="clampCount()">
            <button type="button" @click="step(1)" aria-label="More">+</button>
          </div>
          <div class="ic-presets mt-2">
            <template x-for="n in [1, 3, 5, 10, 50, 100, 1000]" :key="n">
              <button type="button" class="ic-preset" :class="{ 'ic-on': genCount === n }" @click="genCount = n" x-text="n"></button>
            </template>
          </div>
        </div>

        <div>
          <span class="form-label">Preview <span class="text-gray-400 font-normal">(each highlighted box is one blank character)</span></span>
          <div class="ic-gen-preview"><template x-for="i in previewCount" :key="i"><span class="ic-cell" :class="{ 'ic-cell-zero': genIsZeroWidth }" x-text="chr(genCp)"></span></template><span class="ic-more" x-show="genCount > previewCount" x-text="'+ ' + (genCount - previewCount).toLocaleString() + ' more'"></span></div>
        </div>

        <button type="button" class="ic-btn w-full" :class="{ 'ic-done': copiedKey === 'gen' }"
                @click="copy(generated, 'gen', genCount + ' blank character' + (genCount === 1 ? '' : 's') + ' copied')">
          <span x-text="copiedKey === 'gen' ? '✓ Copied!' : '📋 Copy ' + genCount + ' character' + (genCount === 1 ? '' : 's')"></span>
        </button>
      </div>

      {{-- ═══════════════ Tester / detector ═══════════════ --}}
      <div class="lg:col-span-3 space-y-3">
        <div class="card">
          <div class="ic-panel-hdr">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Test &amp; Detect</p>
            <div class="flex flex-wrap items-center gap-2">
              <span class="ic-stat" x-text="charCount + ' character' + (charCount === 1 ? '' : 's')"></span>
              <span class="ic-stat" :class="{ 'ic-warn': hiddenCount > 0 }"
                    x-text="hiddenCount + ' invisible'"></span>
            </div>
          </div>

          <textarea class="ic-textarea" x-model="testText" spellcheck="false"
                    placeholder="Paste here. If this grey text disappears, your invisible character is working."
                    aria-label="Test area"></textarea>

          <div class="ic-panel-hdr" style="border-top:1px solid #f3f4f6;border-bottom:none">
            <div class="flex flex-wrap gap-2">
              <button type="button" class="ic-act" @click="paste()">📥 Paste</button>
              <button type="button" class="ic-act" :class="{ 'ic-done': copiedKey === 'test' }" :disabled="!testText"
                      @click="copy(testText, 'test', 'Text copied')" x-text="copiedKey === 'test' ? '✓ Copied' : '📋 Copy'"></button>
              <button type="button" class="ic-act" :disabled="hiddenCount === 0" @click="clean()">🧹 Remove invisible</button>
              <button type="button" class="ic-act ic-act-danger" :disabled="!testText" @click="testText = ''">✕ Clear</button>
            </div>
          </div>

          <div x-show="testText" x-cloak>
            <div class="ic-reveal"><template x-for="(seg, i) in segments" :key="i"><span><template x-if="seg.hidden"><span class="ic-tag" :title="seg.name" x-text="seg.label"></span></template><template x-if="!seg.hidden"><span x-text="seg.text"></span></template></span></template></div>
          </div>
        </div>

        <div class="card p-4" x-show="found.length" x-cloak>
          <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Hidden characters found</p>
          <div class="ic-found">
            <template x-for="f in found" :key="f.cp">
              <span x-text="f.count + ' × ' + f.name + ' (' + hex(f.cp) + ')'"></span>
            </template>
          </div>
        </div>

        <div class="ic-note">
          🔒 Everything runs in your browser — nothing you type or paste is sent to our server. The detector highlights zero-width,
          direction and filler characters and unusual spaces; normal spaces, tabs and line breaks are left alone.
        </div>
      </div>
    </div>

    {{-- ═══════════════ Reference table ═══════════════ --}}
    <div class="card">
      <div class="p-5 space-y-3" style="border-bottom:1px solid #f3f4f6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
          <div>
            <h2 class="text-lg font-semibold text-gray-900">All Invisible Characters</h2>
            <p class="text-xs text-gray-500 mt-0.5" x-text="filtered.length + ' characters — click Copy to copy one'"></p>
          </div>
          <input type="search" class="form-input sm:max-w-xs" placeholder="Search name or code, e.g. 200B"
                 x-model.debounce.150ms="search" aria-label="Search characters">
        </div>
        <div class="ic-tabs">
          <button type="button" class="ic-tab" :class="{ 'ic-on': group === 'all' }" @click="group = 'all'">All</button>
          <template x-for="g in groups" :key="g.key">
            <button type="button" class="ic-tab" :class="{ 'ic-on': group === g.key }" @click="group = g.key" x-text="g.label"></button>
          </template>
        </div>
      </div>

      <div class="ic-table-wrap">
        <table class="ic-table">
          <thead>
            <tr>
              <th>Code</th>
              <th>Name</th>
              <th class="hidden sm:table-cell">HTML entity</th>
              <th>Sample</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <template x-for="c in filtered" :key="c.cp">
              <tr>
                <td class="ic-mono" x-text="hex(c.cp)"></td>
                <td>
                  <span class="font-medium text-gray-800" x-text="c.name"></span>
                  <span class="block text-xs text-gray-400" x-text="c.note" x-show="c.note"></span>
                </td>
                <td class="ic-mono hidden sm:table-cell" x-text="entity(c.cp)"></td>
                <td><span class="ic-sample" x-text="'[' + chr(c.cp) + ']'"></span></td>
                <td class="text-right">
                  <button type="button" class="ic-copy-sm" :class="{ 'ic-done': copiedKey === 't' + c.cp }"
                          @click="copy(chr(c.cp), 't' + c.cp, c.name + ' copied')"
                          x-text="copiedKey === 't' + c.cp ? '✓ Copied' : 'Copy'"></button>
                </td>
              </tr>
            </template>
            <tr x-show="filtered.length === 0">
              <td colspan="5" class="text-center text-sm text-gray-400 py-8">No characters match your search.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    {{-- ═══════════════ Use cases ═══════════════ --}}
    <div class="card p-5">
      <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">What You Can Do With It</p>
      <div class="ic-uses">
        <div class="ic-use"><span class="text-2xl">💬</span><p class="ic-use-t">Send empty messages</p><p class="ic-use-d">Paste a Hangul Filler into WhatsApp, Messenger or Telegram to send a message that looks blank.</p></div>
        <div class="ic-use"><span class="text-2xl">🎮</span><p class="ic-use-t">Invisible game names</p><p class="ic-use-d">Create a blank or spaced-out name in games like Free Fire, PUBG, Fortnite and Among Us, where allowed.</p></div>
        <div class="ic-use"><span class="text-2xl">📱</span><p class="ic-use-t">Clean social profiles</p><p class="ic-use-d">Add line breaks and blank spacing to Instagram, TikTok and Discord bios, captions and names.</p></div>
        <div class="ic-use"><span class="text-2xl">🧑‍💻</span><p class="ic-use-t">Debug hidden characters</p><p class="ic-use-d">Find zero-width spaces and direction marks that break code, URLs, passwords and spreadsheet lookups.</p></div>
      </div>
    </div>

    <div class="ic-toast ic-in" x-show="toast" x-cloak x-text="toast" role="status" aria-live="polite"></div>

    {{-- ── Admin-editable content, FAQs & related tools ── --}}
    <div class="mt-8 space-y-5">
      @if($tool->long_description)
      <div class="card p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">About This Tool</h2>
        <div class="tool-prose">{{ \App\Support\Prose::render($tool->long_description) }}</div>
      </div>
      @endif

      @foreach($tool->contents->where('is_visible', true) as $section)
      <div class="card p-6">
        @if($section->title)
        <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ $section->title }}</h2>
        @endif
        <div class="tool-prose">{{ \App\Support\Prose::render($section->content) }}</div>
      </div>
      @endforeach

      @if($tool->faqs->where('is_visible', true)->count() > 0)
      <div class="card p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-5">Frequently Asked Questions</h2>
        <div class="space-y-3" x-data="{ open: null }">
          @foreach($tool->faqs->where('is_visible', true)->values() as $fi => $faq)
          <div class="border border-gray-100 rounded-xl overflow-hidden">
            <button type="button" @click="open = open === {{ $fi }} ? null : {{ $fi }}"
                    class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors">
              <span class="font-medium text-gray-800 text-sm">{{ $faq->question }}</span>
              <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform" :class="open === {{ $fi }} ? 'rotate-180' : ''"
                   fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
              </svg>
            </button>
            <div x-show="open === {{ $fi }}" x-cloak class="px-4 pb-4 text-sm text-gray-600 leading-relaxed">{{ $faq->answer }}</div>
          </div>
          @endforeach
        </div>
      </div>
      @endif

      @if($relatedTools->count())
      <div>
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Related Tools</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
          @foreach($relatedTools as $related)
          <a href="{{ route('tools.show', $related->slug) }}" class="card-hover p-4 flex items-center gap-3 no-underline">
            <span class="text-xl">{{ $related->icon }}</span>
            <p class="text-sm font-medium text-gray-800 truncate">{{ $related->name }}</p>
          </a>
          @endforeach
        </div>
      </div>
      @endif
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
/* ─────────────────────────────────────────────────────────────
   Invisible Character — Alpine.js component (prefix: ic-)

   CHARS is the single source of truth: it feeds the reference
   table, the generator's character list and the detector.
   `blank`  → renders as an empty-looking glyph with width, so it
              is offered in the generator (good for names/messages).
   `space`  → a space-like character the cleaner swaps for U+0020.
   Anything else in CHARS is deleted by the cleaner.
──────────────────────────────────────────────────────────── */
function icTool() {
  var MAX_COUNT = 10000;

  var CHARS = [
    // Blank fillers — look empty but count as letters, so apps keep them
    { cp: 0x3164,  g: 'filler', name: 'Hangul Filler', blank: true, note: 'Most widely accepted blank character' },
    { cp: 0xFFA0,  g: 'filler', name: 'Halfwidth Hangul Filler', blank: true },
    { cp: 0x115F,  g: 'filler', name: 'Hangul Choseong Filler', blank: true },
    { cp: 0x1160,  g: 'filler', name: 'Hangul Jungseong Filler', blank: true },
    { cp: 0x2800,  g: 'filler', name: 'Braille Pattern Blank', blank: true },
    { cp: 0x17B4,  g: 'filler', name: 'Khmer Vowel Inherent AQ', blank: true },
    { cp: 0x17B5,  g: 'filler', name: 'Khmer Vowel Inherent AA', blank: true },

    // Spaces
    { cp: 0x0020,  g: 'space', name: 'Space', blank: true, space: true, note: 'Normal space — not flagged by the detector' },
    { cp: 0x00A0,  g: 'space', name: 'No-Break Space', blank: true, space: true },
    { cp: 0x2000,  g: 'space', name: 'En Quad', blank: true, space: true },
    { cp: 0x2001,  g: 'space', name: 'Em Quad', blank: true, space: true },
    { cp: 0x2002,  g: 'space', name: 'En Space', blank: true, space: true },
    { cp: 0x2003,  g: 'space', name: 'Em Space', blank: true, space: true },
    { cp: 0x2004,  g: 'space', name: 'Three-Per-Em Space', blank: true, space: true },
    { cp: 0x2005,  g: 'space', name: 'Four-Per-Em Space', blank: true, space: true },
    { cp: 0x2006,  g: 'space', name: 'Six-Per-Em Space', blank: true, space: true },
    { cp: 0x2007,  g: 'space', name: 'Figure Space', blank: true, space: true },
    { cp: 0x2008,  g: 'space', name: 'Punctuation Space', blank: true, space: true },
    { cp: 0x2009,  g: 'space', name: 'Thin Space', blank: true, space: true },
    { cp: 0x200A,  g: 'space', name: 'Hair Space', blank: true, space: true },
    { cp: 0x202F,  g: 'space', name: 'Narrow No-Break Space', blank: true, space: true },
    { cp: 0x205F,  g: 'space', name: 'Medium Mathematical Space', blank: true, space: true },
    { cp: 0x3000,  g: 'space', name: 'Ideographic Space', blank: true, space: true },

    // Zero-width & formatting
    { cp: 0x200B,  g: 'zero', name: 'Zero-Width Space', blank: true, note: 'No width at all' },
    { cp: 0x200C,  g: 'zero', name: 'Zero-Width Non-Joiner' },
    { cp: 0x200D,  g: 'zero', name: 'Zero-Width Joiner', note: 'Joins emoji sequences' },
    { cp: 0x2060,  g: 'zero', name: 'Word Joiner' },
    { cp: 0xFEFF,  g: 'zero', name: 'Zero-Width No-Break Space (BOM)' },
    { cp: 0x00AD,  g: 'zero', name: 'Soft Hyphen', note: 'Only visible at a line break' },
    { cp: 0x034F,  g: 'zero', name: 'Combining Grapheme Joiner' },
    { cp: 0x180E,  g: 'zero', name: 'Mongolian Vowel Separator' },
    { cp: 0x2061,  g: 'zero', name: 'Function Application' },
    { cp: 0x2062,  g: 'zero', name: 'Invisible Times' },
    { cp: 0x2063,  g: 'zero', name: 'Invisible Separator' },
    { cp: 0x2064,  g: 'zero', name: 'Invisible Plus' },
    { cp: 0xFFFC,  g: 'zero', name: 'Object Replacement Character' },

    // Direction marks
    { cp: 0x200E,  g: 'dir', name: 'Left-to-Right Mark' },
    { cp: 0x200F,  g: 'dir', name: 'Right-to-Left Mark' },
    { cp: 0x061C,  g: 'dir', name: 'Arabic Letter Mark' },
    { cp: 0x202A,  g: 'dir', name: 'Left-to-Right Embedding' },
    { cp: 0x202B,  g: 'dir', name: 'Right-to-Left Embedding' },
    { cp: 0x202C,  g: 'dir', name: 'Pop Directional Formatting' },
    { cp: 0x202D,  g: 'dir', name: 'Left-to-Right Override' },
    { cp: 0x202E,  g: 'dir', name: 'Right-to-Left Override', note: 'Reverses the text after it' },
    { cp: 0x2066,  g: 'dir', name: 'Left-to-Right Isolate' },
    { cp: 0x2067,  g: 'dir', name: 'Right-to-Left Isolate' },
    { cp: 0x2068,  g: 'dir', name: 'First Strong Isolate' },
    { cp: 0x2069,  g: 'dir', name: 'Pop Directional Isolate' },

    // Other
    { cp: 0x180B,  g: 'other', name: 'Mongolian Free Variation Selector 1' },
    { cp: 0x180C,  g: 'other', name: 'Mongolian Free Variation Selector 2' },
    { cp: 0x180D,  g: 'other', name: 'Mongolian Free Variation Selector 3' },
    { cp: 0xFE0E,  g: 'other', name: 'Variation Selector-15 (text style)' },
    { cp: 0xFE0F,  g: 'other', name: 'Variation Selector-16 (emoji style)' },
    { cp: 0x206A,  g: 'other', name: 'Inhibit Symmetric Swapping' },
    { cp: 0x206B,  g: 'other', name: 'Activate Symmetric Swapping' },
    { cp: 0x206C,  g: 'other', name: 'Inhibit Arabic Form Shaping' },
    { cp: 0x206D,  g: 'other', name: 'Activate Arabic Form Shaping' },
    { cp: 0x206E,  g: 'other', name: 'National Digit Shapes' },
    { cp: 0x206F,  g: 'other', name: 'Nominal Digit Shapes' },
    { cp: 0x1D159, g: 'other', name: 'Musical Symbol Null Notehead', blank: true },
    { cp: 0x1D173, g: 'other', name: 'Musical Symbol Begin Beam' },
    { cp: 0x1D174, g: 'other', name: 'Musical Symbol End Beam' },
    { cp: 0x1D175, g: 'other', name: 'Musical Symbol Begin Tie' },
    { cp: 0x1D176, g: 'other', name: 'Musical Symbol End Tie' },
    { cp: 0x1D177, g: 'other', name: 'Musical Symbol Begin Slur' },
    { cp: 0x1D178, g: 'other', name: 'Musical Symbol End Slur' },
    { cp: 0x1D179, g: 'other', name: 'Musical Symbol Begin Phrase' },
    { cp: 0x1D17A, g: 'other', name: 'Musical Symbol End Phrase' },
    { cp: 0xE0020, g: 'other', name: 'Tag Space' },
    { cp: 0xE007F, g: 'other', name: 'Cancel Tag' },
  ];

  // Detector lookup: everything in CHARS except the ordinary space, plus the
  // rest of the Unicode tag block (U+E0000–E007F), which is never visible.
  var BY_CP = {};
  CHARS.forEach(function (c) { if (c.cp !== 0x20) BY_CP[c.cp] = c; });
  function lookup(cp) {
    if (BY_CP[cp]) return BY_CP[cp];
    if (cp >= 0xE0000 && cp <= 0xE007F) return { cp: cp, name: 'Tag Character' };
    return null;
  }

  function chr(cp) { return String.fromCodePoint(cp); }
  function hex(cp) { return 'U+' + cp.toString(16).toUpperCase().padStart(4, '0'); }
  function entity(cp) { return '&' + '#x' + cp.toString(16).toUpperCase() + ';'; }

  return {
    MAX_COUNT: MAX_COUNT,

    quick: [
      { cp: 0x3164, name: 'Hangul Filler', recommended: true, desc: 'Works in WhatsApp, Instagram, Discord and most games. Best for empty messages and blank names.' },
      { cp: 0x2800, name: 'Braille Blank', desc: 'A blank Braille cell. A good fallback when an app rejects the Hangul Filler.' },
      { cp: 0x200B, name: 'Zero-Width Space', desc: 'Takes up no space at all. Useful for hidden breaks inside words and links.' },
    ],

    chars: CHARS,
    groups: [
      { key: 'filler', label: 'Blank fillers' },
      { key: 'space',  label: 'Spaces' },
      { key: 'zero',   label: 'Zero-width' },
      { key: 'dir',    label: 'Direction marks' },
      { key: 'other',  label: 'Other' },
    ],
    group:  'all',
    search: '',

    genCp:    0x3164,
    genCount: 5,

    testText:  '',
    copiedKey: '',
    toast:     '',
    _copyTimer:  null,
    _toastTimer: null,

    chr: chr,
    hex: hex,
    entity: entity,

    // ── Computed ──
    get generatorChars() {
      return CHARS.filter(function (c) { return c.blank && c.cp !== 0x20; });
    },

    // Highlighted cells drawn in the preview; capped so 10,000 stays fast.
    get previewCount() {
      return Math.min(Math.max(parseInt(this.genCount, 10) || 1, 1), 200);
    },

    // These render with no width, so their cell gets a fixed thin bar instead.
    get genIsZeroWidth() {
      return this.genCp === 0x200B;
    },

    get generated() {
      var n = Math.min(Math.max(parseInt(this.genCount, 10) || 1, 1), MAX_COUNT);
      return chr(this.genCp).repeat(n);
    },

    get filtered() {
      var g = this.group;
      var q = this.search.trim().toLowerCase().replace(/^u\+/, '');
      return CHARS.filter(function (c) {
        if (g !== 'all' && c.g !== g) return false;
        if (!q) return true;
        var code = c.cp.toString(16).toLowerCase();
        return c.name.toLowerCase().indexOf(q) !== -1 || code.padStart(4, '0').indexOf(q) !== -1;
      });
    },

    // Text split into visible runs and individual hidden characters.
    get segments() {
      var out = [], run = '';
      for (var ch of this.testText) {
        var info = lookup(ch.codePointAt(0));
        if (info) {
          if (run) { out.push({ hidden: false, text: run }); run = ''; }
          out.push({ hidden: true, label: hex(info.cp), name: info.name });
        } else {
          run += ch;
        }
      }
      if (run) out.push({ hidden: false, text: run });
      return out;
    },

    get found() {
      var counts = {};
      for (var ch of this.testText) {
        var info = lookup(ch.codePointAt(0));
        if (!info) continue;
        if (!counts[info.cp]) counts[info.cp] = { cp: info.cp, name: info.name, count: 0 };
        counts[info.cp].count++;
      }
      return Object.values(counts).sort(function (a, b) { return b.count - a.count; });
    },

    get hiddenCount() {
      return this.found.reduce(function (sum, f) { return sum + f.count; }, 0);
    },

    // Counts code points, not UTF-16 units, so U+1D159 etc. count as one.
    get charCount() {
      return Array.from(this.testText).length;
    },

    // ── Actions ──
    step(delta) {
      this.genCount = (parseInt(this.genCount, 10) || 0) + delta;
      this.clampCount();
    },

    clampCount() {
      var n = parseInt(this.genCount, 10);
      this.genCount = isNaN(n) ? 1 : Math.min(Math.max(n, 1), MAX_COUNT);
    },

    clean() {
      var out = '';
      for (var ch of this.testText) {
        var info = lookup(ch.codePointAt(0));
        if (!info) out += ch;
        else if (info.space) out += ' ';
      }
      var removed = this.hiddenCount;
      this.testText = out;
      this.flash(removed + ' invisible character' + (removed === 1 ? '' : 's') + ' removed');
    },

    async paste() {
      try {
        this.testText = await navigator.clipboard.readText();
      } catch (e) {
        this.flash('Paste was blocked — press Ctrl+V (or long-press → Paste) in the box');
      }
    },

    async copy(text, key, message) {
      var ok = false;
      try {
        await navigator.clipboard.writeText(text);
        ok = true;
      } catch (e) {
        // Fallback for http:// pages and older browsers.
        var ta = document.createElement('textarea');
        ta.value = text;
        ta.setAttribute('readonly', '');
        ta.style.position = 'fixed';
        ta.style.opacity = '0';
        document.body.appendChild(ta);
        ta.select();
        try { ok = document.execCommand('copy'); } catch (e2) { ok = false; }
        document.body.removeChild(ta);
      }

      if (!ok) {
        this.flash('Copy failed — please select the character and copy it manually');
        return;
      }

      this.copiedKey = key;
      clearTimeout(this._copyTimer);
      this._copyTimer = setTimeout(() => { this.copiedKey = ''; }, 1600);
      this.flash('✓ ' + message);
    },

    flash(message) {
      this.toast = message;
      clearTimeout(this._toastTimer);
      this._toastTimer = setTimeout(() => { this.toast = ''; }, 2200);
    },
  };
}
</script>
@endpush
