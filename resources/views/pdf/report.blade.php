@extends('pdf.layouts.report')

@section('title', $result->report->title())

@section('content')
    <div class="judul">{{ $result->report->title() }}</div>

    <table class="keterangan">
        @forelse ($result->filterDescriptions as $label => $value)
            <tr>
                <td>{{ $label }}</td>
                <td>: {{ $value }}</td>
            </tr>
        @empty
            <tr>
                <td>Filter</td>
                <td>: Semua data</td>
            </tr>
        @endforelse
        <tr>
            <td>Jumlah data</td>
            <td>: {{ number_format($result->rowCount(), 0, ',', '.') }}</td>
        </tr>
    </table>

    @if (! empty($aksesBerlakuSampai))
        <div class="catatan-link">
            Klik nama santri untuk melihat detail. Link berlaku sampai {{ $aksesBerlakuSampai }}{{ ! empty($aksesDenganPin) ? ' dan dilindungi PIN' : '' }}.
        </div>
    @endif

    @include('pdf.partials.table', ['columns' => $result->columns, 'rows' => $result->rows])

    @if ($result->summary !== [])
        <div class="ringkasan">
            <h4>Ringkasan</h4>
            @foreach ($result->summary as $group => $items)
                <div class="ringkasan-grup">
                    <table>
                        <tr>
                            <td class="judul-grup" colspan="2">{{ $group }}</td>
                        </tr>
                        @foreach ($items as $label => $value)
                            <tr>
                                <td>{{ $label }}</td>
                                <td class="text-right">{{ is_int($value) ? number_format($value, 0, ',', '.') : $value }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endforeach
        </div>
    @endif

    @foreach ($result->tables as $table)
        <div class="tabel-tambahan">
            <h4>{{ $table['title'] }}</h4>
            @include('pdf.partials.table', ['columns' => $table['columns'], 'rows' => $table['rows']])
        </div>
    @endforeach
@endsection
