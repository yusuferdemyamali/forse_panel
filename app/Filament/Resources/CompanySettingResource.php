<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanySettingResource\Pages;
use App\Models\CompanySetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CompanySettingResource extends Resource
{
    protected static ?string $model = CompanySetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'Ayarlar';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'site_name';

    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $navigationLabel = 'Firma Ayarları';

    protected static ?string $modelLabel = 'Firma Ayarı';

    protected static ?string $pluralModelLabel = 'Firma Ayarları';

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
                Forms\Components\Section::make('Firma Ayarları')
                    ->label(fn () => __('page.general_settings.sections.site'))
                    ->icon('heroicon-o-globe-alt')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Grid::make()->schema([
                            Forms\Components\TextInput::make('phone_number')
                                ->label('Telefon Numarası')
                                ->tel()
                                ->helperText('Var ise firma telefon numarasını girin'),
                            Forms\Components\TextInput::make('email')
                                ->label('E-posta')
                                ->email()
                                ->helperText('Var ise firma e-posta adresini girin'),
                            Forms\Components\TextInput::make('address')
                                ->label('Adres')
                                ->helperText('Var ise firma adresini girin'),
                            Forms\Components\TextInput::make('working_hours')
                                ->label('Çalışma Saatleri')
                                ->helperText('Var ise firma çalışma saatlerini girin'),
                        ])->columns(2)->columnSpan(3),
                        ]),

                        Forms\Components\Section::make('Sosyal Medya Ayarları')
                            ->label('Sosyal Medya Ayarları')
                            ->description('Uygulamanızın sosyal medya bağlantılarını yönetin')
                            ->icon('heroicon-o-photo')
                            ->collapsible()
                            ->schema([
                                Forms\Components\Grid::make()->schema([
                                    Forms\Components\TextInput::make('facebook_url')
                                         ->label('Facebook URL')
                                         ->url()
                                         ->helperText('Facebook sayfa URL\'sini girin'),
                                    Forms\Components\TextInput::make('x_url')
                                         ->label('X URL')
                                         ->url()
                                         ->helperText('X (Twitter) profil URL\'sini girin'),
                                    Forms\Components\TextInput::make('instagram_url')
                                         ->label('Instagram URL')
                                         ->url()
                                         ->helperText('Instagram profil URL\'sini girin'),
                                    Forms\Components\TextInput::make('linkedin_url')
                                         ->label('LinkedIn URL')
                                         ->url()
                                         ->helperText('LinkedIn sayfa URL\'sini girin'),
                                    Forms\Components\TextInput::make('youtube_url')
                                         ->label('YouTube URL')
                                         ->url()
                                         ->helperText('YouTube kanal URL\'sini girin'),
                                ])->columns(2)->columnSpan(3),
                            ]),
                    ])->columns(3);
            
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->latest('id'))
            ->columns([
                Tables\Columns\TextColumn::make('phone_number')->label('Telefon Numarası')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->label('E-posta')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('address')->label('Adres')->limit(50)->sortable()->searchable(),
                Tables\Columns\TextColumn::make('working_hours')->label('Çalışma Saatleri')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Oluşturulma Tarihi')->dateTime()->sortable(),
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
            'index' => Pages\ListCompanySettings::route('/'),
            'create' => Pages\CreateCompanySetting::route('/create'),
            'edit' => Pages\EditCompanySetting::route('/{record}/edit'),
        ];
    }
}
