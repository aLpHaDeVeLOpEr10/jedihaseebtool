<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'icon', 'color',
        'is_active', 'sort_order', 'seo_title', 'seo_description',
        'seo_keywords', 'og_image',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function tools(): HasMany
    {
        return $this->hasMany(Tool::class);
    }

    public function activeTools(): HasMany
    {
        return $this->hasMany(Tool::class)->where('status', 'active');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getSeoTitleAttribute($value): string
    {
        return $value ?: $this->name . ' Tools - ' . Setting::get('site_name', 'JEDISEBITOOL');
    }

    public function getSeoDescriptionAttribute($value): string
    {
        return $value ?: ($this->description ?? 'Browse our collection of ' . $this->name . ' tools.');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
