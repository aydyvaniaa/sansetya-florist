<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['Buket Mawar Merah', 150000, 'Buket', 'mawar', 'merah', 'valentine anniversary', 'Buket mawar merah segar untuk pasangan, romantis dan elegan.', 'buket-mawar.jpg', 120],
            ['Buket Tulip Pink', 175000, 'Buket', 'tulip', 'pink', 'ulang tahun wisuda', 'Buket tulip pink lembut yang manis untuk hadiah ulang tahun atau wisuda.', 'buket-tulip.jpg', 80],
            ['Bunga Meja Minimalis', 95000, 'Bunga Hias Meja', 'baby breath', 'putih', 'dekorasi rumah', 'Rangkaian bunga kecil bergaya minimalis untuk mempercantik meja.', 'meja-minimalis.jpg', 45],
            ['Bloom Box Pastel', 200000, 'Bloom Box', 'mawar', 'pastel', 'ulang tahun hadiah', 'Kotak bunga warna pastel yang cantik, cocok jadi kado spesial.', 'bloom-pastel.jpg', 95],
            ['Bunga Artificial Peony', 85000, 'Artificial', 'peony', 'pink', 'dekorasi rumah', 'Peony artificial yang awet dan nggak layu untuk hiasan ruangan.', 'artificial-peony.jpg', 60],
            ['Buket Custom', 250000, 'Custom', 'campuran', 'bebas', 'semua acara', 'Buket sesuai permintaan, jenis bunga dan warna bisa dipilih sendiri.', 'custom.jpg', 25],
            ['Buket Bunga Matahari', 150000, 'Buket', 'matahari', 'kuning', 'wisuda ulang tahun', 'Buket bunga matahari kuning yang ceria untuk wisuda atau ulang tahun.', 'buket-matahari.jpg', 70],
            ['Buket Mix Warna-Warni', 100000, 'Buket', 'campuran', 'warna-warni', 'ulang tahun', 'Buket campuran bunga warna-warni yang meriah dan penuh semangat.', 'buket-mix.jpg', 90],
            ['Buket Mawar Putih', 160000, 'Buket', 'mawar', 'putih', 'pernikahan anniversary', 'Buket mawar putih yang elegan untuk pernikahan atau anniversary.', 'buket-mawar-putih.jpg', 40],
            ['Buket Lily Putih', 180000, 'Buket', 'lily', 'putih', 'pernikahan wisuda', 'Buket lily putih yang harum dan anggun untuk momen spesial.', 'buket-lily.jpg', 35],
            ['Bunga Meja Tulip Kuning', 110000, 'Bunga Hias Meja', 'tulip', 'kuning', 'dekorasi rumah kantor', 'Tulip kuning segar untuk menghidupkan meja kerja atau ruang tamu.', 'meja-tulip.jpg', 38],
            ['Bunga Meja Lavender', 105000, 'Bunga Hias Meja', 'lavender', 'ungu', 'dekorasi rumah', 'Lavender ungu yang wangi dan menenangkan untuk hiasan meja.', 'meja-lavender.jpg', 42],
            ['Bloom Box Merah Romantis', 220000, 'Bloom Box', 'mawar', 'merah', 'valentine anniversary', 'Kotak bunga mawar merah untuk kejutan romantis bersama pasangan.', 'bloom-merah.jpg', 85],
            ['Bloom Box Sunflower', 195000, 'Bloom Box', 'matahari', 'kuning', 'ulang tahun hadiah', 'Kotak bunga matahari kuning yang cerah, cocok sebagai hadiah.', 'bloom-sunflower.jpg', 55],
            ['Artificial Mawar Pink', 80000, 'Artificial', 'mawar', 'pink', 'dekorasi rumah hadiah', 'Mawar pink artificial yang awet, bisa jadi hiasan atau hadiah.', 'artificial-mawar.jpg', 50],
            ['Artificial Tulip Putih', 75000, 'Artificial', 'tulip', 'putih', 'dekorasi kantor', 'Tulip putih artificial yang rapi untuk dekorasi ruang kerja.', 'artificial-tulip.jpg', 33],
        ];

        foreach ($data as $d) {
            Produk::create([
                'nama'        => $d[0],
                'harga'       => $d[1],
                'kategori'    => $d[2],
                'jenis_bunga' => $d[3],
                'warna'       => $d[4],
                'acara'       => $d[5],
                'deskripsi'   => $d[6],
                'gambar'      => $d[7],
                'terjual'     => $d[8],
            ]);
        }
    }
}