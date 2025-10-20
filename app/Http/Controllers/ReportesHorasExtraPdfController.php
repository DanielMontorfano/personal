<?php

namespace App\Http\Controllers;

use App\Models\ReportesHorasExtra;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportesHorasExtraPdfController extends Controller
{
    public function show($id)
    {
        $reporte = ReportesHorasExtra::with([
            'sector',                      // nombre del sector
            'solicitante',                 // solicitante del reporte
            'usuario',                     // usuario creador
            'operario.puesto',             // operario principal con su puesto
            'registros.operario.puesto',   // cada registro con su operario y su puesto
        ])->findOrFail($id);

        // 👉 Cambiamos la orientación a horizontal
        $pdf = Pdf::loadView('pdf.reporte-horas-extra', [
            'reporte' => $reporte,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream("ReporteHorasExtra_{$reporte->id}.pdf");
    }
}
