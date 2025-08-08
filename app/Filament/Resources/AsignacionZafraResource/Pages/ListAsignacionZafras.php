<?php

namespace App\Filament\Resources\AsignacionZafraResource\Pages;

use App\Filament\Resources\AsignacionZafraResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAsignacionZafras extends ListRecords
{
    protected static string $resource = AsignacionZafraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(), // Opcional si quieres habilitar creación
        ];
    }
}