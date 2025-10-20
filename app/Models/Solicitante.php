<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitante extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_completo',
        'cargo',
        'sector_id',
    ];


    public function sector()
    {
        return $this->belongsTo(\App\Models\Sector::class);
    }

    public function planillasZafra()
{
    return $this->hasMany(PlanillaZafra::class);
}


public function memosEnviados()
{
    return $this->hasMany(\App\Models\Memo::class, 'de_solicitante_id');
}

public function memosRecibidos()
{
    return $this->belongsToMany(\App\Models\Memo::class, 'memo_solicitante', 'solicitante_id', 'memo_id')
                ->withTimestamps();
}
}