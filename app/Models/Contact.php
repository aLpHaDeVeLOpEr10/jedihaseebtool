<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name', 'email', 'subject', 'message', 'is_read', 'is_replied', 'ip_address'
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'is_replied' => 'boolean',
        ];
    }
}
