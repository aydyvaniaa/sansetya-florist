@extends('layouts.app')

@section('title', 'Keranjang - Sansetya Florist')

@section('content')
@if (session('sukses'))
    <div class="alert alert-sukses">{{ session('sukses') }}</div>
@endif

<a href="{{ route('beranda') }}#katalog" class="tombol-kembali">&larr; Kembali Belanja</a>

<section class="keranjang-box">
    <div class="keranjang-head">
        <h1>Keranjang</h1>

        <div class="aksi">
            @if (count($item) > 0)
                <form method="POST" action="{{ route('keranjang.kosongkan') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="tombol tombol-outline">Kosongkan Keranjang</button>
                </form>
            @endif
            <a href="{{ route('beranda') }}#katalog" class="tombol tombol-utama">Lanjut Belanja</a>
        </div>
    </div>

    @if (count($item) === 0)
        <div class="keranjang-kosong">
            <h2>Keranjangmu masih kosong</h2>
            <p>Yuk pilih rangkaian bunga favoritmu dulu.</p>
            <a href="{{ route('beranda') }}#katalog" class="tombol tombol-utama">Lihat Produk</a>
        </div>
    @else
        <div class="baris-keranjang judul">
            <span>Produk</span>
            <span>Harga</span>
            <span>Qty</span>
            <span>Subtotal</span>
            <span></span>
        </div>

        @foreach ($item as $baris)
            @php $p = $baris['produk']; @endphp

            <div class="baris-keranjang">
                <div class="item-produk">
                    <img src="{{ $p->gambar ? asset('images/' . $p->gambar) : asset('images/logo.png') }}"
                         alt="{{ $p->nama }}"
                         onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}'">
                    <a href="{{ route('produk.show', $p->id) }}">{{ $p->nama }}</a>
                </div>

                <span>Rp {{ number_format($p->harga, 0, ',', '.') }}</span>

                <form method="POST" action="{{ route('keranjang.ubah', $p->id) }}" class="qty-form">
                    @csrf
                    @method('PATCH')
                    <button type="submit" name="aksi" value="kurang" aria-label="Kurangi">&minus;</button>
                    <span>{{ $baris['jumlah'] }}</span>
                    <button type="submit" name="aksi" value="tambah" aria-label="Tambah">+</button>
                </form>

                <span class="item-subtotal">Rp {{ number_format($baris['subtotal'], 0, ',', '.') }}</span>

                <form method="POST" action="{{ route('keranjang.hapus', $p->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-hapus" aria-label="Hapus {{ $p->nama }}" title="Hapus">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14M10 11v6M14 11v6"/>
                        </svg>
                    </button>
                </form>
            </div>
        @endforeach

        <div class="ringkasan">
            <div class="baris"><span>Subtotal</span><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></div>
            <div class="baris total"><span>Total</span><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></div>
            <a href="#" class="tombol tombol-utama">Checkout</a>
        </div>
    @endif
</section>
@endsection