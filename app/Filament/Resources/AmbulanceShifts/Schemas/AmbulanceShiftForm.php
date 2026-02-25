<?php

namespace App\Filament\Resources\AmbulanceShifts\Schemas;

use App\Enums\ShiftStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class AmbulanceShiftForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->label(__('app.shifts.nurse')),
                DatePicker::make('date')
                    ->required()
                    ->label(__('app.shifts.date')),
                Select::make('status')
                    ->options(ShiftStatus::class)
                    ->required()
                    ->label(__('app.shifts.status'))
                    ->default(ShiftStatus::Pending),
            ]);
    }
}
