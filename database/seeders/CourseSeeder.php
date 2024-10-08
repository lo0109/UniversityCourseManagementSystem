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
            'course_id' => 'ICT9734',
            'name' => 'Advanced Programming',
            'description' => 'An advanced course on programming concepts and design patterns.',
            'online' => true,
            'workshop' => 3,  // Example: 3 workshops
            'teacherID' => '1',  // Example: teacher 
        ]);
        DB::table('courses')->insert([
            'course_id' => 'ICT5329',
            'name' => 'Database Systems',
            'description' => 'Covers advanced database concepts and SQL optimization techniques.',
            'online' => false,
            'workshop' => 2,
            'teacherID' => '2',  // Example: teacher 
        ]);
        DB::table('courses')->insert([
            'course_id' => 'ICT6124',
            'name' => 'Web Development',
            'description' => 'Learn modern web development techniques and frameworks.',
            'online' => true,
            'workshop' => 4,
            'teacherID' => '3',  // Example: teacher 
        ]);
    }
}
