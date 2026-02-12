<?php

namespace App\Filament\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\View;

class Login extends BaseLogin
{
    public function getView(): string
    {
        if (View::exists('filament.auth.login')) {
            return 'filament.auth.login';
        }

        return parent::getView();
    }

    public function getMaxContentWidth(): Width|string|null
    {
        if (View::exists('filament.auth.login')) {
            return Width::FiveExtraLarge;
        }

        return parent::getMaxContentWidth();
    }
}
