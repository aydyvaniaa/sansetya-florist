document.addEventListener('DOMContentLoaded', function () {
    const kartuProduk = document.querySelectorAll('.kartu-produk');
    const wadah = document.getElementById('rekomendasi');

    if (!wadah) return;

    kartuProduk.forEach(function (kartu) {
        kartu.style.cursor = 'pointer';

        kartu.addEventListener('click', function () {
            const id = kartu.dataset.id;
            const nama = kartu.dataset.nama || 'produk ini';

            // tandai kartu yang sedang dipilih
            kartuProduk.forEach(function (k) { k.classList.remove('terpilih'); });
            kartu.classList.add('terpilih');

            wadah.innerHTML = '<p class="rekom-loading">Memuat rekomendasi...</p>';

            fetch('/rekomendasi/' + id)
                .then(function (res) {
                    if (!res.ok) throw new Error('Gagal memuat data');
                    return res.json();
                })
                .then(function (data) {
                    tampilkanRekomendasi(data, nama);
                    wadah.scrollIntoView({ behavior: 'smooth', block: 'start' });
                })
                .catch(function () {
                    wadah.innerHTML = '<p class="rekom-error">Rekomendasi tidak dapat dimuat.</p>';
                });
        });
    });

    function tampilkanRekomendasi(data, nama) {
        if (data.length === 0) {
            wadah.innerHTML = '<p class="rekom-kosong">Belum ada rekomendasi untuk produk ini.</p>';
            return;
        }

        let html = '<h3 class="rekom-judul">Rekomendasi untuk ' + nama + '</h3>';
        html += '<div class="rekom-grid">';

        data.forEach(function (p) {
            html += '<div class="rekom-kartu">';
            html += '<img src="/images/' + p.gambar + '" alt="' + p.nama + '">';
            html += '<p class="rekom-nama">' + p.nama + '</p>';
            html += '<p class="rekom-harga">Rp. ' + Number(p.harga).toLocaleString('id-ID') + '</p>';
            html += '</div>';
        });

        html += '</div>';
        wadah.innerHTML = html;
    }
});