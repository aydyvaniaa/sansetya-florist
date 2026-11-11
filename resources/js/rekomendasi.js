document.addEventListener('DOMContentLoaded', function () {
    const kartuProduk = document.querySelectorAll('.kartu-pilih-produk');
    const wadah = document.getElementById('rekomendasi');

    if (!wadah || kartuProduk.length === 0) return;

    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
    const endpointDasar = (kartuProduk[0].dataset.rekomendasiUrl || '/rekomendasi').replace(/\/$/, '');

    // Simpan tampilan awal (placeholder) untuk dipulihkan saat pilihan di-reset.
    const htmlAwal = wadah.innerHTML;

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

    // Tombol "Mulai ulang pilihan" dibuat dinamis, jadi pakai event delegation.
    wadah.addEventListener('click', function (event) {
        if (event.target.closest('.rekom-reset')) {
            resetRiwayat();
        }
    });

    function muatRekomendasi(kartu) {
        const id = kartu.dataset.id;
        const nama = kartu.dataset.nama || 'produk ini';

        if (!id) return;

        kartu.classList.add('terpilih');
        kartu.setAttribute('aria-pressed', 'true');

        wadah.innerHTML = '<div class="rekom-status">Sedang menghitung rekomendasi untuk ' + escapeHtml(nama) + '...</div>';

        fetch(endpointDasar + '/' + encodeURIComponent(id), {
            headers: { 'Accept': 'application/json' }
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Server mengembalikan status ' + response.status);
                }
                return response.json();
            })
            .then(function (hasil) {
                tandaiKartu(hasil.riwayat);
                tampilkanRekomendasi(hasil, nama);
                wadah.scrollIntoView({ behavior: 'smooth', block: 'start' });
            })
            .catch(function (error) {
                console.error('Kesalahan rekomendasi:', error);
                wadah.innerHTML = '<div class="rekom-status rekom-error">Rekomendasi belum dapat dimuat. Periksa koneksi atau konfigurasi aplikasi, lalu coba lagi.</div>';
            });
    }

    function resetRiwayat() {
        fetch(endpointDasar, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Server mengembalikan status ' + response.status);
                }
                tandaiKartu([]);
                wadah.innerHTML = htmlAwal;
            })
            .catch(function (error) {
                console.error('Gagal mereset pilihan:', error);
            });
    }

    // Beri tanda pada semua produk yang sudah dipilih (riwayat).
    function tandaiKartu(riwayat) {
        const idAktif = (Array.isArray(riwayat) ? riwayat : []).map(function (p) {
            return Number(p.id);
        });

        kartuProduk.forEach(function (item) {
            const aktif = idAktif.indexOf(Number(item.dataset.id)) !== -1;
            item.classList.toggle('terpilih', aktif);
            item.setAttribute('aria-pressed', aktif ? 'true' : 'false');
        });
    }

    function htmlRiwayat(hasil) {
        const riwayat = Array.isArray(hasil.riwayat) ? hasil.riwayat : [];
        if (riwayat.length === 0) return '';

        let html = '<div class="rekom-riwayat">';
        html += '<span class="rekom-riwayat-label">Berdasarkan pilihanmu:</span>';
        riwayat.forEach(function (p) {
            html += '<span class="rekom-chip">' + escapeHtml(p.nama) + '</span>';
        });
        html += '<button type="button" class="rekom-reset">Mulai ulang pilihan</button>';
        html += '</div>';
        return html;
    }

    function tampilkanRekomendasi(hasil, nama) {
        const data = Array.isArray(hasil.data) ? hasil.data : [];

        if (data.length === 0) {
            wadah.innerHTML = '<div class="rekomendasi-placeholder"><h2>Belum Ada Rekomendasi</h2><p>Belum ditemukan produk lain yang memiliki karakteristik serupa dengan ' + escapeHtml(nama) + '.</p></div>' + htmlRiwayat(hasil);
            return;
        }

        let html = '<div class="rekomendasi-heading">';
        html += '<span class="section-eyebrow">DIPILIH BERDASARKAN KEMIRIPAN</span>';
        html += '<h2>Rekomendasi untuk ' + escapeHtml(nama) + '</h2>';
        html += '<p>Produk diurutkan berdasarkan nilai kemiripan tertinggi menggunakan TF-IDF dan Cosine Similarity.</p>';
        html += '</div>';
        html += htmlRiwayat(hasil);
        html += '<div class="rekom-grid">';

        data.forEach(function (produk) {
            const gambar = produk.gambar_url || '/images/logo.png';
            const harga = Number(produk.harga || 0).toLocaleString('id-ID');
            const similarity = Number(produk.similarity || 0).toLocaleString('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            html += '<article class="rekom-kartu">';
            html += '<img src="' + escapeAttribute(gambar) + '" alt="' + escapeAttribute(produk.nama) + '" onerror="this.onerror=null;this.src=\'/images/logo.png\'">';
            html += '<div class="rekom-detail">';
            html += '<span class="label-kategori">' + escapeHtml(produk.kategori || 'Produk') + '</span>';
            html += '<h3 class="rekom-nama">' + escapeHtml(produk.nama) + '</h3>';
            html += '<p class="rekom-harga">Rp ' + harga + '</p>';

            if (Array.isArray(produk.alasan) && produk.alasan.length > 0) {
                html += '<div class="rekom-alasan">';
                produk.alasan.forEach(function (alasan) {
                    html += '<span class="rekom-tag">' + escapeHtml(alasan) + '</span>';
                });
                html += '</div>';
            }

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