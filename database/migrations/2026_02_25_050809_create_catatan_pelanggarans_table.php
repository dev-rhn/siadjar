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
        Schema::create('catatan_pelanggarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_santri_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pelanggaran_id')->constrained()->cascadeOnDelete();
            
            $table->date('tanggal_kejadian');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catatan_pelanggarans');
    }
};
