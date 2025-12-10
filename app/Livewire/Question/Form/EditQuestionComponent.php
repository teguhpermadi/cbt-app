<?php

namespace App\Livewire\Question\Form;

use App\Enums\QuestionTypeEnum;
use App\Models\Question;
use Livewire\Component;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Attributes\On;

class EditQuestionComponent extends Component
{
    use \Livewire\WithFileUploads;

    public Question $question;
    public $questionType;
    public $content;

    public $questionImage; // Temporary upload
    public $existingQuestionImageUrl;

    public function mount(Question $question)
    {
        $this->question = $question;
        $this->questionType = $question->question_type->value;
        $this->content = $question->content;
        $this->existingQuestionImageUrl = $question->getFirstMediaUrl('question_content');
    }

    public function rules()
    {
        return [
            'content' => 'required|string',
            'questionType' => 'required|string',
            'questionImage' => 'nullable|image|max:2048', // 2MB Max
        ];
    }

    public function update()
    {
        $this->validate();

        DB::transaction(function () {
            $this->question->update([
                'content' => $this->content,
                'question_type' => QuestionTypeEnum::from($this->questionType),
            ]);

            // Handle Question Image
            if ($this->questionImage) {
                $this->question->clearMediaCollection('question_content');
                $this->question->addMedia($this->questionImage)->toMediaCollection('question_content');
            }
        });

        // Trigger Child Component to Save Options
        $this->dispatch('save-options');
    }

    #[On('options-saved')]
    public function onOptionsSaved()
    {
        return redirect()->route('question-banks.show', $this->question->question_bank_id);
    }

    public function cancel()
    {
        return redirect()->route('question-banks.show', $this->question->question_bank_id);
    }

    public function render()
    {
        $questionTypes = collect(QuestionTypeEnum::cases())
            ->map(fn($case) => [
                'id' => $case->value,
                'name' => $case->getLabel()
            ])
            ->all();

        return view('livewire.question.form.edit-question-component', [
            'questionTypes' => $questionTypes,
        ]);
    }
}
