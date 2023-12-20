<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    BerkasPopembelian,
    Pmtpembelian_rinci,
    Pmtpembelian,
    Popembelian,
    Popembelian_rinci,
    Ri_rinci,
    Supplier,
    FakturToRelation,
    fakturpembelian,
    PaymentToFaktur,
    Ri,
    User
};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use PDF;
use DataTables;
use Carbon\Carbon;
use DB;

use Illuminate\Support\Facades\Http;

class PoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     if(auth()->user()->cannot('viewAny', Popembelian::class)) abort('403', 'access denied');

    //     $popembelian = Popembelian::with('rinci')->get();
    //     $nilai_po = 0;
    //     $payment = 0;
    //     $index = 0;
    //     foreach ($popembelian as $po) {
    //         $rinci = Popembelian_rinci::where('popembelian_id', $po->id)->get();
    //         $faktur_relation = FakturToRelation::where('po_id', $po->id)->get();

    //         foreach ($faktur_relation as $relation) {
    //             if (fakturpembelian::find($relation->faktur_id)->is_payment == 1) {
    //                 $payment += $relation->total_perfaktur;
    //             }
    //         }

    //         // if(!empty($faktur_relation)){
    //         //     if(fakturpembelian::find($faktur_relation->faktur_id)->is_payment == 1){
    //         //         $payment = PaymentToFaktur::where('po_id', $po->id)->sum('jumlah_bayar');
    //         //     }
    //         // }

    //         foreach ($rinci as $rinc) {
    //             $harga_disc = $rinc->harga - ($rinc->harga * ($rinc->dsc / 100));
    //             $nilai_po += $rinc->jumlah * $harga_disc;
    //         }
    //         $popembelian[$index]['nilai_po'] = $nilai_po;
    //         $popembelian[$index]['payment'] = $payment;
    //         $nilai_po = 0;
    //         $payment = 0;
    //         $index++;
    //     }


    //     return view('popembelian.index', compact(
    //         'popembelian'
    //     ));
    // }

    public function index(Request $request)
    {
        if (auth()->user()->cannot('viewAny', Popembelian::class)) abort('403', 'access denied');

        $popembelian = Popembelian::with('rinci')->where('status_delete', 0)->get();
        $nilai_po = 0;
        $payment = 0;
        $index = 0;
        foreach ($popembelian as $po) {
            $rinci = Popembelian_rinci::where('popembelian_id', $po->id)->get();
            $faktur_relation = FakturToRelation::where('po_id', $po->id)->get();

            foreach ($faktur_relation as $relation) {
                if (fakturpembelian::find($relation->faktur_id)->is_payment == 1) {
                    $payment += $relation->total_perfaktur;
                }
            }

            // if(!empty($faktur_relation)){
            //     if(fakturpembelian::find($faktur_relation->faktur_id)->is_payment == 1){
            //         $payment = PaymentToFaktur::where('po_id', $po->id)->sum('jumlah_bayar');
            //     }
            // }

            foreach ($rinci as $rinc) {
                $harga_disc = $rinc->harga - ($rinc->harga * ($rinc->dsc / 100));
                $nilai_po += $rinc->jumlah * $harga_disc;
            }
            $popembelian[$index]['nilai_po'] = $nilai_po;
            $popembelian[$index]['payment'] = $payment;
            $nilai_po = 0;
            $payment = 0;
            $index++;
        }

        if ($request->ajax()) {
            return datatables()->of($popembelian)

                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    return '<td>
                <a class="badge badge-light-secondary" href="' . url('admin/po/' . $row->id) . '"><i data-feather="eye"></i> Lihat</a>            
                </td>';
                })
                // ->editColumn('tanggal', function ($row) {
                //     return $row->created_at->format('Y/m/d');
                // })
                ->editColumn('item', function ($row) {
                    $rincian = Popembelian_rinci::where('popembelian_id', $row->id)->get();
                    $item = '';
                    foreach ($rincian as $rincis) {
                        if ($rincis->description != null) {
                            $nama_barang = $rincis->description;
                        } else {
                            $nama_barang = $rincis->barang->nama_barang;
                        }

                        $item .= '<span class="badge badge-pill badge-primary">' . $nama_barang . '</span>';
                    }

                    return $item;
                })

                ->editColumn('nomer_popembelian', function ($row) {
                    return $row->nomer_po;
                })
                // ->filterColumn('tanggal', function ($query, $keyword) {
                //     $query->whereRaw("DATE_FORMAT(tanggal,'%m/%d/%Y') LIKE ?", ["%$keyword%"]);
                // })
                ->editColumn('nilai_po', function ($row) {
                    return "Rp. " . number_format($row->nilai_po, 0, ',', '.');
                })
                ->editColumn('payment', function ($row) {
                    return "Rp. " . number_format($row->payment, 0, ',', '.');
                })
                ->editColumn('approval', function ($row) {
                    $nilai_po = 0;
                    $rinci = Popembelian_rinci::where('popembelian_id', $row->id)->get();
                    foreach ($rinci as $rinc) {
                        $harga_disc = $rinc->harga - ($rinc->harga * ($rinc->dsc / 100));
                        $nilai_po += $rinc->jumlah * $harga_disc;
                    }
                    $approve = '';
                    if ($row->approve_direktur == 0) {
                        $approve .= '<div style="width:150px" class="badge badge-light-warning">Menunggu Direktur</div>';
                    } elseif ($row->approve_direktur == 1) {
                        $approve .= '<div style="width:150px" class="badge badge-light-success">Approve Direktur</div>';
                    } elseif ($row->approve_direktur == 2) {
                        $approve .= '<div style="width:150px" class="badge badge-light-danger">Reject Direktur</div>';
                    }

                    if ($row->nilai_po > 5000000) {
                        if ($row->approve_komisaris == 0) {
                            $approve .= '<div style="width:150px" class="badge badge-light-warning">Menunggu Komisaris</div>';
                        } elseif ($row->approve_komisaris == 1) {
                            $approve .= '<div style="width:150px" class="badge badge-light-success">Approve Komisaris</div>';
                        } elseif ($row->approve_komisaris == 2) {
                            $approve .= '<div style="width:150px" class="badge badge-light-danger">Reject Komisaris</div>';
                        }
                    }

                    return $approve;
                })
                ->editColumn('barang', function ($row) {
                    if ($row->status == 1) {
                        $status = '<div style="width:150px" class="badge badge-light-warning">Diterima Sebagian</div>';
                    } elseif ($row->status == 2) {
                        $count_ri = Ri::where('po_id', $row->id)->count();
                        if ($count_ri > 0) {
                            $status = '<div style="width:150px" class="badge badge-light-success">Diterima</div>';
                        } else {
                            $status = '<div style="width:150px" class="badge badge-light-secondary">Tanpa Penerimaan</div>';
                        }
                    } else {
                        $status = '<div style="width:150px" class="badge badge-light-danger">Belum Diterima</div>';
                    }

                    return $status;
                })
                ->editColumn('faktur', function ($row) {
                    if ($row->status_faktur == 1) {
                        $status_faktur = '<div style="width:150px" class="badge badge-light-warning">Sebagian</div>';
                    } elseif ($row->status_faktur == 2) {
                        $status_faktur = '<div style="width:150px" class="badge badge-light-success">Lengkap</div>';
                    } else {
                        $status_faktur = '<div style="width:150px" class="badge badge-light-danger">Belum Dibuat</div>';
                    }

                    return $status_faktur;
                })
                ->rawColumns(['actions', 'nomer_popembelian', 'approval', 'barang', 'faktur', 'nilai_po', 'payment', 'item'])->make(true);
            // ->rawColumns(['actions'])
            // ->make(true);
        }

        return view('popembelian.index', compact(
            'popembelian'
        ));
    }

    public function exportPDF()
    {
        $popembelian = Popembelian::with('rinci')->get();
        $nilai_po = 0;
        $payment = 0;
        $index = 0;
        foreach ($popembelian as $po) {
            $rinci = Popembelian_rinci::where('popembelian_id', $po->id)->get();
            $faktur_relation = FakturToRelation::where('po_id', $po->id)->get();

            foreach ($faktur_relation as $relation) {
                if (fakturpembelian::find($relation->faktur_id)->is_payment == 1) {
                    $payment += $relation->total_perfaktur;
                }
            }

            // if(!empty($faktur_relation)){
            //     if(fakturpembelian::find($faktur_relation->faktur_id)->is_payment == 1){
            //         $payment = PaymentToFaktur::where('po_id', $po->id)->sum('jumlah_bayar');
            //     }
            // }

            foreach ($rinci as $rinc) {
                $harga_disc = $rinc->harga - ($rinc->harga * ($rinc->dsc / 100));
                $nilai_po += $rinc->jumlah * $harga_disc;
            }
            $popembelian[$index]['nilai_po'] = $nilai_po;
            $popembelian[$index]['payment'] = $payment;
            $nilai_po = 0;
            $payment = 0;
            $index++;
        }
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('popembelian.exportpdf', compact('popembelian'));
        return $pdf->download('Pesanan Pembelian.pdf');
    }

    public function printPDF()
    {
        $popembelian = Popembelian::with('rinci')->get();
        $nilai_po = 0;
        $payment = 0;
        $index = 0;
        foreach ($popembelian as $po) {
            $rinci = Popembelian_rinci::where('popembelian_id', $po->id)->get();
            $faktur_relation = FakturToRelation::where('po_id', $po->id)->get();

            foreach ($faktur_relation as $relation) {
                if (fakturpembelian::find($relation->faktur_id)->is_payment == 1) {
                    $payment += $relation->total_perfaktur;
                }
            }

            // if(!empty($faktur_relation)){
            //     if(fakturpembelian::find($faktur_relation->faktur_id)->is_payment == 1){
            //         $payment = PaymentToFaktur::where('po_id', $po->id)->sum('jumlah_bayar');
            //     }
            // }

            foreach ($rinci as $rinc) {
                $harga_disc = $rinc->harga - ($rinc->harga * ($rinc->dsc / 100));
                $nilai_po += $rinc->jumlah * $harga_disc;
            }
            $popembelian[$index]['nilai_po'] = $nilai_po;
            $popembelian[$index]['payment'] = $payment;
            $nilai_po = 0;
            $payment = 0;
            $index++;
        }
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('popembelian.exportpdf', compact('popembelian'));
        return $pdf->stream();
    }


    public function create()
    {
        if (auth()->user()->cannot('create', Popembelian::class)) abort('403', 'access denied');

        $suppliers = Supplier::where('aktif', 1)->get();
        // $pmt = Pmtpembelian::where(['approve_direktur' => 1, 'status' => 0])->get();
        $pmt = Pmtpembelian::where(['approve_direktur' => 1])->get();
        //return $suppliers;
        return view('popembelian.create', compact(
            'suppliers',
            'pmt'
        ));
    }


    public function _store(Request $request)
    {
        if ($request->pmtpembelian_id == 0 || $request->supplier_id == 0) {
            return redirect()->back()->with('error', 'Pilih Supplier & Permintaan Pembelian Terlebih Dahulu!');
        }
        // validasi untuk pembuatan Purchase Order
        $request->validate([
            'supplier_id' => 'required',
            'nomer_po' => 'required|unique:popembelian,nomer_po',
            'tanggal_po' => 'required',
            'tujuan_pengiriman' => 'required',
            'berkas_1' => 'file|mimes:doc,docx,pdf,jpeg,png|max:2000',
            'berkas_2' => 'file|mimes:doc,docx,pdf,jpeg,png|max:2000',
            'berkas_3' => 'file|mimes:doc,docx,pdf,jpeg,png|max:2000',
            'berkas_4' => 'file|mimes:doc,docx,pdf,jpeg,png|max:2000',
            'berkas_5' => 'file|mimes:doc,docx,pdf,jpeg,png|max:2000',
        ]);

        // Buat Puurchase Order
        $popembelian = new Popembelian();
        $popembelian->nomer_po = $request->nomer_po;
        $popembelian->tanggal_po = $request->tanggal_po;
        $popembelian->tujuan_pengiriman = $request->tujuan_pengiriman;
        $popembelian->keterangan = $request->keterangan;
        $popembelian->pmtpembelian_id = $request->pmtpembelian_id;
        $popembelian->supplier_id = $request->supplier_id;
        $popembelian->is_tax = $request->is_tax;
        $popembelian->user_id = Auth::id();
        $popembelian->save();

        // Buat Purchase Order 
        Pmtpembelian::where('id', $request->pmtpembelian_id)->update(['status' => 1]);

        foreach (Request('id') as $id) {
            $popembelian_rinci = new Popembelian_rinci();
            $popembelian_rinci->jumlah = Request('jumlah')[$id];
            $popembelian_rinci->harga = Request('harga')[$id];
            $popembelian_rinci->popembelian_id = $popembelian->id;
            $popembelian_rinci->barang_id = Request('barang_id')[$id];
            $popembelian_rinci->dsc = Request('dsc')[$id];
            $popembelian_rinci->user_id = Auth::id();
            $popembelian_rinci->save();
        }

        // berkas Purchase Order
        $berkas1 = '';
        $berkas2 = '';
        $berkas3 = '';
        $berkas4 = '';
        $berkas5 = '';

        if ($request->hasFile('berkas_1')) {
            $file = $request->file('berkas_1');
            $originalName1 = $file->getClientOriginalName();
            $berkas1 = $originalName1;
            $file->move('uploads/popembelian', $berkas1);
        }
        if ($request->hasFile('berkas_2')) {
            $file = $request->file('berkas_2');
            $originalName2 = $file->getClientOriginalName();
            $berkas2 = $originalName2;
            $file->move('uploads/popembelian', $berkas2);
        }
        if ($request->hasFile('berkas_3')) {
            $file = $request->file('berkas_3');
            $originalName3 = $file->getClientOriginalName();
            $berkas3 = $originalName3;
            $file->move('uploads/popembelian', $berkas3);
        }
        if ($request->hasFile('berkas_4')) {
            $file = $request->file('berkas_4');
            $originalName4 = $file->getClientOriginalName();
            $berkas4 = $originalName4;
            $file->move('uploads/popembelian', $berkas4);
        }
        if ($request->hasFile('berkas_5')) {
            $file = $request->file('berkas_5');
            $originalName5 = $file->getClientOriginalName();
            $berkas5 = $originalName5;
            $file->move('uploads/popembelian', $berkas5);
        }

        $berkasPopembelian = new BerkasPopembelian();
        $berkasPopembelian->popembelian_id = $popembelian->id;
        $berkasPopembelian->berkas_1 = $berkas1;
        $berkasPopembelian->berkas_2 = $berkas2;
        $berkasPopembelian->berkas_3 = $berkas3;
        $berkasPopembelian->berkas_4 = $berkas4;
        $berkasPopembelian->berkas_5 = $berkas5;
        $berkasPopembelian->save();

        return redirect('/admin/po')->with('success', 'Berhasil menambah data');
    }

    public function store(Request $request)
    {
        if (auth()->user()->cannot('create', Popembelian::class)) abort('403', 'access denied');

        if ($request->pmtpembelian_id == 0 || $request->supplier_id == 0) {
            return redirect()->back()->with('error', 'Pilih Supplier & Permintaan Pembelian Terlebih Dahulu!');
        }
        // validasi untuk pembuatan Purchase Order
        $request->validate([
            'supplier_id' => 'required',
            'nomer_po' => 'required|unique:popembelian,nomer_po',
            'tanggal_po' => 'required',
            'tujuan_pengiriman' => 'required',
            'berkas_1' => 'file|mimes:doc,docx,pdf,jpeg,png|max:2000',
            'berkas_2' => 'file|mimes:doc,docx,pdf,jpeg,png|max:2000',
            'berkas_3' => 'file|mimes:doc,docx,pdf,jpeg,png|max:2000',
            'berkas_4' => 'file|mimes:doc,docx,pdf,jpeg,png|max:2000',
            'berkas_5' => 'file|mimes:doc,docx,pdf,jpeg,png|max:2000',
        ]);

        // Buat Puurchase Order
        $popembelian = new Popembelian();
        $popembelian->nomer_po = $request->nomer_po;
        $popembelian->tanggal_po = $request->tanggal_po;
        $popembelian->tujuan_pengiriman = $request->tujuan_pengiriman;
        $popembelian->keterangan = $request->keterangan;
        $popembelian->pmtpembelian_id = $request->pmtpembelian_id;
        // if(Pmtpembelian::find($request->pmtpembelian_id)->type != 1){
        //     $popembelian->status = 2;
        // }
        if ($request->ri == 0) {
            $popembelian->status = 2;
        }
        $popembelian->supplier_id = $request->supplier_id;
        $popembelian->is_tax = $request->is_tax;
        if ($request->pph != '') {
            $popembelian->pph = $request->pph;
        }
        if ($request->pajak_lain != '') {
            $popembelian->pajak_lain = $request->pajak_lain;
        }
        $popembelian->user_id = Auth::id();
        $popembelian->save();
        $countporinci = 0;

        // Kirim notif ke telegram dengan mengirim parameter
        // nomer permintaan, tanggal permintaan, dan user yang membuat
        // $clientTelegram = new \GuzzleHttp\Client();
        // $body = [
        //     'nomer' => $popembelian->nomer_po,
        //     'tanggal' => $popembelian->tanggal_po,
        //     'user' => Auth::user()->name,
        //     'api_token' => 'XFHF5f0u6XNXN3lLfdh2K95uTV9MdHinc4df3AREBrCttXVtWAPF8l5HcIl5',
        //     'url' => url('https://miresmahisa.com/admin/po/') . $popembelian->id,
        // ];
        // $response = $clientTelegram->post('http://bot-telegram.miresmahisa.com/api/v1/send/notif/permintaan-pembelian', 
        //                 ['form_params' => $body]);

        // End of Notifikasi Telegram
        // Kirim notifikasi ke Grup Mires Mahisa
        // menggunakan api telegram
        $urlApiTelegram = config('telegram_config.telegram_api_url') . 'api/v1/send_notif_telegram/purchase/send_new_purchase_order';
        $body = [
            'api_token' => config('telegram_config.telegram_api_token'),
            'nomerPo' => $popembelian->nomer_po,
            'tanggal' => $popembelian->tanggal_po,
            'user' => Auth::user()->name,
            'url' => url('https://miresmahisa.com/admin/po/') . $popembelian->id,
        ];
        $response = Http::post($urlApiTelegram, $body);

        // update pmt
        $countpmtrinci = count(Pmtpembelian_rinci::where(['pmtpembelian_id' => $request->pmtpembelian_id, 'is_po' => 0])->get());

        foreach (Request('id') as $id) {
            $popembelian_rinci = new Popembelian_rinci();
            if (Request('jumlah')[$id] != '' && !empty(Request('jumlah')[$id]) && Request('jumlah')[$id] != 0) {
                $popembelian_rinci->jumlah = Request('jumlah')[$id];
                if (Request('harga')[$id] != '' || !empty(Request('harga')[$id])) {
                    $popembelian_rinci->harga = Request('harga')[$id];
                } else {
                    $popembelian_rinci->harga = 0;
                }
                $popembelian_rinci->popembelian_id = $popembelian->id;
                $popembelian_rinci->barang_id = Request('barang_id')[$id];
                $popembelian_rinci->dsc = Request('dsc')[$id];
                if (Request('desc')[$id] != '' || Request('desc')[$id] != null || !empty(Request('desc')[$id])) {
                    $popembelian_rinci->description = Request('desc')[$id];
                }
                $popembelian_rinci->user_id = Auth::id();
                if ($request->ri == 0) {
                    $popembelian_rinci->is_received = 1;
                }
                $popembelian_rinci->save();

                // $pmtpembelian_rinci = Pmtpembelian_rinci::find($id);
                // $pmtpembelian_rinci->is_po = 1;
                // $pmtpembelian_rinci->save();
                // $countporinci++;
            }
        }

        // if($countporinci == $countpmtrinci){
        //     Pmtpembelian::where('id', $request->pmtpembelian_id)->update(['status' => 1]);
        // }

        // berkas Purchase Order

        // buat data berkas
        if (!empty($request->berkas)) {
            $totalBerkas = count($request->berkas);
            $collectNamaBerkas = [];

            for ($i = 0; $i < $totalBerkas; $i++) {
                if ($request->hasFile('berkas.' . $i)) {
                    $file = $request->file('berkas')[$i];
                    $originalName = $file->getClientOriginalName();
                    $berkas = str_replace(" ", "", $originalName);
                    $file->move('uploads/popembelian', $berkas);

                    array_push($collectNamaBerkas, $berkas);
                }
            }

            $saveBerkas = count($collectNamaBerkas);

            $berkasPopembelian = new BerkasPopembelian();
            $berkasPopembelian->popembelian_id = $popembelian->id;

            for ($i = 0; $i < $saveBerkas; $i++) {
                $flag = $i + 1;
                $var = "berkas_" . $flag;
                $berkasPopembelian->$var = $collectNamaBerkas[$i];
            }
            $berkasPopembelian->save();
        }

        return redirect('/admin/po')->with('success', 'Berhasil menambah data');
    }


    public function show($id)
    {
        if (auth()->user()->cannot('view', Popembelian::class)) abort('403', 'access denied');

        $popembelian = Popembelian::with('berkas')->find($id);
        $supplier = Supplier::all();

        // return response()->json($popembelian);

        return view('popembelian.detail', compact('popembelian', 'supplier'));
    }


    public function edit(Popembelian $po)
    {
        if (auth()->user()->cannot('update', Popembelian::class)) abort('403', 'access denied');

        $popembelian = $po;
        $suppliers = Supplier::where('aktif', 1)->get();
        $data = [
            'popembelian' => $popembelian,
            'supplier' => $suppliers
        ];
        return view('popembelian.edit', $data);
    }


    public function update(Request $request, Popembelian $po)
    {
        if (auth()->user()->cannot('update', Popembelian::class)) abort('403', 'access denied');

        $request->validate([
            'nomer_po' => 'required|unique:popembelian,nomer_po,' . $po->id,
            'tanggal_po' => 'required',
            'tujuan_pengiriman' => 'required',
        ]);
        $po = Popembelian::find($po->id);
        $po->nomer_po = $request->nomer_po;
        $po->tanggal_po = $request->tanggal_po;
        $po->tujuan_pengiriman = $request->tujuan_pengiriman;
        $po->keterangan = $request->keterangan;
        $po->is_tax = $request->is_tax;
        $po->save();
        return redirect('admin/po/' . $po->id)->with('success', 'Berhasil mengubah data');
    }


    public function destroy(Popembelian $popembelian)
    {
        if (auth()->user()->cannot('delete', Popembelian::class)) abort('403', 'access denied');

        $popembelian = Popembelian::find($popembelian->id);
        // return response()->json($popembelian);
        if ($popembelian->approve_direktur == 1 && $popembelian->approve_komisaris == 1) {
            $receive = Ri::where('po_id', $popembelian->id)->first();
            if ($popembelian->status_faktur != 0 || !is_null($receive)) {
                return back()->with('fail', 'PO telah di setujui oleh direktur dan komisaris, tidak bisa dihapus');
            }
        }

        // Update status PMTpembelian jadi 0
        $pmtpembelian = Pmtpembelian::find($popembelian->pmtpembelian_id);
        $pmtpembelian->status = 0;
        $pmtpembelian->save();

        Pmtpembelian_rinci::where('pmtpembelian_id', $popembelian->pmtpembelian_id)->update(['is_po' => 0]);

        // hapus berkas PO
        $berkaspo = BerkasPopembelian::where('popembelian_id', $popembelian->id)->first();
        if (!empty($berkaspo)) {
            if ($berkaspo->berkas_1 != '') {
                File::delete('uploads/popembelian/' . $berkaspo->berkas_1);
            }
            if ($berkaspo->berkas_2 != '') {
                File::delete('uploads/popembelian/' . $berkaspo->berkas_2);
            }
            if ($berkaspo->berkas_3 != '') {
                File::delete('uploads/popembelian/' . $berkaspo->berkas_3);
            }
            if ($berkaspo->berkas_4 != '') {
                File::delete('uploads/popembelian/' . $berkaspo->berkas_4);
            }
            if ($berkaspo->berkas_5 != '') {
                File::delete('uploads/popembelian/' . $berkaspo->berkas_5);
            }
            $berkaspo->delete();
        }

        // hapus popembelian
        $popembelian->delete();

        return redirect('/admin/po');
    }

    // Approval PO
    public function approval($id)
    {
        if (auth()->user()->cannot('update', Popembelian::class)) abort('403', 'access denied');

        // Ambil ID PO Pembelian
        $popembelian = Popembelian::find($id);
        // Ambil Grand Total Rinci
        $po_rinci = Popembelian_rinci::where('popembelian_id', $id)->get();
        $grandtotal = 0;
        foreach ($po_rinci as $rinci) {
            $total = $rinci->jumlah * ($rinci->harga - ($rinci->harga * $rinci->dsc / 100));
            $grandtotal += $total;
        }
        if ($popembelian->is_tax == 1) {
            $grandtotal = $grandtotal + ($grandtotal * 10 / 100);
        }
        // Jika Yang Approve Direktur
        if (Auth::user()->level_id == 2) {
            $popembelian->approve_direktur = request('approve_direktur');
            $popembelian->note_direktur = request('note_direktur');

            // Approval Kondisi
            if ($popembelian->approve_direktur == 1) {
                $approvalAction = 'DISETUJUI';
            } else if ($popembelian->approve_direktur == 2) {
                $approvalAction = 'TIDAK DISETUJUI';
            }

            // Jika Dibawah 5 Juta
            if ($grandtotal <= 5000000) {
                $popembelian->approve_komisaris = 1;
            }
            $popembelian->save();

            // Kirim notifikasi ke telegram purchasing
            $urlApiTelegram = config('telegram_config.telegram_api_url') . 'api/v1/send_notif_telegram/purchase/send_approval_purchase_order_direktur';
            $body = [
                'api_token' => config('telegram_config.telegram_api_token'),
                'nomerPo' => $popembelian->nomer_po,
                'tanggal' => $popembelian->tanggal_po,
                'catatan' => $popembelian->note_direktur,
                'approvalAction' => $approvalAction,
                'url' => url('https://miresmahisa.com/admin/po/') . $popembelian->id,
            ];
            $response = Http::post($urlApiTelegram, $body);

            // Jika Yang Approve Komisaris
        } elseif (Auth::user()->level_id == 3) {
            $popembelian->approve_komisaris = request('approve_komisaris');
            $popembelian->note_komisaris = request('note_komisaris');
            $popembelian->id_komisaris = Auth::user()->id;
            $popembelian->save();

            // Approval Kondisi
            if ($popembelian->approve_komisaris == 1) {
                $approvalAction = 'DISETUJUI';
            } else if ($popembelian->approve_komisaris == 2) {
                $approvalAction = 'TIDAK DISETUJUI';
            }

            // Kirim notifikasi ke telegram purchasing
            $urlApiTelegram = config('telegram_config.telegram_api_url') . 'api/v1/send_notif_telegram/purchase/send_approval_purchase_order_komisaris';
            $body = [
                'api_token' => config('telegram_config.telegram_api_token'),
                'nomerPo' => $popembelian->nomer_po,
                'tanggal' => $popembelian->tanggal_po,
                'catatan' => $popembelian->note_komisaris,
                'approvalAction' => $approvalAction,
                'url' => url('https://miresmahisa.com/admin/po/') . $popembelian->id,
            ];
            $response = Http::post($urlApiTelegram, $body);
        }

        return redirect('/admin/po')->with('success', 'Berhasil Menetapkan Approval');
    }

    //PO belum diterima
    public function belum_diterima($id_supplier)
    {
        if (auth()->user()->cannot('view', Popembelian::class)) abort('403', 'access denied');

        $data = Popembelian::where(['approve_direktur' => 1, 'approve_komisaris' => 1, 'supplier_id' => $id_supplier])->where('status', '!=', 2)->get();

        return response()->json(['data' => $data]);
    }

    //Rincian PO
    public function get_po_rinci_by_id($id_po)
    {
        if (auth()->user()->cannot('view', Popembelian::class)) abort('403', 'access denied');

        $data = Popembelian_rinci::where(['popembelian_id' => $id_po, 'is_received' => 0])->with('barang')->get();
        // Cek Apakah Ada Kekurangan Di RI Rinci
        $no = 0;
        foreach ($data as $val) {
            if (!empty(Ri_rinci::where('po_rinci_id', $val->id)->get())) {
                $ri_rinci = Ri_rinci::where('po_rinci_id', $val->id)->get();
                $jumlah_rinci = 0;
                foreach ($ri_rinci as $rinci) {
                    $jumlah_rinci += $rinci->qty;
                }
                $data[$no++]['jumlah_datang'] = $jumlah_rinci;
            }
        }

        $po = Popembelian::find($id_po);
        $data = [
            'data' => $data,
            'supplier' => $po->supplier->nama_supplier,
            'supplier_id' => $po->supplier_id,
            'tanggal' => $po->tanggal_po
        ];
        return response()->json($data);
    }
    public function get_pmtpembelian_rinci_id($id)
    {
        if (auth()->user()->cannot('view', Popembelian::class)) abort('403', 'access denied');

        $data = Pmtpembelian_rinci::where('pmtpembelian_id', $id)->with('barang')->get();
        return response()->json($data);
    }
    public function print($id)
    {
        if (auth()->user()->cannot('view', Popembelian::class)) abort('403', 'access denied');

        $cek = Popembelian::where('id', $id)->where(['approve_direktur' => 1, 'approve_komisaris' => 1]);

        if ($cek->first()) {
            $po_rinci = Popembelian_rinci::where('popembelian_id', $id)->get();
            $po = Popembelian::find($id);
            $supplier = Supplier::find($po->supplier_id);

            $direktur = User::where('level_id', 2)->first();

            $status = 'live';

            if ($status == "live") {
                if ($po->id_komisaris != null) {
                    $komisaris_signature = 'http://miresmahisa.com/uploads/signature/' . $po->komisaris_approve->signature;
                    $komisaris = $po->komisaris_approve->name;
                } else {
                    $komisaris_signature = '';
                    $komisaris = '';
                }


                $purchasing_signature = 'http://miresmahisa.com/uploads/signature/' . $po->purchasing_po->signature;
                $directur_signature = 'http://miresmahisa.com/uploads/signature/' . $direktur->signature;
            } else if ($status == "dev") {
                if ($po->id_komisaris != null) {
                    $komisaris_signature = url('uploads/signature/' . $po->komisaris_approve->signature);
                    $komisaris = $po->komisaris_approve->name;
                } else {
                    $komisaris_signature = '';
                    $komisaris = '';
                }

                $purchasing_signature = url('uploads/signature/' . $po->purchasing_po->signature);
                $directur_signature = url('uploads/signature/' . $direktur->signature);

                return view('popembelian.print', compact('po_rinci', 'po', 'supplier', 'purchasing_signature', 'komisaris_signature', 'directur_signature', 'komisaris'));
            }

            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('popembelian.print', compact('po_rinci', 'po', 'supplier', 'purchasing_signature', 'komisaris_signature', 'directur_signature', 'komisaris'));
            //return $pdf->download('FORM DELIVERY ORDER (SO).pdf');

            return $pdf->stream();
        }

        return view('components.print_belum_approve');
    }

    public function printNonTtd($id)
    {
        if (auth()->user()->cannot('view', Popembelian::class)) abort('403', 'access denied');

        $cek = Popembelian::where('id', $id)->where(['approve_direktur' => 1, 'approve_komisaris' => 1]);

        if ($cek->first()) {
            $po_rinci = Popembelian_rinci::where('popembelian_id', $id)->get();
            $po = Popembelian::find($id);
            $supplier = Supplier::find($po->supplier_id);

            $direktur = User::where('level_id', 2)->first();

            $status = 'live';

            if ($status == "live") {
                if ($po->id_komisaris != null) {
                    $komisaris_signature = 'http://miresmahisa.com/uploads/signature/' . $po->komisaris_approve->signature;
                    $komisaris = $po->komisaris_approve->name;
                } else {
                    $komisaris_signature = '';
                    $komisaris = '';
                }


                $purchasing_signature = 'http://miresmahisa.com/uploads/signature/' . $po->purchasing_po->signature;
                $directur_signature = 'http://miresmahisa.com/uploads/signature/' . $direktur->signature;
            } else if ($status == "dev") {
                if ($po->id_komisaris != null) {
                    $komisaris_signature = url('uploads/signature/' . $po->komisaris_approve->signature);
                    $komisaris = $po->komisaris_approve->name;
                } else {
                    $komisaris_signature = '';
                    $komisaris = '';
                }

                $purchasing_signature = url('uploads/signature/' . $po->purchasing_po->signature);
                $directur_signature = url('uploads/signature/' . $direktur->signature);

                return view('popembelian.print-nonttd', compact('po_rinci', 'po', 'supplier', 'purchasing_signature', 'komisaris_signature', 'directur_signature', 'komisaris'));
            }

            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('popembelian.print-nonttd', compact('po_rinci', 'po', 'supplier', 'purchasing_signature', 'komisaris_signature', 'directur_signature', 'komisaris'));
            //return $pdf->download('FORM DELIVERY ORDER (SO).pdf');

            return $pdf->stream();
        }

        return view('components.print_belum_approve');
    }
}
