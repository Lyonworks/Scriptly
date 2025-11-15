@extends('layouts.app')

@section('title', 'Code Editor')

@section('content')
<div class="editor-container">

    {{-- HEADER PANEL --}}
    <div class="editor-header">
        <div class="left-section">
            <h3>
                <span id="project-label">{{ $projectName ?? 'Project' }}</span>
                <input type="text" id="project-name" class="d-none" placeholder="Name Project..." />
                <a href="#" id="edit-action" class="icon-btn" title="Edit"><i class="fa-solid fa-file-pen"></i></a>
            </h3>
        </div>

        <div class="right-section mb-2">
            <a id="run-action" class="icon-btn" title="Run"><i class="fa-solid fa-play"></i></a>
            <a id="save-action" class="icon-btn" title="Save"><i class="fa-solid fa-floppy-disk"></i></a>
        </div>
    </div>

    {{-- MAIN PANEL --}}
    <div class="editor-main">
        {{-- Sidebar --}}
        <aside class="file-panel">
            <ul class="file-tree">
                <li class="file-item active" data-filename="index.html"><span>index.html</span><i class="bx bxl-html5"></i></li>
                <li class="file-item" data-filename="style.css"><span>style.css</span><i class="bx bxl-css3"></i></li>
                <li class="file-item" data-filename="script.js"><span>script.js</span><i class="bx bxl-javascript"></i></li>
            </ul>
        </aside>

        {{-- Code Editor --}}
        <section class="code-editor-panel">
            <div id="monaco-editor"></div>
        </section>

        {{-- Live Preview --}}
        <section class="live-preview-panel d-none" id="live-preview-panel">
            <div class="preview-toolbar">
                <a id="maximize-action" class="prev-btn" title="Maximize Preview"><i class="fa-solid fa-expand"></i></a>
                <a id="hide-preview-action" class="prev-btn me-4" title="Hide Preview"><i class="fa-solid fa-eye-slash"></i></a>
            </div>
            <iframe id="live-preview"></iframe>
        </section>
    </div>

    {{-- CONSOLE PANEL --}}
    <div class="console-panel" id="console-panel">
        <div class="console-header">
            <span><i class="fa-solid fa-terminal"></i> Console</span>
            <div class="console-filters">
                <button class="filter-btn active" data-type="all">All</button>
                <button class="filter-btn" data-type="log">Log</button>
                <button class="filter-btn" data-type="warn">Warn</button>
                <button class="filter-btn" data-type="error">Error</button>
            </div>
            <button id="clear-console" title="Clear Console"><i class="fa-solid fa-broom"></i></button>
        </div>
        <div class="console-body" id="console-body"></div>
    </div>
</div>

{{-- === SCRIPT CONFIG === --}}
<script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.34.1/min/vs/loader.js"></script>
<script>
    window.userLoggedIn = @json(Auth::check());
    window.csrfToken = "{{ csrf_token() }}";
    window.projectFiles = {!! json_encode($projectFiles ?? [
        'index.html' => "<h1>Hello, Scriptly!</h1>\n<p>Click Run to see the preview.</p>",
        'style.css'  => "body {\n  background-color: #1a1a1a;\n  color: #eee;\n  text-align:center;\n  margin-top:50px;\n}",
        'script.js'  => "console.log('Welcome to Scriptly!');"
    ], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!};
    window.projectSaveRoute = "{{ route('projects.save') }}";
    window.projectListUrl = "{{ route('projects.index') }}";
    window.isEditingProject = {{ isset($project) ? 'true' : 'false' }};
    window.projectSlug = "{{ $project->slug ?? '' }}";
</script>

@vite(['resources/css/editor.css', 'resources/js/editor.js'])
@endsection
