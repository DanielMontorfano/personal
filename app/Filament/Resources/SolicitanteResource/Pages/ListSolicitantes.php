<?php

namespace App\Filament\Resources\SolicitanteResource\Pages;

use App\Filament\Resources\SolicitanteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSolicitantes extends ListRecords
{
    protected static string $resource = SolicitanteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
