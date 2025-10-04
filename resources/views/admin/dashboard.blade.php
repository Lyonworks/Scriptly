@extends('layouts.admin')
@section('title','Admin Dashboard')

@section('content')
  <h1 class="fw-bold mb-4">Welcome to Admin Dashboard</h1>

  <div class="row">
    <!-- Recent Activities -->
    <div class="col-lg-8 mb-4">
      <div class="card shadow-sm h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span class="fw-bold">📜 Recent Activities</span>
        </div>
        <div class="card-body" style="max-height: 500px; overflow-y: auto;">
          <ul class="list-group list-group-flush">
            @forelse($activities as $activity)
              <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                  <div class="fw-bold">
                    {{ $activity->user?->name ?? 'Guest' }}
                    <small class="text-muted">({{ ucfirst($activity->action) }})</small>
                  </div>
                  <small>{{ $activity->description }}</small>
                </div>
                <span class="text-muted small">{{ $activity->created_at->diffForHumans() }}</span>
              </li>
            @empty
              <li class="list-group-item text-center text-muted">No recent activities</li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>

    <!-- Widgets - kanan -->
    <div class="col-lg-4 mb-4">
      <div class="row g-4">
        <!-- Users -->
        <div class="col-6">
          <div class="card text-center shadow-sm h-100">
            <div class="card-body">
              <h5 class="card-title">👤 Users</h5>
              <h2 class="fw-bold">{{ \App\Models\User::count() }}</h2>
              <small class="text-muted">Registered</small>
            </div>
          </div>
        </div>

        <!-- Destinations -->
        <div class="col-6">
          <div class="card text-center shadow-sm h-100">
            <div class="card-body">
              <h5 class="card-title">🌍 Destinations</h5>
              <h2 class="fw-bold">{{ \App\Models\Destination::count() }}</h2>
              <small class="text-muted">Total</small>
            </div>
          </div>
        </div>

        <!-- Blogs -->
        <div class="col-6">
          <div class="card text-center shadow-sm h-100">
            <div class="card-body">
              <h5 class="card-title">📝 Blogs</h5>
              <h2 class="fw-bold">{{ \App\Models\Blog::count() }}</h2>
              <small class="text-muted">Total</small>
            </div>
          </div>
        </div>

        <!-- Reviews -->
        <div class="col-6">
          <div class="card text-center shadow-sm h-100">
            <div class="card-body">
              <h5 class="card-title">💬 Reviews</h5>
              <h2 class="fw-bold">{{ \App\Models\Review::count() }}</h2>
              <small class="text-muted">Total</small>
            </div>
          </div>
        </div>

        <!-- Calendar + Clock -->
        <div class="col-12">
          <div class="card text-center shadow-sm h-100">
            <div class="card-body">
              <h2 id="clock" class="fw-bold mb-2"></h2>
              <small id="date" class="text-muted"></small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Script Jam & Kalender --}}
  <script>
    function updateClock() {
      const now = new Date();
      const clock = document.getElementById("clock");
      const date = document.getElementById("date");

      const optionsDate = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

      clock.textContent = now.toLocaleTimeString();
      date.textContent = now.toLocaleDateString('en-US', optionsDate);
    }

    setInterval(updateClock, 1000);
    updateClock();
  </script>
@endsection
