<?php

namespace App\Models;

use App\Enums\GameDifficulty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'image_path',
        'difficulty',
        'age',
        'player_min',
        'player_max',
        'duration',
        'description'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'difficulty' => GameDifficulty::class,
        ];
    }

    /**
     * The tags that belongs to the game.
     *
     * @return BelongToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(related: Tag::class)
            ->using(class: GameTag::class);
    }

    /**
     * Return the pivot table of the game.
     *
     * @return HasMany
     */
    public function gameTags(): HasMany
    {
        return $this->hasMany(related: GameTag::class);
    }
}
