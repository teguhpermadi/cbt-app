<?php

namespace App\Filament\Resources\QuestionBanks\Tables;

use App\Filament\Resources\QuestionBanks\QuestionBankResource;
use App\Models\QuestionBank;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class QuestionBanksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->searchable()
                    ->limit(50)
                    ->wrap(),
                ToggleColumn::make('is_public')
                    ->sortable(),
                TextColumn::make('questions_count')
                    ->label('Questions')
                    ->counts('questions')
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('questions')
                    ->label('Questions')
                    ->url(fn(QuestionBank $record): string => route('question-banks.show', $record)),
                // page questions
                Action::make('page questions')
                    ->label('Page Questions')
                    ->url(fn(QuestionBank $record): string => QuestionBankResource::getUrl('page-question-banks', ['record' => $record])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(function ($query) {
                $query->subjectActive();
            });
    }
}
