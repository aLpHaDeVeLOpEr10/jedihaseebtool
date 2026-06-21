<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class InstagramDownloaderEngine
{
    // yt-dlp binary — check common locations automatically
    private function ytdlpPath(): ?string
    {
        $candidates = [
            'C:\\Users\\DELL\\AppData\\Roaming\\Python\\Python314\\Scripts\\yt-dlp.exe',
            'yt-dlp',
            'yt-dlp.exe',
        ];
        foreach ($candidates as $p) {
            if (@is_executable($p) || $p === 'yt-dlp' || $p === 'yt-dlp.exe') {
                // Quick existence test
                $test = $this->runCmd([$p, '--version'], 5);
                if ($test !== null && str_contains($test, '.')) {
                    return $p;
                }
            }
        }
        return null;
    }

    private array $desktopHeaders = [
        'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
        'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
        'Accept-Language' => 'en-US,en;q=0.9',
        'Accept-Encoding' => 'identity',
        'Sec-Fetch-Dest'  => 'document',
        'Sec-Fetch-Mode'  => 'navigate',
        'Sec-Fetch-Site'  => 'none',
        'Upgrade-Insecure-Requests' => '1',
        'Cache-Control'   => 'max-age=0',
    ];

    // ─────────────────────────────────────────────────────────
    public function download(array $data): array
    {
        $url = trim($data['url'] ?? '');

        if (empty($url)) {
            return ['success' => false, 'error' => 'Please enter an Instagram URL.'];
        }

        if (!$this->isValidUrl($url)) {
            return [
                'success' => false,
                'error'   => 'Please enter a valid Instagram post, reel, or video URL '
                           . '(e.g. https://www.instagram.com/p/ABC123/ or /reel/ABC123/).',
            ];
        }

        if ($this->isStory($url)) {
            return [
                'success' => false,
                'error'   => 'Instagram Stories are private and require a logged-in session to access. '
                           . 'Only public posts and reels can be downloaded without login.',
            ];
        }

        $cleanUrl  = $this->cleanUrl($url);
        $shortcode = $this->extractShortcode($cleanUrl);
        $isReel    = $this->isReelOrVideo($cleanUrl);

        // ── Strategy 1: yt-dlp (most reliable, handles all public reels) ──
        $ytResult = $this->tryYtdlp($cleanUrl);
        if ($ytResult['success']) {
            return $ytResult;
        }

        // ── Strategy 2: Embed page (CDN URL from HTML) ─────────────────
        if ($shortcode) {
            $embedResult = $this->tryEmbedPage($shortcode, $isReel);
            if ($embedResult['success']) {
                return $embedResult;
            }
        }

        // ── Strategy 3: Main Instagram page (og tags + JSON) ───────────
        $pageResult = $this->tryMainPage($cleanUrl);
        if ($pageResult['success']) {
            return $pageResult;
        }

        // ── Strategy 4: oEmbed (thumbnail only, last resort) ───────────
        $oembedResult = $this->tryOembed($cleanUrl);
        if ($oembedResult['success']) {
            return $oembedResult;
        }

        return [
            'success' => false,
            'error'   => $pageResult['error']
                       ?? 'Could not extract media from this post. It may be private, '
                        . 'age-restricted, or Instagram is temporarily blocking requests. '
                        . 'Please try again in a moment.',
        ];
    }

    // ── Strategy 1: yt-dlp ──────────────────────────────────
    private function tryYtdlp(string $url): array
    {
        $bin = $this->ytdlpPath();
        if (!$bin) {
            return ['success' => false];
        }

        // --dump-json gives us url, thumbnail, title, duration, etc. in one call
        $json = $this->runCmd([
            $bin,
            '--dump-json',
            '--no-playlist',
            '--no-warnings',
            '--socket-timeout', '15',
            $url,
        ], 30);

        if (!$json) {
            return ['success' => false];
        }

        $info = json_decode($json, true);
        if (!$info || empty($info['url']) && empty($info['requested_formats'])) {
            return ['success' => false];
        }

        // Pick the best video URL from formats (prefer mp4 with video+audio)
        $videoUrl = null;
        if (!empty($info['requested_formats'])) {
            foreach ($info['requested_formats'] as $fmt) {
                if (!empty($fmt['url']) && ($fmt['vcodec'] ?? 'none') !== 'none') {
                    $videoUrl = $fmt['url'];
                    break;
                }
            }
        }
        if (!$videoUrl && !empty($info['url'])) {
            $videoUrl = $info['url'];
        }
        if (!$videoUrl) {
            return ['success' => false];
        }

        $thumbnail = $info['thumbnail'] ?? null;
        $title     = $info['title']     ?? ($info['description'] ?? '');
        $isVideo   = true;

        $items = [
            ['type' => 'video', 'url' => $videoUrl, 'label' => 'Video / Reel (MP4)'],
        ];
        if ($thumbnail) {
            $items[] = ['type' => 'image', 'url' => $thumbnail, 'label' => 'Thumbnail'];
        }

        return [
            'success'   => true,
            'type'      => 'video',
            'title'     => $title,
            'thumbnail' => $thumbnail,
            'video_url' => $videoUrl,
            'items'     => $items,
            'source_url'=> $url,
        ];
    }

    // ── Run a subprocess safely, return stdout or null ───────
    private function runCmd(array $cmd, int $timeout = 15): ?string
    {
        $descriptors = [
            0 => ['pipe', 'r'],   // stdin
            1 => ['pipe', 'w'],   // stdout
            2 => ['pipe', 'w'],   // stderr
        ];

        try {
            $proc = proc_open($cmd, $descriptors, $pipes);
            if (!is_resource($proc)) return null;

            fclose($pipes[0]);

            // Set non-blocking with timeout loop
            stream_set_timeout($pipes[1], $timeout);
            $stdout = stream_get_contents($pipes[1]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            proc_close($proc);

            return ($stdout && strlen($stdout) > 10) ? $stdout : null;
        } catch (\Exception) {
            return null;
        }
    }

    // ── Central HTTP client — SSL fix for Windows dev ────────
    private function http(array $extraHeaders = [])
    {
        return Http::withHeaders(array_merge($this->desktopHeaders, $extraHeaders))
            ->withOptions([
                'verify'          => false,
                'allow_redirects' => ['max' => 5, 'strict' => false, 'referer' => true],
                'curl' => [
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_ENCODING       => '',
                ],
            ])
            ->timeout(20);
    }

    // ── file_get_contents fallback ────────────────────────────
    private function fetchWithFgc(string $url): ?string
    {
        $ctx = stream_context_create([
            'http' => [
                'method'          => 'GET',
                'header'          => implode("\r\n", [
                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
                    'Accept: text/html,application/xhtml+xml,*/*;q=0.8',
                    'Accept-Language: en-US,en;q=0.9',
                    'Cache-Control: max-age=0',
                    'Upgrade-Insecure-Requests: 1',
                ]),
                'follow_location' => 1,
                'max_redirects'   => 5,
                'timeout'         => 20,
                'ignore_errors'   => true,
            ],
            'ssl' => [
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ],
        ]);

        try {
            $body = @file_get_contents($url, false, $ctx);
            return ($body !== false && strlen($body) > 500) ? $body : null;
        } catch (\Exception) {
            return null;
        }
    }

    // ── Unified HTML fetcher ──────────────────────────────────
    private function getHtml(string $url, array $extraHeaders = []): ?string
    {
        try {
            $response = $this->http($extraHeaders)->get($url);
            if ($response->successful()) {
                $body = $response->body();
                if (strlen($body) > 500) return $body;
            }
        } catch (\Exception) { /* fall through */ }

        return $this->fetchWithFgc($url);
    }

    // ── Validators / helpers ─────────────────────────────────
    private function isValidUrl(string $url): bool
    {
        return (bool) preg_match('/instagram\.com\/(p|reel|tv|stories)\/[A-Za-z0-9_\-]+/i', $url);
    }

    private function isStory(string $url): bool
    {
        return (bool) preg_match('/instagram\.com\/stories\//i', $url);
    }

    private function isReelOrVideo(string $url): bool
    {
        return (bool) preg_match('/instagram\.com\/(reel|tv)\//i', $url);
    }

    private function cleanUrl(string $url): string
    {
        if (preg_match('/(https?:\/\/(?:www\.)?instagram\.com\/(?:p|reel|tv)\/[A-Za-z0-9_-]+)/i', $url, $m)) {
            return $m[1] . '/';
        }
        return $url;
    }

    private function extractShortcode(string $url): ?string
    {
        if (preg_match('/instagram\.com\/(?:p|reel|tv)\/([A-Za-z0-9_-]+)/i', $url, $m)) {
            return $m[1];
        }
        return null;
    }

    // ── Strategy 2: Embed page ───────────────────────────────
    private function tryEmbedPage(string $shortcode, bool $isReel): array
    {
        $embedUrls = $isReel
            ? [
                "https://www.instagram.com/reel/{$shortcode}/embed/captioned/",
                "https://www.instagram.com/reel/{$shortcode}/embed/",
                "https://www.instagram.com/p/{$shortcode}/embed/captioned/",
                "https://www.instagram.com/p/{$shortcode}/embed/",
              ]
            : [
                "https://www.instagram.com/p/{$shortcode}/embed/captioned/",
                "https://www.instagram.com/p/{$shortcode}/embed/",
                "https://www.instagram.com/reel/{$shortcode}/embed/captioned/",
                "https://www.instagram.com/reel/{$shortcode}/embed/",
              ];

        foreach ($embedUrls as $embedUrl) {
            $html = $this->getHtml($embedUrl, [
                'Referer'        => 'https://www.instagram.com/',
                'Sec-Fetch-Site' => 'same-origin',
            ]);
            if (!$html) continue;

            $videoUrl = $this->extractVideoUrlFromHtml($html);
            $imageUrl = $this->extractMeta($html, 'og:image') ?? $this->extractFirstCdnImage($html);
            $title    = $this->extractMeta($html, 'og:title') ?? '';

            if (!$videoUrl && !$imageUrl) continue;

            $isVideo = (bool) $videoUrl;
            $items   = [];
            if ($videoUrl) $items[] = ['type' => 'video', 'url' => $videoUrl, 'label' => 'Video / Reel (MP4)'];
            if ($imageUrl) $items[] = ['type' => 'image', 'url' => $imageUrl, 'label' => $isVideo ? 'Thumbnail' : 'Image'];

            return [
                'success'    => true,
                'type'       => $isVideo ? 'video' : 'image',
                'title'      => $title,
                'thumbnail'  => $imageUrl ?? $videoUrl,
                'video_url'  => $videoUrl,
                'items'      => $items,
                'source_url' => "https://www.instagram.com/" . ($isReel ? "reel" : "p") . "/{$shortcode}/",
            ];
        }

        return ['success' => false];
    }

    // ── Strategy 3: Main Instagram page ─────────────────────
    private function tryMainPage(string $url): array
    {
        $html = $this->getHtml($url);

        if ($html === null) {
            return [
                'success' => false,
                'error'   => 'Could not reach Instagram. Check your server\'s internet connection or try again.',
            ];
        }

        if (strlen($html) < 500) {
            return ['success' => false, 'error' => 'Instagram returned an empty page.'];
        }

        if (
            str_contains($html, 'Log in to Instagram') ||
            str_contains($html, '"loginPage"') ||
            str_contains($html, '/accounts/login/')
        ) {
            return [
                'success' => false,
                'error'   => 'Instagram requires login to view this post. Only public posts can be downloaded.',
            ];
        }

        $video = $this->extractMeta($html, 'og:video')
              ?? $this->extractMeta($html, 'og:video:url')
              ?? $this->extractMeta($html, 'og:video:secure_url')
              ?? $this->extractVideoUrlFromHtml($html);

        $image = $this->extractMeta($html, 'og:image');
        $title = $this->extractMeta($html, 'og:title') ?? '';
        $desc  = $this->extractMeta($html, 'og:description') ?? '';

        if (empty($image) && empty($video)) {
            return [
                'success' => false,
                'error'   => 'Could not find media in this post. Instagram may have changed their page format.',
            ];
        }

        $isVideo = !empty($video);
        $items   = [];
        if ($isVideo)       $items[] = ['type' => 'video', 'url' => $video, 'label' => 'Video / Reel (MP4)'];
        if (!empty($image)) $items[] = ['type' => 'image', 'url' => $image, 'label' => $isVideo ? 'Thumbnail' : 'Image'];

        return [
            'success'     => true,
            'type'        => $isVideo ? 'video' : 'image',
            'title'       => $title,
            'description' => $desc,
            'thumbnail'   => $image ?? null,
            'video_url'   => $video ?? null,
            'items'       => $items,
            'source_url'  => $url,
        ];
    }

    // ── Strategy 4: oEmbed API ───────────────────────────────
    private function tryOembed(string $url): array
    {
        try {
            $response = $this->http([
                'User-Agent' => 'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)',
            ])->get('https://api.instagram.com/oembed/', [
                'url'        => $url,
                'maxwidth'   => 640,
                'omitscript' => 'true',
            ]);

            if (!$response->successful()) return ['success' => false];

            $data = $response->json();
            if (empty($data['thumbnail_url'])) return ['success' => false];

            return [
                'success'    => true,
                'type'       => 'image',
                'title'      => $data['title'] ?? '',
                'author'     => $data['author_name'] ?? '',
                'thumbnail'  => $data['thumbnail_url'],
                'items'      => [
                    ['type' => 'image', 'url' => $data['thumbnail_url'], 'label' => 'Thumbnail (oEmbed)'],
                ],
                'source_url' => $url,
                'note'       => 'Only the thumbnail was retrievable. For the full video, ensure the URL is a public reel.',
            ];

        } catch (\Exception) {
            return ['success' => false];
        }
    }

    // ── Video URL extraction from HTML ───────────────────────
    private function extractVideoUrlFromHtml(string $html): ?string
    {
        $patterns = [
            '/"@type"\s*:\s*"VideoObject"[^}]{0,500}"contentUrl"\s*:\s*"([^"]+)"/si',
            '/"contentUrl"\s*:\s*"(https:\/\/[^"]+\.mp4[^"]*)"/i',
            '/"video_url"\s*:\s*"(https:\\\\?\/\\\\?\/[^"]+\.mp4[^"]*)"/i',
            '/"video_url"\s*:\s*"(https:\/\/[^"]+\.mp4[^"]*)"/i',
            '/"video_versions"[^\]]*\[\s*\{[^}]*"url"\s*:\s*"([^"]+\.mp4[^"]*)"/si',
            '/<video[^>]+src=["\']([^"\']+\.mp4[^"\']*)["\'][^>]*>/i',
            '/<source[^>]+src=["\']([^"\']+\.mp4[^"\']*)["\'][^>]*>/i',
            '/(https:\/\/[a-z0-9\-]+\.cdninstagram\.com\/[^"\'<>\s]+\.mp4[^"\'<>\s]*)/i',
            '/(https:\/\/[a-z0-9\-]+\.fbcdn\.net\/[^"\'<>\s]+\.mp4[^"\'<>\s]*)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $html, $m)) {
                return $this->decodeVideoUrl($m[1]);
            }
        }

        return null;
    }

    private function decodeVideoUrl(string $raw): string
    {
        $url = str_replace(['\\/', '\\u002F', '\\u002f'], '/', $raw);
        $decoded = json_decode('"' . addcslashes($url, '"') . '"');
        if ($decoded && filter_var($decoded, FILTER_VALIDATE_URL)) {
            $url = $decoded;
        }
        return html_entity_decode($url, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    private function extractFirstCdnImage(string $html): ?string
    {
        if (preg_match(
            '#https://[a-z0-9\-]+\.cdninstagram\.com/[^"\'<>\s]+\.(jpg|jpeg|png|webp)[^"\'<>\s]*#i',
            $html, $m
        )) {
            return html_entity_decode($m[0], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        return null;
    }

    private function extractMeta(string $html, string $property): ?string
    {
        $prop = preg_quote($property, '/');
        $patterns = [
            '/<meta[^>]+property=["\']' . $prop . '["\'][^>]+content=["\']([^"\']+)["\'][^>]*>/i',
            '/<meta[^>]+content=["\']([^"\']+)["\'][^>]+property=["\']' . $prop . '["\'][^>]*>/i',
            '/<meta[^>]+property=["\']' . $prop . '["\'][^>]+content="([^"]+)"/i',
        ];
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $html, $m)) {
                return html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
            }
        }
        return null;
    }
}
