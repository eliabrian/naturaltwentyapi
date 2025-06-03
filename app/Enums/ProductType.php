<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ProductType: string implements HasColor, HasLabel
{
    case Food = 'food';
    case Drink = 'drink';

    /**
     * Get the label for each difficultyy.
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Food => 'Food',
            self::Drink => 'Drink',
        };
    }

    /**
     * Get the color for each difficulty.
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Food => 'info',
            self::Drink => 'primary',
        };
    }
}
