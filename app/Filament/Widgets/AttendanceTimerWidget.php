<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Carbon\Carbon;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class AttendanceTimerWidget extends Widget
{
    protected static string $view = 'filament.widgets.attendance-timer-widget';

    public ?Attendance $attendance = null;

    public ?Carbon $runningSince = null;

    public bool $isRunning = false;

    protected static bool $isLazy = false;

    public function mount(): void
    {
        $this->attendance = Attendance::firstOrCreate([
            'user_id' => Auth::id(),
            'date' => today(),
        ]);

        $running = $this->attendance->getRunningSegment();

        if ($running) {
            $this->runningSince = $running->started_at;
            $this->isRunning = true;
        }
    }

    public function startOrResume()
    {
        if (! $this->attendance) {
            $this->attendance = Attendance::firstOrCreate([
                'user_id' => Auth::id(),
                'date' => today(),
            ]);
        }

        $segment = $this->attendance->segments()->create([
            'started_at' => now(),
        ]);

        $this->runningSince = $segment->started_at;
        $this->isRunning = true;
    }

    public function pause()
    {
        $segment = $this->attendance->getRunningSegment();
        if ($segment) {
            $segment->update(['ended_at' => now()]);
        }

        $this->isRunning = false;
        $this->runningSince = null;
    }

    public function stop()
    {
        $this->pause();
    }

    protected function getViewData(): array
    {
        return [
            'isRunning' => $this->isRunning,
            'startedAt' => $this->runningSince,
            'duration' => $this->attendance?->formatted_duration ?? '00:00:00',
        ];
    }
}
