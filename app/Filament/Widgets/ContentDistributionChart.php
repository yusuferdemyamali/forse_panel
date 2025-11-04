<?php

namespace App\Filament\Widgets;

use App\Models\Blog;
use App\Models\Product;
use App\Models\Reference;
use Filament\Widgets\ChartWidget;

class ContentDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'İçerik Dağılımı';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = [
        'md' => 2,
        'xl' => 1,
    ];

    protected function getData(): array
    {
        $data = [];
        $labels = [];
        $backgroundColors = [];
        $borderColors = [];

        // Blog Modülü Aktifse
        if (config('modules.blog')) {
            $data[] = Blog::count();
            $labels[] = 'Blog Yazıları';
            $backgroundColors[] = 'rgb(59, 130, 246)';  // Blue
            $borderColors[] = 'rgb(59, 130, 246)';
        }

        // Products Modülü Aktifse
        if (config('modules.products')) {
            $data[] = Product::count();
            $labels[] = 'Ürünler';
            $backgroundColors[] = 'rgb(245, 158, 11)';  // Yellow/Orange
            $borderColors[] = 'rgb(245, 158, 11)';
        }

        // References Modülü Aktifse
        if (config('modules.references')) {
            $data[] = Reference::count();
            $labels[] = 'Referanslar';
            $backgroundColors[] = 'rgb(34, 197, 94)';  // Green
            $borderColors[] = 'rgb(34, 197, 94)';
        }

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                    'borderColor' => $borderColors,
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'maintainAspectRatio' => false,
            'responsive' => true,
        ];
    }
}
