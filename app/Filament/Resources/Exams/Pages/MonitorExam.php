<?php

namespace App\Filament\Resources\Exams\Pages;

use App\Filament\Resources\Exams\ExamResource;
use App\Models\Exam;
use App\Models\ExamSession;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Support\Enums\FontWeight;
use Illuminate\View\View;

class MonitorExam extends Page implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    protected static string $resource = ExamResource::class;

    protected static ?string $title = 'Monitor Ujian';

    protected string $view = 'filament.resources.exams.pages.monitor-exam';

    public $record;

    public function mount(int | string $record): void
    {
        $this->record = Exam::findOrFail($record);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ExamSession::query()
                    ->where('exam_id', $this->record->id)
                    ->with(['user'])
            )
            ->columns([
                TextColumn::make('user.name')
                    ->label('Siswa')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold),

                TextColumn::make('start_time')
                    ->label('Waktu Mulai')
                    ->dateTime()
                    ->sortable(),

                // TextColumn::make('finish_time')
                //     ->label('Waktu Selesai')
                //     ->dateTime()
                //     ->sortable(),

                TextColumn::make('duration_taken')
                    ->label('Durasi (Menit)')
                    ->suffix(' menit')
                    ->sortable(),

                TextColumn::make('total_score')
                    ->label('Nilai')
                    ->sortable()
                    ->color(fn(ExamSession $record) => $record->total_score >= $this->record->passing_score ? 'success' : 'danger'),

                IconColumn::make('is_finished')
                    ->label('Selesai')
                    ->boolean(),
            ])
            ->recordActions([
                Action::make('details')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->url(fn(ExamSession $record) => route('exams.monitor-session.detail', ['record' => $record->id])),
            ])
            ->poll('10s')
            ->headerActions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->url(fn() => ExamResource::getUrl('edit', ['record' => $this->record->id])),
            ]); // Auto refresh every 10 seconds to monitor live
    }

    // public function render(): View
    // {
    //     return view('filament.resources.exams.pages.monitor-exam');
    // }
}
