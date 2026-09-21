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
        'seo_title', 'seo_description', 'seo_keywords',
        'og_image', 'og_title', 'og_description',
        'twitter_title', 'twitter_description',
        'canonical_url', 'robots', 'schema_markup',
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

            // Guarantee uniqueness: a duplicate slug would make one of the two
            // tools unreachable and split any ranking signal between them.
            $base = $tool->slug;
            $i = 2;
            while (static::withTrashed()->where('slug', $tool->slug)->exists()) {
                $tool->slug = $base . '-' . $i++;
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
        $suffix = Setting::get('seo_title_suffix', '');

        if ($value) {
            return $suffix ? trim($value . ' ' . $suffix) : $value;
        }

        if ($suffix) {
            return trim($this->name . ' ' . $suffix);
        }

        return $this->name . ' - ' . Setting::get('site_name', 'Toolsearch');
    }

    public function getSeoDescriptionAttribute($value): string
    {
        if ($value) {
            return $value;
        }

        if ($this->short_description) {
            return $this->short_description;
        }

        return Setting::get('seo_default_description', 'Use our free ' . $this->name . ' online tool.');
    }

    // Returns the robots meta value, defaulting to index,follow when not set.
    public function getRobotsMetaAttribute(): string
    {
        return $this->getRawOriginal('robots') ?: 'index, follow';
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

    /**
     * Related tools from the same category.
     *
     * Deterministic on purpose: a stable internal link graph is what crawlers
     * need. Ordered by popularity, then name, so the same page always links to
     * the same siblings. Falls back to other categories when the category is
     * too small to fill the row, so no tool is left a dead end.
     */
    public function getRelatedTools(int $limit = 4)
    {
        $related = static::active()
            ->where('category_id', $this->category_id)
            ->where('id', '!=', $this->id)
            ->orderByDesc('view_count')
            ->orderBy('name')
            ->limit($limit)
            ->get();

        if ($related->count() < $limit) {
            $filler = static::active()
                ->where('id', '!=', $this->id)
                ->whereNotIn('id', $related->pluck('id'))
                ->orderByDesc('view_count')
                ->orderBy('name')
                ->limit($limit - $related->count())
                ->get();

            $related = $related->concat($filler);
        }

        return $related;
    }
}
