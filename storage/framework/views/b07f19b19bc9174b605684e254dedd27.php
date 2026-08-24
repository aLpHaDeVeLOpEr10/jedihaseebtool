

<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ══════════════════════════════════════════════════════════════
   Word to JPG Converter — prefix: wj-
   Theme: Microsoft-blue (#2563eb) + clean white document feel
   Pipeline (100% browser-side):
     1. mammoth.js  → DOCX to semantic HTML
     2. html2canvas → HTML rendered to a full-height canvas
     3. Canvas cropping → A4-sized JPG pages (1191×1683 px @ 1.5×)
   Limitation: .docx only (not legacy .doc binary format)
══════════════════════════════════════════════════════════════ */

/* ── Drop zone ─────────────────────────────────────────── */
.wj-dropzone {
  border: 2.5px dashed #93c5fd;
  border-radius: 1rem;
  padding: 2.25rem 1.5rem;
  text-align: center;
  cursor: pointer;
  transition: all .18s;
  background: #eff6ff;
  user-select: none;
}
.wj-dropzone:hover,
.wj-dropzone.wj-drag-over { border-color: #2563eb; background: #dbeafe; transform: scale(1.01); }
.wj-dropzone.wj-has-file  { border-color: #2563eb; background: #dbeafe; border-style: solid; }

.wj-dz-icon  { font-size: 2.5rem; line-height: 1; margin-bottom: .6rem; }
.wj-dz-title { font-size: 1rem; font-weight: 700; color: #374151; margin-bottom: .25rem; word-break: break-all; }
.wj-dz-sub   { font-size: .8rem; color: #9ca3af; }

/* ── Convert button ─────────────────────────────────────── */
.wj-convert-btn {
  width: 100%; padding: .9rem 1.5rem; border-radius: .875rem;
  font-size: 1rem; font-weight: 800; cursor: pointer;
  display: flex; align-items: center; justify-content: center; gap: .5rem;
  transition: all .16s; border: none;
  background: linear-gradient(135deg, #1d4ed8, #2563eb, #3b82f6);
  color: #fff; box-shadow: 0 4px 14px rgba(37,99,235,.35);
}
.wj-convert-btn:hover:not(:disabled) { box-shadow: 0 6px 20px rgba(37,99,235,.5); transform: translateY(-1px); }
.wj-convert-btn:disabled { opacity: .4; cursor: not-allowed; transform: none; box-shadow: none; }

/* ── Progress bar ───────────────────────────────────────── */
.wj-progress-track {
  width: 100%; height: .65rem; border-radius: 9999px;
  background: #dbeafe; overflow: hidden;
}
.wj-progress-fill {
  height: 100%; border-radius: 9999px;
  background: linear-gradient(90deg, #1d4ed8, #3b82f6);
  transition: width .4s ease;
  position: relative; overflow: hidden;
}
.wj-progress-fill::after {
  content: ''; position: absolute; inset: 0;
  background: linear-gradient(90deg,transparent,rgba(255,255,255,.35),transparent);
  animation: wjShimmer 1.4s infinite;
}
@keyframes wjShimmer { 0%{transform:translateX(-100%)} 100%{transform:translateX(200%)} }

/* ── Result grid ────────────────────────────────────────── */
.wj-page-card {
  border: 1.5px solid #f3f4f6; border-radius: .875rem;
  overflow: hidden; background: #fff; transition: box-shadow .15s, transform .15s;
}
.wj-page-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.1); transform: translateY(-1px); }
.wj-thumb-wrap {
  position: relative; width: 100%; padding-top: 141.4%; /* A4 aspect */
  background: repeating-conic-gradient(#e5e7eb 0% 25%, #fff 0% 50%) 0 0 / 12px 12px;
  overflow: hidden;
}
.wj-thumb {
  position: absolute; inset: 0;
  width: 100%; height: 100%; object-fit: contain; display: block;
}
.wj-page-info { padding: .5rem .65rem .6rem; border-top: 1px solid #f3f4f6; }
.wj-page-num  { font-size: .72rem; font-weight: 800; color: #374151; }
.wj-page-dims { font-size: .65rem; color: #9ca3af; margin-top: .1rem; }
.wj-dl-btn {
  display: flex; align-items: center; justify-content: center; gap: .3rem;
  width: 100%; margin-top: .5rem; padding: .35rem .5rem;
  border-radius: .55rem; font-size: .7rem; font-weight: 700;
  cursor: pointer; transition: all .14s;
  border: 1.5px solid #93c5fd; background: #eff6ff; color: #1d4ed8;
}
.wj-dl-btn:hover { background: #2563eb; color: #fff; border-color: #2563eb; }

/* ── Download all button ────────────────────────────────── */
.wj-zip-btn {
  display: inline-flex; align-items: center; gap: .4rem;
  padding: .55rem 1.2rem; border-radius: .875rem;
  font-size: .82rem; font-weight: 700; cursor: pointer;
  background: #166534; color: #fff; border: 1.5px solid #15803d;
  box-shadow: 0 2px 8px rgba(22,101,52,.2);
  transition: all .14s;
}
.wj-zip-btn:hover:not(:disabled) { background: #14532d; }
.wj-zip-btn:disabled { opacity: .5; cursor: not-allowed; }

/* ── Privacy / info badges ──────────────────────────────── */
.wj-privacy {
  display: flex; align-items: flex-start; gap: .5rem;
  padding: .55rem .85rem; border-radius: .75rem;
  background: #eff6ff; border: 1px solid #bfdbfe;
  font-size: .75rem; color: #1d4ed8; font-weight: 500;
}
.wj-warn {
  display: flex; align-items: flex-start; gap: .5rem;
  padding: .6rem .85rem; border-radius: .75rem;
  background: #fffbeb; border: 1.5px solid #fde68a;
  font-size: .78rem; color: #92400e;
}
.wj-info {
  display: flex; align-items: flex-start; gap: .5rem;
  padding: .6rem .85rem; border-radius: .75rem;
  background: #f0fdf4; border: 1.5px solid #bbf7d0;
  font-size: .78rem; color: #166534;
}

/* ── Error strip ────────────────────────────────────────── */
.wj-error {
  display: flex; align-items: flex-start; gap: .5rem;
  padding: .65rem .9rem; border-radius: .75rem;
  background: #fef2f2; border: 1.5px solid #fecaca;
  font-size: .8rem; color: #991b1b; font-weight: 500;
}

/* ── Spinner ────────────────────────────────────────────── */
@keyframes wjSpin { to { transform: rotate(360deg); } }
.wj-spin {
  display: inline-block; width: 1em; height: 1em; border-radius: 50%;
  border: 2px solid rgba(255,255,255,.4); border-top-color: #fff;
  animation: wjSpin .65s linear infinite; flex-shrink: 0;
}
.wj-spin-dark {
  border-color: rgba(37,99,235,.25); border-top-color: #2563eb;
}

/* ── Divider ────────────────────────────────────────────── */
.wj-div {
  display: flex; align-items: center; gap: .6rem;
  font-size: .6rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: #6b7280;
}
.wj-div::before,.wj-div::after { content:''; flex:1; height:1px; background:#e5e7eb; }

/* ── Off-screen Word document render target ─────────────── */
/* This container is used by html2canvas — must be in DOM, visible,
   but moved far off-screen so users never see it. */
#wj-render-host {
  position: fixed;
  left: -99999px;
  top: 0;
  width: 794px;          /* A4 at 96 DPI */
  min-height: 1122px;
  background: #ffffff;
  color: #000000;
  font-family: "Calibri", "Arial", sans-serif;
  font-size: 16px;       /* 12pt at 96dpi */
  line-height: 1.5;
  z-index: -1;
  overflow: visible;
  pointer-events: none;
}
/* Word-like styles applied to mammoth's HTML output */
#wj-render-host .wj-doc-body {
  padding: 96px;         /* 1-inch margins all round */
  min-height: 1122px;
  box-sizing: border-box;
}
#wj-render-host h1      { font-size: 24px; font-weight: 700; margin: .5em 0; color: #000; }
#wj-render-host h2      { font-size: 20px; font-weight: 700; margin: .5em 0; color: #000; }
#wj-render-host h3      { font-size: 18px; font-weight: 700; margin: .5em 0; color: #000; }
#wj-render-host h4      { font-size: 16px; font-weight: 700; margin: .5em 0; }
#wj-render-host p       { margin: 0 0 .6em 0; }
#wj-render-host strong,
#wj-render-host b       { font-weight: 700; }
#wj-render-host em,
#wj-render-host i       { font-style: italic; }
#wj-render-host table   { width: 100%; border-collapse: collapse; margin: .75em 0; font-size: 14px; }
#wj-render-host td,
#wj-render-host th      { border: 1px solid #999; padding: 5px 10px; vertical-align: top; }
#wj-render-host th      { background: #f3f4f6; font-weight: 700; }
#wj-render-host ul,
#wj-render-host ol      { margin: .5em 0 .5em 1.5em; padding: 0; }
#wj-render-host li      { margin: .2em 0; }
#wj-render-host img     { max-width: 100%; height: auto; display: block; }
#wj-render-host a       { color: #2563eb; text-decoration: underline; }
#wj-render-host blockquote {
  border-left: 4px solid #d1d5db; margin: .75em 0 .75em 1em;
  padding: .25em 0 .25em 1em; color: #4b5563;
}
#wj-render-host hr      { border: none; border-top: 1px solid #e5e7eb; margin: 1em 0; }

@media (max-width: 640px) {
  .wj-dropzone { padding: 1.5rem 1rem; }
  .wj-dz-icon  { font-size: 2rem; }
}
</style>


<div id="wj-render-host" aria-hidden="true"></div>

<div class="min-h-screen bg-gray-50">

  
  <div class="bg-white border-b border-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
      <div class="flex items-center gap-4 mb-4">
        <div class="w-14 h-14 rounded-xl bg-blue-100 flex items-center justify-center text-3xl flex-shrink-0">
          📝
        </div>
        <div>
          <h1 class="text-3xl font-bold text-gray-900">Word to JPG Converter</h1>
          <p class="text-gray-500 mt-1">Convert each page of a .docx Word document into a JPG image — free, instant, fully private.</p>
        </div>
      </div>
      <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [
          ['label' => 'Home', 'url' => url('/')],
          ['label' => $tool->category->name, 'url' => route('categories.show', $tool->category)],
          ['label' => 'Word to JPG']
      ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
          ['label' => 'Home', 'url' => url('/')],
          ['label' => $tool->category->name, 'url' => route('categories.show', $tool->category)],
          ['label' => 'Word to JPG']
      ])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $attributes = $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $component = $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>
    </div>
  </div>

  <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
    <div class="grid gap-8 lg:grid-cols-3">

      
      <div class="lg:col-span-2 space-y-5" x-data="wjTool()">

        
        <div class="card p-6 space-y-5" x-show="!converting && !pages.length">

          <h2 class="text-lg font-semibold text-gray-900">Upload a Word Document</h2>

          
          <div class="wj-privacy">
            <span class="flex-shrink-0 mt-0.5">🔒</span>
            <span>Your document is processed entirely in your browser — nothing is ever uploaded to a server.</span>
          </div>

          
          <div class="wj-warn">
            <span class="flex-shrink-0">⚠️</span>
            <span>
              <strong>Supported format: .docx only</strong> (Word 2007 and newer).
              Legacy .doc files are not supported — please re-save as .docx first.
            </span>
          </div>

          
          <div
            :class="['wj-dropzone', isDragging ? 'wj-drag-over' : '', file ? 'wj-has-file' : '']"
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop.prevent="onDrop($event)"
            @click="$refs.fileInput.click()"
          >
            <input
              type="file"
              x-ref="fileInput"
              accept=".docx,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
              @change="onFileChange($event)"
              class="hidden"
            >

            <div x-show="!file">
              <div class="wj-dz-icon">📄 → 🖼️</div>
              <p class="wj-dz-title">Drag &amp; drop your .docx file here</p>
              <p class="wj-dz-sub">or click to browse &nbsp;·&nbsp; .docx only &nbsp;·&nbsp; max 30 MB</p>
            </div>

            <div x-show="file">
              <div class="wj-dz-icon">✅</div>
              <p class="wj-dz-title" x-text="file ? file.name : ''"></p>
              <p class="wj-dz-sub" x-text="file ? (formatSize(file.size) + ' — click to change') : ''"></p>
            </div>
          </div>

          
          <div x-show="fileError" x-transition class="wj-error">
            <span>⚠</span><span x-text="fileError"></span>
          </div>

          
          <div x-show="convError" x-transition class="wj-error">
            <span>⚠</span><span x-text="convError"></span>
          </div>

          
          <button
            @click="convert()"
            :disabled="!file || !!fileError || libsLoading"
            class="wj-convert-btn"
          >
            <span x-show="libsLoading" class="wj-spin"></span>
            <span x-show="libsLoading">Loading converter engine…</span>
            <span x-show="!libsLoading">🔄 Convert to JPG</span>
          </button>

        </div>

        
        <div class="card p-6 space-y-4" x-show="converting" x-transition>

          <h2 class="text-lg font-semibold text-gray-900">Converting…</h2>

          <div class="flex justify-between text-sm font-medium text-gray-600">
            <span x-text="stageLabel"></span>
            <span class="text-blue-600 font-bold" x-text="progressPct + '%'"></span>
          </div>

          <div class="wj-progress-track">
            <div class="wj-progress-fill" :style="'width:' + progressPct + '%'"></div>
          </div>

          <div class="flex items-center gap-2 text-xs text-gray-400">
            <span class="wj-spin wj-spin-dark" style="width:.85em;height:.85em;border-width:1.5px"></span>
            <span>Rendering document in your browser — please keep this tab open.</span>
          </div>

        </div>

        
        <div class="card p-6 space-y-5" x-show="pages.length > 0" x-transition>

          
          <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
              <h2 class="text-lg font-semibold text-gray-900">
                <span x-text="pages.length"></span>
                page<span x-show="pages.length !== 1">s</span> converted
              </h2>
              <p class="text-xs text-gray-400 mt-0.5" x-text="'from: ' + (file ? file.name : '')"></p>
            </div>
            <div class="flex flex-wrap gap-2">
              <button @click="downloadAll()" :disabled="zipping" class="wj-zip-btn">
                <span x-show="zipping" class="wj-spin" style="width:.85em;height:.85em;border-color:rgba(255,255,255,.4);border-top-color:#fff;border-width:1.5px"></span>
                <span x-show="!zipping">⬇</span>
                <span x-show="zipping">Preparing ZIP…</span>
                <span x-show="!zipping">Download All as ZIP</span>
              </button>
              <button @click="reset()" class="btn btn-secondary text-sm">↺ Convert Another</button>
            </div>
          </div>

          
          <div x-show="warnings.length > 0" class="wj-warn text-xs">
            <span class="flex-shrink-0">ℹ️</span>
            <span>
              <strong>Formatting notes:</strong>
              Some elements (e.g. SmartArt, text boxes, custom fonts) may not have rendered perfectly.
              <span x-text="warnings.length + ' warning' + (warnings.length !== 1 ? 's' : '') + ' from the document parser.'"></span>
            </span>
          </div>

          <p class="wj-div">JPG Pages</p>

          
          <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            <template x-for="page in pages" :key="page.pageNum">
              <div class="wj-page-card">
                <div class="wj-thumb-wrap">
                  <img :src="page.dataUrl"
                       :alt="'Page ' + page.pageNum"
                       class="wj-thumb"
                       loading="lazy">
                </div>
                <div class="wj-page-info">
                  <p class="wj-page-num"   x-text="'Page ' + page.pageNum"></p>
                  <p class="wj-page-dims"  x-text="page.width + ' × ' + page.height + ' px'"></p>
                  <button @click="downloadPage(page)" class="wj-dl-btn">⬇ Save JPG</button>
                </div>
              </div>
            </template>
          </div>

          
          <div x-show="zipError" x-transition class="wj-error">
            <span>⚠</span><span x-text="zipError"></span>
          </div>

          
          <div class="wj-info text-xs">
            <span class="flex-shrink-0">ℹ️</span>
            <span>
              Pages are rendered at <strong>1191×1683 px (A4, 1.5× scale)</strong>.
              Complex layouts, custom fonts, headers/footers, and page borders may differ from the original Word document.
            </span>
          </div>

        </div>

        
        <div class="card p-6" x-show="!converting">
          <p class="wj-div mb-4">How It Works</p>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
            <div class="flex gap-3">
              <span class="text-2xl flex-shrink-0">📤</span>
              <div>
                <p class="font-semibold text-gray-800">1. Upload .docx</p>
                <p class="text-gray-500 text-xs mt-0.5">Select a Word 2007+ (.docx) document from your device.</p>
              </div>
            </div>
            <div class="flex gap-3">
              <span class="text-2xl flex-shrink-0">⚙️</span>
              <div>
                <p class="font-semibold text-gray-800">2. Browser Renders</p>
                <p class="text-gray-500 text-xs mt-0.5">The document is parsed and rendered as HTML inside your browser, then captured as A4-sized page images.</p>
              </div>
            </div>
            <div class="flex gap-3">
              <span class="text-2xl flex-shrink-0">💾</span>
              <div>
                <p class="font-semibold text-gray-800">3. Download JPGs</p>
                <p class="text-gray-500 text-xs mt-0.5">Save individual pages or download all pages in a single ZIP file.</p>
              </div>
            </div>
          </div>
        </div>

      </div>

      
      <div class="space-y-6">

        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Category</h3>
          <a href="<?php echo e(route('categories.show', $tool->category)); ?>"
             class="flex items-center gap-3 p-3 rounded-xl bg-brand-50 hover:bg-brand-100 transition-colors">
            <span class="text-xl"><?php echo e($tool->category->icon); ?></span>
            <span class="font-medium text-brand-700"><?php echo e($tool->category->name); ?></span>
          </a>
        </div>

        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Supported Features</h3>
          <ul class="space-y-2 text-xs text-gray-600">
            <li class="flex gap-2"><span class="text-green-600 font-bold">✓</span><span>Paragraphs, headings (H1–H4)</span></li>
            <li class="flex gap-2"><span class="text-green-600 font-bold">✓</span><span>Bold, italic, underline formatting</span></li>
            <li class="flex gap-2"><span class="text-green-600 font-bold">✓</span><span>Tables and lists</span></li>
            <li class="flex gap-2"><span class="text-green-600 font-bold">✓</span><span>Embedded images</span></li>
            <li class="flex gap-2"><span class="text-green-600 font-bold">✓</span><span>Hyperlinks</span></li>
            <li class="flex gap-2"><span class="text-green-600 font-bold">✓</span><span>Blockquotes</span></li>
            <li class="flex gap-2 mt-2"><span class="text-yellow-500 font-bold">~</span><span>Multi-column layouts (approximate)</span></li>
            <li class="flex gap-2"><span class="text-red-400 font-bold">✗</span><span>Headers, footers, page numbers</span></li>
            <li class="flex gap-2"><span class="text-red-400 font-bold">✗</span><span>SmartArt, WordArt, shapes</span></li>
            <li class="flex gap-2"><span class="text-red-400 font-bold">✗</span><span>Legacy .doc format</span></li>
          </ul>
        </div>

        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Tips</h3>
          <ul class="space-y-2 text-xs text-gray-600">
            <li class="flex gap-2"><span class="text-blue-500 font-bold flex-shrink-0">•</span><span>For best results, use simple documents: text, images, and basic tables.</span></li>
            <li class="flex gap-2"><span class="text-blue-500 font-bold flex-shrink-0">•</span><span>Pages are split based on A4 dimensions — very long paragraphs may span page boundaries.</span></li>
            <li class="flex gap-2"><span class="text-blue-500 font-bold flex-shrink-0">•</span><span>Output JPGs are 1191×1683 px (A4 at 144 DPI) — suitable for viewing and printing.</span></li>
            <li class="flex gap-2"><span class="text-blue-500 font-bold flex-shrink-0">•</span><span>Large or image-heavy documents may take 10–30 seconds to render.</span></li>
            <li class="flex gap-2"><span class="text-green-600 font-bold flex-shrink-0">🔒</span><span>Your file never leaves your device.</span></li>
          </ul>
        </div>

        <?php if($relatedTools->count() > 0): ?>
        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Related Tools</h3>
          <div class="space-y-2">
            <?php $__currentLoopData = $relatedTools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('tools.show', $related)); ?>"
               class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-gray-50 transition-colors group">
              <span class="text-lg"><?php echo e($related->icon); ?></span>
              <span class="text-sm text-gray-700 group-hover:text-brand-600 transition-colors"><?php echo e($related->name); ?></span>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>

<script src="https://unpkg.com/mammoth@1.6.0/mammoth.browser.min.js"></script>

<script src="https://unpkg.com/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script>
/* ─────────────────────────────────────────────────────────────────
   Word to JPG Converter — Alpine.js component  (prefix: wj-)

   Conversion pipeline (all in-browser):
   ┌─────────────────────────────────────────────────────────────┐
   │  .docx file (ArrayBuffer)                                   │
   │       │                                                     │
   │       ▼  mammoth.convertToHtml()                           │
   │  Semantic HTML string                                       │
   │       │                                                     │
   │       ▼  Inject into #wj-render-host                       │
   │  Styled A4-width DOM container (position:fixed, off-screen)│
   │       │                                                     │
   │       ▼  html2canvas(container, { scale:1, width:794 })    │
   │  Full-height canvas (794 × totalHeight)                    │
   │       │                                                     │
   │       ▼  For each page: drawImage() crop + scale 1.5×      │
   │  Per-page canvas (1191 × 1683 px @ A4)                     │
   │       │                                                     │
   │       ▼  canvas.toDataURL('image/jpeg', 0.92)              │
   │  JPG data URLs — stored in this.pages[]                    │
   └─────────────────────────────────────────────────────────────┘

   Page dimensions:
     A4 at 96 DPI = 794 × 1122 px (source scale 1)
     Output JPG   = 1191 × 1683 px (1.5× upscale for clarity)

   Capped at 20 pages to avoid browser canvas memory limits.
   (At scale 1 a 20-page canvas = 22,440 px tall — well within limits.)
─────────────────────────────────────────────────────────────────── */
function wjTool() {
  return {

    // ── File state ───────────────────────────────────────
    file:       null,
    fileError:  '',
    isDragging: false,

    // ── Conversion state ─────────────────────────────────
    libsLoading: false,
    converting:  false,
    stage:       '',    // 'reading' | 'html' | 'rendering' | 'paging'
    currentPage: 0,
    totalPages:  0,
    warnings:    [],

    // ── Results ──────────────────────────────────────────
    pages:    [],   // [{ pageNum, dataUrl, width, height }]
    convError:'',

    // ── ZIP ──────────────────────────────────────────────
    zipping:  false,
    zipError: '',

    // ── Computed ─────────────────────────────────────────
    get progressPct() {
      if (this.stage === 'reading')   return 10;
      if (this.stage === 'html')      return 25;
      if (this.stage === 'rendering') return 40;
      if (this.stage === 'paging' && this.totalPages) {
        return Math.round(40 + (this.currentPage / this.totalPages) * 58);
      }
      return 5;
    },

    get stageLabel() {
      if (this.stage === 'reading')   return 'Reading document…';
      if (this.stage === 'html')      return 'Parsing content…';
      if (this.stage === 'rendering') return 'Rendering page layout… (this may take a moment)';
      if (this.stage === 'paging')
        return 'Generating page ' + this.currentPage + ' of ' + this.totalPages + '…';
      return 'Starting…';
    },

    // ── File input ───────────────────────────────────────
    onFileChange(e) {
      var f = e.target.files[0];
      if (f) this._setFile(f);
    },

    onDrop(e) {
      this.isDragging = false;
      var f = e.dataTransfer.files[0];
      if (f) this._setFile(f);
    },

    _setFile(file) {
      this.fileError = '';
      this.convError = '';
      this.pages     = [];
      this.warnings  = [];
      var err = this._validate(file);
      if (err) { this.fileError = err; this.file = null; return; }
      this.file = file;
    },

    _validate(file) {
      if (!file) return 'No file selected.';

      var name = file.name.toLowerCase();
      var isDocx = name.endsWith('.docx')
        || file.type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
      var isDoc  = name.endsWith('.doc');

      if (isDoc && !isDocx)
        return 'Legacy .doc files are not supported. Please re-save your document in .docx format (File → Save As → Word Document) and try again.';

      if (!isDocx)
        return 'Only .docx files are accepted. Please select a Word 2007+ document.';

      if (file.size > 30 * 1024 * 1024)
        return 'File too large (' + this.formatSize(file.size) + '). Maximum size is 30 MB.';

      return '';
    },

    // ── Conversion ───────────────────────────────────────
    async convert() {
      if (!this.file || this.fileError) return;

      // Verify libraries are available (loaded via script tags above)
      if (!window.mammoth || !window.html2canvas) {
        this.convError = 'Converter libraries failed to load. Please refresh the page and try again.';
        return;
      }

      this.convError   = '';
      this.pages       = [];
      this.warnings    = [];
      this.currentPage = 0;
      this.totalPages  = 0;
      this.converting  = true;

      // A4 dimensions at 96 DPI
      var A4_W = 794;
      var A4_H = 1122;
      var MAX_PAGES = 20;
      var OUT_SCALE = 1.5;  // output JPG upscale factor

      var renderHost = document.getElementById('wj-render-host');

      try {
        // ── Phase 1: Read file as ArrayBuffer ──
        this.stage = 'reading';
        var arrayBuffer = await this.file.arrayBuffer();

        // ── Phase 2: Convert DOCX → HTML with mammoth ──
        this.stage = 'html';
        var mammothResult;
        try {
          mammothResult = await window.mammoth.convertToHtml({ arrayBuffer: arrayBuffer });
        } catch (parseErr) {
          var msg = (parseErr && parseErr.message) ? parseErr.message : String(parseErr);
          if (msg.includes('zip') || msg.includes('ZIP'))
            throw new Error('Could not open the file. Make sure it is a valid .docx (not a renamed .doc or corrupted file).');
          throw new Error('Failed to parse the document: ' + msg);
        }

        // Collect non-fatal warnings from mammoth
        this.warnings = (mammothResult.messages || [])
          .filter(function(m) { return m.type === 'warning'; })
          .map(function(m) { return m.message; });

        var htmlContent = mammothResult.value || '';
        if (!htmlContent.trim())
          throw new Error('The document appears to be empty or contains no extractable content.');

        // ── Phase 3: Inject HTML into the off-screen render host ──
        this.stage = 'rendering';

        renderHost.innerHTML =
          '<div class="wj-doc-body">' + htmlContent + '</div>';

        // Allow browser to paint the injected content before capturing.
        // Two rAF frames + a small timeout gives the layout engine time to settle.
        await new Promise(function(r) { requestAnimationFrame(function() { requestAnimationFrame(r); }); });
        await new Promise(function(r) { setTimeout(r, 300); });

        // ── Phase 4: Render the full document to a canvas ──
        var fullCanvas = await window.html2canvas(renderHost, {
          scale:        1,           // render at 1:1 scale; we upscale per-page later
          useCORS:      true,
          allowTaint:   true,
          backgroundColor: '#ffffff',
          logging:      false,
          width:        A4_W,
          windowWidth:  A4_W,
          scrollX:      0,
          scrollY:      -window.scrollY,
        });

        var totalH   = fullCanvas.height;
        var pageCount = Math.min(MAX_PAGES, Math.max(1, Math.ceil(totalH / A4_H)));
        this.totalPages = pageCount;

        var OUT_W = Math.round(A4_W * OUT_SCALE); // 1191
        var OUT_H = Math.round(A4_H * OUT_SCALE); // 1683

        // ── Phase 5: Crop the full canvas into A4-sized pages ──
        for (var i = 0; i < pageCount; i++) {
          this.stage       = 'paging';
          this.currentPage = i + 1;

          // Yield to the event loop so the progress UI re-renders
          await new Promise(function(r) { setTimeout(r, 15); });

          var pageCanvas = document.createElement('canvas');
          pageCanvas.width  = OUT_W;
          pageCanvas.height = OUT_H;
          var ctx = pageCanvas.getContext('2d');

          // White background (important for JPG — no alpha channel)
          ctx.fillStyle = '#ffffff';
          ctx.fillRect(0, 0, OUT_W, OUT_H);

          // Source region in the full canvas
          var srcY = i * A4_H;
          var srcH = Math.min(A4_H, totalH - srcY);

          // Scale the source region up to the output dimensions
          ctx.drawImage(
            fullCanvas,
            0,    srcY,   // source x, y
            A4_W, srcH,   // source width, height
            0,    0,      // dest x, y
            OUT_W, Math.round(srcH * OUT_SCALE) // dest width, height
          );

          var jpgDataUrl = pageCanvas.toDataURL('image/jpeg', 0.92);

          this.pages.push({
            pageNum: i + 1,
            dataUrl: jpgDataUrl,
            width:   OUT_W,
            height:  OUT_H,
          });

          // Free the page canvas memory immediately
          pageCanvas.width  = 0;
          pageCanvas.height = 0;
        }

        // ── Cleanup ──
        renderHost.innerHTML = '';

      } catch (err) {
        this.convError = this._friendlyError(err);
        renderHost.innerHTML = '';
      } finally {
        this.converting = false;
        this.stage      = '';
      }
    },

    _friendlyError(err) {
      if (!err) return 'An unknown error occurred.';
      var msg = String(err.message || err);

      if (msg.includes('zip') || msg.includes('ZIP') || msg.includes('File is not'))
        return 'Could not open the file. Make sure it is a valid .docx file (not a renamed .doc or corrupted archive).';
      if (msg.includes('memory') || msg.includes('quota') || msg.includes('Maximum call stack'))
        return 'The browser ran out of memory while rendering. Try a smaller document or close other browser tabs.';
      if (msg.includes('tainted') || msg.includes('canvas') || msg.includes('cross'))
        return 'Canvas rendering was blocked (possibly due to embedded external images). Try a document without linked images.';
      if (msg.includes('empty') || msg.includes('no extractable'))
        return msg; // already friendly
      return 'Conversion failed: ' + msg;
    },

    // ── Downloads ─────────────────────────────────────────
    downloadPage(page) {
      var baseName = this.file ? this.file.name.replace(/\.docx$/i, '') : 'document';
      var name = baseName + '-page-' + String(page.pageNum).padStart(3, '0') + '.jpg';
      var a = document.createElement('a');
      a.href     = page.dataUrl;
      a.download = name;
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
    },

    async downloadAll() {
      if (!this.pages.length) return;
      this.zipping  = true;
      this.zipError = '';

      try {
        // Load JSZip lazily — only when user clicks Download All
        if (!window.JSZip) {
          await this._loadScript(
            'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js'
          );
        }

        var zip      = new window.JSZip();
        var baseName = this.file ? this.file.name.replace(/\.docx$/i, '') : 'document';
        var folder   = zip.folder(baseName + '-pages');

        for (var i = 0; i < this.pages.length; i++) {
          var p    = this.pages[i];
          var name = baseName + '-page-' + String(p.pageNum).padStart(3, '0') + '.jpg';
          var b64  = p.dataUrl.split(',')[1];
          folder.file(name, b64, { base64: true });
        }

        var content = await zip.generateAsync({
          type: 'blob',
          compression: 'DEFLATE',
          compressionOptions: { level: 6 }
        });

        var url = URL.createObjectURL(content);
        var a   = document.createElement('a');
        a.href     = url;
        a.download = baseName + '-pages.zip';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        setTimeout(function() { URL.revokeObjectURL(url); }, 2000);

      } catch (err) {
        this.zipError = 'ZIP creation failed: ' + (err.message || err);
      } finally {
        this.zipping = false;
      }
    },

    // ── Reset ─────────────────────────────────────────────
    reset() {
      this.file        = null;
      this.fileError   = '';
      this.convError   = '';
      this.zipError    = '';
      this.isDragging  = false;
      this.libsLoading = false;
      this.converting  = false;
      this.stage       = '';
      this.currentPage = 0;
      this.totalPages  = 0;
      this.pages       = [];
      this.warnings    = [];
      this.zipping     = false;

      if (this.$refs.fileInput) this.$refs.fileInput.value = '';

      // Clear render host in case of leftover content
      var rh = document.getElementById('wj-render-host');
      if (rh) rh.innerHTML = '';
    },

    // ── Utilities ─────────────────────────────────────────
    formatSize(bytes) {
      if (!bytes) return '0 B';
      if (bytes < 1024)        return bytes + ' B';
      if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
      return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    },

    _loadScript(src) {
      return new Promise(function(resolve, reject) {
        if (document.querySelector('script[src="' + src + '"]')) { resolve(); return; }
        var s     = document.createElement('script');
        s.src     = src;
        s.onload  = resolve;
        s.onerror = function() { reject(new Error('Failed to load: ' + src)); };
        document.head.appendChild(s);
      });
    },
  };
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\word-to-jpg.blade.php ENDPATH**/ ?>