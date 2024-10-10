@extends('layouts.main')

@section('title', 'Create Assessment')

@section('content')
<div class="container mt-5">
    <h1>Create a New Assessment for {{ $course->name }}</h1>

    <form action="{{ route('assessments.store', ['course' => $course->course_id]) }}" method="POST">
        @csrf
        <div class="form-group mt-3">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <div class="form-group mt-3">
            <label for="maxScore">Max Score:</label>
            <input type="number" name="maxScore" id="maxScore" class="form-control" required>
        </div>

        <div class="form-group mt-3">
            <label for="deadline">Deadline:</label>
            <input type="date" name="deadline" id="deadline" class="form-control" required>
        </div>

        <div class="form-group mt-3">
            <label for="instruction">Instructions:</label>
            <textarea name="instruction" id="instruction" class="form-control"></textarea>
        </div>

        <div class="form-group mt-3">
            <label for="type">Assessment Type:</label>
            <select name="type" id="type" class="form-control" required>
                <option value="">Select an Assessment Type</option>
                @foreach($assessmentTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->type }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group mt-3" id="reviewNumberGroup" style="display: none;">
            <label for="reviewNumber">Number of Reviews Required:</label>
            <input type="number" name="reviewNumber" id="reviewNumber" class="form-control">
        </div>

        <div class="form-group mt-3" id="peerReviewTypeGroup" style="display: none;">
            <label for="peer_review_type">Peer Review Type:</label>
            <select name="peer_review_type" id="peer_review_type" class="form-control">
                <option value="">Select Peer Review Type</option>
                @foreach($peerReviewTypes as $peerType)
                    <option value="{{ $peerType->id }}">{{ $peerType->type }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary mt-4">Create Assessment</button>
    </form>

    <a href="{{ route('courses.show', $course->course_id) }}" class="btn btn-secondary mt-3">Back to Course</a>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const typeSelect = document.getElementById('type');
        const reviewNumberGroup = document.getElementById('reviewNumberGroup');
        const peerReviewTypeGroup = document.getElementById('peerReviewTypeGroup');

        typeSelect.addEventListener('change', function () {
            if (this.value == '1') {
                reviewNumberGroup.style.display = 'block';
                peerReviewTypeGroup.style.display = 'block';
            } else {
                reviewNumberGroup.style.display = 'none';
                peerReviewTypeGroup.style.display = 'none';
            }
        });
    });
</script>
@endsection
