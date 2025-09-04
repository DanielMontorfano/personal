<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitantes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo');
            $table->string('cargo')->nullable(); // jefe, encargado, etc.
            $table->foreignId('sector_id')->constrained('sectors')->onDelete('cascade'); // CAMBIAR esta línea
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitantes');
    }
};