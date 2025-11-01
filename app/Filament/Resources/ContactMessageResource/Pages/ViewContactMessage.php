<?php

namespace App\Filament\Resources\ContactMessageResource\Pages;

use App\Filament\Resources\ContactMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewContactMessage extends ViewRecord
{
    protected static string $resource = ContactMessageResource::class;

    protected static ?string $title = 'Mesaj Detayı';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('markAsRead')
                ->label('Okundu İşaretle')
                ->icon('heroicon-o-check')
                ->color('success')
                ->action(fn () => $this->record->update(['is_read' => true]))
                ->visible(fn (): bool => !$this->record->is_read),
            
            Actions\DeleteAction::make()
                ->label('Mesajı Sil'),
        ];
    }

    protected function afterView(): void
    {
        // Mesaj görüntülendiğinde otomatik olarak okundu işaretle
        if (!$this->record->is_read) {
            $this->record->update(['is_read' => true]);
        }
    }
}
