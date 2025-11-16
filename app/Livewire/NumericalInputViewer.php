<?php

namespace App\Livewire;

use App\Services\LatexMathService;
use Livewire\Component;

class NumericalInputViewer extends Component
{
    public array|string $correctAnswers = [];
    public $showCorrectAnswers = false;

    public function mount(array|string $options = [], array|string $correctAnswers = [], bool $showCorrectAnswers = false)
    {
        $this->showCorrectAnswers = $showCorrectAnswers;
        
        // Handle different formats of correctAnswers
        if (is_string($correctAnswers)) {
            // Handle simple string like "42" or JSON string like "{\"answer\":\"42\"}"
            $decoded = json_decode($correctAnswers, true);
            if ($decoded !== null) {
                // It's a JSON string
                $this->correctAnswers = [$decoded];
            } else {
                // It's a simple string like "42"
                $this->correctAnswers = [['answer' => $correctAnswers]];
            }
        } elseif (is_array($correctAnswers) && isset($correctAnswers[0]) && is_string($correctAnswers[0])) {
            $this->correctAnswers = array_map(fn($answer) => json_decode($answer, true) ?? [], $correctAnswers);
        } elseif (is_array($correctAnswers) && (isset($correctAnswers['answer']) || isset($correctAnswers['answers']))) {
            $this->correctAnswers = [$correctAnswers];
        } else {
            $this->correctAnswers = $correctAnswers;
        }
        
        // Ensure correctAnswers is always an array for internal use
        if (!is_array($this->correctAnswers)) {
            $this->correctAnswers = [];
        }
    }

    public function getCorrectAnswer()
    {
        if (empty($this->correctAnswers)) {
            return null;
        }
        
        // Get the first correct answer
        $firstAnswer = $this->correctAnswers[0];
        
        // Handle different answer formats
        if (is_array($firstAnswer)) {
            return $firstAnswer['answer'] ?? $firstAnswer['answers'] ?? null;
        }
        
        return $firstAnswer;
    }

    public function isValidLatex(): bool
    {
        $correctAnswer = $this->getCorrectAnswer();
        
        if (empty($correctAnswer)) {
            return false;
        }

        $validation = LatexMathService::validateLatexExpression($correctAnswer);
        return $validation['valid'];
    }

    public function getDisplayFormat(): string
    {
        $correctAnswer = $this->getCorrectAnswer();
        
        if (empty($correctAnswer)) {
            return '';
        }

        if ($this->isValidLatex()) {
            $validation = LatexMathService::validateLatexExpression($correctAnswer);
            return $validation['normalized'] ?? $correctAnswer;
        }

        return $correctAnswer;
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
