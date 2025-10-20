@extends('layouts.app')
@section('title', $project->title)
@section('content')
<div class="container">
    <div class="dashboard-header">
        <h2>{{ $project->title }}</h2>
        <div>By {{ $project->user->name }}</div>
    </div>

    <div class="project-card large">
        <div class="card-body">
            <div class="tech-stack mb-2">
                @if(!empty(trim($project->html ?? ''))) <span><i class="bx bxl-html5"></i> HTML</span> @endif
                @if(!empty(trim($project->css ?? '')))  <span><i class="bx bxl-css3"></i> CSS</span> @endif
                @if(!empty(trim($project->js ?? '')))   <span><i class="bx bxl-javascript"></i> JS</span> @endif
            </div>

            <h4>Preview</h4>
            <div class="border p-3 mb-3">
                {!! $project->html !!}
            </div>

            <h4>Code (read-only)</h4>
            <pre class="p-3 bg-dark text-light"><code>{{ $project->html }}{{ "\n\n/* CSS */\n" }}{{ $project->css }}{{ "\n\n// JS\n" }}{{ $project->js }}</code></pre>

            <h5 class="mt-4">Comments</h5>
            @foreach($project->comments as $c)
                <div class="mb-2"><strong>{{ $c->user->name }}</strong>: {{ $c->body }}</div>
            @endforeach
        </div>
    </div>
</div>
@endsection
