<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CourseController;

// Home route (open to everyone)
Route::get('/', function () {
    return view('home');
})->name('home');


// Your Courses route (only for logged-in users)
Route::middleware(['auth'])->get('/courses', [CourseController::class, 'index'])->name('courses.index');

Route::middleware('auth')->group(function () {
    // Display courses that can be added
    Route::get('/courses/add', [CourseController::class, 'courseToAdd'])->name('courses.courseToAdd');

    // Handle adding the course to the user's list
    Route::post('/courses/add/{course}', [CourseController::class, 'addCourses'])->name('courses.addCourses');
    
    // Display the course details
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');

    // Enroll in a workshop
    Route::post('/courses/enroll/{course}', [CourseController::class, 'enrollWorkshop'])->name('enroll.workshop');

    // show the list of assessments
    Route::get('/assessments/{assessment}', [AssessmentController::class, 'show'])->name('assessments.view');


});

// Dashboard route (only for authenticated and verified users)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
