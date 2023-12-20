<?php

namespace App\Http\Controllers\v2\Master\Data;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\v2\Master\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['pelanggan'] = Pelanggan::active()->get();

        return view('v2.master.pelanggan.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('v2.master.pelanggan.create');  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dump($request->all());
        $request->validate([
            'nama_pelanggan'    => 'required|unique:second_mysql.pelanggan,nama_pelanggan',
            'no_handphone'      => 'nullable|numeric',
            'provinsi'          => 'required',
            'kota'              => 'required',
            'detil_alamat'      => 'required',
            'keterangan'        => 'nullable'
        ]);

        $provinsi = explode('-', $request->provinsi);
        $kota = explode('-',$request->kota);

        $data = [
            'kode_pelanggan'    => Pelanggan::kodeTerakhir() + 1,
            'nama_pelanggan'    => $request->nama_pelanggan,
            'no_handphone'      => $request->no_handphone,
            'provinsi'          => $provinsi[1],
            'kota'              => $kota[1],
            'detil_alamat'      => $request->detil_alamat,
            'keterangan'        => $request->keterangan
        ];

        Pelanggan::create($data);
        return back()->with('sukses','DATA BERHASIL DISIMPAN'); 
    }
    


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['pelanggan'] = Pelanggan::findOrFail($id);

        return view('v2.master.pelanggan.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // validasi
        $request->validate([
            'nama_pelanggan' => 'required|unique:second_mysql.pelanggan,nama_pelanggan,' . $id,
            'no_handphone' => 'nullable|numeric',
            'provinsi' => 'required',
            'kota' => 'required',
            'detil_alamat' => 'required',
            'keterangan' => 'nullable'
        ]);

        $pelanggan = Pelanggan::findOrFail($id);

        // Periksa apakah nilai select provinsi dan kota sama dengan nilai dari database
        if ($request->provinsi === $pelanggan->provinsi) {
            // Jika tidak ada perubahan pada select provinsi, gunakan nilai dari database
            $provinsi = $pelanggan->provinsi;
        } else {
            // Jika ada perubahan pada select provinsi, gunakan explode untuk mendapatkan nilai provinsi yang dipilih
            $provinsiData = explode('-', $request->provinsi);
            $provinsi = isset($provinsiData[1]) ? $provinsiData[1] : ''; // Gunakan nilai default jika elemen kedua tidak ada
        }

        if ($request->kota === $pelanggan->kota) {
            // Jika tidak ada perubahan pada select kota, gunakan nilai dari database
            $kota = $pelanggan->kota;
        } else {
            // Jika ada perubahan pada select kota, gunakan explode untuk mendapatkan nilai kota yang dipilih
            $kotaData = explode('-', $request->kota);
            $kota = isset($kotaData[1]) ? $kotaData[1] : ''; // Gunakan nilai default jika elemen kedua tidak ada
        }

        $pelanggan->update([
            'kode_pelanggan'    => Pelanggan::kodeTerakhir() + 1,
            'nama_pelanggan'    => $request->nama_pelanggan,
            'no_handphone'      => $request->no_handphone,
            'provinsi'          => $provinsi,
            'kota'              => $kota,
            'detil_alamat'      => $request->detil_alamat,
            'keterangan'        => $request->keterangan,
            'status_aktif'      => $request->status_aktif
        ]);

        return back()->with('sukses','DATA BERHASIL DIPERBARUI');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
