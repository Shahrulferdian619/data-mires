<?php

namespace App\Http\Controllers\v2\Service;

use App\Http\Controllers\Controller;
use App\Models\v2\Master\Gudang;
use App\Models\v2\Persediaan\Barang;
use App\Models\v2\Service\UpdateStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateStokController extends Controller
{
    public function tambahStok($data, $gudang_asal, $gudang_tujuan)
    {
        // get id gudang asal
        $gudang_asal_id = Gudang::where('nama_gudang', $gudang_asal)->first()->id;

        // get id gudang tujuan
        $gudang_tujuan_id = Gudang::where('nama_gudang', $gudang_tujuan)->first()->id;

        foreach ($data->produk_id as $index => $produk) {
            $p = Barang::find($produk);

            $stok_a = UpdateStok::where('gudang_id', $gudang_asal_id)->where('produk_id', $produk)->first();
            $stok_t = UpdateStok::where('gudang_id', $gudang_tujuan_id)->where('produk_id', $produk)->first();

            // proses update stok gudang asal
            if (!$stok_a) { // bila data awal stok belum ada, buat baru
                $stok_a = new UpdateStok();
                $stok_a->updated_by = Auth::user()->id;
                $stok_a->produk_id = $produk;
                $stok_a->gudang_id = $gudang_asal_id;
                $stok_a->nama_gudang = $gudang_asal;
                $stok_a->kode_produk = $p->kode_barang;
                $stok_a->nama_produk = $p->nama_barang;
                $stok_a->kuantitas -= $data->kuantitas[$index];
            } else {
                $stok_a->kuantitas -= $data->kuantitas[$index];
            }

            // proses update stok gudang tujuan
            if (!$stok_t) { // bila data awal stok belum ada, buat baru
                $stok_t = new UpdateStok();
                $stok_t->updated_by = Auth::user()->id;
                $stok_t->produk_id = $produk;
                $stok_t->gudang_id = $gudang_tujuan_id;
                $stok_t->nama_gudang = $gudang_tujuan;
                $stok_t->kode_produk = $p->kode_barang;
                $stok_t->nama_produk = $p->nama_barang;
                $stok_t->kuantitas += $data->kuantitas[$index];
            } else {
                $stok_t->kuantitas += $data->kuantitas[$index];
            }

            $stok_a->save();
            $stok_t->save();
        }
    }

    public function updateStok($data, $updateData, $gudang_asal, $gudang_tujuan)
    {
        //dd($updateData->rinci);

        // get id gudang asal
        $gudang_asal_id = Gudang::where('nama_gudang', $gudang_asal)->first()->id;

        // get id gudang tujuan
        $gudang_tujuan_id = Gudang::where('nama_gudang', $gudang_tujuan)->first()->id;

        foreach ($data->produk_id as $index => $produk) {
            $p = Barang::find($produk);

            $stok_a = UpdateStok::where('gudang_id', $gudang_asal_id)->where('produk_id', $produk)->first();
            $stok_t = UpdateStok::where('gudang_id', $gudang_tujuan_id)->where('produk_id', $produk)->first();

            // proses update stok gudang asal
            if (!$stok_a) { // bila data awal stok belum ada, buat baru
                $stok_a = new UpdateStok();
                $stok_a->updated_by = Auth::user()->id;
                $stok_a->produk_id = $produk;
                $stok_a->gudang_id = $gudang_asal_id;
                $stok_a->nama_gudang = $gudang_asal;
                $stok_a->kode_produk = $p->kode_barang;
                $stok_a->nama_produk = $p->nama_barang;
                $stok_a->kuantitas -= $data->kuantitas[$index];
            } else {
                $stok_a->kuantitas += $updateData->rinci[$index]->kuantitas - $data->kuantitas[$index];
            }

            // proses update stok gudang tujuan
            if (!$stok_t) { // bila data awal stok belum ada, buat baru
                $stok_t = new UpdateStok();
                $stok_t->updated_by = Auth::user()->id;
                $stok_t->produk_id = $produk;
                $stok_t->gudang_id = $gudang_tujuan_id;
                $stok_t->nama_gudang = $gudang_tujuan;
                $stok_t->kode_produk = $p->kode_barang;
                $stok_t->nama_produk = $p->nama_barang;
                $stok_t->kuantitas += $data->kuantitas[$index];
            } else {
                $stok_t->kuantitas += $data->kuantitas[$index] - $updateData->rinci[$index]->kuantitas;
            }

            $stok_a->save();
            $stok_t->save();
        }
    }

    public function kurangStok($data, $gudang_asal, $gudang_tujuan)
    {
        // get id gudang asal
        $gudang_asal_id = Gudang::where('nama_gudang', $gudang_asal)->first()->id;

        // get id gudang tujuan
        $gudang_tujuan_id = Gudang::where('nama_gudang', $gudang_tujuan)->first()->id;

        foreach ($data->rinci as $r) {
            $p = Barang::find($r->produk_id);

            $stok_a = UpdateStok::where('gudang_id', $gudang_asal_id)->where('produk_id', $r->produk_id)->first();
            $stok_t = UpdateStok::where('gudang_id', $gudang_tujuan_id)->where('produk_id', $r->produk_id)->first();

            $stok_a->kuantitas += $r->kuantitas;
            $stok_t->kuantitas -= $r->kuantitas;

            $stok_a->save();
            $stok_t->save();
        }
    }
}
