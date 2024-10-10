<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\AssessmentTypeSeeder;
use Database\Seeders\PeerReviewTypeSeeder;
use Database\Seeders\CourseSeeder;
use Database\Seeders\EnrollmentSeeder;
use Database\Seeders\AssessmentSeeder;
use Database\Seeders\PeerReviewSeeder;
use App\Models\Course;
use App\Models\Enrollment;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Seed 5 teachers
        $this->call(UserSeeder::class);
        // Seed 50 students
        User::factory(50)->create();

        //seed 10 courses
        \App\Models\Course::factory(10)->create();

        //seed assessment types
        $this->call(AssessmentTypeSeeder::class);
        //seed peer review types
        $this->call(PeerReviewTypeSeeder::class);

        //seed test enrollements
        $this->call(EnrollmentSeeder::class);
        //seed test courses
        $this->call(CourseSeeder::class);
        //seed assesments
        $this->call(AssessmentSeeder::class);
        //seed peer reviews
        $this->call(PeerReviewSeeder::class);

        // Enroll selected students into workshop 2 for each course
        $students = [240012, 240013, 240014, 240015, 240016];
        $courses = Course::pluck('course_id')->toArray();

        foreach ($courses as $courseId) {
            foreach ($students as $studentId) {
                Enrollment::create([
                    'student_id' => $studentId,
                    'course_id' => $courseId,
                    'workshop' => 2,
                ]);
            }
        }
    }
}
