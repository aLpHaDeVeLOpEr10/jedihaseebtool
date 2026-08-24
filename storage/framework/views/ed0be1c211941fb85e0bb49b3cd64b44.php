<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('meta_description', $tool->seo_description); ?>
<?php $__env->startSection('renders_own_content_sections', '1'); ?>

<?php $__env->startSection('content'); ?>
<style>
/* Text-to-PDF Converter — prefix: pdf- */

.pdf-btn-group {
  display: flex; border-radius: .75rem; overflow: hidden;
  border: 1.5px solid #e2e8f0;
}
.pdf-btn-group button {
  flex: 1; padding: .5rem .75rem; font-size: .78rem; font-weight: 700;
  background: #fff; color: #64748b; cursor: pointer; transition: all .15s;
  border: none; border-right: 1.5px solid #e2e8f0; line-height: 1.3;
}
.pdf-btn-group button:last-child { border-right: none; }
.pdf-btn-group button.pdf-active { background: #2563eb; color: #fff; }
.pdf-btn-group button:hover:not(.pdf-active) { background: #f0f7ff; color: #1e40af; }

.pdf-range {
  width: 100%; height: 6px; border-radius: 9999px;
  accent-color: #2563eb; cursor: pointer;
}

.pdf-textarea {
  width: 100%; min-height: 230px; resize: vertical;
  font-family: inherit; font-size: .9rem; line-height: 1.75;
  color: #1e293b; border: 1.5px solid #e2e8f0; border-radius: .875rem;
  padding: .9rem 1rem; outline: none; transition: border-color .15s, box-shadow .15s;
  background: #fafafa;
}
.pdf-textarea:focus {
  border-color: #2563eb; background: #fff;
  box-shadow: 0 0 0 3px rgba(37,99,235,.08);
}
.pdf-textarea::placeholder { color: #94a3b8; }

.pdf-stat-pill {
  display: inline-flex; align-items: center; gap: .3rem;
  padding: .2rem .65rem; border-radius: 9999px;
  font-size: .7rem; font-weight: 600;
  background: #eff6ff; color: #1d4ed8; border: 1.5px solid #bfdbfe;
}

.pdf-section-hdr {
  font-size: .62rem; font-weight: 800; text-transform: uppercase;
  letter-spacing: .1em; color: #94a3b8;
  display: flex; align-items: center; gap: .6rem; margin-bottom: .9rem;
}
.pdf-section-hdr::after { content: ''; flex: 1; height: 1px; background: #f1f5f9; }

.pdf-success {
  display: flex; align-items: flex-start; gap: .65rem; padding: .9rem 1rem;
  background: #f0fdf4; border: 1.5px solid #86efac; border-radius: .875rem;
  color: #15803d; font-size: .84rem; font-weight: 500; animation: pdfFadeIn .25s ease-out;
}
.pdf-error {
  display: flex; align-items: flex-start; gap: .65rem; padding: .9rem 1rem;
  background: #fef2f2; border: 1.5px solid #fecaca; border-radius: .875rem;
  color: #dc2626; font-size: .84rem; font-weight: 500; animation: pdfFadeIn .25s ease-out;
}

.pdf-tip-item { display: flex; gap: .5rem; font-size: .75rem; color: #475569; }
.pdf-tip-item span:first-child { color: #2563eb; font-weight: 800; flex-shrink: 0; margin-top: .05rem; }

@keyframes pdfFadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }

/* filename suffix attachment */
.pdf-filename-wrap { display: flex; }
.pdf-filename-wrap input { border-radius: .75rem 0 0 .75rem; border-right: none; }
.pdf-filename-suffix {
  display: inline-flex; align-items: center; padding: 0 .75rem;
  border: 1.5px solid #e2e8f0; border-left: none;
  border-radius: 0 .75rem .75rem 0;
  background: #f8fafc; color: #94a3b8; font-size: .82rem; font-weight: 600;
  white-space: nowrap;
}
</style>

<div class="min-h-screen bg-gray-50"
     x-data="textToPdf()"
     x-init="init()">

  
  <div class="bg-white border-b border-gray-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
      <div class="flex items-center gap-4 mb-4">
        <div class="tool-icon bg-brand-100 text-brand-600 text-3xl w-14 h-14">
          <?php echo e($tool->icon ?? '📄'); ?>

        </div>
        <div>
          <h1 class="text-2xl sm:text-3xl font-bold text-gray-900"><?php echo e($tool->name); ?></h1>
          <p class="text-gray-500 mt-1 text-sm"><?php echo e($tool->short_description); ?></p>
        </div>
      </div>
      <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [
          ['label' => 'Home',                'url' => url('/')],
          ['label' => $tool->category->name, 'url' => route('categories.show', $tool->category)],
          ['label' => $tool->name]
      ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
          ['label' => 'Home',                'url' => url('/')],
          ['label' => $tool->category->name, 'url' => route('categories.show', $tool->category)],
          ['label' => $tool->name]
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

  <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
    <div class="grid gap-8 lg:grid-cols-3">

      
      <div class="lg:col-span-2 space-y-5">

        
        <div class="card p-6">
          <div class="pdf-section-hdr">Your Text</div>

          <textarea
            x-model="text"
            @input="updateCounts()"
            placeholder="Paste or type your text here…&#10;&#10;All line breaks and paragraphs are preserved in the PDF.&#10;Click 'Load Sample' below to see an example."
            class="pdf-textarea"
            spellcheck="true"
            aria-label="Text to convert to PDF"
          ></textarea>

          <div class="flex items-center justify-between mt-3">
            <div class="flex items-center gap-2 flex-wrap">
              <span x-show="text.length > 0" x-transition class="pdf-stat-pill">
                <span x-text="charCount.toLocaleString()"></span>&nbsp;chars
              </span>
              <span x-show="wordCount > 0" x-transition class="pdf-stat-pill">
                <span x-text="wordCount.toLocaleString()"></span>&nbsp;words
              </span>
            </div>
            <button type="button" @click="loadSample()"
                    class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">
              Load Sample Text
            </button>
          </div>
        </div>

        
        <div class="card p-6 space-y-6">
          <div class="pdf-section-hdr">PDF Options</div>

          
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="form-label" for="pdf-doc-title">Document Title</label>
              <input id="pdf-doc-title" type="text" x-model="docTitle"
                     placeholder="My Document" class="form-input"
                     aria-describedby="help-doc-title">
              <p id="help-doc-title" class="form-help">Embedded in the PDF file metadata</p>
            </div>
            <div>
              <label class="form-label" for="pdf-filename">Download Filename</label>
              <div class="pdf-filename-wrap">
                <input id="pdf-filename" type="text" x-model="filename"
                       placeholder="document" class="form-input"
                       aria-describedby="help-filename">
                <span class="pdf-filename-suffix">.pdf</span>
              </div>
              <p id="help-filename" class="form-help">Name of the downloaded file</p>
            </div>
          </div>

          
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="form-label">Page Size</label>
              <div class="pdf-btn-group" role="group" aria-label="Page size">
                <button type="button"
                        @click="pageSize = 'a4'"
                        :class="pageSize === 'a4' ? 'pdf-active' : ''">
                  A4
                </button>
                <button type="button"
                        @click="pageSize = 'letter'"
                        :class="pageSize === 'letter' ? 'pdf-active' : ''">
                  US Letter
                </button>
              </div>
              <p class="form-help mt-1.5"
                 x-text="pageSize === 'a4' ? '210 × 297 mm — International standard' : '8.5 × 11 in — North American standard'">
              </p>
            </div>
            <div>
              <label class="form-label">Orientation</label>
              <div class="pdf-btn-group" role="group" aria-label="Page orientation">
                <button type="button"
                        @click="orientation = 'portrait'"
                        :class="orientation === 'portrait' ? 'pdf-active' : ''">
                  Portrait
                </button>
                <button type="button"
                        @click="orientation = 'landscape'"
                        :class="orientation === 'landscape' ? 'pdf-active' : ''">
                  Landscape
                </button>
              </div>
              <p class="form-help mt-1.5"
                 x-text="orientation === 'portrait' ? 'Tall page — best for documents and letters' : 'Wide page — best for tables and wide content'">
              </p>
            </div>
          </div>

          
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="form-label" for="pdf-font-size">
                Font Size &mdash;
                <span class="font-bold text-blue-600" x-text="fontSize + 'pt'"></span>
              </label>
              <input id="pdf-font-size" type="range" x-model.number="fontSize"
                     min="8" max="36" step="1" class="pdf-range mt-1"
                     aria-label="Font size in points">
              <div class="flex justify-between text-xs text-gray-400 mt-1">
                <span>8pt</span><span>22pt</span><span>36pt</span>
              </div>
            </div>
            <div>
              <label class="form-label">Text Alignment</label>
              <div class="pdf-btn-group" role="group" aria-label="Text alignment">
                <button type="button" @click="textAlign = 'left'"
                        :class="textAlign === 'left' ? 'pdf-active' : ''" title="Left">Left</button>
                <button type="button" @click="textAlign = 'center'"
                        :class="textAlign === 'center' ? 'pdf-active' : ''" title="Center">Center</button>
                <button type="button" @click="textAlign = 'right'"
                        :class="textAlign === 'right' ? 'pdf-active' : ''" title="Right">Right</button>
                <button type="button" @click="textAlign = 'justify'"
                        :class="textAlign === 'justify' ? 'pdf-active' : ''" title="Justify">Justify</button>
              </div>
              <p class="form-help mt-1.5"
                 x-text="{left:'Left-aligned text (default)',center:'Centered text',right:'Right-aligned text',justify:'Full-width justified text'}[textAlign]">
              </p>
            </div>
          </div>

          
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="form-label" for="pdf-margin">
                Page Margins &mdash;
                <span class="font-bold text-blue-600" x-text="margin + 'mm'"></span>
              </label>
              <input id="pdf-margin" type="range" x-model.number="margin"
                     min="5" max="50" step="5" class="pdf-range mt-1"
                     aria-label="Page margins in millimetres">
              <div class="flex justify-between text-xs text-gray-400 mt-1">
                <span>5mm</span><span>25mm</span><span>50mm</span>
              </div>
            </div>
            <div>
              <label class="form-label" for="pdf-line-spacing">
                Line Spacing &mdash;
                <span class="font-bold text-blue-600" x-text="lineSpacing + '×'"></span>
              </label>
              <input id="pdf-line-spacing" type="range" x-model.number="lineSpacing"
                     min="1" max="3" step="0.25" class="pdf-range mt-1"
                     aria-label="Line spacing multiplier">
              <div class="flex justify-between text-xs text-gray-400 mt-1">
                <span>1× tight</span><span>1.5× normal</span><span>3× spacious</span>
              </div>
            </div>
          </div>
        </div>

        
        <div x-show="success" x-cloak class="pdf-success">
          <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
          </svg>
          <span x-text="success"></span>
        </div>

        <div x-show="error" x-cloak class="pdf-error">
          <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <span x-text="error"></span>
        </div>

        
        <div class="flex flex-wrap gap-3">
          <button type="button"
                  @click="generatePdf()"
                  :disabled="loading || !isValid"
                  class="btn btn-primary btn-lg flex-1 sm:flex-none"
                  aria-live="polite">
            <span x-show="!loading" class="flex items-center justify-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
              Generate &amp; Download PDF
            </span>
            <span x-show="loading" class="flex items-center justify-center gap-2">
              <span class="spinner"></span> Generating PDF…
            </span>
          </button>

          <button type="button"
                  @click="clearText()"
                  :disabled="!text"
                  class="btn btn-secondary">
            Clear Text
          </button>

          <button type="button"
                  @click="resetAll()"
                  class="btn btn-secondary">
            Reset All
          </button>
        </div>

        
        <?php if($tool->long_description): ?>
        <div class="card p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">About This Tool</h2>
          <div class="tool-prose"><?php echo nl2br(e($tool->long_description)); ?></div>
        </div>
        <?php endif; ?>

        
        <?php $__currentLoopData = $tool->contents->where('is_visible', true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card p-6">
          <?php if($section->title): ?>
          <h2 class="text-lg font-semibold text-gray-900 mb-4"><?php echo e($section->title); ?></h2>
          <?php endif; ?>
          <div class="tool-prose"><?php echo nl2br(e($section->content)); ?></div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <?php if($tool->faqs->where('is_visible', true)->count() > 0): ?>
        <div class="card p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-5">Frequently Asked Questions</h2>
          <div class="space-y-3" x-data="{ open: null }">
            <?php $__currentLoopData = $tool->faqs->where('is_visible', true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fi => $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="border border-gray-100 rounded-xl overflow-hidden">
              <button @click="open = open === <?php echo e($fi); ?> ? null : <?php echo e($fi); ?>"
                      class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors">
                <span class="font-medium text-gray-800 text-sm"><?php echo e($faq->question); ?></span>
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform"
                     :class="open === <?php echo e($fi); ?> ? 'rotate-180' : ''"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </button>
              <div x-show="open === <?php echo e($fi); ?>" x-cloak
                   class="px-4 pb-4 text-sm text-gray-600 leading-relaxed">
                <?php echo e($faq->answer); ?>

              </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        </div>
        <?php endif; ?>

      </div>

      
      <div class="space-y-5">

        
        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Current Settings</h3>
          <dl class="space-y-2 text-xs">
            <div class="flex justify-between">
              <dt class="text-gray-500">Page</dt>
              <dd class="font-semibold text-gray-800 capitalize" x-text="pageSize.toUpperCase() + ' · ' + orientation"></dd>
            </div>
            <div class="flex justify-between">
              <dt class="text-gray-500">Font size</dt>
              <dd class="font-semibold text-gray-800" x-text="fontSize + 'pt'"></dd>
            </div>
            <div class="flex justify-between">
              <dt class="text-gray-500">Alignment</dt>
              <dd class="font-semibold text-gray-800 capitalize" x-text="textAlign"></dd>
            </div>
            <div class="flex justify-between">
              <dt class="text-gray-500">Margins</dt>
              <dd class="font-semibold text-gray-800" x-text="margin + 'mm all sides'"></dd>
            </div>
            <div class="flex justify-between">
              <dt class="text-gray-500">Line spacing</dt>
              <dd class="font-semibold text-gray-800" x-text="lineSpacing + '×'"></dd>
            </div>
          </dl>
        </div>

        
        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Tips</h3>
          <ul class="space-y-2.5">
            <li class="pdf-tip-item">
              <span>→</span>
              Use A4 for international docs, Letter for US/Canada
            </li>
            <li class="pdf-tip-item">
              <span>→</span>
              Landscape is great for wide tables or code snippets
            </li>
            <li class="pdf-tip-item">
              <span>→</span>
              12–14pt is ideal for comfortable body text
            </li>
            <li class="pdf-tip-item">
              <span>→</span>
              20–25mm margins give a professional look
            </li>
            <li class="pdf-tip-item">
              <span>→</span>
              1.5× line spacing improves readability significantly
            </li>
            <li class="pdf-tip-item">
              <span>→</span>
              The PDF is generated entirely in your browser — your text is never uploaded
            </li>
          </ul>
        </div>

        
        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Category</h3>
          <a href="<?php echo e(route('categories.show', $tool->category)); ?>"
             class="flex items-center gap-3 p-3 rounded-xl bg-brand-50 hover:bg-brand-100 transition-colors">
            <span class="text-xl"><?php echo e($tool->category->icon); ?></span>
            <span class="font-medium text-brand-700"><?php echo e($tool->category->name); ?></span>
          </a>
        </div>

        
        <?php if($relatedTools->count() > 0): ?>
        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Related Tools</h3>
          <div class="space-y-2">
            <?php $__currentLoopData = $relatedTools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('tools.show', $related)); ?>"
               class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-gray-50 transition-colors group">
              <span class="text-lg"><?php echo e($related->icon); ?></span>
              <span class="text-sm text-gray-700 group-hover:text-brand-600 transition-colors">
                <?php echo e($related->name); ?>

              </span>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
/* ─────────────────────────────────────────────────────────────────
   Text-to-PDF Converter — Alpine.js component
   PDF generation: jsPDF 2.5.1 (UMD, client-side only)
   Text is NEVER sent to a server.

   Flow:
     1. User types / pastes text
     2. Adjusts PDF options (page size, orientation, font, etc.)
     3. Clicks "Generate & Download PDF"
     4. jsPDF renders text line-by-line with auto page-breaks
     5. doc.save() triggers browser download immediately
────────────────────────────────────────────────────────────────── */
function textToPdf() {
  return {

    // ── Form state ──────────────────────────────────────────────
    text:        '',
    docTitle:    '',
    filename:    'document',
    pageSize:    'a4',          // 'a4' | 'letter'
    orientation: 'portrait',    // 'portrait' | 'landscape'
    fontSize:    12,            // pt
    textAlign:   'left',        // 'left' | 'center' | 'right' | 'justify'
    margin:      20,            // mm, all sides
    lineSpacing: 1.5,           // multiplier

    // ── UI state ────────────────────────────────────────────────
    loading:   false,
    success:   '',
    error:     '',
    charCount: 0,
    wordCount: 0,

    init() {},

    get isValid() {
      return this.text.trim().length > 0;
    },

    updateCounts() {
      this.charCount = this.text.length;
      this.wordCount = this.text.trim() ? this.text.trim().split(/\s+/).length : 0;
    },

    // ── PDF generation ──────────────────────────────────────────
    generatePdf() {
      if (!this.isValid) {
        this.error   = 'Please enter some text before generating a PDF.';
        this.success = '';
        return;
      }
      if (typeof window.jspdf === 'undefined') {
        this.error   = 'The PDF library is still loading — please wait a moment and try again.';
        this.success = '';
        return;
      }

      this.loading = true;
      this.success = '';
      this.error   = '';

      // Defer the (synchronous, CPU-bound) jsPDF work by one tick so Alpine
      // can repaint the spinner before the main thread is blocked.
      var self = this;
      setTimeout(function () {
        try {
          var jsPDF = window.jspdf.jsPDF;

          var doc = new jsPDF({
            orientation: self.orientation === 'landscape' ? 'l' : 'p',
            unit:        'mm',
            format:      self.pageSize,  // 'a4' or 'letter'
          });

          // Embed metadata
          var titleStr = self.docTitle.trim();
          if (titleStr) {
            doc.setProperties({ title: titleStr });
          }

          // Layout measurements
          var pageW  = doc.internal.pageSize.getWidth();
          var pageH  = doc.internal.pageSize.getHeight();
          var m      = Number(self.margin);
          var usable = pageW - m * 2;
          var fSize  = Number(self.fontSize);

          doc.setFontSize(fSize);

          // Line height in mm: points → inches → mm, scaled by spacing multiplier
          var lineH = (fSize / 72) * 25.4 * Number(self.lineSpacing);

          // jsPDF wraps long lines at usable width
          var lines = doc.splitTextToSize(self.text, usable);

          // Resolve x position and alignment options once
          var align = self.textAlign;
          var xPos, alignOpts;
          if (align === 'center') {
            xPos = m + usable / 2;
            alignOpts = { align: 'center' };
          } else if (align === 'right') {
            xPos = m + usable;
            alignOpts = { align: 'right' };
          } else if (align === 'justify') {
            xPos = m;
            alignOpts = { align: 'justify', maxWidth: usable };
          } else {
            xPos = m;
            alignOpts = {};
          }

          // Start below the top margin (add font ascender so first line isn't clipped)
          var ascender = (fSize / 72) * 25.4 * 0.8;
          var y = m + ascender;

          for (var i = 0; i < lines.length; i++) {
            // New page when line would overflow bottom margin
            if (i > 0 && y + lineH > pageH - m) {
              doc.addPage();
              y = m + ascender;
            }
            doc.text(lines[i], xPos, y, alignOpts);
            y += lineH;
          }

          // Sanitise filename
          var rawName = self.filename.trim() || 'document';
          var fname   = rawName.replace(/[^a-zA-Z0-9._\-\s]/g, '_').trim() || 'document';
          if (!fname.toLowerCase().endsWith('.pdf')) fname += '.pdf';

          doc.save(fname);
          self.success = 'PDF "' + fname + '" generated and downloaded successfully!';

        } catch (err) {
          self.error = 'Could not generate PDF: ' + (err.message || 'Unknown error. Please try again.');
        }

        self.loading = false;
      }, 30);
    },

    // ── Actions ─────────────────────────────────────────────────
    clearText() {
      this.text    = '';
      this.success = '';
      this.error   = '';
      this.updateCounts();
    },

    resetAll() {
      this.text        = '';
      this.docTitle    = '';
      this.filename    = 'document';
      this.pageSize    = 'a4';
      this.orientation = 'portrait';
      this.fontSize    = 12;
      this.textAlign   = 'left';
      this.margin      = 20;
      this.lineSpacing = 1.5;
      this.success     = '';
      this.error       = '';
      this.updateCounts();
    },

    loadSample() {
      this.text = [
        'Sample PDF Document',
        '',
        'This document was created with the Text-to-PDF Converter — a free, browser-based tool that converts plain text to a downloadable PDF instantly. Your text is never sent to a server.',
        '',
        'Section 1: Introduction',
        '',
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
        '',
        'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
        '',
        'Section 2: Features',
        '',
        '- Page size: A4 (210x297mm) or US Letter (8.5x11in)',
        '- Orientation: Portrait or Landscape',
        '- Font size: 8pt to 36pt',
        '- Text alignment: Left, Center, Right, or Justify',
        '- Page margins: 5mm to 50mm on all sides',
        '- Line spacing: 1x (tight) to 3x (spacious)',
        '- Custom document title embedded in PDF metadata',
        '- Custom download filename',
        '',
        'Section 3: Privacy',
        '',
        'All processing happens in your browser using jsPDF. No data is uploaded anywhere.',
      ].join('\n');
      this.docTitle = 'Sample PDF Document';
      this.filename = 'sample-document';
      this.updateCounts();
    },

  };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\text-to-pdf-converter.blade.php ENDPATH**/ ?>