<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageCategoryResource\Pages;
use App\Filament\Resources\PageCategoryResource\RelationManagers;
use App\Models\PageCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class PageCategoryResource extends Resource
{
    protected static ?string $model = PageCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'İçerik Yönetimi';

    protected static ?string $navigationLabel = 'Menü Kategorileri';

    protected static ?string $modelLabel = 'Menü Kategorisi';

    protected static ?string $pluralModelLabel = 'Menü Kategorileri';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Kategori Bilgileri')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Kategori Adı')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => 
                                $set('slug', Str::slug($state))
                            )
                            ->maxLength(255),

                        Forms\Components\TextInput::make('slug')
                            ->label('URL')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText('URL için kullanılacak benzersiz isim'),

                        Forms\Components\TextInput::make('order')
                            ->label('Sıralama')
                            ->numeric()
                            ->default(0)
                            ->helperText('Menüde görünme sırası (küçükten büyüğe)'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->helperText('Kategori menüde görünsün mü?'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Kategori Adı')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('pages_count')
                    ->label('Sayfa Sayısı')
                    ->counts('pages')
                    ->sortable(),

                Tables\Columns\TextColumn::make('order')
                    ->label('Sıra')
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
            ->defaultSort('order', 'asc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktif Durum')
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPageCategories::route('/'),
            'create' => Pages\CreatePageCategory::route('/olustur'),
            'edit' => Pages\EditPageCategory::route('/{record}/duzenle'),
        ];
    }
}
