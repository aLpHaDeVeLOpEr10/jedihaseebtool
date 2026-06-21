<?php

namespace App\Services;

use App\Models\Tool;
use Illuminate\Http\Request;

class ToolEngine
{
    protected array $engines = [
        'calculator' => CalculatorEngine::class,
        'converter'  => ConverterEngine::class,
        'generator'  => GeneratorEngine::class,
        'text'       => TextToolEngine::class,
        'file'       => FileToolEngine::class,
        'productivity' => ProductivityEngine::class,
    ];

    public function process(Tool $tool, Request $request): array
    {
        // ── Python compiler — dedicated real-execution engine ──
        if ($tool->slug === 'python-compiler') {
            return app(PythonEngine::class)->execute($request->all());
        }

        // ── Instagram downloader tools — server-side media extraction ──
        if (in_array($tool->slug, ['instagram-downloader', 'instagram-reels-and-stories-downloader'], true)) {
            return app(InstagramDownloaderEngine::class)->download($request->all());
        }

        // ── Facebook downloader tool ──────────────────────────────────
        if ($tool->slug === 'facebook-reels-and-stories-downloader') {
            return app(FacebookDownloaderEngine::class)->download($request->all());
        }

        // ── YouTube downloader ────────────────────────────────────────
        if ($tool->slug === 'youtube-video-and-shorts-downloader') {
            return app(YoutubeDownloaderEngine::class)->getInfo($request->all());
        }

        // ── TikTok downloader ─────────────────────────────────────────
        if ($tool->slug === 'tiktok-videos-downloader') {
            return app(TiktokDownloaderEngine::class)->getInfo($request->all());
        }

        // Check if a specific engine method is configured
        if ($tool->engine_class && $tool->engine_method) {
            $engine = app($tool->engine_class);
            $method = $tool->engine_method;
            if (method_exists($engine, $method)) {
                return $engine->$method($request->all(), $tool);
            }
        }

        // Dispatch to engine based on tool_type
        $engineClass = $this->engines[$tool->tool_type] ?? null;
        if ($engineClass) {
            /** @var BaseEngine $engine */
            $engine = app($engineClass);
            return $engine->handle($request->all(), $tool);
        }

        return [
            'success' => false,
            'error' => 'No engine configured for this tool type: ' . $tool->tool_type,
        ];
    }

    public function getEngine(string $type): ?object
    {
        $class = $this->engines[$type] ?? null;
        return $class ? app($class) : null;
    }
}
