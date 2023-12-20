<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Gudang;
use App\Models\MutationHistory;
use App\Models\GudangBarang;
use App\Models\TransaksiBarang;
use Illuminate\Support\Facades\Gate;

class MutationController extends Controller
{
    public function index(){

        //if(auth()->user()->cannot('viewAny', GudangBarang::class)) abort(403, 'access denied');
        if(!Gate::allows('index-mutation')) abort(403, 'access denied');

        $gudang = Gudang::all();
        $barang = Barang::all();
        return view('inventory.mutation', compact('gudang','barang'));
    }

    public function get_barang_by_gudang($id){
        $barang = GudangBarang::where('gudang_id', $id)->where('qty', '!=','0')->with('barang')->get();
        return response()->json($barang);
    }
    public function store(Request $request){

        //if(auth()->user()->cannot('create', GudangBarang::class)) abort(403, 'access denied');
        if(!Gate::allows('create-mutation')) abort(403, 'access denied');

        $rinci = count($request->barang_id);
        for ($i = 0; $i < $rinci; $i++) {

        // Input Gudang Barang In
        // Cek Apakah Ada barang di gudang tersebut sebelumnya 
        $barang_gudang_tujuan = GudangBarang::where(['barang_id' => $request->barang_id[$i], 'gudang_id' => $request->gudang_tujuan])->first();
        if($barang_gudang_tujuan == null){
            $gudang_barang = new GudangBarang;
            $gudang_barang->gudang_id = $request->gudang_tujuan;
            $gudang_barang->barang_id = $request->barang_id[$i];
            $gudang_barang->qty = $request->jumlah_mutasi[$i];
            $gudang_barang->stock_opname = $request->jumlah_mutasi[$i];
            $gudang_barang->save();
        }else{
            $barang_gudang_tujuan->qty = $barang_gudang_tujuan->qty + $request->jumlah_mutasi[$i];
            $barang_gudang_tujuan->save();
        }
        // Input Gudang Barang Out
        $barang_gudang_asal = GudangBarang::where(['barang_id' => $request->barang_id[$i], 'gudang_id' => $request->gudang_asal])->first();
        $barang_gudang_asal->qty = $barang_gudang_asal->qty - $request->jumlah_mutasi[$i];
        if($barang_gudang_asal->qty == 0){
            $barang_gudang_asal->delete();
        }else{
            $barang_gudang_asal->save();
        }

        // Input Transaksi Barang In
        $transaksi_barang = new TransaksiBarang;
        $transaksi_barang->id_barang = $request->barang_id[$i];
        $transaksi_barang->id_gudang = $request->gudang_tujuan;
        $transaksi_barang->jenis_transaksi = 'In';
        $transaksi_barang->nomer_transaksi = $request->nomer;
        $transaksi_barang->sumber_transaksi = 'MU';
        $transaksi_barang->qty = $request->jumlah_mutasi[$i];
        $transaksi_barang->save();

        // Input Transaksi Barang Out
        $transaksi_barang = new TransaksiBarang;
        $transaksi_barang->id_barang = $request->barang_id[$i];
        $transaksi_barang->id_gudang = $request->gudang_asal;
        $transaksi_barang->jenis_transaksi = 'Out';
        $transaksi_barang->nomer_transaksi = $request->nomer;
        $transaksi_barang->sumber_transaksi = 'MU';
        $transaksi_barang->qty = '-'.$request->jumlah_mutasi[$i];
        $transaksi_barang->save();

        // Input History Mutasi
        $mutation_history = new MutationHistory;
        $mutation_history->nomor_mutasi = $request->nomer;
        $mutation_history->tanggal = $request->tanggal;
        $mutation_history->gudang_asal = Gudang::find($request->gudang_asal)->nama_gudang;
        $mutation_history->gudang_tujuan = Gudang::find($request->gudang_tujuan)->nama_gudang;
        $mutation_history->jumlah_mutasi = $request->jumlah_mutasi[$i];
        $mutation_history->save();

        }

        return redirect('admin/list-inventory/all')->with('success', 'Berhasil Mutasi Barang!');
    }

    public function history(Request $request){
        $mutation = MutationHistory::all();
        if($request->ajax()){
            return datatables()->of($mutation)
            
            ->addIndexColumn()
            ->make(true);
        }
        return view('inventory.mutationhistory');
    }
}
