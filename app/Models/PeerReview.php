<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Assessment;

class PeerReview extends Model
{
    use HasFactory;
    protected $fillable = ['assessment_id', 'reviewer_id', 'reviewee_id' , 'comment', 'score', 'peer_review_type_id', 'group'];
    
    /**
     * Get the reviewer associated with the peer review.
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id', 'userID');
    }

    /**
     * Get the reviewee associated with the peer review.
     */
    public function reviewee()
    {
        return $this->belongsTo(User::class, 'reviewee_id', 'userID');
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'assessment_id');
    }

    public function peerReviewType()
    {
        return $this->belongsTo(PeerReviewType::class, 'peer_review_type_id');
    }

}
