<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assessment;
use App\Models\PeerReview;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Enrollment;


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
    public function showGroupDetail($assessmentId, $group){
        // Step 1: Retrieve the assessment
        $assessment = Assessment::findOrFail($assessmentId);
    
        // Step 2: Get the authenticated user's userID
        $loggedInUserID = Auth::user()->userID;
    
        // Step 3: Check if the user is a teacher or a student
        if (Auth::user()->teacher) {
            // Teacher View: Get all the peer reviews
            $allPeerReviews = \DB::table('peer_reviews')
                ->where('assessment_id', $assessmentId)
                ->where('group', $group)
                ->join('users as reviewers', 'peer_reviews.reviewer_id', '=', 'reviewers.userID')
                ->join('users as reviewees', 'peer_reviews.reviewee_id', '=', 'reviewees.userID')
                ->select(
                    'peer_reviews.*',
                    'reviewers.name as reviewer_name',
                    'reviewees.name as reviewee_name'
                )
                ->orderBy('peer_reviews.reviewer_id') // Order by reviewer for consistency
                ->get()
                ->groupBy('reviewer_id');
    
            // Custom pagination logic: Define varying items per page for each reviewer
            $page = LengthAwarePaginator::resolveCurrentPage();
            $itemsPerPage = 1; // Define how many reviewers to show per page (1 reviewer)
    
            // Slice the collection based on the current page and items per page
            $currentItems = $allPeerReviews->slice(($page - 1) * $itemsPerPage, $itemsPerPage)->flatten(1);
    
            // Create a LengthAwarePaginator instance
            $paginatedPeerReviews = new LengthAwarePaginator(
                $currentItems,
                $allPeerReviews->count(),
                $itemsPerPage,
                $page,
                ['path' => LengthAwarePaginator::resolveCurrentPath()]
            );
    
            $groupedReviews = collect($paginatedPeerReviews->items())->groupBy('reviewer_id');
    
    
            // Empty collections for student-related data in teacher view
            $studentReviews = collect();
            $reviewsForUser = collect();
    
        } else {
            // Student View: Get peer reviews where the user is the reviewer or reviewee
            $studentReviews = PeerReview::where('assessment_id', $assessmentId)
                ->where('group', $group)
                ->where('reviewer_id', $loggedInUserID)
                ->with(['reviewer', 'reviewee']) // Eager load relationships
                ->get();

            $reviewsForUser = PeerReview::where('assessment_id', $assessmentId)
                ->where('group', $group)
                ->where('reviewee_id', $loggedInUserID)
                ->with(['reviewer', 'reviewee']) // Eager load relationships
                ->get();
        
                // Empty collections for teacher-related data in student view
                $groupedReviews = collect();
                $paginatedPeerReviews = collect();
            }
        
            // Step 4: Pass the data to the view
            return view('peer_reviews.group_detail', [
                'assessment' => $assessment,
                'studentReviews' => $studentReviews,
                'reviewsForUser' => $reviewsForUser,
                'groupedReviews' => $groupedReviews,
                'paginatedPeerReviews' => $paginatedPeerReviews,
                'group' => $group,
            ]);
    }

    public function giveComment($assessment_id, $review_id)
    {
        // Retrieve the assessment and the specific peer review
        $assessment = Assessment::findOrFail($assessment_id);
        $review = PeerReview::with(['reviewee', 'reviewer'])->findOrFail($review_id);
    
        // Return the view to give a comment, passing the assessment and review
        return view('peer_reviews.give_comment', compact('assessment', 'review'));
    }

    // Update the comment on a peer review
    public function updateComment(Request $request, $assessment_id, $review_id)
    {
         $request->validate([
            'comment' => [
                'required',
                'string',
                'between:10,500',
                function ($attribute, $value, $fail) {
                    // Split the comment into words
                    $words = preg_split('/\s+/', trim($value));
                    $wordCount = count($words);
                    
                    // Check if there are at least 10 words
                    if ($wordCount < 10) {
                        return $fail('The comment must have at least 10 words.');
                    }

                    // Count the unique words
                    $uniqueWords = count(array_unique($words));

                    // Check if at least half of the words are unique
                    if ($uniqueWords < $wordCount / 2) {
                        return $fail('At least half of the words in the comment must be unique.');
                    }
                },
            ],
        ]);

        // If validation passes, update the comment
        $review = PeerReview::findOrFail($review_id);
        $review->update([
            'comment' => $request->input('comment'),
        ]);

        // Redirect back to the assessment's group detail page with a success message
        return redirect()->route('peer_reviews.group_detail', [
            'assessment_id' => $assessment_id,
            'group' => $review->group
        ])->with('success', 'Comment updated successfully!');
    }

    // Update the score on a peer review
    public function updateScore(Request $request, $assessment_id, $review_id)
    {
        $request->validate([
            'score' => 'required|integer|min:0|max:5',
        ]);

        $review = PeerReview::findOrFail($review_id);
        $review->update([
            'score' => $request->input('score'),
        ]);

        return back()->with('success', 'Score updated successfully!');
    }


    /**
     * Show the form for creating groups for a specific assessment and workshop.
     */
    public function createGroups($assessment_id, $workshop)
    {
        // Fetch the assessment
        $assessment = Assessment::findOrFail($assessment_id);

        // Get the students enrolled in this workshop
        $students = Enrollment::where('course_id', $assessment->course_id)
            ->where('workshop', $workshop)
            ->with('student')
            ->get()
            ->pluck('student');

        // Return a view with the assessment, workshop, and list of students
        return view('peer_reviews.create_groups', compact('assessment', 'workshop', 'students'));
    }

    public function storeGroups(Request $request, $assessment_id, $workshop)
    {
        $assessment = Assessment::findOrFail($assessment_id);

        // Validate the request input for group size
        $request->validate([
            'group_size' => 'required|integer|min:' . ($assessment->reviewNumber + 1),
        ]);

        // Get all students in the specified workshop
        $students = Enrollment::where('course_id', $assessment->course_id)
            ->where('workshop', $workshop)
            ->pluck('student_id')
            ->toArray();

        // Get the last peer review records for this assessment, grouped by group number
        $lastPeerReviews = PeerReview::where('assessment_id', $assessment_id)
            ->get()
            ->groupBy('group')
            ->map(function ($reviews) {
                return $reviews->pluck('reviewee_id')->toArray();
            })
            ->toArray();

        // Shuffle the students to ensure randomness
        shuffle($students);
        $groups = [];
        $groupIndex = 0;

        // Function to check if a student was in the same group last time
        $isInSameGroupLastTime = function ($studentId, $currentGroup) use ($lastPeerReviews) {
            // If lastPeerReviews is empty, return false
            if (empty($lastPeerReviews)) {
                return false;
            }
            foreach ($currentGroup as $memberId) {
                foreach ($lastPeerReviews as $lastGroupMembers) {
                    if (in_array($studentId, $lastGroupMembers) && in_array($memberId, $lastGroupMembers)) {
                        return true; // The student was in the same group as a current group member last time
                    }
                }
            }
            return false;
        };

        // Create groups
        while (!empty($students)) {
            // If the current group does not exist, initialize it
            if (!isset($groups[$groupIndex])) {
                $groups[$groupIndex] = [];
            }

            // Extract the first student from the list
            $studentId = array_shift($students);

            // If the student was in the same group as any of the current group members last time, push to the end of the list
            if ($isInSameGroupLastTime($studentId, $groups[$groupIndex])) {
                $students[] = $studentId; // Push to the end of the list
                continue; // Skip to the next iteration
            }

            // Add the student to the current group
            $groups[$groupIndex][] = $studentId;

            // If the group reaches the specified group size, move to the next group
            if (count($groups[$groupIndex]) >= $request->input('group_size')) {
                $groupIndex++;
            }
        }

        // Handle any remaining students
        foreach ($students as $studentId) {
            $addedToGroup = false;
            foreach ($groups as $index => $group) {
                if (!$isInSameGroupLastTime($studentId, $group)) {
                    $groups[$index][] = $studentId;
                    $addedToGroup = true;
                    break; // Exit the inner loop if the student is added
                }
            }
            if (!$addedToGroup) {
                // Add the student to a new group if they couldn't be added to any existing group
                $groups[$groupIndex][] = $studentId;
                $groupIndex++;
            }
        }
        
        // Save each group in the PeerReview table
        foreach ($groups as $groupNumber => $groupMembers) {
            foreach ($groupMembers as $reviewerId) {
                foreach ($groupMembers as $revieweeId) {
                    if ($reviewerId !== $revieweeId) {
                        PeerReview::create([
                            'assessment_id' => $assessment_id,
                            'reviewer_id' => $reviewerId,
                            'reviewee_id' => $revieweeId,
                            'group' => $groupNumber + 1,
                            'peer_review_type_id' => $assessment->peer_review_type_id,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('assessments.show', $assessment_id)->with('success', 'Groups created successfully.');
    
    }


}
