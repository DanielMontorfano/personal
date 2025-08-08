<?php

namespace App\Filament\Resources\PlanillaIngresoResource\Pages;

use App\Filament\Resources\PlanillaIngresoResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreatePlanillaIngreso extends CreateRecord
{
    protected static string $resource = PlanillaIngresoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreateAnotherFormAction(): Action
    {
        return Action::make('createAnother')
            ->hidden()
            ->action(fn () => null);
    }
}