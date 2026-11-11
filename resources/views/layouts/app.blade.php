<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sansetya Florist')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Poppins:wght@400;500;600;700&display=swap">

    {{-- CSS ditaruh langsung di sini, jadi tidak perlu file style.css terpisah --}}
    <style>
/* =====================================================
   SANSETYA FLORIST - STYLE UTAMA (menggantikan style.css
   dan rekomendasi.css yang lama)
   ===================================================== */

* { margin: 0; padding: 0; box-sizing: border-box; }

:root {
    --pink: #fde5eb;
    --pink-2: #fbd0dc;
    --pink-soft: #fff5f7;
    --burgundy: #9a1830;
    --burgundy-dark: #7c1226;
    --text: #3a2a2e;
    --muted: #7d6a70;
    --border: #f3dbe2;
    --white: #fff;
    --shadow: 0 4px 16px rgba(120, 40, 60, .08);
}

body {
    font-family: 'Poppins', sans-serif;
    background: var(--pink-soft);
    color: var(--text);
    font-size: 14px;
    line-height: 1.6;
}

a { text-decoration: none; color: inherit; }
img { max-width: 100%; display: block; }
button { font-family: inherit; }

main {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 30px 4% 10px;
}

h1, h2, h3 { font-family: 'Playfair Display', serif; color: var(--burgundy); line-height: 1.3; }

/* ---------- HEADER ---------- */
.main-header {
    position: sticky;
    top: 0;
    z-index: 50;
    background: var(--pink);
    border-bottom: 2px solid var(--pink-2);
    padding: 10px 5%;
    min-height: 78px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.logo { display: flex; align-items: center; gap: 12px; }
.logo-image { width: 54px; height: 54px; object-fit: contain; }
.logo-name { font-family: 'Playfair Display', serif; font-size: 18px; font-weight: 600; color: var(--burgundy); }

.header-right { display: flex; align-items: center; gap: 22px; }
.navbar { display: flex; align-items: center; gap: 8px; }
.navbar a {
    padding: 8px 16px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    color: var(--text);
    transition: .2s;
}
.navbar a:hover, .navbar a.aktif { background: #fff; color: var(--burgundy); }

.header-actions { display: flex; align-items: center; gap: 6px; }
.icon-button {
    position: relative;
    width: 42px; height: 42px;
    display: flex; align-items: center; justify-content: center;
    border-radius: 50%;
    color: var(--burgundy);
    transition: .2s;
}
.icon-button:hover { background: #fff; }

.badge-keranjang {
    position: absolute;
    top: -2px; right: -4px;
    min-width: 19px; height: 19px;
    padding: 0 5px;
    border-radius: 20px;
    background: #e03a4e;
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    line-height: 19px;
    text-align: center;
}

/* ---------- BANNER ---------- */
.banner {
    background: linear-gradient(120deg, var(--pink), #fff0f3);
    border: 1px solid var(--border);
    border-radius: 22px;
    min-height: 230px;
    padding: 32px 46px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 28px;
    margin-bottom: 40px;
    overflow: hidden;
}
.banner-content { flex: 1; }
.banner h1 { font-size: 32px; margin-bottom: 12px; }
.banner p { font-size: 14px; color: var(--muted); max-width: 460px; }
.banner img { width: 36%; max-width: 340px; height: 190px; object-fit: cover; border-radius: 18px; background: #fff; }

.banner-eyebrow, .section-eyebrow {
    display: inline-block;
    color: #c0617a;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 2px;
    margin-bottom: 6px;
}

.banner-button {
    display: inline-block;
    margin-top: 18px;
    padding: 11px 24px;
    border-radius: 30px;
    background: var(--burgundy);
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    transition: .2s;
}
.banner-button:hover { background: var(--burgundy-dark); transform: translateY(-2px); }

/* ---------- SECTION ---------- */
section { margin-bottom: 44px; }
.section-heading { margin-bottom: 22px; }
.section-heading h2 { font-size: 26px; margin-bottom: 4px; }
.section-heading p { color: var(--muted); font-size: 13px; }

/* ---------- KATEGORI ---------- */
.grid-kategori { display: grid; grid-template-columns: repeat(6, minmax(0, 1fr)); gap: 16px; }
.kartu-kategori {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 16px 8px 12px;
    text-align: center;
    transition: .25s;
}
.kartu-kategori:hover { transform: translateY(-4px); box-shadow: var(--shadow); border-color: var(--pink-2); }
.kartu-kategori img { width: 78px; height: 78px; border-radius: 50%; object-fit: cover; margin: 0 auto 10px; background: var(--pink); }
.kartu-kategori p { font-size: 12px; font-weight: 600; }

/* ---------- KARTU PRODUK ---------- */
.grid-produk { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 22px; }

.kartu-produk {
    display: flex;
    flex-direction: column;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
    transition: .25s;
}
.kartu-produk:hover { transform: translateY(-5px); box-shadow: 0 10px 26px rgba(120, 40, 60, .13); }
.kartu-produk:focus-visible { outline: 3px solid var(--pink-2); outline-offset: 2px; }
.kartu-produk img { width: 100%; height: 240px; object-fit: cover; background: var(--pink); }

.detail-produk { display: flex; flex-direction: column; flex: 1; padding: 16px 18px 20px; }
.detail-produk h3 { font-size: 18px; margin: 10px 0 6px; }
.detail-produk .harga { font-size: 15px; font-weight: 600; color: var(--text); margin-bottom: 14px; }

.label-kategori {
    align-self: flex-start;
    display: inline-block;
    background: #fde3ea;
    color: var(--burgundy);
    border-radius: 20px;
    padding: 3px 12px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .3px;
}

.tombol-order {
    margin-top: auto;
    align-self: flex-start;
    display: inline-block;
    background: var(--burgundy);
    color: #fff;
    padding: 9px 22px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
    transition: .2s;
}
.kartu-produk:hover .tombol-order { background: var(--burgundy-dark); }

.produk-kosong { grid-column: 1 / -1; color: var(--muted); padding: 20px 0; }

/* ---------- BREADCRUMB & ALERT ---------- */
.breadcrumb { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 20px; font-size: 12px; color: var(--muted); }
.breadcrumb a:hover { color: var(--burgundy); }
.breadcrumb strong { color: var(--burgundy); font-weight: 600; }

.alert { margin-bottom: 20px; padding: 14px 20px; border-radius: 14px; font-size: 13px; }
.alert-sukses { background: #eef8f0; color: #2f6b3d; border: 1px solid #cfe9d5; }
.alert-error  { background: #fdeff1; color: #a33f55; border: 1px solid #f4cfd6; }

/* ---------- TOMBOL ---------- */
.tombol {
    display: inline-block;
    padding: 12px 26px;
    border-radius: 30px;
    border: 1.5px solid var(--burgundy);
    font-size: 13px;
    font-weight: 600;
    text-align: center;
    cursor: pointer;
    transition: .2s;
}
.tombol-utama { background: var(--burgundy); color: #fff; }
.tombol-utama:hover { background: var(--burgundy-dark); border-color: var(--burgundy-dark); transform: translateY(-2px); }
.tombol-outline { background: #fff; color: var(--burgundy); }
.tombol-outline:hover { background: var(--pink); }

/* ---------- DETAIL PRODUK ---------- */
.halaman-detail {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
    gap: 36px;
    padding: 28px;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 22px;
    box-shadow: var(--shadow);
}
.detail-gambar img { width: 100%; height: 460px; object-fit: cover; border-radius: 16px; background: var(--pink); }
.detail-info { min-width: 0; }
.detail-info h1 { font-size: 30px; margin: 12px 0 6px; }
.detail-harga { color: var(--burgundy); font-size: 24px; font-weight: 700; margin-bottom: 20px; }

.kartu-deskripsi { background: var(--pink-soft); border: 1px solid var(--border); border-radius: 16px; padding: 18px 20px; }
.kartu-deskripsi h3 { font-size: 16px; margin-bottom: 6px; }
.kartu-deskripsi > p { color: var(--muted); font-size: 13px; margin-bottom: 14px; }
.detail-atribut { list-style: none; border-top: 1px dashed var(--pink-2); padding-top: 10px; }
.detail-atribut li { display: flex; justify-content: space-between; gap: 12px; padding: 6px 0; font-size: 13px; }
.detail-atribut li span { color: var(--muted); }
.detail-atribut li strong { color: var(--text); font-weight: 600; text-align: right; }

.form-beli { margin-top: 22px; }
.baris-jumlah { display: flex; align-items: center; gap: 18px; margin-bottom: 14px; font-weight: 600; }

.pilih-jumlah { display: inline-flex; align-items: center; border: 1px solid var(--border); border-radius: 14px; overflow: hidden; background: #fff; }
.pilih-jumlah input {
    width: 56px; height: 42px;
    border: 0; text-align: center;
    font-family: inherit; font-size: 14px; font-weight: 600;
    background: transparent; -moz-appearance: textfield;
}
.pilih-jumlah input::-webkit-outer-spin-button,
.pilih-jumlah input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
.pilih-jumlah input:focus { outline: none; }
.btn-jumlah { width: 42px; height: 42px; border: 0; background: #fff; color: var(--burgundy); font-size: 18px; font-weight: 700; cursor: pointer; transition: .2s; }
.btn-jumlah:hover { background: var(--pink); }

.baris-subtotal { display: flex; align-items: center; gap: 18px; margin-bottom: 16px; font-weight: 600; }
.baris-subtotal strong { color: var(--burgundy); font-size: 20px; }
.info-keranjang { margin-bottom: 14px; color: var(--muted); font-size: 12px; }
.aksi-beli { display: flex; flex-wrap: wrap; gap: 12px; }

/* ---------- PRODUK SERUPA ---------- */
.bagian-rekomendasi { margin-top: 44px; }
.rekomendasi-heading { margin-bottom: 22px; }
.rekomendasi-heading h2 { font-size: 26px; margin-bottom: 4px; }
.rekomendasi-heading p { color: var(--muted); font-size: 13px; }

.rekom-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 22px; }
.rekom-kartu { display: block; background: #fff; border: 1px solid var(--border); border-radius: 16px; overflow: hidden; transition: .25s; }
.rekom-kartu:hover { transform: translateY(-5px); box-shadow: 0 10px 26px rgba(120, 40, 60, .13); }
.rekom-kartu img { width: 100%; height: 210px; object-fit: cover; background: var(--pink); }
.rekom-detail { padding: 14px 16px 18px; }
.rekom-nama { font-size: 16px; margin: 8px 0 4px; }
.rekom-harga { font-weight: 600; font-size: 14px; }

/* ---------- KERANJANG ---------- */
.keranjang-box { background: #fff; border: 1px solid var(--border); border-radius: 22px; padding: 26px 28px; box-shadow: var(--shadow); }
.keranjang-head { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 14px; margin-bottom: 22px; }
.keranjang-head h1 { font-size: 26px; color: var(--text); }
.keranjang-head .aksi { display: flex; gap: 10px; flex-wrap: wrap; }
.keranjang-head .tombol { padding: 9px 20px; font-size: 12px; }
.keranjang-head form { display: inline; }

.baris-keranjang {
    display: grid;
    grid-template-columns: minmax(0, 2.4fr) 1fr 1.1fr 1fr 44px;
    align-items: center;
    gap: 16px;
    padding: 16px 8px;
    border-bottom: 1px solid var(--border);
}
.baris-keranjang.judul { font-weight: 700; padding-top: 6px; padding-bottom: 12px; border-bottom: 1.5px solid var(--pink-2); }
.item-produk { display: flex; align-items: center; gap: 16px; min-width: 0; }
.item-produk img { width: 84px; height: 84px; object-fit: cover; border-radius: 14px; background: var(--pink); flex-shrink: 0; }
.item-produk a { font-family: 'Playfair Display', serif; font-size: 17px; font-weight: 600; color: var(--burgundy); }
.item-produk a:hover { text-decoration: underline; }
.item-subtotal { font-weight: 700; font-size: 15px; }

.qty-form { display: inline-flex; align-items: center; border: 1px solid var(--border); border-radius: 14px; overflow: hidden; }
.qty-form button { width: 38px; height: 40px; border: 0; background: #fff; color: var(--burgundy); font-size: 17px; font-weight: 700; cursor: pointer; }
.qty-form button:hover { background: var(--pink); }
.qty-form span { min-width: 40px; text-align: center; font-weight: 600; }

.btn-hapus { width: 38px; height: 38px; border-radius: 10px; border: 1px solid var(--pink-2); background: #fff; color: var(--burgundy); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: .2s; }
.btn-hapus:hover { background: var(--pink); }

.ringkasan { margin-top: 26px; max-width: 380px; border: 1px solid var(--border); border-radius: 18px; padding: 20px 22px; }
.ringkasan .baris { display: flex; justify-content: space-between; padding: 8px 0; }
.ringkasan .baris + .baris { border-top: 1px dashed var(--pink-2); }
.ringkasan .total strong { color: var(--burgundy); font-size: 20px; }
.ringkasan .tombol { display: block; width: 100%; margin-top: 14px; padding: 15px; font-size: 15px; }

.keranjang-kosong { text-align: center; padding: 50px 10px; color: var(--muted); }
.keranjang-kosong h2 { font-size: 22px; margin-bottom: 8px; }
.keranjang-kosong .tombol { margin-top: 18px; }

/* ---------- FOOTER ---------- */
footer { background: var(--pink); border-top: 2px solid var(--pink-2); padding: 34px 5% 16px; margin-top: 40px; }
footer .footer-container { display: grid; grid-template-columns: repeat(4, 1fr); gap: 28px; max-width: 1200px; margin: auto; }
footer h3 { font-size: 15px; margin-bottom: 12px; }
footer p, footer a { font-size: 13px; line-height: 2; }
footer .footer-container a:hover { color: var(--burgundy); }
.footer-logo { display: flex; flex-direction: column; align-items: flex-start; gap: 8px; }
.footer-logo p { max-width: 200px; }
.footer-logo-image { width: 65px; height: 65px; object-fit: contain; }
footer .copyright { text-align: center; border-top: 1px solid var(--pink-2); margin-top: 26px; padding-top: 14px; font-size: 11px; color: var(--muted); }

/* ---------- RESPONSIVE ---------- */
@media (max-width: 1000px) {
    .grid-produk, .rekom-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    .grid-kategori { grid-template-columns: repeat(3, minmax(0, 1fr)); }
}

@media (max-width: 820px) {
    .halaman-detail { grid-template-columns: 1fr; gap: 20px; padding: 18px; }
    .detail-gambar img { height: 340px; }
    .banner { padding: 24px; }
    .banner h1 { font-size: 24px; }
    .banner img { height: 140px; }
    footer .footer-container { grid-template-columns: repeat(2, 1fr); }

    .baris-keranjang.judul { display: none; }
    .baris-keranjang { grid-template-columns: 1fr auto; gap: 10px; }
    .baris-keranjang .item-produk { grid-column: 1 / -1; }
    .keranjang-box { padding: 18px; }
}

@media (max-width: 600px) {
    .grid-produk, .rekom-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
    .kartu-produk img, .rekom-kartu img { height: 160px; }
    .detail-produk { padding: 12px 12px 16px; }
    .detail-produk h3 { font-size: 15px; }
    .logo-name { display: none; }
    .navbar a { padding: 6px 10px; font-size: 13px; }
    .header-right { gap: 8px; }
    .banner img { display: none; }
}

/* ---------- TOMBOL KEMBALI ---------- */
.tombol-kembali {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 18px;
    padding: 9px 18px;
    border-radius: 30px;
    background: #fff;
    border: 1.5px solid var(--burgundy);
    color: var(--burgundy);
    font-size: 13px;
    font-weight: 600;
    transition: .2s;
}
.tombol-kembali:hover { background: var(--pink); transform: translateX(-3px); }

    </style>
</head>
<body>
    @include('layouts.header')

    <main>
        @yield('content')
    </main>

    @include('layouts.footer')
</body>
</html>