<?php

namespace App\Livewire\Question\Selector;

use App\Enums\TimerEnum;
use Livewire\Component;

class TimerSelectorComponent extends Component
{
    public $question, $timer, $timersEnum;

    public function mount($question)
    {
        // Populate options as array of arrays compatible with Mary UI
        $this->timersEnum = collect(TimerEnum::cases())
            ->map(fn($case) => [
                'id' => $case->value,
                'name' => $case->getLabel()
            ])
            ->all();

        $this->question = $question;

        // Ensure we get the value whether it is an Enum instance or raw value
        $this->timer = $question->timer;
    }

    public function updatedTimer($value)
    {
        // $value will be the integer value from the enum because we set 'id' => $case->value
        $this->question->timer = $value;
        $this->question->save();
    }

    public function render()
    {
        return view('livewire.question.selector.timer-selector-component');
    }
}
