<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user is a teacher or student
        if ($user->teacher) {
            // Fetch courses taught by the teacher
            $courses = $user->taughtCourses;
        } else {
            // Fetch courses the student is enrolled in
            $courses = $user->enrolledCourses;
        }

        // Return the view with the courses
        return view('courses.index', compact('courses'));
    }

    /**
     * Display all courses that are not taught or enrolled by the user.
     */
    public function courseToAdd()
    {
        $user = Auth::user();

        // Get courses the user is either teaching or enrolled in
        $enrolledOrTaughtCourses = $user->enrolledCourses->pluck('course_id')->merge($user->taughtCourses->pluck('course_id'));

        // Check if the user is a teacher
        if ($user->teacher) {
            // If user is a teacher, show courses with no assigned teacher (i.e., teacherID is null)
            $coursesToAdd = Course::whereNull('teacherID')->get();
        } else {
            // If user is a student, show courses they are not enrolled
            $coursesToAdd = Course::whereNotIn('course_id', $enrolledOrTaughtCourses)->get();
        }

        return view('courses.add', compact('coursesToAdd'));
    }

    /**
     * Add the course to the user's list based on their role.
     */
    public function addCourses(Request $request, $id)
    {
        $user = Auth::user();
        $course = Course::findOrFail($id);

        if ($user->teacher) {
            // If the user is a teacher, assign the course to them by updating teacherID
            $course->teacherID = $user->userID;
            $course->save();

            return redirect()->back()->with('success', 'Course assigned to you as the teacher.');
        } else {
            // If the user is a student, check if they are already enrolled in the course
            $existingEnrollment = $user->enrolledCourses()->where('enrollments.course_id', $id)->first();

            if ($existingEnrollment) {
                // If the student is already enrolled, show an error
                return redirect()->back()->with('error', 'You are already enrolled in this course.');
            }

            // If not enrolled, attach the student to the course with the selected workshop
            $user->enrolledCourses()->attach($id, ['workshop' => $request->workshop]);

            return redirect()->back()->with('success', 'You have successfully enrolled in the course and workshop.');
        }
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
    public function show(string $id)
    {
        // Retrieve the course by its primary key (course_id)
        $course = Course::findOrFail($id);

        $user = Auth::user();

        // Check if the user is already enrolled in the course (students only)
        $enrollment = null;
        $assessments = [];


        if (!$user->teacher) {
            $enrollment = $user->enrolledCourses()->where('enrollments.course_id', $id)->first();
        }

        // Retrieve assessments for the course
        $assessments = $course->assessments;

        // Pass the course, enrollment, and assessments data to the view
        return view('courses.show', compact('course', 'enrollment', 'assessments'));
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
