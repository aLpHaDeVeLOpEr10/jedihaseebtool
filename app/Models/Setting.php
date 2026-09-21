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
        if (array_key_exists($key, static::$cache)) {
            return static::$cache[$key] ?? $default;
        }

        // Settings change rarely and are read several times per request, so
        // they are cached across requests too. set() forgets the key.
        $value = \Illuminate\Support\Facades\Cache::rememberForever(
            'settings.' . $key,
            function () use ($key) {
                $setting = static::where('key', $key)->first();

                if (!$setting) {
                    return ['missing' => true];
                }

                return ['value' => match ($setting->type) {
                    'boolean' => (bool) $setting->value,
                    'integer' => (int) $setting->value,
                    'json'    => json_decode($setting->value, true),
                    default   => $setting->value,
                }];
            }
        );

        if (!is_array($value) || array_key_exists('missing', $value)) {
            static::$cache[$key] = null;

            return $default;
        }

        static::$cache[$key] = $value['value'];

        return $value['value'];
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
