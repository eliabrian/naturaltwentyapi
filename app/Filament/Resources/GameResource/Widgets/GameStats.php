<?php

namespace App\Filament\Resources\GameResource\Widgets;

use App\Filament\Resources\GameResource\Pages\ListGames;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GameStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListGames::class;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Games', $this->getPageTableRecords()->count() ?? '0'),
        ];
    }
}
