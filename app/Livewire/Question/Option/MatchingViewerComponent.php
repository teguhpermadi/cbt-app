<?php

namespace App\Livewire\Question\Option;

use Livewire\Attributes\Locked;
use Livewire\Component;

class MatchingViewerComponent extends Component
{
    #[Locked]
    public $questionId;

    public $leftOptions, $rightOptions;
    public $pairs = [];

    public function mount($questionId)
    {
        $this->questionId = $questionId;

        $question = \App\Models\Question::with('options')->find($questionId);

        $this->leftOptions = $question->options->filter(fn($o) => \Illuminate\Support\Str::startsWith($o->option_key, 'L'));
        $this->rightOptions = $question->options->filter(fn($o) => \Illuminate\Support\Str::startsWith($o->option_key, 'R'));

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
