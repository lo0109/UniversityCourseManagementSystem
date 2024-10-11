<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;

class WorkshopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        // Retrieve the course and workshop from the request query parameters
        $courseId = $request->query('course');
        $workshop = $request->query('workshop');
    
        // Get the course along with the enrollments for the specific workshop
        $course = Course::with(['enrollments' => function ($query) use ($workshop) {
            $query->where('workshop', $workshop)->with('student');
        }])->findOrFail($courseId);
    
        // Get the students in the specified workshop
        $students = $course->enrollments;
    
        // Get students who are not enrolled in this course
        $unenrolledStudents = User::whereDoesntHave('enrollments', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->where('teacher', false) // Exclude teachers
        ->get();
    
        // Pass data to the view
        return view('workshops.show', compact('course', 'workshop', 'students', 'unenrolledStudents'));
    }
    
    /**
     * Enroll a student in the specified workshop.
     */
    public function enroll(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,course_id',
            'workshop' => 'required|integer',
            'student_id' => 'required|exists:users,userID', // Validate against userID instead of id
        ]);

        $courseId = $request->input('course_id');
        $studentId = $request->input('student_id');
        $workshop = $request->input('workshop');

        // Check if the student is already enrolled in the course
        $alreadyEnrolled = Enrollment::where('course_id', $courseId)
            ->where('student_id', $studentId)
            ->exists();

        if ($alreadyEnrolled) {
            return back()->with('error', 'Student is already enrolled in this course.');
        }

        // Enroll the student in the specified workshop
        Enrollment::create([
            'course_id' => $courseId,
            'student_id' => $studentId,
            'workshop' => $workshop,
        ]);

        // Redirect back to the show view with updated data
        return redirect('/workshops/show?course=' . $courseId . '&workshop=' . $workshop)
        ->with('success', 'Student enrolled successfully!');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
