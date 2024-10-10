@extends('layouts.main')

@section('title', 'Assessment Details')

@section('content')
<div class="container mt-5">
    <h1>{{ $assessment->title }}</h1>
    <div class="card">
        <div class="card-body">
            <p><strong>Assessment type: </strong>{{ $assessment->type->type ?? 'N/A' }}</p>
            <p><strong>Max Score: </strong>{{ $assessment->maxScore }}</p>
            <p><strong>Due Date: </strong>{{ $assessment->deadline }}</p>
            <p><strong>Instructions: </strong>{{ $assessment->instruction }}</p>
            <p><strong>Number of reviews required: </strong>{{ $assessment->reviewNumber }}</p>
            <p><strong>Peer review type: </strong>{{ $assessment->peerReviewType->type ?? 'N/A' }}</p>
        </div>
        @if (Auth::check() && Auth::user()->teacher)
            <div class="card-footer">
                <a href="{{ route('assessments.edit', $assessment->id) }}" class="btn btn-info">Edit</a>
                <form action="{{ route('assessments.destroy', $assessment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this assessment?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        @endif
    </div>

    @auth
        <div class="mt-5">
            <h2>Peer Review Groups</h2>
            @if (Auth::user()->teacher)
                <!-- Teacher view: Show all workshops and groups -->
                @if(isset($workshops) && !$workshops->isEmpty())
                    @foreach($workshops as $workshop => $groups)
                        <h2>Workshop {{ $workshop }}</h2>
                        @if($groups->isEmpty())
                            <p>No groups found for this workshop.</p>
                        @else
                            <ul>
                                @foreach($groups as $group)
                                    <li>
                                        Group {{ $group['group'] }} ({{ $group['student_count'] }} members)
                                        <a href="{{ route('peer_reviews.group_detail', ['assessment_id' => $assessment->id, 'group' => $group['group']]) }}" class="btn btn-sm btn-primary">View</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    @endforeach
                @else
                    <p>No peer review groups found for this assessment.</p>
                @endif
            @else
                <!-- Student view: Show only the student's workshop groups -->
                @if (isset($workshops) && !$workshops->isEmpty() && isset($studentWorkshop))
                    <h2>Your Workshop: {{ $studentWorkshop }}</h2>
                    <ul>
                        @foreach ($workshops as $group)
                            <li>
                                Group {{ $group['group'] }} ({{ $group['student_count'] }} members)
                                <a href="{{ route('peer_reviews.group_detail', ['assessment_id' => $assessment->id, 'group' => $group['group']]) }}" class="btn btn-sm btn-primary">View</a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p>No peer review groups found for your workshop.</p>
                    <a href="{{ route('peer_reviews.create', ['assessment_id' => $assessment->id]) }}" class="btn btn-sm btn-primary">
                        Create Group
                    </a>
                @endif
            @endif
        </div>
    @endauth

    <a href="{{ route('courses.show', $assessment->course_id) }}" class="btn btn-primary mt-3">Back to Course</a>
</div>
@endsection
