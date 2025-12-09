<?php

namespace App\Livewire\Question\Selector;

use App\Models\Question;
use Livewire\Attributes\Locked;
use Livewire\Component;

class OrderSelectorComponent extends Component
{
    #[Locked]
    public $questionId;

    #[Locked]
    public $questionBankId;

    public $order;
    public $orderOptions;

    public function mount($questionId, $order)
    {
        $this->questionId = $questionId;
        $this->order = $order;

        // Get the question to find its question bank
        $question = Question::find($questionId);
        $this->questionBankId = $question->question_bank_id;

        // Get total questions in this question bank
        $totalQuestions = Question::where('question_bank_id', $this->questionBankId)->count();

        // Populate order options (1 to total questions)
        $this->orderOptions = collect(range(1, $totalQuestions))
            ->map(fn($number) => [
                'id' => $number,
                'name' => (string) $number
            ])
            ->all();
    }

    public function updatedOrder($value)
    {
        $question = Question::find($this->questionId);
        $oldOrder = $question->order;
        $newOrder = (int) $value;

        // If order hasn't changed, do nothing
        if ($oldOrder === $newOrder) {
            return;
        }

        // Smart reordering: shift other questions to maintain sequential ordering
        if ($newOrder > $oldOrder) {
            // Moving down: shift questions between old and new position up
            Question::where('question_bank_id', $this->questionBankId)
                ->where('id', '!=', $this->questionId)
                ->whereBetween('order', [$oldOrder + 1, $newOrder])
                ->decrement('order');
        } else {
            // Moving up: shift questions between new and old position down
            Question::where('question_bank_id', $this->questionBankId)
                ->where('id', '!=', $this->questionId)
                ->whereBetween('order', [$newOrder, $oldOrder - 1])
                ->increment('order');
        }

        // Update the question's order
        $question->order = $newOrder;
        $question->save();

        // Dispatch event to parent component for reactivity
        $this->dispatch('order-updated', questionId: $this->questionId);
        $this->dispatch('refreshQuestionBank', questionId: $this->questionId);
    }

    public function render()
    {
        return view('livewire.question.selector.order-selector-component');
    }
}
