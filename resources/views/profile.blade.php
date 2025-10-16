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
        <h2>My Projects</h2>

        <div class="projects-grid">

            <div class="project-card large">
                <img src="https://i.imgur.com/mKeUm1Q.png" alt="Project Thumbnail" class="card-thumbnail">
                <div class="card-body">
                    <h3 class="card-title">JS Game: Space Rocks</h3>
                    <ul class="description-list">
                        <li>Interactive gameplay with physics.</li>
                        <li>Score tracking and high scores.</li>
                        <li>Built with pure JavaScript.</li>
                    </ul>
                    <a href="#" class="btn-enter">Enter</a>
                </div>
                <i class='bx bx-dots-horizontal-rounded card-options'></i>
            </div>

            <div class="project-card">
                <div class="card-header">
                    <img src="https://i.imgur.com/s6nC3jW.png" alt="Project Thumbnail" class="card-thumbnail">
                    <div>
                        <h4 class="card-title">JS Game: Space Rocks</h4>
                        <p class="card-date">Created: 2 days ago</p>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="tech-stack">
                        <span>HTML</span><span>CSS</span><span>JS</span>
                    </div>
                </div>
                <i class='bx bx-dots-horizontal-rounded card-options'></i>
            </div>

            <div class="project-card">
                <div class="card-header">
                    <img src="https://i.imgur.com/gL8m6vY.png" alt="Project Thumbnail" class="card-thumbnail">
                    <div>
                        <h4 class="card-title">React Todo App</h4>
                        <p class="card-date">Created: 2 days ago</p>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="tech-stack">
                        <span>HTML</span><span>CSS</span><span>JS</span>
                    </div>
                </div>
                <i class='bx bx-dots-horizontal-rounded card-options'></i>
            </div>

            <div class="project-card">
                <div class="card-header">
                    <img src="https://i.imgur.com/gL8m6vY.png" alt="Project Thumbnail" class="card-thumbnail">
                    <div>
                        <h4 class="card-title">React Todo App</h4>
                        <p class="card-date">Created: 2 days ago</p>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="tech-stack">
                        <span>HTML</span><span>CSS</span><span>JS</span>
                    </div>
                </div>
                <i class='bx bx-dots-horizontal-rounded card-options'></i>
            </div>

            <div class="project-card">
                <div class="card-header">
                     <img src="https://i.imgur.com/uG9G1jB.png" alt="Project Thumbnail" class="card-thumbnail">
                    <div>
                        <h4 class="card-title">CSS Art: Octpulse</h4>
                        <p class="card-date">Created: 2 days ago</p>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="tech-stack">
                        <span>HTML</span><span>CSS</span><span>JS</span>
                    </div>
                </div>
                <i class='bx bx-dots-horizontal-rounded card-options'></i>
            </div>

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
