<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Genel Bakış';
    
    protected static ?string $title = '';

    public function getColumns(): int | string | array
    {
        return [
            'default' => 1,
            'md' => 2,
            'lg' => 3,
            'xl' => 3,
        ];
    }
}
