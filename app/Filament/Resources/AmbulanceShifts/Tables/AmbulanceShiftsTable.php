<?php

namespace App\Filament\Resources\AmbulanceShifts\Tables;

use App\Enums\ShiftStatus;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AmbulanceShiftsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('app.shifts.nurse'))
                    ->sortable(),
                TextColumn::make('date')
                    ->label(__('app.shifts.date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('app.shifts.status'))
                    ->badge(),
                IconColumn::make('is_reserve')
                    ->label(__('app.shifts.reserve'))
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label(__('app.shifts.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('app.shifts.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('approve')
                    ->label(__('app.shifts.approve'))
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === ShiftStatus::Pending)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['status' => ShiftStatus::Accepted]);
                    }),
                Action::make('reject')
                    ->label(__('app.shifts.reject'))
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === ShiftStatus::Pending)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['status' => ShiftStatus::Rejected]);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
