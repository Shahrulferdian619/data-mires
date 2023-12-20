<?php

namespace App\Http\Controllers\Hrga;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assets;
use App\Models\AssetType;
use PDF;
use DataTables;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Gate;

class AssetsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     //dd(auth()->user());
    //     if(auth()->user()->cannot('viewAny', Assets::class)) abort(403, 'access denied');

    //     $asset = Assets::all();

    //     return view('hrga.assets.index', compact(
    //         'asset'
    //     ));
    // }

    public function index(Request $request)
    {
        if(auth()->user()->cannot('viewAny', Assets::class)) abort(403, 'access denied');

        $asset = Assets::all();

        if($request->ajax()){
            return datatables()->of($asset)
            
            ->addIndexColumn()
            ->addColumn('actions', function($row){
                return '<td>
                <a class="badge badge-light-secondary" href="' . route('admin.asset.show', $row->id) .'"><i data-feather="eye"></i> Lihat</a>            
                </td>';
            })
            ->editColumn('tipe_asset', function($row){
                return $row->tipe ? $row->tipe->tipe_asset : $row->tipe_asset;
            })
            ->editColumn('tanggal_perolehan', function ($row) {
                return $row->tanggal_perolehan ? with(new Carbon($row->tanggal_perolehan))->format('d-m-Y') : '';;
            })
            ->editColumn('harga_perolehan', function($row){
                return "Rp. " . number_format($row->harga_perolehan,0,',','.');
            })
            ->rawColumns(['actions','tipe_asset','tanggal_perolehan','harga_perolehan'])->make(true);
        }

        return view('hrga.assets.index', compact(
            'asset'
        ));
    }

    public function exportPDF()
	{
        $asset = Assets::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('hrga.assets.exportpdf',compact('asset'));
        return $pdf->setPaper('a4', 'landscape')->setWarnings(false)->download('Asset.pdf');
		// return $pdf->stream();
	}

    public function printPDF()
	{
        $asset = Assets::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('hrga.assets.exportpdf',compact('asset'));
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
        if(auth()->user()->cannot('create', Assets::class)) abort(403, 'access denied');

        $assettype = AssetType::all();

        return view('hrga.assets.create', compact('assettype'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->cannot('create', Assets::class)) abort(403, 'access denied');

        $validate = $this->validation();

        $assets = new Assets;
        $assets->id_tipeasset = $request->id_tipeasset;
        $assets->nama_asset = $request->nama_asset;
        $assets->tanggal_perolehan = $request->tanggal_perolehan;
        $assets->harga_perolehan = explodeRupiah($request->harga_perolehan);
        $assets->kuantitas = $request->kuantitas;
        $assets->keterangan = $request->keterangan;
        $assets->save();

        //redirect ke create lagi setelah create
        if (isset($_POST['lagi'])) {
            return back()->with('success', 'Data berhasil di tambahkan');
        }

        return redirect('/admin/asset')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(auth()->user()->cannot('view', Assets::class)) abort(403, 'access denied');

        $asset = Assets::find($id);

        return view('hrga.assets.detail', compact('asset'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(auth()->user()->cannot('update', Assets::class)) abort(403, 'access denied');

        $asset = Assets::find($id);
        $assettype = AssetType::all();

        return view('hrga.assets.edit', compact(
            'asset',
            'assettype'
        ));
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
        if(auth()->user()->cannot('update', Assets::class)) abort(403, 'access denied');

        $validate = $this->validation($id);
        
        $assets = Assets::find($id);
        $assets->id_tipeasset = $request->id_tipeasset;
        $assets->nama_asset = $request->nama_asset;
        $assets->tanggal_perolehan = $request->tanggal_perolehan;
        $assets->harga_perolehan = explodeRupiah($request->harga_perolehan);
        $assets->kuantitas = $request->kuantitas;
        $assets->keterangan = $request->keterangan;
        $assets->save();

        return redirect('/admin/asset')->with('success', 'Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(auth()->user()->cannot('delete', Assets::class)) abort(403, 'access denied');

        $assets = Assets::find($id);
        $assets->delete();

        return redirect('/admin/asset')->with('success', 'Data berhasil dihapus');
    }

    private function validation($id = null)
    {
        $validate = request()->validate([
            'id_tipeasset' => 'required',
            'nama_asset' => 'required|unique:asset,nama_asset, '.$id,
            'tanggal_perolehan' => 'required',
            'harga_perolehan' => 'required',
            'kuantitas' => 'required|numeric',
        ]);

        return $validate;
    }
}
