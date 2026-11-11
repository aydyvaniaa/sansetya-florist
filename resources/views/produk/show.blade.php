@extends('layouts.app')

@section('title', $produk->nama . ' - Sansetya Florist')

@section('content')
<a href="{{ route('beranda') }}#katalog" class="tombol-kembali">&larr; Kembali ke Produk</a>

<nav class="breadcrumb">
    <a href="{{ route('beranda') }}">Beranda</a>
    <span>/</span>
    <a href="{{ route('beranda') }}#katalog">Produk</a>
    <span>/</span>
    <strong>{{ $produk->nama }}</strong>
</nav>

@if (session('sukses'))
    <div class="alert alert-sukses">{{ session('sukses') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-error">{{ $errors->first() }}</div>
@endif

<section class="halaman-detail">
    <div class="detail-gambar">
        <img src="{{ $produk->gambar ? asset('images/' . $produk->gambar) : asset('images/logo.png') }}"
             alt="{{ $produk->nama }}"
             onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}'">
    </div>

    <div class="detail-info">
        <span class="label-kategori">{{ $produk->kategori }}</span>
        <h1>{{ $produk->nama }}</h1>
        <p class="detail-harga">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>

        <div class="kartu-deskripsi">
            <h3>Deskripsi Singkat</h3>
            <p>{{ $produk->deskripsi }}</p>

            <ul class="detail-atribut">
                @if ($produk->jenis_bunga)
                    <li><span>Jenis bunga</span><strong>{{ ucfirst($produk->jenis_bunga) }}</strong></li>
                @endif
                @if ($produk->warna)
                    <li><span>Warna</span><strong>{{ ucfirst($produk->warna) }}</strong></li>
                @endif
                @if ($produk->acara)
                    <li><span>Cocok untuk</span><strong>{{ ucwords($produk->acara) }}</strong></li>
                @endif
                <li><span>Terjual</span><strong>{{ $produk->terjual }}</strong></li>
            </ul>
        </div>

        <form action="{{ route('keranjang.tambah') }}" method="POST" class="form-beli">
            @csrf
            <input type="hidden" name="produk_id" value="{{ $produk->id }}">

            <div class="baris-jumlah">
                <span>Kuantitas</span>

                <div class="pilih-jumlah">
                    <button type="button" class="btn-jumlah" data-aksi="kurang" aria-label="Kurangi jumlah">&minus;</button>
                    <input type="number" name="jumlah" id="jumlah" value="1" min="1" max="99"
                           data-harga="{{ (int) $produk->harga }}">
                    <button type="button" class="btn-jumlah" data-aksi="tambah" aria-label="Tambah jumlah">+</button>
                </div>
            </div>

            <div class="baris-subtotal">
                <span>Subtotal</span>
                <strong id="subtotal">Rp {{ number_format($produk->harga, 0, ',', '.') }}</strong>
            </div>

            @if ($jumlahDiKeranjang > 0)
                <p class="info-keranjang">
                    Produk ini sudah ada {{ $jumlahDiKeranjang }} di keranjangmu.
                </p>
            @endif

            <div class="aksi-beli">
                <button type="submit" class="tombol tombol-utama">+ Tambah ke Keranjang</button>
                <a href="{{ route('keranjang.index') }}" class="tombol tombol-outline">Lihat Keranjang</a>
            </div>
        </form>
    </div>
</section>

<section class="bagian-rekomendasi">
    <div class="rekomendasi-heading">
        <span class="section-eyebrow">MUNGKIN KAMU SUKA</span>
        <h2>Produk Serupa untuk Kamu</h2>
        <p>Rangkaian lain yang punya kategori, jenis bunga, warna, atau acara yang mirip.</p>
    </div>

    @if ($rekomendasi->isEmpty())
        <p class="produk-kosong">Belum ada produk lain yang memiliki karakteristik serupa.</p>
    @else
        <div class="rekom-grid">
            @foreach ($rekomendasi as $baris)
                @php $p = $baris['produk']; @endphp

                <a href="{{ route('produk.show', $p->id) }}" class="rekom-kartu">
                    <img src="{{ $p->gambar ? asset('images/' . $p->gambar) : asset('images/logo.png') }}"
                         alt="{{ $p->nama }}"
                         onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}'">
                    <div class="rekom-detail">
                        <span class="label-kategori">{{ $p->kategori }}</span>
                        <h3 class="rekom-nama">{{ $p->nama }}</h3>
                        <p class="rekom-harga">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</section>

<script>
    // Tombol + dan - untuk kuantitas, sekaligus memperbarui subtotal.
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('jumlah');
        const subtotal = document.getElementById('subtotal');

        if (!input) return;

        const harga = Number(input.dataset.harga) || 0;
        const min = Number(input.min) || 1;
        const max = Number(input.max) || 99;

        function perbarui() {
            let nilai = parseInt(input.value, 10);
            if (isNaN(nilai)) nilai = min;
            input.value = Math.min(max, Math.max(min, nilai));

            if (subtotal) {
                subtotal.textContent = 'Rp ' + (harga * Number(input.value)).toLocaleString('id-ID');
            }
        }

        document.querySelectorAll('.pilih-jumlah .btn-jumlah').forEach(function (tombol) {
            tombol.addEventListener('click', function () {
                const sekarang = parseInt(input.value, 10);
                input.value = (isNaN(sekarang) ? min : sekarang) + (tombol.dataset.aksi === 'tambah' ? 1 : -1);
                perbarui();
            });
        });

        input.addEventListener('change', perbarui);
    });
</script>
@endsection