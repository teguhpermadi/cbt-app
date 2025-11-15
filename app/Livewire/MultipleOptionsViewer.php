<?php

namespace App\Livewire;

use App\Enums\QuestionTypeEnum;
use Livewire\Component;

class MultipleOptionsViewer extends Component
{
    public array $options = [];
    public QuestionTypeEnum|string $questionType;
    public array $correctAnswers = [];
    public bool $showCorrectAnswers = false;

    public function mount(array $options, QuestionTypeEnum|string $questionType, array $correctAnswers = [], bool $showCorrectAnswers = false)
    {
        $this->options = $options;
        $this->questionType = is_string($questionType) ? QuestionTypeEnum::from($questionType) : $questionType;
        $this->correctAnswers = $correctAnswers;
        $this->showCorrectAnswers = $showCorrectAnswers;
    }

    public function isOptionCorrect($optionKey): bool
    {
        if (!$this->showCorrectAnswers) {
            return false;
        }

        return in_array($optionKey, $this->correctAnswers);
    }

    public function getOptionText($key): string
    {
        $option = $this->options[$key] ?? null;
        
        if (!$option) {
            return '';
        }

        if (is_array($option)) {
            return $option['text'] ?? '';
        }

        return $option;
    }

    public function getOptionMedia($key): ?string
    {
        $option = $this->options[$key] ?? null;
        
        if (!$option || !is_array($option)) {
            return null;
        }

        return $option['media_id'] ?? null;
    }

    public function getOptionLabel($optionKey): string
    {
        return match ($this->questionType) {
            QuestionTypeEnum::TrueFalse => $optionKey === 'true' ? 'Benar' : 'Salah',
            default => $optionKey,
        };
    }

    public function render()
    {
        return view('livewire.multiple-options-viewer');
    }
}
