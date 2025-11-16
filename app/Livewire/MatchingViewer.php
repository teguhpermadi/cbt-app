<?php

namespace App\Livewire;

use Livewire\Component;

class MatchingViewer extends Component
{
    public array $options = [];
    public array|string $correctAnswers = [];
    public bool $showCorrectAnswers = false;
    public array $leftColumn = [];
    public array $rightColumn = [];
    public array $correctPairs = [];

    public function mount(array|string $options, array|string $correctAnswers = [], bool $showCorrectAnswers = false)
    {
        $this->showCorrectAnswers = $showCorrectAnswers;
        
        // Handle options - can be array or JSON string
        if (is_string($options)) {
            $this->options = json_decode($options, true) ?? [];
        } else {
            $this->options = $options;
        }
        
        // Handle correct answers - support multiple formats
        if (is_string($correctAnswers)) {
            // Handle simple string or JSON string like "{\"pairs\":{\"L1\":\"R1\"}}"
            $decoded = json_decode($correctAnswers, true);
            if ($decoded !== null) {
                // It's a JSON string
                $this->correctAnswers = [$decoded];
            } else {
                // It's a simple string - for matching, this might be unexpected but handle gracefully
                $this->correctAnswers = [['pairs' => []]];
            }
        } elseif (is_array($correctAnswers) && isset($correctAnswers[0]) && is_string($correctAnswers[0]) && str_contains($correctAnswers[0], '{')) {
            // Array of JSON strings: ['{"pairs":{"L1":"R1","L2":"R2"}}']
            $this->correctAnswers = array_map(fn($answer) => json_decode($answer, true) ?? [], $correctAnswers);
        } elseif (is_array($correctAnswers) && (isset($correctAnswers['pairs']))) {
            // Single structured array: ['pairs' => ['L1' => 'R1', 'L2' => 'R2']]
            $this->correctAnswers = [$correctAnswers];
        } else {
            // Array of structured arrays or other format
            $this->correctAnswers = $correctAnswers;
        }
        
        // Ensure correctAnswers is always an array for internal use
        if (!is_array($this->correctAnswers)) {
            $this->correctAnswers = [];
        }
        
        $this->parseOptions();
        $this->parseCorrectAnswers();
    }

    private function parseOptions()
    {
        $this->leftColumn = [];
        $this->rightColumn = [];
        
        // Separate options into left and right columns
        // Left column keys typically start with 'L'
        // Right column keys typically start with 'R'
        foreach ($this->options as $key => $option) {
            if (str_starts_with($key, 'L')) {
                $this->leftColumn[$key] = $option;
            } elseif (str_starts_with($key, 'R')) {
                $this->rightColumn[$key] = $option;
            }
        }
    }

    private function parseCorrectAnswers()
    {
        $this->correctPairs = [];
        
        if (!empty($this->correctAnswers) && isset($this->correctAnswers[0]['pairs'])) {
            $this->correctPairs = $this->correctAnswers[0]['pairs'];
        }
    }

    public function isCorrectPair($leftKey, $rightKey): bool
    {
        if (!$this->showCorrectAnswers || empty($this->correctPairs)) {
            return false;
        }
        
        return isset($this->correctPairs[$leftKey]) && $this->correctPairs[$leftKey] === $rightKey;
    }

    public function getOptionText($key): string
    {
        return $this->options[$key]['text'] ?? '';
    }

    public function getOptionMediaUrl($key): ?string
    {
        if (!isset($this->options[$key]['media_id'])) {
            return null;
        }

        try {
            $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($this->options[$key]['media_id']);
            return $media?->getUrl();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getOptionLabel($key): string
    {
        return $key;
    }

    public function render()
    {
        return view('livewire.matching-viewer');
    }
}
