<?php

namespace App\Livewire\Question;

use App\Models\QuestionBank as QuestionBankModel;
use Livewire\Component;

class QuestionBank extends Component
{
    public $questionBank;

    public function mount(QuestionBankModel $questionBank)
    {
        $this->questionBank = $questionBank->load('questions');
    }

    public function render()
    {
        return view('livewire.question.question-bank');
    }
}
