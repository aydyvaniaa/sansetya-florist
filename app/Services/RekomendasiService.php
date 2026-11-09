<?php

namespace App\Services;

use Illuminate\Support\Collection;

class RekomendasiService
{
    /** Kata umum yang tidak banyak membantu membedakan produk. */
    private array $stopword = [
        'dan', 'yang', 'untuk', 'dengan', 'di', 'ini', 'itu', 'dari',
        'atau', 'ke', 'pada', 'adalah', 'sebagai', 'dalam', 'akan',
        'bisa', 'jadi', 'cocok', 'agar', 'lebih', 'serta', 'oleh',
    ];

    /**
     * Menghasilkan daftar ID produk beserta skor kemiripannya.
     * Skor berada pada rentang 0 sampai 1 dan diurutkan dari terbesar.
     *
     * @return array<int, float>
     */
    public function rekomendasi(
        Collection $semuaProduk,
        int $idProduk,
        int $jumlah = 4
    ): array {
        if ($semuaProduk->isEmpty() || $jumlah < 1) {
            return [];
        }

        // Setiap produk diubah menjadi dokumen/token berdasarkan atributnya.
        $dokumen = [];
        foreach ($semuaProduk as $produk) {
            $dokumen[(int) $produk->id] = $this->tokenisasi(
                $this->gabungFitur($produk)
            );
        }

        if (!array_key_exists($idProduk, $dokumen)) {
            return [];
        }

        // Document Frequency: jumlah dokumen yang mengandung sebuah token.
        $jumlahDokumen = count($dokumen);
        $df = [];

        foreach ($dokumen as $tokens) {
            foreach (array_unique($tokens) as $token) {
                $df[$token] = ($df[$token] ?? 0) + 1;
            }
        }

        // Smoothed IDF mencegah pembagian nol dan menjaga bobot tetap positif.
        $idf = [];
        foreach ($df as $token => $frekuensi) {
            $idf[$token] = log(1 + ($jumlahDokumen / (1 + $frekuensi))) + 1;
        }

        // TF-IDF = frekuensi token dalam dokumen x nilai IDF token.
        $vektor = [];
        foreach ($dokumen as $id => $tokens) {
            $vektorProduk = [];
            $totalToken = count($tokens);

            if ($totalToken > 0) {
                foreach (array_count_values($tokens) as $token => $frekuensi) {
                    $tf = $frekuensi / $totalToken;
                    $vektorProduk[$token] = $tf * ($idf[$token] ?? 0);
                }
            }

            $vektor[$id] = $vektorProduk;
        }

        // Bandingkan produk terpilih dengan produk lain menggunakan cosine.
        $skor = [];
        foreach ($vektor as $id => $vektorLain) {
            if ((int) $id === $idProduk) {
                continue;
            }

            $nilai = $this->cosine($vektor[$idProduk], $vektorLain);
            if ($nilai > 0) {
                $skor[(int) $id] = $nilai;
            }
        }

        arsort($skor, SORT_NUMERIC);

        return array_slice($skor, 0, $jumlah, true);
    }

    /**
     * Menggabungkan atribut produk menjadi teks berbobot.
     * Pengulangan token berfungsi sebagai pembobotan fitur sederhana.
     */
    private function gabungFitur($produk): string
    {
        $fitur = [];

        $this->tambahkanFitur($fitur, 'kategori', $produk->kategori ?? '', 3);
        $this->tambahkanFitur($fitur, 'jenis', $produk->jenis_bunga ?? '', 3);
        $this->tambahkanFitur($fitur, 'warna', $produk->warna ?? '', 2);
        $this->tambahkanFitur($fitur, 'acara', $produk->acara ?? '', 2);
        $this->tambahkanFitur($fitur, 'nama', $produk->nama ?? '', 2);
        $this->tambahkanFitur($fitur, 'deskripsi', $produk->deskripsi ?? '', 1);

        // Harga dikelompokkan agar produk dengan rentang harga berdekatan
        // dapat memiliki sedikit kemiripan tambahan.
        $harga = (int) ($produk->harga ?? 0);
        if ($harga < 100000) {
            $kelompokHarga = 'murah';
        } elseif ($harga <= 200000) {
            $kelompokHarga = 'menengah';
        } else {
            $kelompokHarga = 'tinggi';
        }
        $this->tambahkanFitur($fitur, 'harga', $kelompokHarga, 1);

        return implode(' ', $fitur);
    }

    private function tambahkanFitur(array &$fitur, string $label, string $nilai, int $bobot): void
    {
        $nilai = trim($nilai);
        if ($nilai === '') {
            return;
        }

        // Label atribut membedakan kata yang berasal dari kolom berbeda.
        $fitur[] = str_repeat($label . '_', $bobot) . $nilai;
        $fitur[] = str_repeat($nilai . ' ', max(0, $bobot - 1));
    }

    /** Mengubah teks menjadi token huruf kecil dan menghapus stopword. */
    private function tokenisasi(string $teks): array
    {
        $teks = mb_strtolower($teks, 'UTF-8');
        $tokens = preg_split('/[^\p{L}\p{N}_]+/u', $teks, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        return array_values(array_filter($tokens, function ($token) {
            return mb_strlen($token, 'UTF-8') > 1
                && !in_array($token, $this->stopword, true);
        }));
    }

    /** Menghitung Cosine Similarity antara dua vektor TF-IDF. */
    private function cosine(array $a, array $b): float
    {
        if ($a === [] || $b === []) {
            return 0.0;
        }

        $dot = 0.0;
        foreach ($a as $token => $nilai) {
            if (isset($b[$token])) {
                $dot += $nilai * $b[$token];
            }
        }

        $normA = sqrt(array_sum(array_map(fn ($x) => $x * $x, $a)));
        $normB = sqrt(array_sum(array_map(fn ($x) => $x * $x, $b)));

        if ($normA == 0.0 || $normB == 0.0) {
            return 0.0;
        }

        return max(0.0, min(1.0, $dot / ($normA * $normB)));
    }
}
