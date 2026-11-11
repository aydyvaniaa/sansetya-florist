<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\RekomendasiController;

Route::get('/', [BerandaController::class, 'index'])
    ->name('beranda');

// Halaman detail produk + produk serupa
Route::get('/produk/{id}', [ProdukController::class, 'show'])
    ->whereNumber('id')
    ->name('produk.show');

// Keranjang
Route::get('/keranjang', [KeranjangController::class, 'index'])
    ->name('keranjang.index');

Route::post('/keranjang', [ProdukController::class, 'tambahKeKeranjang'])
    ->name('keranjang.tambah');

Route::delete('/keranjang', [KeranjangController::class, 'kosongkan'])
    ->name('keranjang.kosongkan');

Route::patch('/keranjang/{id}', [KeranjangController::class, 'ubah'])
    ->whereNumber('id')
    ->name('keranjang.ubah');

Route::delete('/keranjang/{id}', [KeranjangController::class, 'hapus'])
    ->whereNumber('id')
    ->name('keranjang.hapus');

// Hapus riwayat produk yang dibuka dan isi keranjang (mulai ulang rekomendasi)
Route::post('/riwayat/reset', [ProdukController::class, 'resetRiwayat'])
    ->name('riwayat.reset');

// API rekomendasi (JSON) dari versi sebelumnya, boleh dibiarkan
Route::get('/rekomendasi/{id}', [RekomendasiController::class, 'show'])
    ->whereNumber('id')
    ->name('rekomendasi.show');

Route::delete('/rekomendasi', [RekomendasiController::class, 'reset'])
    ->name('rekomendasi.reset');