<?php

namespace App\Livewire\Question;

use App\Models\Question;
use Livewire\Attributes\Locked;
use Livewire\Component;

class QuestionComponent extends Component
{
    #[Locked]
    public Question $question;

    public function mount(Question $question)
    {
        $this->question = $question;
    }

    public function editQuestion()
    {
        dd($this->question->id);
    }

    public function deleteQuestion()
    {
        dd($this->question->id);
    }

    public function render()
    {
        return view('livewire.question.question-component');
    }
}
