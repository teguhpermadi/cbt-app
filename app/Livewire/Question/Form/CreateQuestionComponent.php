<?php

namespace App\Livewire\Question\Form;

use App\Enums\DifficultyLevelEnum;
use App\Enums\QuestionTypeEnum;
use App\Enums\TimerEnum;
use App\Models\Question;
use App\Models\QuestionBank;
use Livewire\Component;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile; // Kept consistent with Edit component logic if needed, though mostly trait handles it
use App\Filament\Resources\QuestionBanks\QuestionBankResource;

use App\Models\Option;

class CreateQuestionComponent extends Component
{
    use \Livewire\WithFileUploads;

    public Question $question;
    public $questionType;
    public $content;

    public $questionImage; // Temporary upload
    public $existingQuestionImageUrl;

    public function mount(QuestionBank $questionBank)
    {
        // Buat draft question baru setiap kali masuk halaman buat soal
        // Ini diperlukan agar component child (opsi, selector, dll) bisa bekerja dengan ID question
        $this->question = Question::create([
            'question_bank_id' => $questionBank->id,
            'question_type' => QuestionTypeEnum::MultipleChoice, // Default
            'difficulty_level' => DifficultyLevelEnum::Medium,   // Default
            'timer' => TimerEnum::OneMinute,                     // Default
            'score_value' => 5,                                  // Default score
            'content' => '',
            'is_active' => false, // Draft, akan diaktifkan saat save
            'order' => $questionBank->questions()->max('order') + 1,
        ]);

        $this->questionType = $this->question->question_type->value;
        $this->content = $this->question->content;
        $this->existingQuestionImageUrl = null;

        // Generate default options for the initial type
        $this->generateDefaultOptions(QuestionTypeEnum::MultipleChoice);
    }

    public function updatedQuestionType($value)
    {
        // Update question type in DB so child components know what to render/logic to use
        $this->question->update([
            'question_type' => QuestionTypeEnum::from($value)
        ]);

        // Regenerate options based on the new type
        $this->generateDefaultOptions(QuestionTypeEnum::from($value));
    }

    private function generateDefaultOptions(QuestionTypeEnum $type)
    {
        // Clear existing options
        $this->question->options()->forceDelete();

        switch ($type) {
            case QuestionTypeEnum::MultipleChoice:
                Option::createMultipleChoiceOptions($this->question->id, [
                    ['key' => 'A', 'content' => '', 'is_correct' => false],
                    ['key' => 'B', 'content' => '', 'is_correct' => false],
                    ['key' => 'C', 'content' => '', 'is_correct' => false],
                ]);
                break;

            case QuestionTypeEnum::TrueFalse:
                // Default creates 'Benar' and 'Salah' options
                Option::createTrueFalseOptions($this->question->id, true);
                break;

            case QuestionTypeEnum::MultipleSelection:
                Option::createMultipleChoiceOptions($this->question->id, [
                    ['key' => 'A', 'content' => '', 'is_correct' => false],
                    ['key' => 'B', 'content' => '', 'is_correct' => false],
                    ['key' => 'C', 'content' => '', 'is_correct' => false],
                    ['key' => 'D', 'content' => '', 'is_correct' => false],
                ]);
                break;

            case QuestionTypeEnum::Matching:
                Option::createMatchingOptions($this->question->id, [
                    ['left' => '', 'right' => ''],
                    ['left' => '', 'right' => ''],
                ]);
                break;

            case QuestionTypeEnum::Ordering:
                Option::createOrderingOptions($this->question->id, [
                    '',
                    '',
                    '' // 3 empty items
                ]);
                break;
        }
    }

    public function rules()
    {
        return [
            'content' => 'required|string',
            'questionType' => 'required|string',
            'questionImage' => 'nullable|image|max:2048', // 2MB Max
        ];
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            // Recalculate order to ensure it's the last one
            // We use 'max' excluding the current question (if it's already in DB, which it is)
            // But since 'order' was set at creation, we might just want to re-confirm it's max + 1
            // relative to OTHER active questions.
            // Simplified approach: Get max order of existing questions + 1
            $maxOrder = Question::where('question_bank_id', $this->question->question_bank_id)
                ->where('id', '!=', $this->question->id) // Exclude self
                ->max('order');

            $newOrder = $maxOrder ? $maxOrder + 1 : 1;

            $this->question->update([
                'content' => $this->content,
                'question_type' => QuestionTypeEnum::from($this->questionType),
                'is_active' => true, // Aktifkan soal
                'order' => $newOrder,
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

    public function deleteQuestionImage()
    {
        // If there is a temporary upload, just remove it
        if ($this->questionImage) {
            $this->questionImage = null;
            return;
        }

        // If there is an existing image (unlikely in create, but possible if re-visiting draft logic in future), delete it
        if ($this->question->hasMedia('question_content')) {
            $this->question->clearMediaCollection('question_content');

            Notification::make()
                ->title('Gambar berhasil dihapus')
                ->success()
                ->send();
        }
    }

    #[On('options-saved')]
    public function onOptionsSaved()
    {
        Notification::make()
            ->title('Soal berhasil dibuat')
            ->success()
            ->send();

        // return redirect()->route('question-banks.show', $this->question->question_bank_id);
        return redirect()->to(QuestionBankResource::getUrl('page-question-banks', ['record' => $this->question->question_bank_id]));
    }

    public function cancel()
    {
        // Hapus draft question karena batal membuat
        $this->question->forceDelete();

        // return redirect()->route('question-banks.show', $this->question->question_bank_id);
        return redirect()->to(QuestionBankResource::getUrl('page-question-banks', ['record' => $this->question->question_bank_id]));
    }

    public function render()
    {
        $questionTypes = collect(QuestionTypeEnum::cases())
            ->map(fn($case) => [
                'id' => $case->value,
                'name' => $case->getLabel()
            ])
            ->all();

        return view('livewire.question.form.create-question-component', [
            'questionTypes' => $questionTypes,
        ]);
    }
}
