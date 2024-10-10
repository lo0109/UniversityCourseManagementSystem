@extends('layouts.main')

@section('title', 'Give Comment for Peer Review')

@section('content')
<div class="container mt-5">
    <h1>Give Comment for {{ $assessment->title }} - Group {{ $review->group }}</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>Reviewee:</strong> {{ $review->reviewee->name ?? 'N/A' }}</p>
            <p><strong>Current Comment:</strong> {{ $review->comment ?? 'No comment' }}</p>
        </div>
    </div>

    <form action="{{ route('peer_reviews.update_comment', ['assessment_id' => $assessment->id, 'review_id' => $review->id]) }}" method="POST" class="mt-4">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="comment">Comment:</label>
            <textarea name="comment" id="comment" class="form-control" rows="5" required>{{ $review->comment }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Submit Comment</button>
        <a href="{{ route('assessments.show', $assessment->id) }}" class="btn btn-secondary mt-3">Cancel</a>
    </form>
</div>
@endsection
