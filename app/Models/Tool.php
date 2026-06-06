<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Tool extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'slug', 'short_description', 'long_description',
        'icon', 'color', 'status', 'is_featured', 'tool_type', 'blade_path',
        'input_schema', 'output_schema', 'engine_class', 'engine_method',
        'seo_title', 'seo_description', 'seo_keywords', 'og_image', 'canonical_url',
        'has_custom_blade', 'view_count', 'use_count', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'has_custom_blade' => 'boolean',
            'input_schema' => 'array',
            'output_schema' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($tool) {
            if (empty($tool->slug)) {
                $tool->slug = Str::slug($tool->name);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function contents(): HasMany
    {
        return $this->hasMany(ToolContent::class)->orderBy('sort_order');
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(ToolFaq::class)->orderBy('sort_order');
    }

    public function inputs(): HasMany
    {
        return $this->hasMany(ToolInput::class)->orderBy('sort_order');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getSeoTitleAttribute($value): string
    {
        return $value ?: $this->name . ' - ' . Setting::get('site_name', 'JEDISEBITOOL');
    }

    public function getSeoDescriptionAttribute($value): string
    {
        return $value ?: ($this->short_description ?? 'Use our free ' . $this->name . ' online tool.');
    }

    public function getUrlAttribute(): string
    {
        return route('tools.show', $this->slug);
    }

    public function getBladeViewAttribute(): string
    {
        $customPath = resource_path('views/tools/generated/' . $this->slug . '.blade.php');
        if ($this->has_custom_blade && file_exists($customPath)) {
            return 'tools.generated.' . $this->slug;
        }
        return 'public.tools.dynamic';
    }

    public function incrementViews(): void
    {
        $this->increment('view_count');
    }

    public function incrementUses(): void
    {
        $this->increment('use_count');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('tool_type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function getRelatedTools(int $limit = 4)
    {
        return static::active()
            ->where('category_id', $this->category_id)
            ->where('id', '!=', $this->id)
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }
}
