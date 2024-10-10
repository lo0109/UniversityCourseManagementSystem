<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assessment;
use App\Models\PeerReview;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PeerReviewController extends Controller
{
    /**
     * Display a listing of peer reviews for a given assessment.
     */
    public function index($assessmentId)
    {
        $peerReviews = PeerReview::where('assessment_id', $assessmentId)->with(['reviewer', 'reviewee'])->get();

        return view('peer_reviews.index', compact('peerReviews', 'assessmentId'));
    }

    /**
     * Show the form for creating a new peer review.
     */
    public function create($assessment_id)
    {
        // Find the assessment
        $assessment = Assessment::findOrFail($assessment_id);

        // Get the course ID
        $courseId = $assessment->course_id;

        // Get the current user's workshop for this course
        $currentUserWorkshop = \App\Models\Enrollment::where('student_id', Auth::user()->userID)
            ->where('course_id', $courseId)
            ->value('workshop');

        // Get all students enrolled in the same course and the same workshop
        $enrolledStudents = $assessment->course->enrollments()
            ->where('student_id', '!=', Auth::user()->userID) // Exclude current user
            ->where('workshop', $currentUserWorkshop) // Include only students in the same workshop
            ->pluck('student_id');

        // Filter out students who already have a group in the same assessment
        $studentsInGroups = PeerReview::where('assessment_id', $assessment_id)
            ->whereIn('reviewee_id', $enrolledStudents)
            ->pluck('reviewee_id')
            ->unique();

        // Get the list of students who are in the same workshop but not in any group for this assessment
        $students = \App\Models\User::whereIn('userID', $enrolledStudents)
            ->whereNotIn('userID', $studentsInGroups)
            ->get();

        // Pass the assessment and students to the view
        return view('peer_reviews.create', compact('assessment', 'students'));
    }

    /**
     * Store a newly created peer review in the database.
     */
    public function store(Request $request, $assessment_id)
    {
        // Validate the request to ensure students are selected
        $request->validate([
            'students' => 'required|array|min:2', // Ensure at least two students are selected
        ]);

        $selectedStudents = $request->input('students');
        $currentUserId = Auth::user()->userID;

        // Add the logged-in user to the list of selected students if not already present
        if (!in_array($currentUserId, $selectedStudents)) {
            $selectedStudents[] = $currentUserId;
        }

        // Determine the group number for the new group
        $groupNumber = PeerReview::where('assessment_id', $assessment_id)->max('group') ?? 0;
        $groupNumber += 1;

        // Create peer reviews for each combination
        foreach ($selectedStudents as $reviewerId) {
            foreach ($selectedStudents as $revieweeId) {
                if ($reviewerId != $revieweeId) {
                    PeerReview::create([
                        'reviewer_id' => $reviewerId,
                        'reviewee_id' => $revieweeId,
                        'assessment_id' => $assessment_id,
                        'group' => $groupNumber,
                        'peer_review_type_id' => 2, // student assigned
                    ]);
                }
            }
        }

        // Redirect back to the assessment page with a success message
        return redirect()->route('assessments.show', $assessment_id)->with('success', 'Peer review group created successfully.');
    }

    /**
     * Show the form for editing the specified peer review.
     */
    public function edit($assessmentId, $id)
    {
        $peerReview = PeerReview::findOrFail($id);
        $students = User::where('teacher', false)->get();

        return view('peer_reviews.edit', compact('peerReview', 'assessmentId', 'students'));
    }

    /**
     * Update the specified peer review in the database.
     */
    public function update(Request $request, $assessmentId, $id)
    {
        $request->validate([
            'score' => 'required|integer|min:0|max:100',
            'comment' => 'required|string|max:500',
            'group' => 'required|string|max:50',
        ]);

        $peerReview = PeerReview::findOrFail($id);
        $peerReview->update([
            'score' => $request->score,
            'comment' => $request->comment,
            'group' => $request->group,
        ]);

        return redirect()->route('peer_reviews.index', $assessmentId)->with('success', 'Peer review updated successfully!');
    }

    /**
     * Remove the specified peer review from the database.
     */
    public function destroy($assessmentId, $id)
    {
        $peerReview = PeerReview::findOrFail($id);
        $peerReview->delete();

        return redirect()->route('peer_reviews.index', $assessmentId)->with('success', 'Peer review deleted successfully!');
    }

    /**
     * Show detailed information about a specific peer review group.
     */
    public function showGroupDetail($assessment_id, $group)
    {
        // Fetch the peer reviews for the specified group and assessment
        $peerReviews = PeerReview::where('assessment_id', $assessment_id)->where('group', $group)->with(['reviewer', 'reviewee'])->get();

        $assessment = Assessment::findOrFail($assessment_id);

        // Pass the group number, assessment, and peer reviews to the view
        return view('peer_reviews.group_detail', compact('assessment', 'peerReviews', 'group'));
    }

    // Show the form for giving a comment on a peer review
    public function giveComment($assessment_id, $review_id)
    {
        $assessment = Assessment::findOrFail($assessment_id);
        $review = PeerReview::with(['reviewee', 'reviewer'])->findOrFail($review_id);
        return view('peer_reviews.give_comment', compact('assessment', 'review'));
    }

    // Update the comment on a peer review
    public function updateComment(Request $request, $assessment_id, $review_id)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        $review = PeerReview::findOrFail($review_id);
        $review->update([
            'comment' => $request->input('comment'),
        ]);

        return redirect()->route('assessments.show', $assessment_id)->with('success', 'Comment updated successfully!');
    }

    // Update the score on a peer review
    public function giveScore($assessment_id, $review_id)
    {
        $assessment = Assessment::findOrFail($assessment_id);
        $review = PeerReview::with(['reviewee', 'reviewer'])->findOrFail($review_id);
        return view('peer_reviews.give_score', compact('assessment', 'review'));
    }

    public function updateScore(Request $request, $assessment_id, $review_id)
        {
            $request->validate([
                'score' => 'required|integer|min:0|max:10',
            ]);

            $review = PeerReview::findOrFail($review_id);
            $review->update([
                'score' => $request->input('score'),
            ]);

            return redirect()->route('assessments.show', $assessment_id)->with('success', 'Score updated successfully!');
        }


}
