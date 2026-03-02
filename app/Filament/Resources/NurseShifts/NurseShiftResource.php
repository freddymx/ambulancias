<?php

namespace App\Filament\Resources\NurseShifts;

use App\Filament\Resources\NurseShifts\Pages\ListNurseShifts;
use App\Models\AmbulanceShift;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use BackedEnum;

class NurseShiftResource extends Resource
{
    protected static ?string $model = AmbulanceShift::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationLabel = 'Mis Turnos';

    protected static ?string $slug = 'mis-turnos';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::id());
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->role === 'nurse';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label(__('app.shifts.date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('app.shifts.status'))
                    ->badge(),
                Tables\Columns\IconColumn::make('is_reserve')
                    ->label(__('app.shifts.reserve'))
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNurseShifts::route('/'),
        ];
    }
}
