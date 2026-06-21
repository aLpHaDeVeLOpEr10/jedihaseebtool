@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')
<style>
/* ══════════════════════════════════════════════════════════════
   JPG → PNG Converter  —  prefix: jp-
   Theme: Forest green (#166534 / #16a34a) — lossless / quality
   Conversion: pure browser Canvas API — zero server uploads,
   zero external libraries.
   Flow: FileReader → Image → Canvas.drawImage → toDataURL(png)
══════════════════════════════════════════════════════════════ */

/* ── Drop zone ──────────────────────────────────────────── */
.jp-dropzone {
  border: 2.5px dashed #86efac;
  border-radius: 1rem;
  padding: 2.25rem 1.5rem;
  text-align: center;
  cursor: pointer;
  transition: all .18s;
  background: #f0fdf4;
  user-select: none;
}
.jp-dropzone:hover,
.jp-dropzone.jp-drag-over { border-color: #16a34a; background: #dcfce7; transform: scale(1.01); }
.jp-dropzone.jp-has-file  { border-color: #16a34a; background: #dcfce7; border-style: solid; }

.jp-dz-icon  { font-size: 2.5rem; line-height: 1; margin-bottom: .6rem; }
.jp-dz-title { font-size: 1rem; font-weight: 700; color: #374151; margin-bottom: .25rem; word-break: break-all; }
.jp-dz-sub   { font-size: .8rem; color: #9ca3af; }

/* ── Convert button ─────────────────────────────────────── */
.jp-convert-btn {
  width: 100%; padding: .9rem 1.5rem; border-radius: .875rem;
  font-size: 1rem; font-weight: 800; cursor: pointer;
  display: flex; align-items: center; justify-content: center; gap: .5rem;
  transition: all .16s; border: none;
  background: linear-gradient(135deg, #14532d, #166534, #16a34a);
  color: #fff; box-shadow: 0 4px 14px rgba(22,101,52,.35);
}
.jp-convert-btn:hover:not(:disabled) { box-shadow: 0 6px 20px rgba(22,101,52,.5); transform: translateY(-1px); }
.jp-convert-btn:disabled { opacity: .4; cursor: not-allowed; transform: none; box-shadow: none; }

/* ── Privacy badge ──────────────────────────────────────── */
.jp-privacy {
  display: flex; align-items: center; gap: .5rem;
  padding: .5rem .85rem; border-radius: .75rem;
  background: #f0fdf4; border: 1px solid #bbf7d0;
  font-size: .75rem; color: #166534; font-weight: 500;
}

/* ── Result card ────────────────────────────────────────── */
.jp-result-banner {
  display: flex; align-items: center; gap: .65rem;
  padding: .75rem 1rem; border-radius: .875rem;
  background: #f0fdf4; border: 1.5px solid #86efac;
  font-size: .88rem; font-weight: 600; color: #166534;
}

/* ── Before / After panels ──────────────────────────────── */
.jp-panel {
  border: 1.5px solid #e5e7eb; border-radius: .875rem;
  overflow: hidden; background: #fff;
}
.jp-panel-hdr {
  padding: .5rem .85rem; font-size: .65rem; font-weight: 800;
  text-transform: uppercase; letter-spacing: .1em; border-bottom: 1px solid #f3f4f6;
  display: flex; align-items: center; justify-content: space-between;
}
.jp-panel-hdr-jpg { background: #fef9c3; color: #92400e; }
.jp-panel-hdr-png { background: #f0fdf4; color: #166534; }

.jp-img-wrap {
  display: flex; align-items: center; justify-content: center;
  background: repeating-conic-gradient(#e5e7eb 0% 25%, #fff 0% 50%) 0 0 / 16px 16px;
  min-height: 160px; max-height: 280px; overflow: hidden;
}
.jp-preview-img {
  max-width: 100%; max-height: 280px;
  object-fit: contain; display: block;
}
.jp-panel-info {
  padding: .65rem .85rem; border-top: 1px solid #f3f4f6;
  display: flex; flex-wrap: wrap; gap: .75rem;
}
.jp-info-chip {
  font-size: .72rem; font-weight: 600; color: #6b7280;
  display: flex; align-items: center; gap: .3rem;
}
.jp-info-chip strong { color: #374151; }

/* ── Download button ────────────────────────────────────── */
.jp-download-btn {
  display: flex; align-items: center; justify-content: center; gap: .5rem;
  width: 100%; padding: .8rem 1.5rem; border-radius: .875rem;
  font-size: .95rem; font-weight: 800; cursor: pointer; text-decoration: none;
  background: #166534; color: #fff; border: none;
  box-shadow: 0 4px 14px rgba(22,101,52,.35);
  transition: all .16s;
}
.jp-download-btn:hover { background: #14532d; box-shadow: 0 6px 20px rgba(22,101,52,.5); transform: translateY(-1px); }

/* ── Size comparison bar ────────────────────────────────── */
.jp-size-row {
  display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; gap: .75rem;
  padding: .75rem 1rem; background: #f8fafc; border-radius: .875rem;
  border: 1.5px solid #e5e7eb; font-size: .78rem;
}
.jp-size-label { font-weight: 700; color: #374151; }
.jp-size-val   { font-weight: 800; font-family: 'JetBrains Mono','Fira Code',monospace; }
.jp-size-arrow { color: #9ca3af; font-size: .9rem; text-align: center; }
.jp-size-note  { font-size: .7rem; color: #6b7280; margin-top: .2rem; }

/* ── Validation / error ─────────────────────────────────── */
.jp-error {
  display: flex; align-items: flex-start; gap: .5rem;
  padding: .65rem .9rem; border-radius: .75rem;
  background: #fef2f2; border: 1.5px solid #fecaca;
  font-size: .8rem; color: #991b1b; font-weight: 500;
}

/* ── Spinner ────────────────────────────────────────────── */
@keyframes jpSpin { to { transform: rotate(360deg); } }
.jp-spin {
  display: inline-block; width: 1em; height: 1em; border-radius: 50%;
  border: 2px solid rgba(255,255,255,.4); border-top-color: #fff;
  animation: jpSpin .65s linear infinite; flex-shrink: 0;
}

/* ── Divider ────────────────────────────────────────────── */
.jp-div {
  display: flex; align-items: center; gap: .6rem;
  font-size: .6rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: #6b7280;
}
.jp-div::before,.jp-div::after { content:''; flex:1; height:1px; background:#e5e7eb; }

/* ── Select file again hint ─────────────────────────────── */
.jp-change-hint {
  font-size: .72rem; color: #9ca3af; text-align: center; cursor: pointer;
  text-decoration: underline; text-underline-offset: 2px;
}
.jp-change-hint:hover { color: #166534; }

@media (max-width: 640px) {
  .jp-dropzone { padding: 1.5rem 1rem; }
  .jp-dz-icon  { font-size: 2rem; }
}
</style>

<div class="min-h-screen bg-gray-50">

  {{-- ── Hero header ── --}}
  <div class="bg-white border-b border-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
      <div class="flex items-center gap-4 mb-4">
        <div class="w-14 h-14 rounded-xl bg-green-100 flex items-center justify-content-center text-3xl flex-shrink-0 flex justify-center">
          🖼️
        </div>
        <div>
          <h1 class="text-3xl font-bold text-gray-900">JPG to PNG Converter</h1>
          <p class="text-gray-500 mt-1">Convert any JPG or JPEG image to a lossless PNG — free, instant, and fully private.</p>
        </div>
      </div>
      <x-breadcrumb :items="[
          ['label' => 'Home', 'url' => url('/')],
          ['label' => $tool->category->name, 'url' => route('categories.show', $tool->category)],
          ['label' => 'JPG to PNG']
      ]"/>
    </div>
  </div>

  <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
    <div class="grid gap-8 lg:grid-cols-3">

      {{-- ── Main tool area ── --}}
      <div class="lg:col-span-2 space-y-5" x-data="jpTool()">

        {{-- ══ Upload card (shown until result is ready) ══ --}}
        <div class="card p-6 space-y-5" x-show="!pngDataUrl">

          <h2 class="text-lg font-semibold text-gray-900">Select a JPG Image</h2>

          {{-- Privacy badge --}}
          <div class="jp-privacy">
            <span>🔒</span>
            <span>Converted entirely in your browser — your image is never sent to any server.</span>
          </div>

          {{-- Drop zone --}}
          <div
            :class="['jp-dropzone', isDragging ? 'jp-drag-over' : '', file ? 'jp-has-file' : '']"
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop.prevent="onDrop($event)"
            @click="$refs.fileInput.click()"
          >
            <input
              type="file"
              x-ref="fileInput"
              accept=".jpg,.jpeg,image/jpeg"
              @change="onFileChange($event)"
              class="hidden"
            >

            {{-- No file --}}
            <div x-show="!file">
              <div class="jp-dz-icon">🖼️ → 📄</div>
              <p class="jp-dz-title">Drag &amp; drop your JPG here</p>
              <p class="jp-dz-sub">or click to browse &nbsp;·&nbsp; JPG/JPEG only &nbsp;·&nbsp; max 20 MB</p>
            </div>

            {{-- File selected --}}
            <div x-show="file">
              <div class="jp-dz-icon">✅</div>
              <p class="jp-dz-title" x-text="file ? file.name : ''"></p>
              <p class="jp-dz-sub" x-text="file ? (formatSize(file.size) + ' — click to change') : ''"></p>
            </div>
          </div>

          {{-- Validation error --}}
          <div x-show="fileError" x-transition class="jp-error">
            <span>⚠</span><span x-text="fileError"></span>
          </div>

          {{-- General error --}}
          <div x-show="convError" x-transition class="jp-error">
            <span>⚠</span><span x-text="convError"></span>
          </div>

          {{-- Convert button --}}
          <button
            @click="convert()"
            :disabled="!file || !!fileError || converting"
            class="jp-convert-btn"
          >
            <span x-show="converting" class="jp-spin"></span>
            <span x-show="converting">Converting…</span>
            <span x-show="!converting">🔄 Convert to PNG</span>
          </button>

        </div>

        {{-- ══ Result card ══ --}}
        <div class="card p-6 space-y-5" x-show="pngDataUrl" x-transition>

          {{-- Success banner --}}
          <div class="jp-result-banner">
            <span>✅</span>
            <span>Conversion complete! Your PNG is ready to download.</span>
          </div>

          {{-- Before / After comparison --}}
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

            {{-- Original JPG --}}
            <div class="jp-panel">
              <div class="jp-panel-hdr jp-panel-hdr-jpg">
                <span>Original &nbsp;·&nbsp; JPG</span>
                <span x-text="originalWidth + ' × ' + originalHeight + ' px'"></span>
              </div>
              <div class="jp-img-wrap">
                <img :src="jpgPreviewUrl"
                     alt="Original JPG"
                     class="jp-preview-img"
                     loading="lazy">
              </div>
              <div class="jp-panel-info">
                <span class="jp-info-chip">📁 <strong x-text="file ? formatSize(file.size) : ''"></strong></span>
                <span class="jp-info-chip">📐 <strong x-text="originalWidth + ' × ' + originalHeight"></strong></span>
                <span class="jp-info-chip">🗜️ Lossy compression</span>
              </div>
            </div>

            {{-- Converted PNG --}}
            <div class="jp-panel">
              <div class="jp-panel-hdr jp-panel-hdr-png">
                <span>Converted &nbsp;·&nbsp; PNG</span>
                <span x-text="originalWidth + ' × ' + originalHeight + ' px'"></span>
              </div>
              <div class="jp-img-wrap">
                <img :src="pngDataUrl"
                     alt="Converted PNG"
                     class="jp-preview-img"
                     loading="lazy">
              </div>
              <div class="jp-panel-info">
                <span class="jp-info-chip">📁 <strong x-text="formatSize(pngSizeBytes)"></strong></span>
                <span class="jp-info-chip">📐 <strong x-text="originalWidth + ' × ' + originalHeight"></strong></span>
                <span class="jp-info-chip">✨ Lossless</span>
              </div>
            </div>

          </div>

          {{-- Size comparison note --}}
          <div class="jp-size-row">
            <div>
              <div class="jp-size-label">JPG (original)</div>
              <div class="jp-size-val text-amber-600" x-text="formatSize(file ? file.size : 0)"></div>
            </div>
            <div class="jp-size-arrow">→</div>
            <div>
              <div class="jp-size-label">PNG (converted)</div>
              <div class="jp-size-val text-green-700" x-text="formatSize(pngSizeBytes)"></div>
              <div class="jp-size-note" x-show="pngSizeBytes > (file ? file.size : 0)">
                PNG is lossless — larger file size is normal.
              </div>
              <div class="jp-size-note" x-show="pngSizeBytes <= (file ? file.size : 0)">
                Same quality, lossless encoding.
              </div>
            </div>
          </div>

          {{-- Download button --}}
          <a
            :href="pngDataUrl"
            :download="pngFileName"
            class="jp-download-btn"
          >
            ⬇ Download PNG
          </a>

          {{-- Reset --}}
          <p class="jp-change-hint" @click="reset()">
            ↺ Convert another image
          </p>

        </div>

        {{-- ══ How it works ══ --}}
        <div class="card p-6" x-show="!pngDataUrl || true">
          <p class="jp-div mb-4">How It Works</p>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
            <div class="flex gap-3">
              <span class="text-2xl flex-shrink-0">📤</span>
              <div>
                <p class="font-semibold text-gray-800">1. Upload JPG</p>
                <p class="text-gray-500 text-xs mt-0.5">Select any JPG or JPEG photo — from camera, web, or scans.</p>
              </div>
            </div>
            <div class="flex gap-3">
              <span class="text-2xl flex-shrink-0">🔄</span>
              <div>
                <p class="font-semibold text-gray-800">2. Instant Convert</p>
                <p class="text-gray-500 text-xs mt-0.5">The browser renders the image onto a canvas and re-encodes it as PNG instantly.</p>
              </div>
            </div>
            <div class="flex gap-3">
              <span class="text-2xl flex-shrink-0">💾</span>
              <div>
                <p class="font-semibold text-gray-800">3. Download PNG</p>
                <p class="text-gray-500 text-xs mt-0.5">Save the lossless PNG directly to your device with one click.</p>
              </div>
            </div>
          </div>
        </div>

      </div>

      {{-- ── Sidebar ── --}}
      <div class="space-y-6">

        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Category</h3>
          <a href="{{ route('categories.show', $tool->category) }}"
             class="flex items-center gap-3 p-3 rounded-xl bg-brand-50 hover:bg-brand-100 transition-colors">
            <span class="text-xl">{{ $tool->category->icon }}</span>
            <span class="font-medium text-brand-700">{{ $tool->category->name }}</span>
          </a>
        </div>

        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">JPG vs PNG</h3>
          <div class="space-y-3 text-xs text-gray-600">
            <div class="p-3 bg-amber-50 rounded-xl border border-amber-100">
              <p class="font-bold text-amber-800 mb-1">JPG / JPEG</p>
              <ul class="space-y-1">
                <li>• Lossy compression (some quality lost)</li>
                <li>• Smaller file sizes — good for photos</li>
                <li>• No transparency support</li>
                <li>• Best for photos / web images</li>
              </ul>
            </div>
            <div class="p-3 bg-green-50 rounded-xl border border-green-100">
              <p class="font-bold text-green-800 mb-1">PNG</p>
              <ul class="space-y-1">
                <li>• Lossless — zero quality loss</li>
                <li>• Larger file sizes</li>
                <li>• Supports transparency (alpha)</li>
                <li>• Best for graphics, screenshots, logos</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Things to Know</h3>
          <ul class="space-y-2.5 text-xs text-gray-600">
            <li class="flex gap-2"><span class="text-green-600 font-bold flex-shrink-0">•</span><span>The PNG will preserve every pixel from your JPG exactly — no further quality loss.</span></li>
            <li class="flex gap-2"><span class="text-green-600 font-bold flex-shrink-0">•</span><span>PNG files are often <strong>2–5× larger</strong> than the original JPEG — this is normal.</span></li>
            <li class="flex gap-2"><span class="text-green-600 font-bold flex-shrink-0">•</span><span>JPEG compression artifacts (if any) are preserved — this tool doesn't reconstruct lost data.</span></li>
            <li class="flex gap-2"><span class="text-green-600 font-bold flex-shrink-0">•</span><span>The converted PNG background will be white (JPG has no transparency).</span></li>
            <li class="flex gap-2"><span class="text-green-600 font-bold flex-shrink-0">🔒</span><span>Your image is processed locally — nothing is uploaded.</span></li>
          </ul>
        </div>

        @if($relatedTools->count() > 0)
        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Related Tools</h3>
          <div class="space-y-2">
            @foreach($relatedTools as $related)
            <a href="{{ route('tools.show', $related) }}"
               class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-gray-50 transition-colors group">
              <span class="text-lg">{{ $related->icon }}</span>
              <span class="text-sm text-gray-700 group-hover:text-brand-600 transition-colors">{{ $related->name }}</span>
            </a>
            @endforeach
          </div>
        </div>
        @endif

      </div>

    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
/* ─────────────────────────────────────────────────────────────────
   JPG → PNG Converter — Alpine.js component
   CSS prefix: jp-

   Conversion pipeline (all in-browser, zero server contact):
     1. User selects / drops a .jpg/.jpeg file
     2. FileReader reads it as a data URL
     3. An <img> element loads the data URL → triggers onload
     4. Canvas (same dimensions as the image) draws the image
     5. canvas.toDataURL('image/png') returns the PNG data URL
     6. The data URL is used as the href of a download <a> link

   Why the canvas background is white:
     JPEG has no alpha channel, so we fill the canvas with white
     before drawing, ensuring the PNG also has no transparent areas.

   Size note:
     PNG is lossless — the output file is always larger than the
     JPEG input. This is expected and is displayed to the user.
─────────────────────────────────────────────────────────────────── */
function jpTool() {
  return {

    // ── File state ───────────────────────────────────────
    file:         null,
    fileError:    '',
    isDragging:   false,
    jpgPreviewUrl:'',   // object URL for the original JPG preview

    // ── Conversion state ─────────────────────────────────
    converting: false,
    convError:  '',

    // ── Result ───────────────────────────────────────────
    pngDataUrl:    '',
    pngFileName:   '',
    pngSizeBytes:  0,
    originalWidth: 0,
    originalHeight:0,

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
      this.fileError  = '';
      this.convError  = '';
      this.pngDataUrl = '';

      var err = this._validate(file);
      if (err) { this.fileError = err; this.file = null; return; }

      // Revoke previous object URL to avoid memory leaks
      if (this.jpgPreviewUrl) URL.revokeObjectURL(this.jpgPreviewUrl);

      this.file          = file;
      this.jpgPreviewUrl = URL.createObjectURL(file);
    },

    _validate(file) {
      if (!file) return 'No file selected.';

      var isJpeg = file.type === 'image/jpeg'
                || file.type === 'image/jpg'
                || /\.(jpg|jpeg)$/i.test(file.name);

      if (!isJpeg)
        return 'Only JPG/JPEG images are supported. Please select a file ending in .jpg or .jpeg.';

      if (file.size > 20 * 1024 * 1024)
        return 'File too large (' + this.formatSize(file.size) + '). Maximum allowed size is 20 MB.';

      return '';
    },

    // ── Conversion ───────────────────────────────────────
    async convert() {
      if (!this.file || this.fileError) return;

      this.converting  = true;
      this.convError   = '';
      this.pngDataUrl  = '';

      try {
        // Step 1: read the file into a data URL
        var jpgDataUrl = await this._readAsDataUrl(this.file);

        // Step 2: load into an Image element
        var img = await this._loadImage(jpgDataUrl);

        // Step 3: draw onto a canvas
        var canvas  = document.createElement('canvas');
        canvas.width  = img.naturalWidth;
        canvas.height = img.naturalHeight;
        var ctx = canvas.getContext('2d');

        // Fill white background — JPEG has no alpha; this makes the
        // PNG background opaque white rather than black on some systems
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(img, 0, 0);

        // Step 4: export as PNG
        var pngDataUrl = canvas.toDataURL('image/png');

        // Store results
        this.originalWidth  = img.naturalWidth;
        this.originalHeight = img.naturalHeight;
        this.pngDataUrl     = pngDataUrl;
        this.pngFileName    = this.file.name.replace(/\.(jpg|jpeg)$/i, '') + '.png';

        // Approximate PNG size from base64 string length
        // base64 encodes 3 bytes → 4 chars, so bytes ≈ base64.length * 0.75
        var b64 = pngDataUrl.split(',')[1] || '';
        this.pngSizeBytes = Math.round(b64.length * 0.75);

        // Release canvas memory
        canvas.width  = 0;
        canvas.height = 0;

      } catch (err) {
        this.convError = this._friendlyError(err);
      } finally {
        this.converting = false;
      }
    },

    _friendlyError(err) {
      if (!err) return 'An unknown error occurred during conversion.';
      var msg = String(err.message || err);
      if (msg.toLowerCase().includes('memory') || msg.toLowerCase().includes('quota'))
        return 'The image is too large for the browser to process. Please try a smaller image or close other tabs.';
      if (msg.toLowerCase().includes('load') || msg.toLowerCase().includes('decode'))
        return 'The file could not be read as an image. It may be corrupted or not a valid JPEG.';
      return 'Conversion failed: ' + msg;
    },

    // ── Helpers: async wrappers ───────────────────────────
    _readAsDataUrl(file) {
      return new Promise(function(resolve, reject) {
        var reader = new FileReader();
        reader.onload  = function(e) { resolve(e.target.result); };
        reader.onerror = function()  { reject(new Error('Failed to read file.')); };
        reader.readAsDataURL(file);
      });
    },

    _loadImage(src) {
      return new Promise(function(resolve, reject) {
        var img    = new Image();
        img.onload  = function() { resolve(img); };
        img.onerror = function() { reject(new Error('Image could not be loaded or decoded.')); };
        img.src = src;
      });
    },

    // ── Reset ─────────────────────────────────────────────
    reset() {
      if (this.jpgPreviewUrl) URL.revokeObjectURL(this.jpgPreviewUrl);

      this.file          = null;
      this.fileError     = '';
      this.convError     = '';
      this.isDragging    = false;
      this.converting    = false;
      this.pngDataUrl    = '';
      this.pngFileName   = '';
      this.pngSizeBytes  = 0;
      this.originalWidth = 0;
      this.originalHeight= 0;
      this.jpgPreviewUrl = '';

      if (this.$refs.fileInput) this.$refs.fileInput.value = '';
    },

    // ── Utility ──────────────────────────────────────────
    formatSize(bytes) {
      if (!bytes || bytes === 0) return '0 B';
      if (bytes < 1024)          return bytes + ' B';
      if (bytes < 1024 * 1024)   return (bytes / 1024).toFixed(1) + ' KB';
      return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
    },
  };
}
</script>
@endpush
