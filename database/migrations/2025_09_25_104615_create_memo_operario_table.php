<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('memo_operario', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('memo_id');
            $table->unsignedBigInteger('operario_id');
            $table->timestamps();

            $table->foreign('memo_id')->references('id')->on('memos')->onDelete('cascade');
            $table->foreign('operario_id')->references('id')->on('operarios')->onDelete('cascade');

            $table->unique(['memo_id','operario_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memo_operario');
    }
};
