<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\City;
use App\Models\Packet;
use App\Models\PacketRinci;
use App\Models\Pelanggan;
use App\Models\Province;
use App\Models\v2\Master\Pelanggan as MasterPelanggan;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getKotaByProv($id)
    {

        $kota = City::where('province_id', $id)->get('name');

        return response()->json($kota, 200);
    }

    public function getKodePelanggan($kode_pelanggan)
    {

        $res = Pelanggan::where('kode_pelanggan', $kode_pelanggan)->first('kode_pelanggan');

        return response()->json($res, 200);
    }

    public function getProduk($id)
    {
        $res = Barang::find($id);
        return response()->json($res, 200);
    }

    public function getDataPaketProduk()
    {
        $res = Packet::all();

        return response()->json($res, 200);
    }

    public function getRincianPaket(Request $request)
    {
        $res = PacketRinci::with('barang')->where('id_packet', $request->paket_id)->get();
        $res['nama_paket'] = Packet::find($request->paket_id)->packet_name;
        $res['jumlah'] = PacketRinci::with('barang')->where('id_packet', $request->paket_id)->count();

        return response()->json($res, 200);
    }

    public function storePelangganBaru(Request $request)
    {
        // insert ke data tabel baru
        MasterPelanggan::create([
            'kode_pelanggan' => MasterPelanggan::kodeTerakhir() + 1,
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_handphone' => $request->handphone_pelanggan,
            'provinsi' => Province::find($request->provinsi)->name,
            'kota' => $request->kota,
            'detil_alamat' => $request->detail_alamat,
        ]);

        return redirect()->back()->with('sukses', 'Pelanggan ' . $request->nama_pelanggan . ' berhasil ditambahkan');
    }
}
