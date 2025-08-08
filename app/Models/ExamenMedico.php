<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExamenMedico extends Model
{
    use HasFactory;

    protected $fillable = [
        'ingreso_id',
        'fecha_examen',
        'resultado',
        'observaciones',
    ];

    public function ingreso()
    {
        return $this->belongsTo(Ingreso::class);
    }
}
