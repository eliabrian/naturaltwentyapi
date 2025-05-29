<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum UserRole: int implements HasLabel, HasColor
{
    case Admin = 1;
    case Chef = 2;
    case Cashier = 3;
    case GameMaster = 4;
    case DungeonMaster = 5;

    /**
     * Get the label for each role.
     *
     * @return null|string
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Admin => "Admin",
            self::Chef => "Chef",
            self::Cashier => "Cashier",
            self::GameMaster => "Game Master",
            self::DungeonMaster => "Dungeon Master",
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
            self::Chef => 'success',
            self::Cashier => 'primary',
            self::GameMaster => 'gray',
            self::DungeonMaster => 'danger',
        };
    }
}
