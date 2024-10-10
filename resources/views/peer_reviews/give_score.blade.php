@extends('layouts.main')

@section('title', 'Give Score for Peer Review')

@section('content')
<div class="container mt-5">
    <h1>Give Score for {{ $assessment->title }} - Group {{ $review->group }}</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>Reviewer:</strong> {{ $review->reviewer->name ?? 'N/A' }}</p>
            <p><strong>Current Score:</strong> {{ $review->score !== null ? $review->score : 'Not given' }}</p>
            <p><strong>Current Comment:</strong> {{ $review->comment ?? 'No comment' }}</p>
        </div>
    </div>

    <form action="{{ route('peer_reviews.update_score', ['assessment_id' => $assessment->id, 'review_id' => $review->id]) }}" method="POST" class="mt-4">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="score">Score: (0-10)</label>
            <input type="number" name="score" id="score" class="form-control" value="{{ $review->score }}" min="0" max="100" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Submit Score</button>
        <a href="{{ route('assessments.show', $assessment->id) }}" class="btn btn-secondary mt-3">Cancel</a>
    </form>
</div>
@endsection
