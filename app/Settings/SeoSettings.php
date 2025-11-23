<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SeoSettings extends Settings
{
    // Temel SEO Ayarları
    public string $site_name;
    public string $site_description;
    public ?string $site_keywords;
    public ?string $site_author;
    
    // Sosyal Medya (Open Graph)
    public ?string $og_image;
    public ?string $og_type;
    public ?string $og_locale;
    
    // Twitter Card
    public ?string $twitter_card_type;
    public ?string $twitter_site;
    public ?string $twitter_creator;
    
    // Analytics & Tracking
    public ?string $google_analytics_id;
    public ?string $google_tag_manager_id;
    public ?string $facebook_pixel_id;
    
    // Verification
    public ?string $google_site_verification;
    public ?string $bing_site_verification;
    public ?string $yandex_verification;
    
    // Custom Scripts
    public ?string $head_scripts;
    public ?string $body_scripts;
    public ?string $footer_scripts;
    
    // Robots & Indexing
    public bool $allow_search_engines;
    public ?string $robots_txt_additions;

    public static function group(): string
    {
        return 'seo';
    }
}
