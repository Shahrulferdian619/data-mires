<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategoribarang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KategoriBarangExport;
use App\Imports\KategoriBarangImport;
use PDF;
use DataTables;
use Illuminate\Support\Facades\Http;

class KategoriBarangController extends Controller
{
    public function exportPDF()
	{
		// if(auth()->user()->cannot('viewAny', Kategoribarang::class)) abort(403, 'access denied');
		// $kategoribarangs = Kategoribarang::all();
        // $pdf = PDF::loadView('kategoribarang.pdf', compact('kategoribarang'));
		// // $pdf = PDF::loadview('kategoribarang.pdf', ['KategoriBarang'=>$kategoribarangs]);
        // return $data->download('laporan.pdf');
        $kategoribarangs = Kategoribarang::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('kategoribarang.exportpdf',compact('kategoribarangs'));
        return $pdf->download('Kategori Barang.pdf');
		// return $pdf->stream();
	}
    
    // public function exportPDF()
    // {
    // 	// $pegawai = Pegawai::all();
    //     $kategoribarangs = Kategoribarang::all();
 
    // 	$pdf = PDF::loadview('kategoribarang.pdf',['kategoribarang'=>$kategoribarangs]);
    // 	return $pdf->download('laporan-kategoribarang-pdf');
    // }

    public function printPDF()
	{
        $kategoribarangs = Kategoribarang::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('kategoribarang.exportpdf',compact('kategoribarangs'));
        return $pdf->stream();
	}

    public function importExcel(Request $request) 
    {
        // Excel::import(new KategoriBarangImport, request()->file('file'));
        
        // return back()->with('Sukses', 'Data Berhasil Diimport!')
        // $validatedData = $request->validate([
		// 	'file' => 'required|mimes:csv,xls,xlsx,txt',
		// ]);

		// /**
		//  * Upload + simpan file
		//  */
 		// // ambil file upload
		// $file = $request->file('file'); 
		// // penamaan file upload
		// $filename = Str::replaceArray(':',['-', '-'],  Carbon::now()) . '.' . $file->getClientOriginalName();
		
		// // Save to
        // $file->storeAs(
        //     'public/import/', $filename
		// );
		
		// //Import Dari
		// Excel::import(new KategoriBarangImport, 'public/import/' .  $filename);


		// /**
		//  * Upload tanpa simpan file
		//  */
 
		// // ambil nama file dan import
		Excel::import(new KategoriBarangImport, request()->file('file')); 

		// // notifikasi dengan session
		Session::flash('success','Data Berhasil Diimport!');
 
		// alihkan halaman kembali
		return redirect('/admin/kategoribarang');
	}
    
    public function exportExcel($type) 
	{
        return \Excel::download(new KategoriBarangExport, 'kategoribarang.'.$type);
		// return Excel::download(new KategoriBarangExport, ('users'. '.' .$type));
	}
    
    // public function exportExcel() 
    // {
    //     return Excel::download(new KategoriBarangExport, 'kategoribarang.xlsx');
    // }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     if(auth()->user()->cannot('viewAny', Kategoribarang::class)) abort(403, 'access denied');

    //     $kategoribarangs = Kategoribarang::all();

    //     return view('kategoribarang.index', compact(
    //         'kategoribarangs'
    //     ));
    // }

    public function index(Request $request)
    {
        if(auth()->user()->cannot('viewAny', Kategoribarang::class)) abort(403, 'access denied');

        $kategoribarangs = Kategoribarang::all();

        if($request->ajax()){
            return datatables()->of($kategoribarangs)
            
            ->addIndexColumn()
            ->addColumn('actions', function($row){
                return '<td>
                <a class="badge badge-light-secondary" href="' . route('admin.kategoribarang.show', $row->id) .'"><i data-feather="eye"></i> Lihat</a>            
                </td>';
            })
            ->rawColumns(['actions'])
            ->make(true); 
        }

        return view('kategoribarang.index', compact(
            'kategoribarangs'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->cannot('create', Kategoribarang::class)) abort(403, 'access denied');

        return view('kategoribarang.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->cannot('create', Kategoribarang::class)) abort(403, 'access denied');

        $validate = $this->validation();

        $kategoribarangs = new Kategoribarang;
        $kategoribarangs->nama_kategori = $request->nama_kategori;
        $kategoribarangs->deskripsi_kategori = $request->deskripsi_kategori;
        $kategoribarangs->save();

        return redirect('/admin/kategoribarang');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Kategoribarang $kategoribarang)
    {
        // $kategoribarang = Kategoribarang::find($kategoribarang);

        if(auth()->user()->cannot('view', Kategoribarang::class)) abort(403, 'access denied');

        return view('kategoribarang.detail', compact(
            'kategoribarang'
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
        if(auth()->user()->cannot('update', Kategoribarang::class)) abort(403, 'access denied');

        $kategoribarang = Kategoribarang::find($id);

        return view('kategoribarang.edit', compact(
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
    public function update(Kategoribarang $kategoribarang)
    {
        if(auth()->user()->cannot('update', Kategoribarang::class)) abort(403, 'access denied');

        $validate = $this->validation($kategoribarang->id);

        $kategoribarang = Kategoribarang::find($kategoribarang->id);
        $kategoribarang->nama_kategori = request()->nama_kategori;
        $kategoribarang->deskripsi_kategori = request()->deskripsi_kategori;
        $kategoribarang->save();

        return redirect('/admin/kategoribarang');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kategoribarang $kategoribarang)
    {
        if(auth()->user()->cannot('delete', Kategoribarang::class)) abort(403, 'access denied');

        $kategoribarang = Kategoribarang::find($kategoribarang->id);
        $kategoribarang->delete();

        return redirect('/admin/kategoribarang');
    }

    private function validation($id = null)
    {
        $validate = request()->validate([
            'nama_kategori' => 'required|unique:kategoribarangs,nama_kategori, '.$id,
        ]);

        return $validate;
    }
}
