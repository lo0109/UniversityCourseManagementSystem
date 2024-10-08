@extends('layouts.main')

@section('content')
    <div class="container">
        <h1>Your Courses</h1>

        @if ($courses->isEmpty())
            <p>No courses found.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Course ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Online</th>
                        <th>How many workshop a week</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($courses as $course)
                        <tr>
                            <td>{{ $course->course_id }}</td>
                            <td>{{ $course->name }}</td>
                            <td>{{ $course->description }}</td>
                            <td>{{ $course->online ? 'Yes' : 'No' }}</td>
                            <td>{{ $course->workshop }}</td>
                            <td>
                                <!-- View button to open the course details page -->
                                <a href="{{ route('courses.show', $course->course_id) }}" class="btn btn-info">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
