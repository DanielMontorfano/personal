<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ingresos', function (Blueprint $table) {
            $table->id(); // bigint UNSIGNED NOT NULL AUTO_INCREMENT
            
            // Claves foráneas con convenciones mejoradas
            $table->foreignId('operario_id')
                  ->constrained('operarios')
                  ->onDelete('restrict')  // Cambiado de 'cascade' a 'restrict' para mayor seguridad
                  ->comment('Referencia al operario asociado');
                  
            $table->foreignId('sector_id')
                  ->nullable()
                  ->constrained('sectors')  // Corregido el nombre de la tabla (singular 'sector' -> plural 'sectores')
                  ->onDelete('set null');
                  
            $table->foreignId('planilla_ingreso_id')
                  ->nullable()
                  ->constrained('planilla_ingreso')  // Nombre más consistente en plural
                  ->onDelete('set null');

            $table->foreignId('puesto_id')
                  ->nullable()
                  ->constrained('puestos')
                  ->onDelete('set null');
                  
            $table->foreignId('solicitante_id')
                  ->nullable()
                  ->constrained('operarios')
                  ->onDelete('set null')
                  ->comment('Operario que solicita el ingreso');

            // Campos normales con comentarios descriptivos
            $table->string('modo_contratacion', 50)
                  ->nullable()
                  ->comment('Modalidad de contratación (Ej: Temporal, Permanente)');
                  
            $table->date('fecha_ingreso')
                  ->nullable()
                  ->index()  // Índice para búsquedas frecuentes
                  ->comment('Fecha efectiva de ingreso');
                  
            $table->date('fecha_baja')
                  ->nullable()
                  ->index()  // Índice para búsquedas frecuentes
                  ->comment('Fecha de baja si aplica');
                  
            $table->text('observaciones')
                  ->nullable()
                  ->comment('Notas adicionales sobre el ingreso');

            // Timestamps con precisión (opcional para Laravel 8+)
            $table->timestamps(6); // Precisión de microsegundos
            
            // Índices compuestos para consultas frecuentes
            $table->index(['operario_id', 'fecha_ingreso']);
            $table->index(['sector_id', 'fecha_ingreso']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('ingresos');
    }
};