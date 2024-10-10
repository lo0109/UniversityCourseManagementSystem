<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assessment;
use Illuminate\Support\Facades\Auth;
use App\Models\PeerReview;
use App\Models\PeerReviewType;
use App\Models\Course;

class AssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all assessments
        $assessments = Assessment::all();
        return view('assessment.index', ['assessments' => $assessments]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($course)
    {
        // Retrieve the course to pass it to the view
        $course = Course::findOrFail($course);
        $assessmentTypes = \App\Models\AssessmentType::all();
        $peerReviewTypes = PeerReviewType::all();
    
        // Return the view with the course data, assessment types, and peer review types
        return view('assessments.create', compact('course', 'assessmentTypes', 'peerReviewTypes'));
    }
    
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $courseId)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'maxScore' => 'required|integer|min:1',
            'deadline' => 'required|date',
            'instruction' => 'nullable|string',
            'typeID' => 'required|integer|exists:assessment_types,id', // Ensure the type exists in the assessment_types table
            'reviewNumber' => 'nullable|integer|min:1', // Make nullable for non-peer review types
            'peer_review_type_id' => 'nullable|integer|exists:peer_review_types,id', // Make nullable for non-peer review types
        ]);

        // If the type is not 1 (peer review), set reviewNumber and peer_review_type_id to null
        if ($validatedData['typeID'] != 1) {
            $validatedData['reviewNumber'] = null;
            $validatedData['peer_review_type_id'] = null;
        }

        // Create the assessment
        Assessment::create([
            'course_id' => $courseId,
            'typeID' => $validatedData['typeID'],
            'title' => $validatedData['title'],
            'instruction' => $validatedData['instruction'],
            'maxScore' => $validatedData['maxScore'],
            'deadline' => $validatedData['deadline'],
            'reviewNumber' => $validatedData['reviewNumber'],
            'peer_review_type_id' => $validatedData['peer_review_type_id'],
        ]);

        // Redirect back to the course details page with a success message
        return redirect()->route('courses.show', $courseId)->with('success', 'Assessment created successfully.');
    }

    


    /**
     * Display the specified resource.
     */
    public function show(string $id){
        $assessment = Assessment::with(['course', 'peerReviews'])->findOrFail($id);
        $course = $assessment->course;

        if (Auth::user()->teacher) {
            // Teacher view: Group peer reviews by 'group' for each workshop
            $workshops = $course->enrollments()
                ->with('student')
                ->get()
                ->groupBy('workshop')
                ->map(function ($enrollments, $workshopNumber) use ($assessment) {
                    // Filter peer reviews by the enrollment students for this workshop
                    return PeerReview::where('assessment_id', $assessment->id)
                        ->whereIn('reviewee_id', $enrollments->pluck('student_id'))
                        ->select('group', \DB::raw('COUNT(DISTINCT reviewer_id) as student_count'))
                        ->groupBy('group')
                        ->get();
                });

            $viewData = compact('assessment', 'workshops');
        } else {
            // Student view: Show peer review groups for the student's workshop
            $studentWorkshop = Auth::user()->enrollments()
                ->where('course_id', $assessment->course_id)
                ->value('workshop');

            if ($studentWorkshop) {
                $workshops = PeerReview::where('assessment_id', $assessment->id)
                    ->whereHas('reviewee.enrollments', function ($query) use ($studentWorkshop, $assessment) {
                        $query->where('course_id', $assessment->course_id)
                            ->where('workshop', $studentWorkshop);
                    })
                    ->select('group', \DB::raw('COUNT(DISTINCT reviewer_id) as student_count'))
                    ->groupBy('group')
                    ->get();

                $viewData = compact('assessment', 'workshops', 'studentWorkshop');
            } else {
                $viewData = compact('assessment');
            }
        }

        return view('assessments.show', $viewData);
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $assessment = Assessment::findOrFail($id);
        $assessmentTypes = \App\Models\AssessmentType::all(); // Fetch all available assessment types
        $peerReviewTypes = PeerReviewType::all();

        // Return the edit view with the assessment and assessment types data
        return view('assessments.edit', compact('assessment', 'assessmentTypes', 'peerReviewTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    // Validate the request data
    $request->validate([
        'typeID' => 'required|exists:assessment_types,id',
        'title' => 'required|string|max:20',
        'instruction' => 'required|string',
        'maxScore' => 'required|integer|between:1,100',
        'deadline' => 'required|date',
        'reviewNumber' => 'required|integer|min:1',
        'peerReviewType' => 'nullable|exists:peer_review_types,id',
    ]);

    // Find the assessment by ID
    $assessment = Assessment::findOrFail($id);

    // Update the assessment fields
    $assessment->typeID = $request->input('typeID');
    $assessment->title = $request->input('title');
    $assessment->instruction = $request->input('instruction');
    $assessment->maxScore = $request->input('maxScore');
    $assessment->deadline = $request->input('deadline');
    $assessment->reviewNumber = $request->input('reviewNumber');

    // If the typeID is 1 (peer review), update the peer_review_type_id
    if ($assessment->typeID == 1) {
        $assessment->peer_review_type_id = $request->input('peerReviewType');
    } else {
        // If typeID is not 1, set peer_review_type_id to null
        $assessment->peer_review_type_id = null;
    }

    // Save the assessment
    $assessment->save();

    // Update related peer reviews if the typeID is 1
    if ($assessment->typeID == 1) {
        // Update all peer reviews associated with this assessment to match the new peer_review_type_id
        PeerReview::where('assessment_id', $assessment->id)
            ->update(['peer_review_type_id' => $assessment->peer_review_type_id]);
    }

    // Redirect back to the assessment details page with a success message
    return redirect()->route('assessments.show', $assessment->id)->with('success', 'Assessment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the assessment by ID and delete it
        $assessment = Assessment::findOrFail($id);
        $assessment->delete();

        // Redirect to the assessments list with a success message
        return redirect()->route('assessments.index')->with('success', 'Assessment deleted successfully.');
    }
}
