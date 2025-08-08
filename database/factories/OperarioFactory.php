<?php

namespace Database\Factories;

use App\Models\Operario;
use Illuminate\Database\Eloquent\Factories\Factory;

class OperarioFactory extends Factory
{
    protected $model = Operario::class;

    public function definition()
    {
        return [
            'legajo' => $this->faker->unique()->numberBetween(1000, 9999),
            'nombre_completo' => $this->faker->name(),
            'tipo_liquidacion' => $this->faker->randomElement(['mensual', 'jornalizado']),
            'fecha_ingreso' => $this->faker->date(),
        ];
    }
}