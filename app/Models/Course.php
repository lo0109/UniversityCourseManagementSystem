<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Course extends Model
{
    use HasFactory;
    protected $primaryKey = 'course_id';  // Specify course_id as the primary key
    public $incrementing = false;         // Tell Eloquent that course_id is not auto-incrementing
    protected $keyType = 'string';        // Specify that the primary key is a string (not an integer)

    protected $fillable = ['course_id', 'name', 'description', 'teacherID', 'workshop', 'online'];

    // Relationship to the teacher of the course
    public function teacher(){
        return $this->belongsTo(User::class, 'teacherID', 'userID');
    }

    // Relationship to the students enrolled in the course
    public function students(){
        return $this->belongsToMany(User::class, 'enrollment', 'course_id', 'student_id', 'course_id', 'userID');
    }

    function assessments() {
        return $this->hasMany(\App\Models\Assessment::class, 'course_id');
    }
    
}
