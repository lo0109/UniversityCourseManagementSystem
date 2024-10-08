<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeerReview extends Model
{
    use HasFactory;
    protected $fillable = ['assessmentID', 'reviewer_id', 'reviewee_id' , 'comment', 'typeID'];
    function reviewee() {
        return $this->belongsTo(\App\Models\User::class, 'reviewee_id');
    }
    function reviewer() {
        return $this->belongsTo(\App\Models\User::class, 'reviewer_id');
    }
}
