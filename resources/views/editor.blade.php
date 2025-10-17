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
                <a href="#" id="run-action" class="icon-btn" title="Run" aria-label="Run">
                    <i class="fa-solid fa-play"></i>
                </a>
                <a href="#" id="save-action" class="icon-btn" title="Save" aria-label="Save">
                    <i class="fa-solid fa-floppy-disk "></i>
                </a>
            </div>
        </div>
        <div id="monaco-editor" class="code-editor"></div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.34.1/min/vs/loader.js"></script>

<script>
    const userLoggedIn = @json(Auth::check());
    const csrfToken = "{{ csrf_token() }}";

    const projectFiles = {!! json_encode($projectFiles ?? [
        'index.html' => "<h1>Hello, Scriptly!</h1>\n<p>Click Run to see the preview.</p>",
        'style.css'  => "body {\n  background-color: #1a1a1a;\n  font-family: sans-serif;\n  color: #eee;\n  text-align:center;\n  margin-top:50px;\n}",
        'script.js'  => "console.log(\"Welcome to Scriptly!\");"
    ], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!};

    let editor;
    let currentOpenFile = 'index.html';
    let projectCounter = 1;

    require.config({ paths: { 'vs': 'https://cdn.jsdelivr.net/npm/monaco-editor@0.34.1/min/vs' }});

    require(['vs/editor/editor.main'], function () {
        editor = monaco.editor.create(document.getElementById('monaco-editor'), {
            value: projectFiles[currentOpenFile],
            language: 'html',
            theme: 'vs-dark',
            automaticLayout: true
        });

        editor.getModel().onDidChangeContent(() => {
            projectFiles[currentOpenFile] = editor.getValue();
        });
    });

    const runButton = document.getElementById('run-action');
    const saveButton = document.getElementById('save-action');
    const fileItems = document.querySelectorAll('.file-item');
    const currentFilenameElement = document.getElementById('current-filename');
    const projectNameInput = document.getElementById('project-name');
    const editAction = document.getElementById('edit-action');
    const projectLabel = document.getElementById('project-label');

    editAction?.addEventListener('click', function(e){
        e.preventDefault();
        projectNameInput.classList.toggle('d-none');
        if (!projectNameInput.classList.contains('d-none')) {
            projectNameInput.value = projectLabel.textContent.trim();
            projectNameInput.focus();
            projectNameInput.select();
        } else {
            const val = projectNameInput.value.trim() || projectLabel.textContent.trim() || 'Project';
            projectLabel.textContent = val;
        }
    });

    projectNameInput?.addEventListener('keydown', function(e){
        if (e.key === 'Enter') {
            e.preventDefault();
            const val = projectNameInput.value.trim() || 'Project';
            projectLabel.textContent = val;
            projectNameInput.classList.add('d-none');
        } else if (e.key === 'Escape') {
            projectNameInput.classList.add('d-none');
            projectNameInput.value = projectLabel.textContent.trim();
        }
    });

    projectNameInput?.addEventListener('blur', function(){
        const val = projectNameInput.value.trim();
        if (val) projectLabel.textContent = val;
        projectNameInput.classList.add('d-none');
    });

    function switchFile(filename) {
        if (!editor || !projectFiles[filename]) return;

        currentOpenFile = filename;
        currentFilenameElement.textContent = filename;

        editor.getModel().setValue(projectFiles[filename]);
        const language = filename.endsWith('.css') ? 'css' : (filename.endsWith('.js') ? 'javascript' : 'html');
        monaco.editor.setModelLanguage(editor.getModel(), language);

        fileItems.forEach(item => {
            item.classList.toggle('active', item.dataset.filename === filename);
        });
    }

    runButton?.addEventListener('click', function(e){
        e.preventDefault();

        const html = projectFiles['index.html'] || '';
        const css = projectFiles['style.css'] || '';
        const js = projectFiles['script.js'] || '';

        const sourceCode = `
            <html>
                <head>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    <style>${css}</style>
                </head>
                <body>
                    ${html}
                    <script>${js}<\/script>
                </body>
            </html>
        `;

        const newTab = window.open('', '_blank');
        if (newTab) {
            newTab.document.open();
            newTab.document.write(sourceCode);
            newTab.document.close();
            newTab.document.title = 'Preview - Scriptly';
        } else {
            const dataUrl = 'data:text/html;charset=utf-8,' + encodeURIComponent(sourceCode);
            window.location.href = dataUrl;
        }
    });

    saveButton?.addEventListener('click', function(e){
        e.preventDefault();

        if (!userLoggedIn) {
            alert('🚫 Silakan login untuk menyimpan project Anda.');
            return;
        }

        let projectName = projectNameInput.value.trim();

        if (!projectName || projectName === '') {
            projectCounter++;
            projectName = `Project ${projectCounter}`;
            projectNameInput.value = projectName;
        }

        const payload = {
            _token: csrfToken,
            title: projectName,
            html: projectFiles['index.html'],
            css: projectFiles['style.css'],
            js: projectFiles['script.js']
        };

        fetch("{{ route('projects.save') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify(payload),
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(`✅ ${data.message}\n💾 Nama Project: ${projectName}`);
            } else {
                alert('❌ Gagal menyimpan project.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('⚠️ Terjadi kesalahan saat menyimpan.');
        });
    });

    fileItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            const filename = item.dataset.filename;
            switchFile(filename);
        });
    });
</script>
@endsection
