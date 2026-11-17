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
        Schema::create('laporan_arsips', function (Blueprint $table) {
            $table->id();
            $table->string('report_slug')->index();
            $table->string('judul');
            $table->json('filters')->nullable();
            $table->text('keterangan_filter')->nullable();
            $table->string('orientasi', 20);
            $table->unsignedInteger('jumlah_baris');
            $table->string('file_path');
            $table->unsignedInteger('file_size');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_arsips');
    }
};
