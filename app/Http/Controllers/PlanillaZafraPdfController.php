<?php

namespace App\Http\Controllers;

use App\Models\PlanillaZafra;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;

class PlanillaZafraPdfController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Cargamos las relaciones necesarias
        $planilla = PlanillaZafra::with([
            'zafra',
            'solicitante',
            'sector',
            'asignaciones.puesto',
            'asignaciones.operario'
        ])->findOrFail($id);
        //dd($planilla );
        // Generamos el PDF
        $pdf = Pdf::loadView('pdf.planilla-zafra', compact('planilla'))
                 ->setPaper('a4', 'landscape'); // Horizontal como en tu ejemplo

        return $pdf->stream("PlanillaZafra_{$planilla->id}.pdf");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PlanillaZafra $planillaZafra)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PlanillaZafra $planillaZafra)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlanillaZafra $planillaZafra)
    {
        //
    }
}
