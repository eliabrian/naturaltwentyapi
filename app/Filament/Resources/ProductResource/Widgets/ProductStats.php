<?php

namespace App\Filament\Resources\ProductResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class ProductStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Product', DB::table('products')->count()),
            Stat::make('Low or Critical stock', DB::table('products')->whereColumn('stock', '<=', 'security_stock')->count()),
        ];
    }
}
