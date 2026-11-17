# PRD — Link Preview Santri di PDF Laporan

| | |
|---|---|
| **Produk** | SIADJAR |
| **Penulis** | Raihan Akbar |
| **Status** | v1.0 (diimplementasi, branch `feat/link-preview-santri`) |
| **Tanggal** | 2026-11-16 |
| **Bergantung pada** | Fitur Laporan PDF (`docs/PRD-laporan-pdf.md`, PR #18) |

---

## 1. Latar Belakang

Laporan PDF (Rekap Santri, Rekap Pelanggaran, Rekap Kesehatan) dibagikan ke management dan pengurus santri. Banyak dari mereka tidak punya akun panel admin. Saat membaca laporan, mereka sering butuh detail satu santri: biodata, riwayat pelanggaran, dan riwayat kesehatan. Sekarang detail itu hanya bisa dilihat oleh user yang login.

## 2. Tujuan

1. Nama santri di PDF laporan bisa diklik dan membuka **halaman preview data santri** di web SIADJAR **tanpa login**.
2. Akses dikendalikan **per PDF**: berlaku 30 hari, bisa diperpanjang, bisa dicabut, dan bisa dilindungi PIN (opsional per cetak).
3. Setiap akses tercatat untuk audit.

### Non-Goals

- Akses edit apa pun lewat link.
- Link per santri yang dibagikan terpisah dari PDF.
- Portal wali santri (akun atau login wali).
- Notifikasi real-time saat link dibuka.

## 3. Pertimbangan Keamanan

Satu PDF berisi akses ke **semua santri yang tercantum di dalamnya**. PDF yang diteruskan sama dengan memberikan akses ke semua santri itu. Karena itu:

- Kontrol akses ada di tingkat PDF (arsip): masa berlaku, cabut, dan PIN.
- Link tidak memuat ID yang bisa ditebak tanpa tanda tangan. Mengganti ID santri di URL tidak membuka santri lain.
- Halaman preview hanya menampilkan data minimum (lihat 5.4). NIK, No KK, data orang tua, alamat lengkap, dan lampiran dokumen **tidak pernah** ditampilkan.
- Selama link masih berlaku, PDF diperlakukan sebagai dokumen rahasia. Peringatan ini ditampilkan ke admin saat mencetak.

## 4. Keputusan

| # | Pertanyaan | Keputusan |
|---|---|---|
| 1 | Penerima | Management dan pengurus santri (tanpa akun) |
| 2 | Masa berlaku default | 30 hari, bisa diperpanjang |
| 3 | PIN | Opsional per cetak (PIN per PDF, bukan per santri) |
| 4 | Data di preview | Biodata ringkas, pelanggaran, kesehatan |
| 5 | Toggle link saat cetak | Default **aktif** |
| 6 | PDF lama yang link-nya kedaluwarsa | Ada aksi **Cetak ulang dengan link baru** |

## 5. Kebutuhan Fungsional

### 5.1 Saat Cetak Laporan

| ID | Kebutuhan |
|---|---|
| F1 | Toggle **"Sertakan link preview santri"** di halaman Cetak Laporan, default aktif |
| F2 | Toggle **"Lindungi dengan PIN"**, default mati, hanya tampil jika F1 aktif |
| F3 | Jika F1 aktif, PDF berlaku untuk Rekap Data Santri, Rekap Pelanggaran (tabel utama dan Rekap Poin per Santri), dan Rekap Kesehatan: kolom nama santri menjadi link |
| F4 | Link tampil seperti teks biasa (warna gelap, tanpa garis bawah) agar tetap rapi saat dicetak di kertas |
| F5 | Di atas tabel tercetak satu baris: "Klik nama santri untuk melihat detail. Link berlaku sampai {tanggal}." |
| F6 | Jika PIN aktif, setelah PDF dibuat admin melihat notifikasi persisten berisi PIN 6 digit dan peringatan "Bagikan PIN terpisah dari PDF". PIN tidak dicetak di PDF dan hanya ditampilkan sekali |

### 5.2 Mekanisme Link

| ID | Kebutuhan |
|---|---|
| F7 | Saat cetak dengan link, arsip mendapat `kunci` acak 40 karakter. Yang disimpan hanya hash SHA-256-nya |
| F8 | Format link: `/p/{kunci}/{data_santri_id}/{tanda_tangan}`. `tanda_tangan` = HMAC-SHA256 (APP_KEY) atas `kunci|data_santri_id`, dipotong 32 karakter |
| F9 | Link valid hanya jika: hash kunci cocok dengan arsip, tanda tangan cocok, arsip belum dicabut, dan belum melewati `akses_berlaku_sampai` |
| F10 | Link tidak valid, kedaluwarsa, atau dicabut menampilkan halaman generik "Link tidak berlaku. Hubungi admin pondok." (HTTP 404/410). Tidak ada data santri yang bocor, dan tidak ada pembeda "kunci salah" vs "santri salah" |

### 5.3 PIN

| ID | Kebutuhan |
|---|---|
| F11 | Jika arsip memakai PIN, link pertama kali menampilkan form PIN. Yang disimpan hanya hash PIN (`Hash::make`) |
| F12 | PIN benar disimpan di session untuk arsip tersebut selama 2 jam. Semua santri di PDF yang sama bisa dibuka tanpa memasukkan PIN lagi |
| F13 | Maksimal 5 percobaan salah per 15 menit per IP + arsip. Setelah itu form dikunci sementara |

### 5.4 Halaman Preview

| ID | Kebutuhan |
|---|---|
| F14 | Halaman publik hanya baca, ramah HP, dengan header "YAYASAN ANNY DJAMALUDDIN RASYAD" |
| F15 | **Biodata ringkas**: foto, nama, NISN, JK, tempat/tanggal lahir, jenjang, kelas, kamar, tahun masuk, status |
| F16 | **Pelanggaran**: riwayat (tanggal, pelanggaran, kategori, poin, catatan), total poin, dan status SP sesuai ambang `laporan.ambang_poin` |
| F17 | **Kesehatan**: riwayat (tanggal, penyakit, obat, status sembuh, keterangan) |
| F18 | Data yang ditampilkan adalah **data terkini** (live), bukan snapshot saat PDF dicetak. Ditulis di halaman: "Data per {waktu akses}" |
| F19 | Foto dikirim inline (base64), bukan URL storage publik |
| F20 | Header respons: `X-Robots-Tag: noindex, nofollow`, `Cache-Control: no-store, private`, `Referrer-Policy: no-referrer` |
| F21 | Rate limit 60 request/menit per IP untuk route preview |

### 5.5 Arsip Laporan

| ID | Kebutuhan |
|---|---|
| F22 | Kolom baru di tabel arsip: status link (Aktif s.d. {tanggal} / Kedaluwarsa / Dicabut / Tanpa link), PIN (ya/tidak), jumlah akses |
| F23 | Aksi **Cabut akses** (dengan konfirmasi): semua link di PDF itu langsung mati |
| F24 | Aksi **Perpanjang**: berlaku 30 hari lagi dari hari ini. Tidak tersedia untuk akses yang sudah dicabut |
| F25 | Aksi **Reset PIN**: buat PIN baru dan tampilkan sekali. Tersedia hanya untuk arsip ber-PIN |
| F26 | Aksi **Cetak ulang dengan link baru**: generate PDF baru dengan filter dan orientasi yang sama, kunci baru, dan masa berlaku 30 hari. Hasilnya arsip baru dengan referensi `dicetak_ulang_dari`. Ada opsi "Cabut akses PDF lama" (default aktif). Isi PDF memakai data terkini |
| F27 | Riwayat akses per arsip (waktu, santri, IP, user agent) bisa dilihat dari aksi **Riwayat akses** |

## 6. Desain Teknis (Usulan)

**Migrasi**: tambah kolom di `laporan_arsips`:

| Kolom | Tipe | Keterangan |
|---|---|---|
| akses_kunci_hash | string(64) nullable, unique | SHA-256 dari kunci. Null = PDF tanpa link |
| akses_berlaku_sampai | timestamp nullable | |
| akses_dicabut_at | timestamp nullable | |
| akses_pin_hash | string nullable | Null = tanpa PIN |
| dicetak_ulang_dari | foreignId nullable | ke `laporan_arsips.id`, `nullOnDelete` |

**Tabel baru `laporan_akses_logs`**: `id`, `laporan_arsip_id`, `data_santri_id`, `ip`, `user_agent`, `created_at`.

**Komponen**:

```
app/Reports/Support/SantriPreviewLink.php   // generate kunci, buat URL + tanda tangan, verifikasi
app/Http/Controllers/SantriPreviewController.php  // show, submitPin
resources/views/preview/santri.blade.php    // halaman preview (CSS inline, tanpa build Vite)
resources/views/preview/pin.blade.php
resources/views/preview/tidak-berlaku.blade.php
config/laporan.php                          // link_preview.masa_berlaku_hari = 30, pin_sesi_menit = 120
```

- Kolom laporan bisa diberi penanda `'link' => true`, dan baris membawa `_santri_id`. View `pdf.partials.table` membungkus sel tersebut dengan `<a href>` jika `ReportResult` membawa generator link.
- `ReportPdfGenerator` menerima opsi `denganLink` dan `denganPin`, membuat kunci sebelum render, lalu menyimpan hash-nya ke arsip.
- Route di luar middleware `auth`: `GET /p/{kunci}/{santri}/{tanda_tangan}` dan `POST` untuk PIN, dengan `throttle`.
- Perbandingan tanda tangan memakai `hash_equals`.

## 7. Testing

- Link valid membuka preview. Mengubah `data_santri_id` atau tanda tangan menghasilkan halaman tidak berlaku.
- Link kedaluwarsa atau dicabut tidak menampilkan data.
- Alur PIN: form tampil, PIN salah ditolak, PIN benar membuka semua santri di PDF yang sama, lockout setelah 5 percobaan salah.
- Halaman preview tidak memuat NIK, No KK, atau data orang tua.
- Header noindex, no-store, dan no-referrer ada di respons.
- PDF dengan toggle aktif memuat `href` ke `/p/...`. PDF dengan toggle mati tidak memuat link.
- Aksi arsip: cabut, perpanjang, reset PIN, cetak ulang (arsip baru, kunci baru, akses PDF lama ikut dicabut jika opsinya dipilih).
- Setiap akses preview tercatat di `laporan_akses_logs`.

## 8. Rencana Rilis

| Fase | Isi |
|---|---|
| 1 | Migrasi, `SantriPreviewLink`, link di PDF (3 laporan), halaman preview, halaman tidak berlaku, log akses |
| 2 | PIN per PDF (form, session, lockout, tampil sekali) |
| 3 | Aksi arsip: cabut, perpanjang, reset PIN, cetak ulang dengan link baru, riwayat akses |

## 9. Keputusan Tambahan

| # | Pertanyaan | Keputusan |
|---|---|---|
| 7 | Riwayat di preview | Tampilkan semua riwayat. Total poin dan status SP dihitung untuk periode laporan (jika PDF memakai periode), ditambah total keseluruhan sebagai info. Baris dalam periode disorot |
| 8 | Branch | Branch baru `feat/link-preview-santri`, dibuat di atas `feat/laporan-pdf` (PR #18 belum di-merge) |

## 10. Catatan Implementasi

- PIN dibuat di halaman Cetak Laporan (agar bisa langsung ditampilkan ke admin), dititipkan di session dengan token sekali pakai `pin_token`, lalu dipakai oleh route PDF. Yang disimpan di arsip hanya hash-nya.
- Jika PDF dibuat dengan link, arsip wajib tersimpan. Kegagalan arsip dilempar sebagai error, karena link di PDF bergantung pada arsip.
- Di layar HP, tabel riwayat di halaman preview tampil sebagai kartu per baris.
