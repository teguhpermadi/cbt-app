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

    public function mount(array|string $options, QuestionTypeEnum|string $questionType, array|string $correctAnswers = [], bool $showCorrectAnswers = false)
    {
        $this->questionType = is_string($questionType) ? QuestionTypeEnum::from($questionType) : $questionType;
        $this->showCorrectAnswers = $showCorrectAnswers;
        
        // Handle options - can be array or JSON string
        if (is_string($options)) {
            $this->options = json_decode($options, true) ?? [];
        } else {
            $this->options = $options;
        }
        
        // Handle correct answers - can be array, JSON string, or structured array
        if (is_string($correctAnswers)) {
            $this->correctAnswers = [json_decode($correctAnswers, true) ?? []];
        } elseif (is_array($correctAnswers) && isset($correctAnswers[0]) && is_string($correctAnswers[0])) {
            // Array of JSON strings: ['{"answer":"A"}']
            $this->correctAnswers = array_map(fn($answer) => json_decode($answer, true) ?? [], $correctAnswers);
        } elseif (is_array($correctAnswers) && (isset($correctAnswers['answer']) || isset($correctAnswers['answers']))) {
            // Single structured array: ['answer' => 'A']
            $this->correctAnswers = [$correctAnswers];
        } else {
            // Array of structured arrays or other format
            $this->correctAnswers = $correctAnswers;
        }
        
        // Set default options for true/false questions if empty
        if ($this->questionType === QuestionTypeEnum::TrueFalse && empty($this->options)) {
            $this->options = [
                'True' => ['text' => 'Benar'],
                'False' => ['text' => 'Salah']
            ];
        }
    }

    public function isOptionCorrect($optionKey): bool
    {
        if (!$this->showCorrectAnswers) {
            return false;
        }

        if (empty($this->correctAnswers)) {
            return false;
        }

        // Get the first correct answer
        $keyAnswer = $this->correctAnswers[0] ?? null;
        
        if (!$keyAnswer) {
            return false;
        }
        
        // Parse JSON format if it's a string
        if (is_string($keyAnswer)) {
            $decoded = json_decode($keyAnswer, true);
            $keyAnswer = $decoded ?? [];
        }
        
        // Handle different answer formats
        $correctValue = null;
        
        if (isset($keyAnswer['answer'])) {
            // Single answer format: {"answer":"A"} or {"answer":"True"}
            $correctValue = $keyAnswer['answer'];
        } elseif (isset($keyAnswer['answers'])) {
            // Multiple answers format: {"answers":["A","B"]}
            $correctValue = $keyAnswer['answers'];
        }
        
        if (!$correctValue) {
            return false;
        }
        
        // Handle multiple correct answers
        if (is_array($correctValue)) {
            return in_array($optionKey, $correctValue);
        }
        
        // Handle single correct answer with case-insensitive comparison
        return strtolower($optionKey) === strtolower($correctValue);
    }

    public function getOptionText($optionKey): string
    {
        $option = $this->options[$optionKey] ?? [];
        
        // Handle different option formats
        if (is_string($option)) {
            // Simple string format: "Benar"
            return $option;
        } elseif (is_array($option) && isset($option['text'])) {
            // Array format: {"text":"Benar","media_id":null}
            return $option['text'];
        }
        
        // Fallback to option key
        return $optionKey;
    }

    public function getOptionMedia($key): ?string
    {
        $option = $this->options[$key] ?? null;
        
        if (!$option || !is_array($option)) {
            return null;
        }

        return $option['media_id'] ?? null;
    }

    public function getOptionMediaUrl($key): ?string
    {
        $mediaId = $this->getOptionMedia($key);
        
        if (!$mediaId) {
            return null;
        }
        
        // Try to get media URL using Spatie Media Library
        try {
            // This would require access to the Question model instance
            // For now, return a placeholder URL pattern
            return "/storage/media/{$mediaId}";
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getOptionLabel($optionKey): string
    {
        return match ($this->questionType) {
            QuestionTypeEnum::TrueFalse => match ($optionKey) {
                'True' => 'Benar',
                'False' => 'Salah',
                'true', 'benar' => 'Benar',
                'false', 'salah' => 'Salah',
                default => ucfirst($optionKey),
            },
            default => $optionKey,
        };
    }

    public function render()
    {
        return view('livewire.multiple-options-viewer');
    }
}
