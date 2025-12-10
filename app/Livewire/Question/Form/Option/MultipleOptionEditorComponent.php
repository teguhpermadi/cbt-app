<?php

namespace App\Livewire\Question\Form\Option;

use Livewire\Component;
use \Livewire\Attributes\Modelable;
use \Livewire\WithFileUploads;
use \Livewire\Attributes\On;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Facades\Log;
use App\Models\Question;
use App\Models\Option;

class MultipleOptionEditorComponent extends Component
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
    }

    public function rules()
    {
        return [
            'options.*.new_media' => 'nullable|image|max:2048',
            'options.*.content' => 'nullable',
        ];
    }

    #[On('save-options')]
    public function saveOptions()
    {
        $this->validate();

        $question = Question::findOrFail($this->questionId);

        // 1. Get existing IDs from DB
        $existingIds = $question->options()->pluck('id')->toArray();

        // 2. Identify submitted IDs (those that have an ID)
        $submittedIds = array_column(array_filter($this->options, fn($opt) => isset($opt['id'])), 'id');

        // 3. Delete options that are in DB but missing from submission
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
            }

            if ($option) { // Ensure strict typing if create failed for some reason
                // Handle Option Media
                if (isset($optionData['new_media'])) {
                    $file = $optionData['new_media'];

                    // Manual hydration for Livewire temporary files
                    if (is_string($file) && str_starts_with($file, 'livewire-file:')) {
                        $file = TemporaryUploadedFile::createFromLivewire($file);
                    }

                    if ($file instanceof \Illuminate\Http\UploadedFile && $file->isValid()) {
                        try {
                            $option->clearMediaCollection('option_media');
                            $option->addMedia($file)->toMediaCollection('option_media');

                            // Clear the new_media from the array to prevent serialization issues
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
            'new_media' => null, // Initialize for upload binding
            'id' => null, // Mark as new
        ];
    }

    public function removeOption($index)
    {
        unset($this->options[$index]);
        $this->options = array_values($this->options); // Re-index array
        $this->reindexKeys(); // Maintain A, B, C sequence
    }

    public function deleteOptionMedia($index)
    {
        // 1. Clear new upload if any
        if (isset($this->options[$index]['new_media'])) {
            $this->options[$index]['new_media'] = null;
        }

        // 2. Clear existing media if any
        if (isset($this->options[$index]['id'])) {
            $option = Option::find($this->options[$index]['id']);
            if ($option) {
                $option->clearMediaCollection('option_media');
                $this->options[$index]['media_url'] = null;
                $this->options[$index]['media_path'] = null;
            }
        }

        // Ensure UI updates
        $this->options[$index]['media_url'] = null;
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
