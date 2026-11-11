<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Services\RekomendasiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /** Produk-produk yang pernah dibuka (dipakai juga oleh RekomendasiController). */
    private const KUNCI_RIWAYAT = 'riwayat_produk';

    /** Keranjang disimpan di session dengan bentuk [id_produk => jumlah]. */
    private const KUNCI_KERANJANG = 'keranjang';

    /** Jumlah maksimal produk yang diingat sebagai dasar rekomendasi. */
    private const MAKS_RIWAYAT = 10;

    private const MAKS_JUMLAH = 99;

    /**
     * Halaman detail produk.
     * Rekomendasi dihitung dari produk yang sedang dibuka, produk yang pernah
     * dibuka sebelumnya, dan produk yang sudah ada di keranjang.
     */
    public function show(int $id, Request $request, RekomendasiService $rekomendasiService)
    {
        $produk      = Produk::findOrFail($id);
        $semuaProduk = Produk::all();
        $produkById  = $semuaProduk->keyBy('id');

        // Simpan produk ini ke riwayat (tanpa duplikat, yang terbaru di akhir).
        $riwayat   = array_map('intval', (array) $request->session()->get(self::KUNCI_RIWAYAT, []));
        $riwayat   = array_values(array_diff($riwayat, [(int) $produk->id]));
        $riwayat[] = (int) $produk->id;
        $riwayat   = array_slice($riwayat, -self::MAKS_RIWAYAT);
        $riwayat   = array_values(array_filter($riwayat, fn ($i) => $produkById->has($i)));
        $request->session()->put(self::KUNCI_RIWAYAT, $riwayat);

        // Produk di keranjang ikut dihitung sebagai produk yang dipilih.
        $keranjang = array_map('intval', (array) $request->session()->get(self::KUNCI_KERANJANG, []));
        $idDasar   = array_values(array_unique(array_merge($riwayat, array_keys($keranjang))));
        $idDasar   = array_values(array_filter($idDasar, fn ($i) => $produkById->has($i)));

        // Hitung rekomendasi: [id_produk => skor 0..1], diurutkan dari terbesar.
        $skor = $rekomendasiService->rekomendasi($semuaProduk, $idDasar, 4);

        $rekomendasi = collect($skor)
            ->map(fn ($nilai, $idRek) => [
                'produk'     => $produkById->get($idRek),
                'similarity' => round($nilai * 100, 2),
            ])
            ->filter(fn ($baris) => $baris['produk'] !== null)
            ->values();

        $produkDasar = collect($idDasar)
            ->map(fn ($i) => $produkById->get($i))
            ->filter()
            ->values();

        return view('produk.show', [
            'produk'            => $produk,
            'rekomendasi'       => $rekomendasi,
            'produkDasar'       => $produkDasar,
            'jumlahDiKeranjang' => (int) ($keranjang[(int) $produk->id] ?? 0),
            'totalKeranjang'    => (int) array_sum($keranjang),
        ]);
    }

    /** Menambahkan produk ke keranjang (disimpan di session). */
    public function tambahKeKeranjang(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'produk_id' => ['required', 'integer', 'exists:produks,id'],
            'jumlah'    => ['required', 'integer', 'min:1', 'max:' . self::MAKS_JUMLAH],
        ]);

        $produk = Produk::findOrFail($data['produk_id']);
        $id     = (int) $produk->id;

        $keranjang      = array_map('intval', (array) $request->session()->get(self::KUNCI_KERANJANG, []));
        $keranjang[$id] = min(self::MAKS_JUMLAH, ($keranjang[$id] ?? 0) + (int) $data['jumlah']);
        $request->session()->put(self::KUNCI_KERANJANG, $keranjang);

        return redirect()
            ->route('keranjang.index')
            ->with('sukses', $data['jumlah'] . ' x ' . $produk->nama . ' berhasil ditambahkan ke keranjang.');
    }

    /** Menghapus riwayat produk yang dibuka dan isi keranjang (mulai ulang rekomendasi). */
    public function resetRiwayat(Request $request): RedirectResponse
    {
        $request->session()->forget([self::KUNCI_RIWAYAT, self::KUNCI_KERANJANG]);

        return back();
    }
}