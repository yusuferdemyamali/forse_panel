<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('seo.site_name', '');
        $this->migrator->add('seo.site_description', '');
        $this->migrator->add('seo.og_image', null);
        $this->migrator->add('seo.google_analytics_id', null);
        $this->migrator->add('seo.head_scripts', null);
        $this->migrator->add('seo.body_scripts', null);
    }
};
