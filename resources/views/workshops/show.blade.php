@extends('layouts.main')

@section('title', 'Workshop Details')

@section('content')
<div class="container mt-5">
    <h1>Workshop Details</h1>
    <h3>Course: {{ $course->name }} ({{ $course->course_id }})</h3>
    <h4>Workshop Day: 
        @if ($workshop == 1) Monday
        @elseif ($workshop == 2) Tuesday
        @elseif ($workshop == 3) Wednesday
        @elseif ($workshop == 4) Thursday
        @elseif ($workshop == 5) Friday
        @endif
    </h4>

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">List of Students</h5>
            @if ($students->isEmpty())
                <p>No students are enrolled in this workshop.</p>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $enrollment)
                            <tr>
                                <td>{{ $enrollment->student->name ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- Dropdown to enroll a new student -->
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Enroll a New Student</h5>
            <!-- Form to enroll a student -->
            <form action="{{ route('workshops.enroll') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="student_id">Select Student:</label>
                    <select name="student_id" id="student_id" class="form-control" required>
                        <option value="">-- Select a Student --</option>
                        @foreach ($unenrolledStudents as $student)
                            <option value="{{ $student->userID }}">{{ $student->name }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="course_id" value="{{ $course->course_id }}">
                <input type="hidden" name="workshop" value="{{ $workshop }}">
                <button type="submit" class="btn btn-success mt-3">Enroll Student</button>
            </form>
        </div>
    </div>

    <a href="{{ route('courses.show', $course->course_id) }}" class="btn btn-primary mt-3">Back to Course</a>
</div>
@endsection
