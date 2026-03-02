<?php

namespace App\Filament\Resources\NurseShifts\Pages;

use App\Enums\ShiftStatus;
use App\Filament\Resources\NurseShifts\NurseShiftResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListNurseShifts extends ListRecords
{
    protected static string $resource = NurseShiftResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Todos'),
            'accepted' => Tab::make('Aceptados')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', ShiftStatus::Accepted)),
            'pending' => Tab::make('Pendientes')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', ShiftStatus::Pending)),
            'rejected' => Tab::make('Rechazados')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', ShiftStatus::Rejected)),
            'reserve' => Tab::make('En Reserva')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', ShiftStatus::EnReserva)),
        ];
    }
}
