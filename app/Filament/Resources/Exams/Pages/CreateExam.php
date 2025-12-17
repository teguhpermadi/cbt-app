<?php

namespace App\Filament\Resources\Exams\Pages;

use App\Filament\Resources\Exams\ExamResource;
use Filament\Resources\Pages\CreateRecord;

class CreateExam extends CreateRecord
{
    protected static string $resource = ExamResource::class;

    protected function afterCreate(): void
    {
        $exam = $this->record;

        // Jika Question Bank dipilih, salin soal-soalnya
        if ($exam->question_bank_id) {
            $questionBank = \App\Models\QuestionBank::with(['questions.options'])->find($exam->question_bank_id);

            if ($questionBank && $questionBank->questions->isNotEmpty()) {
                $examQuestions = [];

                $number = 1;

                foreach ($questionBank->questions as $question) {
                    // Skip jika soal tidak aktif
                    if (!$question->is_active) {
                        continue;
                    }

                    $examQuestions[] = [
                        'id' => \Illuminate\Support\Str::ulid(),
                        'exam_id' => $exam->id,
                        'question_id' => $question->id,
                        'question_number' => $number++,
                        'content' => $question->content,
                        'options' => json_encode($question->getOptionsForExam()), // Helper dari model Question
                        'key_answer' => json_encode($question->getKeyAnswerForExam()), // Helper dari model Question
                        'score_value' => $question->score_value,
                        'question_type' => $question->question_type->value,
                        'difficulty_level' => $question->difficulty_level->value,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Batch insert untuk efisiensi
                if (!empty($examQuestions)) {
                    \App\Models\ExamQuestion::insert($examQuestions);
                }
            }
        }
    }
}
