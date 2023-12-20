<?php

namespace App\Http\Controllers\v2\Master\Data;

use App\Http\Controllers\Controller;
use App\Models\v2\Master\Data\Sales;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Sales::all();
        return view('v2.master.sales.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('v2.master.sales.create');
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
            'nama_sales'=>'required|unique:sales'
        ]);

        // dd($request->all());

        $sales = new Sales;
        $sales->nama_sales = $request->nama_sales;
        $sales->target_total_invoice = explodeRupiah($request->target_total_invoice);
        $sales->bonus_presentase = $request->bonus_presentase;
        $sales->keterangan = $request->keterangan;
        $sales->kode = $request->kode_area.'-'.$request->nama_sales;
        $sales->kode_area = $request->kode_area;
        $sales->save();

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
        $data = Sales::find($id);
        return view('v2.master.sales.edit', compact('data'));
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
            'nama_sales' => 'required|unique:sales,nama_sales, '.$id,  
        ]);
        
        $sales = Sales::find($id);
        $sales->nama_sales = $request->nama_sales;
        $sales->kode = $request->kode_sales;
        $sales->kode_area = $request->kode_area;
        $sales->target_total_invoice = explodeRupiah($request->target_total_invoice);
        $sales->bonus_presentase = $request->bonus_presentase;
        $sales->keterangan = $request->keterangan;
        $sales->kode = $request->kode_area.'-'.$request->nama_sales;
        $sales->save();

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
