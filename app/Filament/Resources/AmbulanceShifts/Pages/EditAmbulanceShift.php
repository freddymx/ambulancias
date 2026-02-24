<?php

namespace App\Filament\Resources\AmbulanceShifts\Pages;

use App\Filament\Resources\AmbulanceShifts\AmbulanceShiftResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAmbulanceShift extends EditRecord
{
    protected static string $resource = AmbulanceShiftResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
