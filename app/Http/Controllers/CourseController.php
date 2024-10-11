<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Assessment;
use Illuminate\Support\Facades\DB;


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
        if ($user)
            {if ($user->teacher) {
                // Fetch courses taught by the teacher
                $courses = $user->taughtCourses()->paginate(5);
            } else {
                // Fetch courses the student is enrolled in
                $courses = $user->enrolledCourses()->paginate(5);
            }
            // Return the view with the courses
            return view('courses.index', compact('courses'));
        }
        // If the user is not authenticated, redirect to the login page
        return redirect()->route('login')->with('error', 'You must be logged in to view courses.');
    }

    /**
     * Display all courses that are not taught or enrolled by the user.
     */
    public function courseToAdd()
    {
        // Get the authenticated user
        $user = Auth::user();
        // Get courses the user is either teaching or enrolled in
        $enrolledOrTaughtCourses = $user->enrolledCourses->pluck('course_id')->merge($user->taughtCourses->pluck('course_id'));

        // Check if the user is a teacher
        if ($user->teacher) {
            // If user is a teacher, show courses with no assigned teacher (i.e., teacherID is null)
            $coursesToAdd = Course::whereNull('teacherID')->paginate(5);
        } else {
            // If user is a student, show courses they are not enrolled
            $coursesToAdd = Course::whereNotIn('course_id', $enrolledOrTaughtCourses)->paginate(5);
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

    // teach a course
    public function teach($id)
    {
        $user = Auth::user();
        $course = Course::findOrFail($id);
    
        if (!$user->teacher) {
            return redirect()->back()->with('error', 'Only teachers can teach a course.');
        }
    
        // Assign the current user as the teacher
        $course->teacherID = $user->userID;
        $course->save();
    
        return redirect()->route('courses.show', $course->course_id)->with('success', 'You are now the teacher of this course.');
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
        $request->validate([
            'course_id' => 'required|string|max:7|unique:courses,course_id',
            'name' => 'required|string|max:20',
            'description' => 'required|string',
            'online' => 'required|boolean',
            'workshop' => 'required|integer|min:1|max:5',
            'teacherID' => 'nullable|exists:users,userID',
        ]);
    
        // Create the course and store it in a variable
        $course = Course::create($request->all());
    
        // Redirect to the course detail page with a success message
        return redirect()->route('courses.show', ['course' => $course->course_id])->with('success', 'Course created successfully.');
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
        if (!Auth::check() || !Auth::user()->teacher) {
            return redirect()->back()->with('error', 'You do not have permission to access this page.');
        } else {        
            $course = Course::findOrFail($id);
            return view('courses.edit', compact('course'));}
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $course = Course::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:20',
            'description' => 'required|string',
            'online' => 'required|boolean',
            'workshop' => 'required|integer|min:1|max:5',
        ]);

        // Update the course details
        $course->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'online' => $request->input('online'),
            'workshop' => $request->input('workshop'),
        ]);

        // Redirect to the course detail page with a success message
        return redirect()->route('courses.show', $id)->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

        /**
     * Handle the upload of a course JSON file.
     */
    public function uploadCourse(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'course_file' => 'required|file|mimes:json',
        ]);

        // Read the file content
        $jsonData = json_decode(file_get_contents($request->file('course_file')->getPathname()), true);

        if (!$jsonData) {
            return back()->with('error', 'Invalid JSON format.');
        }

        // Validate the JSON structure
        if (!isset($jsonData['course'], $jsonData['assessments'], $jsonData['students'])) {
            return back()->with('error', 'JSON file is missing required sections.');
        }

        $courseData = $jsonData['course'];
        $assessments = $jsonData['assessments'];
        $students = $jsonData['students'];

        // Check if the course already exists
        if (Course::where('course_id', $courseData['course_id'])->exists()) {
            return back()->with('error', 'A course with the same course ID already exists.');
        }

        // Start a database transaction
        DB::beginTransaction();
        try {
            // Create the course
            $course = Course::create([
                'course_id' => $courseData['course_id'],
                'name' => $courseData['name'],
                'description' => $courseData['description'],
                'online' => $courseData['online'] ?? false,
                'teacherID' => $courseData['teacherID'],
                'workshop' => $courseData['workshop'],
            ]);

            // Create assessments
            foreach ($assessments as $assessmentData) {
                Assessment::create([
                    'title' => $assessmentData['title'],
                    'instruction' => $assessmentData['instruction'],
                    'maxScore' => $assessmentData['maxScore'],
                    'deadline' => $assessmentData['deadline'],
                    'typeID' => $assessmentData['typeID'],
                    'course_id' => $course->course_id,
                ]);
            }

            // Enroll students
            foreach ($students as $studentData) {
                Enrollment::create([
                    'course_id' => $course->course_id,
                    'student_id' => $studentData['userID'],
                    'workshop' => $studentData['workshop'],
                ]);
            }

            // Commit the transaction
            DB::commit();
            return redirect()->route('courses.upload_form')->with('success', 'Course, assessments, and students were successfully created.');
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
