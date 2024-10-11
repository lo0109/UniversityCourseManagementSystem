@extends('layouts.main')

@section('title', 'Add Courses')

@section('content')
    <div class="container mt-5">
        <h1>Add Courses</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($coursesToAdd->isEmpty())
            <p>No courses available to add.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Course ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Online</th>
                        <th>Workshop per week</th>
                        <th>Course Detail</th> 
                    </tr>
                </thead>
                <tbody>
                    @foreach($coursesToAdd as $course)
                        <tr>
                            <td>{{ $course->course_id }}</td>
                            <td>{{ $course->name }}</td>
                            <td>{{ $course->description }}</td>
                            <td>{{ $course->online ? 'Yes' : 'No' }}</td>
                            <td>{{ $course->workshop }}</td>
                            <td>
                                <!-- View button that sends the user to the course detail page -->
                                <a href="{{ route('courses.show', $course->course_id) }}" class="btn btn-info">View</a>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        <!-- Display pagination links -->
        {{ $coursesToAdd->links() }}
    </div>
@endsection
