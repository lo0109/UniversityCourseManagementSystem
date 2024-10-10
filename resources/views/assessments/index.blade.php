@extends('layouts.app')

@section('content')
    <h1>Assessments</h1>

    <a href="{{ route('assessments.create') }}" class="btn btn-primary">Add New Assessment</a>

    @if($assessments->isEmpty())
        <p>No assessments available.</p>
    @else
        <ul>
            @foreach($assessments as $assessment)
                <li>
                    <strong>{{ $assessment->title }}</strong> - {{ $assessment->course->name }}<br>
                    <a href="{{ route('assessments.show', $assessment->id) }}">View</a> |
                    <a href="{{ route('assessments.edit', $assessment->id) }}">Edit</a> |
                    <form action="{{ route('assessments.destroy', $assessment->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
