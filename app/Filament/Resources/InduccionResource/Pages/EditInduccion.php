<?php

namespace App\Filament\Resources\InduccionResource\Pages;

use App\Filament\Resources\InduccionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInduccion extends EditRecord
{
    protected static string $resource = InduccionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
