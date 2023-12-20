<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Gudang;
use App\Models\TransaksiBarang;
use App\Models\GudangBarang;
use PDF;
use DataTables;
use Illuminate\Support\Facades\Gate;

class InventoryController extends Controller
{
    // public function index(){
    //     $barang = Barang::where('type', 1)->get();
    //     return view('inventory.index',compact('barang'));
    // }

    public function index($type, Request $request)
    {
        if(!Gate::allows('index-inventory')) abort(403, 'access denied');

        if($type == 'all'){
            $barang = Barang::all();
        }else{
            if($type == 1 || $type == 2 || $type == 3 || $type == 4){
                $barang = Barang::where('type', $type)->get();
            }else{
                return abort(403);
            }
        }
        if($request->ajax()){
            return datatables()->of($barang)
            
            ->addIndexColumn()
            ->addColumn('actions', function($row){
                return '<td>
                <a class="badge badge-light-secondary" href="' . url('admin/list-inventory/show', $row->id) .'"><i data-feather="eye"></i> Lihat</a>            
                </td>';
            })
            ->rawColumns(['actions'])
            ->make(true);
        }

        return view('inventory.index',compact('barang', 'type'));
    }
    

    public function exportPDF()
	{
        $barang = Barang::where('type', 1)->get();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('inventory.exportpdf',compact('barang'));
        return $pdf->setWarnings(false)->download('Daftar Inventory.pdf');
		// return $pdf->stream();
	}

    public function printPDF()
	{
        $barang = Barang::where('type', 1)->get();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('inventory.exportpdf',compact('barang'));
        return $pdf->setWarnings(false)->stream();
		// return $pdf->stream();
	}
    
    public function itemIn(){

        // if(auth()->user()->cannot('create', TransaksiBarang::class)) abort(403, 'access denied');

        if(!Gate::allows('create-inventory')) abort(403, 'access denied');

        $barang = Barang::where('balance_stok','==','0')->where('type', 1)->get();
        $gudang = Gudang::all();

        return view('inventory.in', compact('barang', 'gudang'));
    }

    public function store(Request $request){

        //if(auth()->user()->cannot('create', TransaksiBarang::class)) abort(403, 'access denied');

        if(!Gate::allows('create-inventory')) abort(403, 'access denied');

        if($request->nomer == null){//cek apakah mengisi nomer jika tidak maka tidak diproses
            return redirect('admin/list-inventory/stock-in')->with('fail', 'Masukkan Nomer Terlebih Dahulu!');
        }
        if($request->gudang_id == 0){//cek apakah mengisi gudang jika tidak maka tidak diproses
            return redirect('admin/list-inventory/stock-in')->with('fail', 'Masukkan Gudang Terlebih Dahulu!');
        }
        foreach($request->index as $index){
            if($request->barang_id[$index] != 0){
                $barang = $request->barang_id[$index];
                $harga_beli = explodeRupiah($request->hargabeli[$index]);
                if(count(GudangBarang::where(['barang_id' => $barang, 'gudang_id' => $request->gudang_id])->get()) > 0){
                    return redirect('admin/list-inventory')->with('success', 'Berhasil Input Stock Awal! Input Terakhir Dipotong Karena Data Telah Ada!');
                }
                $transaksi_barang = new TransaksiBarang;
                $transaksi_barang->id_barang = $barang;
                $transaksi_barang->id_gudang = $request->gudang_id;
                $transaksi_barang->jenis_transaksi = 'In';
                $transaksi_barang->nomer_transaksi = $request->nomer;
                $transaksi_barang->sumber_transaksi = 'II';
                $transaksi_barang->qty = $request->qty[$index];
                $transaksi_barang->save();
    
                $gudang_barang = new GudangBarang;
                $gudang_barang->gudang_id = $request->gudang_id;
                $gudang_barang->barang_Id = $barang;
                $gudang_barang->qty = $request->qty[$index];
                $gudang_barang->stock_opname = 0;
                $gudang_barang->keterangan = '-';
                $gudang_barang->save();

                $item = Barang::find($barang);
                $item->balance_stok = $request->qty[$index];
                $item->hpp = $harga_beli;
                $item->save();
            }
        }
        return redirect('admin/list-inventory')->with('success', 'Berhasil Input Stock Awal!');

    }

    public function show($id){

        //if(auth()->user()->cannot('view', TransaksiBarang::class)) abort(403, 'access denied');

        if(!Gate::allows('read-inventory')) abort(403, 'access denied');

        $barang = Barang::find($id);
        $lokasi_gudang = GudangBarang::where('barang_id', $id)->get();
        $transaksi = TransaksiBarang::where('id_barang', $id)->get();

        return view('inventory.show', compact('lokasi_gudang', 'barang', 'transaksi'));
    }
}
