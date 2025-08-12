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
    $planilla = PlanillaZafra::with([
        'zafra',
        'solicitante',
        'sector',
        'asignaciones.puesto',
        'asignaciones.operario'
    ])->findOrFail($id);

    // Filtramos asignaciones por día
    $porDia = $planilla->asignaciones->where('turno', 'Por día');

    // Asignaciones sin los "Por día"
    $asignacionesPrincipales = $planilla->asignaciones->where('turno', '!=', 'Por día');

    // Reemplazamos la colección original por las filtradas para la tabla principal
    $planilla->setRelation('asignaciones', $asignacionesPrincipales);

    $pdf = Pdf::loadView('pdf.planilla-zafra', [
        'planilla' => $planilla,
        'porDia' => $porDia, // le pasamos la colección aparte
    ])->setPaper('a4', 'landscape');

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
