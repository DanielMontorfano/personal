<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Puesto extends Model
{
    protected $fillable = [
        'nombre',
        'categoria',
    ];

    public function ingresos()
    {
        return $this->hasMany(Ingreso::class);
    }

        // app/Models/Puesto.php
public function asignacionesZafra()
{
    return $this->hasMany(AsignacionZafra::class);
}
}
