<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use App\Notifications\NewUserRegisteredNotification;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class Register extends BaseRegister
{
    protected ?Model $user = null;

    public function getUser(): ?Model
    {
        return $this->user;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                TextInput::make('dni')
                    ->label(__('app.users.dni'))
                    ->unique('users', 'dni')
                    ->required(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function handleRegistration(array $data): Model
    {
        $data['role'] = 'nurse';
        $data['is_active'] = false;

        $this->user = User::create($data);

        return $this->user;
    }

    protected function afterRegister(): void
    {
        $user = $this->getUser();

        Notification::make()
            ->title(__('app.auth.register.completed_title'))
            ->body(__('app.auth.register.completed_body'))
            ->success()
            ->send();

        $superusers = User::whereIn('role', ['admin', 'gestor'])
            ->where('is_active', true)
            ->get();

        foreach ($superusers as $superuser) {
            $superuser->notify(new NewUserRegisteredNotification($user));
        }
    }
}
