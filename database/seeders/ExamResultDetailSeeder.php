<?php

namespace Database\Seeders;

use App\Models\ExamResultDetail;
use App\Models\ExamSession;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection; // Penting: Import Collection

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

        $successfulDetails = 0;
        $skippedSessions = 0;
        
        foreach ($sessions as $session) {
            // PERBAIKAN KRUSIAL: Gunakan Safe Navigation untuk mencegah crash jika relasi hilang.
            $questions = $session->exam?->examQuestions ?? Collection::make([]);
            
            $totalDetails = 0;

            // Jika tidak ada soal terkait dengan Exam ini, lewati sesi
            if ($questions->isEmpty()) {
                // Hapus baris write() jika Anda tidak ingin melihat output ini
                // $this->command->getOutput()->write("  -> Sesi ID {$session->id} dilewati: Tidak ada ExamQuestion terkait.\n");
                $skippedSessions++;
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
            $successfulDetails += $totalDetails;
        }

        $this->command->info('----------------------------------------------------');
        $this->command->info("✅ ExamResultDetail Seeder selesai. Total Detail Dibuat: {$successfulDetails}. Sesi Dilewati: {$skippedSessions}");
        $this->command->info('----------------------------------------------------');
    }
}