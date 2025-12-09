<?php

namespace App\Livewire\Question\Selector;

use App\Enums\TimerEnum;
use Livewire\Attributes\Locked;
use Livewire\Component;

class TimerSelectorComponent extends Component
{
    #[Locked]
    public $questionId;
    public $timer, $timersEnum;

    public function mount($questionId, $timer)
    {
        // Populate options as array of arrays compatible with Mary UI
        $this->timersEnum = collect(TimerEnum::cases())
            ->map(fn($case) => [
                'id' => $case->value,
                'name' => $case->getLabel()
            ])
            ->all();

        $this->questionId = $questionId;
        $this->timer = $timer;
    }

    public function updatedTimer($value)
    {
        // $value will be the integer value from the enum because we set 'id' => $case->value
        $question = \App\Models\Question::find($this->questionId);
        $question->timer = $value;
        $question->save();

        // Dispatch event to parent component for reactivity
        $this->dispatch('timer-updated', questionId: $this->questionId);
    }

    public function render()
    {
        return view('livewire.question.selector.timer-selector-component');
    }
}
