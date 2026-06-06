<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group', 'label', 'description'];

    protected static array $cache = [];

    public static function get(string $key, mixed $default = null): mixed
    {
        if (isset(static::$cache[$key])) {
            return static::$cache[$key];
        }

        $setting = static::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        $value = match ($setting->type) {
            'boolean' => (bool) $setting->value,
            'integer' => (int) $setting->value,
            'json' => json_decode($setting->value, true),
            default => $setting->value,
        };

        static::$cache[$key] = $value;

        return $value;
    }

    public static function set(string $key, mixed $value, string $type = 'string'): static
    {
        $storedValue = is_array($value) ? json_encode($value) : $value;

        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => $storedValue, 'type' => $type]
        );

        static::$cache[$key] = $value;
        Cache::forget('settings.' . $key);

        return $setting;
    }

    public static function flushCache(): void
    {
        static::$cache = [];
    }

    public static function allByGroup(): array
    {
        return static::all()->groupBy('group')->toArray();
    }
}
