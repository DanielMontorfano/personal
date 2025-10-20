<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('memo_solicitante', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('memo_id');
            $table->unsignedBigInteger('solicitante_id');
            $table->timestamps();

            $table->foreign('memo_id')->references('id')->on('memos')->onDelete('cascade');
            $table->foreign('solicitante_id')->references('id')->on('solicitantes')->onDelete('cascade');

            $table->unique(['memo_id','solicitante_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memo_solicitante');
    }
};
