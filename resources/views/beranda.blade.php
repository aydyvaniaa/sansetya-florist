
@extends('layouts.app')

@section('content')

@php
    $bestSeller = $bestSeller ?? [
        (object)['nama'=>'Buket Mix Warna-Warni','harga'=>100000,'gambar'=>'buket-mix.jpg','kategori'=>'Buket Bunga'],
        (object)['nama'=>'Buket Bunga Matahari','harga'=>150000,'gambar'=>'buket-matahari.jpg','kategori'=>'Buket Bunga'],
        (object)['nama'=>'Buket Mawar Merah','harga'=>200000,'gambar'=>'buket-mawar.jpg','kategori'=>'Buket Bunga'],
    ];
@endphp

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
<section class="kategori">
    <h2>Kategori</h2>

    <div class="grid-kategori">
        @php
            $kategori = [
                ['nama' => 'Buket', 'gambar' => 'kategori-buket.jpg'],
                ['nama' => 'Bunga Hias Mobil', 'gambar' => 'mobil.jpg'],
                ['nama' => 'Bloom Box', 'gambar' => 'bloom-box.jpg'],
                ['nama' => 'Artificial', 'gambar' => 'artificial.jpg'],
                ['nama' => 'Vas Bunga', 'gambar' => 'vas.jpg'],
                ['nama' => 'Custom', 'gambar' => 'custom.jpg'],
            ];
        @endphp

        @foreach ($kategori as $k)
            <div class="kartu-kategori">
                <img src="{{ asset('images/'.$k['gambar']) }}"
                     alt="{{ $k['nama'] }}">
                <p>{{ $k['nama'] }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- best seller --}}
<section class="best-seller">
    <h2>Best Seller</h2>

    <div class="grid-produk">
        @foreach ($bestSeller as $p)
            <article class="kartu-produk">
                <img src="{{ asset('images/'.$p->gambar) }}"
                     alt="{{ $p->nama }}">

                <p class="harga">
                    Rp {{ number_format($p->harga, 0, ',', '.') }}
                </p>

                <a class="tombol" href="#">
                    {{ $p->kategori }}
                </a>
            </article>
        @endforeach
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
