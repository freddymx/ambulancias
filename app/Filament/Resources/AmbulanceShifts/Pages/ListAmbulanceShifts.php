<?php

namespace App\Filament\Resources\AmbulanceShifts\Pages;

use App\Filament\Resources\AmbulanceShifts\AmbulanceShiftResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAmbulanceShifts extends ListRecords
{
    protected static string $resource = AmbulanceShiftResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return __('app.shifts.label');
    }
}
