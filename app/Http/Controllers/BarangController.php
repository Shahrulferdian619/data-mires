<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategoribarang;
use App\Models\v2\Inventory\Item;
use PDF;
use DataTables;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;

class BarangController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {

        if(auth()->user()->cannot('viewAny', Barang::class)) abort('403', 'access denied');

        $barang = Barang::all();
        //$barang = Item::all();
        if(url()->current() == url('admin/catalog'))
        {
            $barang = Barang::where('type', 1)->get();
            //$barang = Item::where('item_type',1)->get();
        }

        return view('barang.index', compact(
            'barang'
        ));
    }
    
    public function indexApi()
    {
        $barang = Barang::all();

        return response()->json($barang);
    }
    // public function index(Request $request){
    //     if(auth()->user()->cannot('viewAny', Barang::class)) abort('403', 'access denied');

    //     $barang = Barang::all();
    //     if(url()->current() == url('admin/barang-dagang'))
    //     {
    //         $barang = Barang::where('type', 1)->get();
    //     }

    //     dd($barang);
        
    //     if($request->ajax()){
    //         return datatables()->of($barang)
            
    //         ->addIndexColumn()
    //         ->addColumn('actions', function($row){
    //             return '<td>
    //             <a class="badge badge-light-secondary" href="' . route('admin.barang.show', $row->id) .'"><i data-feather="eye"></i> Lihat</a>            
    //             </td>';
    //         })
    //         ->editColumn('nama_kategori', function($row){
    //             return $row->kategoribarang ? $row->kategoribarang->nama_kategori : $row->nama_kategori;
    //         })
    //         ->editColumn('harga_barang1', function($row){
    //             return "Rp. " . number_format($row->harga_barang1,0,',','.');
    //         })
    //         ->rawColumns(['actions','nama_kategori','harga_barang1'])->make(true);
    //     }

    //     return view('barang.index', compact(
    //         'barang'
    //     ));
    // }
    public function index2($type, Request $request)
    {
        if(auth()->user()->cannot('viewAny', Barang::class)) abort('403', 'access denied');
        // $type = $request->type;
        // $barang = Barang::all();
        if($type == 'all' || $type = ''){
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
                <a class="badge badge-light-secondary" href="' . route('admin.barang.show', $row->id) .'"><i data-feather="eye"></i> Lihat</a>            
                </td>';
            })
            ->editColumn('nama_kategori', function($row){
                return $row->kategoribarang ? $row->kategoribarang->nama_kategori : $row->nama_kategori;
            })
            ->editColumn('harga_barang1', function($row){
                return "Rp. " . number_format($row->harga_barang1,0,',','.');
            })
            ->rawColumns(['actions','nama_kategori','harga_barang1'])->make(true);
        }

        return view('barang.index', compact(
            'barang','type'
        ));
    }

    public function exportPDF()
	{
		// if(auth()->user()->cannot('viewAny', Kategoribarang::class)) abort(403, 'access denied');
		// $kategoribarangs = Kategoribarang::all();
        // $pdf = PDF::loadView('kategoribarang.pdf', compact('kategoribarang'));
		// // $pdf = PDF::loadview('kategoribarang.pdf', ['KategoriBarang'=>$kategoribarangs]);
        // return $data->download('laporan.pdf');
        $barang = Barang::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('barang.exportpdf',compact('barang'));
        return $pdf->setPaper('a4', 'landscape')->setWarnings(false)->download('Barang.pdf');
		// return $pdf->stream();
	}

    public function printPDF()
	{
        $barang = Barang::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('barang.exportpdf',compact('barang'));
        return $pdf->setPaper('a4', 'landscape')->setWarnings(false)->stream();
		// return $pdf->stream();
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->cannot('create', Barang::class)) abort('403', 'access denied');

        $kategoribarang = Kategoribarang::all();

        return view('barang.create', compact('kategoribarang'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->cannot('create', Barang::class)) abort('403', 'access denied');

        $validate = $this->validation();

        $barang = new Barang;
        $barang->kategoribarang_id = $request->kategoribarang_id;
        $barang->type = $request->type;
        $barang->kode_barang = $request->kode_barang;
        $barang->nama_barang = $request->nama_barang;
        $barang->deskripsi_barang = $request->deskripsi_barang;
        $barang->satuan_barang = $request->satuan_barang;
        $barang->harga_barang1 = $request->harga_barang1;
        $barang->harga_barang2 = $request->harga_barang2;
        $barang->harga_barang3 = $request->harga_barang3;
        $barang->harga_barang4 = $request->harga_barang4;
        $barang->harga_barang5 = $request->harga_barang5;
        $barang->save();

        //redirect ke create lagi setelah create
        if (isset($_POST['lagi'])) {
            return back()->with('success', 'Data berhasil di tambahkan');
        }

        return redirect('/admin/barang')->with('success', 'Data berhasil di tambahkan');
    }

    public function storeApi(Request $request)
    {
        $barang = new Barang;
        $barang->kategoribarang_id = $request->kategoribarang_id;
        $barang->type = $request->type;
        $barang->kode_barang = $request->kode_barang;
        $barang->nama_barang = $request->nama_barang;
        $barang->deskripsi_barang = $request->deskripsi_barang;
        $barang->satuan_barang = $request->satuan_barang;
        $barang->harga_barang1 = $request->harga_barang1;
        $barang->harga_barang2 = $request->harga_barang2;
        $barang->harga_barang3 = $request->harga_barang3;
        $barang->harga_barang4 = $request->harga_barang4;
        $barang->harga_barang5 = $request->harga_barang5;
        $barang->save();

        return response()->json(['message'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Barang $barang)
    {
        if(auth()->user()->cannot('view', Barang::class)) abort('403', 'access denied');

        return view('barang.detail', compact(
            'barang'
        ));
    }

    public function showApi($id)
    {
        $data = Barang::findOrFail($id);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(auth()->user()->cannot('update', Barang::class)) abort('403', 'access denied');

        $barang = Barang::find($id);
        $kategoribarang = Kategoribarang::all();

        return view('barang.edit', compact(
            'barang',
            'kategoribarang'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Barang $barang)
    {
        if(auth()->user()->cannot('update', Barang::class)) abort('403', 'access denied');

        $validate = $this->validation($barang->id);

        $barang = Barang::find($barang->id);
        $barang->kategoribarang_id = $request->kategoribarang_id;
        $barang->type = $request->type;
        $barang->kode_barang = $request->kode_barang;
        $barang->nama_barang = $request->nama_barang;
        $barang->deskripsi_barang = $request->deskripsi_barang;
        $barang->satuan_barang = $request->satuan_barang;
        $barang->harga_barang1 = $request->harga_barang1;
        $barang->harga_barang2 = $request->harga_barang2;
        $barang->harga_barang3 = $request->harga_barang3;
        $barang->harga_barang4 = $request->harga_barang4;
        $barang->harga_barang5 = $request->harga_barang5;
        $barang->save();

        return redirect('/admin/barang')->with('success', 'Data berhasil di ubah');
    }

    public function updateApi(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);
        $barang->kategoribarang_id = $request->kategoribarang_id;
        $barang->type = $request->type;
        $barang->kode_barang = $request->kode_barang;
        $barang->nama_barang = $request->nama_barang;
        $barang->deskripsi_barang = $request->deskripsi_barang;
        $barang->satuan_barang = $request->satuan_barang;
        $barang->harga_barang1 = $request->harga_barang1;
        $barang->harga_barang2 = $request->harga_barang2;
        $barang->harga_barang3 = $request->harga_barang3;
        $barang->harga_barang4 = $request->harga_barang4;
        $barang->harga_barang5 = $request->harga_barang5;
        $barang->save();

        return response()->json(['message'=>'update success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Barang $barang)
    {
        if(auth()->user()->cannot('delete', Barang::class)) abort('403', 'access denied');

        try {
            $barang = Barang::find($barang->id);
            $barang->delete();
        } catch (\Throwable $th) {
            return back()->with('fail', 'Data tidak bisa dihapus! sudah digunakan dalam transaksi');
        }

        return redirect('/admin/barang')->with('success', 'Data berhasil di hapus');
    }

    private function validation($id = null)
    {
        $validate = request()->validate([
            'kategoribarang_id' => 'required',
            'kode_barang' => 'required|unique:barangs,kode_barang, '.$id,
            'nama_barang' => 'required',
        ]);

        return $validate;
    }
}
