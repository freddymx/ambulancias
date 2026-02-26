<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EditProfile extends BaseEditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información Personal')
                    ->description('Actualice su información personal y de contacto.')
                    ->aside()
                    ->components([
                        TextInput::make('name')
                            ->label(__('filament-panels::auth/pages/edit-profile.form.name.label'))
                            ->required()
                            ->maxLength(255)
                            ->autofocus(),
                        TextInput::make('email')
                            ->label(__('filament-panels::auth/pages/edit-profile.form.email.label'))
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        TextInput::make('phone')
                            ->label('Teléfono')
                            ->tel()
                            ->maxLength(255),
                        TextInput::make('dni')
                            ->label('DNI')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                    ]),

                Section::make('Seguridad')
                    ->description('Actualice su contraseña si es necesario.')
                    ->aside()
                    ->components([
                        TextInput::make('password')
                            ->label(__('filament-panels::auth/pages/edit-profile.form.password.label'))
                            ->password()
                            ->revealable(filament()->arePasswordsRevealable())
                            ->rule(Password::default())
                            ->autocomplete('new-password')
                            ->dehydrated(fn ($state): bool => filled($state))
                            ->dehydrateStateUsing(fn ($state): string => Hash::make($state))
                            ->live(debounce: 500)
                            ->same('passwordConfirmation'),
                        TextInput::make('passwordConfirmation')
                            ->label(__('filament-panels::auth/pages/edit-profile.form.password_confirmation.label'))
                            ->password()
                            ->revealable(filament()->arePasswordsRevealable())
                            ->required(fn (callable $get) => filled($get('password')))
                            ->visible(fn (callable $get) => filled($get('password')))
                            ->dehydrated(false),
                    ]),

                Section::make('Información del Sistema')
                    ->description('Información administrativa sobre su cuenta (solo lectura).')
                    ->aside()
                    ->components([
                        TextInput::make('role')
                            ->label('Rol')
                            ->disabled()
                            ->dehydrated(false)
                            ->formatStateUsing(fn ($state) => match ($state) {
                                'admin' => 'Administrador',
                                'gestor' => 'Gestor',
                                'nurse' => 'Enfermero/a',
                                default => $state,
                            }),
                        TextInput::make('monthly_shift_limit')
                            ->label('Límite Mensual de Guardias')
                            ->disabled()
                            ->dehydrated(false),
                        Toggle::make('is_active')
                            ->label('Cuenta Activa')
                            ->disabled()
                            ->dehydrated(false)
                            ->onColor('success')
                            ->offColor('danger'),
                    ]),
            ]);
    }
}
