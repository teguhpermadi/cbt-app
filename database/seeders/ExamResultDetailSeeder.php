<?php

namespace Database\Seeders;

use App\Models\ExamResultDetail;
use App\Models\ExamSession;
use App\Models\ExamQuestion; // <--- IMPORT ExamQuestion
use Illuminate\Database\Seeder;

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
            // PERBAIKAN: Ambil ExamQuestion secara langsung berdasarkan ID, bukan melalui relasi
            $questions = ExamQuestion::where('exam_id', $session->exam_id)->get();

            $totalDetails = 0;

            if ($questions->isEmpty()) {
                $skippedSessions++;
                continue;
            }

            foreach ($questions as $question) {
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