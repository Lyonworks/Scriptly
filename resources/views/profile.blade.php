@extends('layouts.app')
@section('title', 'Your Projects')
@section('content')
<div class="profile-container">

    <aside class="profile-sidebar">
        <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : asset('storage/profile/default-avatar.jpg') }}" class="profile-avatar">

        @if(request()->has('edit'))
            <div class="profile-info profile-edit">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf @method('PUT')
                    <div class="mb-2">
                        <label class="form-label label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                    </div>
                    <div class="mb-2">
                        <label class="form-label label">Bio</label>
                        <textarea type="text" name="bio" class="form-control">{{ old('bio', $user->bio) }}</textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label label">Location</label>
                        <input type="text" name="location" class="form-control" value="{{ old('location', $user->location) }}">
                    </div>
                    <div class="mb-2">
                        <label class="form-label label">Link 1</label>
                        <input type="url" name="link1" class="form-control" value="{{ old('link1', $user->link1) }}">
                    </div>
                    <div class="mb-2">
                        <label class="form-label label">Link 2</label>
                        <input type="url" name="link2" class="form-control" value="{{ old('link2', $user->link2) }}">
                    </div>
                    <div class="mb-2">
                        <label class="form-label label">Link 3</label>
                        <input type="url" name="link3" class="form-control" value="{{ old('link3', $user->link3) }}">
                    </div>
                    <div class="gap-2 mt-4">
                        <button type="submit" class="btn btn-success">Save</button>
                        <a href="{{ url()->current() }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        @else
            <div class="profile-info" id="profile-view">
                <h3>{{ $user->name }}</h3>
                <p class="mt-3">{{ $user->bio }}</p>
                <p>{{ $user->location }}</p>
                <p>{{ $user->link1 }}</p>
                <p>{{ $user->link2 }}</p>
                <p>{{ $user->link3 }}</p>
            </div>
        @endif

        <button class="btn btn-primary mt-5" id="edit-profile-btn">Edit Profile</button>
    </aside>

    <main class="projects-content">
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
                <div class="project-card" style="position:relative;">
                    <a href="{{ route('editor') }}?id={{ $project->id }}" class="edit-project-btn" aria-label="Edit project">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>

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
    </main>

</div>
<script>
document.getElementById('edit-profile-btn')?.addEventListener('click', function(e){
    e.preventDefault();
    const url = new URL(window.location.href);
    url.searchParams.set('edit','1');
    window.location.href = url.toString();
});
</script>
@endsection
