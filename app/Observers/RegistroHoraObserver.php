<?php

namespace App\Observers;

use App\Models\RegistroHora;

class RegistroHoraObserver
{
    public function created(RegistroHora $registro)
    {
        $this->recalcularTotales($registro);
    }

    public function updated(RegistroHora $registro)
    {
        $this->recalcularTotales($registro);
    }

    public function deleted(RegistroHora $registro)
    {
        $this->recalcularTotales($registro);
    }

    private function recalcularTotales(RegistroHora $registro)
    {
        $reporte = $registro->reporte; // relación en el modelo RegistroHora

        if ($reporte) {
            $totalHoras = $reporte->registros()->sum('total_horas');
            $totalPago  = $reporte->registros()->sum('total_pago');

            $reporte->update([
                'total_horas_general' => $totalHoras,
                'total_pago_general'  => $totalPago,
            ]);
        }
    }
}
