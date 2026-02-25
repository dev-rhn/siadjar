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
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_surat_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pegawai_id')->constrained('kepegawaians')->cascadeOnDelete();
            $table->enum('arah', ['Masuk', 'Keluar']);
            $table->string('nomor_surat')->unique();
            $table->date('tanggal');
            $table->string('perihal');
            $table->text('keterangan')->nullable();
            $table->enum('status', ['Draft', 'Diajukan', 'Disetujui', 'Ditolak'])->default('Draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
