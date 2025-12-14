<?php

namespace App\Filament\Resources\Exams\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ExamsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('subject.name')
                    ->label('Subject')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('grade.name')
                    ->label('Grade')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('exam_type')
                    ->badge()
                    ->sortable(),

                // TextColumn::make('total_questions')
                //     ->numeric()
                //     ->sortable()
                //     ->alignCenter()
                //     ->label('Questions'),

                // TextColumn::make('duration')
                //     ->numeric()
                //     ->suffix(' min')
                //     ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->sortable(false),

                TextColumn::make('start_time')
                    ->date()
                    ->sortable(),

                TextColumn::make('end_time')
                    ->date()
                    ->sortable(),

                // IconColumn::make('is_published')
                //     ->boolean()
                //     ->label('Published')
                //     ->sortable(),

                // IconColumn::make('is_randomized')
                //     ->boolean()
                //     ->label('Randomized')
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),

                \Filament\Tables\Filters\SelectFilter::make('exam_type')
                    ->options(\App\Enums\ExamTypeEnum::class),

                \Filament\Tables\Filters\SelectFilter::make('subject_id')
                    ->relationship('subject', 'name')
                    ->searchable()
                    ->preload(),

                \Filament\Tables\Filters\SelectFilter::make('grade_id')
                    ->relationship('grade', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
