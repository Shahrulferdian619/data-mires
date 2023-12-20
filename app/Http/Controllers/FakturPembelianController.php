<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BerkasFaktur;
use App\Models\Coa;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Ri_rinci;
use App\Models\Ri;
use App\Models\fakturpembelian;
use App\Models\fakturpembelian_rinci;
use App\Models\FakturToRelation;
use App\Models\Popembelian;
use App\Models\Popembelian_rinci;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use PDF;
use DataTables;
use Carbon\Carbon;

class FakturPembelianController extends Controller
{
    
    public function index(Request $request)
    {
        if (auth()->user()->cannot('viewAny', fakturpembelian::class)) abort('403', 'access denied');

        // session()->forget('fakturpembelian');
        $faktur = fakturpembelian::all();

        if ($request->ajax()) {
            return datatables()->of($faktur)

                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    return '<td>
                <a class="badge badge-light-secondary" href="' . url('admin/fakturpembelian/' . $row->id) . '"><i data-feather="eye"></i> Lihat</a>            
                </td>';
                })
                // ->filterColumn('tanggal', function ($query, $keyword) {
                //     $query->whereRaw("DATE_FORMAT(tanggal,'%m/%d/%Y') LIKE ?", ["%$keyword%"]);
                // })
                ->editColumn('total_perfaktur', function ($row) {
                    return "Rp. " . number_format($row->relation->sum('total_perfaktur'), 2, ',', '.');
                })
                ->editColumn('tanggal', function ($row) {
                    return $row->tanggal ? with(new Carbon($row->tanggal))->format('d-m-Y') : '';;
                })
                ->editColumn('approval', function ($row) {

                    $approve = '';
                    if ($row->approve_direktur == 0) {
                        $approve .= '<div style="width:150px" class="badge badge-light-warning">Menunggu Owner</div>';
                    } elseif ($row->approve_direktur == 1) {
                        $approve .= '<div style="width:150px" class="badge badge-light-success">Approve Owner</div>';
                    } elseif ($row->approve_direktur == 2) {
                        $approve .= '<div style="width:150px" class="badge badge-light-danger">Reject Owner</div>';
                    }

                    return $approve;
                })
                ->editColumn('status', function ($row) {
                    if ($row->is_payment == 0) {
                        $is_payment = '<div class="badge badge-light-danger">Belum dibayar</div>';
                    } elseif ($row->is_payment == 1) {
                        $is_payment = '<div class="badge badge-light-success">Sudah dibayar</div>';
                    }

                    return $is_payment;
                })
                ->editColumn('termin', function ($row) {
                    return $row->termin . " Hari";
                })
                ->rawColumns(['actions', 'total_perfaktur', 'tanggal', 'approval', 'status', 'termin'])->make(true);
            // ->rawColumns(['actions'])
            // ->make(true);
        }

        return view('faktur.index', compact(
            'faktur',
        ));
    }

    public function exportPDF()
    {
        $faktur = fakturpembelian::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('faktur.exportpdf', compact('faktur'));
        return $pdf->setWarnings(false)->download('Tagihan Pembelian.pdf');
    }

    public function printPDF()
    {
        $faktur = fakturpembelian::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('faktur.exportpdf', compact('faktur'));
        return $pdf->setWarnings(false)->stream();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (auth()->user()->cannot('create', fakturpembelian::class)) abort('403', 'access denied');
        $coaKredit = Coa::with('tipeCoa')
            ->whereIsActive(1)
            ->whereIn('id_coatype', [1])  // Tambahkan untuk tidak ditampilkan di pilihan akun rincian
            ->latest()->get();

        $suppliers = [];
        $supp = Supplier::all();
        foreach($supp as $supp){
            if(Popembelian::where(['supplier_id' => $supp->id])->where('status_faktur', '!=', 2)->count() != 0){
                array_push($suppliers, $supp);
            }
        }

        // $suppliers = Supplier::where('aktif', 1)->get();
        // session()->forget('fakturpembelian');
        // return response()->json(session()->get('fakturpembelian'));
        //return $suppliers;
        return view('faktur.create', compact(
            'suppliers',
            'coaKredit'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function _store(Request $request)
    {

        if (isset($_POST['input_session'])) {
            $request->validate([
                'rincian_id' => 'required'
            ]);

            if ($request->based_on == 1) {
                // Purchase Order
                $purchase = [];
                for ($i = 0; $i < count($request->rincian_id); $i++) {
                    $po = Popembelian::find($request->rincian_id[$i]);
                    // return response()->json($po);
                    $fakturRelasi = FakturToRelation::where('po_id', $request->rincian_id[$i])->orderBy('id', 'desc')->first();
                    $lastTotal = 0;
                    if ($fakturRelasi != '') {
                        $lastTotal = $fakturRelasi->total;
                    }
                    $rincian_purchase = [];
                    foreach ($po->rinci as $item) {
                        $rincian_purchase[] = [
                            'barang_id' => $item->barang_id,
                            'barang_nama' => $item->barang->nama_barang,
                            'qty' => $item->jumlah - $item->jumlah_faktur,
                            'harga' => $item->harga,
                            'dsc' => $item->dsc
                        ];
                    }
                    $purchase[] = [
                        'rincian_id' => $po->id,
                        'nomer_rincian' => $po->nomer_po,
                        'tanggal_rincian' => $po->tanggal_po,
                        'pembayaran_terakhir' => $lastTotal,
                        'pajak_lain' => $po->pajak_lain,
                        'rincian' => $rincian_purchase
                    ];
                }

                $fakturPembelian = [
                    'supplier_id' => $request->supplier_id,
                    'based_on' => $request->based_on,
                    'data' => $purchase
                ];

                session()->put('fakturpembelian', $fakturPembelian);
                // return response()->json($fakturPembelian);
            } else if ($request->based_on == 2) {
                // Recieve Item
                // return response()->json($request->rincian_id);
                $poArray = [];
                foreach ($request->rincian_id as $key => $value) {
                    // Temukan PO Dari Recieve Item ID
                    $ri = Ri::find($value);
                    // masukkan po id ke array
                    $poArray[] = $ri->po_id;
                }
                // distinct array po id
                $poArray = array_unique($poArray);
                $purchase = [];
                // looping array po
                foreach ($poArray as $key => $value) {
                    // Temukan PO
                    $po = Popembelian::find($value);
                    // Temukan Relasi Faktur Sebelumnya
                    $fakturRelasi = FakturToRelation::where('po_id', $value)->whereNotNull('ri_id')->orderBy('id', 'desc')->first();
                    // buat variable total terkahir
                    $lastTotal = 0;
                    if ($fakturRelasi != '') {
                        // inputkan total terakhir jika ada
                        $lastTotal = $fakturRelasi->total;
                    }
                    $rincian_purchase = [];
                    // looping rincina yang dipilih
                    foreach ($request->rincian_id as $key_rincian => $value_rincian) {
                        $ri = Ri_rinci::where('ri_id', $value_rincian)->get();
                        foreach ($ri as $key_ri => $value_ri) {
                            $rincian_purchase[] = [
                                'barang_id' => $value_ri->barang_id,
                                'barang_nama' => $value_ri->barang->nama_barang,
                                'qty' => $value_ri->qty,
                                'harga' => $value_ri->harga,
                                'dsc' => $value_ri->dsc,
                                'po_rinci_id' => $value_ri->po_rinci_id,
                                'recieve_item_id' => $value_rincian
                            ];
                        }
                        // return response()->json($ri);
                    }
                    $purchase[] = [
                        'rincian_id' => $po->id,
                        'nomer_rincian' => $po->nomer_po,
                        'tanggal_rincian' => $po->tanggal_po,
                        'pembayaran_terakhir' => $lastTotal,
                        'ppn' => $po->is_tax,
                        'rincian' => $rincian_purchase
                    ];
                }
                $fakturPembelian = [
                    'supplier_id' => $request->supplier_id,
                    'based_on' => $request->based_on,
                    'data' => $purchase
                ];
                session()->put('fakturpembelian', $fakturPembelian);
            }

            return back();
        }
        if (isset($_POST['buat_faktur'])) {
            dd($request);

            $request->validate([
                'nomer_faktur' => 'required|unique:fakturpembelians,nomer_fakturpembelian',
                'tanggal_faktur' => 'required'
            ]);

            // return response()->json($request->barang_id[1]);
            // die;

            $fakturPembelian = session()->get('fakturpembelian');

            $faktur = new fakturpembelian();
            $faktur->user_id = Auth::id();
            $faktur->supplier_id = $fakturPembelian['supplier_id'];
            $faktur->nomer_fakturpembelian = $request->nomer_faktur;
            $faktur->tanggal = $request->tanggal_faktur;
            $faktur->keterangan = $request->keterangan;
            $faktur->termin = $request->termin;
            $faktur->save();

            // return response()->json($fakturPembelian);
            $GL_Total = 0;
            if ($fakturPembelian['based_on'] == 1) {
                // Purchase Order
                foreach ($request->rincian_id as $key => $value) {

                    // Faktur to Relation
                    $FTR = new FakturToRelation();
                    $FTR->faktur_id = $faktur->id;
                    $FTR->po_id = $value;
                    $FTR->total_perfaktur = $request->price_total[$key];
                    $FTR->total = $request->price_total[$key];
                    $FTR->save();

                    $GL_Total += $request->price_total[$key];

                    $qtyTotal = 0;
                    $qtyFakturTotal = 0;
                    $status_faktur = 2;

                    foreach ($request->qty[$value] as $key_item => $value_item) {
                        // Create faktur rinci
                        $fakturRinci = new fakturpembelian_rinci();
                        $fakturRinci->fakturpembelian_id = $faktur->id;
                        $fakturRinci->barang_id = $request->barang_id[$value][$key_item];
                        $fakturRinci->qty = $value_item;
                        $fakturRinci->harga = $request->harga[$value][$key_item];
                        $fakturRinci->dsc = $request->dsc[$value][$key_item];
                        $fakturRinci->save();
                    }

                    // ambil rincian_po dan update jumlah faktur
                    $po_rinci = Popembelian_rinci::where('popembelian_id', $value)->get();
                    foreach ($po_rinci as $key_po => $value_po) {
                        $per_rinci = Popembelian_rinci::find($value_po->id);
                        $per_rinci->jumlah_faktur = $per_rinci->jumlah_faktur + $request->qty[$value][$key_po];
                        $per_rinci->dsc = $request->dsc[$value][$key_po];
                        $per_rinci->update();
                        $qtyTotal += $per_rinci->jumlah;
                        $qtyFakturTotal += $per_rinci->jumlah_faktur;
                    }

                    if ($qtyFakturTotal < $qtyTotal) {
                        $status_faktur = 1;
                    }

                    $updatePO = Popembelian::find($value);
                    $updatePO->status_faktur = $status_faktur;
                    $updatePO->save();
                }
            } else if ($fakturPembelian['based_on'] == 2) {
                // Recieve Item
                foreach (session()->get('fakturpembelian')['data'] as $key => $value) {
                    // Faktur Relation
                    $FTR = new FakturToRelation();
                    $FTR->faktur_id = $faktur->id;
                    $FTR->po_id = $value['rincian_id'];
                    $FTR->total_perfaktur = $request->price_total[$key];
                    $FTR->total = $request->price_total[$key];
                    $FTR->save();

                    $GL_Total += $request->price_total[$key];

                    $qtyTotal = 0;
                    $qtyFakturTotal = 0;
                    $status_faktur = 2;

                    $data_recieve_item_id = [];
                    $data_distinct_rinci = [];
                    foreach ($value['rincian'] as $key_rinci => $value_rinci) {
                        $isCheck = 0;
                        $check_recieve = 0;
                        if (count($data_distinct_rinci) == 0) {
                            $data_distinct_rinci[] = [
                                'barang_id' => $value_rinci['barang_id'],
                                'barang_nama' => $value_rinci['barang_nama'],
                                'qty' => $value_rinci['qty'],
                                'harga' => $value_rinci['harga'],
                                'dsc' => $value_rinci['dsc'],
                                'po_rinci_id' => $value_rinci['po_rinci_id']
                            ];
                            $data_recieve_item_id[] = $value_rinci['recieve_item_id'];
                        } else {
                            foreach ($data_distinct_rinci as $index => $item_distinct) {
                                if ($item_distinct['po_rinci_id'] == $value_rinci['po_rinci_id']) {
                                    $data_distinct_rinci[$index]['qty'] = $item_distinct['qty'] + $value_rinci['qty'];
                                    $isCheck = 1;
                                }
                            }
                            foreach ($data_recieve_item_id as $key_recieve => $value_recieve) {
                                if ($value_recieve == $value_rinci['recieve_item_id']) {
                                    $check_recieve = 1;
                                }
                            }
                            if ($isCheck == 0) {
                                $data_distinct_rinci[] = [
                                    'barang_id' => $value_rinci['barang_id'],
                                    'barang_nama' => $value_rinci['barang_nama'],
                                    'qty' => $value_rinci['qty'],
                                    'harga' => $value_rinci['harga'],
                                    'dsc' => $value_rinci['dsc'],
                                    'po_rinci_id' => $value_rinci['po_rinci_id']
                                ];
                            }
                            if ($check_recieve == 0) {
                                $data_recieve_item_id[] = $value_rinci['recieve_item_id'];
                            }
                        }
                    }
                    foreach ($data_distinct_rinci as $key_dist => $value_dist) {
                        // Buat Faktur Rinci
                        $fakturRinci = new fakturpembelian_rinci();
                        $fakturRinci->fakturpembelian_id = $faktur->id;
                        $fakturRinci->barang_id = $value_dist['barang_id'];
                        $fakturRinci->qty = $value_dist['qty'];
                        $fakturRinci->harga = $value_dist['harga'];
                        $fakturRinci->dsc = $value_dist['dsc'];
                        $fakturRinci->save();

                        // Update PO Rinci
                        $po_rinci = Popembelian_rinci::find($value_dist['po_rinci_id']);
                        $po_rinci->jumlah_faktur = $po_rinci->jumlah_faktur + $value_dist['qty'];
                        $po_rinci->update();

                        $qtyTotal += $po_rinci->jumlah;
                        $qtyFakturTotal += $po_rinci->jumlah_faktur;
                    }

                    if ($qtyFakturTotal < $qtyTotal) {
                        $status_faktur = 1;
                    }

                    // Update Status Recieve Item Status
                    foreach ($data_recieve_item_id as $key_upt => $value_upt) {
                        $riUpdate = Ri::find($value_upt);
                        $riUpdate->status_faktur = 1;
                        $riUpdate->save();
                    }

                    $updatePO = Popembelian::find($value['rincian_id']);
                    $updatePO->status_faktur = $status_faktur;
                    $updatePO->save();
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
                $file->move('uploads/faktur', $berkas1);
            }
            if ($request->hasFile('berkas_2')) {
                $file = $request->file('berkas_2');
                $originalName2 = $file->getClientOriginalName();
                $berkas2 = $originalName2;
                $file->move('uploads/faktur', $berkas2);
            }
            if ($request->hasFile('berkas_3')) {
                $file = $request->file('berkas_3');
                $originalName3 = $file->getClientOriginalName();
                $berkas3 = $originalName3;
                $file->move('uploads/faktur', $berkas3);
            }
            if ($request->hasFile('berkas_4')) {
                $file = $request->file('berkas_4');
                $originalName4 = $file->getClientOriginalName();
                $berkas4 = $originalName4;
                $file->move('uploads/faktur', $berkas4);
            }
            if ($request->hasFile('berkas_5')) {
                $file = $request->file('berkas_5');
                $originalName5 = $file->getClientOriginalName();
                $berkas5 = $originalName5;
                $file->move('uploads/faktur', $berkas5);
            }

            $berkasfaktur = new BerkasFaktur();
            $berkasfaktur->faktur_id = $faktur->id;
            $berkasfaktur->berkas_1 = $berkas1;
            $berkasfaktur->berkas_2 = $berkas2;
            $berkasfaktur->berkas_3 = $berkas3;
            $berkasfaktur->berkas_4 = $berkas4;
            $berkasfaktur->berkas_5 = $berkas5;
            $berkasfaktur->save();

            session()->forget('fakturpembelian');
            return redirect('/admin/fakturpembelian')->with('success', 'Berhasil membuat tagihan');
        }
    }

    public function store(Request $request)
    {
        if (auth()->user()->cannot('create', fakturpembelian::class)) abort(403, 'access denied');

        if (isset($_POST['input_session'])) {
            $request->validate([
                'rincian_id' => 'required'
            ]);

            if ($request->based_on == 1) {
                // Purchase Order
                $purchase = [];
                for ($i = 0; $i < count($request->rincian_id); $i++) {
                    $po = Popembelian::find($request->rincian_id[$i]);
                    // return response()->json($po);
                    $fakturRelasi = FakturToRelation::where('po_id', $request->rincian_id[$i])->orderBy('id', 'desc')->first();
                    $lastTotal = 0;
                    if ($fakturRelasi != '') {
                        $lastTotal = $fakturRelasi->total;
                    }
                    $rincian_purchase = [];
                    foreach ($po->rinci as $item) {
                        $rincian_purchase[] = [
                            'barang_id' => $item->barang_id,
                            'description' => $item->description,
                            'barang_nama' => $item->barang->nama_barang,
                            'qty' => $item->jumlah - $item->jumlah_faktur,
                            'harga' => $item->harga,
                            'dsc' => $item->dsc
                        ];
                    }
                    $purchase[] = [
                        'rincian_id' => $po->id,
                        'nomer_rincian' => $po->nomer_po,
                        'tanggal_rincian' => $po->tanggal_po,
                        'pembayaran_terakhir' => $lastTotal,
                        'ppn' => $po->is_tax,
                        'pph' => $po->pph,
                        'pajak_lain' => $po->pajak_lain,
                        'rincian' => $rincian_purchase
                    ];
                }

                $fakturPembelian = [
                    'supplier_id' => $request->supplier_id,
                    'based_on' => $request->based_on,
                    'data' => $purchase
                ];

                session()->put('fakturpembelian', $fakturPembelian);
                // return response()->json($fakturPembelian);
            } else if ($request->based_on == 2) {
                // Recieve Item
                // return response()->json($request->rincian_id);
                // $poArray = [];
                // foreach ($request->rincian_id as $key => $value) {
                //     // Temukan PO Dari Recieve Item ID
                //     $ri = Ri::find($value);
                //     // masukkan po id ke array
                //     $poArray[] = $ri->po_id;
                // }
                // distinct array po id
                // $poArray = array_unique($poArray);
                // $purchase = [];
                // looping array po
                foreach ($request->rincian_id as $key => $value) {
                    // Catat RI ID
                    $po_id_from_ri = Ri::find($value)->po_id;

                    $po = Popembelian::find($po_id_from_ri);
                    // Temukan Relasi Faktur Sebelumnya
                    $fakturRelasi = FakturToRelation::where('po_id', $value)->whereNotNull('ri_id')->orderBy('id', 'desc')->first();
                    // buat variable total terkahir
                    $lastTotal = 0;
                    if ($fakturRelasi != '') {
                        // inputkan total terakhir jika ada
                        $lastTotal = $fakturRelasi->total;
                    }
                    $rincian_purchase = [];
                    // looping rincian yang dipilih
                    $ri = Ri_rinci::where('ri_id', $value)->get();
                    foreach ($ri as $key_ri => $value_ri) {
                        $rincian_purchase[] = [
                            'barang_id' => $value_ri->barang_id,
                            'barang_nama' => $value_ri->barang->nama_barang,
                            'qty' => $value_ri->qty,
                            'harga' => $value_ri->harga,
                            'dsc' => $value_ri->dsc,
                            'description' => '',
                            'po_rinci_id' => $value_ri->po_rinci_id,
                            'recieve_item_id' => $value
                        ];
                    }
                    // return response()->json($ri);
                    $purchase[] = [
                        'rincian_id' => $po->id,
                        'ri_id' => $value,
                        'nomer_rincian' => $po->nomer_po,
                        'tanggal_rincian' => $po->tanggal_po,
                        'pembayaran_terakhir' => $lastTotal,
                        'ppn' => $po->is_tax,
                        'pph' => $po->pph,
                        'pajak_lain' => $po->pajak_lain,
                        'rincian' => $rincian_purchase
                    ];
                }
                $fakturPembelian = [
                    'supplier_id' => $request->supplier_id,
                    'based_on' => $request->based_on,
                    'data' => $purchase
                ];
                session()->put('fakturpembelian', $fakturPembelian);
            }

            return back();
        } else {

            $request->validate([
                'nomer_faktur' => 'required|unique:fakturpembelians,nomer_fakturpembelian',
                'tanggal_faktur' => 'required'
            ]);

            // return response()->json($request->barang_id[1]);
            // die;

            $fakturPembelian = session()->get('fakturpembelian');

            $faktur = new fakturpembelian();
            $faktur->user_id = Auth::id();
            $faktur->supplier_id = $fakturPembelian['supplier_id'];
            $faktur->nomer_fakturpembelian = $request->nomer_faktur;
            $faktur->tanggal = $request->tanggal_faktur;
            $faktur->keterangan = $request->keterangan;
            $faktur->termin = $request->termin;
            $faktur->save();

            // return response()->json($fakturPembelian);
            $GL_Total = 0;
            if ($fakturPembelian['based_on'] == 1) {
                // Purchase Order
                foreach ($request->rincian_id as $key => $value) {

                    // Faktur to Relation
                    $FTR = new FakturToRelation();
                    $FTR->faktur_id = $faktur->id;
                    $FTR->po_id = $value;
                    $FTR->total_perfaktur = $request->price_total[$key];
                    $FTR->total = $request->price_total[$key];
                    $FTR->save();

                    $GL_Total += $request->price_total[$key];

                    $qtyTotal = 0;
                    $qtyFakturTotal = 0;
                    $status_faktur = 2;

                    foreach ($request->qty[$value] as $key_item => $value_item) {
                        // Create faktur rinci
                        $fakturRinci = new fakturpembelian_rinci();
                        $fakturRinci->fakturpembelian_id = $faktur->id;
                        $fakturRinci->barang_id = $request->barang_id[$value][$key_item];
                        $fakturRinci->qty = $value_item;
                        $fakturRinci->harga = $request->harga[$value][$key_item];
                        $fakturRinci->dsc = $request->dsc[$value][$key_item];
                        $fakturRinci->save();
                    }

                    // ambil rincian_po dan update jumlah faktur
                    $po_rinci = Popembelian_rinci::where('popembelian_id', $value)->get();
                    foreach ($po_rinci as $key_po => $value_po) {
                        $per_rinci = Popembelian_rinci::find($value_po->id);
                        $per_rinci->jumlah_faktur = $per_rinci->jumlah_faktur + $request->qty[$value][$key_po];
                        $per_rinci->dsc = $request->dsc[$value][$key_po];
                        $per_rinci->update();
                        $qtyTotal += $per_rinci->jumlah;
                        $qtyFakturTotal += $per_rinci->jumlah_faktur;
                    }

                    if ($qtyFakturTotal < $qtyTotal) {
                        $status_faktur = 1;
                    }

                    $updatePO = Popembelian::find($value);
                    $updatePO->status_faktur = $status_faktur;
                    $updatePO->save();
                }
            } else if ($fakturPembelian['based_on'] == 2) {
                // Recieve Item
                foreach (session()->get('fakturpembelian')['data'] as $key => $value) {
                    // Faktur Relation
                    $FTR = new FakturToRelation();
                    $FTR->faktur_id = $faktur->id;
                    $FTR->po_id = $value['rincian_id'];
                    $FTR->ri_id = $request->ri_id[$key];
                    $FTR->total_perfaktur = $request->price_total[$key];
                    $FTR->total = $request->price_total[$key];
                    $FTR->save();

                    $GL_Total += $request->price_total[$key];

                    $qtyTotal = 0;
                    $qtyFakturTotal = 0;
                    $status_faktur = 2;

                    $data_recieve_item_id = [];
                    $data_distinct_rinci = [];
                    foreach ($value['rincian'] as $key_rinci => $value_rinci) {
                        $isCheck = 0;
                        $check_recieve = 0;
                        if (count($data_distinct_rinci) == 0) {
                            $data_distinct_rinci[] = [
                                'barang_id' => $value_rinci['barang_id'],
                                'barang_nama' => $value_rinci['barang_nama'],
                                'qty' => $value_rinci['qty'],
                                'harga' => $value_rinci['harga'],
                                'dsc' => $value_rinci['dsc'],
                                'po_rinci_id' => $value_rinci['po_rinci_id']
                            ];
                            $data_recieve_item_id[] = $value_rinci['recieve_item_id'];
                        } else {
                            foreach ($data_distinct_rinci as $index => $item_distinct) {
                                if ($item_distinct['po_rinci_id'] == $value_rinci['po_rinci_id']) {
                                    $data_distinct_rinci[$index]['qty'] = $item_distinct['qty'] + $value_rinci['qty'];
                                    $isCheck = 1;
                                }
                            }
                            foreach ($data_recieve_item_id as $key_recieve => $value_recieve) {
                                if ($value_recieve == $value_rinci['recieve_item_id']) {
                                    $check_recieve = 1;
                                }
                            }
                            if ($isCheck == 0) {
                                $data_distinct_rinci[] = [
                                    'barang_id' => $value_rinci['barang_id'],
                                    'barang_nama' => $value_rinci['barang_nama'],
                                    'qty' => $value_rinci['qty'],
                                    'harga' => $value_rinci['harga'],
                                    'dsc' => $value_rinci['dsc'],
                                    'po_rinci_id' => $value_rinci['po_rinci_id']
                                ];
                            }
                            if ($check_recieve == 0) {
                                $data_recieve_item_id[] = $value_rinci['recieve_item_id'];
                            }
                        }
                    }
                    foreach ($data_distinct_rinci as $key_dist => $value_dist) {
                        // Buat Faktur Rinci
                        $fakturRinci = new fakturpembelian_rinci();
                        $fakturRinci->fakturpembelian_id = $faktur->id;
                        $fakturRinci->barang_id = $value_dist['barang_id'];
                        $fakturRinci->qty = $value_dist['qty'];
                        $fakturRinci->harga = $value_dist['harga'];
                        $fakturRinci->dsc = $value_dist['dsc'];
                        $fakturRinci->save();

                        // Update PO Rinci
                        $po_rinci = Popembelian_rinci::find($value_dist['po_rinci_id']);
                        $po_rinci->jumlah_faktur = $po_rinci->jumlah_faktur + $value_dist['qty'];
                        $po_rinci->update();

                        $qtyTotal += $po_rinci->jumlah;
                        $qtyFakturTotal += $po_rinci->jumlah_faktur;
                    }

                    if ($qtyFakturTotal < $qtyTotal) {
                        $status_faktur = 1;
                    }

                    // Update Status Recieve Item Status
                    foreach ($data_recieve_item_id as $key_upt => $value_upt) {
                        $riUpdate = Ri::find($value_upt);
                        $riUpdate->status_faktur = 1;
                        $riUpdate->save();
                    }

                    $updatePO = Popembelian::find($value['rincian_id']);
                    $updatePO->status_faktur = $status_faktur;
                    $updatePO->save();
                }
            }

            $supplier_detail = Supplier::find($fakturPembelian['supplier_id']);

            // input kredit
            // $storeGeneralLedger = [
            //     'tahun' => date('Y', strtotime($request->tanggal_faktur)),
            //     'tanggal' => $request->tanggal_faktur,
            //     'nomer' => $request->nomer_faktur,
            //     'sumber' => 'pr_pi',
            //     'coa_no' => '2000.1',
            //     'coa' => 'AP Supplier',
            //     'pelanggan' => null,
            //     'pemasok' => $supplier_detail->nama_supplier,
            //     'debit' => 0,
            //     'kredit' => $GL_Total,
            //     'keterangan' => $request->keterangan
            // ];

            // storeGeneralLedger($storeGeneralLedger);
            // $coa = Coa::find($request->kredit_coa_id);

            // input debit
            // $storeGeneralLedger = [
            //     'tahun' => date('Y', strtotime($request->tanggal_faktur)),
            //     'tanggal' => $request->tanggal_faktur,
            //     'nomer' => $request->nomer_faktur,
            //     'sumber' => 'pr_pi',
            //     'coa_no' => $coa->nomer_coa,
            //     'coa' => $coa->nama_coa,
            //     'pelanggan' => null,
            //     'pemasok' => $supplier_detail->nama_supplier,
            //     'debit' => $GL_Total,
            //     'kredit' => 0,
            //     'keterangan' => $request->keterangan
            // ];
            // storeGeneralLedger($storeGeneralLedger);

            if (!empty($request->berkas)) {
                // buat data berkas
                $totalBerkas = count($request->berkas);
                $collectNamaBerkas = [];

                for ($i = 0; $i < $totalBerkas; $i++) {
                    if ($request->hasFile('berkas.' . $i)) {
                        $file = $request->file('berkas')[$i];
                        $originalName = $file->getClientOriginalName();
                        $berkas = str_replace(" ", "", $originalName);
                        $file->move('uploads/faktur', $berkas);

                        array_push($collectNamaBerkas, $berkas);
                    }
                }

                $saveBerkas = count($collectNamaBerkas);

                $berkasfaktur = new BerkasFaktur();
                $berkasfaktur->faktur_id = $faktur->id;

                for ($i = 0; $i < $saveBerkas; $i++) {
                    $flag = $i + 1;
                    $var = "berkas_" . $flag;
                    $berkasfaktur->$var = $collectNamaBerkas[$i];
                }
                $berkasfaktur->save();
            }

            session()->forget('fakturpembelian');
            return redirect('/admin/fakturpembelian')->with('success', 'Berhasil membuat tagihan');
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
        if (auth()->user()->cannot('view', fakturpembelian::class)) abort('403', 'access denied');

        $faktur = fakturpembelian::find($id);
        $supplier = Supplier::all();
        return view('faktur.detail', compact('faktur', 'supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(fakturpembelian $fakturpembelian)
    {
        //
        if (auth()->user()->cannot('update', fakturpembelian::class)) abort('403', 'access denied');

        $faktur = fakturpembelian::find($fakturpembelian->id);
        if ($faktur->is_payment == 1) {
            return back()->with('sweetFail', 'Tidak bisa edit, faktur telah dibuatkan');
        }
        $coaKredit = Coa::with('tipeCoa')
            ->whereIsActive(1)
            ->whereIn('id_coatype', [1])  // Tambahkan untuk tidak ditampilkan di pilihan akun rincian
            ->latest()->get();

        $data = [
            'fakturpembelian' => $faktur,
            'berkas' => BerkasFaktur::whereFakturId($fakturpembelian->id)->first(),
            'coaKredit' => $coaKredit
        ];


        return view('faktur.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function _update(Request $request, fakturpembelian $fakturpembelian)
    {
        //

        $fakturpembelian->termin = $request->termin;
        $fakturpembelian->save();

        $berkas = BerkasFaktur::where('faktur_id', $fakturpembelian->id)->first();

        if ($request->hasFile('berkas_1')) {
            if ($berkas->berkas_1 != '') {
                File::delete('uploads/faktur/' . $berkas->berkas_1);
            }
            $file = $request->file('berkas_1');
            $berkas_1 = $file->getClientOriginalName();
            $file->move('uploads/faktur', $berkas_1);
        } else {
            $berkas_1 = $berkas->berkas_1;
        }

        if ($request->hasFile('berkas_2')) {
            if ($berkas->berkas_2 != '') {
                File::delete('uploads/faktur/' . $berkas->berkas_2);
            }
            $file = $request->file('berkas_2');
            $berkas_2 = $file->getClientOriginalName();
            $file->move('uploads/faktur', $berkas_2);
        } else {
            $berkas_2 = $berkas->berkas_2;
        }

        if ($request->hasFile('berkas_3')) {
            if ($berkas->berkas_3 != '') {
                File::delete('uploads/faktur/' . $berkas->berkas_3);
            }
            $file = $request->file('berkas_3');
            $berkas_3 = $file->getClientOriginalName();
            $file->move('uploads/faktur', $berkas_3);
        } else {
            $berkas_3 = $berkas->berkas_3;
        }

        if ($request->hasFile('berkas_4')) {
            if ($berkas->berkas_4 != '') {
                File::delete('uploads/faktur/' . $berkas->berkas_4);
            }
            $file = $request->file('berkas_4');
            $berkas_4 = $file->getClientOriginalName();
            $file->move('uploads/faktur', $berkas_4);
        } else {
            $berkas_4 = $berkas->berkas_4;
        }

        if ($request->hasFile('berkas_5')) {
            if ($berkas->berkas_5 != '') {
                File::delete('uploads/faktur/' . $berkas->berkas_5);
            }
            $file = $request->file('berkas_5');
            $berkas_5 = $file->getClientOriginalName();
            $file->move('uploads/faktur', $berkas_5);
        } else {
            $berkas_5 = $berkas->berkas_5;
        }

        $berkas->berkas_1 = $berkas_1;
        $berkas->berkas_2 = $berkas_2;
        $berkas->berkas_3 = $berkas_3;
        $berkas->berkas_4 = $berkas_4;
        $berkas->berkas_5 = $berkas_5;
        $berkas->save();

        return redirect('admin/fakturpembelian/' . $fakturpembelian->id)->with('success', 'Berhasil mengubah faktur');
    }

    public function update(Request $request, fakturpembelian $fakturpembelian)
    {
        if (auth()->user()->cannot('update', fakturpembelian::class)) abort(403, 'access denied');

        if (auth()->user()->cannot('update', fakturpembelian::class)) abort('403', 'access denied');

        $request->validate([
            'nomer_fakturpembelian' => 'required|unique:fakturpembelians,nomer_fakturpembelian,' . $fakturpembelian->id,
            'tanggal' => 'required'
        ]);

        $fakturpembelian->nomer_fakturpembelian = $request->nomer_fakturpembelian;
        $fakturpembelian->tanggal = $request->tanggal;
        $fakturpembelian->termin = $request->termin;
        $fakturpembelian->save();

        // update fakturpembelian rinci
        foreach ($request->rinci_id as $key => $value) {
            // dapatkan faktur rinci
            $faktur_rinci = fakturpembelian_rinci::find($value);
            $faktur_rinci->harga = explodeRupiah($request->harga[$key]);
            $faktur_rinci->save();
        }

        destroyGeneralLedger($request->nomer_fakturpembelian, 'pr_pi');

        // update faktur to relation
        $total = fakturpembelian_rinci::where('fakturpembelian_id', $fakturpembelian->id)->sum(DB::raw('(harga - (harga * dsc / 100)) * qty'));
        $faktur_to_relation = FakturToRelation::where('faktur_id', $fakturpembelian->id)->first();
        $faktur_to_relation->total_perfaktur = $total;
        $faktur_to_relation->total = $total;
        $faktur_to_relation->save();

        $supplier_detail = Supplier::find($fakturpembelian->supplier_id);

        // input kredit
        $storeGeneralLedger = [
            'tahun' => date('Y', strtotime($request->tanggal)),
            'tanggal' => $request->tanggal,
            'nomer' => $request->nomer_fakturpembelian,
            'sumber' => 'pr_pi',
            'coa_no' => '2000.1',
            'coa' => 'AP Supplier',
            'pelanggan' => null,
            'pemasok' => $supplier_detail->nama_supplier,
            'debit' => 0,
            'kredit' => $total,
            'keterangan' => $request->keterangan
        ];

        storeGeneralLedger($storeGeneralLedger);
        $coa = Coa::find($request->kredit_coa_id);

        // input debit
        $storeGeneralLedger = [
            'tahun' => date('Y', strtotime($request->tanggal)),
            'tanggal' => $request->tanggal,
            'nomer' => $request->nomer_fakturpembelian,
            'sumber' => 'pr_pi',
            'coa_no' => $coa->nomer_coa,
            'coa' => $coa->nama_coa,
            'pelanggan' => null,
            'pemasok' => $supplier_detail->nama_supplier,
            'debit' => $total,
            'kredit' => 0,
            'keterangan' => $request->keterangan
        ];
        storeGeneralLedger($storeGeneralLedger);

        $berkasFaktur = BerkasFaktur::where('faktur_id', $fakturpembelian->id)->first();

        if (!empty($request->berkas)) {
            $totalBerkas = count($request->berkas);
            $collectNamaBerkas = [];

            for ($i = 0; $i < $totalBerkas; $i++) {
                $flag = $i + 1;
                $var = "berkas_" . $flag;

                if ($request->hasFile('berkas.' . $i)) {
                    $file = $request->file('berkas')[$i];
                    $originalName = $file->getClientOriginalName();
                    $berkas = $originalName;
                    $file->move('uploads/faktur', $berkas);

                    if (!empty($berkasFaktur)) {
                        File::delete('uploads/faktur/' . $berkasFaktur->$var);
                    }


                    array_push($collectNamaBerkas, $berkas);
                }
            }

            $saveBerkas = count($collectNamaBerkas);
            if (!empty($berkasFaktur)) {
                for ($i = 0; $i < $saveBerkas; $i++) {
                    $flag = $i + 1;
                    $var = "berkas_" . $flag;
                    $berkasFaktur->$var = $collectNamaBerkas[$i];
                }
            } else {
                $berkasFaktur = new BerkasFaktur();
                $berkasFaktur->faktur_id = $fakturpembelian->id;

                for ($i = 0; $i < $saveBerkas; $i++) {
                    $flag = $i + 1;
                    $var = "berkas_" . $flag;
                    $berkasFaktur->$var = $collectNamaBerkas[$i];
                }
            }

            $berkasFaktur->save();
        }

        return redirect('admin/fakturpembelian/' . $fakturpembelian->id)->with('success', 'Berhasil mengubah faktur');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(fakturpembelian $fakturpembelian)
    {
        if (auth()->user()->cannot('delete', fakturpembelian::class)) abort('403', 'access denied');


        $po = Popembelian::find(FakturToRelation::where('faktur_id', $fakturpembelian->id)->first()->po_id);
       
        // hapus faktur rinci
        $faktur_rinci = fakturpembelian_rinci::where('fakturpembelian_id', $fakturpembelian->id)->get();
        foreach ($faktur_rinci as $item) {
            $FAKTURRINCI = fakturpembelian_rinci::find($item->id);
            // update jumlah faktur pada po rinci
            $po_rinci = Popembelian_rinci::where(['popembelian_id' => $po->id, 'barang_id' => $item->barang_id])->first();
            $po_rinci->jumlah_faktur = $po_rinci->jumlah_faktur - $item->qty;
            $po_rinci->save();
            // hapus faktur rinci
            $FAKTURRINCI->delete();
        }

        $faktur_relation = FakturToRelation::with('po')->where('faktur_id', $fakturpembelian->id)->get();
        // hapus faktur relation
        foreach ($faktur_relation as $FR) {
            if ($FR->ri_id != null) {
                $ri = Ri::find($FR->ri_id);
                $ri->status_faktur = 0;
                $ri->save();
            }
            $FR->delete();
        }
        $po->status_faktur = 0;
        $po->save();

        // hapus berkas faktur
        $berkas = BerkasFaktur::where('faktur_id', $fakturpembelian->id)->first();
        if (!empty($berkas)) {
            if ($berkas->berkas_1 != '') {
                File::delete('uploads/faktur/' . $berkas->berkas_1);
            }
            if ($berkas->berkas_2 != '') {
                File::delete('uploads/faktur/' . $berkas->berkas_2);
            }
            if ($berkas->berkas_3 != '') {
                File::delete('uploads/faktur/' . $berkas->berkas_3);
            }
            if ($berkas->berkas_4 != '') {
                File::delete('uploads/faktur/' . $berkas->berkas_4);
            }
            if ($berkas->berkas_5 != '') {
                File::delete('uploads/faktur/' . $berkas->berkas_5);
            }
            $berkas->delete();
        }

        destroyGeneralLedger($fakturpembelian->nomer_fakturpembelian, 'pr_pi');
        // hapus faktur pembelian
        $fakturpembelian->delete();

        return redirect('admin/fakturpembelian')->with('success', 'Berhasil menghapus');
    }

    public function _destroy(fakturpembelian $fakturpembelian)
    {
        if (auth()->user()->cannot('delete', fakturpembelian::class)) abort('403', 'access denied');
        // $faktur_relation = FakturToRelation::where('faktur_id', $fakturpembelian->id)->first();
        // $fakturPO = FakturToRelation::where('po_id', $faktur_relation->po_id)->get();
        $faktur_relation = FakturToRelation::with('po')->where('faktur_id', $fakturpembelian->id)->first();
        // return response()->json($faktur_relation);

        // cek apakah faktur berasal dari ri atau po
        $recieve_item = Ri::with('rinci')->where(['po_id' => $faktur_relation->po_id, 'status_faktur' => 1])->get();
        $po = Popembelian::find($faktur_relation->po_id);
        // jika lebih dari nol maka dari ri, jika tidak maka po
        if ($recieve_item->count() > 0) {
            foreach ($recieve_item as $item) {
                $ri = Ri::with('po.fakturrelation')->whereHas('po', function ($query) use ($faktur_relation) {
                    return $query->whereHas('fakturrelation', function ($query2) use ($faktur_relation) {
                        return $query2->where('faktur_id', $faktur_relation->faktur_id);
                    });
                })->where('id', $item->id)->first();
                // return response()->json($ri);
                $ri->status_faktur = 0;
                $ri->save();
            }
            $po->status_faktur = 0;
            $po->save();
        } else {
            $po->status_faktur = 0;
            $po->save();
        }

        // hapus faktur rinci
        $faktur_rinci = fakturpembelian_rinci::where('fakturpembelian_id', $faktur_relation->faktur_id)->get();
        foreach ($faktur_rinci as $item) {
            $FAKTURRINCI = fakturpembelian_rinci::find($item->id);
            // update jumlah faktur pada po rinci
            $po_rinci = Popembelian_rinci::where(['popembelian_id' => $po->id, 'barang_id' => $item->barang_id])->first();
            $po_rinci->jumlah_faktur = $po_rinci->jumlah_faktur - $item->qty;
            $po_rinci->save();
            // hapus faktur rinci
            $FAKTURRINCI->delete();
        }

        // hapus faktur to relation
        $faktur_relation->delete();


        // hapus berkas faktur
        $berkas = BerkasFaktur::where('faktur_id', $faktur_relation->faktur_id)->first();
        if (!empty($berkas)) {
            if ($berkas->berkas_1 != '') {
                File::delete('uploads/faktur/' . $berkas->berkas_1);
            }
            if ($berkas->berkas_2 != '') {
                File::delete('uploads/faktur/' . $berkas->berkas_2);
            }
            if ($berkas->berkas_3 != '') {
                File::delete('uploads/faktur/' . $berkas->berkas_3);
            }
            if ($berkas->berkas_4 != '') {
                File::delete('uploads/faktur/' . $berkas->berkas_4);
            }
            if ($berkas->berkas_5 != '') {
                File::delete('uploads/faktur/' . $berkas->berkas_5);
            }
            $berkas->delete();
        }
        // hapus faktur pembelian
        $faktur->delete();
        destroyGeneralLedger($faktur->nomer_fakturpembelian, 'pr_pi');

        return redirect('admin/fakturpembelian')->with('success', 'Berhasil menghapus');
    }

    public function get_penerimaan_rinci($id)
    {
        if (auth()->user()->cannot('view', fakturpembelian::class)) abort(403, 'access denied');

        $data = Ri_rinci::where('ri_id', $id)->with('barang')->get();
        return response()->json($data);
    }
    public function get_penerimaan($supplier_id)
    {
        if (auth()->user()->cannot('view', fakturpembelian::class)) abort(403, 'access denied');

        // $data = Ri::where('supplier_id', $supplier_id)->where('status_ri', 0)->get();
        $data = Ri::where('supplier_id', $supplier_id)->where('status_ri', 0)->get();

        return response()->json(['data' => $data]);
    }

    public function get_rinci($supplier, $basedon)
    {
        if (auth()->user()->cannot('view', fakturpembelian::class)) abort(403, 'access denied');

        if ($basedon == 1) {
            // $data = Popembelian::with('rinci.barang')
            //     ->where([
            //         'supplier_id' => $supplier,
            //         'approve_direktur' => 1,
            //         'approve_komisaris' => 1,
            //         'status' => 0,
            //         ['status_faktur', '<', 2]
            //     ])
            //     ->orWhere(function ($query) {
            //         $query->where('status', '!=', 0)
            //             ->where('status_faktur', '=', 1);
            //     })
            //     ->get();
            // $data = Popembelian::with('rinci.barang')->with('recieve')->where([
            //     'supplier_id' => $supplier,
            //     'approve_direktur' => 1,
            //     'approve_komisaris' => 1,
            //     ['status_faktur', '!=', 2]
            // ])->whereHas('recieve', function ($query) {
            //     return $query->where('status_faktur', '!=', 1);
            // })->get();
            $data = Popembelian::with('rinci.barang')->with('recieve')->where([
                'supplier_id' => $supplier,
                'approve_direktur' => 1,
                'approve_komisaris' => 1,
                ['status_faktur', '!=', 2]
            ])->orWhere(function ($query) use ($supplier) {
                return $query->where([
                    'supplier_id' => $supplier,
                    'approve_direktur' => 1,
                    'approve_komisaris' => 1,
                    ['status_faktur', '!=', 2]
                ])->whereHas('recieve', function ($query2) {
                    return $query2->where('status_faktur', '!=', 1);
                });
            })->get();
        } else if ($basedon == 2) {
            $data = Ri::with('rinci.barang')->whereHas('po', function ($query) {
                $query->where('status_faktur', '!=', 2);
            })->with('po')->where('supplier_id', $supplier)->where('status_faktur', 0)->get();
        }
        return response()->json([
            'based_on' => $basedon,
            'data' => $data
        ]);
    }

    public function hapus_index_pilihan($basedon, $index)
    {
        // if(auth()->user()->cannot('delete', fakturpembelian::class)) abort(403, 'access denied');

        if (session()->has('fakturpembelian')) {
            if ($basedon == 1) {
                // purchase order
                $fakturPembelian = session()->get('fakturpembelian');
                unset($fakturPembelian['data'][$index]);
                if (count($fakturPembelian['data']) > 0) {
                    session()->put('fakturpembelian', $fakturPembelian);
                } else {
                    session()->forget('fakturpembelian');
                }
            } else if ($basedon == 2) {
                // recieve item
                $fakturPembelian = session()->get('fakturpembelian');
                unset($fakturPembelian['data'][$index]);
                if (count($fakturPembelian['data']) > 0) {
                    session()->put('fakturpembelian', $fakturPembelian);
                } else {
                    session()->forget('fakturpembelian');
                }
            }
        }

        return back();
    }

    public function hapus_semua_pilihan()
    {
        // if(auth()->user()->cannot('delete', fakturpembelian::class)) abort(403, 'access denied');

        if (session()->has('fakturpembelian')) {
            session()->forget('fakturpembelian');
        }

        return back();
    }

    public function approval($id)
    {
        if (auth()->user()->cannot('update', fakturpembelian::class)) abort(403, 'access denied');

        // Ambil ID Faktur
        $faktur = fakturpembelian::find($id);

        if( request('approve_komisaris') == 1){
            $faktur->approve_komisaris = 1;
            $faktur->approve_direktur = 1;
            $faktur->note_komisaris = request('note_komisaris');
        }else{
            $faktur->approve_komisaris = 2;
            $faktur->approve_direktur = 2;
        }
        $faktur->save();

        // Ambil Grand Total Rinci
        // $faktur_relation = FakturToRelation::where('faktur_id', $id)->get();
        // $grandtotal = 0;
        // foreach ($faktur_relation as $relation) {
        //     $grandtotal += $relation->total_perfaktur;
        // }
        // // Jika Yang Approve Direktur
        // if (Auth::user()->level_id == 2) {
        //     $faktur->approve_direktur = request('approve_direktur');
        //     $faktur->note_direktur = request('note_direktur');
        //     // Jika Dibawah 5 Juta
        //     if ($grandtotal <= 5000000) {
        //         $faktur->approve_komisaris = 1;
        //     }
        //     $faktur->save();
        //     //dd($faktur);

        //     // Jika Yang Approve Komisaris
        // } elseif (Auth::user()->level_id == 3) {
        //     $faktur->approve_komisaris = request('approve_komisaris');
        //     $faktur->note_komisaris = request('note_komisaris');
        //     $faktur->save();
        //     //dd($faktur);
        // }

        return redirect('/admin/fakturpembelian')->with('success', 'Berhasil Menetapkan Approval');
    }
}
