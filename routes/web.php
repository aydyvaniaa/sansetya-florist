<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\RekomendasiController;

Route::get('/', [BerandaController::class, 'index'])
    ->name('beranda');

Route::get('/rekomendasi/{id}', [RekomendasiController::class, 'show'])
    ->whereNumber('id')
    ->name('rekomendasi.show');