

<?php $__env->startSection('title', $tool->seo_title ?: 'Instagram Reels & Stories Downloader - Free Online Tool'); ?>
<?php $__env->startSection('description', $tool->seo_description ?: 'Download Instagram Reels, Stories, Posts and Videos for free. Paste any public Instagram URL and save the media instantly.'); ?>

<?php $__env->startSection('renders_own_faqs', '1'); ?>
<?php $__env->startSection('content'); ?>
<style>
/* ══════════════════════════════════════════════════════════════
   Instagram Reels & Stories Downloader  —  prefix: irs-
   Theme: Instagram Stories gradient — orange #f77737 → pink #e1306c → purple #833ab4
   Backend: POST /tools/instagram-reels-and-stories-downloader/process
   Proxy:   GET  /tools/instagram-downloader/proxy?url=...
   Note:    Stories require a logged-in session; public reels/posts work without auth.
══════════════════════════════════════════════════════════════ */

/* ── Gradient helpers ────────────────────────────────────── */
.irs-gradient        { background: linear-gradient(135deg, #833ab4 0%, #e1306c 50%, #f77737 100%); }
.irs-gradient-text   { background: linear-gradient(135deg, #833ab4, #e1306c, #f77737);
                        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
                        background-clip: text; }
.irs-border-color    { border-color: #e1306c; }

/* ── URL input ───────────────────────────────────────────── */
.irs-input-wrap { position: relative; }
.irs-input {
  width: 100%;
  padding: .9rem 1.1rem .9rem 3.1rem;
  border: 2px solid #e5e7eb;
  border-radius: .875rem;
  font-size: .95rem;
  outline: none;
  transition: border-color .15s, box-shadow .15s;
  background: #fff;
  color: #111827;
}
.irs-input:focus {
  border-color: #e1306c;
  box-shadow: 0 0 0 3px rgba(225,48,108,.12);
}
.irs-input.irs-input-err { border-color: #ef4444; }
.irs-input-icon {
  position: absolute; left: .95rem; top: 50%; transform: translateY(-50%);
  font-size: 1.2rem; pointer-events: none; user-select: none; line-height: 1;
}

/* ── Primary button ──────────────────────────────────────── */
.irs-btn {
  width: 100%; padding: .9rem 1.5rem; border-radius: .875rem;
  font-size: 1rem; font-weight: 800; cursor: pointer;
  display: flex; align-items: center; justify-content: center; gap: .55rem;
  transition: all .16s; border: none; color: #fff;
  background: linear-gradient(135deg, #833ab4 0%, #e1306c 50%, #f77737 100%);
  box-shadow: 0 4px 16px rgba(225,48,108,.35);
}
.irs-btn:hover:not(:disabled) {
  box-shadow: 0 7px 22px rgba(225,48,108,.5);
  transform: translateY(-1px);
}
.irs-btn:disabled { opacity: .4; cursor: not-allowed; transform: none; box-shadow: none; }

/* ── Spinner ─────────────────────────────────────────────── */
@keyframes irsSpin { to { transform: rotate(360deg); } }
.irs-spin {
  display: inline-block; width: 1em; height: 1em; border-radius: 50%;
  border: 2px solid rgba(255,255,255,.4); border-top-color: #fff;
  animation: irsSpin .65s linear infinite; flex-shrink: 0;
}

/* ── Error / warning box ─────────────────────────────────── */
.irs-error {
  display: flex; align-items: flex-start; gap: .5rem;
  padding: .7rem .9rem; border-radius: .75rem;
  background: #fef2f2; border: 1.5px solid #fecaca;
  font-size: .82rem; color: #991b1b; font-weight: 500; line-height: 1.5;
}
.irs-warn {
  display: flex; align-items: flex-start; gap: .5rem;
  padding: .6rem .85rem; border-radius: .75rem;
  background: #fffbeb; border: 1px solid #fde68a;
  font-size: .76rem; color: #92400e; font-weight: 500; line-height: 1.45;
}

/* ── Info note ───────────────────────────────────────────── */
.irs-note {
  display: flex; align-items: flex-start; gap: .5rem;
  padding: .65rem .9rem; border-radius: .75rem;
  background: #fff7ed; border: 1px solid #fed7aa;
  font-size: .76rem; color: #9a3412; font-weight: 500; line-height: 1.45;
}

/* ── Success banner ──────────────────────────────────────── */
.irs-success {
  display: flex; align-items: center; gap: .65rem;
  padding: .75rem 1rem; border-radius: .875rem;
  background: linear-gradient(135deg, #fdf4ff 0%, #fff7ed 100%);
  border: 1.5px solid #fbcfe8;
  font-size: .88rem; font-weight: 600; color: #9d174d;
}

/* ── Media type badge ────────────────────────────────────── */
.irs-badge {
  display: inline-flex; align-items: center; gap: .3rem;
  padding: .22rem .7rem; border-radius: 999px;
  font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em;
}
.irs-badge-reel  { background: #fce7f3; color: #9d174d; }
.irs-badge-image { background: #ede9fe; color: #6d28d9; }
.irs-badge-video { background: #fff7ed; color: #c2410c; }

/* ── Preview containers ──────────────────────────────────── */
.irs-preview-wrap {
  border-radius: .875rem; overflow: hidden;
  background: repeating-conic-gradient(#f3f4f6 0% 25%, #fff 0% 50%) 0 0 / 16px 16px;
  display: flex; align-items: center; justify-content: center;
  min-height: 180px;
  border: 1.5px solid #fbcfe8;
}
.irs-preview-img  { max-width: 100%; max-height: 420px; object-fit: contain; display: block; }
.irs-preview-video {
  width: 100%; max-height: 480px;
  border-radius: .875rem;
  border: 1.5px solid #fbcfe8;
  background: #000; display: block;
}

/* ── Video fallback (when CDN video doesn't load) ─────────── */
.irs-video-fallback {
  border-radius: .875rem; overflow: hidden;
  border: 1.5px solid #fbcfe8; background: #000;
  position: relative; cursor: pointer;
}
.irs-play-overlay {
  position: absolute; inset: 0;
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  background: rgba(0,0,0,.45); gap: .5rem;
}
.irs-play-circle {
  width: 68px; height: 68px; border-radius: 50%;
  background: rgba(255,255,255,.92); display: flex; align-items: center; justify-content: center;
  font-size: 1.9rem; line-height: 1; padding-left: 5px;
  box-shadow: 0 4px 20px rgba(0,0,0,.4);
}
.irs-play-label { color: #fff; font-size: .75rem; font-weight: 600; letter-spacing: .02em; }

/* ── Caption card ────────────────────────────────────────── */
.irs-caption {
  background: linear-gradient(135deg, #fdf4ff 0%, #fff7ed 100%);
  border: 1.5px solid #fbcfe8; border-radius: .875rem;
  padding: .9rem 1rem;
}
.irs-caption-label { font-size: .68rem; font-weight: 700; color: #be185d; text-transform: uppercase; letter-spacing: .07em; margin-bottom: .35rem; }
.irs-caption-text  { font-size: .82rem; color: #374151; line-height: 1.55; }

/* ── Download item card ──────────────────────────────────── */
.irs-dl-card {
  padding: .9rem 1rem; border-radius: .875rem;
  background: #fafafa; border: 1.5px solid #e5e7eb;
}
.irs-dl-label {
  font-size: .73rem; font-weight: 700; color: #6b7280;
  text-transform: uppercase; letter-spacing: .05em;
  margin-bottom: .65rem; display: flex; align-items: center; gap: .35rem;
}
.irs-actions { display: flex; gap: .55rem; flex-wrap: wrap; }

/* ── Action buttons ──────────────────────────────────────── */
.irs-dl-btn {
  flex: 1; min-width: 110px;
  display: flex; align-items: center; justify-content: center; gap: .4rem;
  padding: .68rem 1rem; border-radius: .75rem;
  font-size: .83rem; font-weight: 700; cursor: pointer; text-decoration: none;
  border: none; transition: all .15s; color: #fff;
  background: linear-gradient(135deg, #e1306c, #f77737);
  box-shadow: 0 3px 10px rgba(225,48,108,.25);
}
.irs-dl-btn:hover { box-shadow: 0 5px 16px rgba(225,48,108,.4); transform: translateY(-1px); color: #fff; }

.irs-copy-btn {
  display: flex; align-items: center; justify-content: center; gap: .4rem;
  padding: .68rem 1rem; border-radius: .75rem;
  font-size: .83rem; font-weight: 700; cursor: pointer;
  border: 2px solid #fbcfe8; background: #fff; color: #be185d;
  transition: all .15s; white-space: nowrap;
}
.irs-copy-btn:hover { background: #fdf2f8; border-color: #f9a8d4; }
.irs-copy-btn.irs-copied { background: #f0fdf4; border-color: #bbf7d0; color: #166534; }

.irs-open-btn {
  display: flex; align-items: center; justify-content: center; gap: .4rem;
  padding: .68rem 1rem; border-radius: .75rem;
  font-size: .83rem; font-weight: 700; cursor: pointer;
  border: 2px solid #e5e7eb; background: #fff; color: #6b7280;
  transition: all .15s; text-decoration: none; white-space: nowrap;
}
.irs-open-btn:hover { background: #f9fafb; border-color: #d1d5db; color: #374151; }

/* ── Server-note small banner ────────────────────────────── */
.irs-server-note {
  display: flex; align-items: flex-start; gap: .45rem;
  padding: .55rem .8rem; border-radius: .75rem;
  background: #fffbeb; border: 1px solid #fde68a;
  font-size: .73rem; color: #92400e; line-height: 1.4;
}

/* ── Reset link ──────────────────────────────────────────── */
.irs-reset {
  font-size: .72rem; color: #9ca3af; text-align: center; cursor: pointer;
  text-decoration: underline; text-underline-offset: 2px;
}
.irs-reset:hover { color: #e1306c; }

/* ── Section divider ─────────────────────────────────────── */
.irs-divider {
  display: flex; align-items: center; gap: .6rem;
  font-size: .6rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: #6b7280;
}
.irs-divider::before,.irs-divider::after { content:''; flex:1; height:1px; background:#e5e7eb; }

/* ── Feature grid ────────────────────────────────────────── */
.irs-feature {
  display: flex; align-items: flex-start; gap: .65rem;
  padding: .75rem .85rem; border-radius: .875rem;
  background: #fafafa; border: 1.5px solid #f3f4f6;
}
.irs-feature-icon { font-size: 1.4rem; flex-shrink: 0; line-height: 1.2; }

/* ── Supported types list in sidebar ────────────────────── */
.irs-item { display: flex; align-items: center; gap: .5rem; font-size: .82rem; padding: .2rem 0; }
.irs-yes  { color: #16a34a; font-weight: 700; flex-shrink: 0; }
.irs-no   { color: #dc2626; font-weight: 700; flex-shrink: 0; }
.irs-part { color: #d97706; font-weight: 700; flex-shrink: 0; font-size: .75rem; }

@media (max-width: 640px) {
  .irs-actions { flex-direction: column; }
  .irs-dl-btn, .irs-copy-btn, .irs-open-btn { width: 100%; min-width: 0; }
}
</style>

<div class="min-h-screen bg-gray-50">

  
  <div class="bg-white border-b border-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
      <div class="flex items-center gap-4 mb-4">
        
        <div class="flex-shrink-0 p-1 rounded-2xl irs-gradient" style="padding:3px;">
          <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-2xl">
            🎬
          </div>
        </div>
        <div>
          <h1 class="text-3xl font-bold text-gray-900">
            Instagram Reels &amp; Stories Downloader
          </h1>
          <p class="text-gray-500 mt-1">
            <?php echo e($tool->short_description ?: 'Download Instagram Reels, Posts & Videos in HD — free, fast, no login required for public content.'); ?>

          </p>
        </div>
      </div>
      <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [
          ['label' => 'Home',                'url' => url('/')],
          ['label' => $tool->category->name, 'url' => route('categories.show', $tool->category)],
          ['label' => 'Instagram Reels & Stories Downloader']
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
          ['label' => 'Instagram Reels & Stories Downloader']
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

      
      <div class="lg:col-span-2 space-y-5" x-data="irsDownloader()">

        
        <div class="card p-6 space-y-5" x-show="!result">

          <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Paste Instagram URL</h2>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full text-white irs-gradient">FREE</span>
          </div>

          
          <div class="irs-note">
            <span class="flex-shrink-0 mt-0.5">⚡</span>
            <span>Works with <strong>public</strong> Reels, Posts, and Videos. Instagram Stories are private and require login — they cannot be downloaded without authentication.</span>
          </div>

          
          <div>
            <label for="irs-url" class="block text-sm font-medium text-gray-700 mb-1.5">
              Instagram Reel / Post / Video URL
            </label>
            <div class="irs-input-wrap">
              <span class="irs-input-icon">📎</span>
              <input
                id="irs-url"
                type="url"
                x-model="url"
                :class="['irs-input', urlError ? 'irs-input-err' : '']"
                placeholder="https://www.instagram.com/reel/ABC123xyz/"
                x-on:input="urlError = ''; fetchError = ''"
                x-on:keydown.enter="submit()"
                autocomplete="off"
                spellcheck="false"
              >
            </div>
            
            <div x-show="urlError" x-transition class="irs-error mt-2">
              <span class="flex-shrink-0">⚠</span><span x-text="urlError"></span>
            </div>
          </div>

          
          <div x-show="fetchError" x-transition class="irs-error">
            <span class="flex-shrink-0 mt-0.5">⚠</span>
            <div>
              <strong class="block mb-0.5">Could not download media</strong>
              <span x-text="fetchError"></span>
            </div>
          </div>

          
          <button x-on:click="submit()" :disabled="loading" class="irs-btn">
            <span x-show="loading" class="irs-spin"></span>
            <span x-show="loading">Fetching Media…</span>
            <span x-show="!loading">⬇ Download Instagram Media</span>
          </button>

          
          <div class="text-xs text-gray-400 flex flex-wrap gap-x-4 gap-y-1 justify-center">
            <span>✓ instagram.com/reel/…</span>
            <span>✓ instagram.com/p/…</span>
            <span>✓ instagram.com/tv/…</span>
          </div>

        </div>

        
        <div class="card p-6 space-y-5" x-show="result" x-transition>

          
          <div class="irs-success">
            <span>✅</span>
            <span x-text="result && result.type === 'video'
              ? 'Reel / Video found — preview & save below!'
              : 'Image found — ready to download!'">
            </span>
          </div>

          
          <template x-if="result && result.type === 'video' && result.video_url && !videoError">
            <div>
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold text-gray-700">Preview</span>
                <span class="irs-badge irs-badge-reel">🎬 Reel / Video</span>
              </div>
              <video
                :src="result.video_url"
                :poster="result.thumbnail || ''"
                controls
                preload="metadata"
                playsinline
                class="irs-preview-video"
                x-on:error.self="videoError = true"
              >
                Your browser does not support the video tag.
              </video>
              <p class="text-xs text-gray-400 mt-1.5 text-center">
                Click <strong>Download</strong> below to save the MP4 file to your device.
              </p>
            </div>
          </template>

          
          <template x-if="result && result.type === 'video' && videoError">
            <div>
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold text-gray-700">Thumbnail</span>
                <span class="irs-badge irs-badge-reel">🎬 Reel / Video</span>
              </div>
              <div class="irs-video-fallback" x-on:click="openTab(result.video_url)">
                <img :src="result.thumbnail || ''" class="irs-preview-img w-full" style="opacity:.65;">
                <div class="irs-play-overlay">
                  <div class="irs-play-circle">▶</div>
                  <span class="irs-play-label">Tap to open video in new tab</span>
                </div>
              </div>
            </div>
          </template>

          
          <template x-if="result && result.type !== 'video' && result.thumbnail">
            <div>
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold text-gray-700">Preview</span>
                <span class="irs-badge irs-badge-image">🖼 Image</span>
              </div>
              <div class="irs-preview-wrap">
                <img
                  :src="result.thumbnail"
                  :alt="result.title || 'Instagram media'"
                  class="irs-preview-img"
                  loading="lazy"
                >
              </div>
            </div>
          </template>

          
          <div x-show="result && result.title" class="irs-caption">
            <div class="irs-caption-label">Caption</div>
            <div class="irs-caption-text" x-text="result ? result.title : ''"></div>
          </div>

          
          <div x-show="result && result.note" class="irs-server-note">
            <span class="flex-shrink-0">ℹ️</span>
            <span x-text="result ? result.note : ''"></span>
          </div>

          
          <template x-if="result && result.items && result.items.length">
            <div class="space-y-3">
              <p class="irs-divider">Download Options</p>
              <template x-for="(item, idx) in result.items" :key="idx">
                <div class="irs-dl-card">
                  <div class="irs-dl-label">
                    <span x-text="item.type === 'video' ? '🎬' : '🖼'"></span>
                    <span x-text="item.label || (item.type === 'video' ? 'Video / Reel' : 'Image')"></span>
                  </div>
                  <div class="irs-actions">
                    
                    <a
                      :href="proxyUrl(item.url)"
                      download
                      class="irs-dl-btn"
                    >⬇ Download</a>

                    
                    <a
                      :href="item.url"
                      target="_blank"
                      rel="noopener noreferrer"
                      class="irs-open-btn"
                    >🔗 Open</a>

                    
                    <button
                      x-on:click="copyUrl(item.url, idx)"
                      :class="['irs-copy-btn', copied === idx ? 'irs-copied' : '']"
                    >
                      <span x-text="copied === idx ? '✓ Copied!' : '📋 Copy URL'"></span>
                    </button>
                  </div>
                </div>
              </template>
            </div>
          </template>

          
          <p class="irs-reset" x-on:click="reset()">↺ Download another</p>

        </div>

        
        <div class="card p-6">
          <p class="irs-divider mb-4">How It Works</p>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="irs-feature">
              <span class="irs-feature-icon">🔗</span>
              <div>
                <p class="text-sm font-semibold text-gray-800">1. Copy Link</p>
                <p class="text-gray-500 text-xs mt-0.5">Open Instagram, tap ⋯ on any public Reel or post, and copy the link.</p>
              </div>
            </div>
            <div class="irs-feature">
              <span class="irs-feature-icon">⚙️</span>
              <div>
                <p class="text-sm font-semibold text-gray-800">2. Paste &amp; Fetch</p>
                <p class="text-gray-500 text-xs mt-0.5">Paste the URL above. Our server extracts the HD media link from Instagram.</p>
              </div>
            </div>
            <div class="irs-feature">
              <span class="irs-feature-icon">💾</span>
              <div>
                <p class="text-sm font-semibold text-gray-800">3. Save Media</p>
                <p class="text-gray-500 text-xs mt-0.5">Preview then hit Download to save the MP4 video or image to your device.</p>
              </div>
            </div>
          </div>
        </div>

        <?php if($tool->long_description): ?>
        <div class="card p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">About This Tool</h2>
          <div class="tool-prose"><?php echo nl2br(e($tool->long_description)); ?></div>
        </div>
        <?php endif; ?>

        <?php if($tool->faqs->count() > 0): ?>
        <div class="card p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Frequently Asked Questions</h2>
          <?php if (isset($component)) { $__componentOriginal3d56a80c35333d0f1afd23147c30df36 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3d56a80c35333d0f1afd23147c30df36 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.faq-list','data' => ['faqs' => $tool->faqs]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('faq-list'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['faqs' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tool->faqs)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3d56a80c35333d0f1afd23147c30df36)): ?>
<?php $attributes = $__attributesOriginal3d56a80c35333d0f1afd23147c30df36; ?>
<?php unset($__attributesOriginal3d56a80c35333d0f1afd23147c30df36); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3d56a80c35333d0f1afd23147c30df36)): ?>
<?php $component = $__componentOriginal3d56a80c35333d0f1afd23147c30df36; ?>
<?php unset($__componentOriginal3d56a80c35333d0f1afd23147c30df36); ?>
<?php endif; ?>
        </div>
        <?php endif; ?>

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
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Supported Content</h3>
          <div class="space-y-1.5">
            <div class="irs-item"><span class="irs-yes">✓</span><span class="text-gray-700">Reels (MP4 video)</span></div>
            <div class="irs-item"><span class="irs-yes">✓</span><span class="text-gray-700">Photos &amp; images</span></div>
            <div class="irs-item"><span class="irs-yes">✓</span><span class="text-gray-700">IGTV &amp; long videos</span></div>
            <div class="irs-item"><span class="irs-yes">✓</span><span class="text-gray-700">Public accounts only</span></div>
            <div class="irs-item"><span class="irs-part">~</span><span class="text-gray-500">Carousel posts (1st item)</span></div>
            <div class="irs-item"><span class="irs-no">✗</span><span class="text-gray-500">Stories (auth required)</span></div>
            <div class="irs-item"><span class="irs-no">✗</span><span class="text-gray-500">Private accounts</span></div>
          </div>
        </div>

        
        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">How to Copy a Link</h3>
          <ol class="space-y-2.5 text-xs text-gray-600">
            <li class="flex gap-2">
              <span class="text-pink-500 font-bold flex-shrink-0">1.</span>
              <span>Open Instagram and go to the Reel or post you want.</span>
            </li>
            <li class="flex gap-2">
              <span class="text-pink-500 font-bold flex-shrink-0">2.</span>
              <span>Tap <strong>⋯</strong> (three dots) on the post.</span>
            </li>
            <li class="flex gap-2">
              <span class="text-pink-500 font-bold flex-shrink-0">3.</span>
              <span>Select <strong>"Copy Link"</strong>.</span>
            </li>
            <li class="flex gap-2">
              <span class="text-pink-500 font-bold flex-shrink-0">4.</span>
              <span>Paste it into the box above and hit <strong>Download</strong>.</span>
            </li>
          </ol>
        </div>

        
        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Accepted URL Formats</h3>
          <div class="text-xs font-mono text-gray-500 bg-gray-50 rounded-lg p-3 space-y-1.5 leading-relaxed break-all">
            <div>instagram.com/<strong class="text-gray-700">reel</strong>/XYZ/</div>
            <div>instagram.com/<strong class="text-gray-700">p</strong>/XYZ/</div>
            <div>instagram.com/<strong class="text-gray-700">tv</strong>/XYZ/</div>
          </div>
        </div>

        
        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">About Stories</h3>
          <p class="text-xs text-gray-600 leading-relaxed">
            Instagram Stories are <strong>private by design</strong> — they require a logged-in session to view. Without your account cookies, our server gets a login wall and cannot retrieve story content. This is an Instagram limitation, not a tool bug.
          </p>
          <p class="text-xs text-gray-500 mt-2">
            💡 Use the Instagram app's native "Save to camera roll" option before a story expires.
          </p>
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
<script>
/*
  Instagram Reels & Stories Downloader — Alpine.js component  (prefix: irs-)

  Flow:
    1. Client validates URL format (regex — instant, no network).
    2. POST /tools/instagram-reels-and-stories-downloader/process
         → ToolEngine routes to InstagramDownloaderEngine::download()
         → 3 strategies tried in order:
              a) Embed page  — /reel/{code}/embed/captioned/ → extracts <video src>
              b) Main page   — og:video / og:image / embedded JSON mp4 URLs
              c) oEmbed API  — thumbnail only, last resort
         → Returns JSON { success, type, thumbnail, video_url, items[] }
    3. Video result: <video> element plays inline using CDN URL.
         x-on:error.self catches failures and shows thumbnail fallback.
    4. Download via /tools/instagram-downloader/proxy?url=… (shared proxy, same-origin).
         Proxy streams the CDN file with Content-Disposition: attachment.
*/
function irsDownloader() {
  return {
    url:        '',
    urlError:   '',
    fetchError: '',
    loading:    false,
    result:     null,
    videoError: false,
    copied:     -1,

    // ── Client-side URL validation ────────────────────────
    validate() {
      const u = this.url.trim();
      if (!u) {
        this.urlError = 'Please paste an Instagram URL first.';
        return false;
      }
      if (!/instagram\.com\/(p|reel|tv)\/[A-Za-z0-9_-]+/i.test(u)) {
        this.urlError = 'That doesn\'t look like a valid Instagram URL. '
          + 'Expected format: https://www.instagram.com/reel/ABC123/';
        return false;
      }
      this.urlError = '';
      return true;
    },

    // ── Submit to backend ─────────────────────────────────
    async submit() {
      if (!this.validate()) return;

      this.loading    = true;
      this.fetchError = '';
      this.result     = null;
      this.videoError = false;

      try {
        const csrf = document.querySelector('meta[name="csrf-token"]');
        const res  = await fetch('/tools/instagram-reels-and-stories-downloader/process', {
          method:  'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept':       'application/json',
            'X-CSRF-TOKEN': csrf ? csrf.getAttribute('content') : '',
          },
          body: JSON.stringify({ url: this.url.trim() }),
        });

        const data = await res.json();

        if (data.success) {
          this.result = data;
        } else {
          this.fetchError = data.error || 'Could not extract media. Please try again.';
        }
      } catch {
        this.fetchError = 'Network error. Check your connection and try again.';
      } finally {
        this.loading = false;
      }
    },

    // ── Build proxy URL (routes through our server so `download` attr works) ──
    proxyUrl(mediaUrl) {
      return '/tools/instagram-downloader/proxy?url=' + encodeURIComponent(mediaUrl);
    },

    // ── Open URL in new tab ───────────────────────────────
    openTab(u) {
      if (u) window.open(u, '_blank', 'noopener,noreferrer');
    },

    // ── Copy CDN URL to clipboard ─────────────────────────
    async copyUrl(u, idx) {
      try {
        await navigator.clipboard.writeText(u);
      } catch {
        const ta = Object.assign(document.createElement('textarea'), { value: u });
        Object.assign(ta.style, { position: 'fixed', opacity: '0' });
        document.body.appendChild(ta);
        ta.focus(); ta.select();
        try { document.execCommand('copy'); } catch {}
        document.body.removeChild(ta);
      }
      this.copied = idx;
      setTimeout(() => { if (this.copied === idx) this.copied = -1; }, 2000);
    },

    // ── Reset component to initial state ─────────────────
    reset() {
      this.url        = '';
      this.urlError   = '';
      this.fetchError = '';
      this.loading    = false;
      this.result     = null;
      this.videoError = false;
      this.copied     = -1;
    },
  };
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\instagram-reels-and-stories-downloader.blade.php ENDPATH**/ ?>