@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')
<style>
/* ══════════════════════════════════════════════════════════════
   Instagram Downloader  —  prefix: ig-
   Theme: Instagram gradient (pink #e1306c / purple #833ab4)
   Backend: POST /tools/instagram-downloader/process
   Proxy:   GET  /tools/instagram-downloader/proxy?url=...
══════════════════════════════════════════════════════════════ */

/* ── URL input ───────────────────────────────────────────── */
.ig-input-wrap { position: relative; }
.ig-input {
  width: 100%;
  padding: .85rem 1.1rem .85rem 3rem;
  border: 2px solid #e5e7eb;
  border-radius: .875rem;
  font-size: .95rem;
  outline: none;
  transition: border-color .15s, box-shadow .15s;
  background: #fff;
  color: #111827;
}
.ig-input:focus {
  border-color: #c13584;
  box-shadow: 0 0 0 3px rgba(193,53,132,.12);
}
.ig-input.ig-input-err { border-color: #ef4444; }
.ig-input-icon {
  position: absolute; left: .9rem; top: 50%; transform: translateY(-50%);
  font-size: 1.15rem; pointer-events: none; user-select: none; line-height: 1;
}

/* ── Primary button ──────────────────────────────────────── */
.ig-btn {
  width: 100%; padding: .9rem 1.5rem; border-radius: .875rem;
  font-size: 1rem; font-weight: 800; cursor: pointer;
  display: flex; align-items: center; justify-content: center; gap: .5rem;
  transition: all .16s; border: none; color: #fff;
  background: linear-gradient(135deg, #833ab4 0%, #c13584 50%, #e1306c 100%);
  box-shadow: 0 4px 14px rgba(193,53,132,.35);
}
.ig-btn:hover:not(:disabled) {
  box-shadow: 0 6px 20px rgba(193,53,132,.5);
  transform: translateY(-1px);
}
.ig-btn:disabled { opacity: .4; cursor: not-allowed; transform: none; box-shadow: none; }

/* ── Spinner ─────────────────────────────────────────────── */
@keyframes igSpin { to { transform: rotate(360deg); } }
.ig-spin {
  display: inline-block; width: 1em; height: 1em; border-radius: 50%;
  border: 2px solid rgba(255,255,255,.4); border-top-color: #fff;
  animation: igSpin .65s linear infinite; flex-shrink: 0;
}

/* ── Error box ───────────────────────────────────────────── */
.ig-error {
  display: flex; align-items: flex-start; gap: .5rem;
  padding: .7rem .9rem; border-radius: .75rem;
  background: #fef2f2; border: 1.5px solid #fecaca;
  font-size: .82rem; color: #991b1b; font-weight: 500;
  line-height: 1.5;
}

/* ── Note ────────────────────────────────────────────────── */
.ig-note {
  display: flex; align-items: flex-start; gap: .5rem;
  padding: .6rem .85rem; border-radius: .75rem;
  background: #fdf4ff; border: 1px solid #e9d5ff;
  font-size: .76rem; color: #7e22ce; font-weight: 500; line-height: 1.45;
}

/* ── Success banner ──────────────────────────────────────── */
.ig-success {
  display: flex; align-items: center; gap: .65rem;
  padding: .75rem 1rem; border-radius: .875rem;
  background: #fdf4ff; border: 1.5px solid #e9d5ff;
  font-size: .88rem; font-weight: 600; color: #7e22ce;
}

/* ── Media type badge ────────────────────────────────────── */
.ig-badge {
  display: inline-flex; align-items: center; gap: .3rem;
  padding: .22rem .65rem; border-radius: 999px;
  font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em;
}
.ig-badge-image { background: #ede9fe; color: #6d28d9; }
.ig-badge-video { background: #fce7f3; color: #9d174d; }

/* ── Preview containers ──────────────────────────────────── */
.ig-preview-wrap {
  border-radius: .875rem; overflow: hidden;
  background: repeating-conic-gradient(#f3f4f6 0% 25%, #fff 0% 50%) 0 0 / 16px 16px;
  display: flex; align-items: center; justify-content: center;
  min-height: 180px;
  border: 1.5px solid #e9d5ff;
}
.ig-preview-img {
  max-width: 100%; max-height: 420px;
  object-fit: contain; display: block;
}
.ig-preview-video {
  width: 100%; max-height: 480px;
  border-radius: .875rem;
  border: 1.5px solid #e9d5ff;
  background: #000;
  display: block;
}

/* ── Video error fallback ────────────────────────────────── */
.ig-video-fallback {
  border-radius: .875rem; overflow: hidden;
  border: 1.5px solid #e9d5ff; background: #000;
  position: relative; cursor: pointer;
}
.ig-play-overlay {
  position: absolute; inset: 0;
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  background: rgba(0,0,0,.4); gap: .5rem;
}
.ig-play-btn {
  width: 64px; height: 64px; border-radius: 50%;
  background: rgba(255,255,255,.9); display: flex; align-items: center; justify-content: center;
  font-size: 1.75rem; line-height: 1; padding-left: 4px;
  box-shadow: 0 4px 20px rgba(0,0,0,.4);
}
.ig-play-label { color: #fff; font-size: .75rem; font-weight: 600; }

/* ── Caption ─────────────────────────────────────────────── */
.ig-caption {
  background: #fdf4ff; border: 1.5px solid #e9d5ff; border-radius: .875rem;
  padding: .85rem 1rem;
}
.ig-caption-label { font-size: .68rem; font-weight: 700; color: #7e22ce; text-transform: uppercase; letter-spacing: .06em; margin-bottom: .35rem; }
.ig-caption-text  { font-size: .82rem; color: #374151; line-height: 1.5; }

/* ── Download item row ───────────────────────────────────── */
.ig-dl-item {
  padding: .85rem 1rem; border-radius: .875rem;
  background: #fafafa; border: 1.5px solid #e5e7eb;
}
.ig-dl-item-label {
  font-size: .73rem; font-weight: 700; color: #6b7280;
  text-transform: uppercase; letter-spacing: .05em;
  margin-bottom: .65rem; display: flex; align-items: center; gap: .35rem;
}
.ig-action-row { display: flex; gap: .55rem; flex-wrap: wrap; }

/* ── Action buttons ──────────────────────────────────────── */
.ig-dl-btn {
  flex: 1; min-width: 110px;
  display: flex; align-items: center; justify-content: center; gap: .4rem;
  padding: .68rem 1rem; border-radius: .75rem;
  font-size: .83rem; font-weight: 700; cursor: pointer; text-decoration: none;
  border: none; transition: all .15s; color: #fff;
  background: linear-gradient(135deg, #833ab4, #e1306c);
  box-shadow: 0 3px 10px rgba(193,53,132,.25);
}
.ig-dl-btn:hover { box-shadow: 0 5px 16px rgba(193,53,132,.4); transform: translateY(-1px); color: #fff; }

.ig-copy-btn {
  display: flex; align-items: center; justify-content: center; gap: .4rem;
  padding: .68rem 1rem; border-radius: .75rem;
  font-size: .83rem; font-weight: 700; cursor: pointer;
  border: 2px solid #e9d5ff; background: #fff; color: #7e22ce;
  transition: all .15s; white-space: nowrap;
}
.ig-copy-btn:hover { background: #fdf4ff; border-color: #d8b4fe; }
.ig-copy-btn.ig-copied { background: #f0fdf4; border-color: #bbf7d0; color: #166534; }

.ig-open-btn {
  display: flex; align-items: center; justify-content: center; gap: .4rem;
  padding: .68rem 1rem; border-radius: .75rem;
  font-size: .83rem; font-weight: 700; cursor: pointer;
  border: 2px solid #e5e7eb; background: #fff; color: #6b7280;
  transition: all .15s; text-decoration: none; white-space: nowrap;
}
.ig-open-btn:hover { background: #f9fafb; border-color: #d1d5db; color: #374151; }

/* ── Server note banner ──────────────────────────────────── */
.ig-server-note {
  display: flex; align-items: flex-start; gap: .45rem;
  padding: .55rem .8rem; border-radius: .75rem;
  background: #fffbeb; border: 1px solid #fde68a;
  font-size: .73rem; color: #92400e; line-height: 1.4;
}

/* ── Reset link ──────────────────────────────────────────── */
.ig-reset {
  font-size: .72rem; color: #9ca3af; text-align: center; cursor: pointer;
  text-decoration: underline; text-underline-offset: 2px;
}
.ig-reset:hover { color: #e1306c; }

/* ── Section divider ─────────────────────────────────────── */
.ig-div {
  display: flex; align-items: center; gap: .6rem;
  font-size: .6rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: #6b7280;
}
.ig-div::before,.ig-div::after { content:''; flex:1; height:1px; background:#e5e7eb; }

/* ── Sidebar support list ────────────────────────────────── */
.ig-support-row { display: flex; align-items: center; gap: .5rem; font-size: .82rem; }
.ig-yes { color: #16a34a; font-weight: 700; flex-shrink: 0; }
.ig-no  { color: #dc2626; font-weight: 700; flex-shrink: 0; }

@media (max-width: 640px) {
  .ig-action-row { flex-direction: column; }
  .ig-dl-btn, .ig-copy-btn, .ig-open-btn { width: 100%; min-width: 0; }
}
</style>

<div class="min-h-screen bg-gray-50">

  {{-- ── Hero ── --}}
  <div class="bg-white border-b border-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
      <div class="flex items-center gap-4 mb-4">
        <div class="w-14 h-14 rounded-xl flex items-center justify-center text-3xl flex-shrink-0"
             style="background: linear-gradient(135deg,#833ab4,#c13584,#e1306c);">
          📥
        </div>
        <div>
          <h1 class="text-3xl font-bold text-gray-900">{{ $tool->name }}</h1>
          <p class="text-gray-500 mt-1">{{ $tool->short_description }}</p>
        </div>
      </div>
      <x-breadcrumb :items="[
          ['label' => 'Home',                 'url' => url('/')],
          ['label' => $tool->category->name,  'url' => route('categories.show', $tool->category)],
          ['label' => $tool->name]
      ]"/>
    </div>
  </div>

  <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
    <div class="grid gap-8 lg:grid-cols-3">

      {{-- ── Main tool area ── --}}
      <div class="lg:col-span-2 space-y-5" x-data="igDownloader()">

        {{-- ══ Input card ══ --}}
        <div class="card p-6 space-y-5" x-show="!result">

          <h2 class="text-lg font-semibold text-gray-900">Paste Instagram URL</h2>

          <div class="ig-note">
            <span class="flex-shrink-0 mt-0.5">📌</span>
            <span>Only <strong>public</strong> Instagram posts and reels can be downloaded. Private accounts and Stories require login and are not supported.</span>
          </div>

          {{-- URL field --}}
          <div>
            <label for="ig-url-field" class="block text-sm font-medium text-gray-700 mb-1.5">
              Instagram Post / Reel URL
            </label>
            <div class="ig-input-wrap">
              <span class="ig-input-icon">📷</span>
              <input
                id="ig-url-field"
                type="url"
                x-model="url"
                :class="['ig-input', urlError ? 'ig-input-err' : '']"
                placeholder="https://www.instagram.com/reel/ABC123/"
                @input="urlError = ''; error = ''"
                @keydown.enter="submit()"
                autocomplete="off"
                spellcheck="false"
              >
            </div>
            <div x-show="urlError" x-transition class="ig-error mt-2">
              <span class="flex-shrink-0">⚠</span><span x-text="urlError"></span>
            </div>
          </div>

          {{-- Server / fetch error --}}
          <div x-show="error" x-transition class="ig-error">
            <span class="flex-shrink-0 mt-0.5">⚠</span>
            <div>
              <strong class="block mb-0.5">Could not download media</strong>
              <span x-text="error"></span>
            </div>
          </div>

          {{-- Submit --}}
          <button @click="submit()" :disabled="loading" class="ig-btn">
            <span x-show="loading" class="ig-spin"></span>
            <span x-show="loading">Fetching Media…</span>
            <span x-show="!loading">⬇ Download Instagram Media</span>
          </button>

        </div>

        {{-- ══ Result card ══ --}}
        <div class="card p-6 space-y-5" x-show="result" x-transition>

          {{-- Success banner --}}
          <div class="ig-success">
            <span>✅</span>
            <span x-text="result && result.type === 'video'
              ? 'Reel / Video found — preview & download below!'
              : 'Image found — ready to download!'"></span>
          </div>

          {{-- ── VIDEO PREVIEW ── --}}
          <template x-if="result && result.type === 'video' && result.video_url && !videoError">
            <div>
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold text-gray-700">Video Preview</span>
                <span class="ig-badge ig-badge-video">🎬 Reel / Video</span>
              </div>
              {{--
                We use the Instagram CDN URL directly in the video src.
                Public CDN URLs work for playback without CORS restrictions.
                The Download button routes through our proxy (/proxy?url=...) so
                the browser can save the file (cross-origin download attr requires same-origin).
              --}}
              <video
                :src="result.video_url"
                :poster="result.thumbnail || ''"
                controls
                preload="metadata"
                playsinline
                class="ig-preview-video"
                x-on:error.self="videoError = true"
              >
                Your browser does not support the video element.
              </video>
              <p class="text-xs text-gray-400 mt-1.5 text-center">
                Use the <strong>Download</strong> button below to save the MP4 file.
              </p>
            </div>
          </template>

          {{-- Video preview failed — show thumbnail with open link --}}
          <template x-if="result && result.type === 'video' && videoError">
            <div>
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold text-gray-700">Video Thumbnail</span>
                <span class="ig-badge ig-badge-video">🎬 Reel / Video</span>
              </div>
              <div class="ig-video-fallback" @click="openInTab(result.video_url)">
                <img :src="result.thumbnail || ''" class="ig-preview-img w-full" style="opacity:.7;">
                <div class="ig-play-overlay">
                  <div class="ig-play-btn">▶</div>
                  <span class="ig-play-label">Click to open video in new tab</span>
                </div>
              </div>
            </div>
          </template>

          {{-- ── IMAGE PREVIEW ── --}}
          <template x-if="result && result.type !== 'video' && result.thumbnail">
            <div>
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold text-gray-700">Preview</span>
                <span class="ig-badge ig-badge-image">🖼 Image</span>
              </div>
              <div class="ig-preview-wrap">
                <img
                  :src="result.thumbnail"
                  :alt="result.title || 'Instagram image'"
                  class="ig-preview-img"
                  loading="lazy"
                >
              </div>
            </div>
          </template>

          {{-- Caption --}}
          <div x-show="result && result.title" class="ig-caption">
            <div class="ig-caption-label">Caption / Title</div>
            <div class="ig-caption-text" x-text="result ? result.title : ''"></div>
          </div>

          {{-- Server note if present --}}
          <div x-show="result && result.note" class="ig-server-note">
            <span class="flex-shrink-0">ℹ️</span>
            <span x-text="result ? result.note : ''"></span>
          </div>

          {{-- Download items --}}
          <template x-if="result && result.items && result.items.length">
            <div class="space-y-3">
              <p class="ig-div">Download Options</p>

              <template x-for="(item, i) in result.items" :key="i">
                <div class="ig-dl-item">
                  <div class="ig-dl-item-label">
                    <span x-text="item.type === 'video' ? '🎬' : '🖼'"></span>
                    <span x-text="item.label || (item.type === 'video' ? 'Video / Reel' : 'Image')"></span>
                  </div>
                  <div class="ig-action-row">

                    {{--
                      Download via our proxy — routes media through our server so the
                      browser's `download` attribute works (requires same-origin).
                      The proxy validates the URL is a legitimate Instagram CDN domain
                      and streams the file with Content-Disposition: attachment.
                    --}}
                    <a
                      :href="proxyUrl(item.url)"
                      download
                      class="ig-dl-btn"
                    >
                      ⬇ Download
                    </a>

                    {{-- Open direct CDN URL in a new tab (instant, no proxy latency) --}}
                    <a
                      :href="item.url"
                      target="_blank"
                      rel="noopener noreferrer"
                      class="ig-open-btn"
                    >
                      🔗 Open
                    </a>

                    {{-- Copy the CDN URL to clipboard --}}
                    <button
                      @click="copyUrl(item.url, i)"
                      :class="['ig-copy-btn', copiedIndex === i ? 'ig-copied' : '']"
                    >
                      <span x-text="copiedIndex === i ? '✓ Copied!' : '📋 Copy URL'"></span>
                    </button>

                  </div>
                </div>
              </template>
            </div>
          </template>

          {{-- Reset --}}
          <p class="ig-reset" @click="reset()">↺ Download another post</p>

        </div>

        {{-- ══ How it works ══ --}}
        <div class="card p-6">
          <p class="ig-div mb-4">How It Works</p>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
            <div class="flex gap-3">
              <span class="text-2xl flex-shrink-0">🔗</span>
              <div>
                <p class="font-semibold text-gray-800">1. Copy the Link</p>
                <p class="text-gray-500 text-xs mt-0.5">Open Instagram, tap ⋯ on any public post or reel, and select "Copy Link".</p>
              </div>
            </div>
            <div class="flex gap-3">
              <span class="text-2xl flex-shrink-0">🔍</span>
              <div>
                <p class="font-semibold text-gray-800">2. Paste &amp; Fetch</p>
                <p class="text-gray-500 text-xs mt-0.5">Paste the URL and click Download. Our server extracts the actual MP4 or image URL from Instagram.</p>
              </div>
            </div>
            <div class="flex gap-3">
              <span class="text-2xl flex-shrink-0">💾</span>
              <div>
                <p class="font-semibold text-gray-800">3. Save to Device</p>
                <p class="text-gray-500 text-xs mt-0.5">Preview the video in-page or click Download to save the MP4 / JPG to your device.</p>
              </div>
            </div>
          </div>
        </div>

        {{-- Long Description --}}
        @if($tool->long_description)
        <div class="card p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">About This Tool</h2>
          <div class="tool-prose">
            {!! nl2br(e($tool->long_description)) !!}
          </div>
        </div>
        @endif

        {{-- FAQs --}}
        @if($tool->faqs->count() > 0)
        <div class="card p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Frequently Asked Questions</h2>
          <x-faq-list :faqs="$tool->faqs" />
        </div>
        @endif

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
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Supported Content</h3>
          <div class="space-y-2">
            <div class="ig-support-row"><span class="ig-yes">✓</span><span class="text-gray-700">Photos &amp; images</span></div>
            <div class="ig-support-row"><span class="ig-yes">✓</span><span class="text-gray-700">Reels &amp; short videos (MP4)</span></div>
            <div class="ig-support-row"><span class="ig-yes">✓</span><span class="text-gray-700">IGTV videos</span></div>
            <div class="ig-support-row"><span class="ig-yes">✓</span><span class="text-gray-700">Public accounts</span></div>
            <div class="ig-support-row"><span class="ig-no">✗</span><span class="text-gray-500">Private accounts</span></div>
            <div class="ig-support-row"><span class="ig-no">✗</span><span class="text-gray-500">Stories (login required)</span></div>
            <div class="ig-support-row"><span class="ig-no">✗</span><span class="text-gray-500">Carousel — all slides (1st only)</span></div>
          </div>
        </div>

        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">How to Copy a Link</h3>
          <ol class="space-y-2.5 text-xs text-gray-600">
            <li class="flex gap-2">
              <span class="text-pink-500 font-bold flex-shrink-0 mt-0.5">1.</span>
              <span>Open Instagram and go to the post or reel you want.</span>
            </li>
            <li class="flex gap-2">
              <span class="text-pink-500 font-bold flex-shrink-0 mt-0.5">2.</span>
              <span>Tap the <strong>three dots</strong> (⋯) on the post.</span>
            </li>
            <li class="flex gap-2">
              <span class="text-pink-500 font-bold flex-shrink-0 mt-0.5">3.</span>
              <span>Tap <strong>"Copy Link"</strong> from the menu.</span>
            </li>
            <li class="flex gap-2">
              <span class="text-pink-500 font-bold flex-shrink-0 mt-0.5">4.</span>
              <span>Paste the link above and click Download.</span>
            </li>
          </ol>
        </div>

        <div class="card p-5">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Accepted URL Formats</h3>
          <div class="space-y-1.5 text-xs font-mono text-gray-500 bg-gray-50 rounded-lg p-3 break-all leading-relaxed">
            <div>instagram.com/p/<em class="text-gray-400">shortcode</em>/</div>
            <div>instagram.com/reel/<em class="text-gray-400">shortcode</em>/</div>
            <div>instagram.com/tv/<em class="text-gray-400">shortcode</em>/</div>
          </div>
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
   Instagram Downloader — Alpine.js component

   Flow:
     1. User pastes URL → client validates format (regex, no network).
     2. POST /tools/instagram-downloader/process → JSON {success, type, items…}.
        Backend tries 3 strategies in order:
          a) Embed page (/reel/{sc}/embed/) — finds <video> src or JSON video_url
          b) Main page og:video / og:image / JSON extraction
          c) oEmbed API (thumbnail only)
     3. If type === 'video':
          - <video :src="result.video_url"> plays inline (public CDN, no CORS on playback)
          - error handler catches load failures and shows thumbnail + play overlay
     4. Download button → /tools/instagram-downloader/proxy?url=… (same-origin)
        Proxy streams the CDN asset and sets Content-Disposition: attachment so
        the browser saves the file rather than opening it.
─────────────────────────────────────────────────────────────────── */
function igDownloader() {
  return {
    url:         '',
    urlError:    '',
    loading:     false,
    error:       '',
    result:      null,
    copiedIndex: -1,
    videoError:  false,

    // ── Validate URL format client-side ───────────────────
    validate() {
      const u = this.url.trim();

      if (!u) {
        this.urlError = 'Please enter an Instagram URL.';
        return false;
      }

      if (!/instagram\.com\/(p|reel|tv)\/[A-Za-z0-9_-]+/i.test(u)) {
        this.urlError =
          "That doesn't look like a valid Instagram post or reel URL.\n" +
          'Expected: https://www.instagram.com/reel/ABC123/ or /p/ABC123/';
        return false;
      }

      this.urlError = '';
      return true;
    },

    // ── Submit to backend ─────────────────────────────────
    async submit() {
      if (!this.validate()) return;

      this.loading    = true;
      this.error      = '';
      this.result     = null;
      this.videoError = false;

      try {
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const res = await fetch('/tools/instagram-downloader/process', {
          method:  'POST',
          headers: {
            'Content-Type':  'application/json',
            'Accept':        'application/json',
            'X-CSRF-TOKEN':  csrfMeta ? csrfMeta.getAttribute('content') : '',
          },
          body: JSON.stringify({ url: this.url.trim() }),
        });

        const data = await res.json();

        if (data.success) {
          this.result = data;
        } else {
          this.error = data.error || 'Could not process this Instagram URL. Please try again.';
        }

      } catch {
        this.error = 'Network error — please check your internet connection and try again.';
      } finally {
        this.loading = false;
      }
    },

    // ── Build proxy URL for safe same-origin downloads ────
    proxyUrl(mediaUrl) {
      return '/tools/instagram-downloader/proxy?url=' + encodeURIComponent(mediaUrl);
    },

    // ── Open a URL in a new tab ───────────────────────────
    openInTab(url) {
      if (url) window.open(url, '_blank', 'noopener,noreferrer');
    },

    // ── Copy a CDN URL to clipboard ───────────────────────
    async copyUrl(url, index) {
      try {
        await navigator.clipboard.writeText(url);
      } catch {
        // execCommand fallback for older browsers
        const ta = Object.assign(document.createElement('textarea'), {
          value: url,
        });
        Object.assign(ta.style, { position: 'fixed', opacity: '0' });
        document.body.appendChild(ta);
        ta.focus(); ta.select();
        try { document.execCommand('copy'); } catch {}
        document.body.removeChild(ta);
      }
      this.copiedIndex = index;
      setTimeout(() => { if (this.copiedIndex === index) this.copiedIndex = -1; }, 2000);
    },

    // ── Reset to initial state ─────────────────────────────
    reset() {
      this.url         = '';
      this.urlError    = '';
      this.error       = '';
      this.result      = null;
      this.loading     = false;
      this.copiedIndex = -1;
      this.videoError  = false;
    },
  };
}
</script>
@endpush
