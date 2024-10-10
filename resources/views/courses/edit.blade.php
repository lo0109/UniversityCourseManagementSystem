@extends('layouts.main')

@section('title', 'Edit Course')

@section('content')
<div class="container mt-5">
    <h1>Edit Course</h1>

    <form action="{{ route('courses.update', $course->course_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Course Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $course->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" required>{{ old('description', $course->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="online" class="form-label">Online</label>
            <select class="form-select" id="online" name="online" required>
                <option value="1" {{ $course->online ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ !$course->online ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="workshop" class="form-label">Workshops Per Week</label>
            <input type="number" class="form-control" id="workshop" name="workshop" value="{{ old('workshop', $course->workshop) }}" min="1" max="5" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Course</button>
    </form>

    <a href="{{ route('courses.show', $course->course_id) }}" class="btn btn-secondary mt-3">Cancel</a>
</div>
@endsection
