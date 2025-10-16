@extends('layouts.app')
@section('title', 'Your Projects')
@section('content')
<div class="projects-dashboard">
    <div class="dashboard-header">
        <h2>Your Projects</h2>
        <div class="dashboard-controls">
            {{-- Arahkan tombol ini ke halaman pembuatan proyek --}}
            <a href="{{ route('projects.create') }}" class="btn btn-primary">+ New Project</a>
        </div>
    </div>

    {{-- Tampilkan pesan sukses jika ada --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="projects-grid">
        {{-- Ganti loop statis dengan loop dinamis @forelse --}}
        @forelse ($projects as $project)
            <div class="project-card">
                <div class="card-header">
                    <img src="{{ $project->thumbnail_url ?? 'https://via.placeholder.com/50' }}" alt="Project thumbnail" class="project-thumbnail">
                    <button class="options-btn">&hellip;</button>
                </div>
                <h4>{{ $project->title }}</h4>
                {{-- Tampilkan tanggal dengan format yang mudah dibaca --}}
                <p>Created: {{ $project->created_at->diffForHumans() }}</p>
                <div class="tech-stack">
                    {{-- Loop melalui array tech_stack --}}
                    @if(is_array($project->tech_stack))
                        @foreach ($project->tech_stack as $tech)
                            <span>{{ $tech }}</span>
                        @endforeach
                    @endif
                </div>
            </div>
        @empty
            {{-- Tampilan ini akan muncul jika pengguna belum punya proyek --}}
            <div class="empty-state">
                <h3>No projects yet!</h3>
                <p>Click "+ New Project" to get started.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
