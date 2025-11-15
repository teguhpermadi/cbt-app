<?php

namespace App\Filament\Resources\AcademicYears\Schemas;

use App\Enums\SemesterEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AcademicYearForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('year')
                    ->required()
                    ->mask('9999/9999')
                    ->placeholder('2024/2025'),
                Select::make('semester')
                    ->required()
                    ->options(SemesterEnum::class),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
