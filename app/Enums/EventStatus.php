<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum EventStatus: string implements HasLabel, HasColor, HasIcon
{
    case New = 'new';

    case Progress = 'progress';

    case Done = 'done';

    case Cancelled = 'cancelled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::New => 'New',
            self::Progress => 'In Progress',
            self::Done => 'Done',
            self::Cancelled => 'Cancelled',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::New => 'info',
            self::Progress => 'warning',
            self::Done => 'success',
            self::Cancelled => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::New => 'heroicon-m-sparkles',
            self::Progress => 'heroicon-m-arrow-path',
            self::Done => 'heroicon-m-check-badge',
            self::Cancelled => 'heroicon-m-x-circle',
        };
    }
}
