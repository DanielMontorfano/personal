<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Solicitante;

class SolicitantesTableSeeder extends Seeder
{
    public function run()
    {
        $solicitantes = [
            [
                'nombre_completo' => 'Bruno Espíndola',
                'cargo'          => 'Jefe',
                'sector'         => 'Mantenimiento mecánico',
            ],
            [
                'nombre_completo' => 'Mariano Borja',
                'cargo'          => 'Jefe',
                'sector'         => 'Mantenimiento eléctrico',
            ],
            [
                'nombre_completo' => 'Manuel Pérez',
                'cargo'          => 'Jefe',
                'sector'         => 'Laboratorio',
            ],
            [
                'nombre_completo' => 'Ana Torres',
                'cargo'          => null,  // Campo nullable
                'sector'         => 'Ventas',
            ],
            [
                'nombre_completo' => 'Pedro Sánchez',
                'cargo'          => 'Coordinador',
                'sector'         => 'TI',
            ],
        ];

        foreach ($solicitantes as $solicitante) {
            Solicitante::create($solicitante);
        }
    }
}