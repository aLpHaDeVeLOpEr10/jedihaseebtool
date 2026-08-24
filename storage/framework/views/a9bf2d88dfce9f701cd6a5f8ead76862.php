

<?php $__env->startSection('title', $tool->seo_title ?: 'TikTok Videos Downloader - Free Online Tool'); ?>
<?php $__env->startSection('description', $tool->seo_description ?: 'Download TikTok videos without watermark in HD. Paste any TikTok link and save MP4 instantly — free, fast, no app required.'); ?>

<?php $__env->startSection('renders_own_faqs', '1'); ?>
<?php $__env->startSection('content'); ?>
<style>
/* ══════════════════════════════════════════════════════════════
   TikTok Videos Downloader  —  prefix: tk-
   Theme: TikTok black #010101 · pink #fe2c55 · teal #25f4ee
   Backend: POST /tools/tiktok-videos-downloader/process
   Proxy:   GET  /tools/tiktok-videos-downloader/proxy
══════════════════════════════════════════════════════════════ */

.tk-gradient      { background: linear-gradient(135deg, #fe2c55 0%, #25f4ee 100%); }
.tk-gradient-text { background: linear-gradient(135deg, #fe2c55, #25f4ee);
                    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
                    background-clip: text; }
.tk-pink  { color: #fe2c55; }
.tk-teal  { color: #25f4ee; }

/* ── URL input ───────────────────────────────────────────── */
.tk-input-wrap { position: relative; }
.tk-input {
  width: 100%; padding: .9rem 1.1rem .9rem 3.1rem;
  border: 2px solid #e5e7eb; border-radius: .875rem;
  font-size: .95rem; outline: none; background: #fff; color: #111827;
  transition: border-color .15s, box-shadow .15s;
}
.tk-input:focus  { border-color: #fe2c55; box-shadow: 0 0 0 3px rgba(254,44,85,.1); }
.tk-input.tk-err { border-color: #ef4444; }
.tk-input-icon {
  position: absolute; left: .95rem; top: 50%; transform: translateY(-50%);
  font-size: 1.2rem; pointer-events: none; user-select: none; line-height: 1;
}

/* ── Primary button ──────────────────────────────────────── */
.tk-btn {
  width: 100%; padding: .9rem 1.5rem; border-radius: .875rem;
  font-size: 1rem; font-weight: 800; cursor: pointer; border: none; color: #fff;
  display: flex; align-items: center; justify-content: center; gap: .55rem;
  transition: all .16s;
  background: linear-gradient(135deg, #fe2c55 0%, #c41e3a 100%);
  box-shadow: 0 4px 16px rgba(254,44,85,.3);
}
.tk-btn:hover:not(:disabled) { box-shadow: 0 7px 22px rgba(254,44,85,.45); transform: translateY(-1px); }
.tk-btn:disabled              { opacity: .4; cursor: not-allowed; transform: none; box-shadow: none; }

/* ── Spinner ─────────────────────────────────────────────── */
@keyframes tkSpin { to { transform: rotate(360deg); } }
.tk-spin {
  display: inline-block; width: 1em; height: 1em; border-radius: 50%;
  border: 2px solid rgba(255,255,255,.4); border-top-color: #fff;
  animation: tkSpin .65s linear infinite; flex-shrink: 0;
}

/* ── Alert boxes ─────────────────────────────────────────── */
.tk-error {
  display: flex; align-items: flex-start; gap: .5rem;
  padding: .75rem .9rem; border-radius: .75rem;
  background: #fef2f2; border: 1.5px solid #fecaca;
  font-size: .82rem; color: #991b1b; font-weight: 500; line-height: 1.5;
}
.tk-note {
  display: flex; align-items: flex-start; gap: .5rem;
  padding: .6rem .85rem; border-radius: .75rem;
  background: #fff7ed; border: 1px solid #fed7aa;
  font-size: .76rem; color: #9a3412; font-weight: 500; line-height: 1.45;
}

/* ── Video info card ─────────────────────────────────────── */
.tk-info-card {
  display: flex; gap: 1rem; align-items: flex-start;
  padding: 1rem; border-radius: .875rem;
  background: linear-gradient(135deg, #fff0f3 0%, #f0fdfd 100%);
  border: 1.5px solid #fecdd3;
}

/* Portrait thumbnail — TikTok is 9:16 */
.tk-thumb-wrap {
  flex-shrink: 0; width: 72px; border-radius: .625rem;
  overflow: hidden; background: #010101; position: relative;
  aspect-ratio: 9/16;
}
.tk-thumb { width: 100%; height: 100%; object-fit: cover; display: block; }
.tk-duration-badge {
  position: absolute; bottom: 4px; left: 50%; transform: translateX(-50%);
  background: rgba(0,0,0,.8); color: #fff;
  font-size: .6rem; font-weight: 700; padding: 1px 5px;
  border-radius: 3px; letter-spacing: .02em; white-space: nowrap;
}

.tk-meta        { flex: 1; min-width: 0; }
.tk-video-title {
  font-size: .85rem; font-weight: 700; color: #111827;
  line-height: 1.35; margin-bottom: .4rem;
  display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
}
.tk-author { font-size: .73rem; color: #6b7280; display: flex; align-items: center; gap: .3rem; margin-bottom: .2rem; }
.tk-stats  { font-size: .69rem; color: #9ca3af; display: flex; align-items: center; gap: .5rem; flex-wrap: wrap; }

/* ── Download option cards ───────────────────────────────── */
.tk-fmt-grid { display: grid; gap: .7rem; }

.tk-fmt-card {
  display: flex; align-items: center; gap: .85rem;
  padding: .85rem 1rem; border-radius: .875rem;
  border: 1.5px solid #e5e7eb; background: #fff;
  transition: border-color .15s, box-shadow .15s;
}
.tk-fmt-card:hover { border-color: #fca5a5; box-shadow: 0 2px 8px rgba(254,44,85,.08); }
.tk-fmt-card.tk-nowm { border-color: #a7f3d0; background: #f0fdf4; }
.tk-fmt-card.tk-nowm:hover { border-color: #6ee7b7; box-shadow: 0 2px 8px rgba(16,185,129,.1); }

.tk-fmt-icon { font-size: 1.5rem; flex-shrink: 0; line-height: 1; }
.tk-fmt-info { flex: 1; min-width: 0; }
.tk-fmt-label { font-size: .83rem; font-weight: 700; color: #111827; }
.tk-fmt-meta  { font-size: .7rem; color: #9ca3af; margin-top: .15rem; }

.tk-nowm-badge {
  display: inline-flex; align-items: center; gap: .2rem;
  padding: .15rem .5rem; border-radius: 999px;
  font-size: .6rem; font-weight: 800; text-transform: uppercase; letter-spacing: .04em;
  background: #dcfce7; color: #15803d; margin-left: .4rem;
}

.tk-dl-btn {
  display: inline-flex; align-items: center; justify-content: center; gap: .35rem;
  padding: .55rem 1rem; border-radius: .75rem;
  font-size: .8rem; font-weight: 700; cursor: pointer;
  text-decoration: none; border: none; color: #fff; white-space: nowrap;
  background: linear-gradient(135deg, #fe2c55, #c41e3a);
  box-shadow: 0 2px 8px rgba(254,44,85,.25);
  transition: all .15s; flex-shrink: 0;
}
.tk-dl-btn:hover  { box-shadow: 0 4px 14px rgba(254,44,85,.4); transform: translateY(-1px); color: #fff; }
.tk-dl-btn:active { transform: scale(.97); }
.tk-dl-btn.tk-loading { opacity: .7; pointer-events: none; }

/* ── Divider ─────────────────────────────────────────────── */
.tk-divider {
  display: flex; align-items: center; gap: .6rem;
  font-size: .6rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: #6b7280;
}
.tk-divider::before,.tk-divider::after { content:''; flex:1; height:1px; background:#e5e7eb; }

/* ── Reset link ──────────────────────────────────────────── */
.tk-reset {
  font-size: .72rem; color: #9ca3af; text-align: center;
  cursor: pointer; text-decoration: underline; text-underline-offset: 2px;
}
.tk-reset:hover { color: #fe2c55; }

/* ── Feature cards ───────────────────────────────────────── */
.tk-feature {
  display: flex; align-items: flex-start; gap: .65rem;
  padding: .75rem .85rem; border-radius: .875rem;
  background: #fafafa; border: 1.5px solid #f3f4f6;
}
.tk-feature-icon { font-size: 1.4rem; flex-shrink: 0; line-height: 1.2; }

/* ── Sidebar items ───────────────────────────────────────── */
.tk-item { display: flex; align-items: center; gap: .5rem; font-size: .82rem; padding: .2rem 0; }
.tk-yes  { color: #16a34a; font-weight: 700; flex-shrink: 0; }
.tk-no   { color: #dc2626; font-weight: 700; flex-shrink: 0; }

/* ── TikTok logo badge ───────────────────────────────────── */
.tk-logo-badge {
  width: 52px; height: 52px; border-radius: .875rem;
  background: #010101; display: flex; align-items: center;
  justify-content: center; font-size: 1.75rem; flex-shrink: 0;
  box-shadow: 3px 3px 0 #fe2c55, -3px -3px 0 #25f4ee;
}

@media(max-width:480px) {
  .tk-info-card  { flex-direction: column; }
  .tk-thumb-wrap { width: 100%; max-width: 160px; margin: 0 auto; }
}
</style>

<div class="min-h-screen bg-gray-50">

  
  <div class="bg-white border-b border-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
      <div class="flex items-center gap-4 mb-4">
        <div class="tk-logo-badge">🎵</div>
        <div>
          <h1 class="text-3xl font-bold text-gray-900">TikTok Videos Downloader</h1>
          <p class="text-gray-500 mt-1">
            <?php echo e($tool->short_description ?: 'Download TikTok videos without watermark — free, fast, no app required.'); ?>

          </p>
        </div>
      </div>
      <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [
          ['label' => 'Home',                'url' => url('/')],
          ['label' => $tool->category->name, 'url' => route('categories.show', $tool->category)],
          ['label' => 'TikTok Videos Downloader']
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
          ['label' => 'TikTok Videos Downloader']
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

      
      <div class="lg:col-span-2 space-y-5" x-data="tkDownloader()">

        
        <div class="card p-6 space-y-5" x-show="!info">

          <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Paste TikTok Link</h2>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full text-white tk-gradient">FREE</span>
          </div>

          <div class="tk-note">
            <span class="flex-shrink-0 mt-0.5">ℹ️</span>
            <span>Works with <strong>public TikTok videos</strong> (short links and full URLs supported). Private or login-required videos cannot be downloaded.</span>
          </div>

          
          <div>
            <label for="tk-url" class="block text-sm font-medium text-gray-700 mb-1.5">
              TikTok Video URL
            </label>
            <div class="tk-input-wrap">
              <span class="tk-input-icon">📎</span>
              <input
                id="tk-url"
                type="url"
                x-model="url"
                :class="['tk-input', urlError ? 'tk-err' : '']"
                placeholder="https://www.tiktok.com/@user/video/… or vm.tiktok.com/…"
                x-on:input="urlError = ''; fetchError = ''"
                x-on:keydown.enter="submit()"
                autocomplete="off"
                spellcheck="false"
              >
            </div>
            <div x-show="urlError" x-transition class="tk-error mt-2">
              <span class="flex-shrink-0">⚠</span><span x-text="urlError"></span>
            </div>
          </div>

          
          <div x-show="fetchError" x-transition class="tk-error">
            <span class="flex-shrink-0 mt-0.5">⚠</span>
            <div>
              <strong class="block mb-0.5">Could not fetch video</strong>
              <span x-text="fetchError"></span>
            </div>
          </div>

          
          <button x-on:click="submit()" :disabled="loading" class="tk-btn">
            <span x-show="loading" class="tk-spin"></span>
            <span x-show="loading">Fetching Video Info…</span>
            <span x-show="!loading">🔍 Get Download Options</span>
          </button>

          
          <div class="text-xs text-gray-400 flex flex-wrap gap-x-4 gap-y-1 justify-center">
            <span>✓ tiktok.com/@user/video/…</span>
            <span>✓ vm.tiktok.com/…</span>
            <span>✓ vt.tiktok.com/…</span>
          </div>

        </div>

        
        <div class="space-y-5" x-show="info" x-transition>

          
          <div class="card p-5">
            <div class="tk-info-card">

              
              <div class="tk-thumb-wrap">
                <template x-if="info && info.thumbnail">
                  <img :src="info.thumbnail" :alt="info.title" class="tk-thumb" loading="lazy">
                </template>
                <template x-if="!info || !info.thumbnail">
                  <div class="w-full h-full flex items-center justify-center text-3xl bg-gray-900">🎵</div>
                </template>
                <span x-show="info && info.duration" class="tk-duration-badge" x-text="info ? info.duration : ''"></span>
              </div>

              
              <div class="tk-meta">
                <div class="tk-video-title" x-text="info ? info.title : ''"></div>
                <div class="tk-author" x-show="info && info.author">
                  <span>👤</span><span x-text="info ? info.author : ''"></span>
                </div>
                <div class="tk-stats" x-show="info && info.likes">
                  <span x-show="info && info.likes">
                    ❤️ <span x-text="info ? formatCount(info.likes) : ''"></span> likes
                  </span>
                </div>
              </div>
            </div>
          </div>

          
          <div class="card p-6 space-y-4">
            <p class="tk-divider">Download Options</p>

            <div class="tk-fmt-grid">
              <template x-for="(fmt, idx) in (info ? info.formats : [])" :key="idx">
                <div class="tk-fmt-card" :class="fmt.no_wm ? 'tk-nowm' : ''">
                  <span class="tk-fmt-icon" x-text="fmt.icon"></span>
                  <div class="tk-fmt-info">
                    <div class="tk-fmt-label">
                      <span x-text="fmt.label"></span>
                      <span x-show="fmt.no_wm" class="tk-nowm-badge">✓ No Watermark</span>
                    </div>
                    <div class="tk-fmt-meta">
                      <span x-text="fmt.ext.toUpperCase()"></span>
                      <template x-if="fmt.filesize">
                        <span> · <span x-text="formatFilesize(fmt.filesize)"></span></span>
                      </template>
                    </div>
                  </div>
                  <a
                    :href="downloadUrl(fmt)"
                    download
                    class="tk-dl-btn"
                    x-on:click="onDownloadClick($event)"
                    :title="'Download ' + fmt.label"
                  >⬇ Save</a>
                </div>
              </template>
            </div>

            <p class="text-xs text-gray-400 text-center mt-1">
              ℹ️ Download starts processing on our server. Please wait — do not close this tab.
            </p>
          </div>

          
          <p class="tk-reset" x-on:click="reset()">↺ Download another video</p>

        </div>

        
        <div class="card p-6">
          <p class="tk-divider mb-4">How It Works</p>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="tk-feature">
              <span class="tk-feature-icon">📋</span>
              <div>
                <p class="text-sm font-semibold text-gray-800">1. Copy Link</p>
                <p class="text-gray-500 text-xs mt-0.5">Tap Share → Copy link on any public TikTok video.</p>
              </div>
            </div>
            <div class="tk-feature">
              <span class="tk-feature-icon">🔍</span>
              <div>
                <p class="text-sm font-semibold text-gray-800">2. Paste &amp; Fetch</p>
                <p class="text-gray-500 text-xs mt-0.5">Paste the URL above and hit Get Download Options.</p>
              </div>
            </div>
            <div class="tk-feature">
              <span class="tk-feature-icon">💾</span>
              <div>
                <p class="text-sm font-semibold text-gray-800">3. Save to Device</p>
                <p class="text-gray-500 text-xs mt-0.5">Click Save. No watermark version is labeled.</p>
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
            <div class="tk-item"><span class="tk-yes">✓</span><span class="text-gray-700">TikTok Short Videos</span></div>
            <div class="tk-item"><span class="tk-yes">✓</span><span class="text-gray-700">No Watermark (when available)</span></div>
            <div class="tk-item"><span class="tk-yes">✓</span><span class="text-gray-700">Short links (vm.tiktok.com)</span></div>
            <div class="tk-item"><span class="tk-yes">✓</span><span class="text-gray-700">Full resolution MP4</span></div>
            <div class="tk-item"><span class="tk-no">✗</span><span class="text-gray-500">Private / login-only videos</span></div>
            <div class="tk-item"><span class="tk-no">✗</span><span class="text-gray-500">Live streams (ongoing)</span></div>
          </div>
        </div>

        
        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Accepted URL Formats</h3>
          <div class="text-xs font-mono text-gray-500 bg-gray-50 rounded-lg p-3 space-y-1.5 leading-relaxed break-all">
            <div>tiktok.com/<strong class="text-gray-700">@user/video/</strong>ID</div>
            <div><strong class="text-gray-700">vm.tiktok.com/</strong>CODE/</div>
            <div><strong class="text-gray-700">vt.tiktok.com/</strong>CODE/</div>
          </div>
        </div>

        
        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">About Watermarks</h3>
          <p class="text-xs text-gray-600 leading-relaxed">
            TikTok adds a watermark to videos by default. When a <strong class="text-green-700">No Watermark</strong> version is available, it will appear as a separate download option.
          </p>
          <p class="text-xs text-gray-500 mt-2">
            Availability depends on the video and TikTok's current API. Not all videos have a clean version available.
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
  TikTok Videos Downloader — Alpine.js component (prefix: tk-)

  Flow:
    Step 1 — URL input:
      POST /tools/tiktok-videos-downloader/process
        → ToolEngine → TiktokDownloaderEngine::getInfo()
        → yt-dlp --dump-json extracts title, thumbnail, formats
        → Returns { success, title, thumbnail, author, duration, formats[] }

    Step 2 — Download:
      GET /tools/tiktok-videos-downloader/proxy
          ?url={tiktok_url}&format={format_id}&title={title}
        → ToolController::tiktokProxy()
        → yt-dlp downloads to temp file, served via response()->download()
*/
function tkDownloader() {
  return {
    url:        '',
    urlError:   '',
    fetchError: '',
    loading:    false,
    info:       null,

    // ── Client-side URL validation ────────────────────────
    validate() {
      const u = this.url.trim();
      if (!u) {
        this.urlError = 'Please paste a TikTok URL first.';
        return false;
      }
      const ok = /^https?:\/\/(www\.|m\.|vm\.|vt\.)?tiktok\.com\//i.test(u);
      if (!ok) {
        this.urlError = 'That doesn\'t look like a valid TikTok URL. '
          + 'Expected: tiktok.com/@user/video/… or vm.tiktok.com/…';
        return false;
      }
      this.urlError = '';
      return true;
    },

    // ── Submit: fetch video info ──────────────────────────
    async submit() {
      if (!this.validate()) return;

      this.loading    = true;
      this.fetchError = '';
      this.info       = null;

      try {
        const csrf = document.querySelector('meta[name="csrf-token"]');
        const res  = await fetch('/tools/tiktok-videos-downloader/process', {
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
          this.info = data;
        } else {
          this.fetchError = data.error || 'Could not fetch video info. Please try again.';
        }
      } catch {
        this.fetchError = 'Network error. Check your connection and try again.';
      } finally {
        this.loading = false;
      }
    },

    // ── Build proxy download URL ──────────────────────────
    downloadUrl(fmt) {
      const params = new URLSearchParams({
        url:    this.url.trim(),
        format: fmt.format_id,
        title:  (this.info?.title || 'tiktok_video').substring(0, 100),
      });
      return '/tools/tiktok-videos-downloader/proxy?' + params.toString();
    },

    // ── Visual feedback on download click ─────────────────
    onDownloadClick(event) {
      const btn  = event.currentTarget;
      const orig = btn.innerHTML;
      btn.innerHTML = '⏳ Preparing…';
      btn.classList.add('tk-loading');
      setTimeout(() => {
        btn.innerHTML = orig;
        btn.classList.remove('tk-loading');
      }, 8000);
    },

    // ── Format helpers ────────────────────────────────────
    formatCount(n) {
      if (!n) return '';
      if (n >= 1e9) return (n / 1e9).toFixed(1) + 'B';
      if (n >= 1e6) return (n / 1e6).toFixed(1) + 'M';
      if (n >= 1e3) return (n / 1e3).toFixed(1) + 'K';
      return n.toLocaleString();
    },

    formatFilesize(bytes) {
      if (!bytes) return '';
      if (bytes >= 1073741824) return (bytes / 1073741824).toFixed(1) + ' GB';
      if (bytes >= 1048576)    return (bytes / 1048576).toFixed(1) + ' MB';
      if (bytes >= 1024)       return (bytes / 1024).toFixed(0) + ' KB';
      return bytes + ' B';
    },

    // ── Reset to step 1 ───────────────────────────────────
    reset() {
      this.url        = '';
      this.urlError   = '';
      this.fetchError = '';
      this.loading    = false;
      this.info       = null;
    },
  };
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\tiktok-videos-downloader.blade.php ENDPATH**/ ?>