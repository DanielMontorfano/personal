<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AsignacionZafra extends Model
{
    protected $table = 'asignacion_zafras';

    protected $fillable = [
        'planilla_zafra_id',
        'operario_id',
        'puesto_id',
        'turno',
        'categoria_puesto',
        'categoria_operario',
        'categoria_mayor',
        'condicion',
        'ingresado',
        'fecha_ingreso'
    ];

    protected $casts = [
        'ingresado' => 'boolean',
        'fecha_ingreso' => 'datetime'
    ];

    public function planilla(): BelongsTo
    {
        return $this->belongsTo(PlanillaZafra::class, 'planilla_zafra_id');
    }

    public function operario(): BelongsTo
    {
        return $this->belongsTo(Operario::class, 'operario_id');
    }

    public function puesto(): BelongsTo
    {
        return $this->belongsTo(Puesto::class, 'puesto_id');
    }
}