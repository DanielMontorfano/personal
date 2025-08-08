<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('planilla_zafras', function (Blueprint $table) {
            if (Schema::hasColumn('planilla_zafras', 'sector')) {
                $table->dropColumn('sector');
            }
            if (!Schema::hasColumn('planilla_zafras', 'sector_id')) {
                $table->unsignedBigInteger('sector_id')->nullable()->after('numero');
            }
        });
    }

    public function down(): void
    {
        Schema::table('planilla_zafras', function (Blueprint $table) {
            if (Schema::hasColumn('planilla_zafras', 'sector_id')) {
                $table->dropColumn('sector_id');
            }
            if (!Schema::hasColumn('planilla_zafras', 'sector')) {
                $table->string('sector')->nullable();
            }
        });
    }
};
