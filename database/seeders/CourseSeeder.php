<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('courses')->insert([
            'course_id' => 'TRY9734',
            'name' => 'Advanced Programming',
            'description' => 'An advanced course on programming concepts and design patterns.',
            'online' => true,
            'workshop' => 3,  // Example: 3 workshops
        ]);
        DB::table('courses')->insert([
            'course_id' => 'TRY5329',
            'name' => 'Database Systems',
            'description' => 'Covers advanced database concepts and SQL optimization techniques.',
            'online' => false,
            'workshop' => 2,
        ]);
        DB::table('courses')->insert([
            'course_id' => 'TRY6124',
            'name' => 'Web Development',
            'description' => 'Learn modern web development techniques and frameworks.',
            'online' => true,
            'workshop' => 4,
        ]);
        DB::table('courses')->insert([
            'course_id' => 'TRY1234',
            'name' => 'Data Structures and Algorithms',
            'description' => 'Learn about data structures and algorithms in computer science.',
            'online' => false,
            'workshop' => 2,
            'teacherID' => 240000,  // Example: Teacher
        ]);
        DB::table('courses')->insert([
            'course_id' => 'TRY1200',
            'name' => 'Database Design',
            'description' => 'Learn about data structures.',
            'online' => false,
            'workshop' => 2,
            'teacherID' => 240000,  // Example: Teacher
        ]);
        DB::table('courses')->insert([
            'course_id' => 'TRY1201',
            'name' => 'Software Engineering',
            'description' => 'Learn about software engineering principles and practices.',
            'online' => true,
            'workshop' => 3,
            'teacherID' => 240000,  // Example: Teacher
        ]);
    }
}
