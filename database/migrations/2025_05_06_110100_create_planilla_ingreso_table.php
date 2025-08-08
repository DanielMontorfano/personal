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
        Schema::create('planilla_ingreso', function (Blueprint $table) {
            $table->id(); // Equivalente a bigint UNSIGNED AUTO_INCREMENT (columna 'id')
            
            $table->date('fecha')->default('2025-06-11'); // Fecha con valor por defecto
            
            $table->foreignId('solicitante_id')->constrained(); // Clave foránea bigint UNSIGNED
            
            $table->string('numero', 191)->nullable(); // varchar(191) nullable
            
            $table->text('observaciones')->nullable(); // text nullable
            
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planilla_ingreso');
    }
};