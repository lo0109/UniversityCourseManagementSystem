<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Assessment;

class AssessmentMark extends Model
{
    use HasFactory;
    protected $fillable = ['student_id', 'assessment_id', 'score'];

    // Relationship to the student
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Relationship to the assessment
    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'assessment_id');
    }
}
