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
            $table->foreignId('zafra_id')->constrained();
            $table->foreignId('sector_id')->constrained();
            $table->foreignId('solicitante_id')->constrained('solicitantes');
            $table->date('fecha');
            $table->string('numero')->nullable();
            $table->text('observaciones')->nullable();
            $table->softDeletes(); // agrega columna 'deleted_at'

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
