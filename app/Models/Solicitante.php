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
}