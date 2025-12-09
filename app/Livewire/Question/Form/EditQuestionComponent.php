<?php

namespace App\Livewire\Question\Form;

use App\Enums\DifficultyLevelEnum;
use App\Enums\QuestionTypeEnum;
use App\Enums\TimerEnum;
use App\Models\Question;
use App\Models\QuestionBank;
use Livewire\Component;
use Filament\Notifications\Notification;

class EditQuestionComponent extends Component
{
    public Question $question;
    public $questionBankId;
    public $questionType;
    public $difficultyLevel;
    public $timer;
    public $content;
    public $scoreValue;
    public $order;

    public function mount(Question $question)
    {
        $this->question = $question->load('questionBank');
        $this->questionBankId = $question->question_bank_id;
        $this->questionType = $question->question_type->value;
        $this->difficultyLevel = $question->difficulty_level->value;
        $this->timer = $question->timer->value;
        $this->content = $question->content;
        $this->scoreValue = $question->score_value;
        $this->order = $question->order;
    }

    public function rules()
    {
        return [
            'content' => 'required|string',
            'questionType' => 'required|string',
            'difficultyLevel' => 'required|string',
            'timer' => 'required|integer',
            'scoreValue' => 'required|numeric|min:0',
            'order' => 'required|integer|min:1',
        ];
    }

    public function update()
    {
        $this->validate();

        $this->question->update([
            'content' => $this->content,
            'question_type' => QuestionTypeEnum::from($this->questionType),
            'difficulty_level' => DifficultyLevelEnum::from($this->difficultyLevel),
            'timer' => TimerEnum::from($this->timer),
            'score_value' => $this->scoreValue,
            'order' => $this->order,
        ]);

        Notification::make()
            ->title('Soal berhasil diupdate')
            ->success()
            ->send();

        return redirect()->route('question-banks.show', $this->question->question_bank_id);
    }

    public function cancel()
    {
        return redirect()->route('question-banks.show', $this->question->question_bank_id);
    }

    public function render()
    {
        $questionTypes = QuestionTypeEnum::cases();
        $difficultyLevels = DifficultyLevelEnum::cases();
        $timers = TimerEnum::cases();

        return view('livewire.question.form.edit-question-component', [
            'questionTypes' => $questionTypes,
            'difficultyLevels' => $difficultyLevels,
            'timers' => $timers,
        ]);
    }
}
