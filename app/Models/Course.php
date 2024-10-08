<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'name', 'description', 'teacherID', 'workshop', 'online'];
    function students() {
        return $this->belongsToMany(\App\Models\User::class, 'enrollments', 'course_id', 'student_id');
    }
    function assessments() {
        return $this->hasMany(\App\Models\Assessment::class, 'course_id');
    }
    
}
