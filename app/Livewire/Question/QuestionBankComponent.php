<?php

namespace App\Livewire\Question;

use App\Models\QuestionBank;
use Livewire\Attributes\On;
use Livewire\Component;

class QuestionBankComponent extends Component
{
    public $questionBank;

    public function mount(QuestionBank $questionBank)
    {
        $this->questionBank = $questionBank->load('questions');
    }

    #[On('refreshQuestionBank')]
    public function refresh($questionId = null)
    {
        // Reload questions with correct ordering
        $this->questionBank->load(['questions' => function ($query) {
            $query->orderBy('order', 'asc');
        }]);

        // Dispatch browser event for auto-scroll if questionId is provided
        if ($questionId) {
            // Dispatch custom event yang akan ditangkap oleh JavaScript
            $this->js("
                window.dispatchEvent(new CustomEvent('page-refreshed', { 
                    detail: { questionId: '{$questionId}' } 
                }))
            ");
        }
    }

    public function render()
    {
        return view('livewire.question.question-bank-component');
    }
}
