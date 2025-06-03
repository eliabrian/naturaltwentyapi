<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum OpnameShift: string implements HasLabel
{
    case Shift_1 = 'Shift 1';

    case Shift_2 = 'Shift 2';

    /**
     * Get the label for each difficultyy.
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Shift_1 => 'Shift 1',
            self::Shift_2 => 'Shift 2',
        };
    }
}
