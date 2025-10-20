<?php

namespace App\Filament\Resources\ReportesHorasExtraResource\Pages;

use App\Filament\Resources\ReportesHorasExtraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReportesHorasExtra extends EditRecord
{
    protected static string $resource = ReportesHorasExtraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
