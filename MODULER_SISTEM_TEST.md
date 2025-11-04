# 🧪 Modüler Sistem Test Senaryoları

Bu dosya, modüler sistemin doğru çalıştığını test etmek için kullanılabilir.

## Test 1: Blog Modülünü Kapat

### Adımlar:
1. `.env` dosyasını düzenle:
```env
MODULE_BLOG_ENABLED=false
```

2. Cache'i temizle:
```bash
php artisan config:clear
php artisan cache:clear
php artisan filament:clear-cached-components
```

3. Admin paneline giriş yap ve kontrol et:
   - ✅ Blog menüsü GÖZÜKMEMELI
   - ✅ Blog Resource'ları YÜKLENMEMELI
   - ✅ Blog Widget'ları (RecentBlogsTable, ContentGrowthChart, ContentDistributionChart) dashboard'da GÖZÜKMEMELI

### Beklenen Sonuç:
```
✅ Blog navigation grubu gizlendi
✅ BlogResource ve BlogCategoryResource yüklenmedi
✅ Blog ile ilgili widget'lar gösterilmedi
```

---

## Test 2: Tüm Modülleri Kapat

### Adımlar:
1. `.env` dosyasını düzenle:
```env
MODULE_BLOG_ENABLED=false
MODULE_REFERENCES_ENABLED=false
MODULE_CONTACT_ENABLED=false
MODULE_PRODUCTS_ENABLED=false
MODULE_SERVICES_ENABLED=false
MODULE_GALLERY_ENABLED=false
MODULE_FAQ_ENABLED=false
MODULE_TEAM_ENABLED=false
MODULE_ABOUT_ENABLED=false
```

2. Cache'i temizle ve admin paneline gir

### Beklenen Sonuç:
```
✅ Sadece "İçerik Yönetimi" ve "Ayarlar" navigation grupları görünür
✅ Sadece Core Resource'lar yüklenir:
   - PageResource
   - PageCategoryResource
   - SiteSettingResource
   - CompanySettingResource
   - RedirectResource
✅ Sadece Core Widget'lar gösterilir:
   - WelcomeWidget
   - GoogleAnalyticsStatsWidget
   - DashboardStatsOverview
   - QuickActionsWidget
   - SystemInfoWidget
```

---

## Test 3: Sadece Blog ve İletişim Modüllerini Aç

### Adımlar:
1. `.env` dosyasını düzenle:
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

2. Cache'i temizle ve admin paneline gir

### Beklenen Sonuç:
```
✅ Navigation grupları:
   - İçerik Yönetimi
   - Blog ✨ (yeni)
   - İletişim ✨ (yeni)
   - Ayarlar

✅ Resource'lar:
   - Core + BlogResource + BlogCategoryResource + ContactMessageResource

✅ Widget'lar:
   - Core + ContentGrowthChart + RecentBlogsTable + ContentDistributionChart
```

---

## Test 4: Migration Kontrolü

### Blog Modülü Kapalıyken:
```bash
php artisan migrate:status
```

**Beklenen:** Blog migration'ları listelenmemeli.

### Blog Modülü Açıkken:
```bash
php artisan migrate:status
```

**Beklenen:** Blog migration'ları listelenip çalıştırılabilir olmalı.

---

## Test 5: Observer Kontrolü

### Test Kodu (Tinker):
```bash
php artisan tinker
```

```php
// Blog modülü KAPALI iken
$blog = \App\Models\Blog::first();
$blog->title = 'Test';
$blog->save();
// BlogObserver çalışmamalı (cache invalidation yapılmamalı)

// Blog modülü AÇIK iken
$blog = \App\Models\Blog::first();
$blog->title = 'Test 2';
$blog->save();
// BlogObserver çalışmalı (cache invalidation yapılmalı)
```

---

## Test 6: Hata Kontrolü

### Yaygın Hatalar:

#### 1. "Class not found" Hatası
**Sebep:** Resource dosyası import edilmemiş  
**Çözüm:** `AdminPanelProvider.php` dosyasında ilgili Resource'u import et

#### 2. Navigation grubu görünmüyor
**Sebep:** Config cache temizlenmemiş  
**Çözüm:** 
```bash
php artisan config:clear
php artisan filament:clear-cached-components
```

#### 3. Migration çalışmıyor
**Sebep:** Migration dosyası yanlış klasörde  
**Çözüm:** 
```bash
# Doğru klasöre taşı
mv database/migrations/xxx_blog_xxx.php database/migrations/blog/
```

---

## Debug Komutları

### 1. Config değerlerini görüntüle:
```bash
php artisan tinker
```
```php
config('modules');
// Çıktı: ['blog' => true, 'references' => true, ...]
```

### 2. Yüklenen Resource'ları görüntüle:
```bash
php artisan route:list | grep filament
```

### 3. Migration durumunu kontrol et:
```bash
php artisan migrate:status
```

### 4. Cache temizle:
```bash
php artisan optimize:clear
```

---

## Başarı Kriterleri

Modüler sistem başarılı sayılır eğer:

- [x] `.env` dosyasından modül açılıp kapatılabiliyor
- [x] Kapalı modüllerin migration'ları çalışmıyor
- [x] Kapalı modüllerin Resource'ları Filament panelinde görünmüyor
- [x] Kapalı modüllerin Widget'ları dashboard'da görünmüyor
- [x] Kapalı modüllerin Navigation grupları gösterilmiyor
- [x] Kapalı modüllerin Observer'ları kayıt edilmiyor
- [x] Core modül her zaman çalışıyor
- [x] Modül durumu değiştiğinde sistem hatasız çalışıyor

---

**✨ Test başarıyla tamamlandığında, modüler sistem production'a hazırdır!**
