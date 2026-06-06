<?php

namespace App\Services;

use App\Models\Tool;

class GeneratorEngine
{
    public function handle(array $data, Tool $tool): array
    {
        $slug = $tool->slug;

        return match (true) {
            str_contains($slug, 'password')  => $this->password($data),
            str_contains($slug, 'qr-code') || str_contains($slug, 'qr_code') => $this->qrCode($data),
            str_contains($slug, 'color-palette') || str_contains($slug, 'color_palette') => $this->colorPalette($data),
            default => ['success' => false, 'error' => 'Generator not implemented for this tool.'],
        };
    }

    public function password(array $data): array
    {
        $length     = max(4, min(128, (int) ($data['length'] ?? 16)));
        $uppercase  = (bool) ($data['uppercase'] ?? true);
        $lowercase  = (bool) ($data['lowercase'] ?? true);
        $numbers    = (bool) ($data['numbers'] ?? true);
        $symbols    = (bool) ($data['symbols'] ?? true);
        $count      = max(1, min(20, (int) ($data['count'] ?? 1)));
        $exclude    = $data['exclude'] ?? '';

        $chars = '';
        if ($uppercase) $chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if ($lowercase) $chars .= 'abcdefghijklmnopqrstuvwxyz';
        if ($numbers)   $chars .= '0123456789';
        if ($symbols)   $chars .= '!@#$%^&*()_+-=[]{}|;:,.<>?';

        // Remove excluded characters
        if ($exclude) {
            $chars = str_replace(str_split($exclude), '', $chars);
        }

        if (empty($chars)) {
            return ['success' => false, 'error' => 'Please select at least one character type.'];
        }

        $passwords = [];
        for ($p = 0; $p < $count; $p++) {
            $password = '';
            $charLen  = strlen($chars);

            // Ensure at least one of each required type
            $required = [];
            if ($uppercase && strpbrk($chars, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ') !== false) {
                $upperChars = preg_replace('/[^A-Z]/', '', $chars);
                $required[] = $upperChars[random_int(0, strlen($upperChars) - 1)];
            }
            if ($lowercase && strpbrk($chars, 'abcdefghijklmnopqrstuvwxyz') !== false) {
                $lowerChars = preg_replace('/[^a-z]/', '', $chars);
                $required[] = $lowerChars[random_int(0, strlen($lowerChars) - 1)];
            }
            if ($numbers && strpbrk($chars, '0123456789') !== false) {
                $numChars = preg_replace('/[^0-9]/', '', $chars);
                $required[] = $numChars[random_int(0, strlen($numChars) - 1)];
            }

            for ($i = count($required); $i < $length; $i++) {
                $password .= $chars[random_int(0, $charLen - 1)];
            }

            // Shuffle required into password
            $password = str_split($password + $required);
            shuffle($password);
            $passwords[] = implode('', array_slice(array_merge($required, str_split(implode('', $password))), 0, $length));
        }

        return [
            'success'   => true,
            'passwords' => $passwords,
            'results'   => [
                ['label' => 'Password', 'value' => $passwords[0], 'highlight' => true, 'copyable' => true],
                ['label' => 'Length', 'value' => $length],
                ['label' => 'Strength', 'value' => $this->passwordStrength($length, $uppercase, $lowercase, $numbers, $symbols)],
            ],
        ];
    }

    private function passwordStrength(int $length, bool $upper, bool $lower, bool $numbers, bool $symbols): string
    {
        $score = 0;
        if ($length >= 8)  $score++;
        if ($length >= 12) $score++;
        if ($length >= 16) $score++;
        if ($upper)   $score++;
        if ($lower)   $score++;
        if ($numbers) $score++;
        if ($symbols) $score++;

        return match (true) {
            $score >= 6 => '💪 Very Strong',
            $score >= 4 => '✅ Strong',
            $score >= 3 => '⚠️ Medium',
            default     => '❌ Weak',
        };
    }

    public function qrCode(array $data): array
    {
        $text  = trim($data['text'] ?? '');
        $size  = max(100, min(500, (int) ($data['size'] ?? 300)));
        $color = ltrim($data['color'] ?? '000000', '#');
        $bg    = ltrim($data['background'] ?? 'ffffff', '#');

        if (empty($text)) {
            return ['success' => false, 'error' => 'Please enter text or URL to encode.'];
        }

        // Use Google Charts API for QR generation (no server-side library needed)
        $encodedText = urlencode($text);
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data={$encodedText}&color={$color}&bgcolor={$bg}&format=png&qzone=1&margin=10";

        return [
            'success'  => true,
            'qr_url'   => $qrUrl,
            'download' => $qrUrl . '&format=png',
            'results'  => [
                ['label' => 'Content', 'value' => strlen($text) > 50 ? substr($text, 0, 50) . '...' : $text, 'highlight' => true],
                ['label' => 'Size', 'value' => "{$size}×{$size} px"],
                ['label' => 'Characters', 'value' => strlen($text)],
            ],
        ];
    }

    public function colorPalette(array $data): array
    {
        $base    = ltrim($data['base_color'] ?? '#6366f1', '#');
        $mode    = $data['mode'] ?? 'analogous'; // analogous, complementary, triadic, monochromatic
        $count   = max(3, min(10, (int) ($data['count'] ?? 5)));

        // Parse hex to RGB
        [$r, $g, $b] = $this->hexToRgb($base);
        [$h, $s, $l] = $this->rgbToHsl($r, $g, $b);

        $colors = match ($mode) {
            'complementary'  => $this->complementary($h, $s, $l, $count),
            'triadic'        => $this->triadic($h, $s, $l, $count),
            'monochromatic'  => $this->monochromatic($h, $s, $l, $count),
            'split-complementary' => $this->splitComplementary($h, $s, $l, $count),
            default          => $this->analogous($h, $s, $l, $count),
        };

        return [
            'success' => true,
            'palette' => $colors,
            'results' => [
                ['label' => 'Base Color', 'value' => '#' . $base, 'highlight' => true],
                ['label' => 'Mode', 'value' => ucfirst($mode)],
                ['label' => 'Colors Generated', 'value' => count($colors)],
            ],
        ];
    }

    private function hexToRgb(string $hex): array
    {
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }

    private function rgbToHsl(int $r, int $g, int $b): array
    {
        $r /= 255; $g /= 255; $b /= 255;
        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $l   = ($max + $min) / 2;
        $d   = $max - $min;

        if ($d == 0) {
            $h = $s = 0;
        } else {
            $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
            $h = match ($max) {
                $r => (($g - $b) / $d + ($g < $b ? 6 : 0)) / 6,
                $g => (($b - $r) / $d + 2) / 6,
                $b => (($r - $g) / $d + 4) / 6,
                default => 0,
            };
        }

        return [$h * 360, $s, $l];
    }

    private function hslToHex(float $h, float $s, float $l): string
    {
        $h /= 360;
        if ($s == 0) {
            $r = $g = $b = $l;
        } else {
            $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
            $p = 2 * $l - $q;
            $r = $this->hueToRgb($p, $q, $h + 1/3);
            $g = $this->hueToRgb($p, $q, $h);
            $b = $this->hueToRgb($p, $q, $h - 1/3);
        }
        return sprintf('#%02x%02x%02x', round($r * 255), round($g * 255), round($b * 255));
    }

    private function hueToRgb(float $p, float $q, float $t): float
    {
        if ($t < 0) $t += 1;
        if ($t > 1) $t -= 1;
        if ($t < 1/6) return $p + ($q - $p) * 6 * $t;
        if ($t < 1/2) return $q;
        if ($t < 2/3) return $p + ($q - $p) * (2/3 - $t) * 6;
        return $p;
    }

    private function analogous(float $h, float $s, float $l, int $count): array
    {
        $colors = [];
        $step = 30;
        for ($i = 0; $i < $count; $i++) {
            $hue = fmod($h - (($count / 2) * $step) + ($i * $step) + 360, 360);
            $colors[] = ['hex' => $this->hslToHex($hue, $s, $l), 'hsl' => "hsl({$hue}, " . round($s*100) . "%, " . round($l*100) . "%)"];
        }
        return $colors;
    }

    private function complementary(float $h, float $s, float $l, int $count): array
    {
        $colors = [];
        $main = $this->hslToHex($h, $s, $l);
        $comp = $this->hslToHex(fmod($h + 180, 360), $s, $l);
        $colors[] = ['hex' => $main, 'hsl' => "hsl({$h}, " . round($s*100) . "%, " . round($l*100) . "%)"];
        for ($i = 1; $i < $count - 1; $i++) {
            $newL = $l * (0.5 + $i * 0.2);
            $colors[] = ['hex' => $this->hslToHex($h, $s, min(0.9, $newL)), 'hsl' => "hsl({$h}, ...)"];
        }
        $colors[] = ['hex' => $comp, 'hsl' => "hsl(" . fmod($h+180,360) . ", " . round($s*100) . "%, " . round($l*100) . "%)"];
        return array_slice($colors, 0, $count);
    }

    private function triadic(float $h, float $s, float $l, int $count): array
    {
        $colors = [];
        for ($i = 0; $i < $count; $i++) {
            $hue = fmod($h + ($i * 360 / $count), 360);
            $colors[] = ['hex' => $this->hslToHex($hue, $s, $l), 'hsl' => "hsl({$hue}, " . round($s*100) . "%, " . round($l*100) . "%)"];
        }
        return $colors;
    }

    private function monochromatic(float $h, float $s, float $l, int $count): array
    {
        $colors = [];
        for ($i = 0; $i < $count; $i++) {
            $newL = 0.1 + ($i / ($count - 1)) * 0.8;
            $colors[] = ['hex' => $this->hslToHex($h, $s, $newL), 'hsl' => "hsl({$h}, " . round($s*100) . "%, " . round($newL*100) . "%)"];
        }
        return $colors;
    }

    private function splitComplementary(float $h, float $s, float $l, int $count): array
    {
        $colors = [];
        $angles = [0, 150, 210];
        for ($i = 0; $i < $count; $i++) {
            $angle = $angles[$i % 3];
            $hue   = fmod($h + $angle, 360);
            $colors[] = ['hex' => $this->hslToHex($hue, $s, $l), 'hsl' => "hsl({$hue}, ...)"];
        }
        return $colors;
    }
}
