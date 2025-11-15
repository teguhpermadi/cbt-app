<?php

namespace App\Filament\Resources\Subjects\Schemas;

use App\Models\AcademicYear;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SubjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('code')
                    ->required()
                    ->maxLength(255),
                Select::make('grade_id')
                    ->relationship('grade', 'name', fn($query) => $query->whereHas('academicYear', fn($query) => $query->where('is_active', true)))
                    ->required(),
                Hidden::make('academic_year_id')
                    ->default(AcademicYear::where('is_active', true)->first()?->id),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->rows(5),
            ]);
    }
}
