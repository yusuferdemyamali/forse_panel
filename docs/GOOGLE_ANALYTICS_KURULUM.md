# Google Analytics 4 Widget Kurulum Rehberi

Google Analytics 4 widget'ı başarıyla kuruldu! Şimdi aşağıdaki adımları izleyerek yapılandırmayı tamamlayabilirsiniz.

## 📋 Yapılması Gerekenler (Kullanıcı Tarafı)

### 1. Google Cloud'da Servis Hesabı Oluşturma

1. [Google Cloud Console](https://console.cloud.google.com/)'a gidin
2. Proje seçin veya yeni proje oluşturun
3. **APIs & Services > Credentials** sayfasına gidin
4. **CREATE CREDENTIALS > Service Account** seçin
5. Servis hesabı detaylarını doldurun:
   - **Service account name**: `analytics-service-account` (veya istediğiniz bir isim)
   - **Service account ID**: Otomatik oluşacak
   - **CREATE AND CONTINUE** butonuna tıklayın
6. **Role** alanında **Viewer** rolünü seçin (isteğe bağlı)
7. **CONTINUE** ve **DONE** butonlarına tıklayın
8. Oluşturduğunuz servis hesabına tıklayın
9. **KEYS** sekmesine gidin
10. **ADD KEY > Create new key** seçin
11. **JSON** formatını seçin ve **CREATE** butonuna tıklayın
12. JSON dosyası otomatik olarak indirilecek

### 2. Google Analytics'e Servis Hesabı Ekleme

1. [Google Analytics](https://analytics.google.com/) sayfasına gidin
2. **Admin** (sol alt köşe) sayfasına gidin
3. **Property** seviyesinde **Property Access Management** seçin
4. Sağ üstte **+ (Add users)** butonuna tıklayın
5. Servis hesabınızın **email adresini** (JSON dosyasında `client_email` alanı) ekleyin
6. Rol olarak **Viewer** seçin
7. **Add** butonuna tıklayın

### 3. GA4 Property ID'yi Bulma

1. Google Analytics **Admin** sayfasında
2. **Property Settings** seçin
3. **PROPERTY ID**'yi kopyalayın (örn: `123456789`)

### 4. Projeye Kimlik Bilgilerini Ekleme

#### 4.1. JSON Dosyasını Yerleştirin

İndirdiğiniz JSON dosyasını şu konuma yerleştirin:

```bash
storage/app/analytics/service-account-credentials.json
```

Klasör yoksa oluşturun:

```bash
mkdir -p storage/app/analytics
```

#### 4.2. .env Dosyasını Güncelleyin

`.env` dosyanıza şu satırı ekleyin:

```env
ANALYTICS_PROPERTY_ID=123456789
```

(123456789 yerine kendi Property ID'nizi yazın)

### 5. İzinleri Kontrol Edin

JSON dosyasının web sunucusu tarafından okunabilir olduğundan emin olun:

```bash
chmod 644 storage/app/analytics/service-account-credentials.json
```

### 6. Cache'i Temizleyin

```bash
docker-compose exec app php artisan optimize:clear
```

### 7. Testi Yapın

Filament dashboard'a gidin. Artık Google Analytics 4 widget'ını görebilirsiniz!

Widget şu verileri gösterecek:
- **Aktif Kullanıcılar (Son 7 Gün)**: Grafik ve yüzde değişimi ile
- **Sayfa Görüntülenme**: Grafik ve yüzde değişimi ile
- **Ortalama Oturum Süresi**: Dakika:saniye formatında

## 🔧 Yapılandırma Seçenekleri

### Cache Süresi

Varsayılan olarak Google Analytics verileri 24 saat önbelleklenir. Bunu değiştirmek için `config/analytics.php`:

```php
'cache_lifetime_in_minutes' => 60 * 24, // 24 saat
```

### Widget Sırası

Widget'ın dashboard'daki sırasını değiştirmek için `app/Filament/Widgets/GoogleAnalyticsStatsWidget.php`:

```php
protected static ?int $sort = 1; // Daha küçük sayı = üstte görünür
```

### Widget'ı Gizleme

Widget'ı geçici olarak gizlemek için `app/Providers/Filament/AdminPanelProvider.php` dosyasından şu satırı yorum satırı yapın:

```php
->widgets([
    WelcomeWidget::class,
    // GoogleAnalyticsStatsWidget::class, // Yorum satırı yap
    DashboardStatsOverview::class,
    ...
])
```

## ❗ Sorun Giderme

### "Yapılandırma Gerekli" Hatası

Eğer widget'ta "Yapılandırma Gerekli" uyarısı görüyorsanız:

1. `.env` dosyasında `ANALYTICS_PROPERTY_ID` ayarlandığından emin olun
2. JSON dosyasının doğru konumda olduğunu kontrol edin
3. JSON dosyası izinlerini kontrol edin
4. Cache'i temizleyin: `docker-compose exec app php artisan optimize:clear`
5. Laravel log dosyalarını kontrol edin: `storage/logs/laravel.log`

### Veriler Güncellenmiyor

- Cache temizleyin: `docker-compose exec app php artisan cache:clear`
- Cache süresini kısaltın (geliştirme sırasında)
- Google Analytics'te veri toplanıp toplanmadığını kontrol edin

### Kimlik Doğrulama Hatası

1. Servis hesabının Google Analytics'e eklendiğinden emin olun
2. JSON dosyasının bozuk olmadığını kontrol edin
3. Property ID'nin doğru olduğunu doğrulayın

## 📚 Ek Kaynaklar

- [Spatie Laravel Analytics Dokümantasyonu](https://github.com/spatie/laravel-analytics)
- [Google Analytics Data API](https://developers.google.com/analytics/devguides/reporting/data/v1)
- [Filament Widgets Dokümantasyonu](https://filamentphp.com/docs/widgets)

## ✅ Kurulum Özeti

Tamamlanan adımlar:
- ✅ `spatie/laravel-analytics` paketi kuruldu
- ✅ Yapılandırma dosyası yayınlandı (`config/analytics.php`)
- ✅ Filament widget oluşturuldu ve yapılandırıldı
- ✅ Widget dashboard'a eklendi
- ✅ Hata yönetimi ve kullanıcı dostu mesajlar eklendi

Yapılması gerekenler:
- ⏳ Google Cloud'da servis hesabı oluşturma
- ⏳ Google Analytics'e servis hesabı ekleme
- ⏳ Property ID bulma
- ⏳ Kimlik bilgilerini projeye ekleme
- ⏳ Test etme

İyi çalışmalar! 🚀
