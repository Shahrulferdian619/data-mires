<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BerkasPembayaran;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Supplier;
use App\Models\fakturpembelian;
use App\Models\fakturpembelian_rinci;
use App\Models\PaymentToFaktur;
use App\mOdels\FakturToRelation;
use Illuminate\Support\Facades\Gate;
use PDF;
use DataTables;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     //
    //     if(auth()->user()->cannot('viewAny', Payment::class)) abort('403', 'access denied');

    //     $payment = Payment::all();
    //     $index = 0;
    //     foreach ($payment as $pay) {
    //         $total_bayar = PaymentToFaktur::where('payment_id', $pay->id)->sum('jumlah_bayar');
    //         $payment[$index]->total_bayar = $total_bayar;
    //         $index++;
    //     }
    //     return view('payment.index', compact('payment'));
    // }

    public function index(Request $request)
    {
        if(auth()->user()->cannot('viewAny', Payment::class)) abort('403', 'access denied');

        $payment = Payment::all();
        $index = 0;
        foreach ($payment as $pay) {
            $total_bayar = PaymentToFaktur::where('payment_id', $pay->id)->sum('jumlah_bayar');
            $payment[$index]->total_bayar = $total_bayar;
            $index++;
        }

        if($request->ajax()){
            return datatables()->of($payment)
            
            ->addIndexColumn()
            ->addColumn('actions', function($row){
                return '<td>
                <a class="badge badge-light-secondary" href="' . url('admin/pembayaranpembelian/' . $row->id) .'"><i data-feather="eye"></i> Lihat</a>            
                </td>';
            })
            // ->filterColumn('tanggal', function ($query, $keyword) {
            //     $query->whereRaw("DATE_FORMAT(tanggal,'%m/%d/%Y') LIKE ?", ["%$keyword%"]);
            // })
            ->editColumn('tanggal', function ($row) {
                return $row->tanggal ? with(new Carbon($row->tanggal))->format('d-m-Y') : '';;
            })
            ->editColumn('total_bayar', function($row){
                return "Rp. " . number_format($row->total_bayar,2);
            })
            ->editColumn('status', function($row){
                if($row->status == 0){
                    $status = '<span style="width: 100px" class="badge badge-light-warning">Sebagian</span>';
                }else{
                    $status = '<span style="width: 100px" class="badge badge-light-success">Penuh</span>';
                }
                
                return $status;
            })
            ->rawColumns(['actions','tanggal','total_bayar','status'])->make(true);
            // ->rawColumns(['actions'])
            // ->make(true);
        }

        return view('payment.index', compact('payment'));
    }

    public function exportPDF()
	{
        if(auth()->user()->cannot('view', Payment::class)) abort('403', 'access denied');

        $payment = Payment::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('payment.exportpdf',compact('payment'));
        return $pdf->download('Pembayaran.pdf');
	}

    public function printPDF()
	{
        if(auth()->user()->cannot('view', Payment::class)) abort('403', 'access denied');

        $payment = Payment::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('payment.exportpdf',compact('payment'));
        return $pdf->stream();
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        if(auth()->user()->cannot('create', Payment::class)) abort('403', 'access denied');

        $suppliers = Supplier::all();
        return view('payment.create', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function _store(Request $request)
    {
        if(auth()->user()->cannot('create', Payment::class)) abort(403, 'access denied');

        // Validasi
        $request->validate([
            'nomer_payment' => 'required|unique:payments,nomer_payment',
            'tanggal_payment' => 'required|date',
        ]);
        if (empty($request->id)) {
            return redirect()->back()->with('error', 'Faktur Harus Ada Minimal 1!');
        } else {
            $jumlah_tagihan = 0;
            foreach (Request('total') as $tagihan) {
                $jumlah_tagihan += $tagihan;
            }
            $jumlah_bayar = 0;
            foreach (Request('bayar') as $bayar) {
                $jumlah_bayar += $bayar;
            }
            if ($jumlah_bayar <= 0) {
                return redirect()->back()->with('error', 'Faktur Harus Ada Minimal 1!');
            }
            $payment = new Payment;
            $payment->supplier_id = $request->supplier_id;
            $payment->nomer_payment = $request->nomer_payment;
            $payment->tanggal = $request->tanggal_payment;
            $payment->jumlah_tagihan = $jumlah_tagihan;
            $payment->keterangan = $request->keterangan;
            if ($jumlah_bayar >= $jumlah_tagihan) {
                $payment->status = 1;
            }
            $payment->save();
            foreach (Request('id') as $id) {
                if (Request('bayar')[$id] != 0) {
                    $payment_to_faktur = new PaymentToFaktur;
                    $payment_to_faktur->payment_id = $payment->id;
                    $payment_to_faktur->faktur_id = $id;
                    $payment_to_faktur->jumlah_bayar = Request('bayar')[$id];
                    if (Request('total')[$id] <= Request('bayar')[$id]) {
                        $payment_to_faktur->status = 1;
                        fakturpembelian::where('id', $id)->update(['is_payment' => 1]);
                    }
                    $payment_to_faktur->save();
                }
            }
            // berkas
            $berkas1 = '';
            $berkas2 = '';
            $berkas3 = '';
            $berkas4 = '';
            $berkas5 = '';

            if ($request->hasFile('berkas_1')) {
                $file = $request->file('berkas_1');
                $originalName1 = $file->getClientOriginalName();
                $berkas1 = $originalName1;
                $file->move('uploads/pembayaran', $berkas1);
            }
            if ($request->hasFile('berkas_2')) {
                $file = $request->file('berkas_2');
                $originalName2 = $file->getClientOriginalName();
                $berkas2 = $originalName2;
                $file->move('uploads/pembayaran', $berkas2);
            }
            if ($request->hasFile('berkas_3')) {
                $file = $request->file('berkas_3');
                $originalName3 = $file->getClientOriginalName();
                $berkas3 = $originalName3;
                $file->move('uploads/pembayaran', $berkas3);
            }
            if ($request->hasFile('berkas_4')) {
                $file = $request->file('berkas_4');
                $originalName4 = $file->getClientOriginalName();
                $berkas4 = $originalName4;
                $file->move('uploads/pembayaran', $berkas4);
            }
            if ($request->hasFile('berkas_5')) {
                $file = $request->file('berkas_5');
                $originalName5 = $file->getClientOriginalName();
                $berkas5 = $originalName5;
                $file->move('uploads/pembayaran', $berkas5);
            }

            $berkasPembayaran = new BerkasPembayaran();
            $berkasPembayaran->payment_id = $payment->id;
            $berkasPembayaran->berkas_1 = $berkas1;
            $berkasPembayaran->berkas_2 = $berkas2;
            $berkasPembayaran->berkas_3 = $berkas3;
            $berkasPembayaran->berkas_4 = $berkas4;
            $berkasPembayaran->berkas_5 = $berkas5;
            $berkasPembayaran->save();
            return redirect('admin/pembayaranpembelian')->with('success', 'Berhasil Melakukan Pembayaran!');
        }
    }

    public function store(Request $request)
    {

        // Validasi
        if(auth()->user()->cannot('create', Payment::class)) abort('403', 'access denied');
        
        $request->validate([
            'nomer_payment' => 'required|unique:payments,nomer_payment',
            'tanggal_payment' => 'required|date',
        ]);
        if (empty($request->id)) {
            return redirect()->back()->with('error', 'Faktur Harus Ada Minimal 1!');
        } else {
            $jumlah_tagihan = 0;
            foreach (Request('total') as $tagihan) {
                $jumlah_tagihan += $tagihan;
            }
            $jumlah_bayar = 0;
            foreach (Request('bayar') as $bayar) {
                $jumlah_bayar += explodeRupiah($bayar);
            }
            if ($jumlah_bayar <= 0) {
                return redirect()->back()->with('error', 'Faktur Harus Ada Minimal 1!');
            }
            $payment = new Payment;
            $payment->supplier_id = $request->supplier_id;
            $payment->nomer_payment = $request->nomer_payment;
            $payment->tanggal = $request->tanggal_payment;
            $payment->jumlah_tagihan = $jumlah_tagihan;
            $payment->keterangan = $request->keterangan;
            if ($jumlah_bayar >= $jumlah_tagihan) {
                $payment->status = 1;
            }
            $payment->save();
            foreach (Request('id') as $id) {
                if (explodeRupiah(Request('bayar')[$id]) != 0) {
                    $payment_to_faktur = new PaymentToFaktur;
                    $payment_to_faktur->payment_id = $payment->id;
                    $payment_to_faktur->faktur_id = $id;
                    $payment_to_faktur->jumlah_bayar = explodeRupiah(Request('bayar')[$id]);
                    if (Request('total')[$id] <= explodeRupiah(Request('bayar')[$id])) {
                        $payment_to_faktur->status = 1;
                        fakturpembelian::where('id', $id)->update(['is_payment' => 1]);
                    }
                    $payment_to_faktur->save();
                }
            }

            if (!empty($request->berkas)) {
                // buat data berkas
                $totalBerkas = count($request->berkas);
                $collectNamaBerkas = [];

                for ($i = 0; $i < $totalBerkas; $i++) {
                    if ($request->hasFile('berkas.' . $i)) {
                        $file = $request->file('berkas')[$i];
                        $originalName = $file->getClientOriginalName();
                        $berkas = str_replace(" ", "", $originalName);
                        $file->move('uploads/pembayaran', $berkas);

                        array_push($collectNamaBerkas, $berkas);
                    }
                }

                $saveBerkas = count($collectNamaBerkas);

                $berkasPembayaran = new BerkasPembayaran();
                $berkasPembayaran->payment_id = $payment->id;

                for ($i = 0; $i < $saveBerkas; $i++) {
                    $flag = $i + 1;
                    $var = "berkas_" . $flag;
                    $berkasPembayaran->$var = $collectNamaBerkas[$i];
                }
                $berkasPembayaran->save();
            }

            return redirect('admin/pembayaranpembelian')->with('success', 'Berhasil Melakukan Pembayaran!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(auth()->user()->cannot('view', Payment::class)) abort('403', 'access denied');

        $payment = Payment::with('supplier')->with('paymentfaktur.faktur.rinci.barang')->find($id);
        $totalPayment = PaymentToFaktur::where('payment_id', $id)->sum('jumlah_bayar');
        // return response()->json($totalPayment);
        $data = [
            'payment' => $payment,
            'total_payment' => $totalPayment
        ];
        return view('payment.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(auth()->user()->cannot('delete', Payment::class)) abort(403, 'access denied');
    }

    public function get_faktur_by_supplier($id)
    {
        // if(auth()->user()->cannot('read', Payment::class)) abort(403, 'access denied');

        $data = fakturpembelian::where('supplier_id', $id)->where('is_payment', 0)->where(['approve_direktur' => 1, 'approve_komisaris' => 1])->with('relation')->get();
        $no = 0;
        foreach ($data as $val) {
            $index = $no++;
            // Cek Apakah Faktur Pernah Dibayar Sebelumnya
            $payment_to_faktur = PaymentToFaktur::where('faktur_id', $val->id)->get();
            $bayar_sebelumnya = 0;
            if (!empty($payment_to_faktur)) {
                foreach ($payment_to_faktur as $ptf) {
                    $bayar_sebelumnya += $ptf->jumlah_bayar;
                }
            }
            // Hitung Jumlah Pembayaran Sebelumnya
            $data[$index]['bayar_sebelumnya'] = $bayar_sebelumnya;

            // Hitung Nilai Faktur
            $total = FakturToRelation::where('faktur_id', $val->id)->sum('total_perfaktur');
            $total_kekurangan = $total - $bayar_sebelumnya;

            $data[$index]['total'] = round($total, 2);
            $data[$index]['total_kekurangan'] = round($total_kekurangan, 2);
        }
        // dd($data);
        return response()->json($data);
    }
}
