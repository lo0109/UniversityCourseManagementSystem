<!doctype html>
<html lang="en" data-bs-theme="auto">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.122.0">
    <title>@yield('title')</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
      /* Additional styles here */
    </style>
  </head>
  <body>
    <header data-bs-theme="dark">
      <div class="collapse text-bg-dark" id="navbarHeader">
        <div class="container">
          <div class="row">
            <div class="col-sm-8 col-md-7 py-4">
              <h4>About</h4>
              <p class="text-body-secondary">This a website is a platform for teachers and students.</p>
            </div>
            <div class="col-sm-4 offset-md-1 py-4">
              <h4>Contact</h4>
              <ul class="list-unstyled">
                <li><a href="https://github.com/lo0109/UniversityCourseManagementSystem" class="text-white">Github Project Link</a></li>
                <li><a href="https://www.linkedin.com/in/siuwai-lo/" class="text-white">My LinkedIn</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container">
          <a href="/" class="navbar-brand d-flex align-items-center">
            <strong>Course Management</strong>
          </a>
          
          <!-- Navigation Links -->
          <ul class="navbar-nav d-flex flex-row me-auto mb-2 mb-md-0">
            <li class="nav-item">
              <a class="nav-link px-3" href="/">Home</a>
            </li>
            @auth
              <!-- If the user is logged in -->
              <li class="nav-item">
                  <a class="nav-link px-3" href="/courses">Your Courses</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link px-3" href="{{route('courses.courseToAdd')}}">Add Courses</a>
              </li>
            @endauth
          </ul>

          <!-- Authentication Logic -->
          @auth
            <div class="dropdown me-2">
              <a class="btn btn-outline-success dropdown-toggle" href="" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                Hello, {{Auth::user()->name}}
              </a>
              <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <!-- Role Display -->
                <li class="dropdown-item">
                    Role: 
                    @if(Auth::user()->teacher)
                        Teacher
                    @else
                        Student
                    @endif
                </li>

                <!-- Divider for better separation -->
                <li><hr class="dropdown-divider"></li>

                <li><a class="dropdown-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                   Logout
                </a></li>
              </ul>
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
            </form>
          @endauth
          @guest
            <!-- If the user is not authenticated (guest) -->
            <div class="dropdown me-2">
              <a class="btn btn-outline-success" href="{{ route('login') }}" role="button" aria-expanded="false">
                Login
              </a>
              <a class="btn btn-outline-success" href="{{ route('register') }}" role="button" aria-expanded="false">
                Register
              </a>
            </div>
          @endguest

          <!-- Search Form -->
          <form action="/search" method="GET" class="d-flex">
            <input class="form-control me-2" type="search" name="search" placeholder="Search Course">
            <button class="btn btn-outline-success me-2" type="submit">Search</button>
          </form>
        
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main>
      @yield('content')
    </main>

    <!-- Footer -->
    <footer class="text-body-secondary py-5">
      <div class="container">
        <p class="float-end mb-1">
          <a href="">Back to top</a>
        </p>
        <p class="mb-1">Created by Siu Wai Lo</p>
        <p class="mb-0">Last updated on Oct 2024</p>
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
