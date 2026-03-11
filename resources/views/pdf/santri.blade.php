<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Santri - {{ $santri->nama }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 13px; color: #333; padding: 30px; }

        .header { text-align: center; margin-bottom: 24px; padding-bottom: 12px; border-bottom: 2px solid #333; }
        .header h2 { font-size: 18px; text-transform: uppercase; letter-spacing: 1px; }
        .header p { font-size: 12px; margin-top: 4px; color: #555; }

        .section-title {
            padding: 6px 12px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 0;
            font-size: 13px;
            border-left: 4px solid #333;
            background: #f0f0f0;
        }

        /* Tabel info (label - value) */
        table.info { width: 100%; border-collapse: collapse; }
        table.info td { padding: 7px 10px; border: 1px solid #ddd; vertical-align: top; }
        table.info td:first-child { width: 35%; font-weight: bold; background: #fafafa; }

        /* Tabel list (riwayat) */
        table.list { width: 100%; border-collapse: collapse; margin-top: 8px; }
        table.list th { background: #333; color: #fff; padding: 7px 10px; text-align: left; font-size: 12px; }
        table.list td { padding: 7px 10px; border: 1px solid #ddd; vertical-align: top; font-size: 12px; }
        table.list tr:nth-child(even) td { background: #f9f9f9; }

        .badge-sembuh { 
            color: #166534; background: #dcfce7; 
            padding: 2px 6px; border-radius: 4px; 
            font-size: 11px; white-space: nowrap;
            display: inline-block;
        }
        .badge-sakit { 
            color: #991b1b; background: #fee2e2; 
            padding: 2px 6px; border-radius: 4px; 
            font-size: 11px; white-space: nowrap;
            display: inline-block;
        }
        .empty-note { color: #999; font-style: italic; padding: 10px; text-align: center; border: 1px solid #ddd; }

        .footer { margin-top: 40px; text-align: right; font-size: 11px; color: #aaa; border-top: 1px solid #eee; padding-top: 8px; }

        .page-break {
            page-break-before: always;
            padding-top: 20px;
        }

        .foto-wrapper {
            float: right;
            margin: 8px 0 10px 15px;
            text-align: center;
            border: 1px solid #ddd;
            padding: 5px;
            background: #fafafa;
            width: 110px;
        }
        .foto-wrapper img {
            width: 100px;
            height: auto;  /* <-- ubah dari 130px ke auto */
            display: block;
        }
        .foto-wrapper p {
            font-size: 10px;
            color: #888;
            margin-top: 4px;
            text-align: center;
        }
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        @media print {
            .print-btn { display: none; }
            body { padding: 10px; }
        }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <h2>Biodata Santri</h2>
        <p>Yayasan Anny Djamaluddin Rasyad</p>
        <p>Dicetak: {{ now()->translatedFormat('d F Y') }}</p>
    </div>

    {{-- DATA PRIBADI --}}
    <div class="section-title">Data Pribadi</div>
    <table class="info" style="margin-top:4px;">
        <tr>
            <td>Nomor Kartu Keluarga</td>
            <td>{{ $santri->no_kk }}</td>
            @if(!empty($fotoBase64))
            <td rowspan="3" style="width:100px; text-align:center; vertical-align:top; padding:4px;">
                <img src="{{ $fotoBase64 }}" 
                    style="width:100px; height:100px; display:block; margin:0 auto;">
            </td>
            @endif
        </tr>
        <tr><td>NIK</td><td>{{ $santri->nik }}</td></tr>
        <tr><td>NISN</td><td>{{ $santri->nisn }}</td></tr>
        <tr><td>Nama Lengkap</td><td colspan="2">{{ $santri->nama }}</td></tr>
        <tr><td>Tempat, Tanggal Lahir</td><td colspan="2">{{ $santri->tmp_lhr }}, {{ \Carbon\Carbon::parse($santri->tgl_lhr)->format('d-m-Y') }}</td></tr>
        <tr><td>Jenis Kelamin</td><td colspan="2">{{ $santri->jk }}</td></tr>
        <tr><td>Alamat</td><td colspan="2">{{ $santri->alamat }}</td></tr>
        <tr><td>RT / RW</td><td colspan="2">{{ $santri->rt }} / {{ $santri->rw }}</td></tr>
        <tr><td>Desa/Kelurahan</td><td colspan="2">{{ $santri->kel }}</td></tr>
        <tr><td>Kecamatan</td><td colspan="2">{{ $santri->kec }}</td></tr>
        <tr><td>Kabupaten/Kota</td><td colspan="2">{{ $santri->kab }}</td></tr>
        <tr><td>Provinsi</td><td colspan="2">{{ $santri->prov }}</td></tr>
        <tr><td>Kode Pos</td><td colspan="2">{{ $santri->kode_pos }}</td></tr>
        <tr><td>No. HP / WA</td><td colspan="2">{{ $santri->no_hp ?? '-' }}</td></tr>
    </table>

    {{-- DATA PENDIDIKAN --}}
    <div class="section-title">Data Pendidikan</div>
    <table class="info" style="margin-top:8px;">
        <tr><td>Kelas</td><td>{{ $santri->kelas ?? '-' }}</td></tr>
        <tr><td>Kamar</td><td>{{ $santri->kamar ?? '-' }}</td></tr>
        <tr><td>Tahun Masuk</td><td>{{ $santri->tahun_masuk ?? '-' }}</td></tr>
        <tr><td>Status</td><td>{{ $santri->status }}</td></tr>
    </table>

    {{-- DATA ORANG TUA --}}
    <div class="section-title">Data Orang Tua / Wali</div>
    <table class="info" style="margin-top:8px;">
        <tr><td>Nama Ayah</td><td>{{ $santri->nama_ayah ?? '-' }}</td></tr>
        <tr><td>Nama Ibu</td><td>{{ $santri->nama_ibu ?? '-' }}</td></tr>
        <tr><td>No. HP Orang Tua</td><td>{{ $santri->no_hp_ortu ?? '-' }}</td></tr>
        <tr><td>Alamat Orang Tua</td><td>{{ $santri->alamat_ortu ?? '-' }}</td></tr>
    </table>

    {{-- RIWAYAT KESEHATAN --}}
    <div class="page-break">
        <div class="section-title">Riwayat Kesehatan</div>
        @if($santri->kesehatan->count() > 0)
            <table class="list">
                <thead>
                    <tr>
                        <th style="width:5%">No</th>
                        <th style="width:20%">Tanggal Sakit</th>
                        <th style="width:25%">Nama Penyakit</th>
                        <th style="width:20%">Obat</th>
                        <th style="width:15%">Status</th>
                        <th style="width:15%">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($santri->kesehatan as $i => $kes)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($kes->tanggal_sakit)->format('d-m-Y') }}</td>
                        <td>{{ $kes->nama_penyakit }}</td>
                        <td>{{ $kes->obat ?? '-' }}</td>
                        <td>
                            @if($kes->is_sembuh)
                                <span class="badge-sembuh">Sembuh</span>
                            @else
                                <span class="badge-sakit">Belum Sembuh</span>
                            @endif
                        </td>
                        <td>{{ $kes->keterangan ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="empty-note">Tidak ada riwayat kesehatan.</p>
        @endif

    {{-- RIWAYAT PELANGGARAN --}}
        <div class="section-title">Riwayat Pelanggaran</div>
        @if($santri->catatanPelanggarans->count() > 0)
            <table class="list">
                <thead>
                    <tr>
                        <th style="width:5%">No</th>
                        <th style="width:15%">Tanggal</th>
                        <th style="width:20%">Kategori</th>
                        <th style="width:25%">Pelanggaran</th>
                        <th style="width:8%">Poin</th>
                        <th style="width:27%">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($santri->catatanPelanggarans as $i => $pel)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($pel->tanggal_kejadian)->format('d-m-Y') }}</td>
                        <td>{{ $pel->pelanggaran->kategoriPelanggaran->nama_kategori ?? '-' }}</td>
                        <td>{{ $pel->pelanggaran->nama_pelanggaran ?? '-' }}</td>
                        <td style="text-align:center; font-weight:bold; color:#991b1b;">
                            {{ $pel->pelanggaran->poin ?? '-' }}
                        </td>
                        <td>{{ $pel->catatan ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="empty-note">Tidak ada riwayat pelanggaran.</p>
        @endif
    </div>

    <div class="footer">
        Dokumen digenerate otomatis &mdash; {{ config('app.name') }} | {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>