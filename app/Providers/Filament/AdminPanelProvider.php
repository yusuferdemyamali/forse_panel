<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\RequestPasswordReset;
use App\Filament\CustomAvatarProvider;
use App\Filament\Widgets\ContentDistributionChart;
use App\Filament\Widgets\ContentGrowthChart;
use App\Filament\Widgets\DashboardStatsOverview;
use App\Filament\Widgets\GoogleAnalyticsStatsWidget;
use App\Filament\Widgets\QuickActionsWidget;
use App\Filament\Widgets\RecentBlogsTable;
use App\Filament\Widgets\SystemInfoWidget;
use App\Filament\Widgets\WelcomeWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use TomatoPHP\FilamentMediaManager\FilamentMediaManagerPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

// ==========================================
// MODÜLER SİSTEM İÇİN RESOURCE IMPORT'LARI
// ==========================================
use App\Filament\Resources\{
    // Core Resources (Her zaman aktif)
    PageResource,
    PageCategoryResource,
    SiteSettingResource,
    CompanySettingResource,
    RedirectResource,
    
    // Blog Modülü
    BlogResource,
    BlogCategoryResource,
    
    // Ürünler Modülü
    ProductResource,
    ProductCategoryResource,
    
    // Hizmetler Modülü
    ServiceResource,
    ServiceCategoryResource,
    
    // Kurumsal Modülü
    AboutResource,
    ReferenceResource,
    TeamResource,
    FaqResource,
    
    // Galeri Modülü
    GalleryResource,
    
    // İletişim Modülü
    ContactMessageResource,
};

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        // ==========================================
        // DİNAMİK MODÜLER SİSTEM
        // ==========================================
        
        $resources = [];
        $widgets = [];
        $navigationGroups = [];

        // ==========================================
        // CORE RESOURCES (Her zaman aktif)
        // ==========================================
        $resources[] = SiteSettingResource::class;
        $resources[] = CompanySettingResource::class;
        $resources[] = RedirectResource::class;

        // ==========================================
        // CORE WIDGETS (Her zaman aktif)
        // ==========================================
        $widgets[] = WelcomeWidget::class;
        $widgets[] = GoogleAnalyticsStatsWidget::class;
        $widgets[] = DashboardStatsOverview::class;
        $widgets[] = QuickActionsWidget::class;
        $widgets[] = SystemInfoWidget::class;

        // ==========================================
        // NAVIGATION GROUPS - Sabit Sıralama
        // ==========================================
        // Tüm grupları önceden tanımla, aktif olmayanlar otomatik gizlenir
        $allNavigationGroups = [
            'İçerik Yönetimi',
            'Blog',
            'Ürünler',
            'Hizmetler',
            'Kurumsal',
            'İletişim',
            'Ayarlar',
        ];

        // ==========================================
        // SAYFALAR MODÜLÜ (İçerik Yönetimi)
        // ==========================================
        if (config('modules.pages')) {
            $resources[] = PageResource::class;
            $resources[] = PageCategoryResource::class;
        }

        // ==========================================
        // BLOG MODÜLÜ
        // ==========================================
        if (config('modules.blog')) {
            $resources[] = BlogResource::class;
            $resources[] = BlogCategoryResource::class;
            
            $widgets[] = ContentGrowthChart::class;
            $widgets[] = RecentBlogsTable::class;
            $widgets[] = ContentDistributionChart::class;
        }

        // ==========================================
        // ÜRÜNLER MODÜLÜ
        // ==========================================
        if (config('modules.products')) {
            $resources[] = ProductResource::class;
            $resources[] = ProductCategoryResource::class;
        }

        // ==========================================
        // HİZMETLER MODÜLÜ
        // ==========================================
        if (config('modules.services')) {
            $resources[] = ServiceResource::class;
            $resources[] = ServiceCategoryResource::class;
        }

        // ==========================================
        // KURUMSAL MODÜLLER (About, References, Team, FAQ)
        // ==========================================
        if (config('modules.about')) {
            $resources[] = AboutResource::class;
        }

        if (config('modules.references')) {
            $resources[] = ReferenceResource::class;
        }

        if (config('modules.team')) {
            $resources[] = TeamResource::class;
        }

        if (config('modules.faq')) {
            $resources[] = FaqResource::class;
        }

        // ==========================================
        // GALERİ MODÜLÜ
        // ==========================================
        if (config('modules.gallery')) {
            $resources[] = GalleryResource::class;
        }

        // ==========================================
        // İLETİŞİM MODÜLÜ
        // ==========================================
        if (config('modules.contact')) {
            $resources[] = ContactMessageResource::class;
        }

        // ==========================================
        // PANEL YAPISI
        // ==========================================
        return $panel
            ->default()
            ->id('admin')
            ->path('panel5')
            ->login(\App\Filament\Pages\Auth\Login::class)
            ->passwordReset(RequestPasswordReset::class)
            ->colors([
                'primary' => 'rgb(34,193,195)',
            ])
            // discoverResources KALDIRILDI - Manuel yükleme yapıyoruz
            ->resources($resources)
            
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
            ])
            
            // Resource widget'larını otomatik keşfet (BlogPostStats gibi)
            ->discoverWidgets(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            // Dashboard widget'larını manuel yükle (modüler sistem için)
            ->widgets($widgets)
            
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            // Media Manager plugin'i sadece Gallery modülü aktifse yükle
            ->plugins(array_filter([
                config('modules.gallery') ? FilamentMediaManagerPlugin::make() : null,
                FilamentEditProfilePlugin::make()
                    ->setIcon('heroicon-o-user')
                    ->shouldShowDeleteAccountForm(false),
            ]))
            ->brandName('Forse Reklam')
            ->brandLogo(asset('images/forse_logo.png'))
            ->darkMode(false)
            ->brandLogoHeight('2.5rem')
            ->defaultAvatarProvider(CustomAvatarProvider::class)
            ->globalSearch(false)
            ->navigationGroups($allNavigationGroups)
            ->renderHook(
                'panels::body.end',
                fn () => view('filament.admin-styles')
            )
            ->renderHook(
                'panels::user-menu.before',
                fn () => view('filament.version-badge')
            );
    }
}
