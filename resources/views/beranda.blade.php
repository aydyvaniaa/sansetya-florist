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
         onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}'">
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
                ['nama' => 'Buket', 'gambar' => 'kategori-buket.png'],
                ['nama' => 'Bunga Hias Meja', 'gambar' => 'meja-minimalis.png'],
                ['nama' => 'Bloom Box', 'gambar' => 'bloom-pastel.png'],
                ['nama' => 'Artificial', 'gambar' => 'artificial-peony.png'],
                ['nama' => 'Vas Bunga', 'gambar' => 'vas-putih.png'],
                ['nama' => 'Custom', 'gambar' => 'custom.png'],
            ];
        @endphp

        @foreach ($daftarKategori as $item)
            <a class="kartu-kategori" href="#katalog">
                <img src="{{ asset('images/' . $item['gambar']) }}"
                     alt="{{ $item['nama'] }}"
                     onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}'">
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
            {{-- Seluruh kartu adalah link ke halaman detail produk --}}
            <a href="{{ route('produk.show', $p->id) }}"
               class="kartu-produk kartu-link"
               aria-label="Lihat detail {{ $p->nama }}">
                <img src="{{ $p->gambar ? asset('images/' . $p->gambar) : asset('images/logo.png') }}"
                     alt="{{ $p->nama }}"
                     onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}'">
                <div class="detail-produk">
                    <span class="label-kategori">{{ $p->kategori }}</span>
                    <h3>{{ $p->nama }}</h3>
                    <p class="harga">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                    <span class="tombol-order">Lihat Detail</span>
                </div>
            </a>
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
            <p>Klik salah satu produk untuk melihat detail, menambahkannya ke keranjang, dan menemukan produk serupa.</p>
        </div>
    </div>

    <div class="grid-produk" id="daftar-produk">
        @forelse ($produk as $p)
            <a href="{{ route('produk.show', $p->id) }}"
               class="kartu-produk kartu-link"
               aria-label="Lihat detail {{ $p->nama }}">
                <img src="{{ $p->gambar ? asset('images/' . $p->gambar) : asset('images/logo.png') }}"
                     alt="{{ $p->nama }}"
                     onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}'">
                <div class="detail-produk">
                    <span class="label-kategori">{{ $p->kategori }}</span>
                    <h3>{{ $p->nama }}</h3>
                    <p class="harga">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                    <span class="tombol-order">Lihat Detail</span>
                </div>
            </a>
        @empty
            <p class="produk-kosong">Belum ada produk yang tersedia.</p>
        @endforelse
    </div>
</section>
@endsection