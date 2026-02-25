<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Notifications\Notification;

class Login extends BaseLogin
{
    public function mount(): void
    {
        parent::mount();

        if ($this->getPendingApproval()) {
            Notification::make()
                ->title(__('app.auth.login.pending_approval_title'))
                ->body(__('app.auth.login.pending_approval_body'))
                ->warning()
                ->persistent()
                ->send();
        }
    }

    protected function getPendingApproval(): bool
    {
        return request()->query('pending') === 'approval';
    }
}
