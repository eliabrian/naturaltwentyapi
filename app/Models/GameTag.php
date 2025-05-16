<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class GameTag extends Pivot
{
    public function tag(): BelongsTo
    {
        return $this->belongsTo(related: Tag::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(related: Game::class);
    }
}
