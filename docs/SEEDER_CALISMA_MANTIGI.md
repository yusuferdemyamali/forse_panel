# 📚 SEO Seeder'ları - Çalışma Mantığı ve Kullanım Kılavuzu

## 🎯 Genel Bakış

Seeder'lar, Laravel'de veritabanını örnek veya varsayılan verilerle doldurmak için kullanılan sınıflardır. Bu projede iki tip SEO seeder'ı oluşturuldu:

1. **SeoSettingsSeeder** - Global SEO ayarları için
2. **RedirectSeeder** - URL yönlendirmeleri için

---

## 📦 1. SeoSettingsSeeder - Nasıl Çalışır?

### Dosya Konumu
```
database/seeders/SeoSettingsSeeder.php
```

### Çalışma Mantığı

#### A) Spatie Laravel Settings Paketi Kullanımı

```php
$seoSettings = app(SeoSettings::class);
```

**Ne Yapar?**
- `SeoSettings` class'ının bir instance'ını oluşturur
- Bu class, `app/Settings/SeoSettings.php` dosyasında tanımlıdır
- Spatie paketi, bu class'ı otomatik olarak veritabanı ile senkronize eder

#### B) Property Assignment (Özellik Atama)

```php
$seoSettings->site_name = 'Forse Panel';
$seoSettings->site_description = 'Açıklama...';
$seoSettings->google_analytics_id = 'G-XXXXXXXXXX';
```

**Ne Yapar?**
- Public property'lere direkt değer atar
- Henüz veritabanına kaydedilmez (bellekte tutar)

#### C) Save Metodu

```php
$seoSettings->save();
```

**Ne Yapar?**
- Tüm property'leri veritabanına kaydeder
- Her bir property, `settings` tablosunda ayrı bir satır olarak saklanır
- Format: `group` = 'seo', `name` = 'site_name', `payload` = '{"value":"..."}'

### Veritabanı Yapısı

```
settings tablosu:
┌────┬───────┬───────────────────┬────────────────────────────┐
│ id │ group │ name              │ payload                    │
├────┼───────┼───────────────────┼────────────────────────────┤
│ 1  │ seo   │ site_name         │ "Forse Panel..."           │
│ 2  │ seo   │ site_description  │ "Açıklama..."              │
│ 3  │ seo   │ og_image          │ null                       │
│ 4  │ seo   │ google_analytics  │ "G-XXXXXXXXXX"             │
└────┴───────┴───────────────────┴────────────────────────────┘
```

### Neden Bu Yapı?

**Avantajlar:**
- ✅ Settings her zaman tek bir kayıt olarak tutulur (duplicate olmaz)
- ✅ Kodda tip güvenliği var (type-safe)
- ✅ Filament plugin ile otomatik form oluşturulur
- ✅ Cache desteği built-in gelir

**Dezavantajlar:**
- ❌ Her property ayrı bir satır (normalleştirilmiş)
- ❌ JSON serialization overhead'i var

---

## 🔀 2. RedirectSeeder - Nasıl Çalışır?

### Dosya Konumu
```
database/seeders/RedirectSeeder.php
```

### Çalışma Mantığı

#### A) Tekli Kayıt Oluşturma

```php
Redirect::create([
    'source_url' => '/eski-blog',
    'destination_url' => '/blog',
    'status_code' => 301,
    'is_active' => true,
]);
```

**Ne Yapar?**
- `redirects` tablosuna yeni bir satır ekler
- Model'deki `booted()` metodu, URL'leri otomatik temizler
- Observer, cache'i invalidate eder

#### B) Çoklu Kayıt Oluşturma (Loop)

```php
$redirects = [
    ['source_url' => '/old1', 'destination_url' => '/new1', ...],
    ['source_url' => '/old2', 'destination_url' => '/new2', ...],
];

foreach ($redirects as $redirect) {
    Redirect::create($redirect);
}
```

**Ne Yapar?**
- Array içindeki her elemanı tek tek oluşturur
- Her create, ayrı bir SQL INSERT komutu çalıştırır

#### C) updateOrCreate (İdempotent)

```php
Redirect::updateOrCreate(
    ['source_url' => '/promo'],  // ARAMA KOŞULU
    [                             // GÜNCELLENECEK/OLUŞTURULACAK DEĞERLER
        'destination_url' => '/kampanyalar',
        'status_code' => 302,
        'is_active' => true,
    ]
);
```

**Ne Yapar?**
1. Önce `source_url = '/promo'` olan kaydı arar
2. **Bulursa:** İkinci parametredeki değerlerle günceller
3. **Bulamazsa:** Yeni kayıt oluşturur (her iki parametre birleşir)

**Neden Kullanılır?**
- Seeder'ı birden fazla çalıştırsanız bile aynı kayıt tekrar oluşmaz
- Test ortamlarında güvenli
- Production'da veri kaybı olmadan güncelleme yapabilirsiniz

### Veritabanı Yapısı

```
redirects tablosu:
┌────┬─────────────────┬──────────────────┬─────────────┬───────────┐
│ id │ source_url      │ destination_url  │ status_code │ is_active │
├────┼─────────────────┼──────────────────┼─────────────┼───────────┤
│ 1  │ /eski-blog      │ /blog            │ 301         │ 1         │
│ 2  │ /kampanya       │ /urunler?...     │ 302         │ 1         │
│ 3  │ /test-sayfa     │ /anasayfa        │ 301         │ 0         │
└────┴─────────────────┴──────────────────┴─────────────┴───────────┘
```

### 301 vs 302 Status Kodları

| Kod | Anlamı | Ne Zaman Kullanılır? | SEO Etkisi |
|-----|--------|---------------------|------------|
| **301** | Kalıcı Yönlendirme | URL kalıcı olarak değiştiğinde | ✅ SEO değeri yeni URL'ye aktarılır |
| **302** | Geçici Yönlendirme | Kampanyalar, A/B testleri | ⚠️ SEO değeri eski URL'de kalır |

**Örnek Senaryolar:**

```php
// 301: Site yapısı değişti, eski URL artık yok
'/eski-kategori/urun' → '/yeni-kategori/urun' [301]

// 302: Kış kampanyası için geçici yönlendirme
'/kampanya' → '/urunler?discount=winter' [302]
```

---

## 🚀 Seeder'ları Çalıştırma

### Tüm Seeder'ları Çalıştır

```bash
php artisan db:seed
```

**Ne Yapar?**
- `DatabaseSeeder.php` dosyasındaki `call()` metodu içindeki tüm seeder'ları çalıştırır
- Sırayla: SiteSettingSeeder → SeoSettingsSeeder → RedirectSeeder

### Sadece Belirli Bir Seeder'ı Çalıştır

```bash
# Sadece SEO ayarları
php artisan db:seed --class=SeoSettingsSeeder

# Sadece yönlendirmeler
php artisan db:seed --class=RedirectSeeder
```

### Migration ile Birlikte Çalıştır

```bash
# Veritabanını sıfırla, migration'ları çalıştır ve seed et
php artisan migrate:fresh --seed
```

⚠️ **DİKKAT:** Bu komut tüm verileri siler!

---

## 🔧 Model Events ve Observer Entegrasyonu

### Redirect Model'deki booted() Metodu

```php
protected static function booted()
{
    static::saving(function ($redirect) {
        // URL temizleme otomatik yapılır
        $redirect->source_url = '/' . ltrim($redirect->source_url, '/');
    });
}
```

**Ne Zaman Çalışır?**
- `create()` çağrıldığında (seeder)
- `update()` çağrıldığında (admin panelinden)
- `save()` çağrıldığında

**Örnek:**

```php
// Seeder'da yazılan:
'source_url' => 'eski-blog'  // Slash yok

// Veritabanına kaydedilen:
'source_url' => '/eski-blog'  // Otomatik slash eklendi
```

### RedirectObserver - Cache Yönetimi

```php
public function created(Redirect $redirect): void
{
    Cache::forget('redirect:' . $redirect->source_url);
}
```

**Ne Zaman Çalışır?**
- Yeni redirect oluşturulduğunda
- Redirect güncellendiğinde
- Redirect silindiğinde

**Neden Gerekli?**
- RedirectMiddleware, yönlendirmeleri cache'de tutar (performans için)
- Seeder çalıştığında, eski cache'i temizlemek gerekir
- Yoksa yeni yönlendirmeler çalışmaz (24 saat boyunca!)

---

## 💡 Best Practices (En İyi Uygulamalar)

### 1. İdempotent Seeder'lar Yazın

❌ **Kötü:**
```php
Redirect::create(['source_url' => '/test', ...]);
// Tekrar çalıştırınca duplicate hata verir
```

✅ **İyi:**
```php
Redirect::updateOrCreate(
    ['source_url' => '/test'],
    ['destination_url' => '/new', ...]
);
// İstediğiniz kadar çalıştırabilirsiniz
```

### 2. Meaningful Default Values (Anlamlı Varsayılan Değerler)

❌ **Kötü:**
```php
$seoSettings->site_name = 'Test';
$seoSettings->site_description = 'Lorem ipsum...';
```

✅ **İyi:**
```php
$seoSettings->site_name = 'Forse Panel - Kurumsal Web Yönetim Sistemi';
$seoSettings->site_description = 'Profesyonel içerik yönetim sistemi...';
```

### 3. Console Output Ekleyin

```php
$this->command->info('✅ Başarılı!');
$this->command->warn('⚠️ Dikkat!');
$this->command->error('❌ Hata!');
```

**Faydası:**
- Seeder'ın ne yaptığını görebilirsiniz
- Debug için kolaylık
- Production'da log'lara düşer

---

## 🧪 Test Senaryoları

### SEO Settings'i Okuma

```php
use App\Settings\SeoSettings;

// Herhangi bir yerde kullanabilirsiniz
$seoSettings = app(SeoSettings::class);

echo $seoSettings->site_name;
echo $seoSettings->google_analytics_id;
```

### Redirect'leri Okuma

```php
use App\Models\Redirect;

// Aktif yönlendirmeleri listele
$redirects = Redirect::where('is_active', true)->get();

// Belirli bir kaynaktan yönlendirme ara
$redirect = Redirect::where('source_url', '/eski-blog')->first();
```

---

## 📊 Performans Notları

### Settings Tablosu
- **Okuma:** Cache'lenir (çok hızlı)
- **Yazma:** Nadiren olur (admin panelinden)
- **Boyut:** Minimal (her setting bir satır)

### Redirects Tablosu
- **Okuma:** Middleware her request'te kontrol eder
- **Cache:** 24 saat (performans için kritik)
- **Index:** `source_url` ve `is_active` index'li
- **Boyut:** Orta (yüzlerce redirect olabilir)

---

## 🎓 Öğrenme Çıkarımları

### Spatie Settings Paketi
- Settings tek bir instance olarak yönetilir
- Property-based API (kolay kullanım)
- Otomatik cache yönetimi
- Type-safe (tip güvenli)

### Laravel Seeder'lar
- Veritabanını başlangıç verileriyle doldurur
- Test ve development için ideal
- Production'da dikkatli kullanılmalı

### Model Events
- `creating`, `created`, `updating`, `updated`, `saving`, `saved`
- Otomatik veri temizleme için ideal
- Business logic model içinde kalır

### Observer Pattern
- Model event'lerini merkezi bir yerde yönetir
- Cache invalidation için mükemmel
- Separation of Concerns prensibi

---

## 🔗 İlgili Dosyalar

```
📁 Seeder'lar
├── database/seeders/SeoSettingsSeeder.php
├── database/seeders/RedirectSeeder.php
└── database/seeders/DatabaseSeeder.php

📁 Models
├── app/Models/Redirect.php
└── app/Settings/SeoSettings.php

📁 Observers
└── app/Observers/RedirectObserver.php

📁 Middleware
└── app/Http/Middleware/RedirectMiddleware.php

📁 Migrations
├── database/migrations/*_create_settings_table.php
├── database/migrations/*_create_redirects_table.php
└── database/settings/*_create_seo_settings.php
```

---

## ✅ Sonuç

Seeder'lar:
1. ✅ Veritabanını başlangıç verileriyle doldurur
2. ✅ Test ve development için hayati önem taşır
3. ✅ İdempotent (tekrar çalıştırılabilir) olmalıdır
4. ✅ Model events ve observer'larla uyumlu çalışır
5. ✅ Cache yönetimini tetikler

**Şimdi sisteminiz tamamen hazır! 🎉**
