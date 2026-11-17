{{-- Tabel data generik. Kunci "_class" pada baris dipakai sebagai class <tr> (mis. "sorot sp2"). --}}
<table class="data">
    <thead>
        <tr>
            <th style="width: 4%">No</th>
            @foreach ($columns as $column)
                <th @isset($column['width']) style="width: {{ $column['width'] }}" @endisset>{{ $column['label'] }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse ($rows as $row)
            <tr @if (! empty($row['_class'])) class="{{ $row['_class'] }}" @endif>
                <td class="text-center">{{ $loop->iteration }}</td>
                @foreach ($columns as $key => $column)
                    <td class="text-{{ $column['align'] ?? 'left' }}">{{ $row[$key] ?? '-' }}</td>
                @endforeach
            </tr>
        @empty
            <tr>
                <td class="kosong" colspan="{{ count($columns) + 1 }}">Tidak ada data.</td>
            </tr>
        @endforelse
    </tbody>
</table>
