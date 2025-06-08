<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum UserType: string implements HasColor, HasLabel
{
    case Owner = 'owner';
    case Part_Time = 'part_time';
    case Full_Time = 'full_time';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Owner => 'Owner',
            self::Part_Time => 'Part Time',
            self::Full_Time => 'Full Time',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Owner => 'info',
            self::Part_Time => 'primary',
            self::Full_Time => 'success',
        };
    }
}
