@extends('layouts.main')

@section('title', 'Course Details')

@section('content')
<div class="container mt-5">
    <h1>{{ $course->name }}</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Course ID: {{ $course->course_id }}</h5>
            <p class="card-text">{{ $course->description }}</p>
            <p><strong>Online: </strong>{{ $course->online ? 'Yes' : 'No' }}</p>
            <p><strong>How many Workshop a week: </strong>{{ $course->workshop }}</p>

            <!-- Workshop section -->
            @auth
                @if (Auth::user()->teacher)
                    <p><strong>Workshop: </strong>{{ $course->workshop }}</p>
                @else
                    <p><strong>Workshop: </strong>
                        @if($enrollment)
                            <!-- If the student is already enrolled, show the workshop number and day -->
                            {{ $enrollment->pivot->workshop }} - 
                            @if ($enrollment->pivot->workshop == 1) Monday 
                            @elseif ($enrollment->pivot->workshop == 2) Tuesday
                            @elseif ($enrollment->pivot->workshop == 3) Wednesday
                            @elseif ($enrollment->pivot->workshop == 4) Thursday
                            @elseif ($enrollment->pivot->workshop == 5) Friday
                            @endif
                        @else
                            <!-- Show dropdown to select workshop if not already enrolled -->
                            <form action="{{ route('courses.addCourses', $course->course_id) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="workshop">Select Workshop Day:</label>
                                    <select name="workshop" id="workshop" class="form-control" required>
                                        @if($course->workshop >= 1)
                                            <option value="1">Monday</option>
                                        @endif
                                        @if($course->workshop >= 2)
                                            <option value="2">Tuesday</option>
                                        @endif
                                        @if($course->workshop >= 3)
                                            <option value="3">Wednesday</option>
                                        @endif
                                        @if($course->workshop >= 4)
                                            <option value="4">Thursday</option>
                                        @endif
                                        @if($course->workshop >= 5)
                                            <option value="5">Friday</option>
                                        @endif
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-success mt-3">Enroll</button>
                            </form>
                        @endif
                    </p>
                @endif
            @endauth
        </div>
    </div>

    <!-- Assessment section: Always visible -->
    <h2 class="mt-5">Assessments</h2>
    @if($assessments->isEmpty())
        <p>No assessments available for this course.</p>
    @else
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Assessment ID</th>
                    <th>Title</th>
                    <th>Max Score</th>
                    <th>Due Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assessments as $assessment)
                    <tr>
                        <td>{{ $assessment->id }}</td>
                        <td>{{ $assessment->title }}</td>
                        <td>{{ $assessment->maxScore }}</td>
                        <td>{{ $assessment->deadline }}</td>
                        <td>
                            <!-- Show "View" button only if the student is enrolled -->
                            @if($enrollment)
                                <a href="{{ route('assessments.view', $assessment->id) }}" class="btn btn-info">View</a>
                            @else
                                <span class="text-muted">Enroll to view</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{route('courses.index')}}" class="btn btn-primary mt-3">Back to Courses</a>
</div>
@endsection
