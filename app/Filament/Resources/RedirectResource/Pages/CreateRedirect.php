<?php

namespace App\Filament\Resources\RedirectResource\Pages;

use App\Filament\Resources\RedirectResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRedirect extends CreateRecord
{
    protected static string $resource = RedirectResource::class;

    protected static ?string $title = 'Yeni Yönlendirme Oluştur';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
