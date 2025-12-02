<?php

namespace App\Livewire\Question;

use App\Models\QuestionBank;
use Livewire\Component;

class QuestionBankComponent extends Component
{
    public $questionBank;

    public function mount(QuestionBank $questionBank)
    {
        $this->questionBank = $questionBank->load('questions');
    }

    public function render()
    {
        return view('livewire.question.question-bank-component');
    }
}
