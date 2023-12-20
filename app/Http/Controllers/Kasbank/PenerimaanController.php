<?php

namespace App\Http\Controllers\Kasbank;

use App\Exports\ExportAllBookBank;
use App\Exports\ExportBankBook;
use App\Http\Controllers\Controller;
use App\Models\BukuBank;
use App\Models\BukuBankRinci;
use App\Models\Coa;
use App\Models\Gl;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use PDF;
use DataTables;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

use function PHPUnit\Framework\isNull;

class PenerimaanController extends Controller
{
    // public function index()
    // {
    //     if(auth()->user()->cannot('viewAny', BukuBank::class)) abort(403, 'access denied');

    //     $bukuBank = BukuBank::whereIsDeleted(1)
    //                 ->whereSumber('DPT')->latest()->get(); // DPT == Deposit
       
    //     //Data manipulation tambahkan nominal ke jurnal voucher
    //     $bukuBank = $bukuBank->map(function ($val) {
    //         $val['total_nominal'] = BukuBankRinci::whereBukuBankId($val->id)
    //                                 ->whereTipe('D')->sum('nominal');

    //         return $val;
    //     });

    //     return view('kasbank.penerimaan.index', compact('bukuBank'));
    // }

    public function index(Request $request)
    {
        if(auth()->user()->cannot('viewAny', BukuBank::class)) abort(403, 'access denied');

        $bukuBank = BukuBank::whereIsDeleted(1)
                    ->whereSumber('DPT')->latest()->get(); // DPT == Deposit
       
        //Data manipulation tambahkan nominal ke jurnal voucher
        $bukuBank = $bukuBank->map(function ($val) {
            $val['total_nominal'] = BukuBankRinci::whereBukuBankId($val->id)
                                    ->whereTipe('D')->sum('nominal');

            return $val;
        });

        if($request->ajax()){
            return datatables()->of($bukuBank)
            
            ->addIndexColumn()
            ->addColumn('actions', function($row){
                return '<td>
                <a class="badge badge-light-secondary" href="' . route('admin.penerimaan.show', $row->id) .'"><i data-feather="eye"></i> Lihat</a>           
                </td>';
            })
            // ->filterColumn('tanggal', function ($query, $keyword) {
            //     $query->whereRaw("DATE_FORMAT(tanggal,'%m/%d/%Y') LIKE ?", ["%$keyword%"]);
            // })
            ->editColumn('tanggal', function ($row) {
                return $row->tanggal ? with(new Carbon($row->tanggal))->format('d F Y') : '';;
            })
            ->editColumn('total_nominal', function($row){
                return "Rp. " . number_format($row->total_nominal,0,',','.');
            })
            ->editColumn('deskripsi', function($row){
                $deskripsi = !empty($row->deskripsi) ? $row->deskripsi : '-';
            return $deskripsi;
            })
            ->rawColumns(['actions','tanggal','total_nominal','deskripsi'])->make(true);
            // ->rawColumns(['actions'])
            // ->make(true);
        }

        return view('kasbank.penerimaan.index', compact('bukuBank'));
    }

    public function exportPDF()
	{
        $bukuBank = BukuBank::whereIsDeleted(1)
                    ->whereSumber('DPT')->latest()->get(); // DPT == Deposit
       
        //Data manipulation tambahkan nominal ke jurnal voucher
        $bukuBank = $bukuBank->map(function ($val) {
            $val['total_nominal'] = BukuBankRinci::whereBukuBankId($val->id)
                                    ->whereTipe('D')->sum('nominal');

            return $val;
        });
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('kasbank.penerimaan.exportpdf',compact('bukuBank'));
        return $pdf->setWarnings(false)->download('Penerimaan.pdf');
	}

    public function printPDF()
	{
        $bukuBank = BukuBank::whereIsDeleted(1)
                    ->whereSumber('DPT')->latest()->get(); // DPT == Deposit
       
        //Data manipulation tambahkan nominal ke jurnal voucher
        $bukuBank = $bukuBank->map(function ($val) {
            $val['total_nominal'] = BukuBankRinci::whereBukuBankId($val->id)
                                    ->whereTipe('D')->sum('nominal');

            return $val;
        });
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('kasbank.penerimaan.exportpdf',compact('bukuBank'));
        return $pdf->setWarnings(false)->stream();
	}

    public function create()
    {
        if(auth()->user()->cannot('create', BukuBank::class)) abort(403, 'access denied');

        $coaDebit = Coa::with('tipeCoa')
                ->whereIsActive(1)
                ->whereIn('id_coatype', [1]) // Tambahkan untuk ditampilkan di pilihan akun bank
                ->latest()->get();

        $coaKredit = Coa::with('tipeCoa')
                ->whereIsActive(1)
                ->whereNotIn('id_coatype', [1])  // Tambahkan untuk tidak ditampilkan di pilihan akun rincian
                ->latest()->get();
                
        return view('kasbank.penerimaan.create', compact('coaDebit', 'coaKredit'));
    }

    public function store(Request $request)
    {
        if(auth()->user()->cannot('create', BukuBank::class)) abort(403, 'access denied');

        // return response()->json($request->all());
        //Validasi Rule

        //validasi nomer sama
        $bukuBank = BukuBank::whereIsDeleted(1)
                        ->whereSumber('DPT')->whereNomer($request->nomer)->first();

        if(!empty($bukuBank)){
            return response()->json(['errors' => "Error! Nomer sudah digunakan, Masukan nomer lainnya.", 'nomer' => true]);
        }


        $id = Uuid::uuid4()->toString();
        BukuBank::create([
            'id' => $id, // generate uuid
            'nomer' => $request->nomer,
            'tanggal' => $request->tanggal,
            'sumber' => "DPT",
            'deskripsi' => $request->deskripsi,
            'is_deleted' => 1
        ]);


        //Get Total dari rincian
        $rincian = count($request->kredit_coa_id);
        $totalRinci = 0;
        for ($i=0; $i < $rincian; $i++) { 
            $totalRinci += explodeRupiah($request->nominal[$i]);
        }

        //insert buku bank rinci
        BukuBankRinci::create([
            'id' => Uuid::uuid4()->toString(),
            'buku_bank_id' => $id,
            'coa_id' => $request->debit_coa_id,
            'nominal' => $totalRinci,
            'memo' => $request->deskripsi,
            'tipe' => "D"
        ]);

       //insert buku besar
       storeBukuBesar($request->debit_coa_id, $request->tanggal, $request->nomer, $request->deskripsi, "DPT", $totalRinci, 0);
       $coa_details = Coa::find($request->debit_coa_id);
       $storeGL = [
           'tahun' => date('Y',strtotime($request->tanggal)),
           'tanggal' => $request->tanggal,
           'nomer' => $request->nomer,
           'sumber' => 'cb_dp',
           'coa_no' => $coa_details->nomer_coa,
           'coa' => $coa_details->nama_coa,
           'pelanggan' => null,
           'pemasok' => null,
           'debit' => $totalRinci,
           'kredit' => 0,
           'keterangan' => $request->deskripsi,
       ];
       storeGeneralLedger($storeGL);

        for ($i=0; $i < $rincian; $i++) { 
            BukuBankRinci::create([
                'id' => Uuid::uuid4()->toString(), // generate uuid
                'buku_bank_id' => $id,
                'coa_id' => $request->kredit_coa_id[$i],
                'nominal' => explodeRupiah($request->nominal[$i]),
                'memo' => $request->memo[$i],
                'tipe' => "K" // KREDIT
            ]);
            $coa_details = Coa::find($request->kredit_coa_id[$i]);
            $storeGL = [
                'tahun' => date('Y',strtotime($request->tanggal)),
                'tanggal' => $request->tanggal,
                'nomer' => $request->nomer,
                'sumber' => 'cb_dp',
                'coa_no' => $coa_details->nomer_coa,
                'coa' => $coa_details->nama_coa,
                'pelanggan' => null,
                'pemasok' => null,
                'debit' => 0,
                'kredit' => explodeRupiah($request->nominal[$i]),
                'keterangan' => $request->memo[$i],
            ];
            storeGeneralLedger($storeGL);

            //insert buku besar
            storeBukuBesar($request->kredit_coa_id[$i], $request->tanggal, $request->nomer, $request->memo[$i], "DPT", 0, explodeRupiah($request->nominal[$i]));

        }

        return response()->json("OKE");
    }

    public function show($id)
    {
        if(auth()->user()->cannot('view', BukuBank::class)) abort(403, 'access denied');

        //tidak bisa makai model binding karena nama route adalah penerimaan
        $bukuBank = BukuBank::findOrFail($id);

        $debit = BukuBankRinci::with('coa')
                ->whereBukuBankId($bukuBank->id)
                ->whereTipe('D')->first();

        $bukuBankRinci = BukuBankRinci::with('coa')
                        ->whereBukuBankId($bukuBank->id)
                        ->whereTipe('K')->get();

        // dd($bukuBankRinci);
        return view('kasbank.penerimaan.detail', compact('bukuBank', 'debit', 'bukuBankRinci'));
    }

    public function showBukuBank($nomer)
    {
        if(auth()->user()->cannot('view', BukuBank::class)) abort(403, 'access denied');

        //tidak bisa makai model binding karena nama route adalah penerimaan
        $bukuBank = BukuBank::whereNomer($nomer)->whereSumber('DPT')->first();

        $debit = BukuBankRinci::with('coa')
                ->whereBukuBankId($bukuBank->id)
                ->whereTipe('D')->first();

        $bukuBankRinci = BukuBankRinci::with('coa')
                        ->whereBukuBankId($bukuBank->id)
                        ->whereTipe('K')->get();


        return view('kasbank.penerimaan.detailbukubank', compact('bukuBank', 'debit', 'bukuBankRinci'));
    }

    public function edit($id)
    {
        if(auth()->user()->cannot('update', BukuBank::class)) abort(403, 'access denied');

        //tidak bisa makai model binding karena nama route adalah penerimaan
        $bukuBank = BukuBank::findOrFail($id);

        $coaDebit = Coa::with('tipeCoa')
                ->whereIsActive(1)
                ->whereIn('id_coatype', [1]) // Tambahkan untuk ditampilkan di pilihan akun bank
                ->latest()->get();

        $coaKredit = Coa::with('tipeCoa')
                ->whereIsActive(1)
                ->whereNotIn('id_coatype', [1])  // Tambahkan untuk tidak ditampilkan di pilihan akun rincian
                ->latest()->get();

        $debit = BukuBankRinci::with('coa')
                ->whereBukuBankId($bukuBank->id)
                ->whereTipe('D')->first();

        $bukuBankRinci = BukuBankRinci::with('coa')
                        ->whereBukuBankId($bukuBank->id)
                        ->whereTipe('K')->get();

        return view('kasbank.penerimaan.edit', compact('bukuBank', 'coaDebit', 'coaKredit', 'debit', 'bukuBankRinci'));
    }

    public function update(Request $request, $id)
    {
        if(auth()->user()->cannot('update', BukuBank::class)) abort(403, 'access denied');

         //tidak bisa makai model binding karena nama route adalah penerimaan
         $bukuBank = BukuBank::findOrFail($id);
        //Validasi Rule

        //validasi nomer sama
        $bukuBankTemp = BukuBank::whereIsDeleted(1)
                            ->whereSumber('DPT')->whereNomer($request->nomer)->first();
        
        if(!empty($bukuBankTemp) && $bukuBank->nomer != $bukuBankTemp->nomer){
            return response()->json(['errors' => "Error! Nomer sudah digunakan, Masukan nomer lainnya.", 'nomer' => true]);
        }

        //Get Total dari rincian
        $rincian = count($request->kredit_coa_id);

        //validasi rincian
        if($rincian < 2 && empty($request->kredit_coa_id[0]) && empty($request->nominal[0])){
            return response()->json(['errors' => "Error! Data Rincian tidak boleh kosong.", 'rincian' => true]);
        }

        // return response()->json(empty($request->kredit_coa_id[0]). '-'.);

        $totalRinci = 0;
        for ($i=0; $i < $rincian; $i++) { 
            $totalRinci += substr($request->nominal[$i], 0, 1) == "R" ? explodeRupiah($request->nominal[$i]) : $request->nominal[$i];
        }


        //Delete buku besar
        destroyBukuBesar("DPT", $bukuBank->nomer);
        destroyGeneralLedger($request->nomer, 'cb_dp');
        
        $bukuBank->update([
            'nomer' => $request->nomer,
            'tanggal' => $request->tanggal,
            'sumber' => "DPT",
            'deskripsi' => $request->deskripsi
        ]);

        //update buku bank rinci
        BukuBankRinci::whereBukuBankId($bukuBank->id)->whereTipe('D')
        ->update([
            'coa_id' => $request->debit_coa_id,
            'nominal' => $totalRinci,
            'memo' => $request->deskripsi,
        ]);

       //insert buku besar
       storeBukuBesar($request->debit_coa_id, $request->tanggal, $request->nomer, $request->deskripsi, "DPT", $totalRinci, 0);
       $coa_details = Coa::find($request->debit_coa_id);
       $storeGL = [
           'tahun' => date('Y',strtotime($request->tanggal)),
           'tanggal' => $request->tanggal,
           'nomer' => $request->nomer,
           'sumber' => 'cb_dp',
           'coa_no' => $coa_details->nomer_coa,
           'coa' => $coa_details->nama_coa,
           'pelanggan' => null,
           'pemasok' => null,
           'debit' => $totalRinci,
           'kredit' => 0,
           'keterangan' => $request->deskripsi,
       ];
       storeGeneralLedger($storeGL);

        for ($i=0; $i < $rincian; $i++) { 
            if(!is_null($request->kredit_coa_id[$i])){
                if(!is_null($request->buku_bank_rinci_id[$i])){
                    bukuBankRinci::whereId($request->buku_bank_rinci_id[$i])
                    ->update([
                        'coa_id' => $request->kredit_coa_id[$i],
                        'nominal' => explodeRupiah($request->nominal[$i]),
                        'memo' => $request->memo[$i],
                        'tipe' => "K" // KREDIT
                    ]);

                    $coa_details = Coa::find($request->kredit_coa_id[$i]);

                    $storeGL = [
                        'tahun' => date('Y',strtotime($request->tanggal)),
                        'tanggal' => $request->tanggal,
                        'nomer' => $request->nomer,
                        'sumber' => 'cb_dp',
                        'coa_no' => $coa_details->nomer_coa,
                        'coa' => $coa_details->nama_coa,
                        'pelanggan' => null,
                        'pemasok' => null,
                        'debit' => 0,
                        'kredit' => explodeRupiah($request->nominal[$i]),
                        'keterangan' => $request->memo[$i],
                    ];
                    storeGeneralLedger($storeGL);
    
                    //insert buku besar
                    storeBukuBesar($request->kredit_coa_id[$i], $request->tanggal, $request->nomer, $request->memo[$i], "DPT", 0, explodeRupiah($request->nominal[$i]));
                }else{
                    BukuBankRinci::create([
                        'id' => Uuid::uuid4()->toString(), // generate uuid
                        'buku_bank_id' => $id,
                        'coa_id' => $request->kredit_coa_id[$i],
                        'nominal' => explodeRupiah($request->nominal[$i]),
                        'memo' => $request->memo[$i],
                        'tipe' => "K" // KREDIT
                    ]);
                    $coa_details = Coa::find($request->kredit_coa_id[$i]);
                    $storeGL = [
                        'tahun' => date('Y',strtotime($request->tanggal)),
                        'tanggal' => $request->tanggal,
                        'nomer' => $request->nomer,
                        'sumber' => 'cb_dp',
                        'coa_no' => $coa_details->nomer_coa,
                        'coa' => $coa_details->nama_coa,
                        'pelanggan' => null,
                        'pemasok' => null,
                        'debit' => 0,
                        'kredit' => explodeRupiah($request->nominal[$i]),
                        'keterangan' => $request->memo[$i],
                    ];
                    storeGeneralLedger($storeGL);
        
                    //insert buku besar
                    storeBukuBesar($request->kredit_coa_id[$i], $request->tanggal, $request->nomer, $request->memo[$i], "DPT", 0, explodeRupiah($request->nominal[$i]));
                }
            }
           
        }
        
        return response()->json("OKE");
    }

    public function destroy($id)
    {
        if(auth()->user()->cannot('delete', BukuBank::class)) abort(403, 'access denied');

        //tidak bisa makai model binding karena nama route adalah penerimaan
        $bukuBank = BukuBank::findOrFail($id);

        //Delete buku besar
        // softDestroyBukuBesar("DPT", $bukuBank->nomer);
        
        destroyGeneralLedger($bukuBank->nomer, 'cb_dp');

        //Delete buku bank
        softDestroyBukuBank("DPT", $bukuBank->nomer);
        
        //Delete buku bank
        $bukuBank->update(['is_deleted' => 0]);

        return redirect()->route('admin.penerimaan.index')->with('success', 'Deposit (Penerimaan) berhasil dihapus');
    }

    public function exportExcelByNomer($nomer){

        $data = BukuBank::find($nomer);

        $filename = str_replace('/', '-', $data->nomer) . '.xlsx';
        return Excel::download(new ExportBankBook($data->nomer), $filename);
    }

    public function exportExcelAll(Request $request){
        $filename = 'CASHBANK-DEPOSIT-'. str_replace('-', '', $request->start) . '-' . str_replace('-', '', $request->end) . '.xlsx';
        return Excel::download(new ExportAllBookBank('cb_dp', date($request->start), date($request->end)), $filename);
    }

    public function exportPDFByNomer($nomer){
        $bookBank = BukuBank::find($nomer);
        $data = [
            'title' => 'Penerimaan - ' . $bookBank->nomer,
            'gl' => Gl::where('nomer', $bookBank->nomer)->get()
        ];
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('kasbank.pdf',$data);
        $filename = str_replace('/', '-', $bookBank->nomer) . '.pdf';
        return $pdf->setWarnings(false)->download($filename);
    }

    public function destroyBukuBankRinci(Request $request)
    {
        if(auth()->user()->cannot('delete', BukuBank::class)) abort(403, 'access denied');

        BukuBankRinci::whereId($request->id)->delete();

        return response()->json("OKE");

    }
    public function printBukuBank($id){
        if(auth()->user()->cannot('view', BukuBank::class)) abort(403, 'access denied');

        //tidak bisa makai model binding karena nama route adalah penerimaan
        $bukuBank = BukuBank::findOrFail($id);

        $bukuBankRinci = BukuBankRinci::with('coa')
                        ->whereBukuBankId($bukuBank->id)
                        ->whereTipe('K')->get();

        // dd($bukuBankRinci);

        return view('kasbank.pembayaran.print', compact('bukuBank', 'bukuBankRinci'));
    }

}
