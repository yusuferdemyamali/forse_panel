<?php

namespace Database\Seeders;

use App\Models\Redirect;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RedirectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Bu seeder, örnek URL yönlendirmelerini oluşturur.
     * SEO için eski URL'lerden yeni URL'lere 301/302 yönlendirmeleri yapar.
     */
    public function run(): void
    {
        // MANTIK 1: Tek tek kayıt oluşturma
        // Her bir Redirect nesnesi, bir yönlendirme kuralını temsil eder
        
        // Örnek 1: Blog kategorisi değişti - 301 (Kalıcı)
        Redirect::create([
            'source_url' => '/eski-blog',
            'destination_url' => '/blog',
            'status_code' => 301,  // 301 = Kalıcı yönlendirme (SEO için ideal)
            'is_active' => true,
        ]);
        
        // Örnek 2: Ürün sayfası taşındı - 301
        Redirect::create([
            'source_url' => '/urunler/eski-urun',
            'destination_url' => '/urunler/yeni-urun',
            'status_code' => 301,
            'is_active' => true,
        ]);
        
        // Örnek 3: Geçici kampanya yönlendirmesi - 302 (Geçici)
        Redirect::create([
            'source_url' => '/kampanya',
            'destination_url' => '/urunler?kampanya=yaz2025',
            'status_code' => 302,  // 302 = Geçici yönlendirme
            'is_active' => true,
        ]);
        
        // Örnek 4: Harici siteye yönlendirme
        Redirect::create([
            'source_url' => '/destek',
            'destination_url' => 'https://destek.example.com',
            'status_code' => 301,
            'is_active' => true,
        ]);
        
        // Örnek 5: Pasif yönlendirme (aktif değil)
        Redirect::create([
            'source_url' => '/test-sayfa',
            'destination_url' => '/anasayfa',
            'status_code' => 301,
            'is_active' => false,  // Pasif - çalışmayacak
        ]);
        
        // MANTIK 2: Çoklu kayıt oluşturma (Array ile)
        // Çok sayıda yönlendirme varsa, array kullanarak toplu oluşturabilirsiniz
        $redirects = [
            [
                'source_url' => '/hakkimizda-eski',
                'destination_url' => '/hakkimizda',
                'status_code' => 301,
                'is_active' => true,
            ],
            [
                'source_url' => '/iletisim-eski',
                'destination_url' => '/iletisim',
                'status_code' => 301,
                'is_active' => true,
            ],
            [
                'source_url' => '/hizmetler/web-tasarim',
                'destination_url' => '/hizmetlerimiz/web-tasarim',
                'status_code' => 301,
                'is_active' => true,
            ],
        ];
        
        // Her bir redirect için kayıt oluştur
        foreach ($redirects as $redirect) {
            Redirect::create($redirect);
        }
        
        // MANTIK 3: updateOrCreate - Varsa güncelle, yoksa oluştur
        // Seeder'ı birden fazla çalıştırsak bile aynı kayıtları tekrar oluşturmaz
        Redirect::updateOrCreate(
            ['source_url' => '/promo'],  // Bu URL'yi ara
            [
                'destination_url' => '/kampanyalar',
                'status_code' => 302,
                'is_active' => true,
            ]
        );
        
        // KONSOL ÇIKTISI
        $totalRedirects = Redirect::count();
        $activeRedirects = Redirect::where('is_active', true)->count();
        
        $this->command->info('✅ Yönlendirmeler başarıyla oluşturuldu!');
        $this->command->info("   - Toplam Yönlendirme: {$totalRedirects}");
        $this->command->info("   - Aktif Yönlendirme: {$activeRedirects}");
        $this->command->info("   - Pasif Yönlendirme: " . ($totalRedirects - $activeRedirects));
        
        // Örnek yönlendirme listele
        $this->command->newLine();
        $this->command->info('📋 Oluşturulan Yönlendirmeler:');
        Redirect::where('is_active', true)->get()->each(function ($redirect) {
            $statusText = $redirect->status_code == 301 ? '301 (Kalıcı)' : '302 (Geçici)';
            $this->command->line("   {$redirect->source_url} → {$redirect->destination_url} [{$statusText}]");
        });
    }
}
