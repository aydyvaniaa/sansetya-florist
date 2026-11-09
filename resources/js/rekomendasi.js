document.addEventListener('DOMContentLoaded', function () {
    const kartuProduk = document.querySelectorAll('.kartu-pilih-produk');
    const wadah = document.getElementById('rekomendasi');

    if (!wadah || kartuProduk.length === 0) return;

    kartuProduk.forEach(function (kartu) {
        kartu.addEventListener('click', function () {
            muatRekomendasi(kartu);
        });

        kartu.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                muatRekomendasi(kartu);
            }
        });
    });

    function muatRekomendasi(kartu) {
        const id = kartu.dataset.id;
        const nama = kartu.dataset.nama || 'produk ini';
        const endpoint = kartu.dataset.rekomendasiUrl;

        if (!id || !endpoint) return;

        kartuProduk.forEach(function (item) {
            item.classList.toggle('terpilih', item === kartu);
            item.setAttribute('aria-pressed', item === kartu ? 'true' : 'false');
        });

        wadah.innerHTML = '<div class="rekom-status">Sedang menghitung rekomendasi untuk ' + escapeHtml(nama) + '...</div>';

        fetch(endpoint.replace(/\/$/, '') + '/' + encodeURIComponent(id), {
            headers: { 'Accept': 'application/json' }
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Server mengembalikan status ' + response.status);
                }
                return response.json();
            })
            .then(function (hasil) {
                tampilkanRekomendasi(hasil, nama);
                wadah.scrollIntoView({ behavior: 'smooth', block: 'start' });
            })
            .catch(function (error) {
                console.error('Kesalahan rekomendasi:', error);
                wadah.innerHTML = '<div class="rekom-status rekom-error">Rekomendasi belum dapat dimuat. Periksa koneksi atau konfigurasi aplikasi, lalu coba lagi.</div>';
            });
    }

    function tampilkanRekomendasi(hasil, nama) {
        const data = Array.isArray(hasil.data) ? hasil.data : [];

        if (data.length === 0) {
            wadah.innerHTML = '<div class="rekomendasi-placeholder"><h2>Belum Ada Rekomendasi</h2><p>Belum ditemukan produk lain yang memiliki karakteristik serupa dengan ' + escapeHtml(nama) + '.</p></div>';
            return;
        }

        let html = '<div class="rekomendasi-heading">';
        html += '<span class="section-eyebrow">DIPILIH BERDASARKAN KEMIRIPAN</span>';
        html += '<h2>Rekomendasi untuk ' + escapeHtml(nama) + '</h2>';
        html += '<p>Produk diurutkan berdasarkan nilai kemiripan tertinggi menggunakan TF-IDF dan Cosine Similarity.</p>';
        html += '</div><div class="rekom-grid">';

        data.forEach(function (produk) {
            const gambar = produk.gambar_url || '/images/logo.png';
            const harga = Number(produk.harga || 0).toLocaleString('id-ID');
            const similarity = Number(produk.similarity || 0).toLocaleString('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            html += '<article class="rekom-kartu">';
            html += '<img src="' + escapeAttribute(gambar) + '" alt="' + escapeAttribute(produk.nama) + '" onerror="this.src=\'/images/logo.png\'">';
            html += '<div class="rekom-detail">';
            html += '<span class="label-kategori">' + escapeHtml(produk.kategori || 'Produk') + '</span>';
            html += '<h3 class="rekom-nama">' + escapeHtml(produk.nama) + '</h3>';
            html += '<p class="rekom-harga">Rp ' + harga + '</p>';
            html += '<div class="rekom-skor"><span>Tingkat kemiripan</span><strong>' + similarity + '%</strong></div>';
            html += '</div></article>';
        });

        html += '</div>';
        wadah.innerHTML = html;
    }

    function escapeHtml(value) {
        return String(value == null ? '' : value).replace(/[&<>"']/g, function (character) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[character];
        });
    }

    function escapeAttribute(value) {
        return escapeHtml(value);
    }
});
