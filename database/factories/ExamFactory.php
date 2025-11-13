<?php

namespace Database\Factories;

use App\Enums\ExamTypeEnum;
use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exam>
 */
class ExamFactory extends Factory
{
    public function definition(): array
    {
        // 1. Pastikan Relasi Dasar Sudah Ada
        $academicYear = AcademicYear::inRandomOrder()->first();
        $grade = Grade::inRandomOrder()->first();
        $subject = Subject::inRandomOrder()->first();
        
        // Asumsi guru yang membuat ujian adalah user_type 'teacher'
        $teacher = User::get()->random();

        $examType = $this->faker->randomElement(ExamTypeEnum::cases());
        $titlePrefix = match ($examType) {
            ExamTypeEnum::Daily => 'Ulangan Harian',
            ExamTypeEnum::Midterm => 'Ujian Tengah Semester',
            ExamTypeEnum::Final => 'Ujian Akhir Semester',
            ExamTypeEnum::Tryout => 'Try Out Mandiri',
        };
        
        // Jumlah soal yang akan digunakan dalam ujian
        $totalQuestions = $this->faker->numberBetween(30, 50);

        // Waktu mulai dan berakhir ujian
        $startTime = $this->faker->dateTimeBetween('-1 week', '+1 week');
        $endTime = (clone $startTime)->modify('+1 hour'); // Durasi 1 jam default

        return [
            'academic_year_id' => $academicYear->id,
            'grade_id' => $grade->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            
            'title' => "{$titlePrefix} {$subject->name} Kelas {$grade->name}",
            'exam_type' => $examType,
            
            'duration' => $this->faker->randomElement([60, 90, 120]), // Durasi 60/90/120 menit
            'total_questions' => $totalQuestions,
            'passing_score' => $this->faker->randomElement([65, 70, 75]),
            
            'is_published' => $this->faker->boolean(70), // 70% sudah terbit
            'is_randomized' => $this->faker->boolean(80), // 80% urutan diacak
            
            'start_time' => $startTime,
            'end_time' => $endTime,
        ];
    }
}
