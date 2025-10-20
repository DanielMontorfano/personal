<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registro_horas', function (Blueprint $table) {
            $table->id();

            // 🔗 Relación con reportes (cada registro pertenece a un reporte, que ya tiene un operario asociado)
            $table->foreignId('reporte_id')
                  ->constrained('reportes_horas_extras')
                  ->onDelete('cascade');


            // 🔹 Nuevo campo: Puesto ocupado (vinculado con tabla puestos)
            $table->foreignId('puesto_ocupado')
                  ->nullable()
                  ->constrained('puestos')
                  ->nullOnDelete();      

            $table->date('fecha_trabajada');
            $table->time('hora_inicio');
            $table->time('hora_fin');

            $table->decimal('total_horas', 5, 2); // ej: 999.99 máx
            $table->string('tipo_hora_extra', 50)->nullable(); // ej: nocturna, feriado
            $table->string('actividad_espec')->nullable();

            $table->decimal('valor_recargo', 8, 2)->default(0);
            $table->decimal('total_pago', 12, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registro_horas');
    }
};
