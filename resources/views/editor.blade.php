@extends('layouts.app')

@section('title', 'Code Editor')

@section('content')
<div class="editor-container">
    <aside class="file-panel">
        <h3>Project Files</h3>
        <ul class="file-tree">
            <li><i class="fas fa-folder"></i> Project</li>
            <li class="active"><i class="fab fa-html5"></i> index.html</li>
            <li><i class="fab fa-css3-alt"></i> style.css</li>
            <li><i class="fab fa-js"></i> script.js</li>
            <li><i class="fab fa-html5"></i> index.html</li>
        </ul>
    </aside>

    <section class="code-editor-panel">
        <div class="panel-header">
            <h4>Code Editor</h4>
            <div>
                <button class="btn btn-secondary btn-sm">Run</button>
                <button class="btn btn-secondary btn-sm">Save</button>
            </div>
        </div>
        <div class="code-editor">
            <pre><code>// Welcome to Scriptly!
// Your real-time preview will appear on the right.

function setup() {
    createCanvas(400, 400);
}

function draw() {
    if (mouseIsPressed) {
        fill(0);
    } else {
        fill(255);
    }
    ellipse(mouseX, mouseY, 80, 80);
}
            </code></pre>
        </div>
    </section>

    <aside class="live-preview-panel">
        <div class="panel-header">
            <h4>Live Preview</h4>
            <button class="btn-icon">Resize</button>
        </div>
        <div class="preview-content">
            <img src="https://i.imgur.com/9O0Z3gC.png" alt="Live Preview Output" style="width: 100%; height: auto;">
        </div>
    </aside>
</div>
@endsection
