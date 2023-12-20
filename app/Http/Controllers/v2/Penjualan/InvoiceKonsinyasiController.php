<?php

namespace App\Http\Controllers\v2\Penjualan;

use App\Http\Controllers\Controller;
use App\Models\Packet;
use App\Models\Province;
use App\Models\Sales;
use App\Models\v2\Master\Coa;
use App\Models\v2\Master\Gudang;
use App\Models\v2\Master\Pelanggan;
use App\Models\v2\Persediaan\Barang;
use Illuminate\Http\Request;

class InvoiceKonsinyasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('v2.penjualan.invoice-konsinyasi.create', [
            'pelanggan' => Pelanggan::active()->get(),
            'akun_bank' => Coa::bank()->get(),
            'sales' => Sales::all(),
            'gudang' => Gudang::all(),
            'produk' => Barang::produk()->get(),
            'akun_diskon' => Coa::pendapatan()->get(),
            'akun_ppn' => Coa::where('status_aktif', 1)->where('nama_coa', 'like', '%ppn%')->get(),
            'provinsi' => Province::all(),
            'paket' => Packet::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
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
        //
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
        //
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
