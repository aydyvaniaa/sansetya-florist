<?php

namespace App\Services;

use Illuminate\Support\Collection;

/**
 * Content-Based Filtering memakai TF-IDF dan Cosine Similarity.
 *
 * Alur:
 * 1. Setiap produk diubah menjadi "dokumen" berisi token dari atributnya.
 * 2. Token dihitung bobotnya dengan TF-IDF -> setiap produk jadi sebuah vektor.
 * 3. Vektor produk-produk yang dipilih dirata-rata menjadi satu "profil selera".
 * 4. Profil dibandingkan dengan produk lain memakai Cosine Similarity.
 * 5. Produk dengan skor tertinggi (yang belum dipilih) direkomendasikan.
 */
class RekomendasiService
{
    /** Kata umum yang tidak banyak membantu membedakan produk. */
    private array $stopword = [
        'dan', 'yang', 'untuk', 'dengan', 'di', 'ini', 'itu', 'dari',
        'atau', 'ke', 'pada', 'adalah', 'sebagai', 'dalam', 'akan',
        'bisa', 'jadi', 'cocok', 'agar', 'lebih', 'serta', 'oleh',
    ];

    /**
     * Bobot tiap atribut. Semakin besar, semakin berpengaruh pada kemiripan.
     * Bobot diterapkan dengan mengulang token sebanyak nilai bobotnya.
     */
    private array $bobotFitur = [
        'kategori'  => 3,
        'jenis'     => 3,
        'warna'     => 2,
        'acara'     => 2,
        'nama'      => 2,
        'deskripsi' => 1,
        'harga'     => 1,
    ];

    /**
     * Menghasilkan daftar ID produk beserta skor kemiripannya.
     * Skor berada pada rentang 0 sampai 1 dan diurutkan dari terbesar.
     *
     * @param  Collection  $semuaProduk  seluruh produk (model Produk)
     * @param  int|array   $idDipilih    satu ID atau beberapa ID produk yang dipilih
     * @param  int         $jumlah       jumlah rekomendasi maksimal
     * @return array<int, float>         [id_produk => skor]
     */
    public function rekomendasi(
        Collection $semuaProduk,
        int|array $idDipilih,
        int $jumlah = 4
    ): array {
        $idDipilih = array_values(array_unique(array_map('intval', (array) $idDipilih)));

        if ($semuaProduk->isEmpty() || $jumlah < 1 || $idDipilih === []) {
            return [];
        }

        // 1. Ubah setiap produk menjadi daftar token.
        $dokumen = [];
        foreach ($semuaProduk as $produk) {
            $dokumen[(int) $produk->id] = $this->buatToken($produk);
        }

        // Abaikan ID yang tidak ada di daftar produk.
        $idDipilih = array_values(array_filter(
            $idDipilih,
            fn ($id) => array_key_exists($id, $dokumen)
        ));

        if ($idDipilih === []) {
            return [];
        }

        // 2. Hitung IDF lalu TF-IDF untuk semua produk.
        $idf    = $this->hitungIdf($dokumen);
        $vektor = $this->hitungTfIdf($dokumen, $idf);

        // 3. Gabungkan produk-produk yang dipilih menjadi satu profil.
        $profil = $this->buatProfil($vektor, $idDipilih);

        // 4. Bandingkan profil dengan produk lain yang belum dipilih.
        $skor = [];
        foreach ($vektor as $id => $vektorProduk) {
            if (in_array($id, $idDipilih, true)) {
                continue;
            }

            $nilai = $this->cosine($profil, $vektorProduk);
            if ($nilai > 0) {
                $skor[$id] = $nilai;
            }
        }

        // 5. Urutkan dari skor terbesar lalu ambil beberapa teratas.
        arsort($skor, SORT_NUMERIC);

        return array_slice($skor, 0, $jumlah, true);
    }

    /**
     * Mengubah atribut produk menjadi daftar token berbobot.
     * Setiap token diberi awalan nama atribut (contoh: "warna_merah")
     * supaya kata yang sama dari kolom berbeda tidak tercampur.
     */
    private function buatToken($produk): array
    {
        $isi = [
            'kategori'  => $produk->kategori ?? '',
            'jenis'     => $produk->jenis_bunga ?? '',
            'warna'     => $produk->warna ?? '',
            'acara'     => $produk->acara ?? '',
            'nama'      => $produk->nama ?? '',
            'deskripsi' => $produk->deskripsi ?? '',
            'harga'     => $this->kelompokHarga((int) ($produk->harga ?? 0)),
        ];

        $tokens = [];
        foreach ($isi as $label => $teks) {
            $bobot = $this->bobotFitur[$label];

            foreach ($this->tokenisasi((string) $teks) as $kata) {
                for ($i = 0; $i < $bobot; $i++) {
                    $tokens[] = $label . '_' . $kata;
                }
            }
        }

        return $tokens;
    }

    /** Harga dikelompokkan agar produk dengan rentang harga berdekatan sedikit mirip. */
    private function kelompokHarga(int $harga): string
    {
        if ($harga < 100000) {
            return 'murah';
        }

        if ($harga <= 200000) {
            return 'menengah';
        }

        return 'tinggi';
    }

    /** Mengubah teks menjadi token huruf kecil dan menghapus stopword. */
    private function tokenisasi(string $teks): array
    {
        $teks   = mb_strtolower($teks, 'UTF-8');
        $tokens = preg_split('/[^\p{L}\p{N}]+/u', $teks, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        return array_values(array_filter($tokens, function ($token) {
            return mb_strlen($token, 'UTF-8') > 1
                && !in_array($token, $this->stopword, true);
        }));
    }

    /**
     * IDF = log(1 + N / (1 + df)) + 1
     * df = jumlah dokumen yang mengandung token. Token langka mendapat bobot lebih besar.
     *
     * @param  array<int, array<int, string>>  $dokumen
     * @return array<string, float>
     */
    private function hitungIdf(array $dokumen): array
    {
        $jumlahDokumen = count($dokumen);
        $df = [];

        foreach ($dokumen as $tokens) {
            foreach (array_unique($tokens) as $token) {
                $df[$token] = ($df[$token] ?? 0) + 1;
            }
        }

        $idf = [];
        foreach ($df as $token => $frekuensi) {
            $idf[$token] = log(1 + ($jumlahDokumen / (1 + $frekuensi))) + 1;
        }

        return $idf;
    }

    /**
     * TF-IDF = (frekuensi token / total token dokumen) x IDF token.
     *
     * @return array<int, array<string, float>>  [id_produk => [token => bobot]]
     */
    private function hitungTfIdf(array $dokumen, array $idf): array
    {
        $vektor = [];

        foreach ($dokumen as $id => $tokens) {
            $vektorProduk = [];
            $totalToken   = count($tokens);

            if ($totalToken > 0) {
                foreach (array_count_values($tokens) as $token => $frekuensi) {
                    $tf = $frekuensi / $totalToken;
                    $vektorProduk[$token] = $tf * ($idf[$token] ?? 0);
                }
            }

            $vektor[$id] = $vektorProduk;
        }

        return $vektor;
    }

    /**
     * Profil selera = rata-rata vektor TF-IDF dari semua produk yang dipilih.
     * Jika hanya satu produk dipilih, profil sama dengan vektor produk itu.
     */
    private function buatProfil(array $vektor, array $idDipilih): array
    {
        $profil = [];

        foreach ($idDipilih as $id) {
            foreach ($vektor[$id] as $token => $nilai) {
                $profil[$token] = ($profil[$token] ?? 0) + $nilai;
            }
        }

        $jumlah = count($idDipilih);
        foreach ($profil as $token => $nilai) {
            $profil[$token] = $nilai / $jumlah;
        }

        return $profil;
    }

    /** Cosine Similarity = (A . B) / (|A| x |B|). */
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