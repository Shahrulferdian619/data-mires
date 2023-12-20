<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\{
    Pmtpembelian_rinci,
    Popembelian_rinci,
    Popembelian,
    Ri,
    Ri_rinci,
    Ri_rinci_temp,
    Supplier,
    Barang,
    BerkasRi,
    Gudang,
    TransaksiBarang,
    GudangBarang
};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use PDF;
use DataTables;
use Carbon\Carbon;
use DB;

class RiController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     if(auth()->user()->cannot('viewAny', Ri::class)) abort('403', 'access denied');

    //     $ri = Ri::all();

    //     return view('ri.index', compact(
    //         'ri'
    //     ));
    // }

    public function index(Request $request)
    {
        if(auth()->user()->cannot('viewAny', Ri::class)) abort('403', 'access denied');

        $ri = Ri::all();

        if($request->ajax()){
            return datatables()->of($ri)
            
            ->addIndexColumn()
            ->addColumn('actions', function($row){
                return '<td>
                <a class="badge badge-light-secondary" href="' . route('admin.ri.show', $row->id) .'"><i data-feather="eye"></i> Lihat</a>            
                </td>';
            })
            // ->editColumn('tanggal', function ($row) {
            //     return $row->created_at->format('Y/m/d');
            // })
            ->editColumn('tanggal_ri', function ($row) {
                return $row->tanggal_ri ? with(new Carbon($row->tanggal_ri))->format('d-m-Y') : '';;
            })
            // ->filterColumn('tanggal', function ($query, $keyword) {
            //     $query->whereRaw("DATE_FORMAT(tanggal,'%m/%d/%Y') LIKE ?", ["%$keyword%"]);
            // })
            ->editColumn('nama_supplier', function($row){
                return $row->supplier ? $row->supplier->nama_supplier : $row->nama_supplier;
            })
            ->editColumn('nomer_po', function($row){
                return $row->po ? $row->po->nomer_po : $row->nomer_po;
            })
            ->rawColumns(['actions','tanggal_ri','nama_supplier','nomer_po'])->make(true);
            // ->rawColumns(['actions'])
            // ->make(true);
        }

        return view('ri.index', compact(
            'ri'
        ));
    }

    public function exportPDF()
	{
        $ri = Ri::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('ri.exportpdf',compact('ri'));
        return $pdf->download('Penerimaan Barang.pdf');
	}

    public function printPDF()
	{
        $ri = Ri::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('ri.exportpdf',compact('ri'));
        return $pdf->stream();
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->cannot('create', Ri::class)) abort('403', 'access denied');

        //get data supplier
        $suppliers = Supplier::where('aktif', 1)->get();
        $po = Popembelian::where(['approve_direktur' => 1, 'approve_komisaris' => 1])->where('status', '!=', 2)->get();
        $gudang = Gudang::All();

        //get data po by supplier yang sudah diapprove direktur & komisaris

        //return $suppliers;
        return view('ri.create', compact(
            'suppliers',
            'gudang',
            'po'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function _store(Request $request)
    {
        // dd(request());
        // Validasi
        $request->validate([
            'supplier_id' => 'required',
            'nomer_ri' => 'required|unique:ri,nomer_ri',
            'tanggal_ri' => 'required',
            'keterangan' => 'required',
            'berkas_1' => 'file|mimes:doc,docx,pdf,jpeg,png|max:2000',
            'berkas_2' => 'file|mimes:doc,docx,pdf,jpeg,png|max:2000',
            'berkas_3' => 'file|mimes:doc,docx,pdf,jpeg,png|max:2000',
            'berkas_4' => 'file|mimes:doc,docx,pdf,jpeg,png|max:2000',
            'berkas_5' => 'file|mimes:doc,docx,pdf,jpeg,png|max:2000',
        ]);

        // Insert Tabel RI
        $ri = new Ri();
        $ri->user_id = Auth::id();
        $ri->supplier_id = $request->supplier_id;
        $ri->nomer_ri = $request->nomer_ri;
        $ri->tanggal_ri = $request->tanggal_ri;
        $ri->keterangan = $request->keterangan;
        $ri->status_ri = 1;
        $ri->po_id = $request->popembelian_id;
        $ri->save();



        foreach (Request('id') as $id) {
            $ri_rinci = new Ri_rinci();
            $ri_rinci->ri_id = $ri->id;
            $ri_rinci->qty = Request('jumlah')[$id];
            $ri_rinci->harga = Request('harga')[$id];
            $ri_rinci->barang_id = Request('barang_id')[$id];
            if (!empty(Request('note')[$id])) {
                $ri_rinci->note = Request('note')[$id];
            }
            $ri_rinci->po_rinci_id = $id;
            $ri_rinci->save();

            // Cek Apakah Jumlah Barang Diterima Sama Dengan Jumlah Barang Yang Dipesan
            $barang_po = Popembelian_rinci::find($id);
            if ($barang_po->jumlah == $ri_rinci->qty) {
                // Ubah PO Rinci Menjadi Sudah Diterima
                $barang_po->is_received = 1;
                $barang_po->save();
            } else {
                // Cek Pada apakah ada RI Sebelumnya Lalu Hitung Jumlahnya
                $jumlah_ri = Ri_rinci::where('po_rinci_id', $id)->get();
                $qty = 0;
                foreach ($jumlah_ri as $jumlah) {
                    // Hitung Jumlah
                    $qty += $jumlah->qty;
                }

                if (Popembelian_rinci::find($id)->jumlah <= $qty) {
                    $barang_po->is_received = 1;
                    $barang_po->save();
                }
            }

            // Insert Tabel Transaksi Barang
            $transaksibarang = new TransaksiBarang;
            $transaksibarang->id_barang = Request('barang_id')[$id];
            $transaksibarang->id_gudang = Request('gudang')[$id];
            $transaksibarang->jenis_transaksi = 'In';
            $transaksibarang->nomer_transaksi = $request->nomer_ri;
            $transaksibarang->sumber_transaksi = 'RI';
            $transaksibarang->qty = Request('jumlah')[$id];
            $transaksibarang->save();
        }

        // Cek Apakah Semua Pesanan PO Sudah Diterima
        $jumlah_po_rinci_diterima = count(Popembelian_rinci::where(['is_received' => 1, 'popembelian_id' => $request->popembelian_id])->get());
        $jumlah_po_rinci = count(Popembelian_rinci::where('popembelian_id', $request->popembelian_id)->get());

        $po = Popembelian::find($request->popembelian_id);
        if ($jumlah_po_rinci <= $jumlah_po_rinci_diterima) {

            $po->status = 2;
            // Jika Sudah Maka Ubah Status Menjadi 2
            $po->save();
        } else {
            $po->status = 1;
            // Jika Belum Maka Ubah Status Menjadi 1
            $po->save();
        }

        // berkas
        $berkas1 = '';
        $berkas2 = '';
        $berkas3 = '';
        $berkas4 = '';
        $berkas5 = '';

        if ($request->hasFile('berkas_1')) {
            $file = $request->file('berkas_1');
            $originalName1 = $file->getClientOriginalName();
            $berkas1 = $originalName1;
            $file->move('uploads/ri', $berkas1);
        }
        if ($request->hasFile('berkas_2')) {
            $file = $request->file('berkas_2');
            $originalName2 = $file->getClientOriginalName();
            $berkas2 = $originalName2;
            $file->move('uploads/ri', $berkas2);
        }
        if ($request->hasFile('berkas_3')) {
            $file = $request->file('berkas_3');
            $originalName3 = $file->getClientOriginalName();
            $berkas3 = $originalName3;
            $file->move('uploads/ri', $berkas3);
        }
        if ($request->hasFile('berkas_4')) {
            $file = $request->file('berkas_4');
            $originalName4 = $file->getClientOriginalName();
            $berkas4 = $originalName4;
            $file->move('uploads/ri', $berkas4);
        }
        if ($request->hasFile('berkas_5')) {
            $file = $request->file('berkas_5');
            $originalName5 = $file->getClientOriginalName();
            $berkas5 = $originalName5;
            $file->move('uploads/ri', $berkas5);
        }

        $berkasri = new BerkasRi();
        $berkasri->ri_id = $ri->id;
        $berkasri->berkas_1 = $berkas1;
        $berkasri->berkas_2 = $berkas2;
        $berkasri->berkas_3 = $berkas3;
        $berkasri->berkas_4 = $berkas4;
        $berkasri->berkas_5 = $berkas5;
        $berkasri->save();

        // var_dump($ri_rinci);

        return redirect('/admin/ri')->with('success', 'Berhasil menambah data');
    }

    public function store(Request $request)
    {
        if(auth()->user()->cannot('create', Ri::class)) abort('403', 'access denied');

        // dd(request());
        // Validasi
        $request->validate([
            'supplier_id' => 'required',
            'nomer_ri' => 'required|unique:ri,nomer_ri',
            'tanggal_ri' => 'required',
            'keterangan' => 'required',
            'berkas_1' => 'file|mimes:doc,docx,pdf,jpeg,png|max:2000',
            'berkas_2' => 'file|mimes:doc,docx,pdf,jpeg,png|max:2000',
            'berkas_3' => 'file|mimes:doc,docx,pdf,jpeg,png|max:2000',
            'berkas_4' => 'file|mimes:doc,docx,pdf,jpeg,png|max:2000',
            'berkas_5' => 'file|mimes:doc,docx,pdf,jpeg,png|max:2000',
            'gudang' => 'required'
        ]);

        // Insert Tabel RI
        $ri = new Ri();
        $ri->user_id = Auth::id();
        $ri->supplier_id = $request->supplier_id;
        $ri->nomer_ri = $request->nomer_ri;
        $ri->tanggal_ri = $request->tanggal_ri;
        $ri->keterangan = $request->keterangan;
        $ri->status_ri = 1;
        $ri->po_id = $request->popembelian_id;
        $ri->save();

        foreach (Request('id') as $id) {
            $ri_rinci = new Ri_rinci();
            $ri_rinci->ri_id = $ri->id;
            $ri_rinci->qty = Request('jumlah')[$id];
            $ri_rinci->harga = Request('harga')[$id];
            $ri_rinci->dsc = Request('dsc')[$id];
            $ri_rinci->barang_id = Request('barang_id')[$id];
            if (!empty(Request('note')[$id])) {
                $ri_rinci->note = Request('note')[$id];
            }
            $ri_rinci->po_rinci_id = $id;
            $ri_rinci->save();

            // Cek Apakah Jumlah Barang Diterima Sama Dengan Jumlah Barang Yang Dipesan
            $barang_po = Popembelian_rinci::find($id);
            if ($barang_po->jumlah == $ri_rinci->qty) {
                // Ubah PO Rinci Menjadi Sudah Diterima
                $barang_po->is_received = 1;
                $barang_po->save();
            } else {
                // Cek Pada apakah ada RI Sebelumnya Lalu Hitung Jumlahnya
                $jumlah_ri = Ri_rinci::where('po_rinci_id', $id)->get();
                $qty = 0;
                foreach ($jumlah_ri as $jumlah) {
                    // Hitung Jumlah
                    $qty += $jumlah->qty;
                }

                if (Popembelian_rinci::find($id)->jumlah <= $qty) {
                    $barang_po->is_received = 1;
                    $barang_po->save();
                }
            }

           // Insert Tabel Transaksi Barang
           $transaksibarang = new TransaksiBarang;
           $transaksibarang->id_barang = Request('barang_id')[$id];
           $transaksibarang->id_gudang = Request('gudang')[$id];
           $transaksibarang->jenis_transaksi = 'In';
           $transaksibarang->nomer_transaksi = $request->nomer_ri;
           $transaksibarang->sumber_transaksi = 'RI';
           $transaksibarang->qty = Request('jumlah')[$id];
           $transaksibarang->save();

           // Update QTY Barang
           // Insert Balance Stock Item
           $barang = Barang::find(Request('barang_id')[$id]);
           if(!empty($barang->balance_stok)){
              $barang->balance_stok = $barang->balance_stok + Request('jumlah')[$id];
              $barang->save();
           }elseif($barang->balance_stok == 0 || $barang->balance_stok == null || empty($barang->balance_stok)){
              $barang->balance_stok = Request('jumlah')[$id];
              $barang->save();
           }
           // Insert Barang Di Gudang
           $gudang = GudangBarang::where(['barang_id' => Request('barang_id')[$id], 'gudang_id' => Request('gudang')[$id]])->get();
           if(count($gudang) == 0){
               $barang_gudang = new GudangBarang;
               $barang_gudang->gudang_id = Request('gudang')[$id];
               $barang_gudang->barang_id = Request('barang_id')[$id];
               $barang_gudang->qty = Request('jumlah')[$id];
               $barang_gudang->save();
           }else{
               $barang_gudang = GudangBarang::where(['barang_id' => Request('barang_id')[$id], 'gudang_id' => Request('gudang')[$id]])->first();
               $barang_gudang->qty = $barang_gudang->qty + Request('jumlah')[$id];
               $barang_gudang->save();
           }
        }

        // Cek Apakah Semua Pesanan PO Sudah Diterima
        $jumlah_po_rinci_diterima = count(Popembelian_rinci::where(['is_received' => 1, 'popembelian_id' => $request->popembelian_id])->get());
        $jumlah_po_rinci = count(Popembelian_rinci::where('popembelian_id', $request->popembelian_id)->get());

        $po = Popembelian::find($request->popembelian_id);
        if ($jumlah_po_rinci <= $jumlah_po_rinci_diterima) {

            $po->status = 2;
            // Jika Sudah Maka Ubah Status Menjadi 2
            $po->save();
        } else {
            $po->status = 1;
            // Jika Belum Maka Ubah Status Menjadi 1
            $po->save();
        }

        if(!empty($request->berkas)){
            // buat data berkas
            $totalBerkas = count($request->berkas);
            $collectNamaBerkas = [];

            for ($i=0; $i < $totalBerkas; $i++) { 
                if($request->hasFile('berkas.'.$i)){
                    $file = $request->file('berkas')[$i];
                    $originalName = $file->getClientOriginalName();
                    $berkas = str_replace(" ", "", $originalName);
                    $file->move('uploads/ri', $berkas);
                    
                    array_push($collectNamaBerkas, $berkas);
                }
            }

            $saveBerkas = count($collectNamaBerkas);

            $berkasri = new BerkasRi();
            $berkasri->ri_id = $ri->id;

            for ($i=0; $i < $saveBerkas; $i++) { 
                $flag = $i+1;
                $var = "berkas_".$flag;
                $berkasri->$var = $collectNamaBerkas[$i];
            }
            $berkasri->save();
        }


        // var_dump($ri_rinci);
        
        //redirect ke create lagi setelah create
        if (isset($_POST['lagi'])) {
            return back()->with('success', 'Data berhasil di tambahkan');
        }


        return redirect('/admin/ri')->with('success', 'Berhasil menambah data');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(auth()->user()->cannot('view', Ri::class)) abort('403', 'access denied');

        $ri = Ri::find($id);
        $supplier = Supplier::all();

        // return response()->json($popembelian);

        return view('ri.detail', compact('ri', 'supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(auth()->user()->cannot('update', Ri::class)) abort('403', 'access denied');
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
        if(auth()->user()->cannot('update', Ri::class)) abort('403', 'access denied');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ri $ri)
    {
        if(auth()->user()->cannot('delete', Ri::class)) abort('403', 'access denied');

        $ri = Ri::with('rinci')->find($ri->id);

        if ($ri->status_faktur == 1) {
            return back()->with('fail', 'Tidak bisa menghapus, Penerimaan sudah dibuatkan tagihan');
        }

        $berkas = BerkasRi::where('ri_id', $ri->id)->first();
        if(!empty($berkas)){
            if ($berkas->berkas_1 != '') {
                File::delete('uploads/ri/' . $berkas->berkas_1);
            }
            if ($berkas->berkas_2 != '') {
                File::delete('uploads/ri/' . $berkas->berkas_2);
            }
            if ($berkas->berkas_3 != '') {
                File::delete('uploads/ri/' . $berkas->berkas_3);
            }
            if ($berkas->berkas_4 != '') {
                File::delete('uploads/ri/' . $berkas->berkas_4);
            }
            if ($berkas->berkas_5 != '') {
                File::delete('uploads/ri/' . $berkas->berkas_5);
            }
            $berkas->delete();
        }
       

        $po_ri_id = [];
        foreach ($ri->rinci as $key) {
            array_push($po_ri_id, $key->po_rinci_id);
        }

        // hapus rincian ri
        Ri_rinci::where('ri_id', $ri->id)->delete();
        // hapus ri
        $ri->delete();

        // cek apakah ri dengan po yang sama masih ada
        $checkRI = Ri::where('po_id', $ri->po_id)->get()->count();

        if ($checkRI > 0) {
            $statusPO = 1;
        } else {
            $statusPO = 0;
        }

        foreach ($po_ri_id as $key) {
            $PORINCI = Popembelian_rinci::find($key);
            $PORINCI->is_received = 0;
            $PORINCI->save();
        }

        $po = Popembelian::find($ri->po_id);
        $po->status = $statusPO;
        $po->save();

        // return response()->json($ri);
        return redirect('admin/ri')->with('success', 'Berhasil menghapus penerimaan barang');
    }

    public function cancel()
    {
        
        session()->forget('pmtpembelian_rinci');
        session()->forget('pmtpembelian_id');
        session()->forget('supplier_id');
        return redirect('/admin/ri/create');
    }
}
