document.addEventListener('DOMContentLoaded', function () {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || window.csrfToken || '';

    // Show
    document.addEventListener('DOMContentLoaded', () => {
        // Klik card => buka halaman detail
        document.querySelectorAll('.project-item').forEach(card => {
            card.addEventListener('click', e => {
                // Cegah klik icon ikut trigger card
                if (e.target.closest('.icon-actions')) return;
                window.location.href = card.dataset.url;
            });
        });

        // Toggle komentar
        document.querySelectorAll('.comment-toggle').forEach(btn => {
            btn.addEventListener('click', e => {
                e.stopPropagation();
                const id = btn.dataset.id;
                document.querySelector(`#comments-${id}`).classList.toggle('d-none');
            });
        });
    });


    // like buttons
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', async function (e) {
            e.stopPropagation();

            const id = this.dataset.id;

            try {
                const res = await fetch(`/projects/${id}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                });

                const data = await res.json().catch(() => null);

                if (!res.ok || !data) {
                    alert(data?.message || 'Terjadi kesalahan di server.');
                    return;
                }

                // Update jumlah like
                const countEl = document.getElementById(`like-count-${id}`);
                if (countEl) countEl.textContent = data.likes_count;

                // Toggle style + warna
                this.classList.toggle('liked', data.liked);
                this.classList.toggle('fa-solid', data.liked);
                this.classList.toggle('fa-regular', !data.liked);

            } catch (err) {
                console.error('Error:', err);
                alert('Gagal menyukai proyek. Coba lagi nanti.');
            }
        });
    });

    // fork buttons
    document.querySelectorAll('[id^="fork-"]').forEach(btn => {
        btn.addEventListener('click', async function () {
            const id = this.dataset.id;
            const res = await fetch(`/projects/${id}/fork`, {
                method: 'POST',
                headers: {'Content-Type':'application/json','X-CSRF-TOKEN':csrf},
            });
            const data = await res.json();
            if (res.ok) {
                window.location.href = '/projects';
            } else {
                alert(data.message || 'Error');
            }
        });
    });

    // comment toggle & post
    document.querySelectorAll('.comment-toggle').forEach(t => {
        t.addEventListener('click', () => {
            const id = t.dataset.id;
            document.getElementById('comments-' + id).classList.toggle('d-none');
        });
    });

    document.querySelectorAll('.post-comment').forEach(b => {
        b.addEventListener('click', async () => {
            const id = b.dataset.id;
            const textarea = document.querySelector('.comment-input[data-id="'+id+'"]');
            if (!textarea) return;
            const body = textarea.value.trim();
            if (!body) return alert('Comment cannot be empty');
            const res = await fetch(`/projects/${id}/comment`, {
                method: 'POST',
                headers: {'Content-Type':'application/json','X-CSRF-TOKEN':csrf},
                body: JSON.stringify({body})
            });
            const data = await res.json();
            if (res.ok) {
                // simple reload to show new comment; you can append dynamically instead
                window.location.reload();
            } else {
                alert(data.message || 'Error posting comment');
            }
        });
    });

    // filters: reload page with query
    const langSel = document.getElementById('filter-language');
    const sortSel = document.getElementById('sort-by');
    [langSel, sortSel].forEach(el => {
        if (!el) return;
        el.addEventListener('change', () => {
            const params = new URLSearchParams(window.location.search);
            if (langSel && langSel.value) params.set('language', langSel.value); else params.delete('language');
            if (sortSel && sortSel.value) params.set('sort', sortSel.value); else params.delete('sort');
            window.location.search = params.toString();
        });
    });
});
