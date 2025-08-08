<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asignacion_zafras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planilla_zafra_id')->constrained()->cascadeOnDelete();
            $table->foreignId('operario_id')->constrained('operarios')->nullable();
            $table->foreignId('puesto_id')->constrained('puestos');
            $table->enum('turno', ['Mañana', 'Tarde', 'Noche', 'Por día']);
            
            // Campos inmutables
            $table->string('categoria_puesto', 10)->nullable()->comment('CCT del puesto (1-8)');
            $table->string('categoria_operario', 10)->nullable()->comment('Categoría real del operario (1-8)');
            $table->string('categoria_mayor', 10)->nullable()->comment('Mayor categoría aplicada (1-8)');
            $table->char('condicion', 1)->default('T')->comment('P=Permamente, T=Transitorio, E=Especial');
            $table->boolean('ingresado')->default(false);
            $table->timestamp('fecha_ingreso')->nullable();
            $table->timestamps();

            // Índices para mejor performance
            $table->index(['planilla_zafra_id', 'turno']);
            $table->index(['operario_id', 'fecha_ingreso']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asignacion_zafras');
    }
};