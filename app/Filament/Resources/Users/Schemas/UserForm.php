<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Select::make('role')
                    ->options(function () {
                        if (auth()->check() && auth()->user()->role === 'gestor') {
                            return [
                                'nurse' => 'Nurse',
                            ];
                        }
                        return [
                            'admin' => 'Administrator',
                            'gestor' => 'Gestor',
                            'nurse' => 'Nurse',
                        ];
                    })
                    ->live()
                    ->required()
                    ->default('nurse'),
                TextInput::make('password')
                    ->password()
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create'),
                Toggle::make('is_active')
                    ->label('Approved / Active')
                    ->required(),
                TextInput::make('monthly_shift_limit')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(31),
                TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                TextInput::make('identifier')
                    ->label('ID')
                    ->unique(ignoreRecord: true)
                    ->required(fn(callable $get) => $get('role') === 'nurse')
                    ->visible(fn(callable $get) => $get('role') === 'nurse' || !$get('role')),
            ]);
    }
}
