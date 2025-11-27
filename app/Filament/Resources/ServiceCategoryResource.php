<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceCategoryResource\Pages;
use App\Models\ServiceCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ServiceCategoryResource extends Resource
{
    protected static ?string $model = ServiceCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Hizmetler';
    protected static ?string $navigationLabel = 'Kategoriler';
    protected static ?string $modelLabel = 'Hizmet Kategorisi';
    protected static ?string $pluralModelLabel = 'Hizmet Kategorileri';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->label('Kategori Adı')->required()->maxLength(255),
            Forms\Components\TextInput::make('slug')->label('URL')->required()->unique(ignoreRecord: true)->maxLength(255),
            Forms\Components\Textarea::make('description')->label('Açıklama')->rows(3),
            Forms\Components\TextInput::make('order')->label('Sıra')->numeric()->default(0),
            Forms\Components\Toggle::make('is_active')->label('Aktif')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->label('İsim'),
            Tables\Columns\TextColumn::make('slug')->label('URL'),
            Tables\Columns\TextColumn::make('order')->label('Sıra')->sortable(),
            Tables\Columns\IconColumn::make('is_active')->label('Durum')->boolean(),
        ])->actions([
            Tables\Actions\EditAction::make()->label('Düzenle'),
        ])->defaultSort('order', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceCategories::route('/'),
            'create' => Pages\CreateServiceCategory::route('/create'),
            'edit' => Pages\EditServiceCategory::route('/{record}/edit'),
        ];
    }

    /**
     * Provide navigation items for categories with Turkish labels and ordering.
     *
     * @return array<\Filament\Navigation\NavigationItem>
     */
    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make('Hizmet Kategorileri')
                ->group(static::getNavigationGroup())
                ->icon(static::getNavigationIcon())
                ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.service-categories.index'))
                ->url(static::getNavigationUrl())
                ->sort(2),

            NavigationItem::make('Yeni Kategori Ekle')
                ->group(static::getNavigationGroup())
                ->icon('heroicon-o-plus')
                ->url(route('filament.admin.resources.service-categories.create'))
                ->sort(4),
        ];
    }
}
