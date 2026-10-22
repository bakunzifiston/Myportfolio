<?php

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Throwable;

/**
 * Central access point for editable profile settings: config defaults overlaid with database values.
 */
class Settings
{
    private const CACHE_KEY = 'portfolio.settings';

    /** @return array<string, array<string, mixed>> Flat map of key => definition. */
    public static function definitions(): array
    {
        return collect(config('portfolio.settings'))->flatMap(fn ($group) => $group)->all();
    }

    /** @return array<string, mixed> */
    public static function all(): array
    {
        // Resolved once per application instance (i.e. per request), never shared between requests.
        if (app()->bound(self::CACHE_KEY)) {
            return app(self::CACHE_KEY);
        }

        $defaults = collect(static::definitions())->map(fn ($def) => $def['default'] ?? null)->all();
        $resolved = array_merge($defaults, static::stored());
        app()->instance(self::CACHE_KEY, $resolved);

        return $resolved;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $value = Arr::get(static::all(), $key);

        return filled($value) ? $value : $default;
    }

    /** Public URL for a file-type setting, or null when nothing has been uploaded. */
    public static function fileUrl(string $key): ?string
    {
        $path = static::get($key);

        return $path && Storage::disk('public')->exists($path) ? Storage::disk('public')->url($path) : null;
    }

    /** @return array<int, string> Non-empty lines of a textarea setting. */
    public static function lines(string $key): array
    {
        return collect(preg_split('/\R/', (string) static::get($key, '')))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    /** @return array<int, string> Paragraphs separated by blank lines. */
    public static function paragraphs(string $key): array
    {
        return collect(preg_split('/\R\s*\R/', (string) static::get($key, '')))
            ->map(fn ($p) => trim($p))
            ->filter()
            ->values()
            ->all();
    }

    public static function flush(): void
    {
        app()->forgetInstance(self::CACHE_KEY);
        Cache::forget(self::CACHE_KEY);
    }

    /** @return array<string, mixed> */
    private static function stored(): array
    {
        try {
            return Cache::rememberForever(self::CACHE_KEY, function () {
                if (! Schema::hasTable('site_settings')) {
                    return [];
                }

                return SiteSetting::query()->pluck('value', 'key')->filter(fn ($v) => $v !== null)->all();
            });
        } catch (Throwable $e) {
            // The site should still render its defaults if the database is briefly unavailable.
            report($e);

            return [];
        }
    }
}
