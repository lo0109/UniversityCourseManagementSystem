@extends('layouts.main')

@section('title', 'Home')

@section('content')
<div class="container mt-5">
    <h1>Welcome to Course Management</h1>
    <p>This platform allows you to manage courses, enroll in them, and interact with teachers and students.</p>

    <div class="row mt-4">
        <div class="col-md-4">
            <h3>Manage Courses</h3>
            <p>As a teacher, you can create, update, and delete courses easily.</p>
        </div>
        <div class="col-md-4">
            <h3>Enroll in Courses</h3>
            <p>As a student, you can browse and enroll in available courses to learn new skills.</p>
        </div>
        <div class="col-md-4">
            <h3>Interactive Learning</h3>
            <p>Our platform encourages interactive learning between students and teachers.</p>
        </div>
    </div>

    @auth
        <div class="mt-4">
            <a href="/courses" class="btn btn-primary">Go to My Courses</a>
        </div>
    @else
        <div class="mt-4">
            <a href="{{ route('register') }}" class="btn btn-success">Register Now</a>
            <a href="{{ route('login') }}" class="btn btn-outline-success">Login</a>
        </div>
    @endauth
</div>
@endsection
