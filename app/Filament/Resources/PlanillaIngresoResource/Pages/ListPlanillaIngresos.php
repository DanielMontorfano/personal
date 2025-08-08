<?php

namespace App\Filament\Resources\PlanillaIngresoResource\Pages;

use App\Filament\Resources\PlanillaIngresoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlanillaIngresos extends ListRecords
{
    protected static string $resource = PlanillaIngresoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
