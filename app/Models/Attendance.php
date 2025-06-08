<?php

namespace App\Models;

use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'started_at',
        'ended_at',
        'duration',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'duration' => 'integer'
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getGroupDateAttribute(): string
    {
        return $this->started_at
            ? $this->started_at->format('l, d F Y')
            : '';
    }
}
