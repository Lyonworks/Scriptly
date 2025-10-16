@extends('layouts.app')

@section('title', 'Code Editor')

@section('content')
<div class="editor-container">
    <aside class="file-panel">
        <h3>Project Files</h3>
        <ul class="file-tree">
            <li class="file-item active" data-filename="index.html"><i class='bx bxl-html5'></i> index.html</li>
            <li class="file-item" data-filename="style.css"><i class='bx bxl-css3'></i> style.css</li>
            <li class="file-item" data-filename="script.js"><i class='bx bxl-javascript'></i> script.js</li>
        </ul>
    </aside>

    <section class="code-editor-panel">
        <div class="panel-header">
            <h4 id="current-filename">index.html</h4>
            <div class="panel-actions">
                <a href="#" id="run-action" class="icon-btn" title="Run" aria-label="Run">
                    <i class="fa-solid fa-play"></i>
                </a>
                <a href="#" id="save-action" class="icon-btn ms-2" title="Save" aria-label="Save">
                    <i class="fa-solid fa-floppy-disk"></i>
                </a>
            </div>
        </div>
        <div id="monaco-editor" class="code-editor"></div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.34.1/min/vs/loader.js"></script>

<script>
    const projectFiles = {!! json_encode($projectFiles ?? [
        'index.html' => "<h1>Hello, Scriptly!</h1>\n<p>Click Run to see the preview.</p>",
        'style.css'  => "body {\n  background-color: #f0f0f0;\n  font-family: sans-serif;\n  color: #333;\n}",
        'script.js'  => "console.log(\"Welcome to the Scriptly editor!\");"
    ], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!};

    let editor;
    let currentOpenFile = 'index.html';

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
        console.log('Save requested', projectFiles);
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
