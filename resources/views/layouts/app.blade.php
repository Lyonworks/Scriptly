<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scriptly - @yield('title')</title>

    {{-- Hapus link CSS lama jika ada, ganti dengan ini --}}
    @vite('resources/css/app.css')

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    {{-- Font dan Ikon Anda --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
</head>
<body>
    {{-- Kita akan menggunakan class container dari Bootstrap --}}
    <div class="container">
        {{-- Header Anda sebelumnya masih bisa digunakan --}}
        <header class="main-header">
            <div class="logo">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2L2 7V17L12 22L22 17V7L12 2Z" fill="#8A2BE2"/><path d="M12 12L22 7" stroke="white" stroke-width="2"/><path d="M12 12V22" stroke="white" stroke-width="2"/><path d="M12 12L2 7" stroke="white" stroke-width="2"/><path d="M16 4.5L6 9.5" stroke="white" stroke-width="1.5"/></svg>
                <h1>Scriptly</h1>
            </div>
            <nav class="main-nav">
                <a href="{{ url('/') }}">Home</a>
                <a href="{{ url('/editor') }}">Editor</a>
                <a href="{{ url('/projects') }}">Projects</a>
            </nav>
            <div class="header-actions">
                @if(request()->is('editor'))
                    <button class="btn btn-secondary">Run</button>
                    <button class="btn btn-secondary">Save</button>
                    <button class="btn btn-primary">Share</button>
                @elseif(request()->is('projects'))
                     <button class="btn btn-primary">Share</button>
                @else
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-primary">Sign In / Up</a>
                    @else
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://i.pravatar.cc/40?u={{ Auth::id() }}" alt="User Avatar" width="32" height="32" class="rounded-circle me-2">
                                <strong>{{ Auth::user()->name }}</strong>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end text-small shadow">
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                <li><a class="dropdown-item" href="#">Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                @if(in_array(Auth::user()->role_id, [1, 2]))
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                                @endif
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Sign out</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endguest
                @endif
            </div>
        </header>

        <main class="mt-4">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
