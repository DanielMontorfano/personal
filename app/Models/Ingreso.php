<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ingreso extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'operario_id',
        'solicitante_id',
        'sector_id',
        'planilla_ingreso_id',
        'planilla_zafra_id',        // ✅ Agregado para RelationManager
        'puesto_id',                // ✅ Agregado para RelationManager
        'mayores_funciones_id',     // ✅ Agregado para RelationManager
        'modo_contratacion',        // ✅ Agregado para RelationManager
        'fecha_ingreso',
        'fecha_baja',
        'observaciones',
    ];
    
    public function operario()
    {
        return $this->belongsTo(Operario::class);
    }
    
    public function sector()
    {
        return $this->belongsTo(\App\Models\Sector::class);
    }
    
    public function examenMedico()
    {
        return $this->hasOne(ExamenMedico::class);
    }
    
    public function induccion()
    {
        return $this->hasOne(Induccion::class);
    }
    
    public function autorizacion()
    {
        return $this->hasOne(\App\Models\Autorizacion::class);
    }
    
    // CORREGIDO: solicitante_id apunta a operarios, no a solicitantes
    public function solicitante()
    {
        return $this->belongsTo(Solicitante::class, 'solicitante_id');
    }
    
    public function planilla()
    {
        return $this->belongsTo(\App\Models\PlanillaIngreso::class, 'planilla_ingreso_id');
    }
    
    public function planillaZafra()
    {
        return $this->belongsTo(PlanillaZafra::class);
    }
    
    public function puesto()
    {
        return $this->belongsTo(\App\Models\Puesto::class);
    }
}