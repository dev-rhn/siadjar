<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\QuickAccessWidget;
use Filament\Actions\Action;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static string $view = 'filament.pages.dashboard';

    public function getWidgets(): array
    {
        return [
            QuickAccessWidget::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view-site')
                ->label('View Site')
                ->url(config('app.url'))
                ->openUrlInNewTab()
                ->color('primary'),
        ];
    }
}


