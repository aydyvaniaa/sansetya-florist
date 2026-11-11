<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Services\RekomendasiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RekomendasiController extends Controller
{
    /** Kunci session untuk menyimpan produk-produk yang pernah dipilih. */
    private const KUNCI_RIWAYAT = 'riwayat_produk';

    /** Jumlah maksimal produk yang diingat sebagai dasar rekomendasi. */
    private const MAKS_RIWAYAT = 10;

    /**
     * Dipanggil saat pelanggan memilih sebuah produk.
     * Produk disimpan ke riwayat, lalu rekomendasi dihitung dari SEMUA
     * produk yang sudah dipilih (bukan hanya yang terakhir diklik).
     */
    public function show(
        int $id,
        Request $request,
        RekomendasiService $rekomendasiService
    ): JsonResponse {
        $produkDipilih = Produk::findOrFail($id);
        $semuaProduk   = Produk::all();
        $produkById    = $semuaProduk->keyBy('id');

        $riwayat = $this->perbaruiRiwayat($request, (int) $produkDipilih->id, $produkById);

        $hasilSkor = $rekomendasiService->rekomendasi($semuaProduk, $riwayat, 4);

        $produkRiwayat = collect($riwayat)
            ->map(fn ($idRiwayat) => $produkById->get($idRiwayat))
            ->filter()
            ->values();

        $hasil = [];
        foreach ($hasilSkor as $produkId => $skor) {
            $produk = $produkById->get($produkId);

            if (!$produk) {
                continue;
            }

            $hasil[] = [
                'id'         => $produk->id,
                'nama'       => $produk->nama,
                'harga'      => (int) $produk->harga,
                'kategori'   => $produk->kategori,
                'gambar'     => $produk->gambar,
                'gambar_url' => $produk->gambar
                    ? asset('images/' . ltrim($produk->gambar, '/'))
                    : null,
                'similarity' => round($skor * 100, 2),
                'alasan'     => $this->alasanKemiripan($produk, $produkRiwayat),
            ];
        }

        return response()->json([
            'produk_dipilih'     => [
                'id'   => $produkDipilih->id,
                'nama' => $produkDipilih->nama,
            ],
            'riwayat'            => $produkRiwayat->map(fn ($p) => [
                'id'   => $p->id,
                'nama' => $p->nama,
            ])->all(),
            'jumlah_rekomendasi' => count($hasil),
            'data'               => $hasil,
        ]);
    }

    /** Menghapus riwayat pilihan agar rekomendasi mulai dari awal. */
    public function reset(Request $request): JsonResponse
    {
        $request->session()->forget(self::KUNCI_RIWAYAT);

        return response()->json(['status' => 'ok']);
    }

    /**
     * Menambahkan produk ke riwayat (tanpa duplikat, yang terbaru di akhir),
     * membatasi jumlahnya, lalu menyimpannya ke session.
     *
     * @return int[]
     */
    private function perbaruiRiwayat(Request $request, int $id, Collection $produkById): array
    {
        $riwayat = array_map('intval', (array) $request->session()->get(self::KUNCI_RIWAYAT, []));

        $riwayat   = array_values(array_diff($riwayat, [$id]));
        $riwayat[] = $id;
        $riwayat   = array_slice($riwayat, -self::MAKS_RIWAYAT);

        // Buang ID produk yang sudah tidak ada di database.
        $riwayat = array_values(array_filter(
            $riwayat,
            fn ($idRiwayat) => $produkById->has($idRiwayat)
        ));

        $request->session()->put(self::KUNCI_RIWAYAT, $riwayat);

        return $riwayat;
    }

    /**
     * Alasan singkat kenapa sebuah produk direkomendasikan
     * (atribut yang sama dengan produk yang dipilih). Maksimal 3.
     *
     * @return string[]
     */
    private function alasanKemiripan(Produk $kandidat, Collection $pilihan): array
    {
        $alasan = [];

        foreach ($pilihan as $p) {
            if ($p->kategori && $p->kategori === $kandidat->kategori) {
                $alasan['Kategori sama: ' . $kandidat->kategori] = true;
            }

            if ($p->jenis_bunga && mb_strtolower($p->jenis_bunga) === mb_strtolower((string) $kandidat->jenis_bunga)) {
                $alasan['Jenis bunga sama: ' . $kandidat->jenis_bunga] = true;
            }

            if ($p->warna && mb_strtolower($p->warna) === mb_strtolower((string) $kandidat->warna)) {
                $alasan['Warna sama: ' . $kandidat->warna] = true;
            }

            $acaraSama = array_diff(
                array_intersect(
                    preg_split('/\s+/', mb_strtolower((string) $p->acara), -1, PREG_SPLIT_NO_EMPTY) ?: [],
                    preg_split('/\s+/', mb_strtolower((string) $kandidat->acara), -1, PREG_SPLIT_NO_EMPTY) ?: []
                ),
                ['semua']
            );

            if ($acaraSama !== []) {
                $alasan['Acara serupa: ' . implode(', ', $acaraSama)] = true;
            }
        }

        return array_slice(array_keys($alasan), 0, 3);
    }
}