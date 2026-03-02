<?php

namespace App\Filament\Resources\AmbulanceShifts;

use App\Filament\Resources\AmbulanceShifts\Pages\CreateAmbulanceShift;
use App\Filament\Resources\AmbulanceShifts\Pages\EditAmbulanceShift;
use App\Filament\Resources\AmbulanceShifts\Pages\ListAmbulanceShifts;
use App\Filament\Resources\AmbulanceShifts\Pages\ViewAmbulanceShift;
use App\Filament\Resources\AmbulanceShifts\Schemas\AmbulanceShiftForm;
use App\Filament\Resources\AmbulanceShifts\Schemas\AmbulanceShiftInfolist;
use App\Filament\Resources\AmbulanceShifts\Tables\AmbulanceShiftsTable;
use App\Models\AmbulanceShift;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class AmbulanceShiftResource extends Resource
{
    protected static ?string $model = AmbulanceShift::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $navigationLabel = 'Turnos';

    public static function canViewAny(): bool
    {
        return Auth::user()->role === 'admin' || Auth::user()->role === 'gestor';
    }

    public static function form(Schema $schema): Schema
    {
        return AmbulanceShiftForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AmbulanceShiftInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AmbulanceShiftsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAmbulanceShifts::route('/'),
            'create' => CreateAmbulanceShift::route('/create'),
            'view' => ViewAmbulanceShift::route('/{record}'),
            'edit' => EditAmbulanceShift::route('/{record}/edit'),
        ];
    }
}
