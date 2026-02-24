<?php

namespace App\Filament\Resources\AmbulanceShifts\Pages;

use App\Filament\Resources\AmbulanceShifts\AmbulanceShiftResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAmbulanceShift extends ViewRecord
{
    protected static string $resource = AmbulanceShiftResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
