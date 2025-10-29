<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;

class Login extends BaseLogin
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required(),
                TextInput::make('name')
                    ->label('Kullanıcı Adı')
                    ->required(),
                TextInput::make('password')
                    ->label('Şifre')
                    ->password()
                    ->required(),
                $this->getRememberFormComponent(),
            ])
            ->statePath('data');
    }

    public function authenticate(): ?LoginResponse
    {
        $data = $this->form->getState();

        // Attempt login with name, email, and password
        if (Auth::attempt(['name' => $data['name'], 'email' => $data['email'], 'password' => $data['password']])) {
            return app(LoginResponse::class);
        }

        throw ValidationException::withMessages([
            'data.email' => __('filament-panels::pages/auth/login.messages.failed'),
        ]);
    }
}