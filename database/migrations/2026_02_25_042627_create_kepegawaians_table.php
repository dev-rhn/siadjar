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
        Schema::create('kepegawaians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('jabatan_id')->nullable()->constrained('jabatans')->onDelete('set null');
            $table->string('kd_pegawai')->unique();
            $table->string('nama_pegawai');
            $table->string('no_telepon');
            $table->string('alamat');
            $table->string('status_kepegawaian');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kepegawaians');
    }
};
