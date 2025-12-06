<?php

namespace App\Livewire\Question\Selector;

use App\Enums\QuestionScoreEnum;
use Livewire\Component;

class ScoreSelectorComponent extends Component
{
    public $question, $score, $scoresEnum;

    public function mount($question)
    {
        // Populate options as array of arrays compatible with Mary UI
        $this->scoresEnum = collect(QuestionScoreEnum::cases())
            ->map(fn($case) => [
                'id' => $case->value,
                'name' => $case->value
            ])
            ->all();

        $this->question = $question;

        // Ensure we get the value whether it is an Enum instance or raw value
        $this->score = $question->score_value;
    }

    public function updatedScore($value)
    {
        // $value will be the integer value from the enum because we set 'id' => $case->value
        $this->question->score_value = $value;
        $this->question->save();
    }

    public function render()
    {
        return view('livewire.question.selector.score-selector-component');
    }
}
