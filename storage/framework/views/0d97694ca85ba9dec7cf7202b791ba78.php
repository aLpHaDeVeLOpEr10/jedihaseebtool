

<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ══════════════════════════════════════════════════════════════
   PDF to JPG Converter  —  prefix: pj-
   Theme: PDF-brand crimson red (#b91c1c) + warm gray chrome
   Libraries: PDF.js 3.11.174 (canvas rendering) +
              JSZip 3.10.1 (ZIP download, loaded lazily)
   Processing is 100% in-browser — no file is ever uploaded.
══════════════════════════════════════════════════════════════ */

/* ── Drop zone ──────────────────────────────────────────── */
.pj-dropzone {
  border: 2.5px dashed #fca5a5;
  border-radius: 1rem;
  padding: 2.25rem 1.5rem;
  text-align: center;
  cursor: pointer;
  transition: all .18s;
  background: #fef2f2;
  position: relative;
  user-select: none;
}
.pj-dropzone:hover, .pj-dropzone.pj-drag-over {
  border-color: #b91c1c;
  background: #fee2e2;
  transform: scale(1.01);
}
.pj-dropzone.pj-has-file {
  border-color: #16a34a;
  background: #f0fdf4;
}
.pj-dz-icon {
  font-size: 2.5rem;
  line-height: 1;
  margin-bottom: .6rem;
}
.pj-dz-title {
  font-size: 1rem;
  font-weight: 700;
  color: #374151;
  margin-bottom: .25rem;
  word-break: break-all;
}
.pj-dz-sub {
  font-size: .8rem;
  color: #9ca3af;
}

/* ── Progress bar ───────────────────────────────────────── */
.pj-progress-track {
  width: 100%; height: .65rem; border-radius: 9999px;
  background: #fce7f3; overflow: hidden;
}
.pj-progress-fill {
  height: 100%; border-radius: 9999px;
  background: linear-gradient(90deg, #b91c1c, #ef4444);
  transition: width .3s ease;
  position: relative; overflow: hidden;
}
.pj-progress-fill::after {
  content: '';
  position: absolute; inset: 0;
  background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,.35) 50%, transparent 100%);
  animation: pjShimmer 1.4s infinite;
}
@keyframes pjShimmer { 0%{transform:translateX(-100%)} 100%{transform:translateX(200%)} }

/* ── Results grid ───────────────────────────────────────── */
.pj-page-card {
  border: 1.5px solid #f3f4f6;
  border-radius: .875rem;
  overflow: hidden;
  background: #fff;
  transition: box-shadow .15s, transform .15s;
}
.pj-page-card:hover {
  box-shadow: 0 4px 16px rgba(0,0,0,.1);
  transform: translateY(-1px);
}
.pj-thumb-wrap {
  position: relative;
  width: 100%;
  padding-top: 141.4%; /* A4 aspect ratio */
  background: #f8fafc;
  overflow: hidden;
}
.pj-thumb {
  position: absolute; inset: 0;
  width: 100%; height: 100%;
  object-fit: contain;
  display: block;
}
.pj-page-info {
  padding: .5rem .65rem .6rem;
  border-top: 1px solid #f3f4f6;
}
.pj-page-num  { font-size: .72rem; font-weight: 800; color: #374151; }
.pj-page-dims { font-size: .65rem; color: #9ca3af; margin-top: .1rem; }
.pj-dl-btn {
  display: flex; align-items: center; justify-content: center; gap: .3rem;
  width: 100%; margin-top: .5rem; padding: .35rem .5rem;
  border-radius: .55rem; font-size: .7rem; font-weight: 700;
  cursor: pointer; transition: all .14s;
  border: 1.5px solid #fca5a5; background: #fef2f2; color: #b91c1c;
}
.pj-dl-btn:hover { background: #b91c1c; color: #fff; border-color: #b91c1c; }

/* ── Download all button ────────────────────────────────── */
.pj-zip-btn {
  display: inline-flex; align-items: center; gap: .4rem;
  padding: .55rem 1.2rem; border-radius: .875rem;
  font-size: .82rem; font-weight: 700; cursor: pointer;
  transition: all .14s;
  background: #166534; color: #fff; border: 1.5px solid #15803d;
  box-shadow: 0 2px 8px rgba(22,101,52,.2);
}
.pj-zip-btn:hover { background: #15803d; box-shadow: 0 3px 12px rgba(22,101,52,.35); }
.pj-zip-btn:disabled { opacity: .5; cursor: not-allowed; }

/* ── Privacy badge ──────────────────────────────────────── */
.pj-privacy {
  display: flex; align-items: center; gap: .5rem;
  padding: .5rem .85rem; border-radius: .75rem;
  background: #f0fdf4; border: 1px solid #bbf7d0;
  font-size: .75rem; color: #166534; font-weight: 500;
}

/* ── Settings row ───────────────────────────────────────── */
.pj-select {
  width: 100%; padding: .45rem .75rem;
  border: 1.5px solid #e2e8f0; border-radius: .75rem;
  font-size: .82rem; font-weight: 600; color: #374151;
  background: #fff; outline: none; cursor: pointer;
  transition: border-color .14s;
}
.pj-select:focus { border-color: #fca5a5; box-shadow: 0 0 0 3px rgba(185,28,28,.1); }

/* ── Large-file warning ─────────────────────────────────── */
.pj-warn {
  display: flex; align-items: flex-start; gap: .5rem;
  padding: .65rem .9rem; border-radius: .75rem;
  background: #fffbeb; border: 1.5px solid #fde68a;
  font-size: .8rem; color: #92400e;
}

/* ── Error strip ────────────────────────────────────────── */
.pj-error {
  display: flex; align-items: flex-start; gap: .5rem;
  padding: .65rem .9rem; border-radius: .75rem;
  background: #fef2f2; border: 1.5px solid #fecaca;
  font-size: .8rem; color: #991b1b; font-weight: 500;
}

/* ── Section divider ────────────────────────────────────── */
.pj-div {
  display: flex; align-items: center; gap: .6rem;
  font-size: .6rem; font-weight: 800; text-transform: uppercase;
  letter-spacing: .1em; color: #6b7280;
}
.pj-div::before,.pj-div::after { content:''; flex:1; height:1px; background:#e5e7eb; }

/* Spinner utility (in case site doesn't define it) */
@keyframes pjSpin { to { transform: rotate(360deg); } }
.pj-spin {
  display: inline-block; width: 1em; height: 1em; border-radius: 50%;
  border: 2px solid currentColor; border-top-color: transparent;
  animation: pjSpin .6s linear infinite; flex-shrink: 0;
}

/* ── Convert button ─────────────────────────────────────── */
.pj-convert-btn {
  width: 100%; padding: .9rem 1.5rem; border-radius: .875rem;
  font-size: 1rem; font-weight: 800; cursor: pointer;
  display: flex; align-items: center; justify-content: center; gap: .5rem;
  transition: all .16s;
  background: linear-gradient(135deg, #991b1b, #b91c1c, #dc2626);
  color: #fff; border: none;
  box-shadow: 0 4px 14px rgba(185,28,28,.35);
}
.pj-convert-btn:hover:not(:disabled) { box-shadow: 0 6px 20px rgba(185,28,28,.5); transform: translateY(-1px); }
.pj-convert-btn:disabled { opacity: .4; cursor: not-allowed; transform: none; box-shadow: none; }

@media (max-width: 640px) {
  .pj-dropzone { padding: 1.5rem 1rem; }
  .pj-dz-icon  { font-size: 2rem; }
}
</style>

<div class="min-h-screen bg-gray-50">

  
  <div class="bg-white border-b border-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
      <div class="flex items-center gap-4 mb-4">
        <div class="tool-icon bg-red-100 text-red-600 text-3xl w-14 h-14 flex items-center justify-content-center rounded-xl">
          📄
        </div>
        <div>
          <h1 class="text-3xl font-bold text-gray-900">PDF to JPG Converter</h1>
          <p class="text-gray-500 mt-1">Convert every page of a PDF into high-quality JPG images — free, instant, and 100% in-browser.</p>
        </div>
      </div>
      <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [
          ['label' => 'Home', 'url' => url('/')],
          ['label' => $tool->category->name, 'url' => route('categories.show', $tool->category)],
          ['label' => 'PDF to JPG']
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
          ['label' => 'PDF to JPG']
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

      
      <div class="lg:col-span-2 space-y-5"
           x-data="pjTool()"
           x-init="init()">

        
        <div class="card p-6 space-y-5" x-show="!converting && !pages.length">

          <h2 class="text-lg font-semibold text-gray-900">Select a PDF</h2>

          
          <div class="pj-privacy">
            <span>🔒</span>
            <span>Your file never leaves your browser — conversion happens entirely on your device.</span>
          </div>

          
          <div
            :class="['pj-dropzone', isDragging ? 'pj-drag-over' : '', file ? 'pj-has-file' : '']"
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop.prevent="onDrop($event)"
            @click="$refs.fileInput.click()"
          >
            <input
              type="file"
              x-ref="fileInput"
              accept=".pdf,application/pdf"
              @change="onFileChange($event)"
              class="hidden"
            >

            
            <div x-show="!file">
              <div class="pj-dz-icon">📄 → 🖼️</div>
              <p class="pj-dz-title">Drag &amp; drop your PDF here</p>
              <p class="pj-dz-sub">or click to browse &nbsp;·&nbsp; PDF only &nbsp;·&nbsp; max 50 MB</p>
            </div>

            
            <div x-show="file">
              <div class="pj-dz-icon">✅</div>
              <p class="pj-dz-title" x-text="file ? file.name : ''"></p>
              <p class="pj-dz-sub" x-text="file ? formatSize(file.size) + '  ·  click to change' : ''"></p>
            </div>
          </div>

          
          <div x-show="fileError" x-transition class="pj-error">
            <span>⚠</span><span x-text="fileError"></span>
          </div>

          
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="form-label text-xs mb-1 block">JPG Quality</label>
              <select x-model.number="quality" class="pj-select">
                <option value="0.70">Low — 70% (smallest files)</option>
                <option value="0.82">Medium — 82% (balanced)</option>
                <option value="0.92">High — 92% (recommended)</option>
                <option value="1.00">Maximum — 100% (largest files)</option>
              </select>
            </div>
            <div>
              <label class="form-label text-xs mb-1 block">Output Resolution</label>
              <select x-model.number="scale" class="pj-select">
                <option value="1.0">1× — 72 DPI (screen)</option>
                <option value="1.5">1.5× — 108 DPI (default)</option>
                <option value="2.0">2× — 144 DPI (sharp)</option>
                <option value="3.0">3× — 216 DPI (print-quality)</option>
              </select>
            </div>
          </div>

          
          <div x-show="pageCount > 15" x-transition class="pj-warn">
            <span>⚠️</span>
            <span>
              This PDF has <strong x-text="pageCount"></strong> pages.
              Conversion may take 15–30 seconds at higher resolutions.
            </span>
          </div>

          
          <div x-show="convError" x-transition class="pj-error">
            <span>⚠</span><span x-text="convError"></span>
          </div>

          
          <button
            @click="convert()"
            :disabled="!file || !!fileError || libLoading"
            class="pj-convert-btn"
          >
            <span x-show="libLoading" class="pj-spin"></span>
            <span x-show="libLoading">Loading PDF engine…</span>
            <span x-show="!libLoading">🔄 Convert to JPG</span>
          </button>

        </div>

        
        <div class="card p-6 space-y-4" x-show="converting" x-transition>

          <h2 class="text-lg font-semibold text-gray-900">Converting…</h2>

          <div class="flex justify-between text-sm text-gray-600">
            <span>
              <span x-show="currentPage > 0">
                Page <strong x-text="currentPage"></strong>
                of <strong x-text="totalPages"></strong>
              </span>
              <span x-show="currentPage === 0">Loading PDF…</span>
            </span>
            <span class="font-bold text-red-700" x-text="progressPct + '%'"></span>
          </div>

          <div class="pj-progress-track">
            <div class="pj-progress-fill" :style="'width:' + progressPct + '%'"></div>
          </div>

          <div class="flex items-center gap-2 text-xs text-gray-400">
            <span class="pj-spin" style="width:.85em;height:.85em;border-width:1.5px"></span>
            <span>Rendering pages in your browser — please keep this tab open.</span>
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
              <button
                @click="downloadAll()"
                :disabled="zipping"
                class="pj-zip-btn"
              >
                <span x-show="zipping" class="pj-spin" style="width:.9em;height:.9em;border-color:rgba(255,255,255,.5);border-top-color:transparent;"></span>
                <span x-show="!zipping">⬇</span>
                <span x-show="zipping">Preparing ZIP…</span>
                <span x-show="!zipping">Download All as ZIP</span>
              </button>
              <button @click="reset()" class="btn btn-secondary text-sm">↺ Convert Another</button>
            </div>
          </div>

          <p class="pj-div">JPG Pages</p>

          
          <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            <template x-for="page in pages" :key="page.pageNum">
              <div class="pj-page-card">
                <div class="pj-thumb-wrap">
                  <img :src="page.dataUrl"
                       :alt="'Page ' + page.pageNum"
                       class="pj-thumb"
                       loading="lazy">
                </div>
                <div class="pj-page-info">
                  <p class="pj-page-num" x-text="'Page ' + page.pageNum"></p>
                  <p class="pj-page-dims" x-text="page.width + ' × ' + page.height + ' px'"></p>
                  <button @click="downloadPage(page)" class="pj-dl-btn">
                    ⬇ Save JPG
                  </button>
                </div>
              </div>
            </template>
          </div>

          
          <div x-show="convError" x-transition class="pj-error">
            <span>⚠</span><span x-text="convError"></span>
          </div>

          
          <div x-show="zipError" x-transition class="pj-error">
            <span>⚠</span><span x-text="zipError"></span>
          </div>

        </div>

        
        <div class="card p-6" x-show="!converting">
          <p class="pj-div mb-4">How It Works</p>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
            <div class="flex gap-3">
              <span class="text-2xl flex-shrink-0">📤</span>
              <div>
                <p class="font-semibold text-gray-800">1. Upload PDF</p>
                <p class="text-gray-500 text-xs mt-0.5">Select any PDF — presentations, invoices, scans, or documents.</p>
              </div>
            </div>
            <div class="flex gap-3">
              <span class="text-2xl flex-shrink-0">⚙️</span>
              <div>
                <p class="font-semibold text-gray-800">2. Choose Quality</p>
                <p class="text-gray-500 text-xs mt-0.5">Pick JPG quality and resolution. Higher = sharper but larger files.</p>
              </div>
            </div>
            <div class="flex gap-3">
              <span class="text-2xl flex-shrink-0">💾</span>
              <div>
                <p class="font-semibold text-gray-800">3. Download JPGs</p>
                <p class="text-gray-500 text-xs mt-0.5">Save pages individually or download all in a single ZIP file.</p>
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
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Tips</h3>
          <ul class="space-y-2.5 text-xs text-gray-600">
            <li class="flex gap-2"><span class="text-red-500 font-bold flex-shrink-0">•</span><span><strong>1.5×</strong> resolution is ideal for web sharing — crisp without large file sizes.</span></li>
            <li class="flex gap-2"><span class="text-red-500 font-bold flex-shrink-0">•</span><span>Use <strong>3× / 216 DPI</strong> if you need to print or zoom into the images.</span></li>
            <li class="flex gap-2"><span class="text-red-500 font-bold flex-shrink-0">•</span><span>Set quality to <strong>92%</strong> for the best balance of sharpness and file size.</span></li>
            <li class="flex gap-2"><span class="text-red-500 font-bold flex-shrink-0">•</span><span>Large PDFs (20+ pages) take longer at high resolution — be patient.</span></li>
            <li class="flex gap-2"><span class="text-green-500 font-bold flex-shrink-0">🔒</span><span>Your PDF is processed entirely in your browser and is never uploaded.</span></li>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
/* ─────────────────────────────────────────────────────────────────
   PDF to JPG Converter — Alpine.js component
   CSS prefix: pj-

   Flow:
     1. User drops / selects a PDF file (validated client-side)
     2. On convert():
        a. pdfjsLib.getDocument(arrayBuffer) → loads PDF structure
        b. For each page: getPage() → getViewport() → canvas render
        c. canvas.toDataURL('image/jpeg', quality) → stored in pages[]
     3. downloadPage(): direct anchor download from data URL
     4. downloadAll(): load JSZip lazily, bundle all pages, trigger ZIP download

   Memory note: each data URL is ~0.75 × base64-encoded JPEG bytes.
   At 2× scale a typical A4 page ≈ 200–500 KB — 50 pages ≈ 10–25 MB.
   Canvas memory is released after toDataURL() by setting dimensions to 0.
─────────────────────────────────────────────────────────────────── */

// Set PDF.js worker source (same CDN version)
if (window.pdfjsLib) {
  window.pdfjsLib.GlobalWorkerOptions.workerSrc =
    'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
}

function pjTool() {
  return {
    // ── File state ───────────────────────────────────────
    file:       null,
    fileError:  '',
    isDragging: false,

    // ── Settings ─────────────────────────────────────────
    quality: 0.92,   // JPEG quality 0–1
    scale:   1.5,    // render scale (multiplies PDF's native 72 DPI)

    // ── Conversion state ─────────────────────────────────
    libLoading:  false,
    converting:  false,
    currentPage: 0,
    totalPages:  0,
    pageCount:   0,    // set after loading PDF, drives large-file warning
    convError:   '',

    // ── Results ──────────────────────────────────────────
    pages: [],   // [{pageNum, dataUrl, width, height}]

    // ── ZIP ──────────────────────────────────────────────
    zipping:  false,
    zipError: '',

    // ── Computed ─────────────────────────────────────────
    get progressPct() {
      if (!this.totalPages) return 0;
      return Math.round((this.currentPage / this.totalPages) * 100);
    },

    // ── Lifecycle ────────────────────────────────────────
    init() {
      // PDF.js is already loaded via the script tag above.
      // Verify the worker URL is set; bail gracefully if PDF.js failed to load.
      if (!window.pdfjsLib) {
        this.convError = 'PDF engine failed to load. Please refresh the page.';
      }
    },

    // ── File selection ───────────────────────────────────
    onFileChange(e) {
      var file = e.target.files[0];
      if (file) this._setFile(file);
    },

    onDrop(e) {
      this.isDragging = false;
      var file = e.dataTransfer.files[0];
      if (file) this._setFile(file);
    },

    _setFile(file) {
      this.fileError  = '';
      this.convError  = '';
      this.pages      = [];
      this.pageCount  = 0;

      var err = this._validate(file);
      if (err) { this.fileError = err; this.file = null; return; }

      this.file = file;
    },

    _validate(file) {
      if (!file) return 'No file selected.';
      var isPdf = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');
      if (!isPdf) return 'Only PDF files are supported. Please select a file ending in .pdf.';
      if (file.size > 50 * 1024 * 1024)
        return 'File too large (' + this.formatSize(file.size) + '). Maximum allowed size is 50 MB.';
      return '';
    },

    // ── Conversion ───────────────────────────────────────
    async convert() {
      if (!this.file || this.fileError) return;
      if (!window.pdfjsLib) {
        this.convError = 'PDF engine is not available. Please reload the page.';
        return;
      }

      this.convError  = '';
      this.pages      = [];
      this.currentPage = 0;
      this.totalPages  = 0;
      this.converting  = true;

      try {
        // Read the file into an ArrayBuffer
        var arrayBuffer = await this.file.arrayBuffer();

        // Load the PDF document (fast — just parses structure)
        var loadingTask = window.pdfjsLib.getDocument({ data: arrayBuffer });
        var pdf = await loadingTask.promise;

        this.totalPages = pdf.numPages;
        this.pageCount  = pdf.numPages;

        var fileName = this.file.name.replace(/\.pdf$/i, '');

        // Render each page
        for (var pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
          this.currentPage = pageNum;

          var page     = await pdf.getPage(pageNum);
          var viewport = page.getViewport({ scale: this.scale });

          // Create an offscreen canvas
          var canvas  = document.createElement('canvas');
          canvas.width  = Math.round(viewport.width);
          canvas.height = Math.round(viewport.height);
          var ctx = canvas.getContext('2d');

          // Render the PDF page into the canvas
          await page.render({ canvasContext: ctx, viewport: viewport }).promise;

          // Export as JPEG data URL
          var dataUrl = canvas.toDataURL('image/jpeg', this.quality);

          this.pages.push({
            pageNum:  pageNum,
            dataUrl:  dataUrl,
            width:    canvas.width,
            height:   canvas.height,
            fileName: fileName,
          });

          // Release canvas memory
          canvas.width  = 0;
          canvas.height = 0;
        }

      } catch (err) {
        this.convError = this._friendlyError(err);
      } finally {
        this.converting = false;
      }
    },

    _friendlyError(err) {
      if (!err) return 'An unknown error occurred.';
      var msg = err.message || String(err);
      if (msg.includes('Invalid PDF') || msg.includes('Missing PDF'))
        return 'The file does not appear to be a valid PDF. Please try a different file.';
      if (msg.includes('password') || msg.includes('encrypted'))
        return 'This PDF is password-protected. Please remove the password and try again.';
      if (msg.includes('out of memory') || msg.includes('memory'))
        return 'The browser ran out of memory. Try a lower resolution setting or a smaller PDF.';
      return 'Conversion failed: ' + msg;
    },

    // ── Downloads ─────────────────────────────────────────
    downloadPage(page) {
      var name = (page.fileName || 'page') + '-page-' + String(page.pageNum).padStart(3, '0') + '.jpg';
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
        // Load JSZip lazily — only when user actually wants a ZIP
        if (!window.JSZip) {
          await this._loadScript(
            'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js'
          );
        }

        var zip      = new window.JSZip();
        var folder   = zip.folder('pages');
        var baseName = (this.pages[0].fileName || 'page');

        for (var i = 0; i < this.pages.length; i++) {
          var p    = this.pages[i];
          var name = baseName + '-page-' + String(p.pageNum).padStart(3, '0') + '.jpg';
          // Strip the 'data:image/jpeg;base64,' prefix and add raw base64 to zip
          var b64  = p.dataUrl.split(',')[1];
          folder.file(name, b64, { base64: true });
        }

        var content = await zip.generateAsync({ type: 'blob', compression: 'DEFLATE' });
        var url     = URL.createObjectURL(content);
        var a       = document.createElement('a');
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
      this.pages       = [];
      this.pageCount   = 0;
      this.currentPage = 0;
      this.totalPages  = 0;
      this.converting  = false;
      this.zipping     = false;
      // Reset the file input so the same file can be re-selected
      if (this.$refs.fileInput) this.$refs.fileInput.value = '';
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
        var s    = document.createElement('script');
        s.src    = src;
        s.onload  = resolve;
        s.onerror = function() { reject(new Error('Failed to load ' + src)); };
        document.head.appendChild(s);
      });
    },
  };
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\pdf-to-jpg.blade.php ENDPATH**/ ?>