<?php

namespace App\Services;

use App\Models\Ingreso;

class IngresoService
{
    public static function validarSolapamientoOperario(
        int $operarioId,
        string $fechaIngreso,
        ?string $fechaBaja,
        ?Ingreso $ingresoActual = null
    ): bool {
        $query = Ingreso::where('operario_id', $operarioId)
            ->where(function ($q) use ($fechaIngreso, $fechaBaja) {
                $q->where(function ($q2) use ($fechaIngreso, $fechaBaja) {
                    $q2->whereDate('fecha_ingreso', '<=', $fechaBaja ?? now())
                       ->where(function ($q3) use ($fechaIngreso) {
                           $q3->whereNull('fecha_baja')
                              ->orWhereDate('fecha_baja', '>=', $fechaIngreso);
                       });
                });
            });

        if ($ingresoActual) {
            $query->where('id', '!=', $ingresoActual->id);
        }

        return !$query->exists();
    }
}