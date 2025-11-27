<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RedirectResource\Pages;
use App\Models\Redirect;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RedirectResource extends Resource
{
    protected static ?string $model = Redirect::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-right-circle';

    protected static ?string $navigationGroup = 'Ayarlar';

    protected static ?string $navigationLabel = 'Yönlendirmeler';

    protected static ?string $modelLabel = 'Yönlendirme';

    protected static ?string $pluralModelLabel = 'Yönlendirmeler';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Yönlendirme Bilgileri')
                    ->description('URL yönlendirme ayarlarını yapılandırın')
                    ->schema([
                        Forms\Components\TextInput::make('source_url')
                            ->label('Eski URL (Kaynak)')
                            ->required()
                            ->prefix('/')
                            ->placeholder('eski-sayfa')
                            ->helperText('Yönlendirilecek eski URL. Örn: /eski-hizmetim')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->rule('regex:/^[a-z0-9\-\/]+$/'),

                        Forms\Components\TextInput::make('destination_url')
                            ->label('Yeni URL (Hedef)')
                            ->required()
                            ->placeholder('yeni-sayfa veya https://dis-site.com')
                            ->helperText('Yönlendirilecek yeni URL. İç sayfa için: /yeni-sayfa, Dış site için: https://example.com')
                            ->maxLength(255),

                        Forms\Components\Select::make('status_code')
                            ->label('Durum Kodu')
                            ->options([
                                301 => '301 - Kalıcı Yönlendirme (SEO için önerilir)',
                                302 => '302 - Geçici Yönlendirme',
                            ])
                            ->default(301)
                            ->required()
                            ->helperText('301: Kalıcı değişiklikler için, 302: Geçici durumlar için'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->helperText('Yönlendirme aktif olsun mu?'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('source_url')
                    ->label('Eski URL')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-arrow-left'),

                Tables\Columns\TextColumn::make('destination_url')
                    ->label('Yeni URL')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-arrow-right')
                    ->limit(50),

                Tables\Columns\BadgeColumn::make('status_code')
                    ->label('Kod')
                    ->colors([
                        'success' => 301,
                        'warning' => 302,
                    ])
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status_code')
                    ->label('Durum Kodu')
                    ->options([
                        301 => '301 (Kalıcı)',
                        302 => '302 (Geçici)',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Durum')
                    ->placeholder('Tümü')
                    ->trueLabel('Sadece Aktif')
                    ->falseLabel('Sadece Pasif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRedirects::route('/'),
            'create' => Pages\CreateRedirect::route('/create'),
            'edit' => Pages\EditRedirect::route('/{record}/edit'),
        ];
    }
}
