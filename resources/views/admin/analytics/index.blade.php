@extends('layouts.admin')
@section('title', 'Analytics & Logs')

@section('content')
<div class="dashboard space-y-6">
  <h1 class="dashboard-title">Analytics & Logs</h1>

  {{-- Statistik Ringkas --}}
  <div class="stats-grid">
    <div class="stat-card">
      <h3>Total Executions</h3>
      <p>{{ number_format($totalExecutions) }}</p>
    </div>
    <div class="stat-card">
      <h3>Average Execution Time</h3>
      <p>{{ number_format($avgExecutionMs, 2) }}s</p>
    </div>
    <div class="stat-card">
      <h3>Total Logins</h3>
      <p>{{ number_format($totalLogins) }}</p>
    </div>
    <div class="stat-card">
      <h3>API Calls</h3>
      <p>{{ number_format($apiCalls) }}</p>
    </div>
  </div>

  {{-- Chart --}}
  <div class="charts-grid">
    <div class="chart-card">
      <h3>📊 Code Executions Per Day</h3>
      <canvas id="executionChart"></canvas>
    </div>
    <div class="chart-card">
      <h3>🔥 User Activity (Weekly Heatmap)</h3>
      <canvas id="activityHeatmap"></canvas>
    </div>
  </div>

  {{-- Activity Logs --}}
  <div class="activity-card">
    <h3 class="card-title">🧾 Activity Logs</h3>
    <ul class="activity-list">
      @foreach($recentActivities as $log)
      <li>
        <span>{!! $log->description !!}</span>
        <small>{{ $log->created_at->diffForHumans() }}</small>
      </li>
      @endforeach
    </ul>
  </div>

  {{-- Error Logs --}}
  <div class="activity-card">
    <h3 class="card-title text-danger">❌ Error Logs</h3>
    <ul class="activity-list text-danger">
      @forelse($recentErrors as $error)
      <li>
        <span>{{ $error->message }}</span>
        <small>{{ $error->created_at->diffForHumans() }}</small>
      </li>
      @empty
      <li>No recent errors 🎉</li>
      @endforelse
    </ul>
  </div>
</div>

{{-- Script Chart.js --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const executionChart = new Chart(document.getElementById('executionChart'), {
  type: 'line',
  data: {
    labels: {!! json_encode($executionLabels) !!},
    datasets: [{
      label: 'Executions',
      data: {!! json_encode($executionCounts) !!},
      borderColor: '#3B82F6',
      backgroundColor: 'rgba(59,130,246,0.3)',
      fill: true,
      tension: 0.4
    }]
  },
  options: { responsive: true, plugins: { legend: { display: false } } }
});

// Heatmap simulasi (bisa diganti library lain)
const heatmap = new Chart(document.getElementById('activityHeatmap'), {
  type: 'bar',
  data: {
    labels: {!! json_encode($weekDays) !!},
    datasets: [{
      label: 'Activity Count',
      data: {!! json_encode($activityCounts) !!},
      backgroundColor: '#F59E0B'
    }]
  },
  options: { responsive: true, plugins: { legend: { display: false } } }
});
</script>
@endpush
@endsection
