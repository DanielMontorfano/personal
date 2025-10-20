<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reportes_horas_extras', function (Blueprint $table) {
            $table->id();
            $table->string('numero_reporte')->unique();
            $table->string('periodo_reporte');


             // 👇 Relación con Sector (reemplaza seccion_taller)
            $table->foreignId('sector_id')->constrained('sectors')->onDelete('cascade');
            $table->text('trabajos_efectuados')->nullable();
            
            // Relación con solicitantes (jefe que pide)
            $table->foreignId('solicitante_id')->constrained('solicitantes')->onDelete('cascade');
            
            // Relación con users (secretario que opera)
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            
            // Relación con operarios (👈 este es el que faltaba)
            $table->foreignId('operario_id')->constrained('operarios')->onDelete('cascade');


            

            
            $table->string('estado')->default('pendiente');
            $table->date('fecha_reporte');
            $table->decimal('total_horas_general', 8, 2)->default(0);
            $table->decimal('total_pago_general', 12, 2)->default(0);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reportes_horas_extras');
    }
};
