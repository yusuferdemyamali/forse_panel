<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use App\Models\ServiceCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'Hizmetler';
    protected static ?string $navigationLabel = 'Hizmetler';
    protected static ?string $modelLabel = 'Hizmet';
    protected static ?string $pluralModelLabel = 'Hizmetler';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->label('Hizmet Adı')->required()->maxLength(255),
            Forms\Components\TextInput::make('slug')->label('URL')->required()->unique(ignoreRecord: true)->maxLength(255),
            Forms\Components\Select::make('service_category_id')
                ->label('Kategori')
                ->relationship('serviceCategory', 'name')
                ->options(ServiceCategory::where('is_active', true)->pluck('name', 'id'))
                ->required()
                ->searchable()
                ->preload(),
            Forms\Components\RichEditor::make('description')->label('Açıklama')->columnSpanFull(),
            Forms\Components\TextInput::make('price')->label('Fiyat')->numeric()->prefix('₺')->nullable(),
            Forms\Components\FileUpload::make('image')->label('Görsel')->image()->directory('services')->visibility('public')->nullable(),
            Forms\Components\TextInput::make('order')->label('Sıra')->numeric()->default(0),
            Forms\Components\Toggle::make('is_active')->label('Aktif')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->modifyQueryUsing(fn ($query) => $query->with('serviceCategory'))
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable()->label('İsim'),
                Tables\Columns\TextColumn::make('serviceCategory.name')->label('Kategori')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->label('URL')->toggleable(),
                Tables\Columns\TextColumn::make('price')->money('TRY')->label('Fiyat'),
                Tables\Columns\IconColumn::make('is_active')->label('Durum')->boolean(),
            ])->actions([
                Tables\Actions\EditAction::make()->label('Düzenle'),
            ])->defaultSort('order', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }

    /**
     * Provide multiple navigation items (liste ve yeni ekle linkleri) with Turkish labels and explicit ordering.
     *
     * @return array<\Filament\Navigation\NavigationItem>
     */
    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make('Tüm Hizmetler')
                ->group(static::getNavigationGroup())
                ->icon(static::getNavigationIcon())
                ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.services.index'))
                ->url(static::getNavigationUrl())
                ->sort(1),

            NavigationItem::make('Yeni Hizmet Ekle')
                ->group(static::getNavigationGroup())
                ->icon('heroicon-o-plus')
                ->url(route('filament.admin.resources.services.create'))
                ->sort(3),
        ];
    }
}
