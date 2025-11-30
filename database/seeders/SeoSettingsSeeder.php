<?php

namespace Database\Seeders;

use App\Settings\SeoSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeoSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Bu seeder, SEO ayarlarını varsayılan değerlerle doldurur.
     * Spatie Laravel Settings paketi kullanıldığı için, ayarlar 'settings' tablosuna
     * key-value çifti olarak kaydedilir.
     */
    public function run(): void
    {
        // SeoSettings instance'ı oluştur
        // Bu, app/Settings/SeoSettings.php sınıfından gelir
        $seoSettings = app(SeoSettings::class);
        
        // MANTIK 1: Direct Property Assignment
        // Settings class'ındaki public property'lere direkt değer atayabiliriz
        
        // Temel SEO Ayarları
        $seoSettings->site_name = 'Forse Panel - Kurumsal Web Yönetim Sistemi';
        $seoSettings->site_description = 'Forse Panel ile web sitenizi kolayca yönetin. Modern, hızlı ve güvenli içerik yönetim sistemi.';
        $seoSettings->site_keywords = null;
        $seoSettings->site_author = null;
        
        // Sosyal Medya (Open Graph)
        $seoSettings->og_image = null; // Kullanıcı admin panelinden yükleyecek
        $seoSettings->og_type = 'website';
        $seoSettings->og_locale = 'tr_TR';
        
        // Twitter Card
        $seoSettings->twitter_card_type = 'summary_large_image';
        $seoSettings->twitter_site = null;
        $seoSettings->twitter_creator = null;
        
        // Analytics & Tracking
        $seoSettings->google_analytics_id = 'G-XXXXXXXXXX'; // Gerçek ID ile değiştirilmeli
        $seoSettings->google_tag_manager_id = null;
        $seoSettings->facebook_pixel_id = null;
        
        // Verification
        $seoSettings->google_site_verification = null;
        $seoSettings->bing_site_verification = null;
        $seoSettings->yandex_verification = null;
        
        // Head Scripts - Google Tag Manager örneği
        $seoSettings->head_scripts = <<<'HTML'
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-XXXXXXX');</script>
<!-- End Google Tag Manager -->
HTML;
        
        // Body Scripts - Google Tag Manager (noscript) örneği
        $seoSettings->body_scripts = <<<'HTML'
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-XXXXXXX"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
HTML;
        
        // Footer Scripts
        $seoSettings->footer_scripts = null;
        
        // Robots & Indexing
        $seoSettings->allow_search_engines = true;
        $seoSettings->robots_txt_additions = null;
        
        // MANTIK 2: save() metodu ile veritabanına kaydet
        // Bu metod, tüm property'leri 'settings' tablosuna yazar
        // Her bir property, ayrı bir satır olarak 'group' ve 'name' ile kaydedilir
        // Örnek: group='seo', name='site_name', payload='{"value":"..."}'
        $seoSettings->save();
        
        // KONSOL ÇIKTISI
        $this->command->info('✅ SEO Ayarları başarıyla oluşturuldu!');
        $this->command->info('   - Site Adı: ' . $seoSettings->site_name);
        $this->command->info('   - Meta Açıklama: ' . substr($seoSettings->site_description, 0, 50) . '...');
    }
}
