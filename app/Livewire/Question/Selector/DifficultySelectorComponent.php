<?php

namespace App\Livewire\Question\Selector;

use App\Enums\DifficultyLevelEnum;
use Livewire\Component;

class DifficultySelectorComponent extends Component
{
    public $question, $difficultyLevel, $levelsEnum;

    public function mount($question)
    {
        // Populate options as array of arrays compatible with Mary UI (default expects 'id' and 'name')
        $this->levelsEnum = collect(DifficultyLevelEnum::cases())
            ->map(fn($case) => [
                'id' => $case->value,
                'name' => $case->getLabel()
            ])
            ->all();

        $this->question = $question;

        // Ensure we get the value whether it is an Enum instance or raw value (safety check)
        $this->difficultyLevel = $question->difficulty_level;
    }

    public function updatedDifficultyLevel($value)
    {
        $this->question->difficulty_level = $value;
        $this->question->save();
    }

    public function render()
    {
        return view('livewire.question.selector.difficulty-selector-component');
    }
}
