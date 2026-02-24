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
        Schema::create('kesehatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_santri_id')->constrained()->cascadeOnDelete();
            $table->string('nama_penyakit');
            $table->string('obat')->nullable();
            $table->date('tanggal_sakit');
            $table->text('keterangan')->nullable();
            $table->boolean('is_sembuh')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kesehatans');
    }
};
