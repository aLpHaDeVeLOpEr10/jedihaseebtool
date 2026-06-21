<?php

namespace App\Services;

class TiktokDownloaderEngine
{
    // ─────────────────────────────────────────────────────────
    public function getInfo(array $data): array
    {
        $url = trim($data['url'] ?? '');

        if (empty($url)) {
            return ['success' => false, 'error' => 'Please enter a TikTok URL.'];
        }

        if (!$this->isValidUrl($url)) {
            return [
                'success' => false,
                'error'   => 'Please enter a valid TikTok URL. '
                           . 'Accepted: tiktok.com/@user/video/…, vm.tiktok.com/…, vt.tiktok.com/…',
            ];
        }

        $bin = $this->ytdlpPath();
        if (!$bin) {
            return ['success' => false, 'error' => 'Download service temporarily unavailable.'];
        }

        $json = $this->runCmd([
            $bin,
            '--dump-json',
            '--no-playlist',
            '--no-warnings',
            '--socket-timeout', '20',
            $url,
        ], 55);

        if (!$json) {
            return [
                'success' => false,
                'error'   => 'Could not fetch video info. The video may be private, deleted, or '
                           . 'unavailable in your region.',
            ];
        }

        $firstLine = explode("\n", trim($json))[0];
        $info = json_decode($firstLine, true);
        if (!$info) {
            return ['success' => false, 'error' => 'Unexpected response from video service.'];
        }

        $formats = $this->buildFormats($info['formats'] ?? []);

        return [
            'success'   => true,
            'title'     => $info['title'] ?? ($info['description'] ?? 'TikTok Video'),
            'thumbnail' => $info['thumbnail'] ?? null,
            'duration'  => $this->formatDuration((int)($info['duration'] ?? 0)),
            'author'    => $info['uploader'] ?? ($info['creator'] ?? ($info['channel'] ?? null)),
            'likes'     => $info['like_count'] ?? null,
            'formats'   => $formats,
        ];
    }

    // ── Build downloadable format list ───────────────────────
    private function buildFormats(array $raw): array
    {
        // Keep only combined video+audio MP4/WebM formats
        $combined = array_filter($raw, fn($f) =>
            ($f['vcodec'] ?? 'none') !== 'none' &&
            ($f['acodec'] ?? 'none') !== 'none' &&
            in_array($f['ext'] ?? '', ['mp4', 'webm'], true)
        );

        if (empty($combined)) {
            return [[
                'format_id' => 'best[ext=mp4]/best',
                'label'     => 'Best Quality (MP4)',
                'quality'   => 'HD',
                'ext'       => 'mp4',
                'filesize'  => null,
                'no_wm'     => false,
                'icon'      => '🎬',
            ]];
        }

        // Group by (height, no-watermark flag), keep best bitrate per group
        $groups = [];
        foreach ($combined as $fmt) {
            $h    = (int)($fmt['height'] ?? 0);
            $id   = $fmt['format_id'] ?? '';
            $isNw = (bool)preg_match('/download|no.?watermark/i', $id);
            $key  = $h . '_' . ($isNw ? 'nw' : 'std');

            if (!isset($groups[$key]) || ($fmt['tbr'] ?? 0) > ($groups[$key]['tbr'] ?? 0)) {
                $groups[$key] = array_merge($fmt, ['_no_wm' => $isNw]);
            }
        }

        // Sort: no-watermark first, then by height desc
        uasort($groups, function ($a, $b) {
            if ($a['_no_wm'] !== $b['_no_wm']) return $b['_no_wm'] <=> $a['_no_wm'];
            return ($b['height'] ?? 0) <=> ($a['height'] ?? 0);
        });

        $result = [];
        foreach (array_values($groups) as $i => $fmt) {
            if ($i >= 3) break;  // cap at 3 options
            $h     = (int)($fmt['height'] ?? 0);
            $isNw  = $fmt['_no_wm'];
            $label = ($h > 0 ? "{$h}p" : 'HD') . ($isNw ? ' · No Watermark' : '');
            $result[] = [
                'format_id' => $fmt['format_id'],
                'label'     => $label,
                'quality'   => $h > 0 ? "{$h}p" : 'HD',
                'ext'       => 'mp4',
                'filesize'  => $fmt['filesize'] ?? $fmt['filesize_approx'] ?? null,
                'no_wm'     => $isNw,
                'icon'      => '🎬',
            ];
        }

        return $result;
    }

    // ── Helpers ──────────────────────────────────────────────
    private function formatDuration(int $s): string
    {
        if ($s <= 0) return '';
        return sprintf('%d:%02d', (int)($s / 60), $s % 60);
    }

    private function isValidUrl(string $url): bool
    {
        return (bool)preg_match(
            '/^https?:\/\/(www\.|m\.|vm\.|vt\.)?tiktok\.com\//i',
            $url
        );
    }

    public function findYtdlp(): ?string
    {
        return $this->ytdlpPath();
    }

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

    private function runCmd(array $cmd, int $timeout = 15): ?string
    {
        $descriptors = [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
        try {
            $proc = proc_open($cmd, $descriptors, $pipes);
            if (!is_resource($proc)) return null;
            fclose($pipes[0]);
            stream_set_timeout($pipes[1], $timeout);
            $stdout = stream_get_contents($pipes[1]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            proc_close($proc);
            return ($stdout && strlen($stdout) > 5) ? trim($stdout) : null;
        } catch (\Exception) {
            return null;
        }
    }
}
