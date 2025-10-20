<?php

namespace App\Observers;

use App\Models\ReportesHorasExtra;
use Illuminate\Support\Facades\Auth;

class ReportesHorasExtraObserver
{
    /**
     * Cuando se crea un nuevo registro de reporte de horas extras,
     * asigna automáticamente el usuario autenticado.
     */
    public function creating(ReportesHorasExtra $reporte)
    {
        if (Auth::check()) {
            $reporte->usuario_id = Auth::id();
        }
    }

    /**
     * Si querés que también se registre el usuario que actualiza el reporte,
     * podés descomentar este método:
     */
    
    public function updating(ReportesHorasExtra $reporte)
    {
        if (Auth::check()) {
            $reporte->usuario_id = Auth::id();
        }
    }
    
}
