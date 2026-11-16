# PRD — Fitur Laporan Umum (Cetak PDF)

| | |
|---|---|
| **Produk** | SIADJAR (panel admin Filament) |
| **Penulis** | Raihan Akbar |
| **Status** | v1.1 (Fase 1 + L2, L3 diimplementasi) |
| **Tanggal** | 2026-11-10 |

---

## 1. Latar Belakang

Saat ini SIADJAR hanya bisa mencetak PDF untuk **satu santri** (`SantriPdfController` + `resources/views/pdf/santri.blade.php`). Ekspor lain tersedia dalam bentuk Excel (Filament Exporter), tapi tidak ada laporan rekap yang siap cetak untuk kebutuhan administrasi pondok (rapat, laporan ke yayasan, arsip).

Setiap kebutuhan laporan baru saat ini butuh controller + view + route baru, sehingga kode duplikat (header kop, helper base64, setting kertas) dan tidak konsisten.

## 2. Tujuan

1. Menyediakan **satu halaman "Laporan"** di panel admin untuk membuat laporan rekap dari modul yang ada dan mencetaknya ke PDF.
2. Membangun **kerangka laporan generik** (header lembaga, filter, tabel, ringkasan) sehingga laporan baru cukup didefinisikan di satu class, tanpa controller/route/view baru.
3. Tampilan PDF konsisten: header nama lembaga, nomor halaman, tanggal cetak, pencetak.
4. Setiap PDF yang dicetak **diarsipkan** dan bisa diunduh ulang dari riwayat cetak.

### Non-Goals (v1)

- Report builder drag-and-drop / user membuat query sendiri.
- Penjadwalan laporan otomatis & kirim email.
- Grafik/chart di dalam PDF (DomPDF terbatas; dipertimbangkan v2).
- Mengganti ekspor Excel yang sudah ada.

## 3. Pengguna

| Persona | Kebutuhan |
|---|---|
| Admin / TU | Rekap santri, surat masuk/keluar per periode untuk arsip & laporan yayasan |
| Bagian Keamanan / Kesiswaan | Rekap pelanggaran per santri/kelas/kamar beserta total poin |
| Bagian Kesehatan | Rekap santri sakit per periode |
| Pimpinan | Laporan ringkas siap cetak |

> **Keputusan:** tidak ada pembatasan role. Semua user `@siadjar.com` (yang lolos `canAccessPanel`) bisa mengakses semua laporan dan semua arsip.

## 4. Ruang Lingkup Laporan (v1)

| # | Laporan | Sumber Data | Filter | Kolom Utama | Ringkasan |
|---|---|---|---|---|---|
| L1 | Rekap Data Santri | `DataSantri` + `kelas`, `kamar` | status, jenjang, kelas, kamar, tahun masuk, jk | NISN, nama, JK, TTL, kelas, kamar, tahun masuk, status | jumlah per JK, per status |
| L2 | Rekap Pelanggaran | `CatatanPelanggaran` + `pelanggaran.kategoriPelanggaran`, `dataSantri` | rentang tanggal, kategori, kelas, kamar, santri | tanggal, nama santri, kelas, pelanggaran, kategori, poin, catatan | total kejadian, total poin, top 10 santri poin terbanyak, **status sanksi (SP) per santri** (lihat 4.1) |
| L3 | Rekap Kesehatan | `Kesehatan` + `dataSantri` | rentang tanggal, status sembuh, kelas, kamar | tanggal sakit, nama santri, penyakit, obat, status | jumlah kasus, masih sakit vs sembuh |
| L4 | Agenda Surat | `Surat` + `jenisSurat`, `pegawai`, `penerimaSurats` | rentang tanggal, arah (masuk/keluar), jenis surat, status | nomor surat, tanggal, jenis, perihal, penerima/pengirim, status | jumlah per arah, per jenis |
| L5 | Data Kepegawaian | `Kepegawaian` + `jabatan` | jabatan, status kepegawaian | kode, nama, jabatan, telepon, status | jumlah per jabatan/status |
| L6 | Daftar Penghuni Kamar / Kelas | `Kamar`/`Kelas` + santri | kamar/kelas | per kamar/kelas: pengasuh/wali, kapasitas, daftar santri | terisi vs kapasitas |

Prioritas: **L1, L2, L4** (P0), lalu L3, L5 (P1), lalu L6 (P2).

### 4.1 Ambang Poin Pelanggaran (L2)

Total poin dihitung **per santri** dari semua catatan pelanggaran dalam rentang tanggal filter. Status sanksi ditampilkan di tabel ringkasan per santri dan diberi penanda visual (teks tebal / warna latar) di PDF.

| Status | Total poin (default) |
|---|---|
| — (Aman) | < 25 |
| SP1 | 25 – 49 |
| SP2 | 50 – 74 |
| SP3 | ≥ 75 |

- Angka default 25/50/75 dipakai (dikonfirmasi). Disimpan di `config/laporan.php` agar mudah diubah tanpa ubah kode.
- Ringkasan L2 menambah hitungan: jumlah santri per status (SP1/SP2/SP3).
- Filter tambahan L2: "Hanya tampilkan santri berstatus SP" (Toggle).

## 5. User Flow

1. User buka menu **Laporan** (navigation group baru) di panel admin.
2. Pilih jenis laporan (card/select).
3. Form filter muncul sesuai laporan (dinamis per definisi).
4. Pilih orientasi (portrait/landscape). Kertas selalu A4. Tidak ada blok tanda tangan.
5. Klik **Pratinjau**: hasil tabel ditampilkan di halaman (HTML) + jumlah baris.
6. Klik **Cetak PDF**: PDF dibuat, disimpan ke arsip, lalu dibuka di tab baru (stream) atau **Unduh**.
7. Menu **Arsip Laporan** menampilkan riwayat semua PDF yang pernah dicetak; user bisa unduh ulang file yang sama persis.

Tambahan: tombol **"Cetak PDF"** di header tabel resource terkait (mis. List Data Santri) yang membawa filter tabel aktif ke laporan yang sesuai.

## 6. Kebutuhan Fungsional

| ID | Kebutuhan | Prioritas |
|---|---|---|
| F1 | Halaman Filament `Laporan` dengan pilihan jenis laporan | P0 |
| F2 | Tiap laporan didefinisikan sebagai class turunan `BaseReport` (judul, filter schema, query, kolom, ringkasan, orientasi default) | P0 |
| F3 | Registry laporan: daftar class laporan terdaftar di satu tempat (config / array) | P0 |
| F4 | Filter dinamis memakai komponen form Filament (Select, DatePicker range, Toggle) | P0 |
| F5 | Validasi filter: rentang tanggal wajib untuk laporan berbasis periode, maks 1 tahun | P0 |
| F6 | Pratinjau HTML sebelum cetak | P1 |
| F7 | Generate PDF via DomPDF (`barryvdh/laravel-dompdf`, sudah terpasang) memakai satu layout blade generik | P0 |
| F8 | Layout PDF: **tanpa kop/logo**. Header cukup teks nama lembaga **"YAYASAN ANNY DJAMALUDDIN RASYAD"** (tengah, kapital, tebal) + garis bawah, lalu judul laporan, keterangan filter yang dipakai, tabel, ringkasan, footer (tanggal cetak, nama pencetak, "Hal X dari Y") | P0 |
| F9 | Header tabel diulang tiap halaman; baris tidak terpotong di tengah | P0 |
| F10 | Kertas tetap **A4**; user hanya memilih orientasi portrait/landscape (default per laporan) | P0 |
| F11 | Nama file: `laporan-{slug}-{YYYYMMDD-HHmm}.pdf` | P0 |
| F12 | Nama lembaga disimpan di `config/laporan.php` (bisa di-override `LAPORAN_NAMA_LEMBAGA` di `.env`) | P0 |
| F13 | Hitung total poin & status SP per santri di L2 sesuai ambang di 4.1 | P0 |
| F14 | **Arsip:** setiap Cetak PDF menyimpan file ke disk `local` (`storage/app/private/laporan/{YYYY}/{MM}/`) dan mencatat riwayat di tabel `laporan_arsips` | P0 |
| F15 | Resource Filament **Arsip Laporan** (read-only): kolom jenis laporan, ringkasan filter, jumlah baris, dicetak oleh, waktu cetak, ukuran file; aksi Unduh & Lihat; filter per jenis laporan & tanggal cetak | P0 |
| F16 | Unduh arsip lewat route ber-`auth` (file tidak di disk `public`, tidak bisa diakses langsung lewat URL) | P0 |
| F17 | Hapus arsip (hapus record + file) via aksi Filament dengan konfirmasi | P1 |
| F18 | Tombol "Cetak PDF" di list resource yang meneruskan filter tabel aktif | P2 |
| F19 | Refactor PDF santri existing agar memakai header/layout bersama | P2 |

## 7. Kebutuhan Non-Fungsional

- **Performa:** laporan ≤ 2.000 baris selesai < 10 detik. Di atas batas itu tampilkan peringatan & sarankan persempit filter (DomPDF lambat & boros memori untuk dataset besar). Gunakan eager loading untuk hindari N+1.
- **Penyimpanan arsip:** estimasi ukuran PDF 50–500 KB per file. Arsip **disimpan selamanya** (tidak ada auto-hapus); pantau kapasitas disk VM. Arsip ikut backup storage.
- **Keamanan:** route PDF & unduh arsip di belakang middleware `auth` + `canAccessPanel`; parameter filter divalidasi & di-whitelist (tidak ada kolom/sort bebas dari request). Data sensitif (NIK, No KK, data ortu) **tidak** ditampilkan di laporan rekap default.
- **Konsistensi:** satu file CSS cetak untuk semua laporan; font yang didukung DomPDF (DejaVu Sans untuk karakter non-ASCII).
- **Lokal:** tanggal format Indonesia (`10 November 2026`), timezone `Asia/Jakarta`.
- **Testabilitas:** feature test per laporan — filter menghasilkan query benar, endpoint mengembalikan `application/pdf`, user tanpa akses ditolak.

## 8. Desain Teknis (Usulan)

```
app/
  Reports/
    BaseReport.php            // abstract: title(), slug(), filters(): array, query(array $filters): Builder,
                              // columns(): array, summary(Collection $rows, array $filters): array,
                              // orientation(): string
    ReportRegistry.php        // daftar class laporan
    SantriReport.php          // L1
    PelanggaranReport.php     // L2
    SuratReport.php           // L4
    ...
  Models/
    LaporanArsip.php          // riwayat cetak
  Filament/Pages/
    Laporan.php               // halaman pilih laporan + form filter + pratinjau
  Filament/Resources/
    LaporanArsips/            // resource read-only Arsip Laporan
  Http/Controllers/
    ReportPdfController.php   // GET /laporan/{slug}/pdf?filters... : generate, simpan arsip, stream
                              // GET /laporan/arsip/{arsip}/download : unduh ulang
config/
  laporan.php                 // nama lembaga, batas baris, disk arsip, ambang poin SP, daftar laporan
resources/views/pdf/
  layouts/report.blade.php    // header nama lembaga, footer, nomor halaman, CSS cetak
  report.blade.php            // render generik: columns + rows + summary
```

Tabel `laporan_arsips`:

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint | |
| report_slug | string | mis. `pelanggaran` |
| judul | string | judul laporan saat dicetak |
| filters | json | nilai filter tervalidasi (audit) |
| keterangan_filter | text | filter dalam bentuk teks terbaca, mis. `Status: Aktif; Kelas: 7A` |
| orientasi | string | `portrait` / `landscape` |
| jumlah_baris | unsigned int | |
| file_path | string | path relatif di disk `local` |
| file_size | unsigned int | byte |
| user_id | foreignId nullable | pencetak, `nullOnDelete` |
| created_at / updated_at | timestamp | waktu cetak |

- Kolom didefinisikan sebagai array `['label' => 'Nama', 'value' => fn ($row) => $row->nama, 'width' => '25%', 'align' => 'left']` agar view generik.
- Laporan khusus (mis. L6 dikelompokkan per kamar) boleh override `view()` untuk pakai blade sendiri tetapi tetap extend layout yang sama.
- Nomor halaman via `$pdf->getDomPDF()->getCanvas()->page_text(...)` atau script inline DomPDF (`isPhpEnabled`).
- Helper `convertToBase64` dipindah ke class/helper bersama (dipakai foto santri).
- Alur cetak: render PDF, lalu `$pdf->output()` disimpan ke storage, buat record `LaporanArsip`, lalu stream file. Jika simpan gagal, PDF tetap dikirim ke user dan error dicatat di log.

## 9. Metrik Keberhasilan

- 100% laporan P0 bisa dicetak tanpa error di production.
- Admin tidak lagi menyusun rekap manual di Excel untuk laporan bulanan (konfirmasi user setelah 1 bulan).
- Menambah laporan baru ≤ 1 class + (opsional) 1 blade, tanpa route baru.

## 10. Rencana Rilis

| Fase | Isi |
|---|---|
| 1 | Kerangka: `BaseReport`, registry, layout PDF, controller, halaman Laporan, arsip (tabel + resource + unduh); L1 Rekap Santri |
| 2 | L2 Pelanggaran (dengan status SP), L4 Agenda Surat |
| 3 | L3, L5, pratinjau HTML, hapus arsip |
| 4 | L6, tombol cetak dari list resource, refactor PDF santri |

## 11. Keputusan

| # | Pertanyaan | Keputusan |
|---|---|---|
| 1 | Pembatasan akses per role? | Tidak. Semua user `@siadjar.com` akses semua laporan & arsip |
| 2 | Kop surat? | Tanpa kop/logo. Header cukup "YAYASAN ANNY DJAMALUDDIN RASYAD" |
| 3 | Ukuran kertas? | A4 |
| 4 | Tampilkan ambang poin SP di L2? | Ya (lihat 4.1) |
| 5 | Arsipkan PDF? | Ya (F14–F17) |

| 6 | Ambang poin SP? | Default 25 / 50 / 75 |
| 7 | Blok tanda tangan? | Tidak perlu |
| 8 | Masa simpan arsip? | Selamanya |

## 12. Status Implementasi

- **Fase 1 selesai** (branch `feat/laporan-pdf`): `BaseReport`, `ReportRegistry`, `ReportPdfGenerator`, layout PDF, `ReportPdfController`, halaman Cetak Laporan, resource Arsip Laporan, L1 Rekap Data Santri, feature test.
- **L2 Rekap Pelanggaran & L3 Rekap Kesehatan selesai** (dimajukan dari Fase 2/3): filter periode wajib (default bulan berjalan, maks. 1 tahun), kelas, kamar, santri; L2 + kategori & "hanya santri SP", tabel Rekap Poin per Santri dengan sorotan warna SP1/SP2/SP3; L3 + status sembuh, ringkasan 5 penyakit terbanyak, sorotan baris belum sembuh.
- Sisa: L4 Agenda Surat, L5 Data Kepegawaian, L6 Penghuni Kamar/Kelas, pratinjau HTML, hapus arsip, tombol cetak dari list resource, refactor PDF santri.
