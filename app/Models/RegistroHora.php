<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroHora extends Model
{
    use HasFactory;

    protected $table = 'registro_horas';

    protected $fillable = [
        'reporte_id',
        'puesto_ocupado',
        'fecha_trabajada',
        'hora_inicio',
        'hora_fin',
        'total_horas',
        'tipo_hora_extra',
        'actividad_espec',
        'valor_recargo',
        'total_pago',
    ];

    protected $casts = [
    'fecha_trabajada' => 'date',
];


    /**
     * Relación con el reporte padre
     */
    public function reporte()
    {
        return $this->belongsTo(ReportesHorasExtra::class, 'reporte_id');
    }

    /**
     * Relación con el operario (tomado a través del reporte padre)
     */
    public function operario()
    {
        return $this->hasOneThrough(
            Operario::class,            // Modelo destino
            ReportesHorasExtra::class,  // Modelo intermedio
            'id',                       // Clave primaria en reportes_horas_extras
            'id',                       // Clave primaria en operarios
            'reporte_id',               // FK local en registro_horas
            'operario_id'               // FK en reportes_horas_extras
        );
    }

public function puestoOcupado()
{
    return $this->belongsTo(\App\Models\Puesto::class, 'puesto_ocupado');
}

}
