<?php

namespace App\Http\Controllers\v2\Daftar;

use App\Http\Controllers\Controller;
use App\Models\Kategoribarang;
use App\Models\v2\Master\Kategori\KategoriPelanggan;
use Illuminate\Http\Request;

class KategoriPelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['kategori_pelanggan'] = KategoriPelanggan::all();
        return view('v2.master.kategori.pelanggan.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('v2.master.kategori.pelanggan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'kategoriPelanggan.*.kategori_pelanggan'=>'required|min:3'
        ]);

        foreach($request->kategoriPelanggan as $item){
            KategoriPelanggan::create($item);
        }

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
        $data['kategori_pelanggan'] = KategoriPelanggan::findOrFail($id);
        return view('v2.master.kategori.pelanggan.edit', compact('data'));
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
        $request->validate([
            'kategori_pelanggan'=>'required|min:3'
        ]);

        $data = KategoriPelanggan::findOrFail($id);

        $data->update([
            'kategori_pelanggan'=>$request->kategori_pelanggan,
            'status_aktif'=>$request->status_aktif
        ]);
        return back()->with('sukses','DATA BERHASIL DISIMPAN'); 
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
