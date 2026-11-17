<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <style>
        @page {
            margin: 28px 28px 44px 28px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            color: #222;
            margin: 0;
        }

        .lembaga {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding-bottom: 6px;
            border-bottom: 2px solid #222;
        }

        .judul {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 12px 0 8px;
        }

        .keterangan {
            margin-bottom: 10px;
        }

        .keterangan td {
            padding: 1px 6px 1px 0;
            vertical-align: top;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
        }

        table.data thead {
            display: table-header-group;
        }

        table.data tr {
            page-break-inside: avoid;
        }

        table.data th,
        table.data td {
            border: 1px solid #666;
            padding: 3px 4px;
            vertical-align: top;
        }

        table.data th {
            background: #e5e5e5;
            font-weight: bold;
            text-align: center;
        }

        table.data tbody tr:nth-child(even) td {
            background: #f7f7f7;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }

        .kosong {
            text-align: center;
            padding: 12px;
            color: #666;
        }

        .ringkasan {
            margin-top: 14px;
            page-break-inside: avoid;
        }

        .ringkasan h4 {
            font-size: 10px;
            margin: 0 0 4px;
        }

        .ringkasan-grup {
            display: inline-block;
            vertical-align: top;
            margin-right: 24px;
        }

        .ringkasan-grup table {
            border-collapse: collapse;
        }

        .ringkasan-grup td {
            padding: 1px 8px 1px 0;
        }

        .ringkasan-grup .judul-grup {
            font-weight: bold;
            border-bottom: 1px solid #999;
        }

        /* Baris yang perlu perhatian (status SP, belum sembuh) */
        table.data tbody tr.sorot td {
            background: #fdecc8;
            font-weight: bold;
        }

        table.data tbody tr.sorot.sp2 td {
            background: #fbd3a5;
        }

        table.data tbody tr.sorot.sp3 td {
            background: #f8b4b4;
        }

        /* Link preview santri: tampil seperti teks biasa agar rapi saat dicetak di kertas */
        a.link-santri {
            color: inherit;
            text-decoration: none;
        }

        .catatan-link {
            margin-bottom: 6px;
            font-style: italic;
            color: #555;
        }

        .tabel-tambahan {
            margin-top: 16px;
        }

        .tabel-tambahan h4 {
            font-size: 10px;
            margin: 0 0 4px;
        }

        @yield('styles')
    </style>
</head>
<body>
    <div class="lembaga">{{ $namaLembaga }}</div>

    @yield('content')
</body>
</html>
