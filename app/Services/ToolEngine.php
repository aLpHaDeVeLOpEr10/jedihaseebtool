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
