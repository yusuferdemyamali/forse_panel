<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'İletişim Mesajları';

    protected static ?string $modelLabel = 'Mesaj';

    protected static ?string $pluralModelLabel = 'Mesajlar';

    protected static ?string $navigationGroup = 'İletişim';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Mesaj Detayları')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Ad Soyad')
                            ->disabled(),
                        
                        Forms\Components\TextInput::make('email')
                            ->label('E-posta')
                            ->disabled(),
                        
                        Forms\Components\TextInput::make('phone')
                            ->label('Telefon')
                            ->disabled(),
                        
                        Forms\Components\TextInput::make('subject')
                            ->label('Konu')
                            ->disabled()
                            ->columnSpanFull(),
                        
                        Forms\Components\Textarea::make('message')
                            ->label('Mesaj')
                            ->disabled()
                            ->rows(6)
                            ->columnSpanFull(),
                        
                        Forms\Components\Toggle::make('is_read')
                            ->label('Okundu')
                            ->helperText('Mesajı okundu olarak işaretle'),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Tarih Bilgisi')
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Gönderim Tarihi')
                            ->content(fn (ContactMessage $record): string => 
                                $record->created_at->format('d.m.Y H:i')
                            ),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\IconColumn::make('is_read')
                    ->label('Durum')
                    ->boolean()
                    ->trueIcon('heroicon-o-envelope-open')
                    ->falseIcon('heroicon-o-envelope')
                    ->trueColor('success')
                    ->falseColor('warning')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('name')
                    ->label('Ad Soyad')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('email')
                    ->label('E-posta')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-envelope'),
                
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable()
                    ->toggleable()
                    ->icon('heroicon-o-phone'),
                
                Tables\Columns\TextColumn::make('subject')
                    ->label('Konu')
                    ->searchable()
                    ->limit(30)
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('message')
                    ->label('Mesaj')
                    ->limit(50)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_read')
                    ->label('Okunma Durumu')
                    ->placeholder('Tümü')
                    ->trueLabel('Okunmuş')
                    ->falseLabel('Okunmamış'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Görüntüle'),
                
                Tables\Actions\Action::make('markAsRead')
                    ->label('Okundu İşaretle')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(fn (ContactMessage $record) => $record->update(['is_read' => true]))
                    ->visible(fn (ContactMessage $record): bool => !$record->is_read)
                    ->requiresConfirmation(false),
                
                Tables\Actions\DeleteAction::make()
                    ->label('Sil'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('markAsRead')
                        ->label('Okundu İşaretle')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_read' => true])),
                    
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Sil'),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Gönderen Bilgileri')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label('Ad Soyad')
                                    ->icon('heroicon-o-user')
                                    ->size('lg'),
                                
                                Infolists\Components\TextEntry::make('email')
                                    ->label('E-posta')
                                    ->icon('heroicon-o-envelope')
                                    ->copyable()
                                    ->url(fn ($record) => "mailto:{$record->email}"),
                                
                                Infolists\Components\TextEntry::make('phone')
                                    ->label('Telefon')
                                    ->icon('heroicon-o-phone')
                                    ->visible(fn ($record) => !empty($record->phone)),
                                
                                Infolists\Components\TextEntry::make('subject')
                                    ->label('Konu')
                                    ->icon('heroicon-o-tag')
                                    ->visible(fn ($record) => !empty($record->subject)),
                            ]),
                    ])
                    ->collapsible(),
                
                Infolists\Components\Section::make('Mesaj İçeriği')
                    ->schema([
                        Infolists\Components\TextEntry::make('message')
                            ->label('')
                            ->prose()
                            ->columnSpanFull(),
                    ]),
                
                Infolists\Components\Section::make('Detaylar')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Gönderim Tarihi')
                                    ->dateTime('d.m.Y H:i')
                                    ->icon('heroicon-o-clock'),
                                
                                Infolists\Components\IconEntry::make('is_read')
                                    ->label('Okunma Durumu')
                                    ->boolean()
                                    ->trueIcon('heroicon-o-check-circle')
                                    ->falseIcon('heroicon-o-x-circle')
                                    ->trueColor('success')
                                    ->falseColor('danger'),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactMessages::route('/'),
            'view' => Pages\ViewContactMessage::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_read', false)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('is_read', false)->count() > 0 ? 'warning' : null;
    }
}
