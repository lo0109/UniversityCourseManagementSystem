<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // Check if the user is a teacher
        if ($user->teacher) {
            // Get courses where the authenticated user is the teacher
            $courses = Course::where('teacherID', $user->userID)->get();
        } else {
            // If the user is a student, get the courses they are enrolled in
            $courses = Course::whereIn('course_id', function ($query) use ($user) {
                $query->select('course_id')
                      ->from('enrollment')
                      ->where('student_id', $user->userID);
            })->get();
        }

        // Return the view with the courses
        return view('courses.index', compact('courses'));
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
        //
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
