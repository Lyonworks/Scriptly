@extends('layouts.app')

@section('title', 'Code Editor')

@section('content')
<div class="editor-container">
    {{-- Sidebar --}}
    <aside class="file-panel">
        <div class="mb-4">
            <div class="flex justify-between items-center">
                <div class="project-title-wrap d-flex align-items-center">
                    <h3 class="me-5 mb-0">
                        <span id="project-label" class="font-semibold text-lg">Project</span>
                    </h3>
                    <a href="#" id="edit-action" class="icon-btn" title="Edit" aria-label="Edit">
                        <i class="fa-solid fa-file-pen"></i>
                    </a>
                </div>
            </div>
            <input type="text" id="project-name" class="d-none form-control mt-2" value="{{ $projectName ?? 'Project 1' }}" placeholder="Name Project..." />
        </div>
        <ul class="file-tree">
            <li class="file-item active" data-filename="index.html">
                <span class="file-name">index.html</span>
                <span class="file-type"><i class="bx bxl-html5"></i></span>
            </li>
            <li class="file-item" data-filename="style.css">
                <span class="file-name">style.css</span>
                <span class="file-type"><i class="bx bxl-css3"></i></span>
            </li>
            <li class="file-item" data-filename="script.js">
                <span class="file-name">script.js</span>
                <span class="file-type"><i class="bx bxl-javascript"></i></span>
            </li>
        </ul>
    </aside>

    {{-- Editor Panel --}}
    <section class="code-editor-panel">
        <div class="panel-header">
            <h4 id="current-filename">index.html</h4>
            <div class="panel-actions ">
                <a id="run-action" class="icon-btn" title="Run" aria-label="Run">
                    <i class="fa-solid fa-play"></i>
                </a>
                <a id="save-action" class="icon-btn" title="Save" aria-label="Save">
                    <i class="fa-solid fa-floppy-disk "></i>
                </a>
            </div>
        </div>
        <div id="monaco-editor" class="code-editor"></div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.34.1/min/vs/loader.js"></script>

<script>
    window.userLoggedIn = @json(Auth::check());
    window.csrfToken = "{{ csrf_token() }}";
    window.projectFiles = {!! json_encode($projectFiles ?? [
        'index.html' => "<h1>Hello, Scriptly!</h1>\n<p>Click Run to see the preview.</p>",
        'style.css'  => "body {\n  background-color: #1a1a1a;\n  font-family: sans-serif;\n  color: #eee;\n  text-align:center;\n  margin-top:50px;\n}",
        'script.js'  => "console.log(\"Welcome to Scriptly!\");"
    ], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!};
    window.projectCheckNameUrl = "{{ route('projects.checkName') }}";
    window.projectSaveRoute = "{{ route('projects.save') }}";
    window.projectListUrl = "{{ route('projects.index') }}";
    window.csrfToken = "{{ csrf_token() }}";
    window.userLoggedIn = @json(Auth::check());
</script>

@vite('resources/js/editor.js')
@endsection
