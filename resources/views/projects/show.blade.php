@extends('layouts.app')
@section('title', $project->title)
@section('content')
<div class="projects-dashboard">
    <div class="dashboard-header">
        <h2>{{ $project->title }}</h2>
        <div class="d-flex align-items-center">
            <img
                src="{{ $project->user->avatar ? asset('storage/'.$project->user->avatar) : asset('storage/profile/default-avatar.jpg') }}"
                alt="{{ $project->user->name }}"
                class="explore-avatar me-2"
                width="36" height="36"
            />
            <div class="project-owner">
                <strong>{{ $project->user->name }}</strong>
            </div>
        </div>
    </div>

    <div class="project-card large">
        <div class="card-body">
            {{-- Tech Stack --}}
            <div class="tech-stack mb-4">
                @if(!empty(trim($project->html ?? ''))) <span><i class="bx bxl-html5"></i> HTML</span> @endif
                @if(!empty(trim($project->css ?? '')))  <span><i class="bx bxl-css3"></i> CSS</span> @endif
                @if(!empty(trim($project->js ?? '')))   <span><i class="bx bxl-javascript"></i> JS</span> @endif
            </div>

            {{-- Description / Readme --}}
            @if(!empty($project->description))
            <div class="project-description mb-4">
                <h4>Description</h4>
                <div class="description-content">
                    {!! nl2br(e($project->description)) !!}
                </div>
            </div>
            @endif

            {{-- Live Preview --}}
            <h4 class="mb-2">Live Preview</h4>
            <iframe id="project-preview" class="project-preview"></iframe>

            {{-- Code --}}
            <h4 class="mt-4">Code (read-only)</h4>
            <pre class="project-code"><code>{{ $project->html }}{{ "\n\n/* CSS */\n" }}{{ $project->css }}{{ "\n\n// JS\n" }}{{ $project->js }}</code></pre>

            {{-- Comments --}}
            <h4 class="mt-4">Comments</h4>
            @foreach($project->comments as $c)
                <div class="mb-2"><strong>{{ $c->user->name }}</strong>: {{ $c->body }}</div>
            @endforeach
        </div>
    </div>
</div>

{{-- Script untuk mengisi iframe --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const html = @json($project->html ?? '');
    const css  = @json($project->css ?? '');
    const js   = @json($project->js ?? '');

    const previewFrame = document.getElementById('project-preview');
    const previewDoc = previewFrame.contentDocument || previewFrame.contentWindow.document;

    const content = `
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <style>${css}</style>
        </head>
        <body>
            ${html}
            <script>${js}<\/script>
        </body>
        </html>
    `;

    previewDoc.open();
    previewDoc.write(content);
    previewDoc.close();
});
</script>
@endsection
