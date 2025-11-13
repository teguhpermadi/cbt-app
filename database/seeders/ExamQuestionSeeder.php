<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Question;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection; // Import Collection

class ExamQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $exams = Exam::all();
        $questions = Question::all();

        // Pemeriksaan Diagnosis Tambahan
        $this->command->info("ℹ️ Total Exam yang ditemukan: {$exams->count()}");
        $this->command->info("ℹ️ Total Question (Bank Soal) yang ditemukan: {$questions->count()}");


        if ($exams->isEmpty()) {
            $this->command->warn('❌ Tidak ada Exam. Harap jalankan ExamSeeder terlebih dahulu.');
            return;
        }
        if ($questions->isEmpty()) {
            $this->command->error('❌ Tidak ada Question. Harap jalankan QuestionSeeder dan dependensinya (QuestionBankSeeder) terlebih dahulu.');
            return;
        }
        
        $this->command->info('Mengisi soal ke dalam setiap Exam...');
        
        // Iterasi setiap Exam
        foreach ($exams as $exam) {
            $numQuestions = $exam->total_questions;
            
            // Cek jika total_questions tidak masuk akal atau nol
            if ($numQuestions <= 0) {
                 $this->command->getOutput()->write("  -> Exam {$exam->title} dilewati: total_questions diset {$numQuestions}.\n");
                continue;
            }

            // Pastikan tidak mengambil lebih banyak soal dari yang tersedia di bank soal
            $countQuestions = $questions->count();
            $questionsToSelect = min($numQuestions, $countQuestions);

            // Jika jumlah soal yang dipilih nol, lewati
            if ($questionsToSelect === 0) {
                 $this->command->getOutput()->write("  -> Exam {$exam->title} dilewati: Tidak cukup soal di bank soal.\n");
                continue;
            }

            // Ambil sejumlah soal acak dari Bank Soal
            $selectedQuestions = $questions->random($questionsToSelect); 
            
            $questionNumber = 1;
            
            foreach ($selectedQuestions as $question) {
                // Gunakan factory untuk membuat ExamQuestion transaksional
                ExamQuestion::factory()->create([
                    'exam_id' => $exam->id,
                    'question_id' => $question->id,
                    'question_number' => $questionNumber++, 
                    // Salin data lainnya...
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