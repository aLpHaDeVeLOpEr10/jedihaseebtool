<?php

namespace App\Services;

use App\Models\Tool;

class FileToolEngine
{
    public function handle(array $data, Tool $tool): array
    {
        return [
            'success' => false,
            'error'   => 'File tool processing requires additional configuration.',
            'message' => 'Please contact admin to enable this tool.',
        ];
    }
}
