<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeerReviewTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('peer_review_types')->insert([
            ['type' => 'teacher_assign'],  // Type 1
            ['type' => 'student_select'],  // Type 2
        ]);
    }
}
