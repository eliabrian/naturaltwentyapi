<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attendance extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function segments(): HasMany
    {
        return $this->hasMany(AttendanceSegment::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'datetime',
        ];
    }

    public function getFirstStartedAtAttribute()
    {
        return $this->segments()
            ->orderBy('started_at', 'asc')
            ->value('started_at');
    }

    public function getTotalDurationAttribute()
    {
        return $this->segments
            ->filter(fn ($segment) => $segment->ended_at)
            ->reduce(function ($carry, $segment) {
                return $carry + $segment->ended_at->diffInSeconds($segment->started_at);
            }, 0);
    }

    public function getFormattedDurationAttribute()
    {
        $seconds = $this->total_duration;

        $hours = abs($seconds / 3600);
        $minutes = abs(($seconds % 3600) / 60);
        $remainingSeconds = abs($seconds % 60);

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $remainingSeconds);
    }

    public function getRunningSegment()
    {
        return $this->segments()->whereNull('ended_at')->latest()->first();
    }
}
