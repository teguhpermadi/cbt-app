<?php

namespace App\Livewire\Question\Form\Option;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Facades\Log;
use App\Models\Question;
use App\Models\Option;

class MatchingEditorComponent extends Component
{
    use WithFileUploads;

    public $pairs = []; // Array of { id, left_content, right_content, left_media, right_media, ... }
    public $questionId;

    public function mount($questionId)
    {
        $this->questionId = $questionId;
        $this->loadOptions();
    }

    public function loadOptions()
    {
        $question = Question::find($this->questionId);
        $options = $question->options()->orderBy('order')->get();

        // Reconstruct pairs from options (L1 matches R1, etc.)
        // Options are stored as individual rows. We need to group them by pair_id.

        $grouped = [];
        foreach ($options as $option) {
            $pairId = $option->getMetadata('pair_id');
            if (!$pairId) continue;

            if (!isset($grouped[$pairId])) {
                $grouped[$pairId] = ['new_left_media' => null, 'new_right_media' => null];
            }

            $side = $option->getMetadata('side'); // 'left' or 'right'

            $prefix = ($side === 'left') ? 'left_' : 'right_';

            $grouped[$pairId][$prefix . 'id'] = $option->id;
            $grouped[$pairId][$prefix . 'content'] = $option->content;
            $grouped[$pairId][$prefix . 'media_url'] = $option->getMediaUrl();
        }

        $this->pairs = array_values($grouped);

        if (empty($this->pairs)) {
            $this->addPair();
        }
    }

    public function rules()
    {
        return [
            'pairs.*.left_content' => 'required',
            'pairs.*.right_content' => 'required',
            'pairs.*.new_left_media' => 'nullable|image|max:2048',
            'pairs.*.new_right_media' => 'nullable|image|max:2048',
        ];
    }

    public function addPair()
    {
        $this->pairs[] = [
            'left_id' => null,
            'left_content' => '',
            'left_media_url' => null,
            'right_id' => null,
            'right_content' => '',
            'right_media_url' => null,
            'new_left_media' => null,
            'new_right_media' => null,
        ];
    }

    public function removePair($index)
    {
        unset($this->pairs[$index]);
        $this->pairs = array_values($this->pairs);
    }

    public function deletePairMedia($index, $side)
    {
        // side is 'left' or 'right'
        if (isset($this->pairs[$index]['new_' . $side . '_media'])) {
            $this->pairs[$index]['new_' . $side . '_media'] = null;
        }

        $idKey = $side . '_id';
        if (isset($this->pairs[$index][$idKey])) {
            $option = Option::find($this->pairs[$index][$idKey]);
            if ($option) {
                $option->clearMediaCollection('option_media');
                $this->pairs[$index][$side . '_media_url'] = null;
            }
        }
        $this->pairs[$index][$side . '_media_url'] = null;
    }

    #[On('save-options')]
    public function saveOptions()
    {
        $this->validate();

        $question = Question::findOrFail($this->questionId);

        // 1. Get existing IDs
        $existingIds = $question->options()->pluck('id')->toArray();

        // 2. Collect submitted IDs
        $submittedIds = [];
        foreach ($this->pairs as $pair) {
            if (isset($pair['left_id'])) $submittedIds[] = $pair['left_id'];
            if (isset($pair['right_id'])) $submittedIds[] = $pair['right_id'];
        }

        // 3. Delete removed
        $idsToDelete = array_diff($existingIds, $submittedIds);
        if (!empty($idsToDelete)) {
            Option::destroy($idsToDelete);
        }

        // 4. Save
        foreach ($this->pairs as $index => $pairData) {
            $pairId = $index + 1;

            // --- LEFT OPTION ---
            $this->saveOption($pairData, 'left', $pairId, $index * 2);

            // --- RIGHT OPTION ---
            $this->saveOption($pairData, 'right', $pairId, $index * 2 + 1);
        }

        $this->dispatch('options-saved');
    }

    protected function saveOption($pairData, $side, $pairId, $order)
    {
        $prefix = $side . '_';
        $otherSide = ($side === 'left') ? 'right' : 'left';

        $dataToSave = [
            'question_id' => $this->questionId,
            'option_key' => (($side === 'left') ? 'L' : 'R') . $pairId,
            'content' => $pairData[$prefix . 'content'],
            'is_correct' => true, // Matching pairs are implicitly correct connections
            'order' => $order,
            'metadata' => [
                'side' => $side,
                'pair_id' => $pairId,
                'match_with' => (($otherSide === 'left') ? 'L' : 'R') . $pairId,
            ]
        ];

        $optionId = $pairData[$prefix . 'id'] ?? null;
        $option = null;

        if ($optionId) {
            $option = Option::find($optionId);
            if ($option) $option->update($dataToSave);
        } else {
            $option = Option::create($dataToSave);
            // Update local ID
            // Note: Cannot easily update $this->pairs[$index] inside this helper without pass by ref or return
            // But we don't strictly need to update it for the immediate standardized response unless we want to keep editing without refresh.
        }

        // Create/Update Media
        if ($option && isset($pairData['new_' . $prefix . 'media'])) {
            $this->handleMediaUpload($option, $pairData['new_' . $prefix . 'media']);
        }
    }

    protected function handleMediaUpload($option, $file)
    {
        if (is_string($file) && str_starts_with($file, 'livewire-file:')) {
            $file = TemporaryUploadedFile::createFromLivewire($file);
        }

        if ($file instanceof \Illuminate\Http\UploadedFile && $file->isValid()) {
            try {
                $option->clearMediaCollection('option_media');
                $option->addMedia($file)->toMediaCollection('option_media');
            } catch (\Exception $e) {
                Log::error("Media upload failed: " . $e->getMessage());
            }
        }
    }

    public function render()
    {
        return view('livewire.question.form.option.matching-editor-component');
    }
}
