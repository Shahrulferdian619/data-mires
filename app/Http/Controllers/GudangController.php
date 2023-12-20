<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gudang;
use Illuminate\Support\Facades\Gate;
use PDF;
use DataTables;
use Alert;

class GudangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     if(auth()->user()->cannot('viewAny', Gudang::class)) abort('403', 'access denied');

    //     $gudangs = Gudang::all();

    //     return view('gudang.index', compact(
    //         'gudangs'
    //     ));
    // }

    public function index(Request $request)
    {
        if(auth()->user()->cannot('viewAny', Gudang::class)) abort('403', 'access denied');

        $gudangs = Gudang::all();

        if($request->ajax()){
            return datatables()->of($gudangs)
            
            ->addIndexColumn()
            ->addColumn('actions', function($row){
                return '<td>
                <a class="badge badge-light-secondary" href="' . route('admin.gudang.show', $row->id) .'"><i data-feather="eye"></i> Lihat</a>            
                </td>';
            })
            ->rawColumns(['actions'])
            ->make(true); 
        }

        return view('gudang.index', compact(
        'gudangs'
        ));
    }

    public function exportPDF()
	{
		// if(auth()->user()->cannot('viewAny', Kategoribarang::class)) abort(403, 'access denied');
		// $kategoribarangs = Kategoribarang::all();
        // $pdf = PDF::loadView('kategoribarang.pdf', compact('kategoribarang'));
		// // $pdf = PDF::loadview('kategoribarang.pdf', ['KategoriBarang'=>$kategoribarangs]);
        // return $data->download('laporan.pdf');
        $gudangs = Gudang::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('gudang.exportpdf',compact('gudangs'));
        return $pdf->setPaper('a4', 'landscape')->setWarnings(false)->download('Gudang.pdf');
		// return $pdf->stream();
	}

    public function printPDF()
	{
        $gudangs = Gudang::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('gudang.exportpdf',compact('gudangs'));
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
        if(auth()->user()->cannot('create', Gudang::class)) abort('403', 'access denied');

        return view('gudang.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->cannot('create', Gudang::class)) abort('403', 'access denied');

        $validate = $this->validation();
        
        $gudang = new Gudang;
        $gudang->kode_gudang = $request->kode_gudang;
        $gudang->nama_gudang = $request->nama_gudang;
        $gudang->deskripsi_gudang = $request->deskripsi_gudang;
        $gudang->nama_penanggungjawab = $request->nama_penanggungjawab;
        $gudang->save();

        //redirect ke create lagi setelah create
        if (isset($_POST['lagi'])) {
            return back()->with('success', 'Data berhasil di tambahkan');
        }
        // if ($request->target == 'lagi') {
        //     // return back()->with('success', 'Data berhasil di tambahkan');
        //     // alert()->success('Sukses','Data berhasil di tambahkan.')->autoClose(2000);
        //     return response()->json('LAGI');
        // }

        // return response()->json($request->target);
        
        // toast('Berhasil menambahkan data', 'success');
        // alert()->success('Sukses','Data berhasil di tambahkan.')->autoClose(2000);
        // alert()->success('SuccessAlert','Lorem ipsum dolor sit amet.')->persistent(false,false);
        // return redirect('/admin/gudang');
        return redirect('/admin/gudang')->with('success', 'Data berhasil di tambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Gudang $gudang)
    {
        if(auth()->user()->cannot('view', Gudang::class)) abort('403', 'access denied');

        return view('gudang.detail',compact(
            'gudang'
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
        if(auth()->user()->cannot('update', Gudang::class)) abort('403', 'access denied');

        $gudang = Gudang::find($id);

        return view('gudang.edit', compact(
            'gudang'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Gudang  $gudang)
    {
        if(auth()->user()->cannot('update', Gudang::class)) abort('403', 'access denied');

        $validate = $this->validation($gudang->id);
        
        $gudang = Gudang::find($gudang->id);
        $gudang->kode_gudang = $request->kode_gudang;
        $gudang->nama_gudang = $request->nama_gudang;
        $gudang->deskripsi_gudang = $request->deskripsi_gudang;
        $gudang->nama_penanggungjawab = $request->nama_penanggungjawab;
        $gudang->save();

        return redirect('/admin/gudang')->with('success', 'Data berhasil di ubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(auth()->user()->cannot('delete', Gudang::class)) abort('403', 'access denied');

        try {
            $gudang = Gudang::find($id);
            $gudang->delete();
        } catch (\Throwable $th) {
            return back()->with('fail', 'Data tidak bisa dihapus! sudah digunakan dalam transaksi');
        }

        alert()->success('Sukses','Data berhasil di hapus.')->autoClose(1500);
        return redirect('/admin/gudang');
        // return redirect('/admin/gudang')->with('success', 'Data berhasil di hapus');
    }

    private function validation($id = null)
    {
        $validate = request()->validate([
            'kode_gudang' => 'required|unique:gudangs,kode_gudang, '.$id,
            'nama_gudang' => 'required'
         ]);

         return $validate;
    }
}
