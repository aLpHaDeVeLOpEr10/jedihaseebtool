<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('meta_description', $tool->seo_description); ?>
<?php $__env->startSection('renders_own_content_sections', '1'); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ══════════════════════════════════════════════════════════
   Image to PDF  —  prefix: ip-
   Brand: indigo #4f46e5 (brand-600)
   Library: jsPDF 2.5.1 (CDN, UMD)
   All processing is 100 % client-side.
══════════════════════════════════════════════════════════ */

/* ── Drop zone ─────────────────────────────────────────── */
.ip-drop {
  border: 2.5px dashed #c7d2fe;
  border-radius: 1rem;
  padding: 2.5rem 1.5rem;
  text-align: center;
  cursor: pointer;
  transition: all .18s;
  background: #eef2ff;
  position: relative;
  user-select: none;
}
.ip-drop:hover, .ip-drop.ip-drag-hover {
  border-color: #4f46e5;
  background: #e0e7ff;
  transform: scale(1.01);
}
.ip-drop.ip-drop-sm {
  padding: 1rem 1.5rem;
  border-style: dashed;
  border-width: 2px;
}
.ip-drop.ip-drop-sm:hover { transform: none; }
.ip-dz-icon  { font-size: 2.5rem; line-height: 1; margin-bottom: .6rem; }
.ip-dz-title { font-size: 1rem; font-weight: 700; color: #374151; margin-bottom: .2rem; }
.ip-dz-sub   { font-size: .8rem; color: #9ca3af; }

/* ── Image card grid ───────────────────────────────────── */
.ip-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: .75rem;
}
@media (min-width: 480px) { .ip-grid { grid-template-columns: repeat(3, 1fr); } }

.ip-img-card {
  position: relative;
  border: 1.5px solid #e0e7ff;
  border-radius: .875rem;
  overflow: hidden;
  background: #fff;
  transition: box-shadow .15s, border-color .15s, opacity .15s;
  cursor: grab;
}
.ip-img-card:hover { box-shadow: 0 4px 12px rgba(79,70,229,.12); border-color: #a5b4fc; }
.ip-img-card.ip-dragging  { opacity: .4; }
.ip-img-card.ip-drop-here { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.2); }

.ip-thumb-wrap {
  width: 100%; padding-top: 75%; /* 4:3 ratio */
  position: relative; background: #f8fafc; overflow: hidden;
}
.ip-thumb {
  position: absolute; inset: 0;
  width: 100%; height: 100%; object-fit: contain; display: block;
}
.ip-thumb-loading {
  position: absolute; inset: 0;
  display: flex; align-items: center; justify-content: center;
  background: #f8fafc;
}

/* Number badge */
.ip-num-badge {
  position: absolute; top: .4rem; left: .4rem;
  background: rgba(79,70,229,.85); color: #fff;
  font-size: .62rem; font-weight: 800;
  padding: .15rem .45rem; border-radius: 9999px;
  pointer-events: none;
}

/* Remove button */
.ip-remove-btn {
  position: absolute; top: .35rem; right: .35rem;
  width: 1.5rem; height: 1.5rem;
  background: rgba(220,38,38,.85); color: #fff;
  border: none; border-radius: 9999px;
  font-size: .75rem; font-weight: 900; line-height: 1;
  cursor: pointer; display: flex; align-items: center; justify-content: center;
  transition: background .12s;
}
.ip-remove-btn:hover { background: #dc2626; }

/* Drag handle */
.ip-drag-handle {
  position: absolute; top: 50%; right: .35rem;
  transform: translateY(-50%);
  color: #c7d2fe; font-size: .95rem; cursor: grab;
  padding: .2rem;
  pointer-events: none; /* handled by parent draggable */
}

/* Card footer */
.ip-card-foot {
  padding: .4rem .55rem .5rem;
  border-top: 1px solid #f0f1f3;
}
.ip-card-name { font-size: .68rem; font-weight: 600; color: #374151; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ip-card-meta { font-size: .62rem; color: #9ca3af; margin-top: .1rem; }

/* ── Settings grid ─────────────────────────────────────── */
.ip-setting-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}
@media (max-width: 480px) { .ip-setting-grid { grid-template-columns: 1fr; } }

.ip-select {
  width: 100%; padding: .5rem .85rem;
  border: 1.5px solid #e0e7ff; border-radius: .75rem;
  font-size: .82rem; font-weight: 600; color: #374151;
  background: #fff; outline: none; cursor: pointer;
  transition: border-color .14s;
}
.ip-select:focus { border-color: #818cf8; box-shadow: 0 0 0 3px rgba(99,102,241,.12); }

.ip-range { width: 100%; height: 6px; border-radius: 9999px; accent-color: #4f46e5; cursor: pointer; }

/* ── Fit option buttons ────────────────────────────────── */
.ip-fit-group { display: flex; gap: .5rem; flex-wrap: wrap; }
.ip-fit-btn {
  flex: 1; min-width: 5rem; padding: .5rem .6rem;
  border: 1.5px solid #e0e7ff; border-radius: .75rem;
  font-size: .75rem; font-weight: 700; cursor: pointer;
  text-align: center; transition: all .14s;
  background: #fff; color: #6b7280;
}
.ip-fit-btn:hover { border-color: #818cf8; color: #4f46e5; background: #eef2ff; }
.ip-fit-btn.ip-fit-active { background: #eef2ff; border-color: #4f46e5; color: #4338ca; }

/* ── Section divider ───────────────────────────────────── */
.ip-div {
  display: flex; align-items: center; gap: .6rem;
  font-size: .6rem; font-weight: 800; text-transform: uppercase;
  letter-spacing: .1em; color: #6b7280;
}
.ip-div::before,.ip-div::after { content:''; flex:1; height:1px; background:#e5e7eb; }

/* ── Generate button ───────────────────────────────────── */
.ip-gen-btn {
  width: 100%; padding: .9rem 1.5rem; border-radius: .875rem;
  font-size: 1rem; font-weight: 800; cursor: pointer;
  display: flex; align-items: center; justify-content: center; gap: .5rem;
  transition: all .16s; border: none;
  background: linear-gradient(135deg, #3730a3, #4f46e5, #6366f1);
  color: #fff;
  box-shadow: 0 4px 14px rgba(79,70,229,.38);
}
.ip-gen-btn:hover:not(:disabled) { box-shadow: 0 6px 20px rgba(79,70,229,.55); transform: translateY(-1px); }
.ip-gen-btn:disabled { opacity: .4; cursor: not-allowed; transform: none; box-shadow: none; }

/* ── Progress bar ──────────────────────────────────────── */
.ip-prog-track {
  width: 100%; height: .6rem; border-radius: 9999px;
  background: #e0e7ff; overflow: hidden;
}
.ip-prog-fill {
  height: 100%; border-radius: 9999px;
  background: linear-gradient(90deg, #4338ca, #4f46e5, #6366f1);
  transition: width .25s ease; position: relative; overflow: hidden;
}
.ip-prog-fill::after {
  content:''; position:absolute; inset:0;
  background: linear-gradient(90deg,transparent 0%,rgba(255,255,255,.35) 50%,transparent 100%);
  animation: ipShimmer 1.4s infinite;
}
@keyframes ipShimmer { 0%{transform:translateX(-100%)} 100%{transform:translateX(200%)} }

/* ── Alerts ────────────────────────────────────────────── */
.ip-error {
  display:flex; align-items:flex-start; gap:.5rem;
  padding:.7rem .9rem; border-radius:.75rem;
  background:#fef2f2; border:1.5px solid #fecaca;
  font-size:.8rem; color:#991b1b; font-weight:500;
}
.ip-success {
  display:flex; align-items:center; gap:.5rem;
  padding:.7rem .9rem; border-radius:.75rem;
  background:#f0fdf4; border:1.5px solid #86efac;
  font-size:.85rem; color:#15803d; font-weight:600;
}
.ip-privacy {
  display:flex; align-items:center; gap:.5rem;
  padding:.5rem .85rem; border-radius:.75rem;
  background:#eef2ff; border:1px solid #c7d2fe;
  font-size:.75rem; color:#3730a3; font-weight:500;
}

/* ── Stat pill ─────────────────────────────────────────── */
.ip-pill {
  display:inline-flex; align-items:center; gap:.3rem;
  padding:.2rem .6rem; border-radius:9999px;
  font-size:.68rem; font-weight:700;
  background:#eef2ff; color:#3730a3; border:1.5px solid #c7d2fe;
}

/* ── Spinner ───────────────────────────────────────────── */
@keyframes ipSpin { to { transform:rotate(360deg); } }
.ip-spin {
  display:inline-block; width:1em; height:1em; border-radius:50%;
  border:2px solid currentColor; border-top-color:transparent;
  animation:ipSpin .6s linear infinite; flex-shrink:0;
}

@media (max-width:640px) {
  .ip-drop { padding:1.5rem 1rem; }
  .ip-dz-icon { font-size:2rem; }
}
</style>

<div class="min-h-screen bg-gray-50">

  
  <div class="bg-white border-b border-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
      <div class="flex items-center gap-4 mb-4">
        <div class="tool-icon bg-brand-100 text-brand-600 text-3xl w-14 h-14 flex items-center justify-center rounded-xl">
          <?php echo e($tool->icon ?? '🖼️'); ?>

        </div>
        <div>
          <h1 class="text-3xl font-bold text-gray-900"><?php echo e($tool->name); ?></h1>
          <p class="text-gray-500 mt-1"><?php echo e($tool->short_description ?? 'Combine multiple images into a single PDF — choose page size, orientation, margins, and fitting options. Free and 100% in-browser.'); ?></p>
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

  <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
    <div class="grid gap-8 lg:grid-cols-3">

      
      <div class="lg:col-span-2 space-y-5"
           x-data="ipTool()"
           x-init="init()">

        
        <div class="card p-6 space-y-5">

          <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Images</h2>
            <div x-show="images.length > 0" class="flex items-center gap-2">
              <span class="ip-pill" x-text="images.length + ' image' + (images.length !== 1 ? 's' : '')"></span>
              <button type="button" @click="clearAll()"
                      class="text-xs font-semibold text-red-500 hover:text-red-700 transition-colors">
                Clear All
              </button>
            </div>
          </div>

          
          <div class="ip-privacy">
            <span>🔒</span>
            <span>Your images never leave your browser — the PDF is generated entirely on your device.</span>
          </div>

          
          <div x-show="images.length === 0"
               :class="['ip-drop', isDragging ? 'ip-drag-hover' : '']"
               @dragover.prevent="isDragging = true"
               @dragleave.prevent="isDragging = false"
               @drop.prevent="onDrop($event)"
               @click="$refs.fileInput.click()"
               role="button" tabindex="0"
               @keydown.enter.prevent="$refs.fileInput.click()"
               @keydown.space.prevent="$refs.fileInput.click()"
               aria-label="Upload images — click or drag and drop">
            <div class="ip-dz-icon">🖼️</div>
            <p class="ip-dz-title">Drag &amp; drop images here</p>
            <p class="ip-dz-sub">or click to browse &nbsp;·&nbsp; JPG, PNG, WebP &nbsp;·&nbsp; up to 20 MB each &nbsp;·&nbsp; max 40 images</p>
          </div>

          
          <div x-show="images.length > 0" class="space-y-4">

            
            <p class="text-xs text-gray-400 flex items-center gap-1.5">
              <span>⠿</span> Drag cards to reorder &nbsp;·&nbsp; numbers show PDF page order
            </p>

            <div class="ip-grid">
              <template x-for="(img, idx) in images" :key="img.id">
                <div
                  class="ip-img-card"
                  draggable="true"
                  :class="{
                    'ip-dragging' : dragIdx === idx,
                    'ip-drop-here': dragOverIdx === idx && dragIdx !== idx
                  }"
                  @dragstart="onDragStart(idx, $event)"
                  @dragover.prevent="onDragOver(idx)"
                  @dragleave="onDragLeave()"
                  @drop.prevent="onDropReorder(idx)"
                  @dragend="onDragEnd()"
                >
                  
                  <div class="ip-thumb-wrap">
                    
                    <div x-show="img.loading" class="ip-thumb-loading">
                      <span class="ip-spin" style="color:#818cf8;width:1.4rem;height:1.4rem;border-width:2.5px"></span>
                    </div>

                    
                    <img x-show="!img.loading && img.dataUrl"
                         :src="img.dataUrl"
                         :alt="img.name"
                         class="ip-thumb"
                         loading="lazy">

                    
                    <span class="ip-num-badge" x-text="idx + 1"></span>

                    
                    <button type="button"
                            @click.stop="removeImage(img.id)"
                            class="ip-remove-btn"
                            :aria-label="'Remove ' + img.name">
                      ✕
                    </button>

                    
                    <span class="ip-drag-handle" aria-hidden="true">⠿</span>
                  </div>

                  
                  <div class="ip-card-foot">
                    <p class="ip-card-name" x-text="img.name" :title="img.name"></p>
                    <p class="ip-card-meta">
                      <span x-text="formatSize(img.size)"></span>
                      <span x-show="img.width > 0"> · <span x-text="img.width + '×' + img.height"></span></span>
                    </p>
                  </div>
                </div>
              </template>

              
              <div
                :class="['ip-drop ip-drop-sm flex flex-col items-center justify-center gap-1', isDragging ? 'ip-drag-hover' : '']"
                @dragover.prevent="isDragging = true"
                @dragleave.prevent="isDragging = false"
                @drop.prevent="onDrop($event)"
                @click="$refs.fileInput.click()"
                role="button" tabindex="0"
                @keydown.enter.prevent="$refs.fileInput.click()"
                @keydown.space.prevent="$refs.fileInput.click()"
                aria-label="Add more images"
                style="min-height:100px"
              >
                <span class="text-2xl text-indigo-300">＋</span>
                <p class="text-xs font-semibold text-indigo-400">Add more</p>
              </div>
            </div>
          </div>

          
          <input type="file" id="ip-file-input" x-ref="fileInput"
                 accept="image/jpeg,image/jpg,image/png,image/webp,.jpg,.jpeg,.png,.webp"
                 multiple
                 @change="onFileChange($event)"
                 class="hidden" aria-hidden="true">

          
          <div x-show="fileError" x-transition role="alert" class="ip-error">
            <span>⚠</span><span x-text="fileError"></span>
          </div>

        </div>

        
        <div class="card p-6 space-y-5">

          <h2 class="text-lg font-semibold text-gray-900">PDF Settings</h2>

          
          <div class="ip-setting-grid">
            <div>
              <label class="form-label" for="ip-page-size">Page Size</label>
              <select id="ip-page-size" x-model="pageSize" class="ip-select">
                <option value="auto">Auto — match each image</option>
                <option value="a4">A4 (210 × 297 mm)</option>
                <option value="letter">Letter (216 × 279 mm)</option>
                <option value="legal">Legal (216 × 356 mm)</option>
              </select>
              <p class="form-help"
                 x-text="pageSize === 'auto' ? 'Each page sized to its image' : 'All pages use this fixed size'">
              </p>
            </div>
            <div>
              <label class="form-label">Orientation</label>
              <select x-model="orientation" class="ip-select"
                      :disabled="pageSize === 'auto'"
                      :class="pageSize === 'auto' ? 'opacity-50 cursor-not-allowed' : ''">
                <option value="portrait">Portrait (tall)</option>
                <option value="landscape">Landscape (wide)</option>
              </select>
              <p class="form-help"
                 x-text="pageSize === 'auto' ? 'Follows each image orientation' : ''">
              </p>
            </div>
          </div>

          
          <div>
            <label class="form-label" for="ip-margin">
              Page Margin &mdash;
              <span class="font-bold text-brand-600" x-text="margin + ' mm'"></span>
            </label>
            <input id="ip-margin" type="range" x-model.number="margin"
                   min="0" max="40" step="5" class="ip-range"
                   aria-label="Page margin in millimetres">
            <div class="flex justify-between text-xs text-gray-400 mt-1">
              <span>0 mm (no margin)</span><span>20 mm</span><span>40 mm</span>
            </div>
          </div>

          
          <div>
            <label class="form-label">Image Fitting</label>
            <div class="ip-fit-group">
              <button type="button"
                      @click="imageFit = 'fit'"
                      :class="['ip-fit-btn', imageFit === 'fit' ? 'ip-fit-active' : '']">
                <div class="text-lg mb-0.5">⬜</div>
                <div>Fit</div>
                <div class="text-xs font-normal text-gray-400 mt-0.5">Scale inside page</div>
              </button>
              <button type="button"
                      @click="imageFit = 'fill'"
                      :class="['ip-fit-btn', imageFit === 'fill' ? 'ip-fit-active' : '']">
                <div class="text-lg mb-0.5">🔲</div>
                <div>Fill</div>
                <div class="text-xs font-normal text-gray-400 mt-0.5">Cover full page</div>
              </button>
              <button type="button"
                      @click="imageFit = 'stretch'"
                      :class="['ip-fit-btn', imageFit === 'stretch' ? 'ip-fit-active' : '']">
                <div class="text-lg mb-0.5">↔️</div>
                <div>Stretch</div>
                <div class="text-xs font-normal text-gray-400 mt-0.5">Fill exactly</div>
              </button>
              <button type="button"
                      @click="imageFit = 'original'"
                      :class="['ip-fit-btn', imageFit === 'original' ? 'ip-fit-active' : '']">
                <div class="text-lg mb-0.5">🔍</div>
                <div>Original</div>
                <div class="text-xs font-normal text-gray-400 mt-0.5">Actual size</div>
              </button>
            </div>
            <p class="form-help mt-2"
               x-text="{
                 fit:      'Image is scaled to fit within the page margins without cropping.',
                 fill:     'Image is scaled to cover the full page area, centered. May extend beyond margins.',
                 stretch:  'Image is stretched to exactly fill the usable page area.',
                 original: 'Image placed at its natural pixel size (96 DPI). Centered if smaller than page.'
               }[imageFit]">
            </p>
          </div>

        </div>

        
        <div class="card p-6 space-y-4">

          
          <div x-show="genError" x-transition role="alert" class="ip-error">
            <span>⚠</span><span x-text="genError"></span>
          </div>

          
          <div x-show="success && !generating" x-transition class="ip-success">
            <span>✅</span>
            <span>PDF downloaded successfully! Check your downloads folder.</span>
          </div>

          
          <div x-show="generating" x-transition>
            <div class="flex justify-between text-sm text-gray-600 mb-2">
              <span>Building PDF…</span>
              <span class="font-bold text-brand-600" x-text="progress + '%'"></span>
            </div>
            <div class="ip-prog-track"
                 role="progressbar" :aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100">
              <div class="ip-prog-fill" :style="'width:' + progress + '%'"></div>
            </div>
            <p class="text-xs text-gray-400 mt-2 flex items-center gap-1.5">
              <span class="ip-spin" style="width:.75em;height:.75em;border-width:1.5px"></span>
              Processing <span x-text="progressLabel"></span>
            </p>
          </div>

          
          <button type="button"
                  @click="generate()"
                  :disabled="!canGenerate"
                  class="ip-gen-btn"
                  aria-live="polite">
            <span x-show="!generating" class="flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
              <span x-text="images.length === 0
                ? 'Add images above to get started'
                : 'Generate PDF (' + images.length + ' page' + (images.length !== 1 ? 's' : '') + ')'">
              </span>
            </span>
            <span x-show="generating" class="flex items-center gap-2">
              <span class="ip-spin" style="width:.9em;height:.9em"></span>
              Generating PDF…
            </span>
          </button>

          <p class="text-center text-xs text-gray-400">
            One image per page · pages appear in the order shown above
          </p>

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
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Tips</h3>
          <ul class="space-y-2.5 text-xs text-gray-600">
            <li class="flex gap-2"><span class="text-brand-500 font-bold flex-shrink-0 mt-0.5">•</span><span>Drag image cards to reorder — the number badge shows the page order.</span></li>
            <li class="flex gap-2"><span class="text-brand-500 font-bold flex-shrink-0 mt-0.5">•</span><span>Use <strong>Fit</strong> for documents; <strong>Fill</strong> for photos where you want full coverage.</span></li>
            <li class="flex gap-2"><span class="text-brand-500 font-bold flex-shrink-0 mt-0.5">•</span><span><strong>Auto</strong> page size creates a page exactly the right size for each image — ideal for photos.</span></li>
            <li class="flex gap-2"><span class="text-brand-500 font-bold flex-shrink-0 mt-0.5">•</span><span>Set margin to 0 for full-bleed photos; 10–20 mm for documents.</span></li>
            <li class="flex gap-2"><span class="text-green-500 font-bold flex-shrink-0 mt-0.5">🔒</span><span>Images are processed entirely in your browser — <strong>never uploaded</strong>.</span></li>
          </ul>
        </div>

        
        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Supported Formats</h3>
          <div class="flex flex-wrap gap-2">
            <span class="ip-pill">JPG / JPEG</span>
            <span class="ip-pill">PNG</span>
            <span class="ip-pill">WebP</span>
          </div>
          <p class="text-xs text-gray-500 mt-3">Max 20 MB per image · max 40 images per PDF</p>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
/* ─────────────────────────────────────────────────────────────
   Image to PDF — Alpine.js component   (prefix: ip-)
   Brand: indigo (#4f46e5)

   Flow:
     1. User drops / selects images (JPG, PNG, WebP)
     2. Each file is read via FileReader → data URL stored in images[]
     3. User reorders cards by dragging
     4. User picks page size, orientation, margin, fit mode
     5. generate():
        a. For each image: convert to JPEG via offscreen canvas
        b. jsPDF.addImage(jpegDataUrl, 'JPEG', x, y, w, h)
        c. doc.save() triggers browser download

   Security: images never leave the browser.
   Memory:   offscreen canvas is zeroed after toDataURL().
──────────────────────────────────────────────────────────── */

function ipTool() {
  return {

    // ── Image list ────────────────────────────────────────
    images:    [],   // [{id, file, name, size, dataUrl, width, height, loading}]
    fileError: '',
    isDragging: false,

    // ── Drag-reorder state ────────────────────────────────
    dragIdx:     null,
    dragOverIdx: null,

    // ── PDF settings ──────────────────────────────────────
    pageSize:    'a4',       // 'auto'|'a4'|'letter'|'legal'
    orientation: 'portrait', // 'portrait'|'landscape'
    margin:      10,         // mm
    imageFit:    'fit',      // 'fit'|'fill'|'stretch'|'original'

    // ── Generation state ──────────────────────────────────
    generating:    false,
    progress:      0,
    currentImgNum: 0,
    genError:      '',
    success:       false,

    // ── Computed ──────────────────────────────────────────
    get canGenerate() {
      return this.images.length > 0
        && !this.generating
        && this.images.every(function (i) { return !i.loading; });
    },

    get progressLabel() {
      return this.currentImgNum > 0
        ? 'image ' + this.currentImgNum + ' of ' + this.images.length + '…'
        : '';
    },

    // ── Lifecycle ─────────────────────────────────────────
    init() {
      if (typeof window.jspdf === 'undefined') {
        this.genError = 'PDF library is loading… please wait a moment before generating.';
        var self = this;
        var interval = setInterval(function () {
          if (window.jspdf) { self.genError = ''; clearInterval(interval); }
        }, 500);
      }
    },

    // ── File handling ─────────────────────────────────────
    onFileChange(e) {
      var files = Array.from(e.target.files || []);
      if (files.length) this._addFiles(files);
      e.target.value = '';  // allow re-selecting the same files
    },

    onDrop(e) {
      this.isDragging = false;
      var files = Array.from(e.dataTransfer.files || []);
      if (files.length) this._addFiles(files);
    },

    _addFiles(files) {
      this.fileError = '';
      var MAX_FILES  = 40;
      var MAX_BYTES  = 20 * 1024 * 1024;
      var VALID_MIME = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
      var self = this;

      for (var i = 0; i < files.length; i++) {
        var file = files[i];

        if (self.images.length >= MAX_FILES) {
          self.fileError = 'Maximum ' + MAX_FILES + ' images allowed.';
          break;
        }

        var mimeOk = VALID_MIME.includes(file.type);
        var extOk  = /\.(jpe?g|png|webp)$/i.test(file.name);
        if (!mimeOk && !extOk) {
          self.fileError = '"' + file.name + '" is not supported. Please use JPG, PNG, or WebP.';
          continue;
        }

        if (file.size > MAX_BYTES) {
          self.fileError = '"' + file.name + '" is too large (' + self.formatSize(file.size) + '). Max 20 MB per image.';
          continue;
        }

        (function (f) {
          var entry = {
            id:      Date.now() + Math.random(),
            file:    f,
            name:    f.name,
            size:    f.size,
            dataUrl: '',
            width:   0,
            height:  0,
            loading: true,
          };
          self.images.push(entry);
          self._loadDataUrl(entry);
        })(file);
      }
    },

    _loadDataUrl(entry) {
      var self   = this;
      var reader = new FileReader();

      reader.onload = function (e) {
        var dataUrl = e.target.result;
        var img     = new Image();

        img.onload = function () {
          var idx = self.images.findIndex(function (i) { return i.id === entry.id; });
          if (idx !== -1) {
            self.images[idx].dataUrl = dataUrl;
            self.images[idx].width   = img.naturalWidth;
            self.images[idx].height  = img.naturalHeight;
            self.images[idx].loading = false;
          }
        };

        img.onerror = function () {
          var idx = self.images.findIndex(function (i) { return i.id === entry.id; });
          if (idx !== -1) self.images.splice(idx, 1);
          self.fileError = '"' + entry.name + '" could not be read as an image.';
        };

        img.src = dataUrl;
      };

      reader.onerror = function () {
        var idx = self.images.findIndex(function (i) { return i.id === entry.id; });
        if (idx !== -1) self.images.splice(idx, 1);
        self.fileError = '"' + entry.name + '" could not be read.';
      };

      reader.readAsDataURL(entry.file);
    },

    removeImage(id) {
      this.images    = this.images.filter(function (i) { return i.id !== id; });
      this.fileError = '';
      this.success   = false;
    },

    clearAll() {
      this.images    = [];
      this.fileError = '';
      this.genError  = '';
      this.success   = false;
      this.progress  = 0;
      var inp = document.getElementById('ip-file-input');
      if (inp) inp.value = '';
    },

    // ── Drag-and-drop reorder ─────────────────────────────
    onDragStart(idx, e) {
      this.dragIdx = idx;
      e.dataTransfer.effectAllowed = 'move';
    },

    onDragOver(idx) {
      this.dragOverIdx = idx;
    },

    onDragLeave() {
      // Don't clear dragOverIdx here — it flickers; cleared on drop/end
    },

    onDropReorder(idx) {
      if (this.dragIdx === null || this.dragIdx === idx) {
        this.dragIdx     = null;
        this.dragOverIdx = null;
        return;
      }
      var moved = this.images.splice(this.dragIdx, 1)[0];
      this.images.splice(idx, 0, moved);
      this.dragIdx     = null;
      this.dragOverIdx = null;
      this.success     = false;
    },

    onDragEnd() {
      this.dragIdx     = null;
      this.dragOverIdx = null;
    },

    // ── PDF generation ────────────────────────────────────
    async generate() {
      if (!this.canGenerate) return;

      if (!window.jspdf) {
        this.genError = 'PDF library is not ready yet. Please wait a moment and try again.';
        return;
      }

      this.generating    = true;
      this.genError      = '';
      this.success       = false;
      this.progress      = 0;
      this.currentImgNum = 0;

      try {
        var jsPDF = window.jspdf.jsPDF;
        var isAuto = this.pageSize === 'auto';
        var orient = this.orientation === 'landscape' ? 'l' : 'p';

        // ── Create document with first image's settings ──
        var firstImg  = this.images[0];
        var firstDims = isAuto ? this._autoPageDims(firstImg) : null;

        var doc;
        if (isAuto) {
          doc = new jsPDF({
            orientation: firstDims.w >= firstDims.h ? 'l' : 'p',
            unit:        'mm',
            format:      [firstDims.w, firstDims.h],
          });
        } else {
          doc = new jsPDF({
            orientation: orient,
            unit:        'mm',
            format:      this.pageSize,
          });
        }

        doc.setProperties({ title: 'Images PDF' });

        // ── Process each image ────────────────────────────
        for (var i = 0; i < this.images.length; i++) {
          var imgEntry       = this.images[i];
          this.currentImgNum = i + 1;
          this.progress      = Math.round((i / this.images.length) * 100);
          await this._tick();

          // Add a new page for images after the first
          if (i > 0) {
            if (isAuto) {
              var dims = this._autoPageDims(imgEntry);
              doc.addPage([dims.w, dims.h]);
            } else {
              doc.addPage(this.pageSize, orient);
            }
          }

          // Get current page usable area
          var pgW  = doc.internal.pageSize.getWidth();
          var pgH  = doc.internal.pageSize.getHeight();
          var m    = Number(this.margin);
          var usW  = Math.max(1, pgW - m * 2);
          var usH  = Math.max(1, pgH - m * 2);

          // Convert image to JPEG via canvas (reliable across all browsers)
          var converted = await this._toJpeg(imgEntry);

          // Calculate placement in mm
          var pl = this._placement(converted.nW, converted.nH, usW, usH, m);

          doc.addImage(converted.dataUrl, 'JPEG', pl.x, pl.y, pl.w, pl.h, '', 'FAST');
        }

        this.progress = 100;
        await this._tick();

        // ── Build filename ────────────────────────────────
        var fname = this.images.length === 1
          ? this.images[0].name.replace(/\.[^.]+$/, '') + '.pdf'
          : 'images-' + this.images.length + '-pages.pdf';

        doc.save(fname);
        this.success = true;

      } catch (err) {
        this.genError = this._friendly(err);
      } finally {
        this.generating    = false;
        this.currentImgNum = 0;
      }
    },

    // ── Helpers ───────────────────────────────────────────

    // Auto page dims: match image pixel size → mm at 96 DPI
    _autoPageDims(imgEntry) {
      var PX2MM = 25.4 / 96;
      return {
        w: imgEntry.width  * PX2MM,
        h: imgEntry.height * PX2MM,
      };
    },

    // Convert image data URL → JPEG data URL via canvas
    _toJpeg(imgEntry) {
      return new Promise(function (resolve, reject) {
        var img = new Image();

        img.onload = function () {
          try {
            var c   = document.createElement('canvas');
            c.width  = img.naturalWidth;
            c.height = img.naturalHeight;
            var ctx = c.getContext('2d');
            // White background for transparent PNGs
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, c.width, c.height);
            ctx.drawImage(img, 0, 0);

            var jpegUrl = c.toDataURL('image/jpeg', 0.88);
            var nW = img.naturalWidth;
            var nH = img.naturalHeight;

            // Free canvas memory
            c.width = 0; c.height = 0;

            resolve({ dataUrl: jpegUrl, nW: nW, nH: nH });
          } catch (e) {
            reject(e);
          }
        };

        img.onerror = function () {
          reject(new Error('Could not decode image: ' + imgEntry.name));
        };

        img.src = imgEntry.dataUrl;
      });
    },

    // Calculate image position and size in mm for the given usable area
    _placement(imgW, imgH, usW, usH, m) {
      // imgW / imgH: natural pixel dimensions
      // usW / usH / m: mm

      var PX2MM  = 25.4 / 96;
      var imgRat = imgW / imgH;
      var pgRat  = usW  / usH;

      if (this.imageFit === 'original') {
        // Place at natural size (px → mm), centered, clamped to usable area
        var wMm = Math.min(imgW * PX2MM, usW);
        var hMm = Math.min(imgH * PX2MM, usH);
        return {
          x: m + (usW - wMm) / 2,
          y: m + (usH - hMm) / 2,
          w: wMm, h: hMm,
        };
      }

      if (this.imageFit === 'stretch') {
        // Fill the usable area exactly (may distort aspect ratio)
        return { x: m, y: m, w: usW, h: usH };
      }

      if (this.imageFit === 'fill') {
        // Scale so image covers the full usable area (centered, may overflow margin)
        var w, h;
        if (imgRat > pgRat) { h = usH; w = usH * imgRat; }
        else                 { w = usW; h = usW / imgRat; }
        return {
          x: m + (usW - w) / 2,
          y: m + (usH - h) / 2,
          w: w, h: h,
        };
      }

      // Default: 'fit' — scale to fit inside usable area, preserving ratio
      var fw, fh;
      if (imgRat > pgRat) { fw = usW; fh = usW / imgRat; }
      else                 { fh = usH; fw = usH * imgRat; }
      return {
        x: m + (usW - fw) / 2,
        y: m + (usH - fh) / 2,
        w: fw, h: fh,
      };
    },

    _friendly(err) {
      if (!err) return 'An unknown error occurred.';
      var m = (err.message || String(err)).toLowerCase();
      if (m.includes('memory') || m.includes('quota'))
        return 'The browser ran out of memory. Try fewer images or smaller files.';
      if (m.includes('canvas') || m.includes('security') || m.includes('tainted'))
        return 'A security error occurred loading an image. This can happen with cross-origin images.';
      return 'PDF generation failed: ' + (err.message || String(err));
    },

    formatSize(bytes) {
      if (bytes < 1024)    return bytes + ' B';
      if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
      return (bytes / 1048576).toFixed(1) + ' MB';
    },

    _tick() {
      return new Promise(function (r) { setTimeout(r, 20); });
    },

  };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views/tools/generated/image-to-pdf.blade.php ENDPATH**/ ?>