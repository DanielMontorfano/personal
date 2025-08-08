<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlanillaIngreso extends Model
{
    use HasFactory;
    protected $table = 'planilla_ingreso';

    protected $fillable = [
        'fecha',
        'numero',
        'observaciones',
        'solicitante_id',
    ];

    public function solicitante()
    {
        return $this->belongsTo(Solicitante::class);
    }

    public function ingresos()
    {
        return $this->hasMany(Ingreso::class);
    }

    protected static function booted()
    {
        static::created(function ($planilla) {
            // Solo asignar si todavía está vacío
            if (empty($planilla->numero)) {
                $planilla->numero = str_pad($planilla->id, 5, '0', STR_PAD_LEFT); // Ej: 00001
                $planilla->save();
            }
        });
    }




}
