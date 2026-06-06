<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToolFaq extends Model
{
    protected $fillable = [
        'tool_id', 'question', 'answer', 'sort_order', 'is_visible'
    ];

    protected function casts(): array
    {
        return ['is_visible' => 'boolean'];
    }

    public function tool(): BelongsTo
    {
        return $this->belongsTo(Tool::class);
    }
}
