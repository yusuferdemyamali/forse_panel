<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Models\Blog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Navigation\NavigationItem;
use Filament\Tables;
use Filament\Tables\Table;
use Str;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationLabel = 'Blog Yazıları';

    protected static ?string $modelLabel = 'Blog Yazısı';

    protected static ?string $pluralModelLabel = 'Blog Yazıları';

    protected static ?string $navigationGroup = 'Blog';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Blog İçeriği')
                            ->description('Blog yazınızın ana içeriği')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->label('Başlık')
                                    ->live(onBlur: true)
                                    ->maxLength(255)
                                    ->placeholder('Başlık Girin')
                                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                                Forms\Components\TextInput::make('slug')
                                    ->disabled()
                                    ->label('URL')
                                    ->dehydrated()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(Blog::class, 'slug', ignoreRecord: true)
                                    ->helperText('URL başlığınızdan otomatik olarak oluşturulacak.')
                                    ->suffixAction(function (string $operation) {
                                        if ($operation === 'edit') {
                                            return Forms\Components\Actions\Action::make('editSlug')
                                                ->icon('heroicon-o-pencil-square')
                                                ->modalHeading('URL düzenle')
                                                ->modalDescription('Bu yazının URL’sini özelleştirin. Sadece küçük harfler, rakamlar ve tireler kullanın.')
                                                ->modalIcon('heroicon-o-link')
                                                ->modalSubmitActionLabel('URL’yi Güncelle')
                                                ->form([
                                                    Forms\Components\TextInput::make('new_slug')
                                                        ->hiddenLabel()
                                                        ->required()
                                                        ->maxLength(255)
                                                        ->live(debounce: 500)
                                                        ->afterStateUpdated(function (string $state, Forms\Set $set) {
                                                            $set('new_slug', Str::slug($state));
                                                        })
                                                        ->unique(Blog::class, 'slug', ignoreRecord: true)
                                                        ->helperText('Yazdıkça URL otomatik olarak biçimlendirilecektir.'),
                                                ])
                                                ->action(function (array $data, Forms\Set $set) {
                                                    $set('slug', $data['new_slug']);

                                                    Notification::make()
                                                        ->title('URL güncellendi')
                                                        ->success()
                                                        ->send();
                                                });
                                        }

                                        return null;
                                    }),

                                Forms\Components\Textarea::make('excerpt')
                                    ->required()
                                    ->label('Özet')
                                    ->placeholder('Bu yazının kısa bir özetini veya alıntısını sağlayın')
                                    ->helperText('Bu, blog listeleme sayfasında görünecektir')
                                    ->rows(5),

                                Forms\Components\RichEditor::make('content')
                                    ->toolbarButtons([
                                        'attachFiles',
                                        'blockquote',
                                        'bold',
                                        'bulletList',
                                        'codeBlock',
                                        'h1',
                                        'h2',
                                        'h3',
                                        'italic',
                                        'link',
                                        'orderedList',
                                        'redo',
                                        'strike',
                                        'underline',
                                        'undo',
                                    ])
                                    ->required()
                                    ->label('İçerik')
                                    ->placeholder('Buraya yazı içeriğinizi yazın...')
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('blog/content-uploads')
                                    ->columnSpanFull()
                                    ->maxLength(65535)
                                    ->helperText('Yukarıdaki araç çubuğunu kullanarak içeriğinizi biçimlendirin')
                                    ->hint(function (Get $get): string {
                                        $wordCount = str_word_count(strip_tags($get('content')));
                                        $readingTime = ceil($wordCount / 200); // Assuming 200 words per minute

                                        return "{$wordCount} kelime | ~{$readingTime} dk okuma süresi";
                                    })
                                    ->extraInputAttributes(['style' => 'min-height: 500px;']),
                            ]),

                        Forms\Components\Section::make('Medya')
                            ->label('Medya')
                            ->description('Gönderiniz için görsel unsurlar')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Forms\Components\FileUpload::make('thumbnail')
                                    ->label('Dış Resim')
                                    ->collection('thumbnails')
                                    ->image()
                                    ->imageResizeMode('contain')
                                    ->imageCropAspectRatio('16:9')
                                    ->imageResizeTargetWidth('1200')
                                    ->imageResizeTargetHeight('675')
                                    ->helperText('Bu resim, gönderi listeleme sayfalarında ve sosyal paylaşımlarda belirgin bir şekilde görüntülenecektir (16:9 oranı önerilir)')
                                    ->downloadable()
                                    ->responsiveImages(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Görünürlük')
                            ->description('Bu gönderinin nasıl görüneceğini kontrol edin')
                            ->icon('heroicon-o-eye')
                            ->schema([
                                Forms\Components\Select::make('is_active')
                                    ->options([
                                        1 => 'Aktif',
                                        0 => 'Pasif',
                                    ])
                                    ->default(1)
                                    ->label('Durum')
                                    ->live()
                                    ->required(),
                            ]),

                        Forms\Components\Section::make('Kategorizasyon')
                            ->description('Bu gönderiyi organize edin ve sınıflandırın')
                            ->icon('heroicon-o-tag')
                            ->schema([
                                Forms\Components\Select::make('blog_category_id')
                                    ->label('Kategori')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Kategori Seçin')
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Kategori Adı')
                                            ->required(),
                                        Forms\Components\Hidden::make('slug')
                                            ->default(fn ($state) => Str::slug($state['name'] ?? '')),
                                    ])
                                    ->required(),

                                Forms\Components\TextInput::make('order')
                                    ->label('Sıra')
                                    ->numeric()
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Sıra numarasını girin'),
                            ]),

                        Forms\Components\Section::make('🚀 SEO Optimizasyonu')
                            ->description('Arama motoru optimizasyonu için meta bilgileri düzenleyin')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                Forms\Components\Tabs::make('SEO Tabs')
                                    ->tabs([
                                        Forms\Components\Tabs\Tab::make('Temel SEO')
                                            ->icon('heroicon-o-document-text')
                                            ->schema([
                                                Forms\Components\TextInput::make('meta_title')
                                                    ->label('SEO Başlığı')
                                                    ->maxLength(60)
                                                    ->placeholder('Google\'da görünecek başlık')
                                                    ->helperText('Boş bırakılırsa yazı başlığı kullanılır. Maksimum 60 karakter önerilir.')
                                                    ->live(debounce: 500)
                                                    ->afterStateUpdated(function (Get $get, Forms\Set $set, ?string $state) {
                                                        $length = strlen($state ?? '');
                                                        $color = $length > 60 ? 'danger' : ($length > 50 ? 'warning' : 'success');
                                                    })
                                                    ->suffixIcon('heroicon-m-information-circle')
                                                    ->hint(fn (Get $get): string => 
                                                        (strlen($get('meta_title') ?? '') ?: 0) . ' / 60 karakter'
                                                    ),
                                                
                                                Forms\Components\Textarea::make('meta_description')
                                                    ->label('SEO Açıklaması')
                                                    ->maxLength(160)
                                                    ->rows(3)
                                                    ->placeholder('Arama sonuçlarında görünecek kısa açıklama')
                                                    ->helperText('Boş bırakılırsa özet kullanılır. 155-160 karakter arası optimal.')
                                                    ->hint(fn (Get $get): string => 
                                                        (strlen($get('meta_description') ?? '') ?: 0) . ' / 160 karakter'
                                                    ),
                                                
                                                Forms\Components\TextInput::make('focus_keyword')
                                                    ->label('Odak Anahtar Kelime')
                                                    ->placeholder('Örn: asansör bakımı')
                                                    ->helperText('Bu yazının hedeflediği ana anahtar kelime')
                                                    ->maxLength(255),
                                                
                                                Forms\Components\TextInput::make('meta_keywords')
                                                    ->label('Anahtar Kelimeler (Meta Keywords)')
                                                    ->placeholder('asansör, bakım, modernizasyon')
                                                    ->helperText('Virgülle ayırın. Google kullanmıyor ancak diğer arama motorları için faydalı.')
                                                    ->maxLength(255),
                                                
                                                Forms\Components\TextInput::make('canonical_url')
                                                    ->label('Canonical URL')
                                                    ->url()
                                                    ->placeholder('https://aresasansor.com/blog/yaziadi')
                                                    ->helperText('Boş bırakılırsa otomatik oluşturulur. Duplicate content önleme için kullanılır.')
                                                    ->maxLength(255),
                                            ]),
                                        
                                        Forms\Components\Tabs\Tab::make('Sosyal Medya (Open Graph)')
                                            ->icon('heroicon-o-share')
                                            ->schema([
                                                Forms\Components\TextInput::make('og_title')
                                                    ->label('OG Başlığı')
                                                    ->maxLength(70)
                                                    ->placeholder('Facebook/LinkedIn paylaşımlarında görünecek başlık')
                                                    ->helperText('Boş bırakılırsa SEO başlığı kullanılır.')
                                                    ->hint(fn (Get $get): string => 
                                                        (strlen($get('og_title') ?? '') ?: 0) . ' / 70 karakter'
                                                    ),
                                                
                                                Forms\Components\Textarea::make('og_description')
                                                    ->label('OG Açıklaması')
                                                    ->maxLength(200)
                                                    ->rows(3)
                                                    ->placeholder('Sosyal medya paylaşımlarında görünecek açıklama')
                                                    ->helperText('Boş bırakılırsa meta açıklama kullanılır.')
                                                    ->hint(fn (Get $get): string => 
                                                        (strlen($get('og_description') ?? '') ?: 0) . ' / 200 karakter'
                                                    ),
                                                
                                                Forms\Components\FileUpload::make('og_image')
                                                    ->label('OG Görseli')
                                                    ->collection('og_images')
                                                    ->image()
                                                    ->imageResizeMode('cover')
                                                    ->imageCropAspectRatio('1.91:1')
                                                    ->imageResizeTargetWidth('1200')
                                                    ->imageResizeTargetHeight('630')
                                                    ->helperText('1200x630 px önerilir. Boş bırakılırsa thumbnail kullanılır.')
                                                    ->maxSize(2048),
                                            ]),
                                        
                                        Forms\Components\Tabs\Tab::make('Twitter Card')
                                            ->icon('heroicon-o-chat-bubble-left-ellipsis')
                                            ->schema([
                                                Forms\Components\TextInput::make('twitter_title')
                                                    ->label('Twitter Başlığı')
                                                    ->maxLength(70)
                                                    ->placeholder('Twitter\'da görünecek başlık')
                                                    ->helperText('Boş bırakılırsa OG başlığı kullanılır.'),
                                                
                                                Forms\Components\Textarea::make('twitter_description')
                                                    ->label('Twitter Açıklaması')
                                                    ->maxLength(200)
                                                    ->rows(3)
                                                    ->placeholder('Twitter\'da görünecek açıklama')
                                                    ->helperText('Boş bırakılırsa OG açıklaması kullanılır.'),
                                                
                                                Forms\Components\FileUpload::make('twitter_image')
                                                    ->label('Twitter Görseli')
                                                    ->collection('twitter_images')
                                                    ->image()
                                                    ->imageResizeMode('cover')
                                                    ->imageCropAspectRatio('1.91:1')
                                                    ->imageResizeTargetWidth('1200')
                                                    ->imageResizeTargetHeight('628')
                                                    ->helperText('1200x628 px önerilir. Boş bırakılırsa OG görseli kullanılır.')
                                                    ->maxSize(2048),
                                            ]),
                                        
                                        Forms\Components\Tabs\Tab::make('Gelişmiş')
                                            ->icon('heroicon-o-cog-6-tooth')
                                            ->schema([
                                                Forms\Components\Toggle::make('index_page')
                                                    ->label('Sayfayı İndeksle')
                                                    ->helperText('Arama motorlarının bu sayfayı indekslemesine izin ver')
                                                    ->default(true)
                                                    ->inline(false),
                                                
                                                Forms\Components\Toggle::make('follow_links')
                                                    ->label('Linkleri Takip Et')
                                                    ->helperText('Arama motorlarının bu sayfadaki linkleri takip etmesine izin ver')
                                                    ->default(true)
                                                    ->inline(false),
                                                
                                                Forms\Components\Placeholder::make('seo_preview')
                                                    ->label('SEO Önizleme')
                                                    ->content(fn (Get $get): string => 
                                                        '<div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; background: #f9fafb;">
                                                            <div style="color: #1e40af; font-size: 18px; margin-bottom: 4px;">
                                                                ' . ($get('meta_title') ?: $get('title') ?: 'Blog Başlığı') . '
                                                            </div>
                                                            <div style="color: #059669; font-size: 14px; margin-bottom: 4px;">
                                                                https://aresasansor.com/blog/' . ($get('slug') ?: 'url') . '
                                                            </div>
                                                            <div style="color: #4b5563; font-size: 14px;">
                                                                ' . Str::limit($get('meta_description') ?: $get('excerpt') ?: 'Meta açıklama burada görünecek...', 160) . '
                                                            </div>
                                                        </div>'
                                                    )
                                                    ->columnSpanFull(),
                                            ]),
                                    ])
                                    ->columnSpanFull()
                                    ->persistTabInQueryString(),
                            ])
                            ->collapsible()
                            ->collapsed(),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('category')) // N+1 query çözümü
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Durum')
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('views')
                    ->label('Görüntülenme Sayısı')
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Yayınlanma Tarihi')
                    ->date()
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('is_active')
                    ->options([
                        1 => 'Aktif',
                        0 => 'Pasif',
                    ]),
                Tables\Filters\SelectFilter::make('blog_category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Düzenle'),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('publishSelected')
                    ->label('Yayınla')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function ($records): void {
                        foreach ($records as $record) {
                            $record->update([
                                'is_active' => true,
                                'published_at' => now(),
                            ]);
                        }
                        Notification::make()
                            ->title('Selected posts published successfully')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation(),

                Tables\Actions\BulkAction::make('deactivateSelected')
                    ->label('Pasifleştir')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->action(function ($records): void {
                        foreach ($records as $record) {
                            $record->update([
                                'is_active' => false,
                                'published_at' => null,
                            ]);
                        }
                        Notification::make()
                            ->title('Selected posts deactivated successfully')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation(),

                Tables\Actions\DeleteBulkAction::make()
                    ->label('Sil')
                    ->modalHeading('Blog Yazılarını Sil'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/olustur'),
            'edit' => Pages\EditBlog::route('/{record}/duzenle'),
        ];
    }

    /**
     * Override to provide multiple navigation items for the Blog resource
     * (listeleme ve yeni ekleme linkleri).
     *
     * @return array<\Filament\Navigation\NavigationItem>
     */
    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make('Tüm yazılar')
                ->group(static::getNavigationGroup())
                ->icon(static::getNavigationIcon())
                ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.blogs.index'))
                ->url(static::getNavigationUrl())
                ->sort(1),

            NavigationItem::make('Yeni yazı ekle')
                ->group(static::getNavigationGroup())
                ->icon('heroicon-o-plus')
                ->url(route('filament.admin.resources.blogs.create'))
                ->sort(3),
        ];
    }
}
