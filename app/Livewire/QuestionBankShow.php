<?php

namespace App\Livewire;

use App\Models\QuestionBank;
use Livewire\Component;

class QuestionBankShow extends Component
{
    public QuestionBank $questionBank;

    /**
     * Listen for events from child components
     */
    protected $listeners = [
        'refresh-parent' => 'refreshQuestionBank',
        'order-updated' => 'refreshQuestionBank',
        'question-updated' => 'refreshQuestionBank',
        'question-deleted' => 'refreshQuestionBank',
    ];

    public function mount(QuestionBank $question_bank_id)
    {
        $this->questionBank = $question_bank_id;
        $this->questionBank->load(['questions', 'teacher']);
    }

    /**
     * Refresh the question bank data when updates occur
     */
    public function refreshQuestionBank()
    {
        $this->questionBank->refresh();
        $this->questionBank->load(['questions', 'teacher']);
    }

    public function render()
    {
        return view('livewire.question-bank-show')
            ->layout('components.layouts.question-bank', [
                'questionBank' => $this->questionBank,
            ]);
    }
}
