document.addEventListener('DOMContentLoaded', () => {
    // Buat setiap card bisa diklik
    document.querySelectorAll('.project-item').forEach(card => {
        card.addEventListener('click', function (e) {
            // Cegah klik dari tombol like agar tidak ikut redirect
            if (e.target.closest('.like-btn')) return;
            window.location.href = this.dataset.url;
        });
    });

    // LIKE button handler
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', async function (e) {
            e.stopPropagation(); // cegah event klik card

            const id = this.dataset.id;
            try {
                const res = await fetch(`/projects/${id}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    }
                });

                const data = await res.json().catch(() => null);
                if (!res.ok || !data) {
                    alert(data?.message || 'Terjadi kesalahan di server.');
                    return;
                }

                // Update jumlah like
                const countEl = document.getElementById(`like-count-${id}`);
                if (countEl) countEl.textContent = data.likes_count;

                // Toggle state
                this.classList.toggle('liked', data.liked);
                this.classList.toggle('fa-solid', data.liked);
                this.classList.toggle('fa-regular', !data.liked);
            } catch (err) {
                console.error(err);
                alert('Gagal menyukai proyek. Coba lagi nanti.');
            }
        });
    });
});
