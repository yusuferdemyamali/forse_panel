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
        // ==========================================
        // MODÜLER MİGRATİON SİSTEMİ
        // ==========================================
        // Core (temel) migration'lar her zaman yüklenir
        $this->loadMigrationsFrom(database_path('migrations/core'));

        // Modül migration'larını koşullu yükle
        if (config('modules.blog')) {
            $this->loadMigrationsFrom(database_path('migrations/blog'));
        }

        if (config('modules.references')) {
            $this->loadMigrationsFrom(database_path('migrations/references'));
        }
        
        if (config('modules.contact')) {
            $this->loadMigrationsFrom(database_path('migrations/contact'));
        }

        if (config('modules.products')) {
            $this->loadMigrationsFrom(database_path('migrations/products'));
        }

        if (config('modules.services')) {
            $this->loadMigrationsFrom(database_path('migrations/services'));
        }

        if (config('modules.gallery')) {
            $this->loadMigrationsFrom(database_path('migrations/gallery'));
        }

        if (config('modules.faq')) {
            $this->loadMigrationsFrom(database_path('migrations/faq'));
        }

        if (config('modules.team')) {
            $this->loadMigrationsFrom(database_path('migrations/team'));
        }

        if (config('modules.about')) {
            $this->loadMigrationsFrom(database_path('migrations/about'));
        }

        if (config('modules.pages')) {
            $this->loadMigrationsFrom(database_path('migrations/pages'));
        }

        // ==========================================
        // MAİL AYARLARI
        // ==========================================
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

        // ==========================================
        // MODÜLER OBSERVER SİSTEMİ
        // ==========================================
        // Model Observer'larını koşullu kaydet - Cache invalidation için
        
        // Blog Modülü Observers
        if (config('modules.blog')) {
            Blog::observe(BlogObserver::class);
            BlogCategory::observe(BlogCategoryObserver::class);
        }

        // Products Modülü Observers
        if (config('modules.products')) {
            Product::observe(ProductObserver::class);
            ProductCategory::observe(ProductCategoryObserver::class);
        }

        // Team Modülü Observer
        if (config('modules.team')) {
            Team::observe(TeamObserver::class);
        }

        // Gallery Modülü Observer
        if (config('modules.gallery')) {
            Gallery::observe(GalleryObserver::class);
        }

        // References Modülü Observer
        if (config('modules.references')) {
            Reference::observe(ReferenceObserver::class);
        }

        // FAQ Modülü Observer
        if (config('modules.faq')) {
            Faq::observe(FaqObserver::class);
        }

        // About Modülü Observer
        if (config('modules.about')) {
            About::observe(AboutObserver::class);
        }

        // Core Observers (her zaman aktif)
        SiteSetting::observe(SiteSettingObserver::class);
        Redirect::observe(RedirectObserver::class);

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['tr']); // also accepts a closure
        });

        // ==========================================
        // PAGES MODÜLÜ - VIEW SHARING & NAVIGATION
        // ==========================================
        if (config('modules.pages')) {
            // Navbar için kategori ve sayfaları paylaş
            // Try-catch ile migration henüz çalışmamışsa hata vermesin
            try {
                ViewFacade::share('navbarCategories', PageCategory::with(['pages' => function ($query) {
                        $query->where('is_published', true)->orderBy('order');
                    }])
                    ->where('is_active', true)
                    ->orderBy('order')
                    ->get()
                );
            } catch (\Exception $e) {
                // Migration henüz çalışmamışsa veya tablo yoksa boş collection paylaş
                ViewFacade::share('navbarCategories', collect([]));
            }

            // Filament sidebar için özel navigasyon öğeleri
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
}
