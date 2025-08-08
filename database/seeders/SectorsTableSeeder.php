<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sector; // Asegúrate de tener el modelo Sector

class SectorsTableSeeder extends Seeder
{
    public function run()
    {
        $sectors = [
            [
                'nombre' => 'Administración',
                'sigla'  => 'ADM',
            ],
            [
                'nombre' => 'Producción',
                'sigla'  => 'PROD',
            ],
            [
                'nombre' => 'Logística',
                'sigla'  => 'LOG',
            ],
            [
                'nombre' => 'Ventas',
                'sigla'  => 'VENT',
            ],
            [
                'nombre' => 'Tecnología',
                'sigla'  => 'TI',
            ],
        ];

        foreach ($sectors as $sector) {
            Sector::create($sector);
        }
    }
}