<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\LatexMathService;

class NumericalInputViewer extends Component
{
    public $correctAnswer = '';
    public $showCorrectAnswers = false;
    public $validationResult = null;
    public $displayFormat = '';

    public function mount($correctAnswers = '', $showCorrectAnswers = false)
    {
        $this->correctAnswer = $correctAnswers;
        $this->showCorrectAnswers = $showCorrectAnswers;
        
        // Validate and normalize the correct answer
        if (!empty($this->correctAnswer)) {
            $this->validationResult = LatexMathService::validateLatexExpression($this->correctAnswer);
            if ($this->validationResult['valid']) {
                $this->displayFormat = LatexMathService::latexToDisplay($this->validationResult['normalized']);
            }
        }
    }

    /**
     * Get the formatted correct answer
     */
    public function getFormattedAnswer(): string
    {
        if (!$this->showCorrectAnswers || empty($this->correctAnswer)) {
            return '';
        }

        if ($this->validationResult && $this->validationResult['valid']) {
            return $this->validationResult['normalized'];
        }

        return $this->correctAnswer;
    }

    /**
     * Get display format for rendering
     */
    public function getDisplayFormat(): string
    {
        return $this->displayFormat ?: $this->getFormattedAnswer();
    }

    /**
     * Check if answer is valid LaTeX
     */
    public function isValidLatex(): bool
    {
        return $this->validationResult && $this->validationResult['valid'];
    }

    /**
     * Get validation error message
     */
    public function getValidationError(): string
    {
        return $this->validationResult['error'] ?? '';
    }

    /**
     * Get validation suggestions
     */
    public function getValidationSuggestions(): array
    {
        return $this->validationResult['suggestions'] ?? [];
    }

    /**
     * Get answer type (numeric or latex)
     */
    public function getAnswerType(): string
    {
        if (empty($this->correctAnswer)) {
            return 'empty';
        }

        if ($this->isValidLatex()) {
            return 'latex';
        }

        // Check if it's a simple number
        if (is_numeric($this->correctAnswer)) {
            return 'numeric';
        }

        return 'text';
    }

    /**
     * Get answer description
     */
    public function getAnswerDescription(): string
    {
        $type = $this->getAnswerType();
        
        switch ($type) {
            case 'latex':
                return 'Ekspresi Matematika LaTeX';
            case 'numeric':
                return 'Angka';
            case 'text':
                return 'Teks';
            case 'empty':
                return 'Tidak ada jawaban';
            default:
                return 'Tidak diketahui';
        }
    }

    /**
     * Check if answer should be rendered with MathJax
     */
    public function shouldUseMathJax(): bool
    {
        return $this->getAnswerType() === 'latex';
    }

    /**
     * Get MathJax configuration
     */
    public function getMathJaxConfig(): array
    {
        return [
            'tex' => [
                'inlineMath' => [['$', '$'], ['\\(', '\\)']],
                'displayMath' => [['$$', '$$'], ['\\[', '\\]']],
                'processEscapes' => true,
                'processEnvironments' => true
            ],
            'options' => [
                'ignoreHtmlClass' => 'tex2jax_ignore',
                'processHtmlClass' => 'tex2jax_process'
            ]
        ];
    }

    public function render()
    {
        return view('livewire.numerical-input-viewer');
    }
}
