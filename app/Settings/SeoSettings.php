<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SeoSettings extends Settings
{
    public string $site_name;
    public string $site_description;
    public ?string $og_image;
    public ?string $google_analytics_id;
    public ?string $head_scripts;
    public ?string $body_scripts;

    public static function group(): string
    {
        return 'seo';
    }
}
