<?php

namespace App\Filament\Resources\PageCategoryResource\Pages;

use App\Filament\Resources\PageCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPageCategory extends EditRecord
{
    protected static string $resource = PageCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
