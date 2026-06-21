<?php

namespace App\Services;

class FacebookDownloaderEngine
{
    // ─────────────────────────────────────────────────────────
    public function download(array $data): array
    {
        $url = trim($data['url'] ?? '');

        if (empty($url)) {
            return ['success' => false, 'error' => 'Please enter a Facebook URL.'];
        }

        if (!$this->isValidUrl($url)) {
            return [
                'success' => false,
                'error'   => 'Please enter a valid Facebook URL. Accepted formats: '
                           . 'facebook.com/reel/…, facebook.com/watch/…, '
                           . 'facebook.com/share/r/…, facebook.com/[page]/videos/…, fb.watch/…',
            ];
        }

        // ── Strategy 1: yt-dlp ─────────────────────────────────
        $result = $this->tryYtdlp($url);
        if ($result['success']) return $result;

        return [
            'success' => false,
            'error'   => $result['error']
                       ?? 'Could not extract video from this Facebook post. '
                        . 'The video may be private, login-protected, or unavailable.',
        ];
    }

    // ── yt-dlp extraction ────────────────────────────────────
    private function tryYtdlp(string $url): array
    {
        $bin = $this->ytdlpPath();
        if (!$bin) {
            return [
                'success' => false,
                'error'   => 'Video extraction service unavailable. Please try again later.',
            ];
        }

        $json = $this->runCmd([
            $bin,
            '--dump-json',
            '--no-playlist',
            '--no-warnings',
            '--socket-timeout', '15',
            '--extractor-args', 'facebook:skip_webpage=false',
            $url,
        ], 35);

        if (!$json) {
            return [
                'success' => false,
                'error'   => 'Could not retrieve video data. The video may be private or age-restricted.',
            ];
        }

        // yt-dlp sometimes outputs multiple JSON lines for playlists; use first
        $firstLine = explode("\n", trim($json))[0];
        $info = json_decode($firstLine, true);

        if (!$info) {
            return ['success' => false, 'error' => 'Unexpected response from video service.'];
        }

        // Pick best video URL (prefer combined video+audio mp4)
        $videoUrl = $this->pickBestUrl($info);

        if (!$videoUrl) {
            return ['success' => false, 'error' => 'No downloadable video stream found in this post.'];
        }

        $thumbnail = $info['thumbnail'] ?? null;
        $title     = $info['title']       ?? ($info['description'] ?? '');
        $duration  = isset($info['duration']) ? (int) $info['duration'] : null;

        $items = [
            ['type' => 'video', 'url' => $videoUrl, 'label' => 'Video (MP4)'],
        ];
        if ($thumbnail) {
            $items[] = ['type' => 'image', 'url' => $thumbnail, 'label' => 'Thumbnail'];
        }

        return [
            'success'    => true,
            'type'       => 'video',
            'title'      => $title,
            'thumbnail'  => $thumbnail,
            'video_url'  => $videoUrl,
            'duration'   => $duration,
            'items'      => $items,
            'source_url' => $url,
        ];
    }

    // ── Pick best URL from yt-dlp info dict ─────────────────
    private function pickBestUrl(array $info): ?string
    {
        // 1. Try requested_formats (combined streams have vcodec != none)
        if (!empty($info['requested_formats'])) {
            foreach ($info['requested_formats'] as $fmt) {
                if (!empty($fmt['url']) && ($fmt['vcodec'] ?? 'none') !== 'none') {
                    return $fmt['url'];
                }
            }
        }

        // 2. Try formats[] — pick highest-resolution mp4 with both video+audio
        if (!empty($info['formats'])) {
            $best = null;
            $bestHeight = 0;
            foreach ($info['formats'] as $fmt) {
                $hasVideo = ($fmt['vcodec'] ?? 'none') !== 'none';
                $hasAudio = ($fmt['acodec'] ?? 'none') !== 'none';
                $isMp4    = ($fmt['ext'] ?? '') === 'mp4';
                $height   = $fmt['height'] ?? 0;
                if ($hasVideo && $hasAudio && $isMp4 && $height > $bestHeight) {
                    $best = $fmt['url'];
                    $bestHeight = $height;
                }
            }
            if ($best) return $best;

            // Fallback: any format with video
            foreach (array_reverse($info['formats']) as $fmt) {
                if (!empty($fmt['url']) && ($fmt['vcodec'] ?? 'none') !== 'none') {
                    return $fmt['url'];
                }
            }
        }

        // 3. Direct url field
        return $info['url'] ?? null;
    }

    // ── Find yt-dlp binary ───────────────────────────────────
    private function ytdlpPath(): ?string
    {
        $candidates = [
            'C:\\Users\\DELL\\AppData\\Roaming\\Python\\Python314\\Scripts\\yt-dlp.exe',
            'yt-dlp',
            'yt-dlp.exe',
        ];
        foreach ($candidates as $p) {
            $ver = $this->runCmd([$p, '--version'], 5);
            if ($ver && preg_match('/\d{4}\.\d+/', $ver)) {
                return $p;
            }
        }
        return null;
    }

    // ── Safe subprocess runner ───────────────────────────────
    private function runCmd(array $cmd, int $timeout = 15): ?string
    {
        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];
        try {
            $proc = proc_open($cmd, $descriptors, $pipes);
            if (!is_resource($proc)) return null;

            fclose($pipes[0]);
            stream_set_timeout($pipes[1], $timeout);
            $stdout = stream_get_contents($pipes[1]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            proc_close($proc);

            return ($stdout && strlen($stdout) > 10) ? trim($stdout) : null;
        } catch (\Exception) {
            return null;
        }
    }

    // ── URL validation ───────────────────────────────────────
    private function isValidUrl(string $url): bool
    {
        // Accept any facebook.com or fb.watch URL that looks like a video/reel
        return (bool) preg_match(
            '/^https?:\/\/(www\.|m\.|web\.)?facebook\.com\/(reel|watch|share|[^\/\?]+\/videos)|^https?:\/\/fb\.watch\//i',
            $url
        );
    }
}
