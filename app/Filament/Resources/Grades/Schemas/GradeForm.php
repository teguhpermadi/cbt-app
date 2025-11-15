<?php

namespace App\Filament\Resources\Grades\Schemas;

use App\Models\AcademicYear;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GradeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->placeholder('Nama Kelas'),
                TextInput::make('level')
                    ->required()
                    ->placeholder('Level')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(12),
                Hidden::make('academic_year_id')
                    ->default(AcademicYear::active()->first()->id)
                    ->required(),
            ]);
    }
}
