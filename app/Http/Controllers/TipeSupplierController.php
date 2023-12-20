<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TipeSupplier;
use Illuminate\Support\Facades\Gate;
use PDF;
use DataTables;

class TipeSupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function exportPDF()
	{
        $tipesupplier = TipeSupplier::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('tipesupplier.exportpdf',compact('tipesupplier'));
        return $pdf->download('Tipe Supplier.pdf');
	}

    public function printPDF()
	{
        $tipesupplier = TipeSupplier::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('tipesupplier.exportpdf',compact('tipesupplier'));
        return $pdf->stream();
	}

    // public function index()
    // {
    //     if(auth()->user()->cannot('viewAny', TipeSupplier::class)) abort('403', 'access denied');

    //     $tipesupplier = TipeSupplier::all();

    //     return view('tipesupplier.index', compact(
    //         'tipesupplier'
    //     ));
    // }

    public function index(Request $request)
    {
        if(auth()->user()->cannot('viewAny', TipeSupplier::class)) abort('403', 'access denied');

        $tipesupplier = TipeSupplier::all();

        if($request->ajax()){
            return datatables()->of($tipesupplier)
            
            ->addIndexColumn()
            ->addColumn('actions', function($row){
                return '<td>
                <a class="badge badge-light-secondary" href="' . route('admin.tipesupplier.show', $row->id) .'"><i data-feather="eye"></i> Lihat</a>            
                </td>';
            })
            ->rawColumns(['actions'])
            ->make(true); 
        }

        return view('tipesupplier.index', compact(
        'tipesupplier'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->cannot('create', TipeSupplier::class)) abort('403', 'access denied');

        return view('tipesupplier.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        if(auth()->user()->cannot('create', TipeSupplier::class)) abort(403, 'access denied');
        $validate = $this->validation();

        $tipesupplier                          = new TipeSupplier;
        $tipesupplier->tipesupplier           = $request->tipesupplier;
        $tipesupplier->deskripsi_tipesupplier = $request->deskripsi_tipesupplier;
        $tipesupplier->save();
        
        //redirect ke create lagi setelah create
        if (isset($_POST['lagi'])) {
            return back()->with('success', 'Data berhasil di tambahkan');
        }

        return redirect('/admin/tipesupplier')->with('success', 'Data berhasil di tambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TipeSupplier $tipesupplier)
    {
        if(auth()->user()->cannot('view', TipeSupplier::class)) abort('403', 'access denied');

        return view('tipesupplier.detail', compact(
            'tipesupplier'
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
        
        if(auth()->user()->cannot('update', TipeSupplier::class)) abort(403, 'access denied');

        $tipesupplier = TipeSupplier::find($id);

        return view('tipesupplier.edit', compact(
            'tipesupplier'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TipeSupplier $tipesupplier)
    {
        
        if(auth()->user()->cannot('update', TipeSupplier::class)) abort(403, 'access denied');
        $validate = $this->validation($tipesupplier->id);

        $tipesupplier = TipeSupplier::find($tipesupplier->id);
        $tipesupplier->tipesupplier = request()->tipesupplier;
        $tipesupplier->deskripsi_tipesupplier = request()->deskripsi_tipesupplier;
        $tipesupplier->save();

        return redirect('/admin/tipesupplier')->with('success', 'Data berhasil di ubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipeSupplier $tipesupplier)
    {
        
        if(auth()->user()->cannot('delete', TipeSupplier::class)) abort(403, 'access denied');
        try {      
            $tipesupplier = TipeSupplier::find($tipesupplier->id);
            $tipesupplier->delete();
        } catch (\Throwable $th) {
            return back()->with('fail', 'Data tidak bisa dihapus! sudah digunakan dalam transaksi');
        }

        return redirect('/admin/tipesupplier')->with('success', 'Data berhasil di hapus');
    }

    private function validation($id = null)
    {
        $validate = request()->validate([
            'tipesupplier' => 'required|unique:tipesuppliers,tipesupplier,'.$id,
        ]);

        return $validate;
    }
}
