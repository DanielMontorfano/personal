<?php

namespace App\Filament\Resources\AutorizacionResource\Pages;

use App\Filament\Resources\AutorizacionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAutorizacions extends ListRecords
{
    protected static string $resource = AutorizacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
