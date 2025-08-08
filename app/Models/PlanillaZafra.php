<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanillaZafra extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'numero',
        'fecha',
        'observaciones',
        'zafra_id',
        'sector_id',
        'solicitante_id',
        
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function zafra(): BelongsTo
    {
        return $this->belongsTo(Zafra::class);
    }

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    public function solicitante(): BelongsTo
    {
        return $this->belongsTo(Solicitante::class);
    }

    public function asignaciones(): HasMany
    {
        return $this->hasMany(AsignacionZafra::class);
    }
}