<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\EstadoPlantaStats;
use App\Filament\Widgets\OperariosPresentesTable;

class EstadoPlanta extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static string $view = 'filament.pages.estado-planta';
    protected static ?string $title = 'Estado Actual de la Planta';

    protected function getHeaderWidgets(): array
    {
        return [
            EstadoPlantaStats::class,
            //\App\Filament\Widgets\OperariosPresentesTable::class,
            \App\Filament\Widgets\OperariosPorSectorChart::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            OperariosPresentesTable::class,
        ];
    }
}
