

<?php $__env->startSection('title', $tool->seo_title ?: 'Facebook Reels & Stories Downloader - Free Online Tool'); ?>
<?php $__env->startSection('description', $tool->seo_description ?: 'Download Facebook Reels, Videos and Stories for free. Paste any public Facebook URL and save the video instantly.'); ?>

<?php $__env->startSection('renders_own_faqs', '1'); ?>
<?php $__env->startSection('content'); ?>
<style>
/* ══════════════════════════════════════════════════════════════
   Facebook Reels & Stories Downloader  —  prefix: fb-
   Theme: Facebook blue  #1877f2  →  #0a5dc2
   Backend: POST /tools/facebook-reels-and-stories-downloader/process
   Proxy:   GET  /tools/facebook-reels-and-stories-downloader/proxy?url=...
   Note:    Stories / private posts require login — unsupported without auth.
══════════════════════════════════════════════════════════════ */

/* ── Gradient helpers ────────────────────────────────────── */
.fb-gradient      { background: linear-gradient(135deg, #1877f2 0%, #0a5dc2 100%); }
.fb-gradient-text { background: linear-gradient(135deg, #1877f2, #0a5dc2);
                    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
                    background-clip: text; }

/* ── URL input ───────────────────────────────────────────── */
.fb-input-wrap { position: relative; }
.fb-input {
  width: 100%; padding: .9rem 1.1rem .9rem 3.1rem;
  border: 2px solid #e5e7eb; border-radius: .875rem;
  font-size: .95rem; outline: none; background: #fff; color: #111827;
  transition: border-color .15s, box-shadow .15s;
}
.fb-input:focus {
  border-color: #1877f2;
  box-shadow: 0 0 0 3px rgba(24,119,242,.12);
}
.fb-input.fb-input-err { border-color: #ef4444; }
.fb-input-icon {
  position: absolute; left: .95rem; top: 50%; transform: translateY(-50%);
  font-size: 1.2rem; pointer-events: none; user-select: none; line-height: 1;
}

/* ── Primary button ──────────────────────────────────────── */
.fb-btn {
  width: 100%; padding: .9rem 1.5rem; border-radius: .875rem;
  font-size: 1rem; font-weight: 800; cursor: pointer; border: none; color: #fff;
  display: flex; align-items: center; justify-content: center; gap: .55rem;
  transition: all .16s;
  background: linear-gradient(135deg, #1877f2 0%, #0a5dc2 100%);
  box-shadow: 0 4px 16px rgba(24,119,242,.35);
}
.fb-btn:hover:not(:disabled) {
  box-shadow: 0 7px 22px rgba(24,119,242,.5);
  transform: translateY(-1px);
}
.fb-btn:disabled { opacity: .4; cursor: not-allowed; transform: none; box-shadow: none; }

/* ── Spinner ─────────────────────────────────────────────── */
@keyframes fbSpin { to { transform: rotate(360deg); } }
.fb-spin {
  display: inline-block; width: 1em; height: 1em; border-radius: 50%;
  border: 2px solid rgba(255,255,255,.4); border-top-color: #fff;
  animation: fbSpin .65s linear infinite; flex-shrink: 0;
}

/* ── Error box ───────────────────────────────────────────── */
.fb-error {
  display: flex; align-items: flex-start; gap: .5rem;
  padding: .7rem .9rem; border-radius: .75rem;
  background: #fef2f2; border: 1.5px solid #fecaca;
  font-size: .82rem; color: #991b1b; font-weight: 500; line-height: 1.5;
}

/* ── Info note ───────────────────────────────────────────── */
.fb-note {
  display: flex; align-items: flex-start; gap: .5rem;
  padding: .65rem .9rem; border-radius: .75rem;
  background: #eff6ff; border: 1px solid #bfdbfe;
  font-size: .76rem; color: #1e40af; font-weight: 500; line-height: 1.45;
}

/* ── Success banner ──────────────────────────────────────── */
.fb-success {
  display: flex; align-items: center; gap: .65rem;
  padding: .75rem 1rem; border-radius: .875rem;
  background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
  border: 1.5px solid #bfdbfe;
  font-size: .88rem; font-weight: 600; color: #1e40af;
}

/* ── Type badge ──────────────────────────────────────────── */
.fb-badge {
  display: inline-flex; align-items: center; gap: .3rem;
  padding: .22rem .7rem; border-radius: 999px;
  font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em;
}
.fb-badge-reel  { background: #dbeafe; color: #1e40af; }
.fb-badge-video { background: #e0e7ff; color: #3730a3; }
.fb-badge-image { background: #ede9fe; color: #6d28d9; }

/* ── Preview containers ──────────────────────────────────── */
.fb-preview-wrap {
  border-radius: .875rem; overflow: hidden;
  background: repeating-conic-gradient(#f3f4f6 0% 25%, #fff 0% 50%) 0 0 / 16px 16px;
  display: flex; align-items: center; justify-content: center;
  min-height: 180px; border: 1.5px solid #bfdbfe;
}
.fb-preview-img   { max-width: 100%; max-height: 420px; object-fit: contain; display: block; }
.fb-preview-video {
  width: 100%; max-height: 480px; border-radius: .875rem;
  border: 1.5px solid #bfdbfe; background: #000; display: block;
}

/* ── Video fallback overlay ──────────────────────────────── */
.fb-video-fallback {
  border-radius: .875rem; overflow: hidden;
  border: 1.5px solid #bfdbfe; background: #000;
  position: relative; cursor: pointer;
}
.fb-play-overlay {
  position: absolute; inset: 0;
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  background: rgba(0,0,0,.45); gap: .5rem;
}
.fb-play-circle {
  width: 68px; height: 68px; border-radius: 50%;
  background: rgba(255,255,255,.92);
  display: flex; align-items: center; justify-content: center;
  font-size: 1.9rem; line-height: 1; padding-left: 5px;
  box-shadow: 0 4px 20px rgba(0,0,0,.4);
}
.fb-play-label { color: #fff; font-size: .75rem; font-weight: 600; letter-spacing: .02em; }

/* ── Caption card ────────────────────────────────────────── */
.fb-caption {
  background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
  border: 1.5px solid #bfdbfe; border-radius: .875rem; padding: .9rem 1rem;
}
.fb-caption-label { font-size: .68rem; font-weight: 700; color: #1e40af;
                    text-transform: uppercase; letter-spacing: .07em; margin-bottom: .35rem; }
.fb-caption-text  { font-size: .82rem; color: #374151; line-height: 1.55; }

/* ── Download item card ──────────────────────────────────── */
.fb-dl-card {
  padding: .9rem 1rem; border-radius: .875rem;
  background: #fafafa; border: 1.5px solid #e5e7eb;
}
.fb-dl-label {
  font-size: .73rem; font-weight: 700; color: #6b7280;
  text-transform: uppercase; letter-spacing: .05em;
  margin-bottom: .65rem; display: flex; align-items: center; gap: .35rem;
}
.fb-actions { display: flex; gap: .55rem; flex-wrap: wrap; }

/* ── Action buttons ──────────────────────────────────────── */
.fb-dl-btn {
  flex: 1; min-width: 110px;
  display: flex; align-items: center; justify-content: center; gap: .4rem;
  padding: .68rem 1rem; border-radius: .75rem;
  font-size: .83rem; font-weight: 700; cursor: pointer; text-decoration: none;
  border: none; color: #fff; transition: all .15s;
  background: linear-gradient(135deg, #1877f2, #0a5dc2);
  box-shadow: 0 3px 10px rgba(24,119,242,.25);
}
.fb-dl-btn:hover { box-shadow: 0 5px 16px rgba(24,119,242,.4); transform: translateY(-1px); color: #fff; }

.fb-copy-btn {
  display: flex; align-items: center; justify-content: center; gap: .4rem;
  padding: .68rem 1rem; border-radius: .75rem;
  font-size: .83rem; font-weight: 700; cursor: pointer;
  border: 2px solid #bfdbfe; background: #fff; color: #1e40af;
  transition: all .15s; white-space: nowrap;
}
.fb-copy-btn:hover { background: #eff6ff; border-color: #93c5fd; }
.fb-copy-btn.fb-copied { background: #f0fdf4; border-color: #bbf7d0; color: #166534; }

.fb-open-btn {
  display: flex; align-items: center; justify-content: center; gap: .4rem;
  padding: .68rem 1rem; border-radius: .75rem;
  font-size: .83rem; font-weight: 700; cursor: pointer;
  border: 2px solid #e5e7eb; background: #fff; color: #6b7280;
  transition: all .15s; text-decoration: none; white-space: nowrap;
}
.fb-open-btn:hover { background: #f9fafb; border-color: #d1d5db; color: #374151; }

/* ── Section divider ─────────────────────────────────────── */
.fb-divider {
  display: flex; align-items: center; gap: .6rem;
  font-size: .6rem; font-weight: 800; text-transform: uppercase;
  letter-spacing: .1em; color: #6b7280;
}
.fb-divider::before,.fb-divider::after { content:''; flex:1; height:1px; background:#e5e7eb; }

/* ── Reset link ──────────────────────────────────────────── */
.fb-reset {
  font-size: .72rem; color: #9ca3af; text-align: center;
  cursor: pointer; text-decoration: underline; text-underline-offset: 2px;
}
.fb-reset:hover { color: #1877f2; }

/* ── Feature card ────────────────────────────────────────── */
.fb-feature {
  display: flex; align-items: flex-start; gap: .65rem;
  padding: .75rem .85rem; border-radius: .875rem;
  background: #fafafa; border: 1.5px solid #f3f4f6;
}
.fb-feature-icon { font-size: 1.4rem; flex-shrink: 0; line-height: 1.2; }

/* ── Supported list ──────────────────────────────────────── */
.fb-item { display: flex; align-items: center; gap: .5rem; font-size: .82rem; padding: .2rem 0; }
.fb-yes  { color: #16a34a; font-weight: 700; flex-shrink: 0; }
.fb-no   { color: #dc2626; font-weight: 700; flex-shrink: 0; }
.fb-part { color: #d97706; font-weight: 700; flex-shrink: 0; font-size: .75rem; }

@media (max-width: 640px) {
  .fb-actions { flex-direction: column; }
  .fb-dl-btn, .fb-copy-btn, .fb-open-btn { width: 100%; min-width: 0; }
}
</style>

<div class="min-h-screen bg-gray-50">

  
  <div class="bg-white border-b border-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
      <div class="flex items-center gap-4 mb-4">
        <div class="flex-shrink-0 p-0.5 rounded-2xl fb-gradient" style="padding:3px;">
          <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-2xl">
            🎬
          </div>
        </div>
        <div>
          <h1 class="text-3xl font-bold text-gray-900">
            Facebook Reels &amp; Stories Downloader
          </h1>
          <p class="text-gray-500 mt-1">
            <?php echo e($tool->short_description ?: 'Download Facebook Reels, Videos and Stories for free — fast, no login required for public content.'); ?>

          </p>
        </div>
      </div>
      <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [
          ['label' => 'Home',                'url' => url('/')],
          ['label' => $tool->category->name, 'url' => route('categories.show', $tool->category)],
          ['label' => 'Facebook Reels & Stories Downloader']
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
          ['label' => 'Facebook Reels & Stories Downloader']
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

      
      <div class="lg:col-span-2 space-y-5" x-data="fbDownloader()">

        
        <div class="card p-6 space-y-5" x-show="!result">

          <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Paste Facebook URL</h2>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full text-white fb-gradient">FREE</span>
          </div>

          
          <div class="fb-note">
            <span class="flex-shrink-0 mt-0.5">⚡</span>
            <span>Works with <strong>public</strong> Reels, Watch videos, and page videos. Private posts and Stories require login — they cannot be downloaded without authentication.</span>
          </div>

          
          <div>
            <label for="fb-url" class="block text-sm font-medium text-gray-700 mb-1.5">
              Facebook Reel / Video / Watch URL
            </label>
            <div class="fb-input-wrap">
              <span class="fb-input-icon">📎</span>
              <input
                id="fb-url"
                type="url"
                x-model="url"
                :class="['fb-input', urlError ? 'fb-input-err' : '']"
                placeholder="https://www.facebook.com/reel/1234567890"
                x-on:input="urlError = ''; fetchError = ''"
                x-on:keydown.enter="submit()"
                autocomplete="off"
                spellcheck="false"
              >
            </div>
            <div x-show="urlError" x-transition class="fb-error mt-2">
              <span class="flex-shrink-0">⚠</span><span x-text="urlError"></span>
            </div>
          </div>

          
          <div x-show="fetchError" x-transition class="fb-error">
            <span class="flex-shrink-0 mt-0.5">⚠</span>
            <div>
              <strong class="block mb-0.5">Could not download media</strong>
              <span x-text="fetchError"></span>
            </div>
          </div>

          
          <button x-on:click="submit()" :disabled="loading" class="fb-btn">
            <span x-show="loading" class="fb-spin"></span>
            <span x-show="loading">Fetching Video…</span>
            <span x-show="!loading">⬇ Download Facebook Video</span>
          </button>

          
          <div class="text-xs text-gray-400 flex flex-wrap gap-x-4 gap-y-1 justify-center">
            <span>✓ facebook.com/reel/…</span>
            <span>✓ facebook.com/watch/…</span>
            <span>✓ facebook.com/share/r/…</span>
            <span>✓ fb.watch/…</span>
          </div>

        </div>

        
        <div class="card p-6 space-y-5" x-show="result" x-transition>

          
          <div class="fb-success">
            <span>✅</span>
            <span>Video found — preview and save below!</span>
          </div>

          
          <template x-if="result && result.video_url && !videoError">
            <div>
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold text-gray-700">Preview</span>
                <span class="fb-badge fb-badge-reel">🎬 Video</span>
              </div>
              <video
                :src="result.video_url"
                :poster="result.thumbnail || ''"
                controls
                preload="metadata"
                playsinline
                class="fb-preview-video"
                x-on:error.self="videoError = true"
              >
                Your browser does not support the video tag.
              </video>
              <p class="text-xs text-gray-400 mt-1.5 text-center">
                Click <strong>Download</strong> below to save the MP4 file to your device.
              </p>
            </div>
          </template>

          
          <template x-if="result && result.video_url && videoError">
            <div>
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold text-gray-700">Thumbnail</span>
                <span class="fb-badge fb-badge-reel">🎬 Video</span>
              </div>
              <div class="fb-video-fallback" x-on:click="openTab(result.video_url)">
                <img :src="result.thumbnail || ''" class="fb-preview-img w-full" style="opacity:.65;">
                <div class="fb-play-overlay">
                  <div class="fb-play-circle">▶</div>
                  <span class="fb-play-label">Tap to open video in new tab</span>
                </div>
              </div>
            </div>
          </template>

          
          <template x-if="result && !result.video_url && result.thumbnail">
            <div>
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold text-gray-700">Preview</span>
                <span class="fb-badge fb-badge-image">🖼 Image</span>
              </div>
              <div class="fb-preview-wrap">
                <img :src="result.thumbnail" :alt="result.title || 'Facebook media'" class="fb-preview-img" loading="lazy">
              </div>
            </div>
          </template>

          
          <div x-show="result && result.title" class="fb-caption">
            <div class="fb-caption-label">Title / Caption</div>
            <div class="fb-caption-text" x-text="result ? result.title : ''"></div>
          </div>

          
          <div x-show="result && result.note" class="fb-note">
            <span class="flex-shrink-0">ℹ️</span>
            <span x-text="result ? result.note : ''"></span>
          </div>

          
          <template x-if="result && result.items && result.items.length">
            <div class="space-y-3">
              <p class="fb-divider">Download Options</p>
              <template x-for="(item, idx) in result.items" :key="idx">
                <div class="fb-dl-card">
                  <div class="fb-dl-label">
                    <span x-text="item.type === 'video' ? '🎬' : '🖼'"></span>
                    <span x-text="item.label || (item.type === 'video' ? 'Video' : 'Image')"></span>
                  </div>
                  <div class="fb-actions">
                    <a :href="proxyUrl(item.url)" download class="fb-dl-btn">⬇ Download</a>
                    <a :href="item.url" target="_blank" rel="noopener noreferrer" class="fb-open-btn">🔗 Open</a>
                    <button
                      x-on:click="copyUrl(item.url, idx)"
                      :class="['fb-copy-btn', copied === idx ? 'fb-copied' : '']"
                    >
                      <span x-text="copied === idx ? '✓ Copied!' : '📋 Copy URL'"></span>
                    </button>
                  </div>
                </div>
              </template>
            </div>
          </template>

          <p class="fb-reset" x-on:click="reset()">↺ Download another</p>
        </div>

        
        <div class="card p-6">
          <p class="fb-divider mb-4">How It Works</p>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="fb-feature">
              <span class="fb-feature-icon">🔗</span>
              <div>
                <p class="text-sm font-semibold text-gray-800">1. Copy Link</p>
                <p class="text-gray-500 text-xs mt-0.5">Open Facebook, tap ⋯ on any public Reel or video, and tap "Copy link".</p>
              </div>
            </div>
            <div class="fb-feature">
              <span class="fb-feature-icon">⚙️</span>
              <div>
                <p class="text-sm font-semibold text-gray-800">2. Paste &amp; Fetch</p>
                <p class="text-gray-500 text-xs mt-0.5">Paste the URL above. Our server extracts the direct video link from Facebook.</p>
              </div>
            </div>
            <div class="fb-feature">
              <span class="fb-feature-icon">💾</span>
              <div>
                <p class="text-sm font-semibold text-gray-800">3. Save Video</p>
                <p class="text-gray-500 text-xs mt-0.5">Preview then hit Download to save the MP4 video to your device.</p>
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
            <div class="fb-item"><span class="fb-yes">✓</span><span class="text-gray-700">Facebook Reels</span></div>
            <div class="fb-item"><span class="fb-yes">✓</span><span class="text-gray-700">Watch / page videos</span></div>
            <div class="fb-item"><span class="fb-yes">✓</span><span class="text-gray-700">Shared video links</span></div>
            <div class="fb-item"><span class="fb-yes">✓</span><span class="text-gray-700">Public accounts only</span></div>
            <div class="fb-item"><span class="fb-no">✗</span><span class="text-gray-500">Private posts</span></div>
            <div class="fb-item"><span class="fb-no">✗</span><span class="text-gray-500">Stories (login required)</span></div>
            <div class="fb-item"><span class="fb-no">✗</span><span class="text-gray-500">Friends-only posts</span></div>
          </div>
        </div>

        
        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">How to Copy a Link</h3>
          <ol class="space-y-2.5 text-xs text-gray-600">
            <li class="flex gap-2">
              <span class="text-blue-500 font-bold flex-shrink-0">1.</span>
              <span>Open Facebook and go to the Reel or video you want.</span>
            </li>
            <li class="flex gap-2">
              <span class="text-blue-500 font-bold flex-shrink-0">2.</span>
              <span>Tap <strong>⋯</strong> (three dots) on the post.</span>
            </li>
            <li class="flex gap-2">
              <span class="text-blue-500 font-bold flex-shrink-0">3.</span>
              <span>Select <strong>"Copy link"</strong>.</span>
            </li>
            <li class="flex gap-2">
              <span class="text-blue-500 font-bold flex-shrink-0">4.</span>
              <span>Paste it in the box above and hit <strong>Download</strong>.</span>
            </li>
          </ol>
        </div>

        
        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Accepted URL Formats</h3>
          <div class="text-xs font-mono text-gray-500 bg-gray-50 rounded-lg p-3 space-y-1.5 leading-relaxed break-all">
            <div>facebook.com/<strong class="text-gray-700">reel</strong>/123…</div>
            <div>facebook.com/<strong class="text-gray-700">watch</strong>/?v=123…</div>
            <div>facebook.com/<strong class="text-gray-700">share/r</strong>/…</div>
            <div>facebook.com/page/<strong class="text-gray-700">videos</strong>/…</div>
            <div><strong class="text-gray-700">fb.watch</strong>/…</div>
          </div>
        </div>

        
        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">About Stories</h3>
          <p class="text-xs text-gray-600 leading-relaxed">
            Facebook Stories are <strong>private by design</strong> — they require a logged-in session to view. Without your account cookies, our server cannot retrieve story content.
          </p>
          <p class="text-xs text-gray-500 mt-2">
            💡 Use Facebook's "Save video" option in the app to keep stories before they expire.
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
  Facebook Reels & Stories Downloader — Alpine.js component  (prefix: fb-)

  Flow:
    1. Client validates URL (regex — instant, no network).
    2. POST /tools/facebook-reels-and-stories-downloader/process
         → ToolEngine → FacebookDownloaderEngine::download()
         → yt-dlp extracts direct CDN video URL
         → Returns JSON { success, type, thumbnail, video_url, items[] }
    3. Video: <video> element plays inline. x-on:error.self catches failures.
    4. Download via /tools/facebook-reels-and-stories-downloader/proxy?url=…
         Proxy streams the CDN file with Content-Disposition: attachment.
*/
function fbDownloader() {
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
        this.urlError = 'Please paste a Facebook URL first.';
        return false;
      }
      const ok = /^https?:\/\/(www\.|m\.|web\.)?facebook\.com\/(reel|watch|share|[^\/\?]+\/videos)|^https?:\/\/fb\.watch\//i.test(u);
      if (!ok) {
        this.urlError = 'That doesn\'t look like a valid Facebook URL. '
          + 'Expected: facebook.com/reel/… or fb.watch/…';
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
        const res  = await fetch('/tools/facebook-reels-and-stories-downloader/process', {
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

    // ── Build proxy URL ───────────────────────────────────
    proxyUrl(mediaUrl) {
      return '/tools/facebook-reels-and-stories-downloader/proxy?url=' + encodeURIComponent(mediaUrl);
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

    // ── Reset to initial state ────────────────────────────
    reset() {
      this.url = ''; this.urlError = ''; this.fetchError = '';
      this.loading = false; this.result = null;
      this.videoError = false; this.copied = -1;
    },
  };
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\facebook-reels-and-stories-downloader.blade.php ENDPATH**/ ?>