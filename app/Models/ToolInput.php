<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToolInput extends Model
{
    protected $fillable = [
        'tool_id', 'field_name', 'field_label', 'field_type', 'placeholder',
        'default_value', 'required', 'options', 'validation', 'help_text', 'sort_order'
    ];

    protected function casts(): array
    {
        return [
            'required' => 'boolean',
            'options' => 'array',
            'validation' => 'array',
        ];
    }

    public function tool(): BelongsTo
    {
        return $this->belongsTo(Tool::class);
    }
}
