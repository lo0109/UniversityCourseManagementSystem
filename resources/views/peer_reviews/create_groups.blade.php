@extends('layouts.main')

@section('title', 'Create Peer Review Groups')

@section('content')
<div class="container mt-5">
    <h1>Create Groups for {{ $assessment->title }} - Workshop {{ $workshop }}</h1>
    @if ($students->isEmpty())
        <p>No students found for this workshop.</p>
    @else
        <!-- Display the number of students instead of listing their names -->
        <p><strong>Number of Students:</strong> {{ $students->count() }}</p>
    @endif
    <form action="{{ route('peer_reviews.store_groups', ['assessment_id' => $assessment->id, 'workshop' => $workshop]) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="group_size">Group Size: (min.: {{$assessment->reviewNumber +1}} )</label>
            <input type="number" name="group_size" id="group_size" class="form-control" min="{{$assessment->reviewNumber+1}}" required>
        </div>

        <div class="form-group mt-3">
            <button type="submit" class="btn btn-primary">Create Groups</button>
        </div>
    </form>
    <a href="{{ route('assessments.show', $assessment->id) }}" class="btn btn-secondary mt-3">Back to Assessment</a>
</div>
@endsection
