<?php

namespace App\Livewire\Question\Option;

use Livewire\Component;

class MultipleViewerComponent extends Component
{
    public $options;
    public $key_answer;

    public function mount($options, $key_answer)
    {
        if (is_string($options)) {
            $this->options = json_decode($options, true) ?? [];
        } else {
            $this->options = $options ?? [];
        }
        $this->key_answer = $key_answer;
    }

    public function render()
    {
        $parsedOptions = is_string($this->options) ? json_decode($this->options, true) : $this->options;
        $parsedOptions = $parsedOptions ?? [];

        $parsedKeyAnswer = is_string($this->key_answer) ? json_decode($this->key_answer, true) : $this->key_answer;

        $correctKeys = [];
        if (is_array($parsedKeyAnswer)) {
            // Check if 'answer' key exists (common structure)
            if (isset($parsedKeyAnswer['answer'])) {
                $answer = $parsedKeyAnswer['answer'];
                $correctKeys = is_array($answer) ? $answer : [$answer];
            } else {
                // Fallback: assume the array itself contains keys if it's a simple list
                $correctKeys = $parsedKeyAnswer;
            }
        } elseif (is_string($parsedKeyAnswer)) {
            $correctKeys = [$parsedKeyAnswer];
        }

        return view('livewire.question.option.multiple-viewer-component', [
            'parsedOptions' => $parsedOptions,
            'correctKeys' => $correctKeys
        ]);
    }
}
