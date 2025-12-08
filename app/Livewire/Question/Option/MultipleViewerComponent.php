<?php

namespace App\Livewire\Question\Option;

use Livewire\Attributes\Locked;
use Livewire\Component;

class MultipleViewerComponent extends Component
{
    #[Locked]
    public $questionId;

    public function mount($questionId)
    {
        $this->questionId = $questionId;
    }

    public function render()
    {
        $question = \App\Models\Question::with('options')->find($this->questionId);

        return view('livewire.question.option.multiple-viewer-component', [
            'options' => $question->options
        ]);
    }
}
