<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum UserRole: int implements HasLabel, HasColor
{
    case Admin = 1;
    case User = 2;

    /**
     * Get the label for each role.
     *
     * @return null|string
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Admin => "Admin",
            self::User => "User",
        };
    }

    /**
     * Get the color for each role.
     *
     * @return string|array|null
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Admin => 'info',
            self::User => 'success',
        };
    }
}
