<?php

namespace App\Models;

use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'room_id',
        'name',
        'slug',
        'image_path',
        'is_published',
        'status',
        'event_date',
        'description',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'event_date' => 'datetime',
            'status' => EventStatus::class,
        ];
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
