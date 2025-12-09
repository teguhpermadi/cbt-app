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

    public function mount($questionId, $scoreValue)
    {
        // Populate options as array of arrays compatible with Mary UI
        $this->scoresEnum = collect(QuestionScoreEnum::cases())
            ->map(fn($case) => [
                'id' => $case->value,
                'name' => $case->value
            ])
            ->all();

        $this->questionId = $questionId;
        $this->score = $scoreValue;
    }

    public function updatedScore($value)
    {
        // $value will be the integer value from the enum because we set 'id' => $case->value
        $question = \App\Models\Question::find($this->questionId);
        $question->score_value = $value;
        $question->save();

        // Dispatch event to parent component for reactivity
        $this->dispatch('score-updated', questionId: $this->questionId);
    }

    public function render()
    {
        return view('livewire.question.selector.score-selector-component');
    }
}
