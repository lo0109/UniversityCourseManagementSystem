@extends('layouts.main')

@section('title', 'Group Details')

@section('content')
<div class="container mt-5">
    <h1>Group {{ $group }} Details for {{ $assessment->title }}</h1>

    @if(Auth::user()->teacher)
    <h2>Peer Reviews Overview (Teacher View)</h2>
    @if($groupedReviews->isEmpty())
        <p>No peer reviews found for this group.</p>
    @else
        <ul>
        @foreach($groupedReviews as $reviewerId => $reviews)
            <li>
                <h3>Reviewer: {{ $reviews->first()->reviewer_name ?? 'N/A' }}</h3>
                @php
                    // Count the number of reviews with non-null comments
                    $reviewsWithCommentsCount = $reviews->filter(function($review) {
                        return !is_null($review->comment);
                    })->count();

                    // Get the reviewer's assessment score from the AssessmentMark table
                    $reviewerAssessmentMark = \App\Models\AssessmentMark::where('assessment_id', $assessment->id)
                        ->where('student_id', $reviewerId)
                        ->first();
                @endphp
                <strong>No. of Reviews Given:</strong> {{ $reviewsWithCommentsCount }} / {{ $assessment->reviewNumber }}<br>
                <strong> Avg score received from reviewees:</strong> {{ number_format($reviews->avg('score'), 2) ?? 0}}<br>
                @if($reviewerAssessmentMark && $reviewerAssessmentMark->score !== null)
                    <strong>Score:</strong> {{ $reviewerAssessmentMark->score }} / {{ $assessment->maxScore }}<br>
                @else
                    <!-- Form for submitting score for the reviewer -->
                    <form action="{{ route('assessments.update_scores', $assessment->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="group" value="{{ $group }}">
                        <!-- Input field for score -->
                        <input type="number" name="scores[{{ $reviewerId }}]" 
                            min="0" max="{{ $assessment->maxScore }}" 
                            placeholder="0-{{ $assessment->maxScore }}" 
                            required style="width: 70px;">
                        <!-- Submit button -->
                        <button type="submit" class="btn btn-primary">Submit Score</button>
                    </form>
                @endif
                <ul>
                    @foreach($reviews as $review)
                        <li>
                            <strong>Reviewee:</strong> {{ $review->reviewee_name ?? 'N/A' }}<br>
                            <strong>Score Given by Reviewee:</strong> {{ $review->score !== null ? $review->score : 'Not given' }}<br>
                            <strong>Comment:</strong> {{ $review->comment ?? 'No comment' }}
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
        </ul>

        <!-- Add pagination links -->
        {{ $paginatedPeerReviews->links() }}
    @endif
    @else
        <!-- Student views -->
        <h2>Your Peer Reviews</h2>
        @if($studentReviews->isEmpty())
            <p>You have no peer reviews for this group.</p>
        @else
            <ul>
                @foreach($studentReviews as $review)
                    <li>
                        <strong>Reviewee:</strong> {{ $review->reviewee->name ?? 'N/A' }}<br>
                        <strong>Score you received (out of 5):</strong> {{ $review->score !== null ? $review->score : 'Not given' }}<br>
                        <strong>Your review:</strong> {{ $review->comment ?? 'Please submit your review' }}<br>
                        <a href="{{ route('peer_reviews.give_comment', ['assessment_id' => $assessment->id, 'review_id' => $review->id]) }}" class="btn btn-sm btn-secondary mt-2">Give Comment</a>
                    </li>
                @endforeach
            </ul>
        @endif

        <h2>Peer Reviews Received</h2>
        @if($reviewsForUser->isEmpty())
            <p>No one has reviewed you in this group.</p>
        @else
            <ul>
                @foreach($reviewsForUser as $review)
                    <li>
                        <strong>Reviewer:</strong> {{ $review->reviewer->name ?? 'N/A' }}<br>
                        <strong>Score you give (out of 5):</strong> {{ $review->score !== null ? $review->score : 'Not given' }}<br>
                        <strong>Review on your work:</strong> {{ $review->comment ?? 'Pending for review' }}<br>
                        <!-- Form for submitting score for the review received -->
                        @if($review->score !== null)
                            <strong>Your Score:</strong> {{ $review->score }} / 5<br>
                        @else
                            <form action="{{ route('peer_reviews.update_score', ['assessment_id' => $assessment->id, 'review_id' => $review->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <!-- Input field for score -->
                                <input type="number" name="score" min="0" max="5" placeholder="0-5" required style="width: 70px;">
                                <!-- Submit button -->
                                <button type="submit" class="btn btn-primary mt-2">Submit Score</button>
                            </form>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    @endif
    <a href="{{ route('assessments.show', $assessment->id) }}" class="btn btn-primary mt-3">Back to Assessment</a>
</div>
@endsection
