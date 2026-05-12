<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Santri - {{ $santri->nama }}</title>
    <style>
        /* CSS Dasar */
        *{ 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        body { 
            font-family: Arial, sans-serif; 
            font-size: 12px; 
            color: #333; 
            padding: 30px; 
        }

        .header { 
            text-align: center; 
            margin-bottom: 20px; 
            padding-bottom: 12px; 
            border-bottom: 2px solid #333; }
        .header h2 { 
            font-size: 18px; 
            text-transform: uppercase; 
            letter-spacing: 1px; 
        }
        .header p { 
            font-size: 11px; 
            margin-top: 4px; 
            color: #555; 
        }
        .section-title {
            padding: 6px 12px;
            font-weight: bold;
            margin-top: 25px;
            font-size: 13px;
            border-left: 4px solid #333;
            background: #f0f0f0;
        }

        /* Layout Profile (Foto & Info Utama) */
        .profile-container {
            margin-top: 15px;
            width: 100%;
        }

        .foto-profile {
            float: left;
            width: 140px; /* Lebar area foto */
        }

        .foto-profile img {
            width: 130px;
            height: auto;
            border: 1px solid #ddd;
            padding: 2px;
            display: block;
        }

        .info-utama {
            float: left;
            width: calc(100% - 140px); /* Sisa lebar untuk tabel */
        }

        /* Tabel Styling */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }
        
        /* Style Tabel Info (Grid-like) */
        table.info td { 
            padding: 8px 12px; 
            border: 1px solid #ddd; 
            vertical-align: middle; 
        }
                
        table.info td:first-child { 
            width: 35%; 
            font-weight: bold; 
            background: #fafafa; 
        }

        /* Tabel List untuk Riwayat */
        table.list th { 
            background: #333; 
            color: #fff; 
            padding: 7px 10px; 
            text-align: left; 
            font-size: 11px;
        }
        table.list td { 
            padding: 7px 10px; 
            border: 1px solid #ddd;
        }
        table.list tr:nth-child(even) td { 
            background: #f9f9f9; 
        }

        .clearfix::after { 
            content: ""; 
            display: table; 
            clear: both; 
        }
        .page-break { 
            page-break-before: always; 
            padding-top: 20px; 
        }
        .footer { 
            margin-top: 30px; 
            text-align: right; 
            font-size: 10px; 
            color: #aaa; 
            border-top: 1px solid #eee; 
            padding-top: 5px; 
        }

        .badge-sembuh { 
            color: #166534; 
            background: #dcfce7; 
            padding: 2px 5px; 
            border-radius: 3px; 
            font-size: 10px; 
        }
        .badge-sakit { 
            color: #991b1b; 
            background: #fee2e2; 
            padding: 2px 5px; 
            border-radius: 3px; 
            font-size: 10px;
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
    <div class="section-title" style="margin-top: 0;">Data Pribadi</div>

    <div class="profile-container clearfix">
        <div class="foto-profile">
            @if(!empty($fotoBase64))
                <img src="{{ $fotoBase64 }}">
            @else
                <div style="width:130px; height:160px; border:1px solid #ddd; background:#f9f9f9;"></div>
            @endif
        </div>

        <div class="info-utama">
            <table class="info" style="margin-top: 0;">
                <tr>
                    <td>Nomor Kartu Keluarga</td>
                    <td>{{ $santri->no_kk }}</td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td>{{ $santri->nik }}</td>
                </tr>
                <tr>
                    <td>NISN</td>
                    <td>{{ $santri->nisn }}</td>
                </tr>
                <tr>
                    <td>Nama Lengkap</td>
                    <td>{{ $santri->nama }}</td>
                </tr>
                <tr>
                    <td>Tempat, Tanggal Lahir</td>
                    <td>{{ $santri->tmp_lhr }}, {{ \Carbon\Carbon::parse($santri->tgl_lhr)->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>{{ $santri->jk }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- DATA ALAMAT --}}
    <div class="section-title">Data Alamat</div>
    <table class="info">
        <tr>
            <td>Alamat Lengkap</td>
            <td>{{ $santri->alamat }}</td>
        </tr>
        <tr>
            <td>RT / RW</td>
            <td>{{ $santri->rt }} / {{ $santri->rw }}</td>
        </tr>
        <tr>
            <td>Desa / Kelurahan</td>
            <td>{{ $santri->kel }}</td>
        </tr>
        <tr>
            <td>Kecamatan</td>
            <td>{{ $santri->kec }}</td>
        </tr>
        <tr>
            <td>Kabupaten / Kota</td>
            <td>{{ $santri->kab }}</td>
        </tr>
        <tr>
            <td>Provinsi</td>
            <td>{{ $santri->prov }}</td>
        </tr>
        <tr>
            <td>Kode Pos</td>
            <td>{{ $santri->kode_pos }}</td>
        </tr>
        <tr>
            <td>No. HP / WA Santri</td>
            <td>{{ $santri->no_hp ?? '-' }}</td>
        </tr>
    </table>

    {{-- DATA PENDIDIKAN --}}
    <div class="section-title">Data Pendidikan</div>
    <table class="info">
        <tr><td>Kelas</td><td>{{ $santri->kelas->nama_kelas ?? '-' }}</td></tr>
        <tr><td>Kamar</td><td>{{ $santri->kamar->nama_kamar ?? '-' }}</td></tr>
        <tr><td>Tahun Masuk</td><td>{{ $santri->tahun_masuk ?? '-' }}</td></tr>
        <tr><td>Status</td><td>{{ $santri->status }}</td></tr>
    </table>

    {{-- DATA ORANG TUA --}}
    <div class="section-title">Data Orang Tua / Wali</div>
    <table class="info">
        <tr><td>Nama Ayah</td><td>{{ $santri->nama_ayah ?? '-' }}</td></tr>
        <tr><td>Nama Ibu</td><td>{{ $santri->nama_ibu ?? '-' }}</td></tr>
        <tr><td>No. HP Orang Tua</td><td>{{ $santri->no_hp_ortu ?? '-' }}</td></tr>
        <tr><td>Alamat Orang Tua</td><td>{{ $santri->alamat_ortu ?? '-' }}</td></tr>
    </table>

    {{-- RIWAYAT KESEHATAN --}}
    <div class="page-break">
        <div class="section-title">Riwayat Kesehatan</div>
        @if($santri->kesehatan->count() > 0)
            <table class="list" style="margin-top:10px;">
                <thead>
                    <tr>
                        <th style="width:5%">No</th>
                        <th style="width:20%">Tanggal Sakit</th>
                        <th style="width:25%">Penyakit</th>
                        <th style="width:15%">Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($santri->kesehatan as $i => $kes)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($kes->tanggal_sakit)->format('d-m-Y') }}</td>
                        <td>{{ $kes->nama_penyakit }}</td>
                        <td>
                            <span class="{{ $kes->is_sembuh ? 'badge-sembuh' : 'badge-sakit' }}">
                                {{ $kes->is_sembuh ? 'Sembuh' : 'Sakit' }}
                            </span>
                        </td>
                        <td>{{ $kes->keterangan ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align:center; padding:20px; border:1px solid #ddd; margin-top:10px; color:#999; font-style:italic;">Tidak ada riwayat kesehatan.</p>
        @endif

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
            <p style="text-align:center; padding:20px; border:1px solid #ddd; margin-top:10px; color:#999; font-style:italic;">Tidak ada riwayat pelanggaran.</p>
        @endif
    </div>

    {{-- LAMPIRAN GAMBAR --}}
    @foreach($lampirans as $lampiran)
    <div class="page-break">
        <div class="section-title">Lampiran: {{ $lampiran['label'] }}</div>
        <div style="text-align: center; margin-top: 20px;">
            <img src="{{ $lampiran['data'] }}" style="max-width: 100%; max-height: 800px; border: 1px solid #ddd;">
        </div>
    </div>
    @endforeach

    <div class="footer">
        Dokumen digenerate otomatis &mdash; {{ config('app.name') }} | {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>