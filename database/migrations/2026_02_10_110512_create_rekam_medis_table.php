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
        Schema::create('rekam_medis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pasien_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('dokter_id')->constrained('users')->onDelete('cascade');

            $table->date('tanggal');
            $table->text('anamnesis')->nullable();
            $table->text('pemeriksaan')->nullable();
            $table->string('diagnosis')->nullable();
            $table->string('kode_icd')->nullable();
            $table->text('tindakan')->nullable();
            $table->text('pengobatan')->nullable();
            $table->text('rujukan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekam_medis');
    }
};
