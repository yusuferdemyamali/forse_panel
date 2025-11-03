<?php

namespace App\Filament\Pages;

use App\Settings\MailSettings;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SettingsPage;
use Illuminate\Support\Facades\Mail;

class ManageMailSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static string $settings = MailSettings::class;

    protected static ?string $navigationGroup = 'Site Yönetimi';

    protected static ?string $navigationLabel = 'E-posta Ayarları';

    protected static ?string $title = 'E-posta (SMTP) Ayarları';

    protected static ?int $navigationSort = 8;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('E-posta Sunucu Ayarları')
                    ->description('SMTP sunucu bilgilerinizi girin')
                    ->icon('heroicon-o-server')
                    ->schema([
                        Select::make('mailer')
                            ->label('Mail Sürücüsü')
                            ->options([
                                'smtp' => 'SMTP',
                                'sendmail' => 'Sendmail',
                                'mailgun' => 'Mailgun',
                                'ses' => 'Amazon SES',
                                'postmark' => 'Postmark',
                                'log' => 'Log (Test)',
                            ])
                            ->default('smtp')
                            ->required()
                            ->helperText('E-posta göndermek için kullanılacak sürücü'),

                        TextInput::make('host')
                            ->label('SMTP Sunucusu')
                            ->required()
                            ->placeholder('smtp.gmail.com')
                            ->helperText('SMTP sunucu adresi (örn: smtp.gmail.com)')
                            ->columnSpan(1),

                        TextInput::make('port')
                            ->label('Port')
                            ->required()
                            ->numeric()
                            ->default(587)
                            ->helperText('SMTP port numarası (genellikle 587 veya 465)')
                            ->columnSpan(1),

                        Select::make('encryption')
                            ->label('Şifreleme')
                            ->options([
                                'tls' => 'TLS',
                                'ssl' => 'SSL',
                                '' => 'Yok',
                            ])
                            ->default('tls')
                            ->required()
                            ->helperText('Güvenlik protokolü (TLS önerilir)'),
                    ])
                    ->columns(2),

                Section::make('Kimlik Doğrulama')
                    ->description('SMTP kullanıcı bilgileriniz')
                    ->icon('heroicon-o-key')
                    ->schema([
                        TextInput::make('username')
                            ->label('Kullanıcı Adı / E-posta')
                            ->required()
                            ->email()
                            ->placeholder('ornek@gmail.com')
                            ->helperText('SMTP kullanıcı adı veya e-posta adresi'),

                        TextInput::make('password')
                            ->label('Şifre / Uygulama Şifresi')
                            ->required()
                            ->password()
                            ->revealable()
                            ->helperText('SMTP şifreniz veya uygulama şifreniz'),
                    ]),

                Section::make('Gönderici Bilgileri')
                    ->description('E-postalarda görünecek gönderici bilgileri')
                    ->icon('heroicon-o-user')
                    ->schema([
                        TextInput::make('from_address')
                            ->label('Gönderici E-posta Adresi')
                            ->required()
                            ->email()
                            ->placeholder('noreply@siteniz.com')
                            ->helperText('E-postaların gönderileceği adres'),

                        TextInput::make('from_name')
                            ->label('Gönderici Adı')
                            ->required()
                            ->placeholder('Site Adınız')
                            ->helperText('E-postalarda görünecek gönderici adı'),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('test')
                ->label('Test Maili Gönder')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Test Maili Gönder')
                ->modalDescription('Mevcut ayarlarla bir test e-postası gönderilecek. Devam etmek istiyor musunuz?')
                ->modalSubmitActionLabel('Gönder')
                ->action(function () {
                    try {
                        $settings = app(MailSettings::class);
                        
                        // Test için geçici olarak ayarları uygula
                        config([
                            'mail.default' => $settings->mailer,
                            'mail.mailers.smtp' => [
                                'transport' => 'smtp',
                                'host' => $settings->host,
                                'port' => $settings->port,
                                'encryption' => $settings->encryption,
                                'username' => $settings->username,
                                'password' => $settings->password,
                            ],
                            'mail.from' => [
                                'address' => $settings->from_address,
                                'name' => $settings->from_name,
                            ],
                        ]);

                        Mail::raw(
                            'Bu bir test e-postasıdır. SMTP ayarlarınız başarıyla çalışıyor! 🎉',
                            function ($message) use ($settings) {
                                $message->to($settings->from_address)
                                    ->subject('Test E-postası - ' . config('app.name'));
                            }
                        );

                        Notification::make()
                            ->title('Test maili gönderildi!')
                            ->success()
                            ->body('E-posta başarıyla gönderildi. Lütfen gelen kutunuzu kontrol edin.')
                            ->send();

                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Test maili gönderilemedi!')
                            ->danger()
                            ->body('Hata: ' . $e->getMessage())
                            ->send();
                    }
                }),
        ];
    }
}
