<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'Buket Mawar Merah', 'harga' => 150000, 'kategori' => 'Buket', 'gambar' => 'buket-mawar.jpg', 'terjual' => 120],
            ['nama' => 'Buket Tulip Pink', 'harga' => 175000, 'kategori' => 'Buket', 'gambar' => 'buket-tulip.jpg', 'terjual' => 80],
            ['nama' => 'Bunga Meja Minimalis', 'harga' => 95000, 'kategori' => 'Bunga Hias Meja', 'gambar' => 'meja-minimalis.jpg', 'terjual' => 45],
            ['nama' => 'Bloom Box Pastel', 'harga' => 200000, 'kategori' => 'Bloom Box', 'gambar' => 'bloom-pastel.jpg', 'terjual' => 95],
            ['nama' => 'Bunga Artificial Peony', 'harga' => 85000, 'kategori' => 'Artificial', 'gambar' => 'artificial-peony.jpg', 'terjual' => 60],
            ['nama' => 'Vas Keramik Putih', 'harga' => 70000, 'kategori' => 'Vas Bunga', 'gambar' => 'vas-putih.jpg', 'terjual' => 30],
            ['nama' => 'Buket Custom', 'harga' => 250000, 'kategori' => 'Custom', 'gambar' => 'custom.jpg', 'terjual' => 25],
        ];

        foreach ($data as $item) {
            Produk::create($item);
        }
    }
}