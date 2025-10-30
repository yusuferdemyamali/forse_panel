<?php

namespace App\Models;

use App\Services\CacheService;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'site_domain',
        'site_logo',
        'site_favicon',
        'is_maintenance',
    ];

    protected $casts = [
        'is_maintenance' => 'boolean',
    ];

    /**
     * Cache'li site ayarlarını getir
     */
    public static function getCachedSettings()
    {
        $key = CacheService::generateKey('site_settings');

        return CacheService::remember(
            $key,
            CacheService::LONG_TTL, // Site ayarları çok nadiren değişir
            function () {
                return static::first() ?? new static;
            }
        );
    }

    /**
     * Cache'li belirli bir ayarı getir
     */
    public static function getCachedSetting(string $key, $default = null)
    {
        $cacheKey = CacheService::generateKey('site_setting', $key);

        return CacheService::remember(
            $cacheKey,
            CacheService::LONG_TTL,
            function () use ($key, $default) {
                $settings = static::first();

                return $settings ? ($settings->{$key} ?? $default) : $default;
            }
        );
    }

    /**
     * Cache'li bakım modu durumunu kontrol et
     */
    public static function getCachedMaintenanceStatus(): bool
    {
        $key = CacheService::generateKey('maintenance_status');

        return CacheService::remember(
            $key,
            CacheService::SHORT_TTL, // Bakım modu sık kontrol edilebilir
            function () {
                $settings = static::first();

                return $settings ? (bool) $settings->is_maintenance : false;
            }
        );
    }
}
