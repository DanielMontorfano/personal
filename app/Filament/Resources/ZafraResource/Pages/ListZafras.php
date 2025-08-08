<?php

namespace App\Filament\Resources\ZafraResource\Pages;

use App\Filament\Resources\ZafraResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListZafras extends ListRecords
{
    protected static string $resource = ZafraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
