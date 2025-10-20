<?php

namespace App\Filament\Resources\RegistroHoraResource\Pages;

use App\Filament\Resources\RegistroHoraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRegistroHora extends EditRecord
{
    protected static string $resource = RegistroHoraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
