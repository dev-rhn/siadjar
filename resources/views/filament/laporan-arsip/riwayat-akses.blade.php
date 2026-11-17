<div>
    @if ($logs->isEmpty())
        <p style="color: var(--gray-500)">Link di PDF ini belum pernah dibuka.</p>
    @else
        <p style="margin-bottom: 8px; font-size: 0.875rem; color: var(--gray-500)">
            Total dibuka {{ $total }}x{{ $total > $logs->count() ? ', menampilkan '.$logs->count().' terakhir' : '' }}.
        </p>
        <div style="max-height: 60vh; overflow-y: auto">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem">
                <thead>
                    <tr style="text-align: left">
                        <th style="padding: 6px 8px; border-bottom: 1px solid var(--gray-200)">Waktu</th>
                        <th style="padding: 6px 8px; border-bottom: 1px solid var(--gray-200)">Santri</th>
                        <th style="padding: 6px 8px; border-bottom: 1px solid var(--gray-200)">IP</th>
                        <th style="padding: 6px 8px; border-bottom: 1px solid var(--gray-200)">Perangkat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $log)
                        <tr>
                            <td style="padding: 6px 8px; border-bottom: 1px solid var(--gray-100); white-space: nowrap">{{ $log->created_at->format('d M Y H:i') }}</td>
                            <td style="padding: 6px 8px; border-bottom: 1px solid var(--gray-100)">{{ $log->dataSantri?->nama ?? '(santri dihapus)' }}</td>
                            <td style="padding: 6px 8px; border-bottom: 1px solid var(--gray-100)">{{ $log->ip ?? '-' }}</td>
                            <td style="padding: 6px 8px; border-bottom: 1px solid var(--gray-100)" title="{{ $log->user_agent }}">{{ \Illuminate\Support\Str::limit($log->user_agent ?? '-', 60) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
