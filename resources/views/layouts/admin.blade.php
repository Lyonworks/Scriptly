<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title','Admin Panel')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  @vite('resources/css/app.css')
</head>
<body >

  <div class="d-flex">
    {{-- Sidebar --}}
    <div class="text-white p-3 min-vh-100" style="width: 240px; background: #2F4F4F;">
      <h4 class="mb-3 text-center">
        <a href="/" class="text-decoration-none text-white fw-bold">ExploreNusa</a>
      </h4>
      <hr class="border-light opacity-50 mb-4">
      <ul class="nav flex-column">
        <li class="nav-item mb-2">
          <a href="/admin/dashboard" class="nav-link text-white"><img src="{{ asset('storage/icons/bars-solid-full.svg') }}" alt="Users" width="22" height="22" class="me-2"> Dashboard</a>
        </li>
        @if(Auth::check() && Auth::user()->role_id == 1)
          <li class="nav-item mb-2">
            <a href="{{ route('admin.users.index') }}" class="nav-link text-white">
              <img src="{{ asset('storage/icons/user-solid-full.svg') }}" alt="Users" width="22" height="22" class="me-2"> Users
            </a>
          </li>
        @endif
        <li class="nav-item mb-2">
          <a href="/admin/destinations" class="nav-link text-white"><img src="{{ asset('storage/icons/location-dot-solid-full.svg') }}" width="22" height="22" class="me-2"> Destinations</a>
        </li>
        <li class="nav-item mb-2">
          <a href="/admin/facilities" class="nav-link text-white"><img src="{{ asset('storage/icons/map-pin-solid-full.svg') }}" alt="Users" width="22" height="22" class="me-2"> Facilities</a>
        </li>
        <li class="nav-item mb-2">
          <a href="/admin/trending" class="nav-link text-white"><img src="{{ asset('storage/icons/fire-solid-full.svg') }}" width="22" height="22" class="me-2"> Trending Tours</a>
        </li>
        <li class="nav-item mb-2">
          <a href="/admin/top" class="nav-link text-white"><img src="{{ asset('storage/icons/star-solid-full.svg') }}" width="22" height="22" class="me-2"> Top Destinations</a>
        </li>
        <li class="nav-item mb-2">
          <a href="/admin/reviews" class="nav-link text-white"><img src="{{ asset('storage/icons/comment-solid-full.svg') }}" width="22" height="22" class="me-2"> Reviews</a>
        </li>
        <li class="nav-item mb-2">
          <a href="/admin/blogs" class="nav-link text-white"><img src="{{ asset('storage/icons/newspaper-solid-full.svg') }}" width="22" height="22" class="me-2"> Blogs</a>
        </li>
      </ul>
    </div>

    {{-- Konten utama --}}
    <main class="p-4 w-100">

      {{-- Profil User yang login --}}
      @if(Auth::check())
      <div class="d-flex justify-content-between align-items-center text-white mb-4 p-3 rounded shadow-sm" style="background: #2F4F4F;">
        <div>
          <h5 class="mb-0">👋 Hai, {{ Auth::user()->name }}</h5>
          <small>Role:
            @if(Auth::user()->role_id == 1)
              Superadmin
            @elseif(Auth::user()->role_id == 2)
              Admin
            @endif
          </small>
        </div>
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button class="btn btn-sm btn-danger">Logout</button>
        </form>
      </div>
      @endif

      {{-- Konten halaman --}}
      @yield('content')
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  @stack('scripts')
</body>
</html>
