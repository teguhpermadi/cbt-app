<?php

namespace App\Livewire\Question\Form\Option;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Question;
use App\Models\Option;

class EssayEditorComponent extends Component
{
    public $questionId;
    public $referenceAnswer;

    public function mount($questionId)
    {
        $this->questionId = $questionId;
        $this->loadData();
    }

    public function loadData()
    {
        $question = Question::findOrFail($this->questionId);
        $option = $question->options()->first();

        if ($option) {
            $this->referenceAnswer = $option->content;
        } else {
            $this->referenceAnswer = '';
        }
    }

    public function rules()
    {
        return [
            'referenceAnswer' => 'nullable|string',
        ];
    }

    #[On('save-options')]
    public function saveOptions()
    {
        $this->validate();

        $question = Question::findOrFail($this->questionId);

        // Find existing or create new option for Essay
        $option = $question->options()->first();

        $dataToSave = [
            'question_id' => $this->questionId,
            'option_key' => 'ESSAY', // Standard key for essay
            'content' => $this->referenceAnswer,
            'is_correct' => true, // Always true since it's the reference
            'order' => 1,
            'metadata' => [
                'type' => 'essay_reference',
                'rubric' => $this->referenceAnswer // Duplicate for compatibility if needed
            ]
        ];

        if ($option) {
            $option->update($dataToSave);
        } else {
            Option::create($dataToSave);
        }

        $this->dispatch('options-saved');
    }

    public function render()
    {
        return view('livewire.question.form.option.essay-editor-component');
    }
}
