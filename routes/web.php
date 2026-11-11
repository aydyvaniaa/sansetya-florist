<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\RekomendasiController;

Route::get('/', [BerandaController::class, 'index'])
    ->name('beranda');

// Pilih produk -> hitung rekomendasi (berdasarkan semua produk yang sudah dipilih)
Route::get('/rekomendasi/{id}', [RekomendasiController::class, 'show'])
    ->whereNumber('id')
    ->name('rekomendasi.show');

// Hapus riwayat pilihan produk (mulai ulang rekomendasi)
Route::delete('/rekomendasi', [RekomendasiController::class, 'reset'])
    ->name('rekomendasi.reset');