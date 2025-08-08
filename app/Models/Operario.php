<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operario extends Model
{
    use HasFactory;

    protected $fillable = [
        'legajo',
        'nombre_completo',
        'tipo_liquidacion',
        'fecha_ingreso',
        'direccion',
        'dni',
        'fecha_nacimiento',
        'cuil',
        'categoria',
        'sector',
        'tarea',
        'gerencia'
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'fecha_nacimiento' => 'date',
    ];

    // Relación con ingresos
    public function ingresos()
    {
        return $this->hasMany(Ingreso::class);
    }

    // Relación como solicitante
    public function ingresosSolicitados()
    {
        return $this->hasMany(Ingreso::class, 'solicitante_id');
    }

    // app/Models/Operario.php
public function asignacionesZafra()
{
    return $this->hasMany(AsignacionZafra::class);
}
}