@extends('layouts.admin')
@section('title', 'Admin Dashboard')

@section('content')
<div class="dashboard space-y-6">
  <h1 class="dashboard-title">Dashboard Overview</h1>

  {{-- Statistik Ringkas --}}
  <div class="stats-grid">
    <div class="stat-card">
      <h3>Total Users</h3>
      <p>{{ number_format($totalUsers) }}</p>
    </div>
    <div class="stat-card">
      <h3>Total Projects</h3>
      <p>{{ number_format($totalProjects) }}</p>
    </div>
    <div class="stat-card">
      <h3>Code Executions</h3>
      <p>{{ number_format($totalExecutions) }}</p>
    </div>
    <div class="stat-card">
      <h3>Public Projects</h3>
      <p>{{ number_format($totalPublic) }}</p>
    </div>
  </div>

  {{-- Aktivitas Terakhir --}}
  <div class="activity-card">
    <h3 class="card-title">⏱️ Recent Activity</h3>
    <ul class="activity-list">
      @foreach($recentActivities as $activity)
      <li>
        <span>{!! $activity->description !!}</span>
        <small>{{ $activity->created_at->diffForHumans() }}</small>
      </li>
      @endforeach
    </ul>
  </div>

  {{-- Grafik --}}
  <div class="charts-grid">
    <div class="chart-card">
      <h3>📈 User Growth per Month</h3>
      <canvas id="userChart"></canvas>
    </div>
    <div class="chart-card">
      <h3>🚀 Project Growth per Month</h3>
      <canvas id="projectChart"></canvas>
    </div>
  </div>
</div>

{{-- Script Chart.js --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const userChart = new Chart(document.getElementById('userChart'), {
  type: 'line',
  data: {
    labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
    datasets: [{
      label: 'Users',
      data: {!! json_encode(array_values($userGrowth)) !!},
      borderColor: '#60A5FA',
      backgroundColor: 'rgba(96,165,250,0.3)',
      fill: true,
      tension: 0.4
    }]
  },
  options: { responsive: true, plugins: { legend: { display: false } } }
});

const projectChart = new Chart(document.getElementById('projectChart'), {
  type: 'bar',
  data: {
    labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
    datasets: [{
      label: 'Projects',
      data: {!! json_encode(array_values($userGrowth)) !!},
      backgroundColor: '#34D399'
    }]
  },
  options: { responsive: true, plugins: { legend: { display: false } } }
});
</script>
@endpush
@endsection
