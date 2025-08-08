<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperariosTable extends Migration
{
    public function up(): void
    {
        Schema::create('operarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('legajo')->unique();
            $table->string('nombre_completo');
            $table->enum('tipo_liquidacion', ['mensual', 'jornalizado']);
            $table->date('fecha_ingreso')->nullable();
            $table->string('direccion')->nullable();
            $table->string('dni', 20)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('cuil', 20)->nullable();
            $table->string('categoria')->nullable();
            $table->string('sector')->nullable();  // O FK si normalizas
            $table->string('tarea')->nullable();   // O FK si normalizas
            $table->unsignedInteger('gerencia')->nullable();  // Puede ser FK
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operarios');
    }
}
