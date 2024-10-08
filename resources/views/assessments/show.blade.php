@extends('layouts.main')

@section('title', 'Assessment Details')

@section('content')
<div class="container mt-5">
    <h1>{{ $assessment->title }}</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>Max Score: </strong>{{ $assessment->maxScore }}</p>
            <p><strong>Due Date: </strong>{{ $assessment->deadline }}</p>
            <p><strong>Instructions: </strong>{{ $assessment->instruction }}</p>
        </div>
    </div>

    <a href="{{ url()->previous() }}" class="btn btn-primary mt-3">Back to Course</a>
</div>
@endsection
