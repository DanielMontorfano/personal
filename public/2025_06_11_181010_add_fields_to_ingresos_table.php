<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ingresos', function (Blueprint $table) {
            $table->foreignId('planilla_zafra_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('puesto_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('mayor_funcion_id')->nullable()->constrained('mayor_funcions')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ingresos', function (Blueprint $table) {
            $table->dropForeign(['planilla_zafra_id']);
            $table->dropColumn('planilla_zafra_id');

            $table->dropForeign(['puesto_id']);
            $table->dropColumn('puesto_id');

            $table->dropForeign(['mayor_funcion_id']);
            $table->dropColumn('mayor_funcion_id');
        });
    }
};
