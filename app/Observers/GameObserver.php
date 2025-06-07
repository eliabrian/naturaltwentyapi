<?php

namespace App\Observers;

use App\Events\GameAvailabilityChanged;
use App\Models\Game;

class GameObserver
{
    /**
     * Handle the Game "created" event.
     */
    public function created(Game $game): void
    {
        // Broadcast initial availability state
        GameAvailabilityChanged::dispatch($game);
    }

    /**
     * Handle the Game "updated" event.
     */
    public function updated(Game $game): void
    {
        // Check if is_available was changed
        if ($game->wasChanged('is_available')) {
            GameAvailabilityChanged::dispatch($game);
        }
    }

    /**
     * Handle the Game "deleted" event.
     */
    public function deleted(Game $game): void
    {
        //
    }

    /**
     * Handle the Game "restored" event.
     */
    public function restored(Game $game): void
    {
        //
    }

    /**
     * Handle the Game "force deleted" event.
     */
    public function forceDeleted(Game $game): void
    {
        //
    }
}
