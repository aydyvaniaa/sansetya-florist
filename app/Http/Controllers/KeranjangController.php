<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    /** Keranjang disimpan di session dengan bentuk [id_produk => jumlah]. */
    private const KUNCI = 'keranjang';

    private const MAKS_JUMLAH = 99;

    /** Halaman keranjang. */
    public function index(Request $request)
    {
        $keranjang = $this->ambil($request);

        $produk = Produk::whereIn('id', array_keys($keranjang))->get()->keyBy('id');

        $item = [];
        $total = 0;

        foreach ($keranjang as $id => $jumlah) {
            $p = $produk->get($id);

            if (!$p) {
                continue; // produk sudah dihapus dari database
            }

            $subtotal = (int) $p->harga * $jumlah;
            $total += $subtotal;

            $item[] = [
                'produk'   => $p,
                'jumlah'   => $jumlah,
                'subtotal' => $subtotal,
            ];
        }

        return view('keranjang.index', compact('item', 'total'));
    }

    /** Tombol + / - di halaman keranjang. */
    public function ubah(Request $request, int $id): RedirectResponse
    {
        $request->validate(['aksi' => ['required', 'in:tambah,kurang']]);

        $keranjang = $this->ambil($request);

        if (isset($keranjang[$id])) {
            $baru = $keranjang[$id] + ($request->aksi === 'tambah' ? 1 : -1);
            $keranjang[$id] = max(1, min(self::MAKS_JUMLAH, $baru));
            $request->session()->put(self::KUNCI, $keranjang);
        }

        return redirect()->route('keranjang.index');
    }

    /** Hapus satu produk dari keranjang. */
    public function hapus(Request $request, int $id): RedirectResponse
    {
        $keranjang = $this->ambil($request);
        unset($keranjang[$id]);
        $request->session()->put(self::KUNCI, $keranjang);

        return redirect()->route('keranjang.index')->with('sukses', 'Produk dihapus dari keranjang.');
    }

    /** Kosongkan seluruh keranjang. */
    public function kosongkan(Request $request): RedirectResponse
    {
        $request->session()->forget(self::KUNCI);

        return redirect()->route('keranjang.index')->with('sukses', 'Keranjang dikosongkan.');
    }

    /** @return array<int,int> */
    private function ambil(Request $request): array
    {
        $data = [];

        foreach ((array) $request->session()->get(self::KUNCI, []) as $id => $jumlah) {
            $data[(int) $id] = (int) $jumlah;
        }

        return $data;
    }
}