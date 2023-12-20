<?php

namespace App\Http\Controllers\v2\Master\Data;

use App\Http\Controllers\Controller;
use App\Models\v2\Master\Gudang;
use Illuminate\Http\Request;

class GudangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gudang = Gudang::all();
        return view('v2.master.gudang.index', compact('gudang'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('v2.master.gudang.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_gudang'=>'required',
            'pic_gudang'=>'nullable',
            'alamat_gudang'=>'nullable',
            'keterangan'=>'nullable'
        ]);

        Gudang::create($data);
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
        $data['gudang'] = Gudang::findOrFail($id);

        return view('v2.master.gudang.edit', compact('data'));
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
            'nama_gudang'=>'required',
        ]);

        $data = Gudang::findOrFail($id);

        $data->update([
            'nama_gudang'    => $request->nama_gudang,
            'pic_gudang'     => $request->pic_gudang,
            'alamat_gudang'  => $request->alamat_gudang,
            'keterangan'     => $request->keterangan
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
