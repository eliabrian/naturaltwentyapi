<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use PharIo\Manifest\Author;

class AttendanceWidget extends Widget
{
    protected static string $view = 'filament.widgets.attendance-widget';

    public ?Attendance $attendance = null;

    public bool $isRunning = false;

    protected static bool $isLazy = false;

    public function mount(): void
    {
        $this->attendance = Attendance::whereNull('ended_at')
            ->where('user_id', Auth::id())
            ->whereToday('started_at')
            ->latest()
            ->first();

        if ($this->attendance) {
            $this->isRunning = true;
        }
    }

    public function start(): void
    {
        if (! $this->attendance) {
            $this->attendance = Attendance::create([
                'user_id' => Auth::id(),
                'started_at' => now(),
            ]);
        }

        $this->isRunning = true;
    }

    public function stop(): void
    {
        if ($this->attendance) {
            $diffInSeconds = abs(
                round(now()->diffInSeconds($this->attendance->started_at), 0, PHP_ROUND_HALF_UP)
            );
            $this->attendance->update([
                'ended_at' => now(),
                'duration' => $diffInSeconds,
            ]);
        }
        $this->isRunning = false;
        $this->attendance = null;
    }
}
