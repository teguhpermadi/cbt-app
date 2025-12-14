<?php

namespace App\Filament\Resources\QuestionBanks\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class QuestionBankForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id')
                    ->default(auth()->id()),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->required()
                    ->rows(3),
                Select::make('subject_id')
                    ->relationship('subject', 'name')
                    ->required(),
                Toggle::make('is_public')
                    ->required()
                    ->inline(false)
                    ->hint('Jika diaktifkan, bank soal dapat dikoreksi oleh guru lain.'),
            ]);
    }
}
