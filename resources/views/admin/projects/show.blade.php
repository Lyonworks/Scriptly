@extends('layouts.admin')

@section('title', 'Project Detail')

@section('content')
<div class="stat-card shadow-sm rounded-4 p-4">

    {{-- Header --}}
    <h2 class="fw-bold mb-1">{{ $project->title }}</h2>
    <p class="fs-6 mb-3">Project ID: #{{ $project->id }}</p>

    {{-- Info Grid --}}
    <div class="info-grid">
        <div class="info-card">
            <strong>Author:</strong><br>
            {{ $project->user?->name ?? 'Unknown User' }}
        </div>

        <div class="info-card">
            <strong>Visibility:</strong><br>
            <span class="badge {{ $project->is_public ? 'bg-success' : 'bg-secondary' }}">
                {{ $project->is_public ? 'Public' : 'Private' }}
            </span>
        </div>

        <div class="info-card">
            <strong>Created At:</strong><br>
            {{ $project->created_at->format('d M Y, H:i') }}
        </div>

        <div class="info-card">
            <strong>Last Update:</strong><br>
            {{ $project->updated_at->format('d M Y, H:i') }}
        </div>

    </div>

    {{-- Code Preview --}}
    <h3 class="fw-bold mb-2">Code Preview:</h3>
    <pre class="project-code"><code>{{ $project->html }}{{ "\n\n/* CSS */\n" }}{{ $project->css }}{{ "\n\n// JS\n" }}{{ $project->js }}</code></pre>

    {{-- Tombol Kembali --}}
    <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary mt-4">
        <i class="fa fa-arrow-left me-1"></i> Back
    </a>

</div>
@endsection
