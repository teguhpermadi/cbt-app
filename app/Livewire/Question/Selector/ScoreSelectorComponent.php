<?php

namespace App\Livewire\Question\Selector;

use App\Enums\QuestionScoreEnum;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ScoreSelectorComponent extends Component
{
    #[Locked]
    public $questionId;
    public $score, $scoresEnum;

    public function mount($questionId)
    {
        // Populate options as array of arrays compatible with Mary UI
        $this->scoresEnum = collect(QuestionScoreEnum::cases())
            ->map(fn($case) => [
                'id' => $case->value,
                'name' => $case->value
            ])
            ->all();

        $this->questionId = $questionId;

        // Load question and get score value
        $question = \App\Models\Question::find($questionId);
        $this->score = $question->score_value;
    }

    public function updatedScore($value)
    {
        // $value will be the integer value from the enum because we set 'id' => $case->value
        $question = \App\Models\Question::find($this->questionId);
        $question->score_value = $value;
        $question->save();
    }

    public function render()
    {
        return view('livewire.question.selector.score-selector-component');
    }
}
