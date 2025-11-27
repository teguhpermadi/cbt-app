<?php

namespace App\Livewire;

use App\Enums\DifficultyLevelEnum;
use App\Models\Question;
use Livewire\Component;

class DifficultySelector extends Component
{

    public Question $question;

    public ?DifficultyLevelEnum $selectedDifficulty = null;
    public bool $isLoading = false;

    /**
     * Mount the component with the question.
     */
    public function mount(Question $question): void
    {
        $this->question = $question;
        $this->selectedDifficulty = $question->difficulty_level;
    }

    /**
     * Update the question difficulty when selection changes.
     */
    public function updatedSelectedDifficulty(): void
    {
        if ($this->selectedDifficulty) {
            $this->dispatch('question-loading-start');
            try {
                // Set loading state
                $this->isLoading = true;

                // Simulate processing time untuk demo loading state
                // usleep(1000000); // 1 second delay

                $this->question->difficulty_level = $this->selectedDifficulty;
                $this->question->save();

                $this->dispatch('difficulty-updated', difficulty: $this->selectedDifficulty);
            } catch (\Exception $e) {
                // Reset to original value
                $this->selectedDifficulty = $this->question->difficulty_level;
            } finally {
                // Always remove loading state
                $this->isLoading = false;
                $this->dispatch('question-loading-end');
            }
        }
    }

    /**
     * Get all available difficulty options.
     */
    public function getDifficultyOptions(): array
    {
        return collect(DifficultyLevelEnum::cases())->mapWithKeys(fn(DifficultyLevelEnum $difficulty) => [
            $difficulty->value => $difficulty->getLabel()
        ])->toArray();
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('livewire.difficulty-selector');
    }
}
