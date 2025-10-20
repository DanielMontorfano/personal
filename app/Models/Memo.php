<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memo extends Model
{
    use HasFactory;

    protected $fillable = [
        'de_solicitante_id',
        'fecha',
        'referencia',
        'cuerpo',
        'numero',
    ];

    protected $dates = ['fecha'];

    // Remitente (Solicitante que envía)
    public function remitente()
    {
        return $this->belongsTo(Solicitante::class, 'de_solicitante_id');
    }

    // Destinatarios (Solicitantes)
    public function destinatarios()
    {
        return $this->belongsToMany(Solicitante::class, 'memo_solicitante', 'memo_id', 'solicitante_id')
                    ->withTimestamps();
    }

    // Operarios mencionados
    public function operarios()
    {
        return $this->belongsToMany(Operario::class, 'memo_operario', 'memo_id', 'operario_id')
                    ->withTimestamps();
    }

    // Numeración automática basada en id: establece el número después de crear
    protected static function booted()
    {
        static::created(function (Memo $memo) {
            if (empty($memo->numero)) {
                $memo->numero = sprintf('MEM-%04d', $memo->id);
                // saveQuietly para evitar loop de eventos
                $memo->saveQuietly();
            }
        });
    }
}
