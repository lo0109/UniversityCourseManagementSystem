<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CourseController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\WorkshopController;
use App\Http\Controllers\PeerReviewController;

// Home route (open to everyone)
Route::get('/', function () {
    return view('home');
})->name('home');



// Courses route (only for logged-in users)
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
    Route::get('/assessments/{assessment}', [AssessmentController::class, 'show'])->name('assessments.show');

    // show workshops
    Route::get('/courses/{course}/workshops/{workshop}', [WorkshopController::class, 'show'])->name('workshops.show');

    //edit the course
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');

    // Teach the course
    Route::post('/courses/{course}/teach', [CourseController::class, 'teach'])->name('courses.teach');

    // Edit assessment
    Route::get('/assessments/{assessment}/edit', [AssessmentController::class, 'edit'])->name('assessments.edit');
    Route::put('/assessments/{assessment}', [AssessmentController::class, 'update'])->name('assessments.update');

    // Delete assessment
    Route::delete('/assessments/{assessment}', [AssessmentController::class, 'destroy'])->name('assessments.destroy');
    
    //peer review group detail
    Route::get('/assessments/{assessment_id}/workshops/{workshop}/group/{group}', [PeerReviewController::class, 'showGroupDetail'])->name('peer_reviews.group_detail');

    //peer review
    Route::get('/assessments/{assessment_id}/peer-reviews/{review_id}/comment', [PeerReviewController::class, 'giveComment'])->name('peer_reviews.give_comment');
    // Update the comment on a peer review
    Route::put('/assessments/{assessment_id}/peer-reviews/{review_id}/comment', [PeerReviewController::class, 'updateComment'])->name('peer_reviews.update_comment');

    //peer review score
    Route::get('/assessments/{assessment_id}/peer-reviews/{review_id}/score', [PeerReviewController::class, 'giveScore'])->name('peer_reviews.give_score');
    Route::put('/assessments/{assessment_id}/peer-reviews/{review_id}/score', [PeerReviewController::class, 'updateScore'])->name('peer_reviews.update_score');

    // Update the scores for an assessment
    Route::put('/assessments/{assessment}/update-scores', [AssessmentController::class, 'updateScores'])->name('assessments.update_scores');

    // Create an assessment for a specific course
    Route::get('/courses/{course}/assessments/create', [AssessmentController::class, 'create'])->name('assessments.create');
    Route::post('/courses/{course}/assessments', [AssessmentController::class, 'store'])->name('assessments.store');
        
    // Create a peer review group
    Route::get('assessments/{assessment_id}/peer_reviews/create', [PeerReviewController::class, 'create'])->name('peer_reviews.create');
    Route::post('assessments/{assessment_id}/peer_reviews', [PeerReviewController::class, 'store'])->name('peer_reviews.store');

    // Route for creating peer review groups
    Route::get('/assessments/{assessment_id}/workshops/{workshop}/create-groups', [PeerReviewController::class, 'createGroups'])->name('peer_reviews.create_groups');
    // Route for storing peer review groups
    Route::post('/assessments/{assessment_id}/workshops/{workshop}/store-groups', [PeerReviewController::class, 'storeGroups'])->name('peer_reviews.store_groups');});

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

// Register resource routes at the end to avoid conflicts with custom routes
Route::resource('courses', CourseController::class);
Route::resource('assessments', AssessmentController::class);
Route::resource('workshops', WorkshopController::class);