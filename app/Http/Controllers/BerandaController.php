<?php

namespace App\Http\Controllers;

use App\Models\Produk;

class BerandaController extends Controller
{
    public function index()
    {
        $bestSeller = Produk::orderByDesc('terjual')->take(4)->get();
        $kategori   = Produk::select('kategori')->distinct()->pluck('kategori');
        $produk     = Produk::all();

        return view('beranda', compact('bestSeller', 'kategori', 'produk'));
    }
}