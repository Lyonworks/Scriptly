<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scriptly - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body>
    <div class="container">
        <header class="main-header">
            <div class="logo">
                <img src="{{ asset('storage/assets/scriptly.png') }}" alt="Scriptly Logo" height="40">
            </div>
            <nav class="main-nav">
                <a href="{{ url('/') }}">Home</a>
                <a href="{{ url('/explore') }}">Explore</a>
                <a href="{{ url('/editor') }}">Editor</a>
                <a href="{{ url('/projects') }}">Projects</a>
            </nav>
            <div class="header-actions">
                @guest
                        <a href="{{ route('login') }}" class="btn btn-primary">Sign In / Up</a>
                    @else
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-light text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : asset('storage/profile/default-avatar.jpg') }}" alt="User Avatar" width="32" height="32" class="rounded-circle me-2">
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end text-small shadow">
                                <li class="dropdown-item-text"><strong>{{ Auth::user()->name }}</strong></li>
                                <li><a class="dropdown-item" href="/profile">Profile</a></li>
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
                        </div   >
                    @endguest
            </div>
        </header>

        <main class="mt-4">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
