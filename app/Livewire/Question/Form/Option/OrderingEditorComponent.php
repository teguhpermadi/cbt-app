<?php

namespace App\Livewire\Question\Form\Option;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Facades\Log;
use App\Models\Question;
use App\Models\Option;

class OrderingEditorComponent extends Component
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

        $this->options = $question->options()->orderBy('order')->get()->map(function ($option) {
            return [
                'id' => $option->id,
                'option_key' => $option->option_key, // Will be 1, 2, 3...
                'content' => $option->content,
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
            'options.*.content' => 'required',
            'options.*.new_media' => 'nullable|image|max:2048',
        ];
    }

    public function addOption()
    {
        $index = count($this->options);
        $this->options[] = [
            'id' => null,
            'option_key' => (string)($index + 1),
            'content' => '',
            'media_url' => null,
            'new_media' => null,
            'order' => $index,
            'metadata' => ['correct_position' => $index + 1],
        ];
    }

    public function removeOption($index)
    {
        unset($this->options[$index]);
        $this->options = array_values($this->options);
        $this->reindexOptions();
    }

    public function updateOptionOrder($orderedIds)
    {
        // $orderedIds represents the visual order (top to bottom)
        // If the item has an ID (existing), we find it.
        // If it looks like a temporary ID (hash), we track it by index? 
        // Actually, SortableJS with Livewire usually easiest with `wire:sortable` or by strictly using keys.
        // If we use `wire:key="option-{{ $index }}"`, reordering DOM doesn't reorder Livewire array automatically.
        // We need to map the new order to `$this->options`.

        // Strategy: 
        // The view will pass an array of indices in the new order.
        // e.g., [2, 0, 1] means old index 2 is now first, old 0 is second...

        $newOptions = [];
        foreach ($orderedIds as $oldIndex) {
            if (isset($this->options[$oldIndex])) {
                $newOptions[] = $this->options[$oldIndex];
            }
        }
        $this->options = $newOptions;
        $this->reindexOptions();
    }

    protected function reindexOptions()
    {
        foreach ($this->options as $index => &$option) {
            $option['order'] = $index;
            $option['option_key'] = (string)($index + 1);
            $option['metadata']['correct_position'] = $index + 1;
        }
    }

    #[On('save-options')]
    public function saveOptions()
    {
        $this->validate();

        $question = Question::findOrFail($this->questionId);
        $existingIds = $question->options()->pluck('id')->toArray();
        $submittedIds = array_column(array_filter($this->options, fn($opt) => isset($opt['id'])), 'id');

        $idsToDelete = array_diff($existingIds, $submittedIds);
        if (!empty($idsToDelete)) {
            Option::destroy($idsToDelete);
        }

        foreach ($this->options as $index => $optionData) {
            $dataToSave = [
                'question_id' => $this->questionId,
                'option_key' => (string)($index + 1),
                'content' => $optionData['content'],
                'is_correct' => true, // In ordering, valid sequence is the key. Individual options aren't 'correct' vs 'incorrect' in isolation, but let's mark them true as they are part of the solution.
                'order' => $index,
                'metadata' => [
                    'correct_position' => $index + 1
                ]
            ];

            if (isset($optionData['id'])) {
                $option = Option::find($optionData['id']);
                if ($option) {
                    $option->update($dataToSave);
                }
            } else {
                $option = Option::create($dataToSave);
                $this->options[$index]['id'] = $option->id;
            }

            if ($option && isset($optionData['new_media'])) {
                // Media handling code (similar to others)
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
                        Log::error("Media upload failed: " . $e->getMessage());
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

    public function render()
    {
        return view('livewire.question.form.option.ordering-editor-component');
    }
}
