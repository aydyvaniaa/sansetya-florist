@extends('layouts.app')

@section('content')
@php
  $bestSeller = $bestSeller ?? [
    (object)['nama'=>'Buket Mix Warna-Warni','harga'=>100000,'gambar'=>'buket-mix.jpg','kategori'=>'Buket Bunga'],
    (object)['nama'=>'Buket Bunga Matahari','harga'=>150000,'gambar'=>'buket-matahari.jpg','kategori'=>'Buket Bunga'],
    (object)['nama'=>'Buket Mawar Merah','harga'=>200000,'gambar'=>'buket-mawar.jpg','kategori'=>'Buket Bunga'],
  ];
@endphp

<section class="banner">
  <div>
    <h1>Hai, Selamat Datang di Sansetya Florist!</h1>
    <p>Temukan rangkaian bunga terbaik untuk setiap momen spesialmu.</p>
  </div>
  <img src="{{ asset('images/banner.jpg') }}" alt="Buket bunga">
</section>

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

<section class="best-seller">
  <h2>Best Seller</h2>
  <div class="grid-produk">
    @foreach ($bestSeller as $p)
      <article class="kartu-produk">
        <img src="{{ asset('images/'.$p->gambar) }}" alt="{{ $p->nama }}">
        <p class="harga">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
        <a class="tombol" href="#">{{ $p->kategori }}</a>
      </article>
    @endforeach
  </div>
</section>
@endsection