# Sansetya Florist

Website penjualan Sansetya Florist (Laravel). Fitur rekomendasi memakai Content-Based Filtering (TF-IDF dan cosine similarity).

## Cara Menjalankan
1. `git clone https://github.com/aydyvaniaa/sansetya-florist.git`
2. `cd sansetya-florist`
3. `composer install`
4. `copy .env.example .env`
5. `php artisan key:generate`
6. Atur database di `.env` (SQLite: `DB_CONNECTION=sqlite`)
7. `php artisan migrate:fresh --seed`
8. `php artisan serve`

## Fitur Rekomendasi
Saat produk diklik, sistem membandingkan fitur produk (kategori, nama, deskripsi, tags, rentang harga) dengan produk lain, lalu menampilkan 4 produk paling mirip di bawahnya.