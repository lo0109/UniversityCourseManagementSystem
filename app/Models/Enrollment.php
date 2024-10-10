<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Course;

class Enrollment extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'course_id',
        'workshop',
    ];

    /**
     * Relationship to the Course model.
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    /**
     * Relationship to the User model (as a student).
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'userID');
    }
}
