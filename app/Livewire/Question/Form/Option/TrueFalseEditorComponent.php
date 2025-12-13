<?php

namespace App\Livewire\Question\Form\Option;

use Livewire\Component;
use Livewire\Attributes\Modelable;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Facades\Log;
use App\Models\Question;
use App\Models\Option;

class TrueFalseEditorComponent extends Component
{
    use WithFileUploads;

    public $options = [];
    public $questionId;

    public function mount($questionId)
    {
        $this->questionId = $questionId;
        $this->loadOptions();
    }

    public function loadOptions()
    {
        $question = Question::find($this->questionId);

        $this->options = $question->options->map(function ($option) {
            return [
                'id' => $option->id,
                'option_key' => $option->option_key,
                'content' => $option->content,
                'is_correct' => $option->is_correct,
                'media_path' => $option->media_path,
                'media_url' => $option->getMediaUrl(),
                'new_media' => null,
                'order' => $option->order,
                'metadata' => $option->metadata,
            ];
        })->toArray();

        // Enforce exactly 2 options for True/False
        if (count($this->options) > 2) {
            $this->options = array_slice($this->options, 0, 2);
        }

        while (count($this->options) < 2) {
            $index = count($this->options);
            $this->options[] = [
                'option_key' => chr(65 + $index),
                'content' => $index === 0 ? 'Benar' : 'Salah',
                'is_correct' => false, // User must select correct answer manually
                'new_media' => null,
                'id' => null,
                'order' => $index,
            ];
        }
    }

    public function rules()
    {
        return [
            'options.*.new_media' => 'nullable|image|max:2048',
            'options.*.content' => 'required|string', // Content required for T/F
        ];
    }

    #[On('save-options')]
    public function saveOptions()
    {
        $this->validate();

        // Custom Validation: Exactly one correct answer
        $correctCount = collect($this->options)->where('is_correct', true)->count();
        if ($correctCount !== 1) {
            $this->addError('correct_answer', 'Harap tandai satu opsi sebagai kunci jawaban yang benar.');
            return;
        }

        $question = Question::findOrFail($this->questionId);

        // 1. Get existing IDs from DB
        $existingIds = $question->options()->pluck('id')->toArray();

        // 2. Identify submitted IDs
        $submittedIds = array_column(array_filter($this->options, fn($opt) => isset($opt['id'])), 'id');

        // 3. Delete options that are in DB but missing (shouldn't happen for T/F usually, but safe to keep)
        $idsToDelete = array_diff($existingIds, $submittedIds);
        if (!empty($idsToDelete)) {
            Option::destroy($idsToDelete);
        }

        // 4. Update or Create
        foreach ($this->options as $index => $optionData) {
            $dataToSave = [
                'question_id' => $this->questionId,
                'option_key' => $optionData['option_key'] ?? chr(65 + $index),
                'content' => $optionData['content'],
                'is_correct' => $optionData['is_correct'] ?? false,
                'order' => $index,
            ];

            if (isset($optionData['id'])) {
                $option = Option::find($optionData['id']);
                if ($option) {
                    $option->update($dataToSave);
                }
            } else {
                $option = Option::create($dataToSave);
                // Update local array with new ID
                $this->options[$index]['id'] = $option->id;
            }

            if ($option) {
                // Handle Option Media
                if (isset($optionData['new_media'])) {
                    $file = $optionData['new_media'];

                    if (is_string($file) && str_starts_with($file, 'livewire-file:')) {
                        $file = TemporaryUploadedFile::createFromLivewire($file);
                    }

                    if ($file instanceof \Illuminate\Http\UploadedFile && $file->isValid()) {
                        try {
                            $option->clearMediaCollection('option_media');
                            $option->addMedia($file)->toMediaCollection('option_media');
                            $this->options[$index]['new_media'] = null;
                        } catch (\Exception $e) {
                            Log::error("Failed to upload option media: " . $e->getMessage());
                        }
                    }
                }
            }
        }

        $this->dispatch('options-saved');
    }

    public function deleteOptionMedia($index)
    {
        if (isset($this->options[$index]['new_media'])) {
            $this->options[$index]['new_media'] = null;
        }

        if (isset($this->options[$index]['id'])) {
            $option = Option::find($this->options[$index]['id']);
            if ($option) {
                $option->clearMediaCollection('option_media');
                $this->options[$index]['media_url'] = null;
                $this->options[$index]['media_path'] = null;
            }
        }
        $this->options[$index]['media_url'] = null;
    }

    public function setCorrectAnswer($index)
    {
        foreach ($this->options as $key => &$option) {
            $option['is_correct'] = ($key === $index);
        }
    }

    public function render()
    {
        return view('livewire.question.form.option.true-false-editor-component');
    }
}
