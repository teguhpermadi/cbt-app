<?php

namespace App\Livewire;

use App\Enums\QuestionScoreEnum;
use App\Models\Question;
use Livewire\Component;

class QuestionScoreSelector extends Component
{

    public Question $question;

    public ?QuestionScoreEnum $selectedScore = null;
    public bool $isLoading = false;

    /**
     * Mount the component with the question.
     */
    public function mount(Question $question): void
    {
        $this->question = $question;
        // Convert score_value to QuestionScoreEnum if exists
        if ($this->question->score_value) {
            $this->selectedScore = QuestionScoreEnum::tryFrom($this->question->score_value);
        }
    }

    /**
     * Update the question score when selection changes.
     */
    public function updatedSelectedScore(): void
    {
        if ($this->selectedScore) {
            $this->dispatch('question-loading-start');
            try {
                // Set loading state
                $this->isLoading = true;

                // Simulate processing time untuk demo loading state
                // usleep(1000000); // 1 second delay

                $this->question->score_value = $this->selectedScore->getScore();
                $this->question->save();

                $this->dispatch('score-updated', score: $this->selectedScore);
            } catch (\Exception $e) {
                // Reset to original value
                $this->selectedScore = QuestionScoreEnum::tryFrom($this->question->score_value);
            } finally {
                // Always remove loading state
                $this->isLoading = false;
                $this->dispatch('question-loading-end');
            }
        }
    }

    /**
     * Get all available score options.
     */
    public function getScoreOptions(): array
    {
        return collect(QuestionScoreEnum::cases())->mapWithKeys(fn(QuestionScoreEnum $score) => [
            $score->value => $score->getLabel()
        ])->toArray();
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('livewire.question-score-selector');
    }
}
