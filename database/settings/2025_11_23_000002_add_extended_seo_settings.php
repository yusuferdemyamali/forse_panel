<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Yeni SEO alanlarını ekle
        $this->migrator->add('seo.site_keywords', null);
        $this->migrator->add('seo.site_author', 'Ares Asansör');
        
        // Open Graph
        $this->migrator->add('seo.og_type', 'website');
        $this->migrator->add('seo.og_locale', 'tr_TR');
        
        // Twitter Card
        $this->migrator->add('seo.twitter_card_type', 'summary_large_image');
        $this->migrator->add('seo.twitter_site', null);
        $this->migrator->add('seo.twitter_creator', null);
        
        // Analytics & Tracking
        $this->migrator->add('seo.google_tag_manager_id', null);
        $this->migrator->add('seo.facebook_pixel_id', null);
        
        // Verification
        $this->migrator->add('seo.google_site_verification', null);
        $this->migrator->add('seo.bing_site_verification', null);
        $this->migrator->add('seo.yandex_verification', null);
        
        // Additional Scripts
        $this->migrator->add('seo.footer_scripts', null);
        
        // Robots
        $this->migrator->add('seo.allow_search_engines', true);
        $this->migrator->add('seo.robots_txt_additions', null);
    }
    
    public function down(): void
    {
        $this->migrator->delete('seo.site_keywords');
        $this->migrator->delete('seo.site_author');
        $this->migrator->delete('seo.og_type');
        $this->migrator->delete('seo.og_locale');
        $this->migrator->delete('seo.twitter_card_type');
        $this->migrator->delete('seo.twitter_site');
        $this->migrator->delete('seo.twitter_creator');
        $this->migrator->delete('seo.google_tag_manager_id');
        $this->migrator->delete('seo.facebook_pixel_id');
        $this->migrator->delete('seo.google_site_verification');
        $this->migrator->delete('seo.bing_site_verification');
        $this->migrator->delete('seo.yandex_verification');
        $this->migrator->delete('seo.footer_scripts');
        $this->migrator->delete('seo.allow_search_engines');
        $this->migrator->delete('seo.robots_txt_additions');
    }
};
