<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sales;
use PDF;
use Illuminate\Support\Facades\Gate;
use DataTables;

class SalesController extends Controller
{
    
    public function index(Request $request)
    {
        if(auth()->user()->cannot('viewAny', Sales::class)) abort('403', 'access denied');

        $sales = Sales::all();

        if($request->ajax()){
            return datatables()->of($sales)
            
            ->addIndexColumn()
            ->addColumn('actions', function($row){
                return '<td>
                <a class="badge badge-light-secondary" href="' . route('admin.sales.show', $row->id) .'"><i data-feather="eye"></i> Lihat</a>            
                </td>';
            })
            ->editColumn('target_total_invoice', function($row){
                return "Rp. " . number_format($row->target_total_invoice,0,',','.');
            })
            ->editColumn('bonus_presentase', function($row){
                return $row->bonus_presentase . " %";
            })
            ->editColumn('kode', function($row){
                return $row->kode;
            })
            ->rawColumns(['actions','target_total_invoice','bonus_presentase'])->make(true);
        }

        return view('sales.index', compact(
        'sales'
        ));
    }

    public function exportPDF()
	{
        $sales = Sales::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('sales.exportpdf',compact('sales'));
        return $pdf->download('Sales.pdf');
		// return $pdf->stream();
	}

    public function printPDF()
	{
        $sales = Sales::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('sales.exportpdf',compact('sales'));
        return $pdf->stream();
		// return $pdf->stream();
	}

    public function create()
    {
        if(auth()->user()->cannot('create', Sales::class)) abort('403', 'access denied');

        return view('sales.create');
    }

    public function store(Request $request)
    {
        if(auth()->user()->cannot('create', Sales::class)) abort('403', 'access denied');

        $validate = $this->validation();

        $sales = new Sales;
        $sales->nama_sales = $request->nama_sales;
        $sales->target_total_invoice = explodeRupiah($request->target_total_invoice);
        $sales->bonus_presentase = $request->bonus_presentase;
        $sales->keterangan = $request->keterangan;
        $sales->kode = $request->kode_area.'-'.$request->nama_sales;
        $sales->kode_area = $request->kode_area;
        $sales->save();

        //redirect ke create lagi setelah create
        if (isset($_POST['lagi'])) {
            return back()->with('success', 'Data berhasil di tambahkan');
        }

        return redirect('/admin/sales')->with('success', 'Data berhasil ditambahkan');
    }

    public function show($id)
    {
        if(auth()->user()->cannot('view', Sales::class)) abort('403', 'access denied');

        $sales = sales::find($id);

        return view('sales.detail', compact(
            'sales'
        ));
    }

    public function edit($id)
    {
        if(auth()->user()->cannot('update', Sales::class)) abort('403', 'access denied');

        $sales = sales::find($id);

        return view('sales.edit', compact(
            'sales'
        ));
    }

    public function update(Request $request, $id)
    {
        if(auth()->user()->cannot('update', Sales::class)) abort('403', 'access denied');

        $validate = $this->validation($id);

        $sales = Sales::find($id);
        $sales->nama_sales = $request->nama_sales;
        $sales->kode = $request->kode_sales;
        $sales->kode_area = $request->kode_area;
        $sales->target_total_invoice = explodeRupiah($request->target_total_invoice);
        $sales->bonus_presentase = $request->bonus_presentase;
        $sales->keterangan = $request->keterangan;
        $sales->kode = $request->kode_area.'-'.$request->nama_sales;
        $sales->save();

        return redirect('/admin/sales')->with('success', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        if(auth()->user()->cannot('delete', Sales::class)) abort('403', 'access denied');

        $sales = Sales::find($id);
        $sales->delete();

        return redirect('/admin/sales');
    }

    private function validation($id = null)
    {
        $validate = request()->validate([
            'nama_sales' => 'required|unique:sales,nama_sales, '.$id,
        ]);

        return $validate;
    }
}
