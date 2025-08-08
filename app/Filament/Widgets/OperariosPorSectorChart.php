<?php

namespace App\Filament\Widgets;

use App\Models\Ingreso;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Str;

class OperariosPorSectorChart extends ChartWidget
{
    protected static ?string $heading = 'Operarios por Sector (hoy)';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = Ingreso::whereDate('fecha_ingreso', '<=', today())
            ->where(function ($query) {
                $query->whereNull('fecha_baja')
                    ->orWhereDate('fecha_baja', '>=', today());
            })
            ->with('solicitante')
            ->get()
            ->groupBy(fn ($ingreso) => $ingreso->solicitante?->sector ?? 'Sin sector')
            ->map(fn ($ingresos) => $ingresos->count());

        // 🎨 Colores fijos por sector (normalizados)
        $colorPorSector = [
            'calderas' => '#8B4513',                // Marrón
            'trapiche' => '#10b981',                // Verde
            'fabricación' => '#facc15',             // Amarillo
            'mantenimiento mecánico' => '#f97316',  // Naranja
            'mantenimiento eléctrico' => '#f97316', // Naranja
            'depósitos' => '#3b82f6',               // Azul
            'servicios generales' => '#3b82f6',     // Azul
            'sin sector' => '#9ca3af',              // Gris claro
        ];


        $labels = $data->keys();

        // Normalizar las claves del array para que coincidan siempre
        $colorPorSectorNormalized = collect($colorPorSector)->mapWithKeys(function ($color, $key) {
            return [Str::lower(trim($key)) => $color];
        });

        $backgroundColors = $labels->map(function ($sector) use ($colorPorSectorNormalized) {
            $clave = Str::lower(trim($sector));
            return $colorPorSectorNormalized[$clave] ?? '#d1d5db'; // gris si no se encuentra
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Cantidad de operarios',
                    'data' => $data->values(),
                    'backgroundColor' => $backgroundColors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'ticks' => [
                        'stepSize' => 1,
                        'precision' => 0,
                        'beginAtZero' => true,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false, // 👈 leyenda desactivada
                ],
            ],
        ];
    }
}
