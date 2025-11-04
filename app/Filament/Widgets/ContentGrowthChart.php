<?php

namespace App\Filament\Widgets;

use App\Models\Blog;
use App\Models\Product;
use Filament\Widgets\ChartWidget;

class ContentGrowthChart extends ChartWidget
{
    protected static ?string $heading = 'İçerik Büyüme Grafiği';

    protected static ?int $sort = 1;

    protected static ?string $pollingInterval = '30s';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $months = collect();
        $datasets = [];

        // Son 6 ayın verilerini al
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthName = $month->format('M Y');
            $months->push($monthName);
        }

        // Blog Modülü Aktifse
        if (config('modules.blog')) {
            $blogData = collect();
            
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                
                $blogsCount = Blog::whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->count();
                $blogData->push($blogsCount);
            }

            $datasets[] = [
                'label' => 'Blog Yazıları',
                'data' => $blogData->toArray(),
                'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                'borderColor' => 'rgb(59, 130, 246)',
                'borderWidth' => 2,
                'fill' => true,
            ];
        }

        // Products Modülü Aktifse
        if (config('modules.products')) {
            $productData = collect();
            
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                
                $productsCount = Product::whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->count();
                $productData->push($productsCount);
            }

            $datasets[] = [
                'label' => 'Ürünler',
                'data' => $productData->toArray(),
                'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                'borderColor' => 'rgb(245, 158, 11)',
                'borderWidth' => 2,
                'fill' => true,
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => $months->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'elements' => [
                'point' => [
                    'radius' => 4,
                    'hoverRadius' => 6,
                ],
            ],
        ];
    }
}
