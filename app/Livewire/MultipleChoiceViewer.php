<?php

namespace App\Livewire;

use App\Enums\QuestionTypeEnum;
use Livewire\Component;

class MultipleChoiceViewer extends Component
{
    public array $options = [];
    public array $correctAnswers = [];
    public bool $showCorrectAnswers = false;

    public function mount(array|string $options, array|string $correctAnswers = [], bool $showCorrectAnswers = false)
    {
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
    }

    public function isOptionCorrect($optionKey): bool
    {
        if (!$this->showCorrectAnswers || empty($this->correctAnswers)) {
            return false;
        }

        // Check all correct answers
        foreach ($this->correctAnswers as $keyAnswer) {
            if (!$keyAnswer) {
                continue;
            }
            
            // Parse JSON format if it's a string
            if (is_string($keyAnswer)) {
                $decoded = json_decode($keyAnswer, true);
                $keyAnswer = $decoded ?? [];
            }
            
            // Handle single answer format: {"answer":"A"}
            if (isset($keyAnswer['answer'])) {
                $correctValue = $keyAnswer['answer'];
                
                // Handle single correct answer with case-insensitive comparison
                if (is_string($correctValue) && strtolower($optionKey) === strtolower($correctValue)) {
                    return true;
                }
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
        } elseif (is_array($option)) {
            // Array format: ["text" => "Option text", "media_id" => "xyz"]
            return $option['text'] ?? 'Option not found';
        }
        
        return 'Option not found';
    }

    public function getOptionLabel($optionKey): string
    {
        return $optionKey;
    }

    public function getOptionMediaUrl($optionKey): ?string
    {
        $option = $this->options[$optionKey] ?? [];
        
        if (is_array($option) && isset($option['media_id']) && $option['media_id']) {
            try {
                $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($option['media_id']);
                return $media?->getUrl();
            } catch (\Exception $e) {
                return null;
            }
        }
        
        return null;
    }

    public function render()
    {
        return view('livewire.multiple-choice-viewer');
    }
}
