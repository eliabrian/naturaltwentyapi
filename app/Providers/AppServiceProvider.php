<?php

namespace App\Providers;

use App\Models\Game;
use App\Observers\GameObserver;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentAsset::register([
            Js::make('app-js', Vite::asset('resources/js/app.js')),
        ]);
        Game::observe(GameObserver::class);
    }
}
