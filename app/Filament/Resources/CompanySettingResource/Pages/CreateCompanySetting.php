<?php

namespace App\Filament\Resources\CompanySettingResource\Pages;

use App\Filament\Resources\CompanySettingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCompanySetting extends CreateRecord
{
    protected static string $resource = CompanySettingResource::class;

    protected static ?string $title = 'Firma Ayarı Ekle';

    protected static bool $canCreateAnother = false;

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
