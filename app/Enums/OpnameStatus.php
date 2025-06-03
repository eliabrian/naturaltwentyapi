<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum OpnameStatus: string implements HasColor, HasLabel
{
    case Draft = 'draft';

    case Awating_Review = 'awaiting_review';

    case Under_Review = 'under_review';

    case Approved = 'approved';

    /**
     * Get the label for each difficultyy.
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Awating_Review => 'Awaiting Review',
            self::Under_Review => 'Under Review',
            self::Approved => 'Approved',
        };
    }

    /**
     * Get the color for each difficulty.
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Awating_Review => 'info',
            self::Under_Review => 'warning',
            self::Approved => 'success',
        };
    }
}
