@extends('layouts.app')
@section('title','Explore Projects')
@section('content')
<div class="projects-dashboard">
    <div class="dashboard-header">
        <h2>Explore</h2>
        <div class="dashboard-controls">
            <select id="sort-by" class="form-select form-select-sm ms-2">
                <option value="recent" @if(request('sort')!='trending') selected @endif>Recent</option>
                <option value="trending" @if(request('sort')=='trending') selected @endif>Trending</option>
            </select>
        </div>
    </div>

    <div class="projects-grid">
        @forelse($projects as $project)
            <div class="project-card project-item"
                 data-url="{{ route('projects.show', $project->id) }}"
                 style="cursor:pointer; position:relative;">

                <div class="d-flex align-items-center mb-3">
                    <img
                        src="{{ $project->user->avatar ? asset('storage/'.$project->user->avatar) : asset('storage/profile/default-avatar.jpg') }}"
                        alt="{{ $project->user->name }}"
                        class="explore-avatar me-2"
                        width="36" height="36"
                    />
                    <div class="project-owner">
                        <strong>{{ $project->user->name }}</strong>
                        <span>/{{ $project->title }}</span>
                    </div>
                </div>

                <p class="project-description mb-5">
                    {{ $project->description }}
                </p>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="tech-stack mb-2">
                        @if(!empty(trim($project->html ?? '')))
                            <span title="HTML"><i class="bx bxl-html5"></i> HTML</span>
                        @endif
                        @if(!empty(trim($project->css ?? '')))
                            <span title="CSS"><i class="bx bxl-css3"></i> CSS</span>
                        @endif
                        @if(!empty(trim($project->js ?? '')))
                            <span title="JS"><i class="bx bxl-javascript"></i> JS</span>
                        @endif
                        @if($project->language)
                            <span>{{ $project->language }}</span>
                        @endif
                    </div>

                    <div class="like-area d-flex align-items-center gap-1">
                        <i class="fa-regular fa-heart like-btn"
                           data-id="{{ $project->id }}"
                           title="Like"
                           style="cursor:pointer; font-size:1.2rem;"></i>
                        <span class="like-count" id="like-count-{{ $project->id }}">
                            {{ $project->likes_count ?? 0 }}
                        </span>
                    </div>
                </div>

            </div>
        @empty
            <div class="empty-state">No public projects found.</div>
        @endforelse
    </div>

    <div class="mt-4">{{ $projects->links() }}</div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
</script>

@vite('resources/js/explore.js')
@endsection
