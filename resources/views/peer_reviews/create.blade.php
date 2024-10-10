<!-- resources/views/peer_reviews/create.blade.php -->

@extends('layouts.main')

@section('title', 'Create Peer Review Group')

@section('content')
<div class="container mt-5">
    <h1>Create a New Peer Review Group for {{ $assessment->title }}</h1>

    <form action="{{ route('peer_reviews.store', ['assessment_id' => $assessment->id]) }}" method="POST">
        @csrf
        <div class="form-group mt-3">
            <label>Select Students to Add to the Group:</label>
            @if($students->isEmpty())
                <h3>No students available for selection</h3>
            @else
                <div class="form-check">
                    @foreach ($students as $student)
                        <input type="checkbox" name="students[]" value="{{ $student->userID }}" class="form-check-input" id="student_{{ $student->userID }}">
                        <label class="form-check-label" for="student_{{ $student->userID }}">
                            {{ $student->name }}
                        </label>
                        <br>
                    @endforeach
                </div>
            @endif
        </div>

        <button type="submit" class="btn btn-primary mt-3" {{ $students->isEmpty() ? 'disabled' : '' }}>Create Group</button>
    </form>

    <a href="{{ route('assessments.show', $assessment->id) }}" class="btn btn-secondary mt-3">Back to Assessment</a>
</div>
@endsection
