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
        $seoSettings->site_name = 'Forse Panel - Kurumsal Web Yönetim Sistemi';
        
        $seoSettings->site_description = 'Forse Panel ile web sitenizi kolayca yönetin. Modern, hızlı ve güvenli içerik yönetim sistemi.';
        
        // Nullable alanlar - başlangıçta boş bırakılabilir
        $seoSettings->og_image = null; // Kullanıcı admin panelinden yükleyecek
        
        // Google Analytics ID örneği
        $seoSettings->google_analytics_id = 'G-XXXXXXXXXX'; // Gerçek ID ile değiştirilmeli
        
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
