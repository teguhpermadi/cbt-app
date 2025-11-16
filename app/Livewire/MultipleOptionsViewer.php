<?php

namespace App\Livewire;

use App\Enums\QuestionTypeEnum;
use Livewire\Component;

class MultipleOptionsViewer extends Component
{
    public array $options = [];
    public QuestionTypeEnum|string $questionType;
    public array|string $correctAnswers = [];
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
            // Handle simple string like "A" or JSON string like "{\"answer\":\"A\"}"
            $decoded = json_decode($correctAnswers, true);
            if ($decoded !== null) {
                // It's a JSON string
                $this->correctAnswers = [$decoded];
            } else {
                // It's a simple string like "A"
                $this->correctAnswers = [['answer' => $correctAnswers]];
            }
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
        
        // Ensure correctAnswers is always an array for internal use
        if (!is_array($this->correctAnswers)) {
            $this->correctAnswers = [];
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

        // Check all correct answers for multiple selection compatibility
        foreach ($this->correctAnswers as $keyAnswer) {
            if (!$keyAnswer) {
                continue;
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
                continue;
            }
            
            // Handle multiple correct answers
            if (is_array($correctValue)) {
                if (in_array($optionKey, $correctValue)) {
                    return true;
                }
            }
            
            // Handle single correct answer with case-insensitive comparison
            if (is_string($correctValue) && strtolower($optionKey) === strtolower($correctValue)) {
                return true;
            }
        }
        
        return false;
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
