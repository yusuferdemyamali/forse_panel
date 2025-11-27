<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Filament\Resources\PageResource\RelationManagers;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'İçerik Yönetimi';

    protected static ?string $navigationLabel = 'Menüler';

    protected static ?string $modelLabel = 'Menü';

    protected static ?string $pluralModelLabel = 'Menüler';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Sayfa İçeriği')
                            ->schema([
                                Forms\Components\Select::make('page_category_id')
                                    ->label('Kategori')
                                    ->relationship('pageCategory', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Kategori Adı')
                                            ->required(),
                                        Forms\Components\TextInput::make('slug')
                                            ->label('URL')
                                            ->required(),
                                    ]),

                                Forms\Components\TextInput::make('title')
                                    ->label('Başlık')
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

                                Forms\Components\RichEditor::make('content')
                                    ->label('İçerik')
                                    ->columnSpanFull()
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('pages/content-uploads'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Görsel ve Ayarlar')
                            ->schema([
                                Forms\Components\FileUpload::make('featured_image')
                                    ->label('Öne Çıkan Görsel')
                                    ->image()
                                    ->disk('public')
                                    ->directory('pages')
                                    ->imageEditor()
                                    ->columnSpanFull(),

                                Forms\Components\TextInput::make('order')
                                    ->label('Sıralama')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Kategori içinde görünme sırası'),

                                Forms\Components\Toggle::make('is_published')
                                    ->label('Yayında mı?')
                                    ->default(false)
                                    ->helperText('Sayfa web sitesinde görünsün mü?'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('pageCategory.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Yayında')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('order')
                    ->label('Sıra')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('page_category_id')
                    ->label('Kategori')
                    ->relationship('pageCategory', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Yayın Durumu')
                    ->placeholder('Tümü')
                    ->trueLabel('Sadece Yayında')
                    ->falseLabel('Sadece Taslak'),
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/olustur'),
            'edit' => Pages\EditPage::route('/{record}/duzenle'),
        ];
    }
}
