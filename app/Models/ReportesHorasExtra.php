<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportesHorasExtra extends Model
{
    use HasFactory;

    protected $table = 'reportes_horas_extras';

    protected $fillable = [
        'numero_reporte',
        'periodo_reporte',
        'sector_id', // 👈 AQUI
        'trabajos_efectuados',
        'solicitante_id',
        'usuario_id',
        'operario_id',   // 👈 nuevo campo
        'estado',
        'fecha_reporte',
        'total_horas_general',
        'total_pago_general',
    ];

    public function solicitante()
    {
        return $this->belongsTo(Solicitante::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function operario()
    {
        return $this->belongsTo(Operario::class); // 👈 agregado
    }

public function sector()
{
    return $this->belongsTo(Sector::class, 'sector_id');
}




    public function registros()
    {
        return $this->hasMany(RegistroHora::class, 'reporte_id');
    }

    protected static function booted()
    {
        static::creating(function ($reporte) {
            $lastId = self::max('id') + 1;
            $reporte->numero_reporte = 'RPT-' . str_pad($lastId, 5, '0', STR_PAD_LEFT);
        });
    }

    
}
