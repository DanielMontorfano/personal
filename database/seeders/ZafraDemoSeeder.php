<?php

namespace Database\Seeders;

use App\Models\Operario;
use App\Models\Puesto;
use App\Models\Solicitante;
use App\Models\Zafra;
use App\Models\PlanillaZafra;
use App\Models\AsignacionZafra;
use Illuminate\Database\Seeder;

class ZafraDemoSeeder extends Seeder
{
public function run()
{
    // 1. Solicitante
    $solicitante = Solicitante::firstOrCreate([
        'nombre_completo' => 'Jefe de Zafra',
        'cargo' => 'Supervisor',
        'sector' => 'Producción'
    ]);

    // 2. Operarios (sin factory)
    $operarios = [
        ['legajo' => 1001, 'nombre_completo' => 'Juan Pérez'],
        ['legajo' => 1002, 'nombre_completo' => 'María Gómez'],
        ['legajo' => 1003, 'nombre_completo' => 'Carlos López']
    ];
    foreach ($operarios as $operario) {
        Operario::firstOrCreate($operario);
    }

    // 3. Puestos
    $puestos = [
        ['nombre' => 'Ayudante', 'categoria' => 1],
        ['nombre' => 'Operador', 'categoria' => 2],
        ['nombre' => 'Supervisor', 'categoria' => 3]
    ];
    foreach ($puestos as $puesto) {
        Puesto::firstOrCreate($puesto);
    }

    // Resto del seeder (igual que antes)...
}
}