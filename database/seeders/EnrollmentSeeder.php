<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Enrollmentseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('enrollments')->insert([
            'student_id' => 200006,
            'course_id' => 'ICT9734',
            'workshop' => 1,
        ]);
        DB::table('enrollments')->insert([
            'student_id' => 200006,
            'course_id' => 'ICT5329',
            'workshop' => 1,
        ]);
        DB::table('enrollments')->insert([
            'student_id' => 200006,
            'course_id' => 'ICT6124',
            'workshop' => 1,
        ]);
        DB::table('enrollments')->insert([
            'student_id' => 200007,
            'course_id' => 'ICT9734',
            'workshop' => 1,
        ]);
        DB::table('enrollments')->insert([
            'student_id' => 200007,
            'course_id' => 'ICT5329',
            'workshop' => 1,
        ]);
        DB::table('enrollments')->insert([
            'student_id' => 200007,
            'course_id' => 'ICT6124',
            'workshop' => 1,
        ]);


        DB::table('enrollments')->insert([
            'student_id' => 200008,
            'course_id' => 'ICT9734',
            'workshop' => 1,
        ]);
        DB::table('enrollments')->insert([
            'student_id' => 200008,
            'course_id' => 'ICT5329',
            'workshop' => 1,
        ]);
        DB::table('enrollments')->insert([
            'student_id' => 200008,
            'course_id' => 'ICT6124',
            'workshop' => 1,
        ]);


        DB::table('enrollments')->insert([
            'student_id' => 200009,
            'course_id' => 'ICT9734',
            'workshop' => 2,
        ]);
        DB::table('enrollments')->insert([
            'student_id' => 200009,
            'course_id' => 'ICT5329',
            'workshop' => 2,
        ]);
        DB::table('enrollments')->insert([
            'student_id' => 200009,
            'course_id' => 'ICT6124',
            'workshop' => 2,
        ]);
    }
}
