<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = User::factory(2)->create();
        foreach ($admins as $admin) {
            $admin->assignRole('admin');
        }

        $teachers = User::factory(10)->create();
        foreach ($teachers as $teacher) {
            $teacher->assignRole('teacher');
        }

        $students = User::factory(50)->create();
        foreach ($students as $student) {
            $student->assignRole('student');
        }
    }
}
