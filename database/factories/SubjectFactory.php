<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement([
            'Matematika', 
            'Bahasa Indonesia', 
            'Bahasa Inggris', 
            'Fisika', 
            'Kimia', 
            'Biologi', 
            'Sejarah'
        ]) . ' ' . $this->faker->randomElement(['Wajib', 'Peminatan']);

        // Mengambil 3 huruf pertama dan 3 angka acak untuk kode
        $code = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $name), 0, 3)) . $this->faker->unique()->randomNumber(3);

        return [
            'name' => $name,
            'code' => $code,
            'description' => $this->faker->paragraph(),
        ];
    }
}
