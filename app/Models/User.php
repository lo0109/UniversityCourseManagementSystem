<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'userID',
        'teacher',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function taughtCourses() {
        return $this->hasMany(\App\Models\Course::class, 'teacherID', 'userID');
    }
    
    public function enrolledCourses() {
        return $this->belongsToMany(\App\Models\Course::class, 'enrollments', 'student_id', 'course_id', 'userID', 'course_id')
        ->withPivot('workshop');  // Include the workshop field from the pivot table
    }

    public function enrollments()    {
        return $this->hasMany(Enrollment::class, 'student_id', 'userID');
    }
    
}
