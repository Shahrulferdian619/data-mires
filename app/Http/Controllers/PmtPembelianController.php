<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\{
    Pmtpembelian,
    Pmtpembelian_rinci,
    Pmtpembelian_rinci_temp,
    Barang,
    BerkasPmtpembelian,
    Popembelian,
    Supplier,
    User,

    TelegramNotif
};

use App\Http\Requests\PmtpembelianFormRequest;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PDF;
use DataTables;
use Carbon\Carbon;
use DB;
use NotificationChannels\Telegram\Telegram;

use Illuminate\Support\Facades\Http;

class PmtPembelianController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(Request $request)
    {
        if (auth()->user()->cannot('viewAny', Pmtpembelian::class)) abort('403', 'access denied');

        //$pmtpembelians = Pmtpembelian::all();
        $pmtpembelians = Pmtpembelian::where('status_delete', 0)->get();

        if ($request->ajax()) {
            return datatables()->of($pmtpembelians)

                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    return '<td>
                <a class="badge badge-light-secondary" href="' . route('admin.pmtpembelian.show', $row->id) . '"><i data-feather="eye"></i> Lihat</a>            
                </td>';
                })
                // ->editColumn('tanggal', function ($row) {
                //     return $row->created_at->format('Y/m/d');
                // })
                ->editColumn('tanggal', function ($row) {
                    return $row->tanggal ? with(new Carbon($row->tanggal))->format('d-m-Y') : '';;
                })
                // ->filterColumn('tanggal', function ($query, $keyword) {
                //     $query->whereRaw("DATE_FORMAT(tanggal,'%m/%d/%Y') LIKE ?", ["%$keyword%"]);
                // })
                ->editColumn('approve_direktur', function ($row) {
                    if ($row->approve_direktur == 0) {
                        $approve_direktur = '<div class="badge badge-light-warning">Belum disetujui</div>';
                    } elseif ($row->approve_direktur == 1) {
                        $approve_direktur = '<div class="badge badge-light-success">Sudah disetujui</div>';
                    } elseif ($row->approve_direktur == 2) {
                        $approve_direktur = '<div class="badge badge-light-danger">Tidak Disetujui</div>';
                    } else {
                        $approve_direktur = "-";
                    }
                    return $approve_direktur;
                })
                ->rawColumns(['actions', 'tanggal', 'approve_direktur'])->make(true);
            // ->rawColumns(['actions'])
            // ->make(true);
        }

        return view('pmtpembelian.index', compact(
            'pmtpembelians'
        ));
    }

    public function exportPDF()
    {
        $pmtpembelians = Pmtpembelian::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('pmtpembelian.exportpdf', compact('pmtpembelians'));
        return $pdf->download('Permintaan Pembelian.pdf');
    }

    public function printPDF()
    {
        $pmtpembelians = Pmtpembelian::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('pmtpembelian.exportpdf', compact('pmtpembelians'));
        return $pdf->stream();
    }


    public function create($tipe)
    {
        // Generate nomer purchase request otomatis
        $getLastNoPR = substr(Pmtpembelian::latest()->first()->nomer_pmtpembelian, 3, 3);

        $bulan = bulan_romawi(date('m'));
        //$count_pmt = Pmtpembelian::whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->count();
        $count = str_pad($getLastNoPR + 1, 3, "0", STR_PAD_LEFT);

        $pmt_number = "PR-" . $count . "/" . $bulan . "/" . date('Y');

        if (auth()->user()->cannot('create', Pmtpembelian::class)) abort('403', 'access denied');

        if ($tipe == '1') {
            $barang = Barang::where('type', 1)->get();
        } elseif ($tipe == '2') {
            $barang = Barang::where('type', 2)->get();
        } elseif ($tipe == '3') {
            $barang = Barang::where('type', 3)->get();
        } elseif ($tipe == '4') {
            $barang = Barang::where('type', 4)->get();
        } else {
            return abort(403);
        }

        return view('pmtpembelian.create', [
            'barangs' => $barang,
            'type' => $tipe,
            'pmt' => Pmtpembelian::all(),
            'pmt_number' => $pmt_number
        ]);
    }


    public function _store(Request $request)
    {
        if (isset($_POST['tambah_rinci'])) {

            // validasi saat menambahkan barang

            $request->validate([
                'barang_id' => 'required',
                'qty' => 'required|integer',
                'harga' => 'required|integer',
                'note' => 'required',
                'nomer_pmtpembelian' => 'required',
                'tanggal' => 'required|date',
            ]);

            // Temukan barang
            $barang = Barang::find($request->barang_id);

            // Membuat array
            $rincian_barang[] = [
                'barang_id' => $request->barang_id,
                'nama_barang' => $barang->nama_barang,
                'qty' => $request->qty,
                'harga' => $request->harga,
                'note' => $request->note
            ];

            // cek apakah sudah ada session
            if (session()->has('pmtpembelian_rinci')) {
                // jika suda ada tambahkan data barang ke session
                session()->push('pmtpembelian_rinci', [
                    'barang_id' => $request->barang_id,
                    'nama_barang' => $barang->nama_barang,
                    'qty' => $request->qty,
                    'harga' => $request->harga,
                    'note' => $request->note,
                ]);
            } else {
                // jika belum ada maka buat dan masukkan data barang
                session()->put('pmtpembelian_rinci', $rincian_barang);
            }

            // buat session data informasi permintaan pembelian
            $dataPMTPembelian = [
                'nomer_pmtpembelian' => $request->nomer_pmtpembelian,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
                'reff_pmt' => $request->pmt_id,
            ];

            // cek apakah sudah ada session
            if (session()->has('pmtpembelian')) {
                // jika sudah ada, timpa session data lama dengan data baru
                session()->put('pmtpembelian', $dataPMTPembelian);
            } else {
                // jika belum ada, maka buat session baru
                session()->put('pmtpembelian', $dataPMTPembelian);
            }
            return back()->with('success', 'Berhasil menambahkan data rinci');
        }

        if (isset($_POST['tambah_pmtpembelian'])) {
            // validasi untuk buat permintaan pembelian
            $request->validate([
                'nomer_pmtpembelian' => 'required|unique:pmtpembelian,nomer_pmtpembelian',
                'tanggal' => 'required|date',
                'berkas_1' => 'file|mimes:doc,docx,pdf,jpeg,jpg,png|max:2000',
                'berkas_2' => 'file|mimes:doc,docx,pdf,jpeg,jpg,png|max:2000',
                'berkas_3' => 'file|mimes:doc,docx,pdf,jpeg,jpg,png|max:2000',
                'berkas_4' => 'file|mimes:doc,docx,pdf,jpeg,jpg,png|max:2000',
                'berkas_5' => 'file|mimes:doc,docx,pdf,jpeg,jpg,png|max:2000',
            ]);

            // buat data permintaan pembelian
            $data = new Pmtpembelian();
            $data->user_id = Auth::id();
            if (!empty($request->pmt_id)) {
                $data->pmtrefference_id = $request->pmt_id;
            }
            $data->nomer_pmtpembelian = $request->nomer_pmtpembelian;
            $data->tanggal = $request->tanggal;
            $data->keterangan = $request->keterangan;
            $data->save();

            // buat data permintaan pembelian rinci
            foreach (session()->get('pmtpembelian_rinci') as $key) {
                $datarinci = new Pmtpembelian_rinci();
                $datarinci->barang_id = $key['barang_id'];
                $datarinci->qty = $key['qty'];
                $datarinci->harga = $key['harga'];

                $datarinci->note = $key['note'];
                $datarinci->pmtpembelian_id = $data->id;
                $datarinci->user_id = Auth::id();
                $datarinci->save();
            }

            // hapus session
            session()->forget('pmtpembelian_rinci');
            session()->forget('pmtpembelian');

            // buat data berkas
            $berkas1 = '';
            $berkas2 = '';
            $berkas3 = '';
            $berkas4 = '';
            $berkas5 = '';

            if ($request->hasFile('berkas_1')) {
                $file = $request->file('berkas_1');
                $originalName1 = $file->getClientOriginalName();
                $berkas1 = $originalName1;
                $file->move('uploads/pmtpembelian', $berkas1);
            }
            if ($request->hasFile('berkas_2')) {
                $file = $request->file('berkas_2');
                $originalName2 = $file->getClientOriginalName();
                $berkas2 = $originalName2;
                $file->move('uploads/pmtpembelian', $berkas2);
            }
            if ($request->hasFile('berkas_3')) {
                $file = $request->file('berkas_3');
                $originalName3 = $file->getClientOriginalName();
                $berkas3 = $originalName3;
                $file->move('uploads/pmtpembelian', $berkas3);
            }
            if ($request->hasFile('berkas_4')) {
                $file = $request->file('berkas_4');
                $originalName4 = $file->getClientOriginalName();
                $berkas4 = $originalName4;
                $file->move('uploads/pmtpembelian', $berkas4);
            }
            if ($request->hasFile('berkas_5')) {
                $file = $request->file('berkas_5');
                $originalName5 = $file->getClientOriginalName();
                $berkas5 = $originalName5;
                $file->move('uploads/pmtpembelian', $berkas5);
            }

            // buat berkas permintaan pembelian
            $berkasPmtpembelian = new BerkasPmtpembelian();
            $berkasPmtpembelian->pmtpembelian_id = $data->id;
            $berkasPmtpembelian->berkas_1 = $berkas1;
            $berkasPmtpembelian->berkas_2 = $berkas2;
            $berkasPmtpembelian->berkas_3 = $berkas3;
            $berkasPmtpembelian->berkas_4 = $berkas4;
            $berkasPmtpembelian->berkas_5 = $berkas5;
            $berkasPmtpembelian->save();

            return redirect('admin/pmtpembelian')->with('success', 'Permintaan pembelian berhasil dibuat');
        }
    }

    public function store(Request $request)
    {

        if (auth()->user()->cannot('create', Pmtpembelian::class)) abort('403', 'access denied');

        $pmt = Pmtpembelian::whereNomerPmtpembelian($request->nomer_pmtpembelian)->first();

        if (!empty($pmt)) {
            return response()->json(['errors' => "Error! Nomer sudah digunakan, Masukan nomer lainnya.", 'nomer' => true]);
        }

        // buat data permintaan pembelian
        $data = new Pmtpembelian();
        $data->user_id = Auth::id();
        if (!empty($request->pmt_id)) {
            $data->pmtrefference_id = $request->pmt_id;
        }
        $data->nomer_pmtpembelian = $request->nomer_pmtpembelian;
        $data->tanggal = $request->tanggal;
        $data->type = $request->type;
        $data->keterangan = $request->keterangan;
        $data->save();

        // Kirim notif ke telegram dengan mengirim parameter
        // nomer permintaan, tanggal permintaan, dan user yang membuat
        // $clientTelegram = new \GuzzleHttp\Client();
        // $body = [
        //     'nomer' => $data->nomer_pmtpembelian,
        //     'tanggal' => $data->tanggal,
        //     'user' => Auth::user()->name,
        //     'api_token' => 'XFHF5f0u6XNXN3lLfdh2K95uTV9MdHinc4df3AREBrCttXVtWAPF8l5HcIl5',
        //     'url' => url('https://miresmahisa.com/admin/pmtpembelian/') . $data->id,
        // ];
        // $response = $clientTelegram->post('http://bot-telegram.miresmahisa.com/api/v1/send/notif/permintaan-pembelian', 
        //                 ['form_params' => $body]);

        // End of Notifikasi Telegram
        $urlApiTelegram = config('telegram_config.telegram_api_url') . 'api/v1/send_notif_telegram/purchase/send_new_purchase_request';
        $body = [
            'api_token' => config('telegram_config.telegram_api_token'),
            'nomerPr' => $data->nomer_pmtpembelian,
            'tanggal' => $data->tanggal,
            'user' => Auth::user()->name,
            'url' => url('https://miresmahisa.com/admin/pmtpembelian/') . $data->id,
        ];
        $response = Http::post($urlApiTelegram, $body);

        // buat data permintaan pembelian rinci
        $rinci = count($request->barang_id);
        for ($i = 0; $i < $rinci; $i++) {
            if ($data->type == 4) {
                $desc = $request->description[$i];
            } else {
                $desc = null;
            }
            $datarinci = new Pmtpembelian_rinci();
            $datarinci->barang_id = $request->barang_id[$i];
            $datarinci->qty = $request->qty[$i];
            $datarinci->harga = 0;
            $datarinci->description = $desc;
            $datarinci->note = $request->note[$i];
            $datarinci->pmtpembelian_id = $data->id;
            $datarinci->user_id = Auth::id();
            $datarinci->save();
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
                    $file->move('uploads/pmtpembelian', $berkas);

                    array_push($collectNamaBerkas, $berkas);
                }
            }

            $saveBerkas = count($collectNamaBerkas);

            // buat berkas permintaan pembelian
            $berkasPmtpembelian = new BerkasPmtpembelian();
            $berkasPmtpembelian->pmtpembelian_id = $data->id;

            for ($i = 0; $i < $saveBerkas; $i++) {
                $flag = $i + 1;
                $var = "berkas_" . $flag;
                $berkasPmtpembelian->$var = $collectNamaBerkas[$i];
            }
            $berkasPmtpembelian->save();
        }

        //redirect ke create lagi setelah create
        if ($request->target == 'lagi') {
            // return back()->with('success', 'Data berhasil di tambahkan');
            return response()->json('LAGI');
        }

        return response()->json($request->target);
    }


    public function show(Pmtpembelian $pmtpembelian)
    {
        if (auth()->user()->cannot('view', Pmtpembelian::class)) abort('403', 'access denied');

        return view('pmtpembelian.show', compact('pmtpembelian'));
    }


    public function edit(Pmtpembelian $pmtpembelian)
    {
        if (auth()->user()->cannot('update', Pmtpembelian::class)) abort('403', 'access denied');

        //jika pengajuan sudah disetujui / tidak disetujui, maka admin tidak dapat melakukan edit
        if ($pmtpembelian->approve_direktur == 1 || $pmtpembelian->approve_direktur == 2) {
            return view('pmtpembelian.tidakbisaedit');
        }

        $berkas = BerkasPmtpembelian::wherePmtpembelianId($pmtpembelian->id)->first();

        // dd($berkas);

        $data = $pmtpembelian->with('rinci')->with('supplier')->with('rinci.barang')->find($pmtpembelian->id);
        // return response()->json($data);
        return view('pmtpembelian.edit', [
            'pmtpembelian' => $data,
            'barang' => Barang::all(),
            'suppliers' => Supplier::where('aktif', 1)->get(),
            'berkas' => $berkas
        ]);
    }


    public function _update(Request $request, $id)
    {

        if (isset($_POST['add-new-product'])) {
            $request->validate([
                'barang_id' => 'required',
                'harga' => 'required',
                'qty' => 'required'
            ]);

            $data = new Pmtpembelian_rinci();
            $data->barang_id = $request->barang_id;
            $data->harga = $request->harga;
            $data->qty = $request->qty;
            $data->note = $request->note;
            $data->pmtpembelian_id = $id;
            $data->user_id = Auth::id();
            $data->save();

            return back();
        } else if (isset($_POST['update-product'])) {
            $request->validate([
                'barang_id' => 'required',
                'harga' => 'required',
                'qty' => 'required'
            ]);

            $data = Pmtpembelian_rinci::find($request->rincian_id);
            $data->barang_id = $request->barang_id;
            $data->harga = $request->harga;
            $data->qty = $request->qty;
            $data->note = $request->note;
            $data->save();

            return back();
        } else if (isset($_POST['update-pmt'])) {

            $data = Pmtpembelian::find($id);
            $upberkas = BerkasPmtpembelian::where('pmtpembelian_id', $id)->first();

            if ($request->hasFile('berkas_1')) {
                $file = $request->file('berkas_1');
                $originalName1 = $file->getClientOriginalName();
                $berkas1 = $originalName1;
                $file->move('uploads/pmtpembelian', $berkas1);
                if ($upberkas->berkas_1 != '') {
                    File::delete('uploads/pmtpembelian/' . $upberkas->berkas_1);
                }
            } else {
                $berkas1 = $upberkas->berkas_1;
            }
            if ($request->hasFile('berkas_2')) {
                $file = $request->file('berkas_2');
                $originalName2 = $file->getClientOriginalName();
                $berkas2 = $originalName2;
                $file->move('uploads/pmtpembelian', $berkas2);
                if ($upberkas->berkas_2 != '') {
                    File::delete('uploads/pmtpembelian/' . $upberkas->berkas_2);
                }
            } else {
                $berkas2 = $upberkas->berkas_2;
            }
            if ($request->hasFile('berkas_3')) {
                $file = $request->file('berkas_3');
                $originalName3 = $file->getClientOriginalName();
                $berkas3 = $originalName3;
                $file->move('uploads/pmtpembelian', $berkas3);
                if ($upberkas->berkas_3 != '') {
                    File::delete('uploads/pmtpembelian/' . $upberkas->berkas_3);
                }
            } else {
                $berkas3 = $upberkas->berkas_3;
            }
            if ($request->hasFile('berkas_4')) {
                $file = $request->file('berkas_4');
                $originalName4 = $file->getClientOriginalName();
                $berkas4 = $originalName4;
                $file->move('uploads/pmtpembelian', $berkas4);
                if ($upberkas->berkas_4 != '') {
                    File::delete('uploads/pmtpembelian/' . $upberkas->berkas_4);
                }
            } else {
                $berkas4 = $upberkas->berkas_4;
            }
            if ($request->hasFile('berkas_5')) {
                $file = $request->file('berkas_5');
                $originalName5 = $file->getClientOriginalName();
                $berkas5 = $originalName5;
                $file->move('uploads/pmtpembelian', $berkas5);
                if ($upberkas->berkas_5 != '') {
                    File::delete('uploads/pmtpembelian/' . $upberkas->berkas_5);
                }
            } else {
                $berkas5 = $upberkas->berkas_5;
            }

            $upberkas->berkas_1 = $berkas1;
            $upberkas->berkas_2 = $berkas2;
            $upberkas->berkas_3 = $berkas3;
            $upberkas->berkas_4 = $berkas4;
            $upberkas->berkas_5 = $berkas5;
            $upberkas->save();

            return redirect('admin/pmtpembelian')->with('success', 'Berhasil update permintaan pembelian');
        }
    }

    public function update(Request $request, $id)
    {
        // if(auth()->user()->cannot('update', Pmtpembelian::class)) abort('403', 'access denied');

        //validasi nomer sama
        $pmtTemp = Pmtpembelian::whereNomerPmtpembelian($request->nomer_pmtpembelian)->first();
        $pmt = Pmtpembelian::whereId($id)->first();

        if (!empty($pmtTemp) && $pmt->nomer != $pmtTemp->nomer) {
            return response()->json(['errors' => "Error! Nomer sudah digunakan, Masukan nomer lainnya.", 'nomer' => true]);
        }

        $data = Pmtpembelian::find($id);

        $data->update([
            'nomer_pmtpembelian' => $request->nomer_pmtpembelian,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan
        ]);

        //update rincian 
        if (is_null($request->barang_id[0])) {
            return response()->json(['errors' => "Data rincian tidak boleh kosong!", 'rincian' => true]);
        }

        //update rincian 
        $rinci = count($request->barang_id);
        for ($i = 0; $i < $rinci; $i++) {
            if (!is_null($request->barang_id[$i])) {
                if (!is_null($request->pmtpembelian_rinci_id[$i])) {
                    Pmtpembelian_rinci::whereId($request->pmtpembelian_rinci_id[$i])
                        ->update([
                            'barang_id' => $request->barang_id[$i],
                            'qty' => $request->qty[$i],
                            'harga' => 0,
                            'note' => $request->note[$i],
                            'user_id' => Auth::id(),
                        ]);
                } else {
                    Pmtpembelian_rinci::create([
                        'barang_id' => $request->barang_id[$i],
                        'qty' => $request->qty[$i],
                        'harga' => 0,
                        'note' => $request->note[$i],
                        'pmtpembelian_id' => $data->id,
                        'user_id' => Auth::id(),
                    ]);
                }
            }
        }


        //sampai sini

        $upberkas = BerkasPmtpembelian::where('pmtpembelian_id', $id)->first();

        // buat data berkas
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
                    $file->move('uploads/pmtpembelian', $berkas);

                    if (!empty($upberkas)) {
                        File::delete('uploads/pmtpembelian/' . $upberkas->$var);
                    }

                    array_push($collectNamaBerkas, $berkas);
                }
            }

            $saveBerkas = count($collectNamaBerkas);

            if (!empty($upberkas)) {
                for ($i = 0; $i < $saveBerkas; $i++) {
                    $flag = $i + 1;
                    $var = "berkas_" . $flag;
                    $upberkas->$var = $collectNamaBerkas[$i];
                }
            } else {
                // buat berkas permintaan pembelian
                $upberkas = new BerkasPmtpembelian();
                $upberkas->pmtpembelian_id = $id;

                for ($i = 0; $i < $saveBerkas; $i++) {
                    $flag = $i + 1;
                    $var = "berkas_" . $flag;
                    $upberkas->$var = $collectNamaBerkas[$i];
                }
            }

            $upberkas->save();
        }

        return redirect('admin/pmtpembelian')->with('success', 'Berhasil update permintaan pembelian');
    }


    public function destroy(Pmtpembelian $pmtpembelian)
    {
        if (auth()->user()->cannot('delete', Pmtpembelian::class)) abort('403', 'access denied');

        $pmtpembelian = Pmtpembelian::find($pmtpembelian->id);

        if ($pmtpembelian->approve_direktur == 1) {
            return back()->with('fail', 'Permintaan telah di setujui oleh direktur dan komisaris, tidak bisa dihapus');
        }

        //hapus pmtpembelian rinci berdasarkan id pmtpembelian
        foreach ($pmtpembelian->rinci as $pmtrinci) {
            $pmtrinci->delete();
        }

        // hapus file dari direktori
        if (!empty($pmtpembelian->berkaspendukung)) {
            if (!empty($pmtpembelian->berkaspendukung->berkas_1)) {
                File::delete('uploads/pmtpembelian/' . $pmtpembelian->berkaspendukung->berkas_1);
            }
            if (!empty($pmtpembelian->berkaspendukung->berkas_2)) {
                File::delete('uploads/pmtpembelian/' . $pmtpembelian->berkaspendukung->berkas_2);
            }
            if (!empty($pmtpembelian->berkaspendukung->berkas_3)) {
                File::delete('uploads/pmtpembelian/' . $pmtpembelian->berkaspendukung->berkas_3);
            }
            if (!empty($pmtpembelian->berkaspendukung->berkas_4)) {
                File::delete('uploads/pmtpembelian/' . $pmtpembelian->berkaspendukung->berkas_4);
            }
            if (!empty($pmtpembelian->berkaspendukung->berkas_5)) {
                File::delete('uploads/pmtpembelian/' . $pmtpembelian->berkaspendukung->berkas_5);
            }

            // delete dari tabel
            $pmtpembelian->berkaspendukung->delete();
        }

        //menghapus tabel pmtpembelian 
        $pmtpembelian->delete();

        return redirect('admin/pmtpembelian')->with('success', 'Permintaan pembelian berhasil dihapus');
    }

    public function hapus_data_rincian($id)
    {
        if (auth()->user()->cannot('delete', Pmtpembelian::class)) abort('403', 'access denied');

        $data = Pmtpembelian_rinci::find($id);
        $data->delete();

        return back();
    }

    public function destroyRinci(Request $request)
    {
        if (auth()->user()->cannot('delete', Pmtpembelian::class)) abort('403', 'access denied');

        Pmtpembelian_rinci::whereId($request->id)->delete();

        return response()->json("OKE");
    }

    public function hapus_rincian($key)
    {
        if (auth()->user()->cannot('delete', Pmtpembelian::class)) abort('403', 'access denied');

        $key = intval($key);
        if (session()->has('pmtpembelian_rinci')) {
            if (gettype($key) == 'integer') {
                $pmtpembelian = session()->get('pmtpembelian_rinci');
                unset($pmtpembelian[$key]);
                session()->put('pmtpembelian_rinci', $pmtpembelian);
            }
        }

        return back();
    }

    //Ambil harga produk dengan ID
    public function get_harga_produk($id)
    {
        if (auth()->user()->cannot('view', Pmtpembelian::class)) abort('403', 'access denied');

        $harga_produk = Barang::find($id);

        return $harga_produk;
    }

    //fungsi untuk mengambil data rinci produk dari tabel temp_pmtpembelians_rinci
    public function get_produk_temp()
    {
        if (auth()->user()->cannot('view', Pmtpembelian::class)) abort('403', 'access denied');

        $carts = Pmtpembelian_rinci_temp::where('pmtpembelian_id', 0)
            ->where('user_id', auth()->id())
            ->get();

        return response()->json([
            'msg' => 'Sukses get data cart temp.',
            'data' => compact('carts')
        ]);
    }

    //fungsi untuk menyimpan rincian produk ke tabel temp_pmtpembelians_rinci
    public function store_produk(Request $request)
    {
        if (auth()->user()->cannot('view', Pmtpembelian::class)) abort('403', 'access denied');

        Pmtpembelian_rinci_temp::create([
            'barang_id' => $request->input('barang_id'),
            'nama_barang' => Barang::find($request->input('barang_id'))->nama_barang,
            'qty' => $request->input('qty'),
            'harga' => $request->input('harga'),
            'note' => $request->input('note'),
            'user_id' => Auth::id()
        ]);
    }

    //untuk menghapus produk temp rinci
    public function del_produk_temp($id)
    {
        if (auth()->user()->cannot('delete', Pmtpembelian::class)) abort('403', 'access denied');

        $temp_produk = Pmtpembelian_rinci_temp::find($id);
        $temp_produk->delete();
    }

    //permintaan pembelian belum diterima
    public function belum_diterima()
    {
        if (auth()->user()->cannot('create', Pmtpembelian::class)) abort('403', 'access denied');

        $data = Popembelian::where(['approve_direktur' => 1, 'approve_komisaris' => 1])->get();

        return response()->json(['data' => $data]);
    }

    public function get_pmtpembelian_rinci_id($id)
    {
        if (auth()->user()->cannot('view', Pmtpembelian::class)) abort('403', 'access denied');

        // $data = Pmtpembelian_rinci::where(['pmtpembelian_id' => $id, 'is_po' => 0])->with('barang')->get();

        $data = Pmtpembelian_rinci::where(['pmtpembelian_id' => $id])->with('barang')->get();
        return response()->json($data);
    }


    // approval permintaan
    public function approval($id)
    {
        if (auth()->user()->cannot('update', Pmtpembelian::class)) abort('403', 'access denied');

        $pmt = Pmtpembelian::find($id);
        $pmt->approve_direktur = request('approve_direktur');
        $pmt->note_direktur = request('note_direktur');
        $pmt->save();

        if ($pmt->approve_direktur == 1) {
            $approvalAction = "DISETUJUI";
        } else if ($pmt->approve_direktur == 2) {
            $approvalAction = "TIDAK DISETUJUI";
        }

        // Kirim notif ke telegram dengan mengirim parameter
        // nomer permintaan, tanggal permintaan, dan user yang membuat
        // $clientTelegram = new \GuzzleHttp\Client();
        // $body = [
        //     'nomer' => $pmt->nomer_pmtpembelian,
        //     'tanggal' => $pmt->tanggal,
        //     //'user' => Pmtpembelian::where('user_id', $pmt->user_id)->get()->user()->name,
        //     'catatan' => $pmt->note_direktur,
        //     'api_token' => 'XFHF5f0u6XNXN3lLfdh2K95uTV9MdHinc4df3AREBrCttXVtWAPF8l5HcIl5',
        //     'url' => url('https://miresmahisa.com/admin/pmtpembelian/') . $pmt->id,
        // ];
        // $response = $clientTelegram->post('http://bot-telegram.miresmahisa.com/api/v1/send/notif/permintaan-pembelian/approveDirektur', 
        //                 ['form_params' => $body]);
        // End of Notifikasi Telegram
        $urlApiTelegram = config('telegram_config.telegram_api_url') . 'api/v1/send_notif_telegram/purchase/send_approval_purchase_request';
        $body = [
            'api_token' => config('telegram_config.telegram_api_token'),
            'nomerPr' => $pmt->nomer_pmtpembelian,
            'tanggal' => $pmt->tanggal,
            'catatan' => $pmt->note_direktur,
            'approvalAction' => $approvalAction,
            'url' => url('https://miresmahisa.com/admin/pmtpembelian/') . $pmt->id,
        ];
        $response = Http::post($urlApiTelegram, $body);

        return redirect('/admin/pmtpembelian')->with('success', 'Pengajuan berhasil dirubah...');
    }
    //print permintaan pembelian apabila status sudah disetujui
    public function print($id)
    {
        if (auth()->user()->cannot('view', Pmtpembelian::class)) abort('403', 'access denied');

        $cek = Pmtpembelian::where('id', $id)->where(['approve_direktur' => 1]);

        if ($cek->first()) {
            $pmt = $cek->first();
            // dd($pmt->purchasing);
            $pembelian = Pmtpembelian_rinci::where('pmtpembelian_id', $id)->get();

            $direktur = User::where('level_id', 2)->first();

            $status = 'live';

            if ($status == "live") {
                $purchasing_signature = 'http://miresmahisa.com/uploads/signature/' . $pmt->purchasing->signature;
                $directur_signature = 'http://miresmahisa.com/uploads/signature/' . $direktur->signature;
            } else if ($status == "dev") {
                $purchasing_signature = url('uploads/signature/' . $pmt->purchasing->signature);
                $directur_signature = url('uploads/signature/' . $direktur->signature);

                return view('pmtpembelian.print', compact('pembelian', 'pmt', 'purchasing_signature', 'directur_signature'));
            }

            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                ->loadView('pmtpembelian.print', compact('pembelian', 'pmt', 'purchasing_signature', 'directur_signature'));
            // return $pdf->download('PR (' . $pmt->nomer_pmtpembelian . ').pdf');
            return $pdf->stream();
        }

        return view('components.print_belum_approve');
    }

    public function printNonTtd($id)
    {
        if (auth()->user()->cannot('view', Pmtpembelian::class)) abort('403', 'access denied');

        $cek = Pmtpembelian::where('id', $id)->where(['approve_direktur' => 1]);

        if ($cek->first()) {
            $pmt = $cek->first();
            // dd($pmt->purchasing);
            $pembelian = Pmtpembelian_rinci::where('pmtpembelian_id', $id)->get();

            $direktur = User::where('level_id', 2)->first();

            $status = 'live';

            if ($status == "live") {
                $purchasing_signature = 'http://miresmahisa.com/uploads/signature/' . $pmt->purchasing->signature;
                $directur_signature = 'http://miresmahisa.com/uploads/signature/' . $direktur->signature;
            } else if ($status == "dev") {
                $purchasing_signature = url('uploads/signature/' . $pmt->purchasing->signature);
                $directur_signature = url('uploads/signature/' . $direktur->signature);

                return view('pmtpembelian.print', compact('pembelian', 'pmt', 'purchasing_signature', 'directur_signature'));
            }

            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                ->loadView('pmtpembelian.print-nonttd', compact('pembelian', 'pmt', 'purchasing_signature', 'directur_signature'));
            // return $pdf->download('PR (' . $pmt->nomer_pmtpembelian . ').pdf');
            return $pdf->stream();
        }

        return view('components.print_belum_approve');
    }
}
