<?php

namespace App\Http\Controllers\v2\produksi;

use App\Http\Controllers\Controller;
use App\Models\v2\Master\Barang;
use App\Models\v2\Produksi\SemiProduksi;
use Illuminate\Http\Request;

class SemiProduksiController extends Controller
{
    public function index()
    {
        $data = SemiProduksi::with('barang')->with('semiProduksiRinci.barang')->get();
        // dd($data);
        return view('v2.produksi.semi-produksi.index',[
            'data'=>$data
        ]);
    }

    public function create()
    {
        $data1 = Barang::type()->get();
        $data2 = Barang::all();
        // dd(count($data1));
        // dd(count($data2));

        return view('v2.produksi.semi-produksi.create',[
            'barang'=>Barang::type()->get()
        ]);
    }

    public function store(Request $req)
    {
        $req->validate([
            'nomer_produksi'=>'required|unique:second_mysql.semi_produksi',
            'tanggal_produksi'=>'required',
            'kuantitas'=>'required',
            'barang_id'=>'required',
            'rincian.*.barang_id'=>'required',
            'rincian.*.kuantitas'=>'required',
            'rincian.*.catatan'=>'nullable'
        ]);

        $rowRincian = collect($req->input('rincian'))->pluck('barang_id');
        // dd(count($rowRincian->unique()));
        if (count($rowRincian) !== count($rowRincian->unique())) {
            return back()->with('sukses','ITEM TIDAK BOLEH SAMA')->withInput();
        }

        // dd('sukses');

        $data = SemiProduksi::create($req->except('rincian'));
        foreach ($req->input('rincian') as $value) {
            $data->semiProduksiRinci()->create([
                'barang_id'=>$value['barang_id'],
                'kuantitas'=>$value['kuantitas'],
                'catatan'=>$value['catatan']
            ]);
        }
     
        return back()->with('sukses','DATA SEMI PRODUKSI BERHASIL DIBUAT DENGAN NOMOR PRODUKSI: '.$data->nomer_produksi);
    }

    public function show($id)
    {
        $data = SemiProduksi::find($id);
        // dd($data->barang->nama_barang);
        // dd($data->semiProduksiRinci);
        return view('v2.produksi.semi-produksi.show', [
            'data'=>$data,
        ]);
    }

    public function edit($id)
    {
        $data = SemiProduksi::find($id);
        $barang = Barang::type()->get();
        // dd($data);
        return view('v2.produksi.semi-produksi.edit',compact('data', 'barang'));
    }

    public function update(Request $req, $id)
    {
        $req->validate([
            'nomer_produksi'=>'required|unique:second_mysql.semi_produksi,nomer_produksi,'.$id,
            'tanggal_produksi'=>'required',
            'kuantitas'=>'required',
            'barang_id'=>'required',
            'rincian.*.barang_id'=>'required',
            'rincian.*.kuantitas'=>'required',
            'rincian.*.catatan'=>'nullable'
        ]);

        $rowRincian = collect($req->input('rincian'))->pluck('barang_id');
        if (count($rowRincian) !== count($rowRincian->unique())) {
            return back()->with('sukses','ITEM TIDAK BOLEH SAMA')->withInput();
        }

        $data = SemiProduksi::find($id);
        $data->update($req->except('rincian'));
        $data->semiProduksiRinci()->delete();
        foreach ($req->input('rincian') as $value) {
            $data->semiProduksiRinci()->create([
                'barang_id'=>$value['barang_id'],
                'kuantitas'=>$value['kuantitas'],
                'catatan'=>$value['catatan']
            ]);
        }

        return back()->with('sukses','DATA SEMI PRODUKSI BERHASIL DIUPDATE DENGAN NOMOR PRODUKSI: '.$data->nomer_produksi);
    }
}
