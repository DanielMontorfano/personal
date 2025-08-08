<?php

namespace App\Filament\Resources\PlanillaIngresoResource\Pages;

use App\Filament\Resources\PlanillaIngresoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;


class EditPlanillaIngreso extends EditRecord
{
    protected static string $resource = PlanillaIngresoResource::class;
  

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Ver PDF')
                ->icon('heroicon-o-printer')
                ->url(fn () => route('planilla.pdf', ['id' => $this->record->id]))
                ->openUrlInNewTab(),
        ];
    }
}
