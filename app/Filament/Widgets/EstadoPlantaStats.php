<?php

namespace App\Filament\Widgets;

use App\Models\Ingreso;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class EstadoPlantaStats extends BaseWidget
{
    protected function getCards(): array
    {
        // 1. Ingresos activos al día de hoy
        $presentes = Ingreso::whereDate('fecha_ingreso', '<=', today())
            ->where(function ($q) {
                $q->whereNull('fecha_baja')
                  ->orWhere('fecha_baja', '>=', today());
            })->count();

        // 2. Sectores activos (diferentes solicitantes con operarios dentro)
        $sectoresActivos = Ingreso::whereDate('fecha_ingreso', '<=', today())
            ->where(function ($q) {
                $q->whereNull('fecha_baja')
                  ->orWhere('fecha_baja', '>=', today());
            })
            ->with('solicitante')
            ->get()
            ->pluck('solicitante.sector')
            ->unique()
            ->count();

        // 3. Total de ingresos registrados
        $totalIngresos = Ingreso::count();

        return [
            Card::make('Operarios dentro de planta', $presentes)
                ->description('Al día de hoy')
                ->color('success'),

            Card::make('Secciones activassss', $sectoresActivos)
                ->description('Con al menos un operario')
                ->color('info'),

            Card::make('Ingresos totales registrados', $totalIngresos)
                ->color('gray'),
        ];
    }

    protected static ?int $sort = -1; // Lo muestra arriba si hay más widgets
}
