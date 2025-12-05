<?php

namespace App\Livewire\Question\Option;

use Livewire\Component;

class OrderingViewerComponent extends Component
{
    public $question, $options;
    public function mount($question)
    {
        $this->question = $question;
        $this->options = $question->options;
    }

    public function render()
    {
        return view('livewire.question.option.ordering-viewer-component');
    }
}
