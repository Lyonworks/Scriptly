@extends('layouts.app')

@section('title', 'Your Projects')

@section('content')
<div class="projects-dashboard">
    <div class="dashboard-header">
        <h2>Your Projects</h2>
        <div class="dashboard-controls">
            <input type="search" placeholder="Search...">
            <span>Sort by: Recently Modified</span>
            <button class="btn btn-primary">+ New Project</button>
        </div>
    </div>

    <div class="projects-grid">
        <div class="project-card">
            <div class="card-header">
                <img src="https://i.imgur.com/s6nC3jW.png" alt="Project thumbnail" class="project-thumbnail">
                 <button class="options-btn">&hellip;</button>
            </div>
            <h4>React Todo App</h4>
            <p>Created: 2 days ago</p>
            <div class="tech-stack">
                <span>HTML</span>
                <span>CSS</span>
                <span>JS</span>
            </div>
        </div>
         <div class="project-card">
            <div class="card-header">
                <img src="https://i.imgur.com/mKeUm1Q.png" alt="Project thumbnail" class="project-thumbnail">
                <button class="options-btn">&hellip;</button>
            </div>
            <h4>JS Game: Space Rocks</h4>
            <p>Created: 2 days ago</p>
            <div class="tech-stack">
                <span>HTML</span>
                <span>CSS</span>
                <span>JS</span>
            </div>
        </div>
         <div class="project-card">
            <div class="card-header">
                <img src="https://i.imgur.com/uG9G1jB.png" alt="Project thumbnail" class="project-thumbnail">
                <button class="options-btn">&hellip;</button>
            </div>
            <h4>JS Gemal Blog Template</h4>
            <p>Created: 3 days ago</p>
            <div class="tech-stack">
                <span>HTML</span>
                <span>CSS</span>
                <span>JS</span>
            </div>
        </div>
        @for ($i = 0; $i < 6; $i++)
        <div class="project-card">
             <div class="card-header">
                 <img src="https://via.placeholder.com/50" alt="Project thumbnail" class="project-thumbnail">
                 <button class="options-btn">&hellip;</button>
            </div>
            <h4>Sample Project {{ $i + 1 }}</h4>
            <p>Created: {{ $i + 1 }} days ago</p>
            <div class="tech-stack">
                <span>HTML</span>
                <span>CSS</span>
            </div>
        </div>
        @endfor
    </div>
</div>
@endsection
