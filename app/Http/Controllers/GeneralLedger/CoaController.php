<?php

namespace App\Http\Controllers\GeneralLedger;

use App\Http\Controllers\Controller;
use App\Models\BukuBankRinci;
use App\Models\BukuBesar;
use App\Models\Coa;
use App\Models\JurnalVoucherRinci;
use App\Models\TipeCoa;
use Illuminate\Http\Request;
use PDF;
use DataTables;
use Carbon\Carbon;

class CoaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     if(auth()->user()->cannot('viewAny', Coa::class)) abort(403, "Access Denied.");

    //     $coa = Coa::with('tipeCoa')
    //             ->whereIsActive(1)
    //             ->latest()->get();

    //     return view('generalledger.coa.index', compact('coa'));
    // }

    public function index(Request $request)
    {
        if(auth()->user()->cannot('viewAny', Coa::class)) abort(403, "Access Denied.");

        $coa = Coa::with('tipeCoa')
                ->whereIsActive(1)
                ->latest()->get();

        if($request->ajax()){
            return datatables()->of($coa)
            
            ->addIndexColumn()
            ->addColumn('actions', function($row){
                return '<td>
                <a class="badge badge-light-secondary" href="' . route('admin.coa.show', $row->id) .'"><i data-feather="eye"></i> Lihat</a>           
                </td>';
            })
            // ->filterColumn('tanggal', function ($query, $keyword) {
            //     $query->whereRaw("DATE_FORMAT(tanggal,'%m/%d/%Y') LIKE ?", ["%$keyword%"]);
            // })
            ->editColumn('tipecoa', function($row){
                return $row->tipeCoa ? $row->tipeCoa->tipecoa : $row->tipecoa;
            })
            ->editColumn('keterangan', function($row){
                $keterangan = !empty($row->keterangan) ? $row->keterangan : '-';
            return $keterangan;
            })
            ->rawColumns(['actions','tipecoa','keterangan'])->make(true);
            // ->rawColumns(['actions'])
            // ->make(true);
        }

        return view('generalledger.coa.index', compact('coa'));
    }

    public function exportPDF()
	{
        $coa = Coa::with('tipeCoa')
                ->whereIsActive(1)
                ->latest()->get();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('generalledger.coa.exportpdf',compact('coa'));
        return $pdf->setWarnings(false)->download('Daftar Akun.pdf');
	}

    public function printPDF()
	{
        $coa = Coa::with('tipeCoa')
                ->whereIsActive(1)
                ->latest()->get();
                $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('generalledger.coa.exportpdf',compact('coa'));
        return $pdf->setWarnings(false)->stream();
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->cannot('create', Coa::class)) abort(403, "Access Denied.");

        $tipeCoa = TipeCoa::get();

        return view('generalledger.coa.create', compact('tipeCoa'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->cannot('create', Coa::class)) abort(403, "Access Denied.");

        $request->validate([
            'nomer_coa' => 'unique:coa',
        ]);

        Coa::create([
            'id_coatype' => $request->id_coatype,
            'nomer_coa' => $request->nomer_coa,
            'nama_coa' => $request->nama_coa,
            'keterangan' => $request->keterangan,
            'saldo_awal' => explodeRupiah($request->saldo_awal),
        ]);

        return redirect()->route('admin.coa.index')->with('success', 'List Account (COA) (Daftar Akun) berhasil dibuat');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Coa $coa)
    {
        if(auth()->user()->cannot('view', Coa::class)) abort(403, "Access Denied.");

        return view('generalledger.coa.detail', compact('coa'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Coa $coa)
    {
        if(auth()->user()->cannot('update', Coa::class)) abort(403, "Access Denied.");

        $tipeCoa = TipeCoa::get();

        return view('generalledger.coa.edit', compact('tipeCoa', 'coa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coa $coa)
    {
        if(auth()->user()->cannot('update', Coa::class)) abort(403, "Access Denied.");

        if($request->nomer_coa != $coa->nomer_coa){
            $request->validate([
                'nomer_coa' => 'unique:coa',
            ]);
        }

        $coa->update([
            'id_coatype' => $request->id_coatype,
            'nomer_coa' => $request->nomer_coa,
            'nama_coa' => $request->nama_coa,
            'keterangan' => $request->keterangan,
            'saldo_awal' => explodeRupiah($request->saldo_awal),
        ]);

        return redirect()->route('admin.coa.index')->with('success', 'List Account (COA) (Daftar Akun) berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coa $coa)
    { 
        if(auth()->user()->cannot('delete', Coa::class)) abort(403, "Access Denied.");

        //cek bank ready exists
        $bukuBankRinci = BukuBankRinci::whereCoaId($coa->id)->first();
        $bukuBesar = BukuBesar::whereCoaId($coa->id)->first();
        $jurnalVoucherRinci = JurnalVoucherRinci::whereCoaId($coa->id)->first();

        if(!empty($bukuBankRinci) || !empty($bukuBesar) || !empty($jurnalVoucherRinci)){
            return redirect()->route('admin.coa.index')->with('fail', 'List Account (COA) (Daftar Akun) sudah digunakan tidak bisa dihapus');
        }
        
        $coa->update(['is_active' => 0]);
        return redirect()->route('admin.coa.index')->with('success', 'List Account (COA) (Daftar Akun) berhasil dihapus');
    }
}
