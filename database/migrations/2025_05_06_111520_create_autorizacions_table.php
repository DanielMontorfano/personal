<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('autorizacions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ingreso_id')->constrained()->onDelete('cascade');

            // Firmantes
            $table->string('jefe_sector')->nullable();
            $table->string('medicina_laboral')->nullable();
            $table->string('seguridad_higiene')->nullable();
            $table->string('personal')->nullable();
            $table->string('jefe_planta')->nullable();

            $table->date('fecha_autorizacion')->nullable();
            $table->text('detalle_aprobacion')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('autorizacions');
    }
};
