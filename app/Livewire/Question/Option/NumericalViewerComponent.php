<?php

namespace App\Livewire\Question\Option;

use Livewire\Attributes\Locked;
use Livewire\Component;

class NumericalViewerComponent extends Component
{
    #[Locked]
    public $options;

    public function mount($options)
    {
        $this->options = $options;
    }

    public function render()
    {
        return view('livewire.question.option.numerical-viewer-component');
    }
}
