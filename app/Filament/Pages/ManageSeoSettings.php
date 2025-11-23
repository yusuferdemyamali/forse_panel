<?php

namespace App\Filament\Pages;

use App\Settings\SeoSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageSeoSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?string $navigationLabel = 'SEO Ayarları';

    protected static ?string $title = 'SEO Ayarları';
    
    protected static ?string $navigationGroup = 'Site Yönetimi';

    protected static string $settings = SeoSettings::class;
    
    protected static ?int $navigationSort = 7;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('SEO Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Genel Ayarlar')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Forms\Components\Section::make('Temel Bilgiler')
                                    ->description('Sitenizin temel SEO bilgileri')
                                    ->schema([
                                        Forms\Components\TextInput::make('site_name')
                                            ->label('Site Adı')
                                            ->required()
                                            ->maxLength(60)
                                            ->placeholder('Ares Asansör - Akıllı Çözümler İle Yükselın')
                                            ->helperText('Maksimum 60 karakter önerilir')
                                            ->hint(fn ($state): string => (strlen($state ?? '') ?: 0) . ' / 60'),

                                        Forms\Components\Textarea::make('site_description')
                                            ->label('Site Açıklaması')
                                            ->required()
                                            ->rows(3)
                                            ->maxLength(160)
                                            ->placeholder('Ares Asansör, Türkiye genelinde asansör montajı, bakımı ve modernizasyonu konusunda hizmet vermektedir.')
                                            ->helperText('Arama sonuçlarında görünecek açıklama. 155-160 karakter ideal.')
                                            ->hint(fn ($state): string => (strlen($state ?? '') ?: 0) . ' / 160'),

                                        Forms\Components\Textarea::make('site_keywords')
                                            ->label('Site Anahtar Kelimeleri')
                                            ->rows(2)
                                            ->placeholder('asansör, asansör bakımı, asansör montajı, istanbul asansör')
                                            ->helperText('Virgülle ayırın. Örn: asansör, bakım, modernizasyon')
                                            ->columnSpanFull(),

                                        Forms\Components\TextInput::make('site_author')
                                            ->label('Site Yazarı / Şirket')
                                            ->placeholder('Ares Asansör')
                                            ->default('Ares Asansör'),
                                    ])
                                    ->columns(2),

                                Forms\Components\Section::make('Arama Motoru Kontrolü')
                                    ->schema([
                                        Forms\Components\Toggle::make('allow_search_engines')
                                            ->label('Arama Motorlarına İzin Ver')
                                            ->helperText('Kapatıldığında site arama motorlarında görünmez (noindex)')
                                            ->default(true)
                                            ->inline(false),

                                        Forms\Components\Textarea::make('robots_txt_additions')
                                            ->label('Robots.txt Ek Kuralları')
                                            ->rows(4)
                                            ->placeholder("User-agent: *\nDisallow: /admin")
                                            ->helperText('robots.txt dosyasına eklenecek özel kurallar'),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Sosyal Medya')
                            ->icon('heroicon-o-share')
                            ->schema([
                                Forms\Components\Section::make('Open Graph (Facebook, LinkedIn)')
                                    ->description('Sosyal medya paylaşımları için görsel ve bilgiler')
                                    ->schema([
                                        Forms\Components\FileUpload::make('og_image')
                                            ->label('OG Varsayılan Görseli')
                                            ->image()
                                            ->imageEditor()
                                            ->imageEditorAspectRatios([
                                                '1.91:1',
                                            ])
                                            ->maxSize(2048)
                                            ->disk('public')
                                            ->directory('seo/og-images')
                                            ->helperText('1200x630 px önerilir. Facebook, LinkedIn paylaşımlarında kullanılır.')
                                            ->columnSpanFull(),

                                        Forms\Components\Select::make('og_type')
                                            ->label('OG Type')
                                            ->options([
                                                'website' => 'Website',
                                                'article' => 'Article',
                                                'business.business' => 'Business',
                                            ])
                                            ->default('website')
                                            ->required(),

                                        Forms\Components\Select::make('og_locale')
                                            ->label('Dil / Bölge (Locale)')
                                            ->options([
                                                'tr_TR' => 'Türkçe (Türkiye)',
                                                'en_US' => 'English (US)',
                                                'en_GB' => 'English (UK)',
                                            ])
                                            ->default('tr_TR')
                                            ->required(),
                                    ])
                                    ->columns(2),

                                Forms\Components\Section::make('Twitter Card')
                                    ->schema([
                                        Forms\Components\Select::make('twitter_card_type')
                                            ->label('Twitter Card Tipi')
                                            ->options([
                                                'summary' => 'Summary (Küçük görsel)',
                                                'summary_large_image' => 'Summary Large Image (Büyük görsel)',
                                            ])
                                            ->default('summary_large_image')
                                            ->required(),

                                        Forms\Components\TextInput::make('twitter_site')
                                            ->label('Twitter Site (@kullaniciadi)')
                                            ->placeholder('@aresasansor')
                                            ->helperText('Twitter kullanıcı adınız (@ ile başlayan)'),

                                        Forms\Components\TextInput::make('twitter_creator')
                                            ->label('Twitter Creator (@kullaniciadi)')
                                            ->placeholder('@yusuf')
                                            ->helperText('İçerik oluşturan kişinin Twitter hesabı'),
                                    ])
                                    ->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make('Analytics & Tracking')
                            ->icon('heroicon-o-chart-bar')
                            ->schema([
                                Forms\Components\Section::make('Google Tracking')
                                    ->schema([
                                        Forms\Components\TextInput::make('google_analytics_id')
                                            ->label('Google Analytics ID')
                                            ->placeholder('G-XXXXXXXXXX veya UA-XXXXXXXXX')
                                            ->helperText('Google Analytics ölçüm kimliği')
                                            ->prefixIcon('heroicon-m-chart-bar'),

                                        Forms\Components\TextInput::make('google_tag_manager_id')
                                            ->label('Google Tag Manager ID')
                                            ->placeholder('GTM-XXXXXXX')
                                            ->helperText('Google Tag Manager container kimliği')
                                            ->prefixIcon('heroicon-m-code-bracket'),
                                    ])
                                    ->columns(2),

                                Forms\Components\Section::make('Diğer Tracking')
                                    ->schema([
                                        Forms\Components\TextInput::make('facebook_pixel_id')
                                            ->label('Facebook Pixel ID')
                                            ->placeholder('123456789012345')
                                            ->helperText('Facebook Pixel kimliği (Meta Ads için)')
                                            ->prefixIcon('heroicon-m-megaphone'),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Site Doğrulama')
                            ->icon('heroicon-o-shield-check')
                            ->schema([
                                Forms\Components\Section::make('Arama Motoru Doğrulama')
                                    ->description('Arama motorlarına site sahipliğinizi doğrulamak için')
                                    ->schema([
                                        Forms\Components\TextInput::make('google_site_verification')
                                            ->label('Google Site Verification')
                                            ->placeholder('abc123def456...')
                                            ->helperText('Google Search Console doğrulama kodu')
                                            ->columnSpanFull(),

                                        Forms\Components\TextInput::make('bing_site_verification')
                                            ->label('Bing Webmaster Verification')
                                            ->placeholder('123ABC456DEF...')
                                            ->helperText('Bing Webmaster Tools doğrulama kodu')
                                            ->columnSpanFull(),

                                        Forms\Components\TextInput::make('yandex_verification')
                                            ->label('Yandex Verification')
                                            ->placeholder('abc123...')
                                            ->helperText('Yandex Webmaster doğrulama kodu')
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Özel Kodlar')
                            ->icon('heroicon-o-code-bracket')
                            ->schema([
                                Forms\Components\Section::make('Custom Scripts')
                                    ->description('Özel tracking, widget veya script kodları')
                                    ->schema([
                                        Forms\Components\Textarea::make('head_scripts')
                                            ->label('Head Scripts')
                                            ->rows(5)
                                            ->placeholder('<script>...</script>')
                                            ->helperText('</head> etiketinden önce çalışacak kodlar')
                                            ->columnSpanFull(),

                                        Forms\Components\Textarea::make('body_scripts')
                                            ->label('Body Scripts (Üst)')
                                            ->rows(5)
                                            ->placeholder('<script>...</script>')
                                            ->helperText('<body> etiketinden hemen sonra çalışacak kodlar')
                                            ->columnSpanFull(),

                                        Forms\Components\Textarea::make('footer_scripts')
                                            ->label('Footer Scripts')
                                            ->rows(5)
                                            ->placeholder('<script>...</script>')
                                            ->helperText('</body> etiketinden önce çalışacak kodlar (sayfa sonu)')
                                            ->columnSpanFull(),
                                    ]),

                                Forms\Components\Section::make('⚠️ Uyarı')
                                    ->schema([
                                        Forms\Components\Placeholder::make('warning')
                                            ->content('Özel kod alanlarına eklediğiniz kodlar doğrudan sayfaya eklenir. Güvenli olmayan kodlar site güvenliğini tehlikeye atabilir. Sadece güvenilir kaynaklardan gelen kodları kullanın.')
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsed(),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->persistTabInQueryString(),
            ]);
    }
}
