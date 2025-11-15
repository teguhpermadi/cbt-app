<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionBank;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Jalankan seeder database.
     */
    public function run(): void
    {
        // Pastikan QuestionBank sudah ada. Jika belum ada, buat 3 bank soal dummy.
        if (QuestionBank::count() === 0) {
            $this->command->warn('QuestionBank kosong. Membuat 3 QuestionBank dummy terlebih dahulu.');
            QuestionBank::factory(3)->create();
        }

        $this->command->info('Membuat 21 soal (Question) dengan variasi tipe dan media...');

        // Kita akan membuat 21 soal secara total. 
        // Factory akan secara acak memilih tipe soal, bobot, dan menambahkan media dummy.
        Question::factory(21)->create();
        
        $this->command->info('✅ 21 Question berhasil dibuat.');

        // Opsional: Buat beberapa soal spesifik untuk memastikan semua tipe terwakili.
        // Meskipun factory acak, ini memastikan semua tipe soal minimal ada 1.
        $this->createSpecificQuestions();
    }
    
    /**
     * Metode untuk memastikan semua tipe soal ada minimal 1 (mengatasi keacakan factory).
     */
    private function createSpecificQuestions(): void
    {
        $bank = QuestionBank::inRandomOrder()->first();
        if (!$bank) return; // Jika tidak ada bank soal, hentikan

        $types = \App\Enums\QuestionTypeEnum::cases();
        
        foreach ($types as $type) {
            Question::factory()->withType($type)->create([
                'question_bank_id' => $bank->id,
                'score_value' => 15, // Bobot lebih tinggi untuk soal spesifik
            ]);
        }

        $this->command->info('✅ Soal spesifik untuk setiap tipe telah ditambahkan.');
    }
}