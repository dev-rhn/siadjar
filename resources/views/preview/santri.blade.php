@extends('preview.layout')

@section('title', $santri->nama)

@php
    $tanggal = fn ($value) => $value ? \Illuminate\Support\Carbon::parse($value)->locale('id')->translatedFormat('d F Y') : '-';
    $badgeSp = fn (string $status) => $status === \App\Reports\Support\StatusPoin::AMAN ? '' : strtolower($status);
@endphp

@section('content')
    <div class="card">
        <div class="profil">
            @if ($fotoBase64)
                <img src="{{ $fotoBase64 }}" alt="Foto {{ $santri->nama }}">
            @else
                <div class="foto-kosong">Tanpa foto</div>
            @endif
            <div>
                <h1>{{ $santri->nama }}</h1>
                <span class="badge">{{ $santri->status ?? '-' }}</span>
                <dl class="biodata">
                    <dt>NISN</dt><dd>{{ $santri->nisn ?: '-' }}</dd>
                    <dt>Jenis kelamin</dt><dd>{{ $santri->jk ?: '-' }}</dd>
                    <dt>Tempat, tgl lahir</dt><dd>{{ collect([$santri->tmp_lhr, $santri->tgl_lhr ? $tanggal($santri->tgl_lhr) : null])->filter()->implode(', ') ?: '-' }}</dd>
                    <dt>Jenjang</dt><dd>{{ $santri->jenjang ?: '-' }}</dd>
                    <dt>Kelas</dt><dd>{{ $santri->kelas?->nama_kelas ?? '-' }}</dd>
                    <dt>Kamar</dt><dd>{{ $santri->kamar?->nama_kamar ?? '-' }}</dd>
                    <dt>Tahun masuk</dt><dd>{{ $santri->tahun_masuk ?: '-' }}</dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>Pelanggaran</h2>
        <div class="stat">
            @if ($periode)
                <div>
                    <span class="muted">Poin periode {{ $periode[0]->translatedFormat('d M Y') }} – {{ $periode[1]->translatedFormat('d M Y') }}</span>
                    <strong>{{ $totalPoinPeriode }}</strong>
                </div>
            @endif
            <div>
                <span class="muted">Status</span>
                <strong><span class="badge {{ $badgeSp($statusSp) }}">{{ $statusSp }}</span></strong>
            </div>
            <div>
                <span class="muted">Total poin keseluruhan</span>
                <strong>{{ $totalPoinKeseluruhan }}</strong>
            </div>
        </div>
        <p class="muted">
            Ambang: {{ $keteranganAmbang }}.
            @if ($periode)
                Status dihitung dari poin dalam periode laporan; baris berwarna berada dalam periode.
            @else
                Status dihitung dari total poin keseluruhan.
            @endif
        </p>

        @if ($pelanggarans->isEmpty())
            <p class="muted">Tidak ada catatan pelanggaran.</p>
        @else
            <div class="tabel">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Pelanggaran</th>
                            <th>Kategori</th>
                            <th class="kanan">Poin</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pelanggarans as $catatan)
                            <tr @if ($dalamPeriode($catatan)) class="periode" @endif>
                                <td data-label="Tanggal" style="white-space: nowrap">{{ $tanggal($catatan->tanggal_kejadian) }}</td>
                                <td data-label="Pelanggaran">{{ $catatan->pelanggaran?->nama_pelanggaran ?? '-' }}</td>
                                <td data-label="Kategori">{{ $catatan->pelanggaran?->kategoriPelanggaran?->nama_kategori ?? '-' }}</td>
                                <td data-label="Poin" class="kanan">{{ $catatan->pelanggaran?->poin ?? 0 }}</td>
                                <td data-label="Catatan">{{ $catatan->catatan ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="card">
        <h2>Kesehatan</h2>
        @if ($kesehatans->isEmpty())
            <p class="muted">Tidak ada catatan kesehatan.</p>
        @else
            <div class="tabel">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal sakit</th>
                            <th>Penyakit</th>
                            <th>Obat</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kesehatans as $kesehatan)
                            <tr>
                                <td data-label="Tanggal sakit" style="white-space: nowrap">{{ $tanggal($kesehatan->tanggal_sakit) }}</td>
                                <td data-label="Penyakit">{{ $kesehatan->nama_penyakit }}</td>
                                <td data-label="Obat">{{ $kesehatan->obat ?: '-' }}</td>
                                <td data-label="Status">
                                    @if ($kesehatan->is_sembuh)
                                        <span class="badge sembuh">Sembuh</span>
                                    @else
                                        <span class="badge belum">Belum sembuh</span>
                                    @endif
                                </td>
                                <td data-label="Keterangan">{{ $kesehatan->keterangan ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <p class="muted" style="text-align: center">
        Data per {{ $diaksesPada->translatedFormat('d F Y H:i') }} · Dari laporan "{{ $arsip->judul }}" ·
        Akses berlaku sampai {{ $arsip->akses_berlaku_sampai->locale('id')->translatedFormat('d F Y') }}
    </p>
@endsection
