<?php

namespace App\Livewire;

use App\Enums\QuestionTypeEnum;
use Livewire\Component;

class EssayAnswerViewer extends Component
{
    public array $rubric = [];
    public QuestionTypeEnum $questionType;
    public bool $showCorrectAnswers = false;

    public function mount(array|string $keyAnswer, QuestionTypeEnum|string $questionType, bool $showCorrectAnswers = false)
    {
        $this->questionType = is_string($questionType) ? QuestionTypeEnum::from($questionType) : $questionType;
        $this->showCorrectAnswers = $showCorrectAnswers;
        
        // Handle key_answer - can be array or JSON string
        if (is_string($keyAnswer)) {
            $this->rubric = json_decode($keyAnswer, true) ?? [];
        } else {
            $this->rubric = $keyAnswer;
        }
    }

    public function render()
    {
        return view('livewire.essay-answer-viewer');
    }

    public function getRubricPoints(): array
    {
        return $this->rubric ?? [];
    }

    public function getTotalMaxScore(): int
    {
        return array_sum(array_column($this->rubric, 'max_score'));
    }
}
