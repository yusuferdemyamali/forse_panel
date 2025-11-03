<?php

namespace App\Filament\Resources\RedirectResource\Pages;

use App\Filament\Resources\RedirectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRedirects extends ListRecords
{
    protected static string $resource = RedirectResource::class;

    protected static ?string $title = 'Yönlendirmeler';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Yeni Yönlendirme'),
        ];
    }
}
