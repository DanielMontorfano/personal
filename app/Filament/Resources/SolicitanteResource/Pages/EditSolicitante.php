<?php

namespace App\Filament\Resources\SolicitanteResource\Pages;

use App\Filament\Resources\SolicitanteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSolicitante extends EditRecord
{
    protected static string $resource = SolicitanteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
