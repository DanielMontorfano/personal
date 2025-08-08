<?php

namespace App\Filament\Resources\AsignacionZafraResource\Pages;

use App\Filament\Resources\AsignacionZafraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAsignacionZafra extends EditRecord
{
    protected static string $resource = AsignacionZafraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
