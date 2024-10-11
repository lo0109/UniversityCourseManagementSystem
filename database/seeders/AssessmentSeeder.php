<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Assessment;
use App\Models\Course;
use App\Models\PeerReviewType;

class AssessmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch all courses
        $courses = Course::all();

        // Loop through each course and create an assessment
        foreach ($courses as $course) {
            // Create the "Peer Review" assessment for each course
            Assessment::create([
                'course_id' => $course->course_id,
                'title' => 'Peer Review',
                'instruction' => 'Review and provide feedback on your peer\'s work.',
                'maxScore' => 100,  // Default max score
                'deadline' => now()->addWeeks(2),  // Default deadline 2 weeks from now
                'reviewNumber' => 2,  // Default number of reviews
                'typeID' => 1,  // Assuming '1' is the type for Peer Review
                'peer_review_type_id' => 1, //  peer review type 1
            ]);
        }
    }
}
