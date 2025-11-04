<?php

namespace App\Providers;

use App\Models\About;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Faq;
use App\Models\Gallery;
use App\Models\PageCategory;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Redirect;
use App\Models\Reference;
use App\Models\SiteSetting;
use App\Models\Team;
use App\Observers\AboutObserver;
use App\Observers\BlogCategoryObserver;
use App\Observers\BlogObserver;
use App\Observers\FaqObserver;
use App\Observers\GalleryObserver;
use App\Observers\ProductCategoryObserver;
use App\Observers\ProductObserver;
use App\Observers\RedirectObserver;
use App\Observers\ReferenceObserver;
use App\Observers\SiteSettingObserver;
use App\Observers\TeamObserver;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\Support\Facades\FilamentView;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationItem;
use App\Filament\Resources\PageResource;
use App\Filament\Resources\PageCategoryResource;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Mail ayarlarını veritabanından yükle ve uygula
        try {
            $mailSettings = app(\App\Settings\MailSettings::class);
            
            // Ayarlar dolu ise uygula
            if ($mailSettings->host && $mailSettings->username) {
                config([
                    'mail.default' => $mailSettings->mailer ?? 'smtp',
                    'mail.mailers.smtp' => [
                        'transport' => 'smtp',
                        'host' => $mailSettings->host,
                        'port' => $mailSettings->port ?? 587,
                        'encryption' => $mailSettings->encryption ?? 'tls',
                        'username' => $mailSettings->username,
                        'password' => $mailSettings->password,
                        'timeout' => null,
                    ],
                    'mail.from' => [
                        'address' => $mailSettings->from_address ?? config('mail.from.address'),
                        'name' => $mailSettings->from_name ?? config('mail.from.name'),
                    ],
                ]);
            }
        } catch (\Exception $e) {
            // Migration henüz çalışmamışsa veya tablo yoksa varsayılan ayarları kullan
            // Log kaydı tutabiliriz ancak uygulama çökmesin
        }

        FilamentView::registerRenderHook(
            'panels::auth.login.form.after',
            fn (): View => view('filament.login_extra')
    );

        // Model Observer'larını kaydet - Cache invalidation için
        Blog::observe(BlogObserver::class);
        BlogCategory::observe(BlogCategoryObserver::class);
        Product::observe(ProductObserver::class);
        ProductCategory::observe(ProductCategoryObserver::class);

        // Diğer modüller için observer'lar
        Team::observe(TeamObserver::class);
        Gallery::observe(GalleryObserver::class);
        Reference::observe(ReferenceObserver::class);
        Faq::observe(FaqObserver::class);
        About::observe(AboutObserver::class);
        SiteSetting::observe(SiteSettingObserver::class);
        
        // SEO Redirect Observer'ı kaydet
        Redirect::observe(RedirectObserver::class);

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['tr']); // also accepts a closure
        });

        // Navbar için kategori ve sayfaları paylaş
        ViewFacade::share('navbarCategories', PageCategory::with(['pages' => function ($query) {
                $query->where('is_published', true)->orderBy('order');
            }])
            ->where('is_active', true)
            ->orderBy('order')
            ->get()
        );

        // Filament sidebar için özel navigasyon öğeleri (sıralamayı burada belirtiyoruz)
        // Sıralama: 1 = Tüm Sayfalar (PageResource), 2 = Sayfa Kategorileri (PageCategoryResource)
        // 3 = Yeni Sayfa Ekle, 4 = Kategori Ekle
        Filament::registerNavigationItems([
            NavigationItem::make('Yeni Sayfa Ekle')
                ->group('İçerik Yönetimi')
                ->url(fn (): string => PageResource::getUrl('create'))
                ->sort(3)
                ->icon('heroicon-o-plus'),

            NavigationItem::make('Kategori Ekle')
                ->group('İçerik Yönetimi')
                ->url(fn (): string => PageCategoryResource::getUrl('create'))
                ->sort(4)
                ->icon('heroicon-o-plus'),
        ]);
    }
}
