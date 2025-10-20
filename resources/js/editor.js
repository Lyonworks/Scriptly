const userLoggedIn = window.userLoggedIn;
const csrfToken = window.csrfToken;
const projectFiles = window.projectFiles;
let editor;
let currentOpenFile = 'index.html';
let projectCounter = 1;

require.config({
    paths: {
        'vs': 'https://cdn.jsdelivr.net/npm/monaco-editor@0.34.1/min/vs'
    }
});

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

// --- ALERT ELEMENT (notif merah)
let alertBox = document.createElement('div');
alertBox.id = "alert-box";
alertBox.className = "d-none mt-2";
alertBox.style.color = "#dc3545";
alertBox.style.fontSize = "0.69rem";
alertBox.style.fontWeight = "500";
projectNameInput.insertAdjacentElement('afterend', alertBox);

// === Edit project name ===
editAction?.addEventListener('click', function (e) {
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

projectNameInput?.addEventListener('keydown', function (e) {
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

projectNameInput?.addEventListener('blur', function () {
    const val = projectNameInput.value.trim();
    if (val) projectLabel.textContent = val;
    projectNameInput.classList.add('d-none');
});

function switchFile(filename) {
    if (!editor || !projectFiles[filename]) return;

    currentOpenFile = filename;
    currentFilenameElement.textContent = filename;

    editor.getModel().setValue(projectFiles[filename]);
    const language = filename.endsWith('.css')
        ? 'css'
        : filename.endsWith('.js')
        ? 'javascript'
        : 'html';
    monaco.editor.setModelLanguage(editor.getModel(), language);

    fileItems.forEach(item => {
        item.classList.toggle('active', item.dataset.filename === filename);
    });
}

runButton?.addEventListener('click', function (e) {
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

saveButton?.addEventListener('click', async function (e) {
    e.preventDefault();

    if (!userLoggedIn) {
        alert('🚫 Silakan login untuk menyimpan project Anda.');
        window.location.href = '/login';
        return;
    }

    let projectName = projectNameInput.value.trim() || projectLabel.textContent.trim();

    // === Jika kosong → auto beri nama Project 1, 2, dst ===
    if (!projectName || projectName.startsWith('Project')) {
        projectName = `Project ${projectCounter}`;
        projectCounter++;
    }

    // 🔍 Periksa dulu ke server apakah nama project sudah ada
    const checkResponse = await fetch(`/projects/check-name?name=${encodeURIComponent(projectName)}`, {
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        }
    });
    const checkData = await checkResponse.json();

    if (checkData.exists) {
        alertBox.textContent = `${projectName} already exists in this account`;
        alertBox.classList.remove('d-none');
        alertBox.classList.add('small');
        return;
    } else {
        alertBox.classList.add('d-none');
    }

    // === Kirim ke server ===
    const payload = {
        _token: csrfToken,
        title: projectName,
        description: "Created using Scriptly",
        html: projectFiles['index.html'],
        css: projectFiles['style.css'],
        js: projectFiles['script.js']
    };

    fetch(window.projectSaveRoute, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify(payload),
    })
    .then(async response => {
        const text = await response.text();
        let data;
        try {
            data = JSON.parse(text);
        } catch {
            console.warn("Non-JSON response:", text);
            alert("⚠️ Terjadi kesalahan: mungkin kamu belum login atau server error.");
            return;
        }

        if (response.ok) {
            window.location.href = window.projectListUrl;
        } else {
            alert(data.message || '❌ Gagal menyimpan project.');
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
