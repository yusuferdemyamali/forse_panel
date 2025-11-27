<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Ayarlar';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'site_name';

    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $navigationLabel = 'Genel Ayarlar';

    protected static ?string $modelLabel = 'Genel Ayar';

    protected static ?string $pluralModelLabel = 'Genel Ayarlar';

    public static function getNavigationUrl(): string
    {
        $record = static::getModel()::first();

        if ($record) {
            return static::getUrl('edit', ['record' => $record]);
        }

        return static::getUrl('create');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Site Ayarları')
                    ->label(fn () => __('page.general_settings.sections.site'))
                    ->icon('heroicon-o-globe-alt')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Grid::make()->schema([
                            Forms\Components\TextInput::make('site_name')
                                ->label('Firma Adı')
                                ->required(),
                            Forms\Components\Toggle::make('is_maintenance')
                                ->label('Bakım Modu')
                                ->helperText('Etkinleştirildiğinde, siteniz ziyaretçilere bir bakım sayfası gösterecektir')
                                ->required(),
                        ]),

                        Forms\Components\Section::make('Marka Ayarları')
                            ->label('Marka ve Görünüş')
                            ->description('Uygulamanızın görsel kimliğini özelleştirin')
                            ->icon('heroicon-o-photo')
                            ->collapsible()
                            ->schema([
                                Forms\Components\Grid::make()->schema([
                                    Forms\Components\FileUpload::make('site_logo')
                                        ->label('Site Logosu')
                                        ->image()
                                        ->directory('sites')
                                        ->visibility('public')
                                        ->moveFiles()
                                        ->helperText('Logonuzu yükleyin.'),

                                    Forms\Components\FileUpload::make('site_favicon')
                                        ->label('Site Faviconu')
                                        ->image()
                                        ->directory('sites')
                                        ->visibility('public')
                                        ->moveFiles()
                                        ->acceptedFileTypes(['image/x-icon', 'image/vnd.microsoft.icon', 'image/png', 'image/jpeg'])
                                        ->helperText('ICO, PNG ve JPG formatlarını destekler'),
                                ])->columns(2)->columnSpan(3),
                            ])->columns(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->latest('id'))
            ->columns([
                Tables\Columns\TextColumn::make('site_name')->label('Site Adı'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Sil')->modalHeading('Site Ayarlarını Sil'),
                ])->label('Toplu İşlemler'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiteSettings::route('/'),
            'create' => Pages\CreateSiteSetting::route('/create'),
            'edit' => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }
}
