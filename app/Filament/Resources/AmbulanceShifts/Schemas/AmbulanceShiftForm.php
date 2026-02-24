<?php

namespace App\Filament\Resources\AmbulanceShifts\Schemas;

use App\Enums\ShiftStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AmbulanceShiftForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                DatePicker::make('date')
                    ->required(),
                Toggle::make('is_reserve')
                    ->required(),
                Select::make('status')
                    ->options(ShiftStatus::class)
                    ->required()
                    ->default(ShiftStatus::Pending),
            ]);
    }
}
