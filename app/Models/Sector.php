<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sector extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'sigla'];

    public function ingresos()
    {
        return $this->hasMany(\App\Models\Ingreso::class);
        
    }

    
    public function solicitantes()
    {
        return $this->hasMany(\App\Models\Solicitante::class);
    }
    
}
