@extends('layouts.admin')
@section('title','Admin Dashboard')

@section('content')
  <h1 class="fw-bold mb-4">Welcome to Admin Dashboard</h1>

  <div class="row">
    
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
