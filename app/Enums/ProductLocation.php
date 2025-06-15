<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ProductLocation: string implements HasLabel
{
    case Storage = 'storage';
    case Kitchen = 'kitchen';
    case Bar = 'bar';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Storage => 'Storage',
            self::Kitchen => 'Kitchen',
            self::Bar => 'Bar',
        };
    }
}
