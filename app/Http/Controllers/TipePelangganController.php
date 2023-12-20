<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TipePelanggan;
use Illuminate\Support\Facades\Gate;
use DataTables;
use PDF;


class TipePelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     if(!Gate::allows('read-tipepelanggan')) abort(403);

    //     $tipepelanggan = TipePelanggan::all();

    //     return view('tipepelanggan.index', compact(
    //         'tipepelanggan'
    //     ));
    // }

    public function exportPDF()
	{
        $tipepelanggan = TipePelanggan::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('tipepelanggan.exportpdf',compact('tipepelanggan'));
        return $pdf->download('Tipe Pelanggan.pdf');
	}

    public function printPDF()
	{
        $tipepelanggan = TipePelanggan::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('tipepelanggan.exportpdf',compact('tipepelanggan'));
        return $pdf->stream();
	}

    public function index(Request $request)
    {
        if(auth()->user()->cannot('viewAny', TipePelanggan::class)) abort('403', 'access denied');

        $tipepelanggan = TipePelanggan::all();

        if($request->ajax()){
            return datatables()->of($tipepelanggan)
            
            ->addIndexColumn()
            ->addColumn('actions', function($row){
                return '<td>
                <a class="badge badge-light-secondary" href="' . route('admin.tipepelanggan.show', $row->id) .'"><i data-feather="eye"></i> Lihat</a>            
                </td>';
            })
            ->rawColumns(['actions'])
            ->make(true); 
        }

        return view('tipepelanggan.index', compact(
            'tipepelanggan'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->cannot('create', TipePelanggan::class)) abort('403', 'access denied');

        return view('tipepelanggan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->cannot('create', TipePelanggan::class)) abort('403', 'access denied');

        $validate = $this->validation();

        $tipepelanggan                          = new TipePelanggan;
        $tipepelanggan->tipepelanggan           = $request->tipepelanggan;
        $tipepelanggan->deskripsi_tipepelanggan = $request->deskripsi_tipepelanggan;
        $tipepelanggan->save();

        //redirect ke create lagi setelah create
        if (isset($_POST['lagi'])) {
            return back()->with('success', 'Data berhasil di tambahkan');
        }

        return redirect('/admin/tipepelanggan')->with('success', 'Data berhasil di tambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TipePelanggan $tipepelanggan)
    {
        if(auth()->user()->cannot('view', TipePelanggan::class)) abort('403', 'access denied');

        return view('tipepelanggan.detail',compact(
            'tipepelanggan'
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
        if(auth()->user()->cannot('update', TipePelanggan::class)) abort('403', 'access denied');

        $tipepelanggan = TipePelanggan::find($id);

        return view('tipepelanggan.edit', compact(
            'tipepelanggan'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TipePelanggan $tipepelanggan)
    {
        if(auth()->user()->cannot('update', TipePelanggan::class)) abort('403', 'access denied');

        $validate = $this->validation($tipepelanggan->id);

        $tipepelanggan = TipePelanggan::find($tipepelanggan->id);
        $tipepelanggan->tipepelanggan = request()->tipepelanggan;
        $tipepelanggan->deskripsi_tipepelanggan = request()->deskripsi_tipepelanggan;
        $tipepelanggan->save();

        return redirect('/admin/tipepelanggan')->with('success', 'Data berhasil di ubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipePelanggan $tipepelanggan)
    {
        if(auth()->user()->cannot('delete', TipePelanggan::class)) abort('403', 'access denied');

        try {
            $tipepelanggan = TipePelanggan::find($tipepelanggan->id);
            $tipepelanggan->delete();
        } catch (\Throwable $th) {
            return back()->with('fail', 'Data tidak bisa dihapus! sudah digunakan dalam transaksi');
        }
        
        return redirect('/admin/tipepelanggan')->with('success', 'Data berhasil di hapus');
    }

    private function validation($id = null)
    {
        $validate = request()->validate([
            'tipepelanggan' => 'required|unique:tipepelanggans,tipepelanggan,'.$id
        ]);

        return $validate;
    }
}
