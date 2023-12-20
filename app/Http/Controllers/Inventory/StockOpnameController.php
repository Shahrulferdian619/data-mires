<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Gudang;
use App\Models\GudangBarang;
use App\Models\TransaksiBarang;
use Illuminate\Support\Facades\Gate;

class StockOpnameController extends Controller
{
    public function index(){
        
        // if(auth()->user()->cannot('viewAny', Popembelian::class)) abort('403', 'access denied');
        if(!Gate::allows('index-stock')) abort(403, 'access denied');
        $gudang = Gudang::all();
        if(count($gudang) <= 0){
            abort('403', 'Sistem Belum Siap! Gudang Belum Diinputkan!');
        }
        $barang = Barang::all();

        return view('inventory.stock-opname', compact('barang', 'gudang'));
    }
    public function get_barang_by_gudang($id){
        // if(auth()->user()->cannot('read', Popembelian::class)) abort('403', 'access denied');
        // if(!Gate::allows('index-stock')) abort(403, 'access denied');
        if(!Gate::allows('read-stock')) abort(403, 'access denied');

        $data = gudang::all();
        
        $no = 0;

        foreach($data as $gudang){
            $gudang_barang = GudangBarang::where(['barang_id' => $id, 'gudang_id' => $gudang->id])->first();
            if($gudang_barang == null){
                $jumlah = 0;
            }else{
                $jumlah = $gudang_barang->qty;
            }
            $data[$no]['gudang'] = $gudang->nama_gudang;
            $data[$no]['gudang_id'] = $gudang->id;
            $data[$no]['jumlah_barang'] = $jumlah;
            $no++;
        }

        return response()->json($data);
    }
    
    public function store(Request $request){

        if(!Gate::allows('create-stock')) abort(403, 'access denied');

        if(auth()->user()->cannot('create', Popembelian::class)) abort('403', 'access denied');
        if(auth()->user()->cannot('update', Popembelian::class)) abort('403', 'access denied');
        if(auth()->user()->cannot('delete', Popembelian::class)) abort('403', 'access denied');
        $request->validate([
            'nomer' => 'required'
        ]);

        $no = 0;
        foreach($request->barang_id as $id_barang){
            // Insert Transaksi
            $transaksi_barang = new TransaksiBarang;
            $transaksi_barang->id_barang = $id_barang;
            $transaksi_barang->id_gudang = $request->gudang_id;
            $transaksi_barang->jenis_transaksi = 'Opname';
            $transaksi_barang->nomer_transaksi = $request->nomer;
            $transaksi_barang->sumber_transaksi = 'SA';
            $transaksi_barang->qty = $request->jumlah_opname[$no];
            $transaksi_barang->save();
            // Update Gudang
            $gudang_barang = GudangBarang::where(['barang_id' => $id_barang, 'gudang_id' => $request->gudang_id])->first();
            if($gudang_barang == null){
                $gudang_barang = new GudangBarang;
                $gudang_barang->gudang_id = $request->gudang_id;
                $gudang_barang->barang_id = $id_barang;
                $gudang_barang->qty = $request->jumlah_opname[$no];
                $gudang_barang->stock_opname = '+'.$request->jumlah_opname[$no];
                $gudang_barang->save();
            }else{
                $gudang_barang->qty = $request->jumlah_opname[$no];
                $gudang_barang->stock_opname = '+'.$request->jumlah_opname[$no];
                $gudang_barang->save();
            }
            // Update Balance Stock
            $balance_stock = GudangBarang::where('barang_id', $id_barang)->sum('qty');
            $barang = Barang::find($id_barang);
            $barang->balance_stok = $balance_stock;
            $barang->save();

            $no++;
        }
        // foreach($request->id as $id){
        //     if($request->jumlah[$id] != $request->jumlah_adjusment[$id]){
        //         // Insert Transaksi Barang
        //         $transaksi_barang = new TransaksiBarang;
        //         $transaksi_barang->id_barang = $request->barang_id;
        //         $transaksi_barang->id_gudang = $id;
        //         $transaksi_barang->jenis_transaksi = 'Opname';
        //         $transaksi_barang->nomer_transaksi = $request->nomer;
        //         $transaksi_barang->sumber_transaksi = 'SA';
        //         $transaksi_barang->qty = $request->jumlah_adjusment[$id] - $request->jumlah[$id];
        //         $transaksi_barang->save();

        //         // Update Gudang
        //         $gudang_barang = GudangBarang::where(['barang_id' => $request->barang_id, 'gudang_id' => $id])->first();
        //         if($gudang_barang == null){
        //             $gudang_barang = new GudangBarang;
        //             $gudang_barang->gudang_id = $id;
        //             $gudang_barang->barang_id = $request->barang_id;
        //             $gudang_barang->qty = $request->jumlah_adjusment[$id];
        //             if($request->jumlah_adjusment[$id] < 0){
        //                 $request->jumlah_adjusment[$id] = '-'.$request->jumlah_adjusment[$id];
        //             }
        //             $gudang_barang->stock_opname = $request->jumlah_adjusment[$id];
        //             $gudang_barang->save();
        //         }else{
        //             $gudang_barang->qty = $request->jumlah_adjusment[$id];
        //             $gudang_barang->stock_opname = $request->jumlah_adjusment[$id];
        //             $gudang_barang->save();
        //         }

        //         // Update Balance Stock
        //         $balance_stock = GudangBarang::where('barang_id', $request->barang_id)->sum('qty');
        //         $barang = Barang::find($request->barang_id);
        //         $barang->balance_stok = $balance_stock;
        //         $barang->save();
        //     }
        // }
        return redirect('admin/list-inventory/all')->with('success', 'Berhasil Adjusment Stock!');
    }
}
