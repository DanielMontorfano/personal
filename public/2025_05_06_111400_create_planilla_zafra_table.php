<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('planilla_zafras', function (Blueprint $table) {
            $table->id(); // `id` bigint UNSIGNED AUTO_INCREMENT
            
            $table->unsignedInteger('numero')->index(); // `numero` int UNSIGNED con índice
            
            $table->year('anio'); // `anio` tipo YEAR
            
            $table->string('turno', 20); // `turno` varchar(20)
            
            $table->foreignId('sector_id')->constrained(); // `sector_id` clave foránea
            
            $table->timestamps(); // `created_at` y `updated_at`
        });

        // Opcional: Agregar comentarios (MySQL)
        //DB::statement("ALTER TABLE `planilla_zafras` COMMENT = 'Tabla de planillas de zafras'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planilla_zafras');
    }
};