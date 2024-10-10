<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Course;

class Enrollmentseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = [240006, 240007, 240008, 240009, 240010, 240011, 240012, 240013, 240014, 240015];

        // Fetch all course IDs from the courses table
        $courses = Course::pluck('course_id')->toArray();

        foreach ($courses as $course) {
            // Enroll the first 6 students into workshop 1 for each course
            for ($i = 0; $i < 6; $i++) {
                DB::table('enrollments')->insert([
                    'student_id' => $students[$i],
                    'course_id' => $course,
                    'workshop' => 1,
                ]);
            }
        }

    }
}
