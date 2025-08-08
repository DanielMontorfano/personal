<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('examen_medicos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ingreso_id')->constrained()->onDelete('cascade');

            $table->date('fecha_examen');
            $table->enum('resultado', ['apto', 'no_apto', 'apto_con_restricciones']);
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('examen_medicos');
    }
};
