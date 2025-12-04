<?php

namespace App\Livewire\Question\Option;

use Livewire\Component;

class MatchingViewerComponent extends Component
{
    public $question, $leftOptions, $rightOptions;

    public function mount($question)
    {
        dump($question->options);
        $this->question = $question;
        $this->leftOptions = $question->options->filter(fn($o) => \Illuminate\Support\Str::startsWith($o->option_key, 'L'))->sortBy('order');
        $this->rightOptions = $question->options->filter(fn($o) => \Illuminate\Support\Str::startsWith($o->option_key, 'R'))->sortBy('order');
    }

    public function render()
    {
        return view('livewire.question.option.matching-viewer-component');
    }
}
