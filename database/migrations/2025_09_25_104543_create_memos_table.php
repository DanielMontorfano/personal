<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('memos', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->nullable()->unique(); // MEM-0001 (se llenará al crear)
            $table->unsignedBigInteger('de_solicitante_id'); // remitente
            $table->timestamp('fecha')->nullable();
            $table->string('referencia')->nullable();
            $table->longText('cuerpo')->nullable();
            $table->timestamps();

            $table->foreign('de_solicitante_id')
                  ->references('id')
                  ->on('solicitantes')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memos');
    }
};
