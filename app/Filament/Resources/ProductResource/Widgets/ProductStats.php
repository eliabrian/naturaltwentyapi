<?php

namespace App\Filament\Resources\ProductResource\Widgets;

use App\Enums\ProductLocation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class ProductStats extends BaseWidget
{
    protected function getStats(): array
    {
        $productQuery = DB::table('products');

        $totalProduct = $productQuery->count();
        $lowStockProduct = $productQuery->whereColumn('stock', '<=', 'security_stock')->count();

        return [
            Stat::make('Total Product', $totalProduct),
            Stat::make('Low or Critical stock', $lowStockProduct),
        ];
    }
}
