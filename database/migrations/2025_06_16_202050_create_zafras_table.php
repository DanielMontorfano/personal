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
// database/migrations/2025_06_16_200417_create_zafras_table.php

// Verifica que coincida con esto:
Schema::create('zafras', function (Blueprint $table) {
    $table->id();
    $table->string('nombre');
$table->date('fecha_inicio')->nullable(); // Cambiar si es necesario
$table->date('fecha_fin')->nullable();
    $table->text('observaciones')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zafras');
    }
};
