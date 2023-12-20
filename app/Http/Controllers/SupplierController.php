<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TipeSupplier;
use Illuminate\Support\Facades\Gate;
use PDF;
use DataTables;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     if(auth()->user()->cannot('viewAny', Supplier::class)) abort('403', 'access denied');

    //     $supplier = Supplier::all();

    //     return view('supplier.index', compact(
    //         'supplier'
    //     ));
    // }

    public function index(Request $request)
    {
        if(auth()->user()->cannot('viewAny', Supplier::class)) abort('403', 'access denied');

        $supplier = Supplier::all();

        if($request->ajax()){
            return datatables()->of($supplier)
            
            ->addIndexColumn()
            ->addColumn('actions', function($row){
                return '<td>
                <a class="badge badge-light-secondary" href="' . route('admin.supplier.show', $row->id) .'"><i data-feather="eye"></i> Lihat</a>            
                </td>';
            })
            ->editColumn('tipesupplier', function($row){
                return $row->tipesupplier ? $row->tipesupplier->tipesupplier : $row->tipesupplier;
            })
            ->editColumn('alamat', function($row){
                return $row->negara.', '.$row->provinsi.', '.$row->kota.', '.$row->kecamatan;
            })
            ->rawColumns(['actions','tipesupplier','alamat'])->make(true);
        }

        return view('supplier.index', compact(
            'supplier'
        ));
    }

    public function exportPDF()
	{
        $supplier = Supplier::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('supplier.exportpdf',compact('supplier'));
        return $pdf->setPaper('a4', 'landscape')->setWarnings(false)->download('Supplier.pdf');
	}

    public function printPDF()
	{
        $supplier = Supplier::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('supplier.exportpdf',compact('supplier'));
        return $pdf->setPaper('a4', 'landscape')->setWarnings(false)->stream();
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->cannot('create', Supplier::class)) abort('403', 'access denied');

        $tipesupplier = TipeSupplier::all();

        return view('supplier.create', compact(
            'tipesupplier'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->cannot('create', Supplier::class)) abort('403', 'access denied');

        $validate = $this->validation();

        $supplier = new Supplier();
        $supplier->kode_supplier = $request->kode_supplier;
        $supplier->nama_supplier = $request->nama_supplier;
        $supplier->handphone_supplier = $request->handphone_supplier;
        $supplier->email_supplier = $request->email_supplier;
        $supplier->pic = $request->pic_supplier;
        $supplier->nomer_rekening = $request->rekening_supplier;
        $supplier->negara = $request->negara;
        $supplier->provinsi = $request->provinsi;
        $supplier->kota = $request->kota;
        $supplier->kecamatan = $request->kecamatan;
        $supplier->detail_alamat = $request->detail_alamat;
        $supplier->deskripsi_supplier = $request->deskripsi_supplier;
        $supplier->tipesupplier_id = $request->tipesupplier_id;
        $supplier->save();

        //redirect ke create lagi setelah create
        if (isset($_POST['lagi'])) {
            return back()->with('success', 'Data berhasil di tambahkan');
        }

        return redirect('/admin/supplier')->with('success', 'Data berhasil di tambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        if(auth()->user()->cannot('view', Supplier::class)) abort('403', 'access denied');

        return view('supplier.detail', compact(
            'supplier'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(auth()->user()->cannot('update', Supplier::class)) abort('403', 'access denied');

        $supplier = Supplier::find($id);
        $tipesupplier = TipeSupplier::all();

        return view('supplier.edit', compact(
            'supplier',
            'tipesupplier'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        if(auth()->user()->cannot('update', Supplier::class)) abort('403', 'access denied');

        $validate = $this->validation($supplier->id);

        $supplier->kode_supplier = $request->kode_supplier;
        $supplier->nama_supplier = $request->nama_supplier;
        $supplier->handphone_supplier = $request->handphone_supplier;
        $supplier->email_supplier = $request->email_supplier;
        $supplier->negara = $request->negara;
        $supplier->provinsi = $request->provinsi;
        $supplier->kota = $request->kota;
        $supplier->pic = $request->pic_supplier;
        $supplier->nomer_rekening = $request->rekening_supplier;
        $supplier->kecamatan = $request->kecamatan;
        $supplier->detail_alamat = $request->detail_alamat;
        $supplier->deskripsi_supplier = $request->deskripsi_supplier;
        $supplier->tipesupplier_id = $request->tipesupplier_id;
        $supplier->save();

        return redirect('/admin/supplier')->with('success', 'Data berhasil di ubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        if(auth()->user()->cannot('delete', Supplier::class)) abort('403', 'access denied');

        try {
            $supplier = Supplier::find($supplier->id);
            $supplier->delete();
        } catch (\Throwable $th) {
            return back()->with('fail', 'Data tidak bisa dihapus! sudah digunakan dalam transaksi');
        }

        return redirect('/admin/supplier')->with('success', 'Data berhasil di ubah');
    }

    private function validation($id = null)
    {
        $validate = request()->validate([
            'tipesupplier_id' => 'required',
            'kode_supplier' => 'required|unique:suppliers,kode_supplier, '.$id,
            'nama_supplier' => 'required',
            'pic_supplier' => 'required',
            'detail_alamat' => 'required'
        ]);

        return $validate;
    }
}
