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
            ['type' => 'peer-review'],  // Type 1
            ['type' => 'exam'],  // Type 2
            ['type' => 'test']      // Type 3
        ]);
    }
}
