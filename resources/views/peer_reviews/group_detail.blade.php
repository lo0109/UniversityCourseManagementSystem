@extends('layouts.main')

@section('title', 'Group Details')

@section('content')
<div class="container mt-5">
    <h1>Group {{ $group }} Details for {{ $assessment->title }}</h1>

    @php
        // Get the authenticated user's userID
        $loggedInUserID = Auth::user()->userID;

        // Filter the peer reviews to include only those where the logged-in user's userID is the reviewer_id
        $studentReviews = $peerReviews->filter(function ($review) use ($loggedInUserID) {
            return $review->reviewer_id == $loggedInUserID;
        });

        // Filter the peer reviews to include only those where the logged-in user's userID is the reviewee_id
        $reviewsForUser = $peerReviews->filter(function ($review) use ($loggedInUserID) {
            return $review->reviewee_id == $loggedInUserID;
        });
    @endphp
    @if(Auth::user()->teacher)
        <h2>Peer Reviews Overview (Teacher View)</h2>

        @php
            // Group the peer reviews by the reviewer
            $groupedReviews = $peerReviews->groupBy('reviewer_id');
        @endphp

        @if($groupedReviews->isEmpty())
            <p>No peer reviews found for this group.</p>
        @else
            <ul>
                @foreach($groupedReviews as $reviewerId => $reviews)
                    <li>
                        <h3>Reviewer: {{ $reviews->first()->reviewer->name ?? 'N/A' }}</h3>
                        <ul>
                            @foreach($reviews as $review)
                                <li>
                                    <strong>Reviewee:</strong> {{ $review->reviewee->name ?? 'N/A' }}<br>
                                    <strong>Score:</strong> {{ $review->score !== null ? $review->score : 'Not given' }}<br>
                                    <strong>Comment:</strong> {{ $review->comment ?? 'No comment' }}
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        @endif

        <a href="{{ route('assessments.show', $assessment->id) }}" class="btn btn-primary mt-3">Back to Assessment</a>
    @else
        <!-- Student views -->
        <h2>Your Reviews Given</h2>
        @if($studentReviews->isEmpty())
            <p>You have no peer reviews for this group.</p>
        @else
            <ul>
                @foreach($studentReviews as $review)
                    <li>
                        <strong>Reviewee:</strong> {{ $review->reviewee->name ?? 'N/A' }}<br>
                        <strong>Score:</strong> {{ $review->score !== null ? $review->score : 'Not given' }}<br>
                        <strong>Comment:</strong> {{ $review->comment ?? 'No comment' }}<br>
                        <a href="{{ route('peer_reviews.give_comment', ['assessment_id' => $assessment->id, 'review_id' => $review->id]) }}" class="btn btn-sm btn-secondary mt-2">Give Comment</a>
                    </li>
                @endforeach
            </ul>
        @endif

        <h2>Reviews for You</h2>
        @if($reviewsForUser->isEmpty())
            <p>No one has reviewed you in this group.</p>
        @else
            <ul>
                @foreach($reviewsForUser as $review)
                    <li>
                        <strong>Reviewer:</strong> {{ $review->reviewer->name ?? 'N/A' }}<br>
                        <strong>Score:</strong> {{ $review->score !== null ? $review->score : 'Not given' }}<br>
                        <strong>Comment:</strong> {{ $review->comment ?? 'No comment' }}<br>
                        <a href="{{ route('peer_reviews.give_score', ['assessment_id' => $assessment->id, 'review_id' => $review->id]) }}" class="btn btn-sm btn-primary mt-2">Give Score</a>
                    </li>
                @endforeach
            </ul>
        @endif

        <a href="{{ route('assessments.show', $assessment->id) }}" class="btn btn-primary mt-3">Back to Assessment</a>
    @endif
</div>
@endsection
