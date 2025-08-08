<?php

namespace App\Filament\Resources\PlanillaZafraResource\Pages;

use App\Filament\Resources\PlanillaZafraResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPlanillaZafra extends ViewRecord
{
    protected static string $resource = PlanillaZafraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}