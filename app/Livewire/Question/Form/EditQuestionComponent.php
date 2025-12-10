<?php

namespace App\Livewire\Question\Form;

use App\Enums\QuestionTypeEnum;
use App\Models\Question;
use Livewire\Component;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class EditQuestionComponent extends Component
{
    public Question $question;
    public $questionType;
    public $content;
    public $options = []; // Initialize as array

    public function mount(Question $question)
    {
        $this->question = $question;
        $this->questionType = $question->question_type->value;
        $this->content = $question->content;

        // Load existing options into array
        $this->options = $question->options->map(function ($option) {
            return [
                'id' => $option->id,
                'option_key' => $option->option_key,
                'content' => $option->content,
                'is_correct' => $option->is_correct,
                'media_path' => $option->media_path,
                'order' => $option->order,
                'metadata' => $option->metadata,
            ];
        })->toArray();
    }

    public function rules()
    {
        return [
            'content' => 'required|string',
            'questionType' => 'required|string',
            'options' => 'array', // Validation for options
            'options.*.content' => 'required|string',
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

            $this->syncOptions();
        });

        // Send notification
        Notification::make()
            ->title('Soal berhasil diperbarui')
            ->success()
            ->send();

        return redirect()->route('question-banks.show', $this->question->question_bank_id);
    }

    protected function syncOptions()
    {
        // 1. Get existing IDs from DB
        $existingIds = $this->question->options()->pluck('id')->toArray();

        // 2. Identify submitted IDs
        $submittedIds = array_column(array_filter($this->options, fn($opt) => isset($opt['id'])), 'id');

        // 3. Delete options that are in DB but missing from submission
        $idsToDelete = array_diff($existingIds, $submittedIds);
        if (!empty($idsToDelete)) {
            $this->question->options()->whereIn('id', $idsToDelete)->delete();
        }

        // 4. Update or Create
        foreach ($this->options as $index => $optionData) {
            $dataToSave = [
                'question_id' => $this->question->id,
                'option_key' => $optionData['option_key'] ?? chr(65 + $index), // Fallback key if missing
                'content' => $optionData['content'],
                'is_correct' => $optionData['is_correct'] ?? false,
                'order' => $index, // Ensure order matches array index
                // 'metadata' => $optionData['metadata'] ?? null, // Uncomment if metadata needed
            ];

            if (isset($optionData['id'])) {
                // Update existing
                $this->question->options()->where('id', $optionData['id'])->update($dataToSave);
            } else {
                // Create new
                $this->question->options()->create($dataToSave);
            }
        }
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
