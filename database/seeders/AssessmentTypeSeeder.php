<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssessmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('assessment_types')->insert([
            ['type' => 'Peer Review'],  // Type 1
            ['type' => 'Exam'],  // Type 2
            ['type' => 'Test']      // Type 3
        ]);
    }
}
