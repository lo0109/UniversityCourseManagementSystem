<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'typeID', 'title', 'instruction','maxScore', 'deadline', 'reviewNumber', 'student_id'];
    function peerReviews() {
        return $this->hasMany(\App\Models\PeerReview::class, 'assessmentID');
    }
    
}
