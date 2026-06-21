<?php

namespace App\Services;

class YoutubeDownloaderEngine
{
    // ─────────────────────────────────────────────────────────
    public function getInfo(array $data): array
    {
        $url = trim($data['url'] ?? '');

        if (empty($url)) {
            return ['success' => false, 'error' => 'Please enter a YouTube URL.'];
        }

        if (!$this->isValidUrl($url)) {
            return [
                'success' => false,
                'error'   => 'Please enter a valid YouTube URL. '
                           . 'Accepted: youtube.com/watch?v=…, youtube.com/shorts/…, youtu.be/…',
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
            '--socket-timeout', '15',
            $url,
        ], 35);

        if (!$json) {
            return [
                'success' => false,
                'error'   => 'Could not fetch video info. The video may be private, '
                           . 'age-restricted, or unavailable in your region.',
            ];
        }

        $firstLine = explode("\n", trim($json))[0];
        $info = json_decode($firstLine, true);
        if (!$info) {
            return ['success' => false, 'error' => 'Unexpected response from video service.'];
        }

        $formats  = $this->buildFormats($info['formats'] ?? []);
        $duration = $info['duration'] ?? 0;
        $isShort  = str_contains($url, '/shorts/') || ($duration > 0 && $duration <= 60);

        return [
            'success'   => true,
            'title'     => $info['title']       ?? 'YouTube Video',
            'thumbnail' => $info['thumbnail']   ?? null,
            'duration'  => $this->formatDuration($duration),
            'channel'   => $info['uploader']    ?? ($info['channel'] ?? null),
            'views'     => $info['view_count']  ?? null,
            'is_short'  => $isShort,
            'formats'   => $formats,
        ];
    }

    // ── Build format list visible to the user ────────────────
    // Only offer formats that produce a single output file without ffmpeg:
    //   • Combined video+audio streams (both vcodec and acodec present)
    //   • Audio-only streams
    // Video-only DASH streams are excluded because merging needs ffmpeg.
    private function buildFormats(array $raw): array
    {
        $combined  = [];  // keyed by height
        $audioOnly = [];  // keyed by abr

        foreach ($raw as $fmt) {
            $fmtId  = $fmt['format_id'] ?? '';
            $vcodec = $fmt['vcodec']    ?? 'none';
            $acodec = $fmt['acodec']    ?? 'none';
            $ext    = $fmt['ext']       ?? '';
            $height = $fmt['height']    ?? 0;
            $tbr    = $fmt['tbr']       ?? 0;
            $abr    = $fmt['abr']       ?? 0;
            $fs     = $fmt['filesize']  ?? $fmt['filesize_approx'] ?? null;

            $hasVideo = $vcodec !== 'none';
            $hasAudio = $acodec !== 'none';

            // Combined (video+audio) — usable without ffmpeg
            if ($hasVideo && $hasAudio && $height > 0 && in_array($ext, ['mp4', 'webm'], true)) {
                $existing = $combined[$height] ?? null;
                if (!$existing || $tbr > ($existing['tbr'] ?? 0)) {
                    $combined[$height] = [
                        'format_id' => $fmtId,
                        'quality'   => "{$height}p",
                        'ext'       => 'mp4',
                        'filesize'  => $fs,
                        'tbr'       => $tbr,
                        'type'      => 'video',
                        'label'     => "{$height}p MP4",
                        'icon'      => '🎬',
                    ];
                }
            }

            // Audio-only — select best per bitrate bucket, always prefer m4a over webm
            if (!$hasVideo && $hasAudio && in_array($ext, ['m4a', 'mp3', 'webm', 'ogg'], true)) {
                $bucket   = (int) ($abr / 50);
                $existing = $audioOnly[$bucket] ?? null;
                $existExt = $existing['ext'] ?? '';
                // Prefer m4a; only replace if better bitrate OR same-ish bitrate but upgrading to m4a
                $betterBitrate  = $abr > ($existing['abr'] ?? 0);
                $betterCodec    = $ext === 'm4a' && $existExt !== 'm4a';
                if (!$existing || $betterBitrate || ($betterCodec && $abr >= ($existing['abr'] ?? 0) * 0.95)) {
                    $audioOnly[$bucket] = [
                        'format_id' => $fmtId,
                        'quality'   => $abr ? round($abr) . ' kbps' : 'Best',
                        'ext'       => $ext === 'webm' ? 'opus' : $ext,
                        'filesize'  => $fs,
                        'abr'       => $abr,
                        'type'      => 'audio',
                        'label'     => 'Audio Only (' . strtoupper($ext === 'webm' ? 'OPUS' : $ext) . ')',
                        'icon'      => '🎵',
                    ];
                }
            }
        }

        // Sort combined formats by height descending (best quality first)
        krsort($combined);
        $result = array_values($combined);

        // Add best audio-only option
        if (!empty($audioOnly)) {
            usort($audioOnly, fn($a, $b) => $b['abr'] - $a['abr']);
            $result[] = $audioOnly[0];
        }

        // Fallback: if no combined format found, offer generic best
        if (empty($result)) {
            $result[] = [
                'format_id' => 'best[ext=mp4]/best',
                'quality'   => 'Best Available',
                'ext'       => 'mp4',
                'filesize'  => null,
                'type'      => 'video',
                'label'     => 'Best Quality',
                'icon'      => '🎬',
            ];
        }

        return $result;
    }

    // ── Helpers ──────────────────────────────────────────────
    private function formatDuration(int $s): string
    {
        if ($s <= 0) return '';
        $h = (int) ($s / 3600);
        $m = (int) (($s % 3600) / 60);
        $sec = $s % 60;
        return $h > 0
            ? sprintf('%d:%02d:%02d', $h, $m, $sec)
            : sprintf('%d:%02d', $m, $sec);
    }

    private function isValidUrl(string $url): bool
    {
        return (bool) preg_match(
            '/^https?:\/\/(www\.|m\.)?(youtube\.com\/(watch|shorts|embed|live)|youtu\.be\/)/i',
            $url
        );
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

    // Exposed for use by ToolController proxy
    public function findYtdlp(): ?string
    {
        return $this->ytdlpPath();
    }
}
