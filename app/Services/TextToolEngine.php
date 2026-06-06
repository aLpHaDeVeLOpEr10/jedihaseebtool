<?php

namespace App\Services;

use App\Models\Tool;

class TextToolEngine
{
    public function handle(array $data, Tool $tool): array
    {
        $slug = $tool->slug;

        return match (true) {
            str_contains($slug, 'json')     => $this->jsonFormatter($data),
            str_contains($slug, 'summarize') || str_contains($slug, 'summarizer') => $this->textSummarizer($data),
            str_contains($slug, 'word-count') || str_contains($slug, 'word_count') => $this->wordCount($data),
            str_contains($slug, 'case')     => $this->caseConverter($data),
            default => $this->wordCount($data),
        };
    }

    public function jsonFormatter(array $data): array
    {
        $json   = trim($data['json'] ?? '');
        $indent = max(2, min(8, (int) ($data['indent'] ?? 4)));
        $action = $data['action'] ?? 'format'; // format, minify, validate

        if (empty($json)) {
            return ['success' => false, 'error' => 'Please enter JSON to process.'];
        }

        $decoded = json_decode($json);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'error'   => 'Invalid JSON: ' . json_last_error_msg(),
                'results' => [
                    ['label' => 'Status', 'value' => '❌ Invalid JSON'],
                    ['label' => 'Error', 'value' => json_last_error_msg()],
                ],
            ];
        }

        if ($action === 'minify') {
            $output = json_encode($decoded);
        } else {
            $output = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            // Apply custom indent
            if ($indent !== 4) {
                $output = preg_replace_callback('/^( +)/m', function ($m) use ($indent) {
                    $spaces = strlen($m[1]);
                    return str_repeat(' ', ($spaces / 4) * $indent);
                }, $output);
            }
        }

        $stats = $this->analyzeJson($decoded);

        return [
            'success' => true,
            'output'  => $output,
            'results' => [
                ['label' => 'Status',      'value' => '✅ Valid JSON', 'highlight' => true],
                ['label' => 'Size (raw)',   'value' => $this->formatBytes(strlen($json))],
                ['label' => 'Size (formatted)', 'value' => $this->formatBytes(strlen($output ?? ''))],
                ['label' => 'Keys',         'value' => $stats['keys']],
                ['label' => 'Arrays',       'value' => $stats['arrays']],
                ['label' => 'Depth',        'value' => $stats['depth']],
            ],
        ];
    }

    private function analyzeJson(mixed $data, int $depth = 0): array
    {
        static $keys = 0, $arrays = 0, $maxDepth = 0;
        $keys = $arrays = $maxDepth = 0;

        $analyze = function ($item, $d) use (&$analyze, &$keys, &$arrays, &$maxDepth) {
            $maxDepth = max($maxDepth, $d);
            if (is_object($item)) {
                foreach ((array)$item as $k => $v) {
                    $keys++;
                    $analyze($v, $d + 1);
                }
            } elseif (is_array($item)) {
                $arrays++;
                foreach ($item as $v) {
                    $analyze($v, $d + 1);
                }
            }
        };

        $analyze($data, 0);
        return compact('keys', 'arrays', 'maxDepth') + ['depth' => $maxDepth];
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 1) . ' MB';
    }

    public function textSummarizer(array $data): array
    {
        $text    = trim($data['text'] ?? '');
        $ratio   = max(0.1, min(0.9, (float) ($data['ratio'] ?? 0.3)));
        $method  = $data['method'] ?? 'extractive';

        if (strlen($text) < 50) {
            return ['success' => false, 'error' => 'Text is too short to summarize. Please enter at least 50 characters.'];
        }

        // Split into sentences
        $sentences = preg_split('/(?<=[.!?])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $wordCount = str_word_count($text);

        if (count($sentences) < 2) {
            return ['success' => false, 'error' => 'Please enter multiple sentences for summarization.'];
        }

        // Score sentences by word frequency (extractive summarization)
        $words = array_map('strtolower', str_word_count($text, 1));
        $stopWords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'from', 'is', 'it', 'this', 'that', 'was', 'are', 'as', 'be', 'been', 'being', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'could', 'should', 'may', 'might'];
        $wordFreq = array_count_values(array_diff($words, $stopWords));

        $sentenceScores = [];
        foreach ($sentences as $i => $sentence) {
            $sentWords = array_map('strtolower', str_word_count($sentence, 1));
            $score = 0;
            foreach ($sentWords as $word) {
                $score += $wordFreq[$word] ?? 0;
            }
            // Prefer sentences near the beginning
            $posScore = 1 - ($i / count($sentences)) * 0.3;
            $sentenceScores[$i] = $score * $posScore;
        }

        arsort($sentenceScores);
        $keepCount = max(1, (int) ceil(count($sentences) * $ratio));
        $topIndices = array_slice(array_keys($sentenceScores), 0, $keepCount);
        sort($topIndices);

        $summary = implode(' ', array_map(fn($i) => $sentences[$i], $topIndices));

        return [
            'success' => true,
            'summary' => $summary,
            'results' => [
                ['label' => 'Summary', 'value' => $summary, 'highlight' => true],
                ['label' => 'Original Length',  'value' => $wordCount . ' words'],
                ['label' => 'Summary Length',   'value' => str_word_count($summary) . ' words'],
                ['label' => 'Compression',      'value' => round((1 - str_word_count($summary) / $wordCount) * 100) . '%'],
                ['label' => 'Sentences',        'value' => $keepCount . ' of ' . count($sentences)],
            ],
        ];
    }

    public function wordCount(array $data): array
    {
        $text = $data['text'] ?? '';

        if (empty(trim($text))) {
            return ['success' => false, 'error' => 'Please enter some text.'];
        }

        $words     = str_word_count($text);
        $chars     = strlen($text);
        $charsNoSp = strlen(preg_replace('/\s/', '', $text));
        $sentences = preg_match_all('/[.!?]+/', $text);
        $paragraphs = count(array_filter(preg_split('/\n\n+/', trim($text))));
        $readTime  = max(1, ceil($words / 200));

        return [
            'success' => true,
            'results' => [
                ['label' => 'Words',               'value' => number_format($words), 'highlight' => true],
                ['label' => 'Characters',          'value' => number_format($chars)],
                ['label' => 'Characters (no spaces)', 'value' => number_format($charsNoSp)],
                ['label' => 'Sentences',           'value' => $sentences],
                ['label' => 'Paragraphs',          'value' => $paragraphs],
                ['label' => 'Reading Time',        'value' => $readTime . ' min'],
            ],
        ];
    }

    public function caseConverter(array $data): array
    {
        $text = $data['text'] ?? '';

        if (empty(trim($text))) {
            return ['success' => false, 'error' => 'Please enter some text.'];
        }

        return [
            'success' => true,
            'results' => [
                ['label' => 'UPPERCASE',     'value' => strtoupper($text), 'copyable' => true],
                ['label' => 'lowercase',     'value' => strtolower($text), 'copyable' => true],
                ['label' => 'Title Case',    'value' => ucwords(strtolower($text)), 'copyable' => true],
                ['label' => 'Sentence case', 'value' => ucfirst(strtolower($text)), 'copyable' => true],
                ['label' => 'camelCase',     'value' => lcfirst(str_replace(' ', '', ucwords(strtolower($text)))), 'copyable' => true],
                ['label' => 'snake_case',    'value' => strtolower(str_replace(' ', '_', $text)), 'copyable' => true],
                ['label' => 'kebab-case',    'value' => strtolower(str_replace(' ', '-', $text)), 'copyable' => true],
            ],
        ];
    }
}
