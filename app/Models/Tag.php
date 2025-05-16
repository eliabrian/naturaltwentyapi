<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tag extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'color',
    ];

    /**
     * The games that belongs to the tag.
     *
     * @return BelongToMany
     */
    public function games(): BelongsToMany
    {
        return $this->belongsToMany(related: Game::class)
            ->using(class: GameTag::class);
    }

    /**
     * Return the pivot table of the tag.
     *
     * @return HasMany
     */
    public function gameTags(): HasMany
    {
        return $this->hasMany(related: GameTag::class);
    }
}
