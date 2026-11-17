<?php

namespace App\Filament\Support;

use Filament\Actions\Action;
use Filament\Notifications\Notification;

/**
 * Notifikasi untuk admin terkait link preview santri di PDF.
 */
class AksesLinkNotification
{
    /**
     * PIN hanya ditampilkan sekali (yang disimpan hanya hash-nya), jadi notifikasi dibuat persisten.
     */
    public static function pin(string $pin, ?string $urlPdf = null): void
    {
        Notification::make()
            ->title("PIN akses PDF: {$pin}")
            ->body('Catat PIN ini sekarang, karena tidak akan ditampilkan lagi. Bagikan PIN terpisah dari PDF, jangan di pesan atau grup yang sama.')
            ->warning()
            ->persistent()
            ->actions($urlPdf ? [
                Action::make('bukaPdf')
                    ->label('Buka PDF')
                    ->url($urlPdf)
                    ->openUrlInNewTab(),
            ] : [])
            ->send();
    }

    public static function pdfSiap(string $urlPdf): void
    {
        Notification::make()
            ->title('PDF berhasil dibuat')
            ->success()
            ->actions([
                Action::make('bukaPdf')
                    ->label('Buka PDF')
                    ->url($urlPdf)
                    ->openUrlInNewTab(),
            ])
            ->send();
    }
}
