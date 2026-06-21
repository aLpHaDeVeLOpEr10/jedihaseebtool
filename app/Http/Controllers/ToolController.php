<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tool;
use App\Services\ToolEngine;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function __construct(private ToolEngine $engine) {}

    public function index(Request $request)
    {
        $query = Tool::active()->with('category');

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('type')) {
            $query->where('tool_type', $request->type);
        }

        if ($request->filled('sort')) {
            match ($request->sort) {
                'popular' => $query->orderByDesc('view_count'),
                'newest'  => $query->latest(),
                'name'    => $query->orderBy('name'),
                default   => $query->ordered(),
            };
        } else {
            $query->ordered();
        }

        $tools      = $query->paginate(24)->withQueryString();
        $categories = Category::active()->withCount('activeTools')->ordered()->get();
        $toolTypes  = Tool::active()->distinct()->pluck('tool_type')->sort()->values();

        return view('public.tools.index', compact('tools', 'categories', 'toolTypes'));
    }

    public function show(string $slug)
    {
        $tool = Tool::active()
            ->with(['category', 'contents', 'faqs', 'inputs'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment view count
        $tool->incrementViews();

        $relatedTools = $tool->getRelatedTools(4);

        // Determine view
        $customView = 'tools.generated.' . $tool->slug;
        if ($tool->has_custom_blade && view()->exists($customView)) {
            return view($customView, compact('tool', 'relatedTools'));
        }

        return view('public.tools.dynamic', compact('tool', 'relatedTools'));
    }

    public function process(Request $request, string $slug)
    {
        $tool = Tool::active()->where('slug', $slug)->firstOrFail();

        // Rate limiting
        $key = 'tool_process_' . $request->ip() . '_' . $tool->id;
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, 30)) {
            return response()->json(['success' => false, 'error' => 'Too many requests. Please slow down.'], 429);
        }
        \Illuminate\Support\Facades\RateLimiter::hit($key, 60);

        $result = $this->engine->process($tool, $request);
        $tool->incrementUses();

        return response()->json($result);
    }

    public function instagramProxy(Request $request): mixed
    {
        $url = $request->get('url', '');

        // Only allow Instagram CDN domains — prevents open-proxy misuse
        if (!$url || !preg_match('/^https:\/\/[a-z0-9\-\.]+\.(cdninstagram\.com|fbcdn\.net)\//i', $url)) {
            return response()->json(['error' => 'Invalid media URL.'], 403);
        }

        try {
            // Stream the response so large video files don't exhaust PHP memory
            $upstream = \Illuminate\Support\Facades\Http::withHeaders([
                'Referer'    => 'https://www.instagram.com/',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
                'Accept'     => '*/*',
            ])->withOptions([
                'stream' => true,
                'verify' => false,
                'curl'   => [
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => 0,
                ],
            ])->timeout(60)->get($url);

            if (!$upstream->successful()) {
                return response()->json(['error' => 'Media not available or the link has expired.'], 404);
            }

            $psrResponse = $upstream->toPsrResponse();
            $contentType = $psrResponse->getHeaderLine('Content-Type') ?: 'application/octet-stream';
            $contentLen  = $psrResponse->getHeaderLine('Content-Length');

            // Derive filename extension from content-type
            $ext = match (true) {
                str_contains($contentType, 'video') => 'mp4',
                str_contains($contentType, 'png')   => 'png',
                str_contains($contentType, 'webp')  => 'webp',
                default                             => 'jpg',
            };

            $headers = [
                'Content-Type'        => $contentType,
                'Content-Disposition' => "attachment; filename=\"instagram_download.{$ext}\"",
                'Cache-Control'       => 'no-store',
                'X-Accel-Buffering'   => 'no',
            ];

            if ($contentLen) {
                $headers['Content-Length'] = $contentLen;
            }

            return response()->stream(function () use ($psrResponse) {
                $body = $psrResponse->getBody();
                while (!$body->eof()) {
                    echo $body->read(8192);
                    if (ob_get_level() > 0) {
                        ob_flush();
                    }
                    flush();
                }
            }, 200, $headers);

        } catch (\Exception) {
            return response()->json(['error' => 'Download failed. The media link may have expired.'], 500);
        }
    }

    public function facebookProxy(\Illuminate\Http\Request $request): mixed
    {
        $url = $request->get('url', '');

        // Only allow Facebook / Meta CDN domains
        if (!$url || !preg_match(
            '/^https:\/\/[a-z0-9\-\.]+\.(fbcdn\.net|cdninstagram\.com|facebook\.com|facebookcdn\.com)\//i',
            $url
        )) {
            return response()->json(['error' => 'Invalid media URL.'], 403);
        }

        try {
            $upstream = \Illuminate\Support\Facades\Http::withHeaders([
                'Referer'    => 'https://www.facebook.com/',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
                'Accept'     => '*/*',
            ])->withOptions([
                'stream' => true,
                'verify' => false,
                'curl'   => [
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => 0,
                ],
            ])->timeout(60)->get($url);

            if (!$upstream->successful()) {
                return response()->json(['error' => 'Media not available or the link has expired.'], 404);
            }

            $psrResponse = $upstream->toPsrResponse();
            $contentType = $psrResponse->getHeaderLine('Content-Type') ?: 'application/octet-stream';
            $contentLen  = $psrResponse->getHeaderLine('Content-Length');

            $ext = match (true) {
                str_contains($contentType, 'video') => 'mp4',
                str_contains($contentType, 'png')   => 'png',
                str_contains($contentType, 'webp')  => 'webp',
                default                             => 'jpg',
            };

            $headers = [
                'Content-Type'        => $contentType,
                'Content-Disposition' => "attachment; filename=\"facebook_download.{$ext}\"",
                'Cache-Control'       => 'no-store',
                'X-Accel-Buffering'   => 'no',
            ];
            if ($contentLen) $headers['Content-Length'] = $contentLen;

            return response()->stream(function () use ($psrResponse) {
                $body = $psrResponse->getBody();
                while (!$body->eof()) {
                    echo $body->read(8192);
                    if (ob_get_level() > 0) ob_flush();
                    flush();
                }
            }, 200, $headers);

        } catch (\Exception) {
            return response()->json(['error' => 'Download failed. The media link may have expired.'], 500);
        }
    }

    public function youtubeProxy(\Illuminate\Http\Request $request): mixed
    {
        $url      = $request->get('url', '');
        $formatId = $request->get('format', 'best[ext=mp4]/best');
        $title    = $request->get('title', 'youtube_video');
        $ext      = $request->get('ext', 'mp4');

        // Security: only allow YouTube URLs
        if (!$url || !preg_match('/^https?:\/\/(www\.|m\.)?(youtube\.com|youtu\.be)\//i', $url)) {
            return response()->json(['error' => 'Invalid URL.'], 403);
        }

        // Sanitize format_id — allow alphanumeric, dashes, brackets, comparison operators
        if (!preg_match('/^[a-zA-Z0-9_\-\+\[\]\/\.\<=\(\)]{1,100}$/', $formatId)) {
            $formatId = 'best[ext=mp4]/best';
        }

        // Sanitize ext
        $ext = in_array(strtolower($ext), ['mp4', 'm4a', 'webm', 'opus'], true)
            ? strtolower($ext) : 'mp4';

        // Sanitize filename
        $title = preg_replace('/[^\w\s\-\.]/', '', $title);
        $title = trim(substr($title, 0, 120)) ?: 'youtube_video';

        // Find yt-dlp binary
        $bin = app(\App\Services\YoutubeDownloaderEngine::class)->findYtdlp();
        if (!$bin) {
            return response()->json(['error' => 'Download service unavailable.'], 500);
        }

        @set_time_limit(0);
        @ini_set('max_execution_time', '0');

        // Step 1: ask yt-dlp for the direct CDN URL (fast, ~3-5 s)
        $cdnUrl = $this->getYtCdnUrl($bin, $formatId, $url);
        if (!$cdnUrl) {
            return response()->json(['error' => 'Could not retrieve download link. Please try again.'], 500);
        }

        // Step 2: proxy the CDN URL via HTTP client (same reliable approach as Instagram/Facebook)
        return $this->streamYtCdnUrl($cdnUrl, $title, $ext);
    }

    private function getYtCdnUrl(string $bin, string $formatId, string $url): ?string
    {
        $descriptors = [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
        $cmd = [
            $bin, '--get-url', '-f', $formatId,
            '--no-playlist', '--no-warnings',
            '--socket-timeout', '15',
            $url,
        ];

        $proc = @proc_open($cmd, $descriptors, $pipes);
        if (!is_resource($proc)) return null;

        fclose($pipes[0]);
        stream_set_timeout($pipes[1], 40);
        $output = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        // drain stderr to avoid pipe-full deadlock
        stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        proc_close($proc);

        if (!$output) return null;

        // yt-dlp may return multiple URLs (adaptive) — take the first
        $lines  = array_values(array_filter(array_map('trim', explode("\n", $output))));
        $cdnUrl = $lines[0] ?? '';

        // Must be a googlevideo.com CDN link
        if (!preg_match('/^https:\/\/[a-z0-9\-\.]+\.googlevideo\.com\//i', $cdnUrl)) {
            return null;
        }

        return $cdnUrl;
    }

    private function streamYtCdnUrl(string $cdnUrl, string $title, string $ext): mixed
    {
        try {
            $upstream = \Illuminate\Support\Facades\Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Referer'    => 'https://www.youtube.com/',
                'Accept'     => '*/*',
            ])->withOptions([
                'stream' => true,
                'verify' => false,
                'curl'   => [CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => 0],
            ])->timeout(180)->get($cdnUrl);

            if (!$upstream->successful()) {
                return response()->json(['error' => 'Download link expired. Please try again.'], 404);
            }

            $psrResponse = $upstream->toPsrResponse();
            $contentLen  = $psrResponse->getHeaderLine('Content-Length');

            $mimeMap = [
                'mp4'  => 'video/mp4',
                'm4a'  => 'audio/mp4',
                'webm' => 'video/webm',
                'opus' => 'audio/ogg; codecs=opus',
            ];
            $contentType = $mimeMap[$ext] ?? 'application/octet-stream';

            $headers = [
                'Content-Type'        => $contentType,
                'Content-Disposition' => "attachment; filename=\"{$title}.{$ext}\"",
                'Cache-Control'       => 'no-store',
                'X-Accel-Buffering'   => 'no',
            ];
            if ($contentLen) {
                $headers['Content-Length'] = $contentLen;
            }

            return response()->stream(function () use ($psrResponse) {
                $body = $psrResponse->getBody();
                while (!$body->eof()) {
                    echo $body->read(65536);
                    if (ob_get_level() > 0) ob_flush();
                    flush();
                }
            }, 200, $headers);

        } catch (\Exception) {
            return response()->json(['error' => 'Download failed. Please try again.'], 500);
        }
    }

    public function tiktokProxy(\Illuminate\Http\Request $request): mixed
    {
        $url      = $request->get('url', '');
        $formatId = $request->get('format', 'best[ext=mp4]/best');
        $title    = $request->get('title', 'tiktok_video');

        // Security: only allow TikTok URLs
        if (!$url || !preg_match('/^https?:\/\/(www\.|m\.|vm\.|vt\.)?tiktok\.com\//i', $url)) {
            return response()->json(['error' => 'Invalid URL.'], 403);
        }

        // Sanitize format_id
        if (!preg_match('/^[a-zA-Z0-9_\-\+\[\]\/\.\<=\(\)]{1,100}$/', $formatId)) {
            $formatId = 'best[ext=mp4]/best';
        }

        // Sanitize filename
        $title = preg_replace('/[^\w\s\-\.]/', '', $title);
        $title = trim(substr($title, 0, 120)) ?: 'tiktok_video';

        $bin = app(\App\Services\TiktokDownloaderEngine::class)->findYtdlp();
        if (!$bin) {
            return response()->json(['error' => 'Download service unavailable.'], 500);
        }

        @set_time_limit(0);
        @ini_set('max_execution_time', '0');

        // Download to a temp file — avoids Windows pipe-streaming issues and
        // lets yt-dlp set all the TikTok CDN headers it needs internally.
        $uid     = uniqid('tk_', true);
        $tmpTpl  = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $uid . '.%(ext)s';

        $descriptors = [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
        $cmd = [
            $bin, '-f', $formatId,
            '--no-playlist', '--no-warnings',
            '--socket-timeout', '25',
            '-o', $tmpTpl,
            $url,
        ];

        $proc = @proc_open($cmd, $descriptors, $pipes);
        if (!is_resource($proc)) {
            return response()->json(['error' => 'Download service error.'], 500);
        }

        fclose($pipes[0]);
        stream_set_timeout($pipes[1], 120);
        stream_get_contents($pipes[1]);  // drain stdout
        fclose($pipes[1]);
        stream_get_contents($pipes[2]);  // drain stderr
        fclose($pipes[2]);
        proc_close($proc);

        // Find the file yt-dlp created (extension is filled in by yt-dlp from template)
        $pattern = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $uid . '.*';
        $files   = glob($pattern);
        $tmpFile = !empty($files) ? $files[0] : null;

        if (!$tmpFile || !file_exists($tmpFile) || filesize($tmpFile) < 1000) {
            if ($tmpFile) @unlink($tmpFile);
            return response()->json([
                'error' => 'Could not download this video. It may be private, region-restricted, or deleted.',
            ], 500);
        }

        $ext = strtolower(pathinfo($tmpFile, PATHINFO_EXTENSION)) ?: 'mp4';

        return response()->download($tmpFile, $title . '.' . $ext, [
            'Content-Type'  => in_array($ext, ['mp4', 'webm'], true) ? "video/{$ext}" : 'video/mp4',
            'Cache-Control' => 'no-store',
        ])->deleteFileAfterSend(true);
    }

    public function search(Request $request)
    {
        $q          = $request->q ?? '';
        $category   = $request->category ?? '';
        $type       = $request->type ?? '';

        $query = Tool::active()->with('category');

        if (!empty($q)) {
            $query->where(function ($q2) use ($q) {
                $q2->where('name', 'like', "%{$q}%")
                   ->orWhere('short_description', 'like', "%{$q}%")
                   ->orWhere('long_description', 'like', "%{$q}%")
                   ->orWhere('seo_keywords', 'like', "%{$q}%");
            });
        }

        if (!empty($category)) {
            $query->whereHas('category', fn($q2) => $q2->where('slug', $category));
        }

        if (!empty($type)) {
            $query->where('tool_type', $type);
        }

        $tools      = $query->ordered()->paginate(20)->withQueryString();
        $categories = Category::active()->ordered()->get();

        return view('public.search', compact('tools', 'q', 'category', 'type', 'categories'));
    }
}
