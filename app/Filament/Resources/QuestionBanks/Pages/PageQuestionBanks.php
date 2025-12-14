<?php

namespace App\Filament\Resources\QuestionBanks\Pages;

use App\Filament\Resources\QuestionBanks\QuestionBankResource;
use App\Models\QuestionBank;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Livewire\Attributes\On;

class PageQuestionBanks extends Page
{
    protected static string $resource = QuestionBankResource::class;

    protected string $view = 'filament.resources.question-banks.pages.page-question-banks';

    public QuestionBank $record;

    public function mount(QuestionBank $record)
    {
        $this->record = $record->load('questions');
    }

    public function getTitle(): string
    {
        return $this->record->name;
    }

    public function getSubheading(): ?string
    {
        return $this->record->description;
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make('edit_header')
                ->record($this->record)
                ->form([
                    TextInput::make('name')
                        ->label('Nama Question Bank')
                        ->required()
                        ->maxLength(255),
                    Textarea::make('description')
                        ->label('Deskripsi')
                        ->rows(3),
                ])
                ->action(function (array $data, QuestionBank $record) {
                    $record->update($data);

                    Notification::make()
                        ->title('Header updated successfully')
                        ->success()
                        ->send();

                    $this->redirect($this->getResource()::getUrl('page-question-banks', ['record' => $record]));
                })
                ->icon('heroicon-o-pencil')
                ->color('gray')
                ->tooltip('Edit Title & Description'),

            Action::make('create_question')
                ->label('Tambah Soal')
                ->icon('heroicon-o-plus')
                ->url(route('questions.create', ['questionBank' => $this->record->id]))
                ->button(),
        ];
    }

    #[On('refreshQuestionBank')]
    public function refreshQuestionList($questionId = null)
    {
        // Reload questions with correct ordering
        $this->record->load(['questions' => function ($query) {
            $query->orderBy('order', 'asc');
        }]);

        // Dispatch browser event for auto-scroll if questionId is provided
        if ($questionId) {
            $this->js("
                window.dispatchEvent(new CustomEvent('page-refreshed', { 
                    detail: { questionId: '{$questionId}' } 
                }))
            ");
        }
    }
}
