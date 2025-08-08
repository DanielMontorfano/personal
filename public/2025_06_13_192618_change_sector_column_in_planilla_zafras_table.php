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
    Schema::table('planilla_zafras', function (Blueprint $table) {
        $table->dropColumn('sector'); // Eliminamos el campo viejo
        $table->foreignId('sector_id') // Agregamos la FK
            ->constrained('sectors')
            ->after('numero');
    });
}

public function down(): void
{
    Schema::table('planilla_zafras', function (Blueprint $table) {
        $table->dropForeign(['sector_id']);
        $table->dropColumn('sector_id');
        $table->string('sector')->after('numero'); // Restauramos el campo anterior
    });
}

};
