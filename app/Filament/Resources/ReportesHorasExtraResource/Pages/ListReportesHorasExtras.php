<?php

namespace App\Filament\Resources\ReportesHorasExtraResource\Pages;

use App\Filament\Resources\ReportesHorasExtraResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReportesHorasExtras extends ListRecords
{
    protected static string $resource = ReportesHorasExtraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
