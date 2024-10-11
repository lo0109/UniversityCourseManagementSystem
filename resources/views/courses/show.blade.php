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
            <p><strong>Teacher: </strong>{{ $course->teacher->name ?? 'No teacher assigned'}}</p>
            <p><strong>How many Workshops a week: </strong>{{ $course->workshop }}</p>
            
            <!-- Display the number of people enrolled in the course -->
            <p><strong>Number of Students Enrolled: </strong>{{ $course->enrollments->count() }}</p>

            <!-- Workshop section -->
            @auth
                @if (Auth::user()->teacher)
                    <h3 class="mt-4">Workshops</h3>
                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th>Workshop Day</th>
                                <th>Number of Students</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 1; $i <= $course->workshop; $i++)
                                <tr>
                                    <td>
                                        @if ($i == 1) Monday 
                                        @elseif ($i == 2) Tuesday
                                        @elseif ($i == 3) Wednesday
                                        @elseif ($i == 4) Thursday
                                        @elseif ($i == 5) Friday
                                        @endif
                                    </td>
                                    <td>{{ $course->enrollments->where('workshop', $i)->count() }} students</td>
                                    <td>
                                        <!-- Only show View button if the user is the course's teacher -->
                                        @if ($course->teacherID == Auth::user()->userID)
                                            
                                        <!-- THIS ONE NOT WORKING ??? <a href="{{ route('workshops.show', ['course' => $course->course_id, 'workshop' => $i]) }}" class="btn btn-info">View</a> -->
                                            <a href="{{ url('workshops/show') }}?course={{ $course->course_id }}&workshop={{ $i }}" class="btn btn-info">View</a>
                                            @else
                                            <span class="text-muted">Not available</span>
                                        @endif
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table> 

                    <!-- Display Edit or Teach This Course button -->
                    @if ($course->teacherID == Auth::user()->userID)
                        <a href="{{ route('courses.edit', $course->course_id) }}" class="btn btn-warning mt-3">Edit</a>
                    @elseif (is_null($course->teacherID))
                        <form action="{{ route('courses.teach', $course->course_id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success mt-3">Teach this course</button>
                        </form>
                    @endif
                @else
                    <p><strong>Workshop: </strong>
                        @if ($enrollment)
                            <!-- Show workshop number, day, and number of students if already enrolled -->
                            {{ $enrollment->pivot->workshop }} - 
                            @if ($enrollment->pivot->workshop == 1) Monday 
                            @elseif ($enrollment->pivot->workshop == 2) Tuesday
                            @elseif ($enrollment->pivot->workshop == 3) Wednesday
                            @elseif ($enrollment->pivot->workshop == 4) Thursday
                            @elseif ($enrollment->pivot->workshop == 5) Friday
                            @endif
                            ({{ $course->enrollments->where('workshop', $enrollment->pivot->workshop)->count() }} students)
                        @else
                            <!-- Show radio buttons to select workshop if not already enrolled -->
                            <form action="{{ route('courses.addCourses', $course->course_id) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>Select Workshop Day:</label>
                                    @for ($i = 1; $i <= $course->workshop; $i++)
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input" name="workshop" value="{{ $i }}" required>
                                            <label class="form-check-label">
                                                @if ($i == 1) Monday 
                                                @elseif ($i == 2) Tuesday
                                                @elseif ($i == 3) Wednesday
                                                @elseif ($i == 4) Thursday
                                                @elseif ($i == 5) Friday
                                                @endif
                                                ({{ $course->enrollments->where('workshop', $i)->count() }} students)
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                                <button type="submit" class="btn btn-success mt-3">Enroll</button>
                            </form>
                        @endif
                    </p>
                @endif
            @endauth
        </div>
    </div>

    <!-- Button for adding a new assessment -->
    @auth
        @if (Auth::user()->teacher && $course->teacherID == Auth::user()->userID)
            <!-- <a href="{{ route('assessments.create', ['course' => $course->course_id]) }}" class="btn btn-success mt-4">Add New Assessment</a> -->
            <a href="{{ url('courses/' . $course->course_id . '/assessments/create') }}" class="btn btn-success mt-4">Add New Assessment</a>
        @endif
    @endauth

    <!-- Assessment section: Always visible -->
    <h2 class="mt-5">Assessments</h2>
    @if ($assessments->isEmpty())
        <p>No assessments available for this course.</p>
    @else
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Assessment Type</th>
                    <th>Title</th>
                    <th>Max Mark</th>
                    <th>Due Date</th>
                    <th>Mark</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($assessments as $assessment)
                    <tr>
                        <td>{{ $assessment->type->type ?? 'N/A' }}</td>
                        <td>{{ $assessment->title }}</td>
                        <td>{{ $assessment->maxScore }}</td>
                        <td>{{ $assessment->deadline }}</td>
                        <td>
                            @if (Auth::check() && !Auth::user()->teacher)
                                <!-- Show the student's mark for the assessment -->
                                @php
                                    $studentMark = $assessment->assessmentMarks->where('student_id', Auth::user()->userID)->first();
                                @endphp
                                {{ $studentMark ? $studentMark->score . ' / ' . $assessment->maxScore : 'Not graded' }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if ($enrollment || Auth::user()->teacher)
                                <a href="{{ route('assessments.show', $assessment->id) }}" class="btn btn-info">View</a>
                            @else
                                <span class="text-muted">Enroll to view</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('courses.index') }}" class="btn btn-primary mt-3">Back to Courses</a>
</div>
@endsection
