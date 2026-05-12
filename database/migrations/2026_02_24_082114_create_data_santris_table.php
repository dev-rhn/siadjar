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
            $table->string('no_kk')->nullable();
            $table->string('nik')->nullable()->unique();
            $table->string('nama');
            $table->string('nisn')->nullable();
            $table->string('tmp_lhr')->nullable();
            $table->date('tgl_lhr')->nullable();
            $table->enum('jk', ['Laki-laki', 'Perempuan'])->nullable();

            $table->foreignId('kamar_id')->nullable()->constrained('kamars')->onDelete('set null');
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null');
            
            // Data Alamat
            $table->text('alamat')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('kel')->nullable();
            $table->string('kec')->nullable();
            $table->string('kab')->nullable();
            $table->string('prov')->nullable();
            $table->string('kode_pos')->nullable();

            // Data Pendidikan & Status
            $table->string('asal_sekolah')->nullable();
            $table->enum('keterangan', ['Dhuafa', 'Yatim', 'Piatu', 'Yatim Piatu', 'Reguler'])->default('Reguler')->nullable();
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif')->nullable();
            $table->string('tahun_masuk')->nullable();
            $table->enum('jenjang', ['SD', 'SMP', 'SMA'])->nullable();

            // Data Ayah
            $table->string('nik_ayah', 16)->nullable();
            $table->string('nama_ayah')->nullable();
            $table->string('tmp_lhr_ayah')->nullable();
            $table->date('tgl_lhr_ayah')->nullable();
            $table->string('pendidikan_ayah')->nullable();
            $table->string('pekerjaan_ayah')->nullable();

            // Data Ibu
            $table->string('nik_ibu', 16)->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('tmp_lhr_ibu')->nullable();
            $table->date('tgl_lhr_ibu')->nullable();
            $table->string('pendidikan_ibu')->nullable();
            $table->string('pekerjaan_ibu')->nullable();

            // Data Wali
            $table->string('nik_wali', 16)->nullable();
            $table->string('nama_wali')->nullable();
            $table->string('tmp_lhr_wali')->nullable();
            $table->date('tgl_lhr_wali')->nullable();
            $table->string('pendidikan_wali')->nullable();
            $table->string('pekerjaan_wali')->nullable();
            
            // Dokumen Pendukung
            $table->string('foto_santri')->nullable();
            $table->string('foto_kk')->nullable();
            $table->string('foto_akte')->nullable();
            $table->string('ijazah')->nullable();
            $table->string('nilai_rapot')->nullable();
            $table->string('surat_ket_pindah_sekolah')->nullable();
            $table->string('surat_ket_dhuafa')->nullable();
            $table->string('surat_kematian_org_tua')->nullable();
            $table->string('surat_ket_hak_asuh')->nullable();
            
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
