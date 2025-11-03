<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('mail.mailer', env('MAIL_MAILER', 'smtp'));
        $this->migrator->add('mail.host', env('MAIL_HOST', 'smtp.gmail.com'));
        $this->migrator->add('mail.port', (int) env('MAIL_PORT', 587));
        $this->migrator->add('mail.encryption', env('MAIL_ENCRYPTION', 'tls'));
        $this->migrator->add('mail.username', env('MAIL_USERNAME', ''));
        $this->migrator->add('mail.password', env('MAIL_PASSWORD', ''));
        $this->migrator->add('mail.from_address', env('MAIL_FROM_ADDRESS', 'noreply@example.com'));
        $this->migrator->add('mail.from_name', env('MAIL_FROM_NAME', 'Laravel'));
    }
};
