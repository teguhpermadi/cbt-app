<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Question;
use Illuminate\Database\Seeder;

class ExamQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $exams = Exam::all();
        $questions = Question::all();

        if ($exams->isEmpty()) {
            $this->command->warn('❌ Tidak ada Exam. Harap jalankan ExamSeeder terlebih dahulu.');
            return;
        }
        if ($questions->isEmpty()) {
            $this->command->warn('❌ Tidak ada Question. Harap jalankan QuestionSeeder terlebih dahulu.');
            return;
        }
        
        $this->command->info('Mengisi soal ke dalam setiap Exam...');
        
        // Iterasi setiap Exam
        foreach ($exams as $exam) {
            $numQuestions = $exam->total_questions;
            
            // Pastikan tidak mengambil lebih banyak soal dari yang tersedia di bank soal
            $countQuestions = $questions->count();
            $questionsToSelect = min($numQuestions, $countQuestions);

            // Ambil sejumlah soal acak dari Bank Soal
            $selectedQuestions = $questions->random($questionsToSelect); 
            
            $questionNumber = 1;
            
            foreach ($selectedQuestions as $question) {
                // Gunakan factory untuk membuat ExamQuestion transaksional
                ExamQuestion::factory()->create([
                    'exam_id' => $exam->id,
                    'question_id' => $question->id,
                    'question_number' => $questionNumber++, // <-- Ini adalah kunci keunikan
                    
                    // Salin data dari Question
                    'content' => $question->content,
                    'options' => $question->options,
                    'key_answer' => $question->key_answer,
                    'score_value' => $question->score_value,
                    'question_type' => $question->question_type,
                    'difficulty_level' => $question->difficulty_level,
                ]);
            }
            $this->command->getOutput()->write("  -> Exam {$exam->title} diisi dengan {$questionsToSelect} soal.\n");
        }
        
        $this->command->info('✅ ExamQuestion Seeder selesai.');
    }
}