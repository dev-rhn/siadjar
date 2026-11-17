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
        Schema::table('laporan_arsips', function (Blueprint $table) {
            $table->string('akses_kunci_hash', 64)->nullable()->unique()->after('file_size');
            $table->timestamp('akses_berlaku_sampai')->nullable()->after('akses_kunci_hash');
            $table->timestamp('akses_dicabut_at')->nullable()->after('akses_berlaku_sampai');
            $table->string('akses_pin_hash')->nullable()->after('akses_dicabut_at');
            $table->foreignId('dicetak_ulang_dari')->nullable()->after('akses_pin_hash')
                ->constrained('laporan_arsips')->nullOnDelete();
        });

        Schema::create('laporan_akses_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_arsip_id')->constrained('laporan_arsips')->cascadeOnDelete();
            $table->foreignId('data_santri_id')->nullable()->constrained('data_santris')->nullOnDelete();
            $table->string('ip', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_akses_logs');

        Schema::table('laporan_arsips', function (Blueprint $table) {
            $table->dropConstrainedForeignId('dicetak_ulang_dari');
            $table->dropUnique(['akses_kunci_hash']);
            $table->dropColumn(['akses_kunci_hash', 'akses_berlaku_sampai', 'akses_dicabut_at', 'akses_pin_hash']);
        });
    }
};
