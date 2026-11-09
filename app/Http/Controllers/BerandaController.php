<?php

namespace App\Http\Controllers;

use App\Models\Produk;

class BerandaController extends Controller
{
    public function index()
    {
        $bestSeller = Produk::query()
            ->orderByDesc('terjual')
            ->take(4)
            ->get();

        $kategori = Produk::query()
            ->select('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');

        // Seluruh produk dibutuhkan agar pelanggan dapat memilih produk
        // sebagai dasar pencarian rekomendasi.
        $produk = Produk::query()
            ->orderBy('nama')
            ->get();

        return view('beranda', compact('bestSeller', 'kategori', 'produk'));
    }
}
