
@extends('layouts.app')

@section('title', 'Katalog - Sansetya Florist')

@section('content')

<style>
    .katalog-page {
        padding: 10px 0 40px;
    }

    .katalog-title {
        margin-bottom: 22px;
    }

    .katalog-title h1 {
        font-size: 30px;
        margin-bottom: 5px;
    }

    .katalog-title p {
        color: #4B392D;
        font-size: 13px;
    }

    .pencarian-katalog {
        margin-bottom: 18px;
    }

    .pencarian-katalog input {
        width: 100%;
        padding: 12px 18px;
        border: 1px solid #F3DDE0;
        border-radius: 30px;
        outline: none;
        font-family: 'DM Sans', sans-serif;
        font-size: 13px;
    }

    .pencarian-katalog input:focus {
        border-color: #c0617a;
    }

    .filter-kategori {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 24px;
    }

    .filter-btn {
        border: none;
        padding: 8px 16px;
        border-radius: 25px;
        background: #FFE6EC;
        color: #4B392D;
        font-family: 'DM Sans', sans-serif;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: .2s;
    }

    .filter-btn:hover,
    .filter-btn.aktif {
        background: #4B392D;
        color: white;
    }

    .katalog-page .grid-produk {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 20px;
    }

    .katalog-page .kartu-produk {
        min-width: 0;
    }

    .katalog-page .kartu-produk img {
        width: 100%;
        height: 230px;
        object-fit: contain;
        background: #fff;
        display: block;
    }

    .katalog-page .detail-produk h3 {
        font-size: 14px;
    }

    .tombol-keranjang-katalog {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    width: 100%;
    margin-top: 10px;
    padding: 9px;
    border: none;
    border-radius: 25px;
    background: #FFE6EC;
    color: #4B392D;
    font-family: 'DM Sans', sans-serif;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: .2s;
}

    .tombol-keranjang-katalog:hover {
        background: #4B392D;
        color: white;
    }

    .tidak-ada-produk {
        display: none;
        grid-column: 1 / -1;
        text-align: center;
        padding: 40px 10px;
        color: #4B392D;
    }

    @media (max-width: 700px) {
        .katalog-page .grid-produk {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .katalog-page .kartu-produk img {
            height: 150px;
        }
    }

    @media (max-width: 400px) {
        .katalog-page .grid-produk {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="katalog-page">

    {{-- judul katalog --}}
    <div class="katalog-title">
        <h1>Katalog Produk</h1>
        <p>Temukan rangkaian bunga favorit untuk setiap momen spesialmu.</p>
    </div>

    {{-- pencarian produk --}}
    <div class="pencarian-katalog">
        <input
            type="text"
            id="cariProduk"
            placeholder="Cari buket, bloom box, bunga hias..."
        >
    </div>

    {{-- filter kategori --}}
    <div class="filter-kategori">

        <button class="filter-btn aktif" data-filter="semua">
            Semua
        </button>

        @foreach ($kategori as $item)
            <button
                class="filter-btn"
                data-filter="{{ strtolower($item) }}"
            >
                {{ $item }}
            </button>
        @endforeach

    </div>

    {{-- daftar produk --}}
    <div class="grid-produk" id="daftarKatalog">

        @forelse ($produk as $p)

            <div
                class="kartu-produk item-katalog"
                data-nama="{{ strtolower($p->nama) }}"
                data-kategori="{{ strtolower($p->kategori) }}"
            >

                <a href="{{ route('produk.show', $p->id) }}">
                    <img
                        src="{{ $p->gambar ? asset('images/' . $p->gambar) : asset('images/logo.png') }}"
                        alt="{{ $p->nama }}"
                        onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}'"
                    >
                </a>

                <div class="detail-produk">

                    <span class="label-kategori">
                        {{ $p->kategori }}
                    </span>

                    <h3>{{ $p->nama }}</h3>

                    <p class="harga">
                        Rp {{ number_format($p->harga, 0, ',', '.') }}
                    </p>

                    <form action="{{ route('keranjang.tambah') }}" method="POST">
                        @csrf

                        <input
                            type="hidden"
                            name="produk_id"
                            value="{{ $p->id }}"
                        >

                        <input
                            type="hidden"
                            name="jumlah"
                            value="1"
                        >

                        <button type="submit" class="tombol-keranjang-katalog">
    <svg xmlns="http://www.w3.org/2000/svg"
         width="15" height="15"
         viewBox="0 0 24 24"
         fill="none"
         stroke="currentColor"
         stroke-width="2"
         stroke-linecap="round"
         stroke-linejoin="round">
        <circle cx="9" cy="21" r="1"/>
        <circle cx="20" cy="21" r="1"/>
        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
    </svg>
    Tambah Keranjang
</button>
                    </form>

                </div>
            </div>

        @empty

            <p class="produk-kosong">
                Belum ada produk yang tersedia.
            </p>

        @endforelse

        <div class="tidak-ada-produk" id="tidakAdaProduk">
            <p>Produk yang kamu cari tidak ditemukan.</p>
        </div>

    </div>

</div>

{{-- javascript pencarian dan filter --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const inputCari = document.getElementById('cariProduk');
    const tombolFilter = document.querySelectorAll('.filter-btn');
    const produk = document.querySelectorAll('.item-katalog');
    const pesanKosong = document.getElementById('tidakAdaProduk');

    let kategoriAktif = 'semua';

    function tampilkanProduk() {

        const kataKunci = inputCari.value.toLowerCase().trim();
        let jumlahTampil = 0;

        produk.forEach(function (item) {

            const nama = item.dataset.nama;
            const kategori = item.dataset.kategori;

            const cocokNama = nama.includes(kataKunci);
            const cocokKategori =
                kategoriAktif === 'semua' ||
                kategori === kategoriAktif;

            if (cocokNama && cocokKategori) {
                item.style.display = '';
                jumlahTampil++;
            } else {
                item.style.display = 'none';
            }

        });

        pesanKosong.style.display =
            jumlahTampil === 0 ? 'block' : 'none';
    }

    inputCari.addEventListener('input', tampilkanProduk);

    tombolFilter.forEach(function (tombol) {

        tombol.addEventListener('click', function () {

            tombolFilter.forEach(function (btn) {
                btn.classList.remove('aktif');
            });

            this.classList.add('aktif');

            kategoriAktif = this.dataset.filter;

            tampilkanProduk();

        });

    });

});
</script>

@endsection
