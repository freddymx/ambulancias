<?php

namespace App\Filament\Resources\Users\Tables;

use App\Notifications\UserActivatedNotification;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label(__('app.users.email'))
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('dni')
                    ->label(__('app.users.dni'))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('role')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'admin' => 'danger',
                        'gestor' => 'warning',
                        'nurse' => 'info',
                        default => 'gray',
                    })
                    ->searchable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label(__('app.users.active'))
                    ->color(fn(bool $state): string => $state ? 'success' : 'danger'),
                TextColumn::make('monthly_shift_limit')
                    ->numeric()
                    ->sortable()
                    ->label(__('app.users.monthly_limit')),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('activate')
                    ->label(__('app.users.activate'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn($record) => ! $record->is_active)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['is_active' => true]);
                        $record->notify(new UserActivatedNotification(auth()->user()?->name ?? 'Admin'));
                    }),
                Action::make('deactivate')
                    ->label(__('app.users.deactivate'))
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn($record) => $record->is_active && $record->role !== 'admin')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['is_active' => false]);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
