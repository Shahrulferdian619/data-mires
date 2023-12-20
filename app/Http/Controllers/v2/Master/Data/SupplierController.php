<?php

namespace App\Http\Controllers\v2\Master\Data;

use App\Http\Controllers\Controller;
use App\Models\v2\Master\Supplier;
use App\Models\v2\Master\TipeSupplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['supplier'] = Supplier::all();

        return view('v2.master.supplier.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['tipeSupplier'] = TipeSupplier::all();

        return view('v2.master.supplier.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dataValidated = $request->validate([
            'tipe_supplier_id' => 'required|numeric',
            'nama' => 'required|unique:second_mysql.supplier,nama',
            'kode' => 'required|unique:second_mysql.supplier,kode',
            'nama_pic' => 'nullable|max:100',
            'keterangan' => 'nullable|max:200',
            'no_telp' => 'required|numeric',
            'provinsi' => 'nullable|max:100',
            'kota' => 'nullable|max:100',
            'nama_pic' => 'nullable|max:100',
            'detil_alamat' => 'nullable|max:250',
            'nomer_rekening' => 'nullable|numeric',
        ]);

        Supplier::create($dataValidated);

        return back()->with('sukses', 'DATA SUPPLIER BERHASIL DITAMBAHKAN');
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
        $data['supplier'] = Supplier::findOrFail($id);
        $data['tipeSupplier'] = TipeSupplier::all();

        return view('v2.master.supplier.edit', compact('data'));
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
        $dataValidated = $request->validate([
            'tipe_supplier_id' => 'required|numeric',
            'nama' => 'required|unique:second_mysql.supplier,nama,' . $id,
            'kode' => 'required|unique:second_mysql.supplier,kode,' . $id,
            'nama_pic' => 'nullable|max:100',
            'keterangan' => 'nullable|max:200',
            'no_telp' => 'required|numeric',
            'provinsi' => 'nullable|max:100',
            'kota' => 'nullable|max:100',
            'nama_pic' => 'nullable|max:100',
            'detil_alamat' => 'nullable|max:250',
            'nomer_rekening' => 'nullable|numeric',
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->update($dataValidated);

        return back()->with('sukses', 'DATA BERHASIL DIPERBARUI');
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
