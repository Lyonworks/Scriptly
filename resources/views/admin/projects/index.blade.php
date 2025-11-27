@extends('layouts.admin')
@section('title', 'Project Management')

@section('content')
<div class="dashboard space-y-6">
  <h1 class="dashboard-title">Project Management</h1>

  {{-- Statistik --}}
  <div class="stats-grid">
    <div class="stat-card">
      <h3>Total Projects</h3>
      <p>{{ number_format($totalProjects) }}</p>
    </div>
    <div class="stat-card">
      <h3>Public Projects</h3>
      <p>{{ number_format($publicProjects) }}</p>
    </div>
    <div class="stat-card">
      <h3>Private Projects</h3>
      <p>{{ number_format($privateProjects) }}</p>
    </div>
    <div class="stat-card">
      <h3>Deleted Projects</h3>
      <p>{{ number_format($deletedProjects) }}</p>
    </div>
  </div>

  {{-- Search --}}
  <div class="search-bar flex items-center gap-2 mb-4">
    <form method="GET" class="mb-4 d-flex align-items-center" style="gap: 10px;">
        <div class="flex-grow-1 position-relative">
            <i class="fa fa-search position-absolute" style="left: 12px; top: 50%; transform: translateY(-50%); color: #aaa;"></i>
            <input type="text" name="search" class="form-control ps-5" placeholder="Search by title or author" value="{{ request('search') }}">
        </div>
        <button class="btn btn-primary px-4">Search</button>
    </form>
  </div>

  {{-- Table --}}
  <div class="tables-card shadow-sm rounded-4 p-3">
    <h3 class="mb-3 fw-semibold">📄 Project List</h3>
    <div class="table-responsive">
      <table class="table table-card text-white align-middle mb-0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Language</th>
            <th>Visibility</th>
            <th>Created At</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($projects as $project)
          <tr>
            <td>{{ $project->id }}</td>
            <td class="fw-semibold">{{ $project->title }}</td>
            <td>{{ $project->user->name }}</td>
            <td>{{ $project->languages }}</td>
            <td>
              <span class="badge {{ $project->is_public ? 'bg-success' : 'bg-secondary' }}">
                {{ $project->is_public ? 'Public' : 'Private' }}
              </span>
            </td>
            <td>{{ $project->created_at->format('d M Y') }}</td>
            <td>
              <a href="{{ route('admin.projects.show', $project) }}" class="btn btn-sm btn-info">
                <i class="fa fa-eye"></i>
              </a>

              <form action="{{ route('admin.projects.toggle', $project) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-warning">
                  {{ $project->is_public ? 'Make Private' : 'Make Public' }}
                </button>
              </form>

              <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this project?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">
                  <i class="fa fa-trash"></i>
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="7" class="text-center">No projects found</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="mt-3">
      {{ $projects->links() }}
    </div>
  </div>
</div>
@endsection
