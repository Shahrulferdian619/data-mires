<?php

namespace App\Http\Controllers\Hrga;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssetType;
use PDF;
use DataTables;
use Illuminate\Support\Facades\Gate;

class AssetTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     if(auth()->user()->cannot('viewAny', AssetType::class)) abort(403, 'access denied');

    //     $assettype = AssetType::all();

    //     return view('hrga.assettype.index', compact(
    //         'assettype'
    //     ));
    // }

    public function index(Request $request)
    {
        if(auth()->user()->cannot('viewAny', AssetType::class)) abort(403, 'access denied');

        $assettype = AssetType::all();

        if($request->ajax()){
            return datatables()->of($assettype)
            
            ->addIndexColumn()
            ->addColumn('actions', function($row){
                return '<td>
                <a class="badge badge-light-secondary" href="' . route('admin.tipeasset.show', $row->id) .'"><i data-feather="eye"></i> Lihat</a>            
                </td>';
            })
            ->rawColumns(['actions'])
            ->make(true); 
        }

        return view('hrga.assettype.index', compact(
            'assettype'
        ));
    }

    public function exportPDF()
	{
        $assettype = AssetType::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('hrga.assettype.exportpdf',compact('assettype'));
        return $pdf->setWarnings(false)->download('Kategori Asset.pdf');
		// return $pdf->stream();
	}

    public function printPDF()
	{
        $assettype = AssetType::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('hrga.assettype.exportpdf',compact('assettype'));
        return $pdf->setWarnings(false)->stream();
		// return $pdf->stream();
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->cannot('create', AssetType::class)) abort(403, 'access denied');

        return view('hrga.assettype.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->cannot('create', AssetType::class)) abort(403, 'access denied');

        $validate = $this->validation();

        $assettype = new AssetType;
        $assettype->tipe_asset = $request->tipe_asset;
        $assettype->keterangan = $request->keterangan;
        $assettype->save();

        //redirect ke create lagi setelah create
        if (isset($_POST['lagi'])) {
            return back()->with('success', 'Data berhasil di tambahkan');
        }

        return redirect('/admin/tipeasset')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(auth()->user()->cannot('view', AssetType::class)) abort(403, 'access denied');

        $assettype= AssetType::find($id);

        return view('hrga.assettype.detail', compact(
            'assettype'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(auth()->user()->cannot('update', AssetType::class)) abort(403, 'access denied');

        $assettype = AssetType::find($id);

        return view('hrga.assettype.edit', compact(
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
        if(auth()->user()->cannot('update', AssetType::class)) abort(403, 'access denied');

        $validate = $this->validation($id);
        
        $assettype = AssetType::find($id);
        $assettype->tipe_asset = $request->tipe_asset;
        $assettype->keterangan = $request->keterangan;
        $assettype->save();

        return redirect('/admin/tipeasset')->with('success', 'Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(auth()->user()->cannot('delete', AssetType::class)) abort(403, 'access denied');

        try {
            $assettype = AssetType::find($id);
            $assettype->delete();
        } catch (\Throwable $th) {
            return back()->with('fail', 'Data tidak bisa dihapus! sudah digunakan dalam transaksi');
        }

        return redirect('/admin/tipeasset')->with('success', 'Data berhasil di hapus');
    }

    private function validation($id = null)
    {
        $validate = request()->validate([
            'tipe_asset' => 'required|unique:tipe_asset,tipe_asset, '.$id,
        ]);

        return $validate;
    }
}
