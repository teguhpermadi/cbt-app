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

    public function mount($questionId, $difficultyLevel)
    {
        // Populate options as array of arrays compatible with Mary UI (default expects 'id' and 'name')
        $this->levelsEnum = collect(DifficultyLevelEnum::cases())
            ->map(fn($case) => [
                'id' => $case->value,
                'name' => $case->getLabel()
            ])
            ->all();

        $this->questionId = $questionId;
        $this->difficultyLevel = $difficultyLevel;
    }

    public function updatedDifficultyLevel($value)
    {
        $question = \App\Models\Question::find($this->questionId);
        $question->difficulty_level = $value;
        $question->save();

        // Dispatch event to parent component for reactivity
        $this->dispatch('difficulty-updated', questionId: $this->questionId);
    }

    public function render()
    {
        return view('livewire.question.selector.difficulty-selector-component');
    }
}
