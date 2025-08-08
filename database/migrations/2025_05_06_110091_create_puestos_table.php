<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('puestos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique(); // Nombre único del puesto
            $table->unsignedTinyInteger('categoria'); // Categoría del puesto (1 a 8)
            $table->unsignedSmallInteger('orden')->default(0); // Orden para listados
            $table->text('descripcion')->nullable(); // Campo adicional recomendado
            $table->boolean('activo')->default(true); // Para manejar baja lógica
            $table->timestamps();
            
            // Índices para mejorar performance
            $table->index('orden');
            $table->index('categoria');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('puestos');
    }
};