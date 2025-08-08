<?php

namespace App\Filament\Resources\InduccionResource\Pages;

use App\Filament\Resources\InduccionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInduccions extends ListRecords
{
    protected static string $resource = InduccionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
