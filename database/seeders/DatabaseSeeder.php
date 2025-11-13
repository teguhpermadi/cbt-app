<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            UserSeeder::class,
            AcademicYearSeeder::class,
            GradeSeeder::class,
            SubjectSeeder::class,
            ReadingMaterialSeeder::class,
            
            // --- Model Bank Soal ---
            QuestionBankSeeder::class,       
            QuestionSeeder::class,           
            QuestionPeerReviewSeeder::class, 
            
            // --- Model Konfigurasi Ujian ---
            ExamSeeder::class,
        ]);
    }
}
