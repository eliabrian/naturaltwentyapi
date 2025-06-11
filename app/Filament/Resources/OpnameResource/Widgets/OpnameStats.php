<?php

namespace App\Filament\Resources\OpnameResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class OpnameStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Opname', DB::table('opnames')->count()),
            Stat::make('Draft', DB::table('opnames')->where('status', '=', 'draft')->count()),
            Stat::make('Awaiting Review', DB::table('opnames')->where('status', '=', 'awaiting_review')->count()),
            Stat::make('Under Review', DB::table('opnames')->where('status', '=', 'under_review')->count()),
        ];
    }
}
