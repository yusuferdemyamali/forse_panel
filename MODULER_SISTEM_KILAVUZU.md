# 📦 Modüler Sistem Kullanım Kılavuzu

Bu proje, **config dosyası üzerinden açılıp kapatılabilen modüler bir yapıya** sahiptir. 

## 🎯 Modüler Sistemin Amacı

Kapatılan modüller için:
1. ✅ Migration'lar çalışmaz
2. ✅ Filament Resource'ları panele yüklenmez
3. ✅ Widget'lar dashboard'da görünmez
4. ✅ Navigation grupları gösterilmez
5. ✅ Observer'lar kayıt edilmez

## 🛠️ Modül Nasıl Açılır/Kapatılır?

### 1. `.env` Dosyasını Düzenle

`.env` dosyanıza aşağıdaki satırları ekleyin (`.envexample` dosyasından referans alabilirsiniz):

```env
# MODÜL YÖNETİMİ
MODULE_BLOG_ENABLED=true
MODULE_REFERENCES_ENABLED=true
MODULE_CONTACT_ENABLED=true
MODULE_PRODUCTS_ENABLED=true
MODULE_SERVICES_ENABLED=true
MODULE_GALLERY_ENABLED=true
MODULE_FAQ_ENABLED=true
MODULE_TEAM_ENABLED=true
MODULE_ABOUT_ENABLED=true
MODULE_PAGES_ENABLED=true
```

### 2. Modülü Kapat

Bir modülü kapatmak için değeri `false` yapın:

```env
# Blog modülünü kapatmak için:
MODULE_BLOG_ENABLED=false
```

### 3. Cache'i Temizle

Değişikliklerin uygulanması için cache'i temizleyin:

```bash
php artisan config:clear
php artisan cache:clear
php artisan filament:clear-cached-components
```

### 4. Migration'ları Yönet

Eğer bir modülü kapatıp daha sonra açarsanız, o modülün migration'larını çalıştırın:

```bash
php artisan migrate
```

Bir modülü tamamen kaldırmak istiyorsanız (dikkatli kullanın!):

```bash
php artisan migrate:rollback --path=database/migrations/blog
```

## 📂 Modül Yapısı

Her modül için ayrı migration klasörü oluşturuldu:

```
database/migrations/
├── core/           # Temel sistemin migration'ları (users, settings, redirects - her zaman aktif)
├── blog/           # Blog modülü
├── references/     # Referanslar modülü
├── contact/        # İletişim modülü
├── products/       # Ürünler modülü
├── services/       # Hizmetler modülü
├── gallery/        # Galeri modülü
├── faq/            # SSS modülü
├── team/           # Ekip modülü
├── about/          # Hakkımızda modülü
└── pages/          # Sayfalar modülü (İçerik Yönetimi)
```

## 🎨 Mevcut Modüller

| Modül | Açıklama | Config Anahtarı |
|-------|----------|----------------|
| **Blog** | Blog yazıları ve kategoriler | `modules.blog` |
| **References** | Referanslar/Müşteriler | `modules.references` |
| **Contact** | İletişim mesajları | `modules.contact` |
| **Products** | Ürünler ve kategoriler | `modules.products` |
| **Services** | Hizmetler ve kategoriler | `modules.services` |
| **Gallery** | Fotoğraf galerisi | `modules.gallery` |
| **FAQ** | Sıkça Sorulan Sorular | `modules.faq` |
| **Team** | Ekip üyeleri | `modules.team` |
| **About** | Hakkımızda sayfası | `modules.about` |
| **Pages** | Dinamik sayfalar ve kategoriler | `modules.pages` |

## 🔍 Modül Durumunu Kontrol Etme

Laravel Tinker ile modül durumunu kontrol edebilirsiniz:

```bash
php artisan tinker
```

```php
// Tüm modüllerin durumunu göster
config('modules');

// Belirli bir modülün durumunu kontrol et
config('modules.blog'); // true veya false döner
```

## 📝 Yeni Modül Eklemek

Yeni bir modül eklemek için aşağıdaki adımları izleyin:

### 1. Config Dosyasını Güncelle

`config/modules.php` dosyasına yeni modülü ekle:

```php
'yeni_modul' => env('MODULE_YENI_MODUL_ENABLED', true),
```

### 2. Migration Klasörü Oluştur

```bash
mkdir database/migrations/yeni_modul
```

### 3. AppServiceProvider'ı Güncelle

`app/Providers/AppServiceProvider.php` dosyasının `boot()` metoduna ekle:

```php
if (config('modules.yeni_modul')) {
    $this->loadMigrationsFrom(database_path('migrations/yeni_modul'));
}
```

### 4. AdminPanelProvider'ı Güncelle

`app/Providers/Filament/AdminPanelProvider.php` dosyasına Resource ve Widget'ları ekle:

```php
if (config('modules.yeni_modul')) {
    $resources[] = YeniModulResource::class;
    $navigationGroups[] = 'Yeni Modül';
}
```

### 5. .env.example'ı Güncelle

```env
MODULE_YENI_MODUL_ENABLED=true
```

## ⚠️ Önemli Notlar

1. **Core modülü her zaman aktiftir** ve kapatılamaz (users, settings, redirects vb.)
2. **İçerik Yönetimi modülü** (Pages) artık `.env` dosyasından kapatılabilir
3. **Kurumsal grubu** (About, References, Team, FAQ) en az bir modül aktifse görünür
4. **Widget'lar** modüllere göre dinamik olarak yüklenir ve içerikleri koşullu oluşturulur
5. **Observer'lar** yalnızca aktif modüller için çalışır
6. **Dashboard Widget'ları** (QuickActionsWidget, ContentGrowthChart, ContentDistributionChart, DashboardStatsOverview) modül durumuna göre otomatik olarak güncellenir

## 🚀 Örnek Senaryolar

### Senaryo 1: Sadece Blog ve İletişim Modüllerini Kullan

```env
MODULE_BLOG_ENABLED=true
MODULE_REFERENCES_ENABLED=false
MODULE_CONTACT_ENABLED=true
MODULE_PRODUCTS_ENABLED=false
MODULE_SERVICES_ENABLED=false
MODULE_GALLERY_ENABLED=false
MODULE_FAQ_ENABLED=false
MODULE_TEAM_ENABLED=false
MODULE_ABOUT_ENABLED=false
```

**Sonuç:**
- ✅ Blog menüsü görünür
- ✅ İletişim menüsü görünür
- ❌ Ürünler, Hizmetler, Kurumsal menüleri gizlenir

### Senaryo 2: E-ticaret Odaklı (Ürünler ve Hizmetler)

```env
MODULE_BLOG_ENABLED=false
MODULE_PRODUCTS_ENABLED=true
MODULE_SERVICES_ENABLED=true
MODULE_GALLERY_ENABLED=true
```

**Sonuç:**
- ✅ Ürünler ve Hizmetler modülleri aktif
- ✅ Galeri aktif (ürün fotoğrafları için)
- ❌ Blog devre dışı

## 🔧 Sorun Giderme

### Modül kapatıldı ama hala görünüyor?

```bash
php artisan config:clear
php artisan cache:clear
php artisan filament:clear-cached-components
```

### Migration hataları alıyorum?

```bash
# Tüm migration'ları sıfırla ve yeniden çalıştır
php artisan migrate:fresh --seed
```

### Sayfa 500 hatası alıyorum?

```bash
# Log dosyasını kontrol et
tail -f storage/logs/laravel.log

# Filament view cache'ini temizle
php artisan view:clear
php artisan optimize:clear
```

## 📚 Teknik Detaylar

### Dosya Yapısı

```
config/
└── modules.php                 # Modül konfigürasyonu

app/Providers/
├── AppServiceProvider.php      # Migration ve Observer yönetimi
└── Filament/
    └── AdminPanelProvider.php  # Resource ve Widget yönetimi

database/migrations/
├── core/                       # Core migration'lar
└── [modul-adi]/               # Modül migration'ları
```

### Çalışma Mantığı

1. **Config Dosyası:** `.env` → `config/modules.php`
2. **Migration:** `AppServiceProvider::boot()` → `loadMigrationsFrom()`
3. **Filament:** `AdminPanelProvider::panel()` → Dinamik resource/widget yükleme
4. **Observer:** `AppServiceProvider::boot()` → Koşullu observer kayıt

---

**✨ İyi çalışmalar!**
