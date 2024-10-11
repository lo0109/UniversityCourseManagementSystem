@extends('layouts.main')

@section('title', 'Give Comment for Peer Review')

@section('content')
<div class="container mt-5">
    <h1>Give Comment for {{ $assessment->title }} - Group {{ $review->group }}</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>Reviewee:</strong> {{ $review->reviewee->name ?? 'N/A' }}</p>
            <p><strong>Current Score:</strong> {{ $review->score !== null ? $review->score : 'Not given' }}</p>
            <p><strong>Current Comment:</strong> {{ $review->comment ?? 'No comment' }}</p>
        </div>
    </div>
    <!-- Display Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger mt-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('peer_reviews.update_comment', ['assessment_id' => $assessment->id, 'review_id' => $review->id]) }}" method="POST" class="mt-4">
        @csrf
        @method('PUT')
        <input type="hidden" name="workshop" value="{{ $review->reviewee->enrollments->where('course_id', $assessment->course_id)->first()->workshop }}">
        <div class="form-group mt-3">
            <label for="comment">Comment:</label>
            <textarea name="comment" id="comment" class="form-control" rows="5" required>{{ $review->comment }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Submit Comment</button>
        <a href="{{ url('assessments/' . $assessment->id . '/workshops/' . $review->reviewee->enrollments->where('course_id', $assessment->course_id)->first()->workshop . '/group/' . $review->group) }}" class="btn btn-secondary mt-3">Cancel</a>
        </form>
</div>
@endsection
