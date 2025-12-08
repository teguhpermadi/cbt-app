<?php

namespace App\Livewire\Question\Option;

use Livewire\Attributes\Locked;
use Livewire\Component;

class OrderingViewerComponent extends Component
{
    #[Locked]
    public $questionId;
    public $options;

    public function mount($questionId)
    {
        $this->questionId = $questionId;
        $question = \App\Models\Question::with('options')->find($questionId);
        $this->options = $question->options;
    }

    public function render()
    {
        return view('livewire.question.option.ordering-viewer-component');
    }
}
