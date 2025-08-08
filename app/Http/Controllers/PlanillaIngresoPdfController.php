<?php

namespace App\Http\Controllers;

use App\Models\PlanillaIngreso;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PlanillaIngresoPdfController extends Controller
{
    public function show($id)
    {
        $planilla = PlanillaIngreso::with(['solicitante', 'ingresos.operario', 'ingresos.sector', 'ingresos.examenMedico', 'ingresos.induccion'])
            ->findOrFail($id);

            $pdf = Pdf::loadView('pdf.planilla-ingreso', compact('planilla'))->setPaper('a4', 'landscape');



        return $pdf->stream("PlanillaIngreso_{$planilla->id}.pdf");
        // Si querés descargar directamente:
        // return $pdf->download("PlanillaIngreso_{$planilla->id}.pdf");
    }
}
