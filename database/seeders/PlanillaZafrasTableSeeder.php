<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlanillaZafra;
use App\Models\Zafra;
use App\Models\Solicitante;
use Carbon\Carbon;

class PlanillaZafrasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener algunas zafras y solicitantes existentes
        $zafras = Zafra::pluck('id')->toArray();
        $solicitantes = Solicitante::pluck('id')->toArray();

        if (empty($zafras) || empty($solicitantes)) {
            $this->command->error('No hay zafras o solicitantes en la base de datos. Crea algunos primero.');
            return;
        }

        $planillas = [
            [
                'zafra_id' => $zafras[0],
                'solicitante_id' => $solicitantes[0],
                'fecha' => Carbon::now()->subDays(10),
                'numero' => 'PZ-2023-001',
                'observaciones' => 'Primera planilla de la zafra',
            ],
            [
                'zafra_id' => $zafras[0],
                'solicitante_id' => $solicitantes[1],
                'fecha' => Carbon::now()->subDays(5),
                'numero' => 'PZ-2023-002',
                'observaciones' => 'Planilla con observaciones especiales',
            ],
            [
                'zafra_id' => $zafras[1],
                'solicitante_id' => $solicitantes[2],
                'fecha' => Carbon::now(),
                'numero' => 'PZ-2023-003',
                'observaciones' => null,
            ],
        ];

        foreach ($planillas as $planilla) {
            PlanillaZafra::create($planilla);
        }

        $this->command->info('Seeder de planilla_zafras ejecutado correctamente!');
    }
}