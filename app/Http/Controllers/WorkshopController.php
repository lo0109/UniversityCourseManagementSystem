<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

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
        $courseId = $request->query('course');
        $workshop = $request->query('workshop');
        // Get the course along with the enrollments for the specific workshop
        $course = Course::with(['enrollments' => function ($query) use ($workshop) {
            $query->where('workshop', $workshop);
        }])->findOrFail($courseId);

        // Get the students in the specified workshop
        $students = $course->enrollments;

        // Pass data to the view
        return view('workshops.show', compact('course', 'workshop', 'students'));
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
