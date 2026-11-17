<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="referrer" content="no-referrer">
    <title>@yield('title') — {{ $namaLembaga }}</title>
    <style>
        :root {
            --bg: #f4f4f5;
            --card: #ffffff;
            --text: #18181b;
            --muted: #71717a;
            --border: #e4e4e7;
            --sorot: #fdecc8;
            --sp2: #fbd3a5;
            --sp3: #f8b4b4;
            --hijau: #15803d;
            --merah: #b91c1c;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg: #09090b;
                --card: #18181b;
                --text: #f4f4f5;
                --muted: #a1a1aa;
                --border: #27272a;
                --sorot: #4a3a14;
                --sp2: #5a3412;
                --sp3: #5f1d1d;
                --hijau: #4ade80;
                --merah: #f87171;
            }
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            font-size: 15px;
            line-height: 1.5;
        }

        .wrap {
            max-width: 860px;
            margin: 0 auto;
            padding: 16px;
        }

        header.lembaga {
            text-align: center;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 12px 0;
            border-bottom: 2px solid var(--text);
            margin-bottom: 16px;
            font-size: 14px;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 16px;
        }

        h1 { font-size: 20px; margin: 0 0 4px; }
        h2 { font-size: 16px; margin: 0 0 12px; }

        .muted { color: var(--muted); font-size: 13px; }

        .profil {
            display: flex;
            gap: 16px;
            align-items: flex-start;
        }

        .profil img, .profil .foto-kosong {
            width: 96px;
            height: 128px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid var(--border);
            flex-shrink: 0;
        }

        .foto-kosong {
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            font-size: 12px;
        }

        dl.biodata {
            display: grid;
            grid-template-columns: max-content 1fr;
            gap: 4px 12px;
            margin: 8px 0 0;
        }

        dl.biodata dt { color: var(--muted); }
        dl.biodata dd { margin: 0; }

        .stat {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 12px;
        }

        .stat div {
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px 12px;
            min-width: 140px;
        }

        .stat strong { display: block; font-size: 18px; }

        .badge {
            display: inline-block;
            padding: 1px 8px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid var(--border);
        }

        .badge.sp1 { background: var(--sorot); }
        .badge.sp2 { background: var(--sp2); }
        .badge.sp3 { background: var(--sp3); }
        .badge.sembuh { color: var(--hijau); }
        .badge.belum { color: var(--merah); }

        .tabel { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th, td {
            text-align: left;
            padding: 6px 8px;
            border-bottom: 1px solid var(--border);
            vertical-align: top;
        }

        th { color: var(--muted); font-weight: 600; white-space: nowrap; }
        tr.periode td { background: var(--sorot); }
        .kanan { text-align: right; }

        form.pin {
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-width: 280px;
            margin: 0 auto;
        }

        input[type=password] {
            font-size: 22px;
            letter-spacing: 6px;
            text-align: center;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: var(--bg);
            color: var(--text);
        }

        button {
            font-size: 15px;
            padding: 10px;
            border-radius: 8px;
            border: 0;
            background: var(--text);
            color: var(--bg);
            font-weight: 600;
            cursor: pointer;
        }

        .error { color: var(--merah); font-size: 14px; }

        @media (max-width: 520px) {
            .profil { flex-direction: column; align-items: center; text-align: center; }
            dl.biodata { text-align: left; }

            /* Tabel riwayat jadi kartu per baris agar tidak perlu scroll horizontal */
            .tabel thead { display: none; }
            .tabel table, .tabel tbody, .tabel tr, .tabel td { display: block; width: 100%; }
            .tabel tr { border-bottom: 1px solid var(--border); padding: 8px; }
            .tabel td { border: 0; padding: 2px 0; display: flex; gap: 12px; white-space: normal !important; }
            .tabel td::before { content: attr(data-label); color: var(--muted); min-width: 96px; flex-shrink: 0; }
            .tabel td.kanan { text-align: left; }
        }
    </style>
</head>
<body>
    <div class="wrap">
        <header class="lembaga">{{ $namaLembaga }}</header>
        @yield('content')
    </div>
</body>
</html>
