<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sector extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'sigla'];

    // 🔹 Relación con Ingresos
    public function ingresos()
    {
        return $this->hasMany(\App\Models\Ingreso::class);
    }

    // 🔹 Relación con Solicitantes
    public function solicitantes()
    {
        return $this->hasMany(\App\Models\Solicitante::class);
    }

    // 🔹 Relación con Reportes de Horas Extras (la nueva)
    public function reportesHorasExtras()
    {
        return $this->hasMany(\App\Models\ReportesHorasExtra::class, 'sector_id');
    }
}
