
@extends('layouts.app')

@section('title', 'Beranda - Sansetya Florist')

@section('content')

{{-- carousel banner --}}
<section class="sf-carousel" id="sfCarousel">

    {{-- slide 1 --}}
    <div class="sf-slide active">
        <div class="sf-banner-content">
            <span>OUR COLLECTION</span>
            <h1>
                A Little Bloom,<br>
                <em>A Lot of Joy.</em>
            </h1>
            <p>Temukan keindahan sederhana dalam setiap rangkaian bunga.</p>
            <a href="#katalog" class="sf-banner-button">
                Jelajahi Produk →
            </a>
        </div>
        <img src="{{ asset('images/banner-1.png') }}" alt="Koleksi bunga">
    </div>

    {{-- slide 2 --}}
    <div class="sf-slide">
        <div class="sf-banner-content">
            <span>SPECIAL MOMENTS</span>
            <h1>
                Give Love,<br>
                <em>Gift Flowers.</em>
            </h1>
            <p>Satu rangkaian bunga, sejuta cara untuk menunjukkan rasa sayang.</p>
            <a href="#katalog" class="sf-banner-button">
                Jelajahi Produk →
            </a>
        </div>
        <img src="{{ asset('images/banner-2.png') }}" alt="Bunga untuk hadiah">
    </div>

    {{-- slide 3 --}}
    <div class="sf-slide">
        <div class="sf-banner-content">
            <span>MADE WITH LOVE</span>
            <h1>
                Let Your Feelings<br>
                <em>Blossom.</em>
            </h1>
            <p>Ungkapkan hal yang sulit diucapkan melalui keindahan bunga.</p>
            <a href="#katalog" class="sf-banner-button">
                Jelajahi Produk →
            </a>
        </div>
        <img src="{{ asset('images/banner-3.png') }}" alt="Rangkaian bunga pilihan">
    </div>

    {{-- tombol navigasi --}}
    <button class="sf-arrow sf-prev" type="button"
        aria-label="Banner sebelumnya">&#10094;</button>

    <button class="sf-arrow sf-next" type="button"
        aria-label="Banner berikutnya">&#10095;</button>

    {{-- indikator --}}
    <div class="sf-dots">
        <button class="sf-dot active" type="button" aria-label="Slide 1"></button>
        <button class="sf-dot" type="button" aria-label="Slide 2"></button>
        <button class="sf-dot" type="button" aria-label="Slide 3"></button>
    </div>

</section>

{{-- kategori --}}
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

{{-- best seller --}}
<section class="best-seller">
    <div class="section-heading">
        <div>
            <span class="section-eyebrow">PALING DISUKAI</span>
            <h2>Best Seller</h2>
        </div>
    </div>

    <div class="grid-produk grid-best-seller">
        @forelse ($bestSeller as $p)
            <a href="{{ route('produk.show', $p->id) }}"
                class="kartu-produk kartu-link"
                aria-label="Lihat detail {{ $p->nama }}">

                <img src="{{ $p->gambar ? asset('images/' . $p->gambar) : asset('images/logo.png') }}"
                    alt="{{ $p->nama }}"
                    onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}'">

                <div class="detail-produk">
                    <span class="label-kategori">{{ $p->kategori }}</span>
                    <h3>{{ $p->nama }}</h3>
                    <p class="harga">
                        Rp {{ number_format($p->harga, 0, ',', '.') }}
                    </p>
                    <span class="tombol-order">Lihat Detail</span>
                </div>
            </a>
        @empty
            <p class="produk-kosong">Belum ada produk best seller.</p>
        @endforelse
    </div>
</section>

{{-- katalog produk --}}
<section class="katalog" id="katalog">
    <div class="section-heading katalog-heading">
        <div>
            <span class="section-eyebrow">PILIH PRODUK UNTUK MEMULAI</span>
            <h2>Jelajahi Semua Produk</h2>
            <p>
                Klik salah satu produk untuk melihat detail, menambahkannya
                ke keranjang, dan menemukan produk serupa.
            </p>
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
                    <p class="harga">
                        Rp {{ number_format($p->harga, 0, ',', '.') }}
                    </p>
                    <span class="tombol-order">Lihat Detail</span>
                </div>
            </a>
        @empty
            <p class="produk-kosong">Belum ada produk yang tersedia.</p>
        @endforelse
    </div>
</section>

{{-- javascript carousel --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const carousel = document.getElementById('sfCarousel');

    if (!carousel) return;

    const slides = carousel.querySelectorAll('.sf-slide');
    const dots = carousel.querySelectorAll('.sf-dot');
    const prev = carousel.querySelector('.sf-prev');
    const next = carousel.querySelector('.sf-next');

    if (!slides.length || !dots.length || !prev || !next) return;

    let current = 0;
    let timer;

    function showSlide(index) {
        slides[current].classList.remove('active');
        dots[current].classList.remove('active');

        current = (index + slides.length) % slides.length;

        slides[current].classList.add('active');
        dots[current].classList.add('active');
    }

    function startAutoSlide() {
        clearInterval(timer);

        timer = setInterval(function () {
            showSlide(current + 1);
        }, 4500);
    }

    next.addEventListener('click', function () {
        showSlide(current + 1);
        startAutoSlide();
    });

    prev.addEventListener('click', function () {
        showSlide(current - 1);
        startAutoSlide();
    });

    dots.forEach(function (dot, index) {
        dot.addEventListener('click', function () {
            showSlide(index);
            startAutoSlide();
        });
    });

    startAutoSlide();
});
</script>

@endsection
