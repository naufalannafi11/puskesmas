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
        Schema::create('rekam_medis_obat', function (Blueprint $col) {
            $col->id();
            $col->foreignId('rekam_medis_id')->constrained('rekam_medis')->onDelete('cascade');
            $col->foreignId('obat_id')->constrained('obats')->onDelete('cascade');
            $col->integer('jumlah')->default(1);
            $col->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekam_medis_obat');
    }
};
