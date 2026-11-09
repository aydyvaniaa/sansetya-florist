@extends('layouts.app')

@section('title', 'Beranda - Sansetya Florist')

@section('content')
<section class="banner">
    <div class="banner-content">
        <span class="banner-eyebrow">BLOOM WITH LOVE</span>
        <h1>Hai, Selamat Datang di Sansetya Florist!</h1>
        <p>Temukan rangkaian bunga terbaik untuk setiap momen spesialmu.</p>
        <a class="banner-button" href="#katalog">Jelajahi Produk</a>
    </div>
    <img src="{{ asset('images/banner.jpg') }}" alt="Rangkaian bunga Sansetya Florist"
         onerror="this.src='{{ asset('images/logo.png') }}'">
</section>

<section class="kategori" id="kategori">
    <div class="section-heading">
        <div>
            <span class="section-eyebrow">TEMUKAN FAVORITMU</span>
            <h2>Kategori Produk</h2>
        </div>
    </div>

    <div class="grid-kategori">
        @php
            $daftarKategori = [
                ['nama' => 'Buket', 'gambar' => 'kategori-buket.jpg'],
                ['nama' => 'Bunga Hias Meja', 'gambar' => 'meja-minimalis.jpg'],
                ['nama' => 'Bloom Box', 'gambar' => 'bloom-pastel.jpg'],
                ['nama' => 'Artificial', 'gambar' => 'artificial-peony.jpg'],
                ['nama' => 'Vas Bunga', 'gambar' => 'vas-putih.jpg'],
                ['nama' => 'Custom', 'gambar' => 'custom.jpg'],
            ];
        @endphp

        @foreach ($daftarKategori as $item)
            <a class="kartu-kategori" href="#katalog">
                <img src="{{ asset('images/' . $item['gambar']) }}"
                     alt="{{ $item['nama'] }}"
                     onerror="this.src='{{ asset('images/logo.png') }}'">
                <p>{{ $item['nama'] }}</p>
            </a>
        @endforeach
    </div>
</section>

<section class="best-seller">
    <div class="section-heading">
        <div>
            <span class="section-eyebrow">PALING DISUKAI</span>
            <h2>Best Seller</h2>
        </div>
    </div>

    <div class="grid-produk grid-best-seller">
        @forelse ($bestSeller as $p)
            <article class="kartu-produk">
                <img src="{{ $p->gambar ? asset('images/' . $p->gambar) : asset('images/logo.png') }}"
                     alt="{{ $p->nama }}"
                     onerror="this.src='{{ asset('images/logo.png') }}'">
                <div class="detail-produk">
                    <span class="label-kategori">{{ $p->kategori }}</span>
                    <h3>{{ $p->nama }}</h3>
                    <p class="harga">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                </div>
            </article>
        @empty
            <p class="produk-kosong">Belum ada produk best seller.</p>
        @endforelse
    </div>
</section>

<section class="katalog" id="katalog">
    <div class="section-heading katalog-heading">
        <div>
            <span class="section-eyebrow">PILIH PRODUK UNTUK MEMULAI</span>
            <h2>Jelajahi Semua Produk</h2>
            <p>Klik salah satu produk untuk melihat rekomendasi yang memiliki karakteristik serupa.</p>
        </div>
    </div>

    <div class="grid-produk" id="daftar-produk">
        @forelse ($produk as $p)
            <article class="kartu-produk kartu-pilih-produk"
                     data-id="{{ $p->id }}"
                     data-nama="{{ $p->nama }}"
                     data-rekomendasi-url="{{ url('/rekomendasi') }}"
                     tabindex="0" role="button"
                     aria-label="Lihat rekomendasi untuk {{ $p->nama }}">
                <img src="{{ $p->gambar ? asset('images/' . $p->gambar) : asset('images/logo.png') }}"
                     alt="{{ $p->nama }}"
                     onerror="this.src='{{ asset('images/logo.png') }}'">
                <div class="detail-produk">
                    <span class="label-kategori">{{ $p->kategori }}</span>
                    <h3>{{ $p->nama }}</h3>
                    <p class="harga">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                    <span class="petunjuk-pilih">Lihat rekomendasi</span>
                </div>
            </article>
        @empty
            <p class="produk-kosong">Belum ada produk yang tersedia.</p>
        @endforelse
    </div>
</section>

<section class="bagian-rekomendasi" aria-live="polite">
    <div id="rekomendasi" class="rekomendasi-container">
        <div class="rekomendasi-placeholder">
            <span class="section-eyebrow">PERSONALIZED FOR YOU</span>
            <h2>Rekomendasi Produk</h2>
            <p>Pilih salah satu produk di atas untuk menemukan produk lain yang mirip.</p>
        </div>
    </div>
</section>
@endsection
