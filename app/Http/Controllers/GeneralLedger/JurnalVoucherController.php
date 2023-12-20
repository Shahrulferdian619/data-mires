<?php

namespace App\Http\Controllers\GeneralLedger;

use App\Http\Controllers\Controller;
use App\Models\Coa;
use App\Models\JurnalVoucher;
use App\Models\JurnalVoucherRinci;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use PDF;
use DataTables;
use Carbon\Carbon;

class JurnalVoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     if(auth()->user()->cannot('viewAny', JurnalVoucher::class)) abort(403, 'Access Denied.');

    //     $jurnalVoucher = JurnalVoucher::whereIsDeleted(1)->latest()->get();
    
    //     //Data manipulation tambahkan nominal ke jurnal voucher
    //     $jurnalVoucher = $jurnalVoucher->map(function ($val) {
    //         $val['total_nominal'] = JurnalVoucherRinci::whereJurnalVoucherId($val->id)
    //                                 ->whereTipe('D')->sum('nominal');

    //         return $val;
    //     });

    //     return view('generalledger.jurnalvoucher.index', compact('jurnalVoucher'));
    // }

    public function index(Request $request)
    {
        if(auth()->user()->cannot('viewAny', JurnalVoucher::class)) abort(403, 'Access Denied.');

        $jurnalVoucher = JurnalVoucher::whereIsDeleted(1)->latest()->get();
    
        //Data manipulation tambahkan nominal ke jurnal voucher
        $jurnalVoucher = $jurnalVoucher->map(function ($val) {
            $val['total_nominal'] = JurnalVoucherRinci::whereJurnalVoucherId($val->id)
                                    ->whereTipe('D')->sum('nominal');

            return $val;
        });

        if($request->ajax()){
            return datatables()->of($jurnalVoucher)
            
            ->addIndexColumn()
            ->addColumn('actions', function($row){
                return '<td>
                <a class="badge badge-light-secondary" href="' . route('admin.jurnal-voucher.show', $row->id) .'"><i data-feather="eye"></i> Lihat</a>           
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

        return view('generalledger.jurnalvoucher.index', compact('jurnalVoucher'));
    }

    public function exportPDF()
	{
        $jurnalVoucher = JurnalVoucher::whereIsDeleted(1)->latest()->get();
    
        //Data manipulation tambahkan nominal ke jurnal voucher
        $jurnalVoucher = $jurnalVoucher->map(function ($val) {
            $val['total_nominal'] = JurnalVoucherRinci::whereJurnalVoucherId($val->id)
                                    ->whereTipe('D')->sum('nominal');

            return $val;
        });
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('generalledger.jurnalvoucher.exportpdf',compact('jurnalVoucher'));
        return $pdf->setWarnings(false)->download('Jurnal Umum.pdf');
	}

    public function printPDF()
	{
        $jurnalVoucher = JurnalVoucher::whereIsDeleted(1)->latest()->get();
    
        //Data manipulation tambahkan nominal ke jurnal voucher
        $jurnalVoucher = $jurnalVoucher->map(function ($val) {
            $val['total_nominal'] = JurnalVoucherRinci::whereJurnalVoucherId($val->id)
                                    ->whereTipe('D')->sum('nominal');

            return $val;
        });
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('generalledger.jurnalvoucher.exportpdf',compact('jurnalVoucher'));
        return $pdf->setWarnings(false)->stream();
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->cannot('create', JurnalVoucher::class)) abort(403, 'Access Denied.');

        $coa = Coa::with('tipeCoa')
                ->whereIsActive(1)
                ->latest()->get();
                
        return view('generalledger.jurnalvoucher.create', compact('coa'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->cannot('create', JurnalVoucher::class)) abort(403, 'Access Denied.');

        // return response()->json($request->all());
        //Validasi Rule

        //validasi nomer sama
        $jurnalVoucher = JurnalVoucher::whereIsDeleted(1)->whereNomer($request->nomer)->first();

        if(!empty($jurnalVoucher)){
            return response()->json(['errors' => "Error! Nomer sudah digunakan, Masukan nomer lainnya.", 'nomer' => true]);
        }

        // Jika debit dan kredit tidak balance
        $rincian = count($request->coa_id);
        $totalDebit = 0;
        $totalKredit = 0;
        for ($i=0; $i < $rincian; $i++) { 
            if(!is_null($request->debit[$i])){ //cek debit jika null tidak diproses
                $totalDebit += explodeRupiah($request->debit[$i]);
            }

            if(!is_null($request->kredit[$i])){ //cek kredit jika null tidak diproses
                $totalKredit += explodeRupiah($request->kredit[$i]);
            }
           
        }


        if ($totalDebit != $totalKredit) {
            return response()->json(['errors' => "Error! Debit dan Kredit tidak balance.", 'tidak_balance' => true]);
        }

        for ($i=0; $i < $rincian; $i++) { 
            if(!is_null($request->debit[$i]) && !is_null($request->kredit[$i])){ //cek debit dan kredit jika di isi secara bersamaan
                return response()->json(['errors' => "Error! Debit dan Kredit tidak boleh diisi sebaris.", 'sebaris' => true]);
            }
           
        }

        $id = Uuid::uuid4()->toString();
        JurnalVoucher::create([
            'id' => $id, // generate uuid
            'nomer' => $request->nomer,
            'tanggal' => $request->tanggal,
            'sumber' => "JV",
            'deskripsi' => $request->deskripsi,
            'is_deleted' => 1
        ]);

        //insert buku bank
        $bukuBankId = Uuid::uuid4()->toString();
        storeBukuBank($bukuBankId, $request->coa_id, $request->nomer, $request->tanggal, "JV",  $request->deskripsi);

        for ($i=0; $i < $rincian; $i++) { 
            if(!is_null($request->debit[$i])){ //cek debit jika null tidak diproses
                JurnalVoucherRinci::create([
                    'id' => Uuid::uuid4()->toString(), // generate uuid
                    'jurnal_voucher_id' => $id,
                    'coa_id' => $request->coa_id[$i],
                    'nominal' => explodeRupiah($request->debit[$i]),
                    'memo' => $request->memo[$i],
                    'tipe' => "D" // DEBIT
                ]);

                //insert buku besar
                // storeBukuBesar($request->coa_id[$i], $request->tanggal, $request->nomer, $request->memo[$i], "JV", explodeRupiah($request->debit[$i]), 0);

                // store gl
                $data = [
                    'tahun' => date('Y', strtotime($request->tanggal)),
                    'tanggal' => $request->tanggal,
                    'nomer' => $request->nomer,
                    'sumber' => 'gl_jv',
                    'coa_no' => COA::find($request->coa_id[$i])->nomer_coa,
                    'coa' => COA::find($request->coa_id[$i])->nama_coa,
                    'pelanggan' => null,
                    'pemasok' => null,
                    'debit' => explodeRupiah($request->debit[$i]),
                    'kredit' => 0,
                    'keterangan' => $request->memo[$i]
                ];
                storeGeneralLedger($data);

                //insert buku bank rinci
                storeBukuBankRinci($bukuBankId, $request->coa_id[$i], explodeRupiah($request->debit[$i]), $request->memo[$i], 'D');
            }

            if(!is_null($request->kredit[$i])){ //cek kredit jika null tidak diproses
                JurnalVoucherRinci::create([
                    'id' => Uuid::uuid4()->toString(), // generate uuid
                    'jurnal_voucher_id' => $id,
                    'coa_id' => $request->coa_id[$i],
                    'nominal' => explodeRupiah($request->kredit[$i]),
                    'memo' => $request->memo[$i],
                    'tipe' => "K" // KREDIT
                ]);

                //insert buku besar
                //storeBukuBesar($request->coa_id[$i], $request->tanggal, $request->nomer, $request->memo[$i], "JV", 0, explodeRupiah($request->kredit[$i]));
                $data = [
                    'tahun' => date('Y', strtotime($request->tanggal)),
                    'tanggal' => $request->tanggal,
                    'nomer' => $request->nomer,
                    'sumber' => 'gl_jv',
                    'coa_no' => COA::find($request->coa_id[$i])->nomer_coa,
                    'coa' => COA::find($request->coa_id[$i])->nama_coa,
                    'pelanggan' => null,
                    'pemasok' => null,
                    'debit' => 0,
                    'kredit' => explodeRupiah($request->kredit[$i]),
                    'keterangan' => $request->memo[$i]
                ];
                storeGeneralLedger($data);

                 //insert buku bank rinci
                 storeBukuBankRinci($bukuBankId, $request->coa_id[$i], explodeRupiah($request->kredit[$i]), $request->memo[$i], 'K');
            }
        }

        


        return response()->json("OKE");
    }

    /**
     * Display the specified resource.
     *
      * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(JurnalVoucher $jurnalVoucher)
    {
        if(auth()->user()->cannot('view', JurnalVoucher::class)) abort(403, 'Access Denied.');

        $jurnalVoucherRinci = JurnalVoucherRinci::with('coa')
                                ->whereJurnalVoucherId($jurnalVoucher->id)->get();

        return view('generalledger.jurnalvoucher.detail', compact('jurnalVoucher', 'jurnalVoucherRinci'));
    }

    public function showBukuBank($nomer)
    {
        if(auth()->user()->cannot('view', JurnalVoucher::class)) abort(403, 'Access Denied.');

        $jurnalVoucher = JurnalVoucher::whereNomer($nomer)->first();
        $jurnalVoucherRinci = JurnalVoucherRinci::with('coa')
                                ->whereJurnalVoucherId($jurnalVoucher->id)->get();

        return view('generalledger.jurnalvoucher.detailbukubank', compact('jurnalVoucher', 'jurnalVoucherRinci'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(JurnalVoucher $jurnalVoucher)
    {
        if(auth()->user()->cannot('update', JurnalVoucher::class)) abort(403, 'Access Denied.');

        $coa = Coa::with('tipeCoa')
                ->whereIsActive(1)
                ->latest()->get();

        $jurnalVoucherRinci = JurnalVoucherRinci::with('coa')
                            ->whereJurnalVoucherId($jurnalVoucher->id)->get();

        return view('generalledger.jurnalvoucher.edit', compact('jurnalVoucher', 'jurnalVoucherRinci', 'coa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JurnalVoucher $jurnalVoucher)
    {
        if(auth()->user()->cannot('update', JurnalVoucher::class)) abort(403, 'Access Denied.');

        //Validasi Rule

        //validasi nomer sama
        $jurnalVoucherTemp = JurnalVoucher::whereIsDeleted(1)->whereNomer($request->nomer)->first();
        
        if(!empty($jurnalVoucherTemp) && $jurnalVoucher->nomer != $jurnalVoucherTemp->nomer){
            return response()->json(['errors' => "Error! Nomer sudah digunakan, Masukan nomer lainnya.", 'nomer' => true]);
        }

        // Jika debit dan kredit tidak balance
        $rincian = count(array_filter($request->coa_id));
        $totalDebit = 0;
        $totalKredit = 0;
        for ($i=0; $i < $rincian; $i++) { 
            if(!is_null($request->debit[$i])){ //cek debit jika null tidak diproses
                $totalDebit += explodeRupiah($request->debit[$i]);
            }

            if(!is_null($request->kredit[$i])){ //cek kredit jika null tidak diproses
                $totalKredit += explodeRupiah($request->kredit[$i]);
            }
           
        }


        if ($totalDebit != $totalKredit) {
            return response()->json(['errors' => "Error! Debit dan Kredit tidak balance.", 'tidak_balance' => true]);
        }

        for ($i=0; $i < $rincian; $i++) { 
            if(!is_null($request->debit[$i]) && !is_null($request->kredit[$i])){ //cek debit dan kredit jika di isi secara bersamaan
                return response()->json(['errors' => "Error! Debit dan Kredit tidak boleh diisi sebaris.", 'sebaris' => true]);
            }
           
        }

        //Delete buku besar
        // destroyBukuBesar("JV", $jurnalVoucher->nomer);
        
        destroyGeneralLedger($jurnalVoucher->nomer, 'gl_jv');

        //Delete buku besar
        destroyBukuBank("JV", $jurnalVoucher->nomer);

        $jurnalVoucher->update([
            'nomer' => $request->nomer,
            'tanggal' => $request->tanggal,
            'sumber' => "JV",
            'deskripsi' => $request->deskripsi
        ]);

         //insert buku bank
        $bukuBankId = Uuid::uuid4()->toString();
        storeBukuBank($bukuBankId, $request->coa_id, $request->nomer, $request->tanggal, "JV",  $request->deskripsi);

        for ($i=0; $i < $rincian; $i++) { 
            if(!is_null($request->jurnal_voucher_rinci_id[$i])){
                if(!is_null($request->debit[$i])){ //cek debit jika null tidak diproses

                    JurnalVoucherRinci::whereId($request->jurnal_voucher_rinci_id[$i])
                                ->update([
                                    'coa_id' => $request->coa_id[$i],
                                    'nominal' => explodeRupiah($request->debit[$i]),
                                    'memo' => $request->memo[$i],
                                    'tipe' => "D" // DEBIT
                                ]);
                   
                    //insert buku besar
                    $data = [
                        'tahun' => date('Y', strtotime($request->tanggal)),
                        'tanggal' => $request->tanggal,
                        'nomer' => $request->nomer,
                        'sumber' => 'gl_jv',
                        'coa_no' => COA::find($request->coa_id[$i])->nomer_coa,
                        'coa' => COA::find($request->coa_id[$i])->nama_coa,
                        'pelanggan' => null,
                        'pemasok' => null,
                        'debit' => explodeRupiah($request->debit[$i]),
                        'kredit' => 0,
                        'keterangan' => $request->memo[$i]
                    ];
                    storeGeneralLedger($data);

                    //insert buku bank rinci
                    storeBukuBankRinci($bukuBankId, $request->coa_id[$i], explodeRupiah($request->debit[$i]), $request->memo[$i], 'D');
                }
    
                if(!is_null($request->kredit[$i])){ //cek kredit jika null tidak diproses
                    JurnalVoucherRinci::whereId($request->jurnal_voucher_rinci_id[$i])
                        ->update([
                            'coa_id' => $request->coa_id[$i],
                            'nominal' => explodeRupiah($request->kredit[$i]),
                            'memo' => $request->memo[$i],
                            'tipe' => "K" // KREDIT
                        ]);
    
                    //insert buku besar
                    // storeBukuBesar($request->coa_id[$i], $request->tanggal, $request->nomer, $request->memo[$i], "JV", 0, explodeRupiah($request->kredit[$i]));
                    $data = [
                        'tahun' => date('Y', strtotime($request->tanggal)),
                        'tanggal' => $request->tanggal,
                        'nomer' => $request->nomer,
                        'sumber' => 'gl_jv',
                        'coa_no' => COA::find($request->coa_id[$i])->nomer_coa,
                        'coa' => COA::find($request->coa_id[$i])->nama_coa,
                        'pelanggan' => null,
                        'pemasok' => null,
                        'debit' => 0,
                        'kredit' => explodeRupiah($request->kredit[$i]),
                        'keterangan' => $request->memo[$i]
                    ];
                    storeGeneralLedger($data);

                    //insert buku bank rinci
                    storeBukuBankRinci($bukuBankId, $request->coa_id[$i], explodeRupiah($request->kredit[$i]), $request->memo[$i], 'K');
                }
            }else{
                if(!is_null($request->debit[$i])){ //cek debit jika null tidak diproses
                    JurnalVoucherRinci::create([
                        'id' => Uuid::uuid4()->toString(), // generate uuid
                        'jurnal_voucher_id' => $jurnalVoucher->id,
                        'coa_id' => $request->coa_id[$i],
                        'nominal' => explodeRupiah($request->debit[$i]),
                        'memo' => $request->memo[$i],
                        'tipe' => "D" // DEBIT
                    ]);
    
                    //insert buku besar
                    // storeBukuBesar($request->coa_id[$i], $request->tanggal, $request->nomer, $request->memo[$i], "JV", explodeRupiah($request->debit[$i]), 0);
                    $data = [
                        'tahun' => date('Y', strtotime($request->tanggal)),
                        'tanggal' => $request->tanggal,
                        'nomer' => $request->nomer,
                        'sumber' => 'gl_jv',
                        'coa_no' => COA::find($request->coa_id[$i])->nomer_coa,
                        'coa' => COA::find($request->coa_id[$i])->nama_coa,
                        'pelanggan' => null,
                        'pemasok' => null,
                        'debit' => explodeRupiah($request->debit[$i]),
                        'kredit' => 0,
                        'keterangan' => $request->memo[$i]
                    ];
                    storeGeneralLedger($data);
                     //insert buku bank rinci
                    storeBukuBankRinci($bukuBankId, $request->coa_id[$i], explodeRupiah($request->debit[$i]), $request->memo[$i], 'D');
                }
    
                if(!is_null($request->kredit[$i])){ //cek kredit jika null tidak diproses
                    JurnalVoucherRinci::create([
                        'id' => Uuid::uuid4()->toString(), // generate uuid
                        'jurnal_voucher_id' => $jurnalVoucher->id,
                        'coa_id' => $request->coa_id[$i],
                        'nominal' => explodeRupiah($request->kredit[$i]),
                        'memo' => $request->memo[$i],
                        'tipe' => "K" // KREDIT
                    ]);
    
                    //insert buku besar
                    // storeBukuBesar($request->coa_id[$i], $request->tanggal, $request->nomer, $request->memo[$i], "JV", 0, explodeRupiah($request->kredit[$i]));
                    $data = [
                        'tahun' => date('Y', strtotime($request->tanggal)),
                        'tanggal' => $request->tanggal,
                        'nomer' => $request->nomer,
                        'sumber' => 'gl_jv',
                        'coa_no' => COA::find($request->coa_id[$i])->nomer_coa,
                        'coa' => COA::find($request->coa_id[$i])->nama_coa,
                        'pelanggan' => null,
                        'pemasok' => null,
                        'debit' => 0,
                        'kredit' => explodeRupiah($request->kredit[$i]),
                        'keterangan' => $request->memo[$i]
                    ];
                    storeGeneralLedger($data);
                    //insert buku bank rinci
                    storeBukuBankRinci($bukuBankId, $request->coa_id[$i], explodeRupiah($request->kredit[$i]), $request->memo[$i], 'K');
                }
            }
           
        }
        
        return response()->json("OKE");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(JurnalVoucher $jurnalVoucher)
    {
        if(auth()->user()->cannot('delete', JurnalVoucher::class)) abort(403, 'Access Denied.');

        //Delete buku besar
        // softDestroyBukuBesar("JV", $jurnalVoucher->nomer);

        destroyGeneralLedger($jurnalVoucher->nomer, 'gl_jv');

        //Delete buku bank
        softDestroyBukuBank("JV", $jurnalVoucher->nomer);
        
        //Delete jurnal voucher
        $jurnalVoucher->update(['is_deleted' => 0]);

        return redirect()->route('admin.jurnal-voucher.index')->with('success', 'Jurnal Voucher (Jurnal Umum) berhasil dihapus');
    }

    public function destroyJurnalVoucherRinci(Request $request)
    {
        if(auth()->user()->cannot('delete', JurnalVoucher::class)) abort(403, 'Access Denied.');

        JurnalVoucherRinci::whereId($request->id)->delete();

        return response()->json("OKE");

    }
}
