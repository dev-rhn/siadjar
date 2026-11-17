@extends('preview.layout')

@section('title', 'Masukkan PIN')

@section('content')
    <div class="card" style="text-align: center">
        <h1>Masukkan PIN</h1>
        <p class="muted">Data santri dilindungi PIN. PIN diberikan oleh admin pondok bersama laporan.</p>

        @if ($terkunci)
            <p class="error">Terlalu banyak percobaan salah. Coba lagi dalam {{ $tungguMenit }} menit.</p>
        @else
            <form class="pin" method="POST">
                @csrf
                <input type="password" name="pin" inputmode="numeric" pattern="[0-9]*" maxlength="6" autocomplete="off" autofocus required aria-label="PIN">
                @error('pin')
                    <div class="error">{{ $message }}</div>
                @enderror
                <button type="submit">Buka</button>
            </form>
        @endif
    </div>
@endsection
