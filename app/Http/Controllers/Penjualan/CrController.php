<?php

namespace App\Http\Controllers\Penjualan;

use App\Http\Controllers\Controller;
use App\Models\BerkasCr;
use App\Models\Coa;
use App\Models\Pelanggan;
use App\Models\Penjualan_Invoice;
use App\Models\Penjualan_Invoice_Rinci;
use App\Models\Penjualan_SO;
use App\Models\PenjualanCR;
use App\Models\PenjualanCR_Rinci;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Gate;
use DataTables;
use Carbon\Carbon;
use DB;
use Ramsey\Uuid\Uuid;

class CrController extends Controller
{
    //
    // public function index()
    // {
    //     if(auth()->user()->cannot('viewAny', PenjualanCR::class)) abort('403', 'access denied');

    //     $cr = PenjualanCR::with('pelanggan')->get();
    //     return view('penjualan.cr.index', ['cr' => $cr]);
    // }

    public function index(Request $request)
    {
        if(auth()->user()->cannot('viewAny', PenjualanCR::class)) abort('403', 'access denied');

        $cr = PenjualanCR::with('pelanggan')->get();

        if($request->ajax()){
            return datatables()->of($cr)
            
            ->addIndexColumn()
            ->addColumn('actions', function($row){
                return '<td>
                <a class="badge badge-light-secondary" href="' . url('admin/cr/' . $row->id) .'"><i data-feather="eye"></i> Lihat</a>            
                </td>';
            })
            // ->editColumn('tanggal', function ($row) {
            //     return $row->created_at->format('Y/m/d');
            // })
            ->editColumn('tanggal_cr', function ($row) {
                return $row->tanggal_cr ? with(new Carbon($row->tanggal_cr))->format('d-m-Y') : '';;
            })
            // ->filterColumn('tanggal', function ($query, $keyword) {
            //     $query->whereRaw("DATE_FORMAT(tanggal,'%m/%d/%Y') LIKE ?", ["%$keyword%"]);
            // })
            ->editColumn('nama_pelanggan', function($row){
                return $row->pelanggan ? $row->pelanggan->nama_pelanggan : $row->nama_pelanggan;
            })
            ->editColumn('total_payment', function($row){
                return "Rp. " . number_format($row->total_payment,0,',','.');
            })
            ->rawColumns(['actions','tanggal_cr','nama_pelanggan','total_payment'])->make(true);
            // ->rawColumns(['actions'])
            // ->make(true);
        }

        return view('penjualan.cr.index', ['cr' => $cr]);
    }

    public function exportPDF()
	{
        $cr = PenjualanCR::with('pelanggan')->get();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('penjualan.cr.exportpdf',compact('cr'));
        return $pdf->download('Pembayaran Pelanggan.pdf');
	}

    public function printPDF()
	{
        $cr = PenjualanCR::with('pelanggan')->get();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('penjualan.cr.exportpdf',compact('cr'));
        return $pdf->stream();
	}

    public function create()
    {
        if(auth()->user()->cannot('create', PenjualanCR::class)) abort('403', 'access denied');

        $coaDebit = Coa::with('tipeCoa')->whereIsActive(1)->whereIn('id_coatype', [1])->latest()->get(); 
        $customer = [];
        $pelanggan = Pelanggan::all();
        foreach($pelanggan as $cust){
            if(Penjualan_Invoice::where(['pelanggan_id' => $cust->id])->where('is_payment','!=' ,2)->count() != 0){
                array_push($customer, $cust);
            }
        }
        $data = [
            'customer' => $customer,
            'coaDebit' => $coaDebit
        ];
        return view('penjualan.cr.create', $data);
    }

    public function getInvoice($idpelanggan)
    {
        if(auth()->user()->cannot('view', PenjualanCR::class)) abort('403', 'access denied');

        $invoice = Penjualan_Invoice::where('pelanggan_id', $idpelanggan)->where('is_payment', '!=', 2)->get();
        $index = 0;
        foreach($invoice as $inv){
            $rinci = Penjualan_Invoice_Rinci::where('penjualan_invoice_id', $inv->id)->get();
            $tagihan = 0;
            foreach($rinci as $value){
                $total = $value->harga * $value->qty;
                $subtotal = $total - ($total * $value->dsc / 100) - $value->diskon_nominal - $value->potongan_admin + $value->cashback_ongkir;
                $tagihan += $subtotal;
            }
            $bayar_sebelumnya = PenjualanCR_Rinci::where('penjualan_invoice_id', $inv->id)->sum('total_payment');
        
            $invoice[$index]->jumlah_tagihan = $tagihan;
            $invoice[$index]->bayar_sebelumnya = $bayar_sebelumnya;
            $invoice[$index]->sisa = $tagihan - $bayar_sebelumnya;

            $index++;
        }
        return response()->json($invoice);
    }


    public function store(Request $request)
    {
        if(auth()->user()->cannot('create', PenjualanCR::class)) abort('403', 'access denied');

        $request->validate([
            'cr_nomer' => 'required|unique:penjualan_cr,nomer_cr',
            'cr_tanggal' => 'required',
            'id_pelanggan' => 'required',
            'payment' => 'required',
            'debit_coa_id' => 'required'
        ]);

        $data = new PenjualanCR();
        $data->pelanggan_id = $request->id_pelanggan;
        $data->nomer_cr = $request->cr_nomer;
        $data->tanggal_cr = $request->cr_tanggal;
        $data->note = $request->keterangan;
        $data->bank = $request->debit_coa_id;
        $data->total_payment = explodeRupiah($request->payment);
        $data->save();

        $index = 0;
        foreach($request->invoice_id as $inv_id){
            $cr_rinci = new PenjualanCR_Rinci;
            $cr_rinci->penjualan_cr_id = $data->id;
            $cr_rinci->penjualan_invoice_id = $inv_id;
            $cr_rinci->total_payment = explodeRupiah($request->bayar[$index]);
            $cr_rinci->keterangan = $request->note[$index];
            $cr_rinci->save();

            // Hitung Total Invoice
            $totalInvoice = 0;
            $invoiceDetail = Penjualan_Invoice_Rinci::where('penjualan_invoice_id', $inv_id)->get();
            $invoice = Penjualan_Invoice::with('so')->find($inv_id);
            $salesOrder = Penjualan_SO::find($invoice->so_id);
            
            foreach($invoiceDetail as $key => $value){
                $totalInvoice += (($value->harga - ($value->harga * ($value->dsc / 100)) - $value->diskon_nominal) * $value->qty) - $value->potongan_admin + $value->cashback_ongkir;
                
                if ($invoice->so->is_tax == 1) {
                    $totalInvoice = $totalInvoice + ($totalInvoice * (10 / 100));
                }
            }
            // Hitung Total Pembayaran
            $totalPayment = PenjualanCR_Rinci::where('penjualan_invoice_id', $inv_id)->sum('total_payment');
            if($totalPayment < $totalInvoice){
                $invoice->is_payment = 1;
                $invoice->save();
                $salesOrder->status_invoice = 1;
                $salesOrder->save();
            }else{
                $invoice->is_payment = 2;
                $invoice->save();
                $salesOrder->status_invoice = 2;
                $salesOrder->save();
            }
            $index++;
        }


        // buat data berkas
        if (!empty($request->berkas)) {
            $totalBerkas = count($request->berkas);
            $collectNamaBerkas = [];

            for ($i = 0; $i < $totalBerkas; $i++) {
                if ($request->hasFile('berkas.' . $i)) {
                    $file = $request->file('berkas')[$i];
                    $originalName = $file->getClientOriginalName();
                    $berkas = $originalName;
                    $file->move('uploads/cr_penjualan', $berkas);

                    array_push($collectNamaBerkas, $berkas);
                }
            }

            $saveBerkas = count($collectNamaBerkas);

            $berkas_so = new BerkasCr();
            $berkas_so->cr_id = $data->id;

            for ($i = 0; $i < $saveBerkas; $i++) {
                $flag = $i + 1;
                $var = "berkas_" . $flag;
                $berkas_so->$var = $collectNamaBerkas[$i];
            }
            $berkas_so->save();
        }

        $pelangganDetail = Pelanggan::find($request->id_pelanggan);

        // Input Buku bank
        $bukuBankId = Uuid::uuid4()->toString();
        storeBukuBankCR($bukuBankId, $request->cr_nomer, $request->cr_tanggal, "CR",  $request->keterangan);

        // input buku bank rinci
        storeBukuBankRinci($bukuBankId, $request->debit_coa_id, explodeRupiah($request->payment), '-', 'D');

        // input kredit 
        storeGeneralLedger([
            'tahun' => date('Y', strtotime($request->cr_tanggal)),
            'tanggal' => $request->cr_tanggal,
            'nomer' => $request->cr_nomer,
            'sumber' => 'sls_cr',
            'coa_no' => 1100,
            'coa' => 'Piutang Dagang',
            'pelanggan' => $pelangganDetail->nama_pelanggan,
            'pemasok' => null,
            'debit' => 0,
            'kredit' => explodeRupiah($request->payment)
        ]);
        $coaDetail = Coa::find($request->debit_coa_id);
        // input debit
        storeGeneralLedger([
            'tahun' => date('Y', strtotime($request->cr_tanggal)),
            'tanggal' => $request->cr_tanggal,
            'nomer' => $request->cr_nomer,
            'sumber' => 'sls_cr',
            'coa_no' => $coaDetail->nomer_coa,
            'coa' => $coaDetail->nama_coa,
            'pelanggan' => $pelangganDetail->nama_pelanggan,
            'pemasok' => null,
            'debit' => explodeRupiah($request->payment),
            'kredit' => 0
        ]);

        return redirect('admin/cr')->with('success', 'Berhasil membuat data');
    }

    public function show(PenjualanCR $cr)
    {
        if(auth()->user()->cannot('view', PenjualanCR::class)) abort('403', 'access denied');

        $data = [
            'cr' => $cr
        ];

        return view('penjualan.cr.show', $data);
    }
}
