<?php

namespace App\Filament\Resources\ReportesHorasExtraResource\Pages;

use App\Filament\Resources\ReportesHorasExtraResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReportesHorasExtra extends CreateRecord
{
    protected static string $resource = ReportesHorasExtraResource::class;

    protected function getRedirectUrl(): string
    {
        // 👇 Redirige al edit del registro recién creado
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }
}
