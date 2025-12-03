<?php

namespace App\Livewire\Question\Option;

use Livewire\Component;

class MultipleViewerComponent extends Component
{
    public $question;

    public function mount($question)
    {
        $this->question = $question;
    }

    public function render()
    {
        // Pastikan options di-load
        if (!$this->question->relationLoaded('options')) {
            $this->question->load('options');
        }

        return view('livewire.question.option.multiple-viewer-component', [
            'options' => $this->question->options
        ]);
    }
}
