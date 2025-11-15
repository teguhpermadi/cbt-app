<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum DifficultyLevelEnum: string implements HasLabel
{
    case Easy = 'mudah';
    case Medium = 'sedang';
    case Hard = 'sulit';

    public function getLabel(): string
    {
        return match ($this) {
            self::Easy => 'Mudah',
            self::Medium => 'Sedang',
            self::Hard => 'Sulit',
        };
    }
}