<?php

namespace App\Livewire\Question\Option;

use Livewire\Component;

class MatchingViewerComponent extends Component
{
    public $question, $leftOptions, $rightOptions;
    public $pairs = [];

    public function mount($question)
    {
        $this->question = $question;
        $this->leftOptions = $question->options->filter(fn($o) => \Illuminate\Support\Str::startsWith($o->option_key, 'L'))->shuffle();
        $this->rightOptions = $question->options->filter(fn($o) => \Illuminate\Support\Str::startsWith($o->option_key, 'R'))->shuffle();

        // Build pairs mapping (Left ID -> Right ID)
        $rightMap = $this->rightOptions->pluck('id', 'option_key');

        foreach ($this->leftOptions as $left) {
            $targetKey = $left->getMetadata('match_with');
            if (isset($rightMap[$targetKey])) {
                $this->pairs[] = [
                    'left' => $left->id,
                    'right' => $rightMap[$targetKey]
                ];
            }
        }
    }

    public function render()
    {
        return view('livewire.question.option.matching-viewer-component');
    }
}
