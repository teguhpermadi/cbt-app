<?php

namespace App\Livewire;

use App\Models\QuestionBank;
use Livewire\Component;

class QuestionBankSidebar extends Component
{
    public $questionBankId;
    public $questions = [];

    protected $listeners = [
        'refresh-parent' => 'loadQuestions',
        'order-updated' => 'loadQuestions',
        'question-updated' => 'loadQuestions',
        'question-deleted' => 'loadQuestions',
    ];

    public function mount($questionBankId)
    {
        $this->questionBankId = $questionBankId;
        $this->loadQuestions();
    }

    public function loadQuestions()
    {
        $questionBank = QuestionBank::with('questions')->find($this->questionBankId);

        if ($questionBank) {
            $this->questions = $questionBank->questions()
                ->orderBy('order', 'asc')
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.question-bank-sidebar');
    }
}
