<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Assessment;
use App\Models\PeerReview;
use App\Models\User;
use App\Models\Enrollment;

class PeerReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void{
        // Get all assessments that are linked to courses
        $assessments = Assessment::all();

        foreach ($assessments as $assessment) {
            // Get all students enrolled in the course related to this assessment
            $enrollments = Enrollment::where('course_id', $assessment->course_id)->get();

            // Assign peer reviews within the same group
            foreach ($enrollments as $enrollment) {
                // The current student is the reviewer
                $reviewerId = $enrollment->student_id;

                // Assign each student in the same group as a reviewee (except the reviewer themself)
                foreach ($enrollments as $reviewee) {
                    if ($reviewee->student_id != $reviewerId) {
                        // Insert a peer review record
                        PeerReview::create([
                            'assessment_id' => $assessment->id,
                            'reviewer_id' => $reviewerId,
                            'reviewee_id' => $reviewee->student_id,
                            'peer_review_type_id' => $assessment->peer_review_type_id,
                            'group'=>1,
                        ]);
                    }
                }
            }
        }
    }
}
