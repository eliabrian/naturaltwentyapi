<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceSegment extends Model
{
     /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'attendance_id',
        'started_at',
        'ended_at',
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
        ];
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
