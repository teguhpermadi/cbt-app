<?php

namespace App\Livewire\Question;

use Livewire\Component;

class Question extends Component
{
    public $question;
    
    public function mount($question)
    {
        $this->question = $question;
    }
    public function render()
    {
        return view('livewire.question.question');
    }
}
