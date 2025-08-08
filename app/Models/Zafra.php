<?php

// app/Models/Zafra.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zafra extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'observaciones'
    ];

    public function planillas()
    {
        return $this->hasMany(PlanillaZafra::class);
    }
}