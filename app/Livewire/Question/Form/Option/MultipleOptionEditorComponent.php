<?php

namespace App\Livewire\Question\Form\Option;

use Livewire\Component;
use \Livewire\Attributes\Modelable;

class MultipleOptionEditorComponent extends Component
{
    #[Modelable]
    public $options = [];

    // Helper to generate next key (A, B, C...)
    protected function getNextKey($index)
    {
        return chr(65 + $index);
    }

    public function addOption()
    {
        $this->options[] = [
            'option_key' => $this->getNextKey(count($this->options)),
            'content' => '',
            'is_correct' => false,
            'id' => null, // Mark as new
        ];
    }

    public function removeOption($index)
    {
        unset($this->options[$index]);
        $this->options = array_values($this->options); // Re-index array
        $this->reindexKeys(); // Maintain A, B, C sequence
    }

    public function setCorrectAnswer($index)
    {
        foreach ($this->options as $key => &$option) {
            $option['is_correct'] = ($key === $index);
        }
    }

    protected function reindexKeys()
    {
        foreach ($this->options as $index => &$option) {
            $option['option_key'] = $this->getNextKey($index);
        }
    }

    public function render()
    {
        return view('livewire.question.form.option.multiple-option-editor-component');
    }
}
