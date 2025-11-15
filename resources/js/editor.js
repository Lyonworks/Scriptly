const userLoggedIn = window.userLoggedIn ?? false;
const csrfToken = window.csrfToken ?? '';
const projectFiles = window.projectFiles ?? {
    'index.html': '',
    'style.css': '',
    'script.js': ''
};

let editor;
let currentOpenFile = 'index.html';
let projectCounter = 1;

// === MONACO EDITOR ===
require.config({
    paths: { 'vs': 'https://cdn.jsdelivr.net/npm/monaco-editor@0.34.1/min/vs' }
});

require(['vs/editor/editor.main'], function () {
    editor = monaco.editor.create(document.getElementById('monaco-editor'), {
        value: projectFiles[currentOpenFile],
        language: 'html',
        theme: 'vs-dark',
        automaticLayout: true
    });

    editor.onDidChangeModelContent(() => {
        projectFiles[currentOpenFile] = editor.getValue();
    });
});

// === ELEMENTS ===
const runButton = document.getElementById('run-action');
const saveButton = document.getElementById('save-action');
const fileItems = document.querySelectorAll('.file-item');
const projectNameInput = document.getElementById('project-name');
const editAction = document.getElementById('edit-action');
const projectLabel = document.getElementById('project-label');
const previewPanel = document.getElementById('live-preview-panel');
const previewIframe = document.getElementById('live-preview');
const maximizeBtn = document.getElementById('maximize-action');
const hidePreviewBtn = document.getElementById('hide-preview-action');
const filterBtns = document.querySelectorAll('.filter-btn');

// === ALERT BOX ===
let alertBox = document.createElement('div');
alertBox.id = "alert-box";
alertBox.className = "d-none mt-2";
alertBox.style.color = "#dc3545";
alertBox.style.fontSize = "0.69rem";
alertBox.style.fontWeight = "500";
projectNameInput?.insertAdjacentElement('afterend', alertBox);

// === Edit Project Name ===
editAction?.addEventListener('click', (e) => {
    e.preventDefault();
    projectNameInput.classList.toggle('d-none');

    if (!projectNameInput.classList.contains('d-none')) {
        projectNameInput.value = projectLabel.textContent.trim();
        projectNameInput.focus();
        projectNameInput.select();
    } else {
        const val = projectNameInput.value.trim() || 'Project';
        projectLabel.textContent = val;
    }
});

projectNameInput?.addEventListener('keydown', (e) => {
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

projectNameInput?.addEventListener('blur', () => {
    const val = projectNameInput.value.trim();
    if (val) projectLabel.textContent = val;
    projectNameInput.classList.add('d-none');
});

// === Run Project ===
runButton?.addEventListener('click', function (e) {
    e.preventDefault();
    previewPanel.classList.remove('d-none');
    setTimeout(() => previewPanel.classList.add('show'), 10);

    const html = projectFiles['index.html'] || '';
    const css = projectFiles['style.css'] || '';
    const js = projectFiles['script.js'] || '';

    const sourceCode = `
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <style>${css}</style>
        </head>
        <body>
            ${html}

            <script>
                window.onerror = function(msg, url, line, col, err) {
                    parent.postMessage({ type: 'error', message: msg + ' (line ' + line + ')' }, '*');
                    return true;
                };

                ['log','warn','error'].forEach(type => {
                    const orig = console[type];
                    console[type] = function(...args) {
                        parent.postMessage({
                            type,
                            message: args.map(a =>
                                typeof a === 'object' ? JSON.stringify(a, null, 2) : a
                            ).join(' ')
                        }, '*');
                        orig.apply(console, args);
                    };
                });
            </script>

            <script id="user-script">
                ${js}
            </script>
        </body>
        </html>
    `;

    const blob = new Blob([sourceCode], { type: 'text/html' });
    const blobUrl = URL.createObjectURL(blob);

    previewIframe.setAttribute(
        'sandbox',
        'allow-scripts allow-same-origin allow-popups allow-modals allow-forms allow-pointer-lock allow-top-navigation-by-user-activation'
    );
    previewIframe.src = blobUrl;
});

// === Hide Preview ===
hidePreviewBtn?.addEventListener('click', (e) => {
    e.preventDefault();
    previewPanel.classList.remove('show');
    setTimeout(() => {
        previewPanel.classList.add('d-none');
        previewIframe.src = "about:blank";
    }, 300);
});

// === Toggle Maximize Preview ===
maximizeBtn?.addEventListener('click', (e) => {
    e.preventDefault();
    const container = document.querySelector('.editor-container');
    if (!container) return;

    container.classList.toggle('preview-maximized');
    const icon = maximizeBtn.querySelector('i');

    if (container.classList.contains('preview-maximized')) {
        icon?.classList.replace('fa-expand', 'fa-compress');
        maximizeBtn.title = "Minimize Preview";
        hidePreviewBtn.style.display = "none";
    } else {
        icon?.classList.replace('fa-compress', 'fa-expand');
        maximizeBtn.title = "Maximize Preview";
        hidePreviewBtn.style.display = "inline-flex";
    }
});

// === Save Project ===
saveButton?.addEventListener('click', async (e) => {
    e.preventDefault();

    if (!userLoggedIn) {
        alert('🚫 Silakan login untuk menyimpan project Anda.');
        window.location.href = '/login';
        return;
    }

    let projectName = projectNameInput.value.trim() || projectLabel.textContent.trim() || 'Project';
    if (!projectName || projectName.startsWith('Project')) {
        projectName = `Project ${projectCounter++}`;
    }

    const payload = {
        _token: csrfToken,
        title: projectName,
        description: "Created using Scriptly",
        html: projectFiles['index.html'],
        css: projectFiles['style.css'],
        js: projectFiles['script.js']
    };

    const isEditing = window.isEditingProject === true || window.isEditingProject === "true";
    const projectSlug = window.projectSlug;
    let url, method;

    if (isEditing && projectSlug) {
        url = `/projects/${projectSlug}/update`;
        method = "PUT";
    } else {
        const checkResponse = await fetch(`/projects/check-name?name=${encodeURIComponent(projectName)}`, {
            headers: { "X-CSRF-TOKEN": csrfToken }
        });
        const checkData = await checkResponse.json();

        if (checkData.exists) {
            alertBox.textContent = `${projectName} already exists in this account`;
            alertBox.classList.remove('d-none');
            return;
        } else {
            alertBox.classList.add('d-none');
        }

        url = window.projectSaveRoute;
        method = "POST";
    }

    const response = await fetch(url, {
        method,
        headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken },
        body: JSON.stringify(payload)
    });

    if (!response.ok) {
        alert("❌ Gagal menyimpan project.");
        return;
    }

    const data = await response.json().catch(() => ({}));
    window.location.href = window.projectListUrl || '/projects';
});

// === Switch File di Sidebar ===
fileItems.forEach(item => {
    item.addEventListener('click', (e) => {
        e.preventDefault();
        switchFile(item.dataset.filename);
    });
});

function switchFile(filename) {
    if (!filename) return;
    fileItems.forEach(i => i.classList.remove('active'));
    document.querySelector(`[data-filename="${filename}"]`)?.classList.add('active');
    currentOpenFile = filename;

    const langMap = { html: 'html', css: 'css', js: 'javascript' };
    const ext = filename.split('.').pop();
    const lang = langMap[ext] || 'plaintext';

    if (editor) {
        const model = monaco.editor.createModel(projectFiles[filename] || '', lang);
        editor.setModel(model);
        editor.focus();
    }
}

// === CUSTOM CONSOLE CAPTURE ===
function appendToConsole(type, message) {
    const consoleBody = document.getElementById('console-body');
    if (!consoleBody) return;

    // 🔍 Cek apakah log terakhir sama dengan yang baru
    const lastLine = consoleBody.lastElementChild;
    if (lastLine && lastLine.dataset.message === message && lastLine.dataset.type === type) {
        let count = parseInt(lastLine.dataset.count || "1", 10) + 1;
        lastLine.dataset.count = count;
        let counter = lastLine.querySelector('.console-counter');
        if (!counter) {
            counter = document.createElement('span');
            counter.classList.add('console-counter');
            lastLine.appendChild(counter);
        }
        counter.textContent = ` (x${count})`;
        return;
    }

    // 🔹 Buat elemen baru untuk log
    const line = document.createElement('div');
    line.classList.add('console-line', `console-${type}`, 'fade-in');
    line.dataset.type = type;
    line.dataset.message = message;
    line.dataset.count = 1;

    const iconMap = {
        log: '<i class="fa-solid fa-circle-dot"></i>',
        warn: '<i class="fa-solid fa-circle-exclamation"></i>',
        error: '<i class="fa-solid fa-triangle-exclamation"></i>'
    };

    line.innerHTML = `${iconMap[type] ?? ''} <span>${message}</span>`;
    consoleBody.appendChild(line);

    // 🔽 Auto-scroll ke bawah
    consoleBody.scrollTop = consoleBody.scrollHeight;
}

// === RECEIVE MESSAGES FROM IFRAME ===
window.addEventListener('message', (event) => {
    if (!event.data) return;

    const { type, message } = event.data;

    if (['log', 'warn', 'error'].includes(type)) {
        appendToConsole(type, message);
    }
});


// === FILTER BUTTONS ===
filterBtns.forEach(btn => btn.addEventListener('click', () => {
    filterBtns.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const type = btn.dataset.type;
    document.querySelectorAll('.console-line').forEach(line => {
        line.style.display = (type === 'all' || line.dataset.type === type) ? 'block' : 'none';
    });
}));

// === CLEAR CONSOLE ===
document.getElementById('clear-console')?.addEventListener('click', () => {
    const consoleBody = document.getElementById('console-body');
    if (consoleBody) consoleBody.innerHTML = '';
});
