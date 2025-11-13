<?php

namespace Database\Seeders;

use App\Models\ExamResultDetail;
use App\Models\ExamSession;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class ExamResultDetailSeeder extends Seeder
{
    public function run(): void
    {
        $sessions = ExamSession::all();

        if ($sessions->isEmpty()) {
            $this->command->warn('❌ Tidak ada ExamSession. Harap jalankan ExamSession Seeder terlebih dahulu.');
            return;
        }

        $this->command->info('Membuat detail jawaban (ExamResultDetail) untuk setiap sesi...');

        foreach ($sessions as $session) {
            // PERBAIKAN UTAMA:
            // Menggunakan Optional Chaining (?->) untuk mengakses relasi examQuestions.
            // Jika $session->exam adalah null, maka ?? collect() akan memastikan $questions 
            // bernilai Collection kosong, sehingga loop foreach aman.
            $questions = $session->exam?->examQuestions ?? Collection::make([]);
            
            $totalDetails = 0;

            // Jika tidak ada soal (misalnya ExamQuestionSeeder belum dijalankan), lewati sesi ini
            if ($questions->isEmpty()) {
                 $this->command->getOutput()->write("  -> Sesi ID {$session->id} (Percobaan {$session->attempt_number}) dilewati: Tidak ada soal ditemukan.\n");
                continue;
            }

            foreach ($questions as $question) {
                // Buat detail jawaban untuk setiap soal dalam sesi ini
                ExamResultDetail::factory()->create([
                    'exam_session_id' => $session->id,
                    'exam_question_id' => $question->id,
                ]);
                $totalDetails++;
            }
            $this->command->getOutput()->write("  -> Sesi ID {$session->id} (Percobaan {$session->attempt_number}) diisi dengan {$totalDetails} detail jawaban.\n");
        }

        $this->command->info('✅ ExamResultDetail Seeder selesai.');
    }
}