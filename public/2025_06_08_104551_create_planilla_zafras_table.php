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
            $table->id();
            $table->unsignedInteger('numero')->unique();
            $table->string('sector');
            $table->year('anio');
            $table->string('turno', 20); // Ej: mañana, tarde, noche
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planilla_zafras');
    }
};