<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum GameDifficulty: string implements HasColor, HasLabel
{
    case Kids = 'kids';

    case Easy = 'easy';

    case Medium = 'medium';

    case Hard = 'hard';

    case Expert = 'expert';

    /**
     * Get the label for each difficultyy.
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Kids => 'Kids',
            self::Easy => 'Easy',
            self::Medium => 'Medium',
            self::Hard => 'Hard',
            self::Expert => 'Expert',
        };
    }

    /**
     * Get the color for each difficulty.
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Kids => 'info',
            self::Easy => 'success',
            self::Medium => 'gray',
            self::Hard => 'warning',
            self::Expert => 'danger',
        };
    }
}
