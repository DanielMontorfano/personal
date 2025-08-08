<?php

namespace App\Filament\Resources\AutorizacionResource\Pages;

use App\Filament\Resources\AutorizacionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAutorizacion extends EditRecord
{
    protected static string $resource = AutorizacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
