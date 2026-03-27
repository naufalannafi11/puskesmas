<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->text('rencana_tindak_lanjut')->nullable()->after('rujukan');
        });
    }

    public function down(): void
    {
        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->dropColumn('rencana_tindak_lanjut');
        });
    }
};
