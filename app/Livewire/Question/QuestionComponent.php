<?php

namespace App\Livewire\Question;

use App\Models\Question;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class QuestionComponent extends Component
{
    #[Locked]
    public Question $question;

    public function mount(Question $question)
    {
        $this->question = $question->load('options', 'questionBank');
    }

    // Event listeners for updates from selector components
    #[On('difficulty-updated')]
    public function onDifficultyUpdated($questionId)
    {
        if ($this->question->id === $questionId) {
            // $this->question->refresh();
        }
    }

    #[On('timer-updated')]
    public function onTimerUpdated($questionId)
    {
        if ($this->question->id === $questionId) {
            // $this->question->refresh();
        }
    }

    #[On('score-updated')]
    public function onScoreUpdated($questionId)
    {
        if ($this->question->id === $questionId) {
            // $this->question->refresh();
        }
    }

    #[On('order-updated')]
    public function onOrderUpdated($questionId)
    {
        if ($this->question->id === $questionId) {
            // $this->question->refresh();
        }
    }

    public function editQuestion()
    {
        return redirect()->route('questions.edit', $this->question->id);
    }

    public function deleteQuestion()
    {
        // delete question
        $this->question->delete();
        $this->dispatch('refreshQuestionBank');
    }

    public function render()
    {
        return view('livewire.question.question-component');
    }
}
