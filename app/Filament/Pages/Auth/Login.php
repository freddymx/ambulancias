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
                ->title('Cuenta pendiente de revisión')
                ->body('Tu cuenta está a la espera de aprobación por un supervisor. Te notificaremos cuando sea activada.')
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
