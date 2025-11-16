<?php

namespace App\Livewire;

use App\Services\LatexMathService;
use Livewire\Component;

class NumericalInputViewer extends Component
{
    public $correctAnswer;
    public $showCorrectAnswers = false;

    public function mount($correctAnswer = null, $showCorrectAnswers = false)
    {
        $this->correctAnswer = $correctAnswer;
        $this->showCorrectAnswers = $showCorrectAnswers;
    }

    public function isValidLatex(): bool
    {
        if (empty($this->correctAnswer)) {
            return false;
        }

        $validation = LatexMathService::validateLatexExpression($this->correctAnswer);
        return $validation['valid'];
    }

    public function getDisplayFormat(): string
    {
        if (empty($this->correctAnswer)) {
            return '';
        }

        if ($this->isValidLatex()) {
            $validation = LatexMathService::validateLatexExpression($this->correctAnswer);
            return $validation['normalized'] ?? $this->correctAnswer;
        }

        return $this->correctAnswer;
    }

    public function shouldUseMathJax(): bool
    {
        return $this->isValidLatex();
    }

    public function render()
    {
        return view('livewire.numerical-input-viewer');
    }
}
