<?php

namespace App\Filament\Widgets;

use App\Models\Blog;
use App\Models\Product;
use App\Models\Reference;
use App\Models\Team;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsOverview extends BaseWidget
{
    protected static ?int $sort = 0;

    protected static ?string $pollingInterval = '15s';

    protected int|string|array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 3;
    }

    protected function getStats(): array
    {
        $stats = [];
        $totalContent = 0;
        $newContentThisMonth = 0;
        $contentDescription = [];

        // Blog Modülü Aktifse
        if (config('modules.blog')) {
            $totalBlogs = Blog::count();
            $activeBlogsCount = Blog::where('is_active', true)->count();
            $blogsThisMonth = Blog::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            $totalContent += $totalBlogs;
            $newContentThisMonth += $blogsThisMonth;
            $contentDescription[] = 'Blog';

            $stats[] = Stat::make('Aktif Blog Yazıları', $activeBlogsCount)
                ->description("Toplam {$totalBlogs} yazıdan")
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('primary')
                ->url(route('filament.admin.resources.blogs.index'))
                ->chart([3, 7, $activeBlogsCount]);
        }

        // Products Modülü Aktifse
        if (config('modules.products')) {
            $totalProducts = Product::count();
            $activeProductsCount = Product::where('is_active', true)->count();
            $productsThisMonth = Product::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            $totalContent += $totalProducts;
            $newContentThisMonth += $productsThisMonth;
            $contentDescription[] = 'Ürün';

            $stats[] = Stat::make('Ürün Kataloğu', $activeProductsCount)
                ->description("Toplam {$totalProducts} üründen")
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary')
                ->url(route('filament.admin.resources.products.index'))
                ->chart([1, 3, $activeProductsCount]);
        }

        // References Modülü Aktifse
        if (config('modules.references')) {
            $totalReferences = Reference::count();
            $totalContent += $totalReferences;
            $contentDescription[] = 'Referans';

            $stats[] = Stat::make('Müşteri Referansları', $totalReferences)
                ->description('Onaylanmış referans')
                ->descriptionIcon('heroicon-m-star')
                ->color('primary')
                ->url(route('filament.admin.resources.references.index'));
        }

        // Team Modülü Aktifse
        if (config('modules.team')) {
            $totalTeam = Team::count();

            $stats[] = Stat::make('Ekip Üyeleri', $totalTeam)
                ->description('Aktif ekip üyesi')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->url(route('filament.admin.resources.teams.index'));
        }

        // Toplam İçerik Kartı (En başa ekle)
        if ($totalContent > 0) {
            array_unshift($stats, 
                Stat::make('Toplam İçerik Sayısı', $totalContent)
                    ->description(implode(', ', $contentDescription))
                    ->descriptionIcon('heroicon-m-document-duplicate')
                    ->color('primary')
                    ->chart([7, 12, 18, 22, 15, 28, $totalContent])
            );
        }

        // Bu Ayki Yeni İçerik Kartı
        if ($newContentThisMonth > 0 || (config('modules.blog') || config('modules.products'))) {
            $stats[] = Stat::make('Bu Ayki Yeni İçerik', $newContentThisMonth)
                ->description("Yeni içerik eklendi")
                ->descriptionIcon('heroicon-m-plus-circle')
                ->color('primary')
                ->chart([2, 4, 6, $newContentThisMonth]);
        }

        return $stats;
    }
}
