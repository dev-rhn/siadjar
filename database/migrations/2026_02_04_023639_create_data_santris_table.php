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
        Schema::create('data_santris', function (Blueprint $table) {
            $table->id();

            // Data Identitas Utama
            $table->string('no_kk', 16);
            $table->string('nik', 16)->unique();
            $table->string('nama');
            $table->string('nisn', 10)->nullable();
            $table->string('tmp_lhr');
            $table->date('tgl_lhr');
            $table->enum('jk', ['Laki-laki', 'Perempuan']);
            
            // Data Alamat
            $table->text('alamat');
            $table->string('rt', 3);
            $table->string('rw', 3);
            $table->string('kel');
            $table->string('kec');
            $table->string('kab');
            $table->string('prov');
            $table->string('kode_pos', 5);
            
            // Data Pendidikan & Status
            $table->string('asal_sekolah');
            $table->enum('keterangan', ['Dhuafa', 'Yatim', 'Piatu', 'Yatim Piatu', 'Reguler'])->default('Reguler');
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->year('tahun_masuk');
            $table->enum('jenjang', ['SD', 'SMP', 'SMA']);

            // Data Ayah / Wali
            $table->string('nik_ayah', 16)->nullable();
            $table->string('nama_ayah')->nullable();
            $table->string('tmp_lhr_ayah')->nullable();
            $table->date('tgl_lhr_ayah')->nullable();
            $table->string('pendidikan_ayah')->nullable();
            $table->string('pekerjaan_ayah')->nullable();

            // Data Ibu / Wali
            $table->string('nik_ibu', 16)->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('tmp_lhr_ibu')->nullable();
            $table->date('tgl_lhr_ibu')->nullable();
            $table->string('pendidikan_ibu')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_santris');
    }
};
