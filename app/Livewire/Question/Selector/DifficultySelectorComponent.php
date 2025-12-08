<?php

namespace App\Livewire\Question\Selector;

use App\Enums\DifficultyLevelEnum;
use Livewire\Attributes\Locked;
use Livewire\Component;

class DifficultySelectorComponent extends Component
{
    #[Locked]
    public $questionId;
    public $difficultyLevel, $levelsEnum;

    public function mount($questionId)
    {
        // Populate options as array of arrays compatible with Mary UI (default expects 'id' and 'name')
        $this->levelsEnum = collect(DifficultyLevelEnum::cases())
            ->map(fn($case) => [
                'id' => $case->value,
                'name' => $case->getLabel()
            ])
            ->all();

        $this->questionId = $questionId;

        // Load question and get difficulty level
        $question = \App\Models\Question::find($questionId);
        $this->difficultyLevel = $question->difficulty_level;
    }

    public function updatedDifficultyLevel($value)
    {
        $question = \App\Models\Question::find($this->questionId);
        $question->difficulty_level = $value;
        $question->save();
    }

    public function render()
    {
        return view('livewire.question.selector.difficulty-selector-component');
    }
}
