<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;
use Carbon\Carbon;

class GoogleAnalyticsStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 3;
    }

    protected function getStats(): array
    {
        // GA4 yapılandırması kontrolü
        if (!config('analytics.property_id') || !file_exists(config('analytics.service_account_credentials_json'))) {
            return [
                Stat::make('Google Analytics', 'Yapılandırma Gerekli')
                    ->description('GA4 kimlik bilgilerini ayarlayın. Detaylar için docs/GOOGLE_ANALYTICS_KURULUM.md dosyasına bakın.')
                    ->descriptionIcon('heroicon-o-exclamation-triangle')
                    ->color('warning'),
            ];
        }

        try {
            // Son 7 gün ve bir önceki 7 günlük periyotları tanımla
            $currentPeriod = Period::days(7);
            $previousPeriod = Period::create(
                Carbon::now()->subDays(14),
                Carbon::now()->subDays(7)
            );

            // Aktif kullanıcılar (GA4)
            $activeUsers = $this->getActiveUsers($currentPeriod);
            $previousActiveUsers = $this->getActiveUsers($previousPeriod);
            
            // Toplam görüntülenme (GA4)
            $screenPageViews = $this->getScreenPageViews($currentPeriod);
            $previousScreenPageViews = $this->getScreenPageViews($previousPeriod);
            
            // Ortalama oturum süresi (saniye cinsinden)
            $avgSessionDuration = $this->getAvgSessionDuration($currentPeriod);
            $previousAvgSessionDuration = $this->getAvgSessionDuration($previousPeriod);

            return [
                Stat::make('Aktif Kullanıcılar (7 Gün)', $this->formatNumber($activeUsers))
                    ->description($this->getChangeDescription($activeUsers, $previousActiveUsers))
                    ->descriptionIcon($this->getChangeIcon($activeUsers, $previousActiveUsers))
                    ->color($this->getChangeColor($activeUsers, $previousActiveUsers))
                    ->chart($this->getDailyActiveUsers($currentPeriod)),

                Stat::make('Sayfa Görüntülenme', $this->formatNumber($screenPageViews))
                    ->description($this->getChangeDescription($screenPageViews, $previousScreenPageViews))
                    ->descriptionIcon($this->getChangeIcon($screenPageViews, $previousScreenPageViews))
                    ->color($this->getChangeColor($screenPageViews, $previousScreenPageViews))
                    ->chart($this->getDailyPageViews($currentPeriod)),

                Stat::make('Ort. Oturum Süresi', $this->formatDuration($avgSessionDuration))
                    ->description($this->getChangeDescription($avgSessionDuration, $previousAvgSessionDuration))
                    ->descriptionIcon($this->getChangeIcon($avgSessionDuration, $previousAvgSessionDuration))
                    ->color($this->getChangeColor($avgSessionDuration, $previousAvgSessionDuration)),
            ];
        } catch (\Exception $e) {
            // Hata durumunda kullanıcıya bilgilendirme kartı göster
            return [
                Stat::make('Google Analytics', 'Yapılandırma Gerekli')
                    ->description('GA4 kimlik bilgilerini config/analytics.php dosyasından ayarlayın.')
                    ->descriptionIcon('heroicon-o-exclamation-triangle')
                    ->color('warning'),
            ];
        }
    }

    protected function getActiveUsers(Period $period): int
    {
        try {
            $result = Analytics::get(
                period: $period,
                metrics: ['activeUsers'],
            );

            return (int) ($result->first()['activeUsers'] ?? 0);
        } catch (\Exception $e) {
            return 0;
        }
    }

    protected function getScreenPageViews(Period $period): int
    {
        try {
            $result = Analytics::get(
                period: $period,
                metrics: ['screenPageViews'],
            );

            return (int) ($result->first()['screenPageViews'] ?? 0);
        } catch (\Exception $e) {
            return 0;
        }
    }

    protected function getAvgSessionDuration(Period $period): float
    {
        try {
            $result = Analytics::get(
                period: $period,
                metrics: ['averageSessionDuration'],
            );

            return (float) ($result->first()['averageSessionDuration'] ?? 0);
        } catch (\Exception $e) {
            return 0;
        }
    }

    protected function getDailyActiveUsers(Period $period): array
    {
        try {
            $result = Analytics::get(
                period: $period,
                metrics: ['activeUsers'],
                dimensions: ['date'],
            );

            return $result->pluck('activeUsers')->map(fn($value) => (int) $value)->toArray();
        } catch (\Exception $e) {
            return [0, 0, 0, 0, 0, 0, 0];
        }
    }

    protected function getDailyPageViews(Period $period): array
    {
        try {
            $result = Analytics::get(
                period: $period,
                metrics: ['screenPageViews'],
                dimensions: ['date'],
            );

            return $result->pluck('screenPageViews')->map(fn($value) => (int) $value)->toArray();
        } catch (\Exception $e) {
            return [0, 0, 0, 0, 0, 0, 0];
        }
    }

    protected function formatNumber($number): string
    {
        if ($number >= 1000000) {
            return number_format($number / 1000000, 1) . 'M';
        } elseif ($number >= 1000) {
            return number_format($number / 1000, 1) . 'K';
        }
        
        return number_format($number);
    }

    protected function formatDuration($seconds): string
    {
        $minutes = floor($seconds / 60);
        $secs = $seconds % 60;
        
        return sprintf('%d:%02d dk', $minutes, $secs);
    }

    protected function getChangeDescription($current, $previous): string
    {
        if ($previous == 0) {
            return 'Önceki döneme göre';
        }

        $change = (($current - $previous) / $previous) * 100;
        $changeFormatted = number_format(abs($change), 1);

        if ($change > 0) {
            return "{$changeFormatted}% artış";
        } elseif ($change < 0) {
            return "{$changeFormatted}% azalış";
        }

        return 'Değişiklik yok';
    }

    protected function getChangeIcon($current, $previous): string
    {
        if ($current > $previous) {
            return 'heroicon-o-arrow-trending-up';
        } elseif ($current < $previous) {
            return 'heroicon-o-arrow-trending-down';
        }

        return 'heroicon-o-minus';
    }

    protected function getChangeColor($current, $previous): string
    {
        if ($current > $previous) {
            return 'success';
        } elseif ($current < $previous) {
            return 'danger';
        }

        return 'gray';
    }
}
