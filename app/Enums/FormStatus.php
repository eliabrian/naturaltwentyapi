<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum FormStatus: string implements HasColor, HasLabel
{
    case Draft = 'draft';

    case Awaiting_Review = 'awaiting_review';

    case Approved = 'approved';

    case Done = 'done';

    /**
     * Get the label for each form.
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Awaiting_Review => 'Awaiting Review',
            self::Approved => 'Approved',
            self::Done => 'Done',
        };
    }

    /**
     * Get the color for each form.
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Awaiting_Review => 'warning',
            self::Approved => 'info',
            self::Done => 'success',
        };
    }
}
