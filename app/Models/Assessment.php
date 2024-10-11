<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\AssessmentType;
use App\Models\PeerReviewType;
use App\Models\AssessmentMark;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'typeID', 'title', 'instruction','maxScore', 'deadline', 'reviewNumber', 'peer_review_type_id'];
    
    function peerReviews() {
        return $this->hasMany(\App\Models\PeerReview::class, 'assessment_id');
    }
    
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }
    public function type(){
        return $this->belongsTo(AssessmentType::class, 'typeID', 'id');
    }

    public function peerReviewType(){
        return $this->belongsTo(PeerReviewType::class, 'peer_review_type_id');
    }

    public function assessmentMarks()
    {
        return $this->hasMany(AssessmentMark::class, 'assessment_id');
    }
}
