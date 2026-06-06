<?php

namespace App\Services;

use App\Models\Tool;

class ProductivityEngine
{
    public function handle(array $data, Tool $tool): array
    {
        $slug = $tool->slug;

        return match (true) {
            str_contains($slug, 'todo') || str_contains($slug, 'to-do') => $this->todo($data),
            str_contains($slug, 'notes') || str_contains($slug, 'note') => $this->notes($data),
            default => ['success' => false, 'error' => 'Productivity tool not configured yet.'],
        };
    }

    public function todo(array $data): array
    {
        // Notes/Todos are handled client-side with localStorage/Alpine.js
        return [
            'success'     => true,
            'client_side' => true,
            'message'     => 'Todo tool runs client-side.',
        ];
    }

    public function notes(array $data): array
    {
        return [
            'success'     => true,
            'client_side' => true,
            'message'     => 'Notes tool runs client-side.',
        ];
    }
}
