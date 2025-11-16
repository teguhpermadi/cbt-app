<?php

namespace App\Filament\Resources\QuestionBanks\Pages;

use App\Models\QuestionBank;
use App\Filament\Resources\QuestionBanks\QuestionBankResource;
use Filament\Resources\Pages\Page;

class ListQuestionsPage extends Page
{
    protected static string $resource = QuestionBankResource::class;

    protected string $view = 'filament.resources.question-banks.pages.list-questions-page';

    public $questions;

    public function mount($record): void
    {
        $this->questions = QuestionBank::find($record)->questions;
    }

    /**
     * Refresh questions data - can be called from other components
     */
    public function refreshQuestions(): void
    {
        $recordId = $this->questions->first()?->question_bank_id;
        if ($recordId) {
            $this->questions = QuestionBank::find($recordId)->questions()->orderBy('order')->get();
        }
        $this->dispatch('component-refreshed');
    }
}
