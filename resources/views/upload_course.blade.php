@extends('layouts.main')

@section('title', 'Upload Course')

@section('content')
<div class="container mt-5">
    <h1>Upload Course Information</h1>
    <form action="{{ route('courses.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="course_file">Course JSON File:</label>
            <input type="file" name="course_file" id="course_file" class="form-control" required accept=".json">
        </div>
        <button type="submit" class="btn btn-primary mt-3">Upload Course</button>
    </form>

    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
    @endif
</div>
@endsection
