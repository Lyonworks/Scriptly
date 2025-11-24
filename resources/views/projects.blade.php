@extends('layouts.app')
@section('title', 'Your Projects')
@section('content')
<div class="projects-dashboard">
    <div class="dashboard-header">
        <h2>Projects</h2>
        <div class="dashboard-controls">
            <a href="{{ route('editor') }}" class="btn btn-primary">+ New Project</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

        <div class="projects-grid">
            @forelse ($projects as $project)
                <div class="project-card"
                     style="position:relative; cursor:pointer;"
                     onclick="window.location='{{ route('projects.show', $project->slug) }}'">
                    <div class="dropstart position-absolute top-0 end-0 mt-2 me-2">
                        <a class="setting-btn" data-bs-toggle="dropdown" aria-expanded="false" aria-label="setting project">
                            <i class="fa-solid fa-ellipsis"></i>
                        </a>

                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('projects.edit', $project->slug) }}">
                                    <i class="fa-solid fa-pen me-2"></i> Edit
                                </a>
                            </li>
                            <li>
                                <form action="{{ route('projects.delete', $project->slug) }}" method="POST" onsubmit="return confirm('Are you sure want to delete this project?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="dropdown-item">
                                        <i class="fa-solid fa-trash me-2"></i> Delete
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>

                    <h4>{{ $project->title }}</h4>
                    <p>Created: {{ $project->created_at->diffForHumans() }}</p>

                    <div class="tech-stack" aria-hidden="false">
                        @if(!empty(trim($project->html ?? '')))
                            <span title="HTML"><i class="bx bxl-html5"></i> HTML</span>
                        @endif
                        @if(!empty(trim($project->css ?? '')))
                            <span title="CSS"><i class="bx bxl-css3"></i> CSS</span>
                        @endif
                        @if(!empty(trim($project->js ?? '')))
                            <span title="JavaScript"><i class="bx bxl-javascript"></i> JS</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <h3>No projects yet!</h3>
                    <p>Click "+ New Project" to get started.</p>
                </div>
            @endforelse
        </div>
    </div>
    @endsection
