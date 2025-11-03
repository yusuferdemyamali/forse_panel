<?php

namespace App\Filament\Pages;

use App\Settings\SeoSettings;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageSeoSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static string $settings = SeoSettings::class;

    protected static ?string $navigationGroup = 'Site Yönetimi';

    protected static ?string $navigationLabel = 'SEO Ayarları';

    protected static ?string $title = 'SEO Ayarları';

    protected static ?int $navigationSort = 7;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Genel SEO Ayarları')
                    ->description('Site genelinde kullanılacak temel SEO bilgileri')
                    ->icon('heroicon-o-globe-alt')
                    ->schema([
                        TextInput::make('site_name')
                            ->label('Site Adı')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Arama motorlarında ve tarayıcı başlığında görünecek site adı'),

                        Textarea::make('site_description')
                            ->label('Site Meta Açıklaması')
                            ->rows(3)
                            ->maxLength(160)
                            ->helperText('Yaklaşık 160 karakter idealdir. Arama sonuçlarında görünür.')
                            ->columnSpanFull(),
                    ]),

                Section::make('Sosyal Medya Ayarları')
                    ->description('Sosyal medya paylaşımları için Open Graph ayarları')
                    ->icon('heroicon-o-share')
                    ->collapsible()
                    ->schema([
                        FileUpload::make('og_image')
                            ->label('Genel Sosyal Medya (OG) Görseli')
                            ->image()
                            ->disk('public')
                            ->directory('seo')
                            ->imageEditor()
                            ->helperText('Paylaşımlarda görünecek varsayılan görsel. Önerilen boyut: 1200x630px')
                            ->columnSpanFull(),
                    ]),

                Section::make('İzleme ve Analitik Kodları')
                    ->description('Google Analytics ve diğer izleme araçları için kodlar')
                    ->icon('heroicon-o-chart-bar')
                    ->collapsible()
                    ->schema([
                        TextInput::make('google_analytics_id')
                            ->label('Google Analytics ID')
                            ->placeholder('G-XXXXXXXXXX veya UA-XXXXXXXX-X')
                            ->helperText('Google Analytics Measurement ID\'nizi girin'),

                        Textarea::make('head_scripts')
                            ->label('Head Kodu')
                            ->helperText('</head> etiketinden önce eklenecek kodlar (Google Tag Manager, Meta Pixel vb.)')
                            ->rows(8)
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'font-mono text-sm']),

                        Textarea::make('body_scripts')
                            ->label('Body Kodu')
                            ->helperText('</body> etiketinden önce eklenecek kodlar')
                            ->rows(8)
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'font-mono text-sm']),
                    ]),
            ]);
    }
}
