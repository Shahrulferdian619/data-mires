<?php

namespace App\Http\Controllers\Penjualan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Penjualan_SO;
use App\Models\Penjualan_SO_rinci;
use App\Models\Penjualan_DO;
use App\Models\Penjualan_DO_Rinci;
use App\Models\Gudang;
use App\Models\TransaksiBarang;
use App\Models\Barang;
use App\Models\GudangBarang;
use App\Models\BerkasDeliveryorder;
use App\Notifications\SendNotificationSalesTelegram;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use PDF;
use DataTables;
use Carbon\Carbon;
use DB;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;

class DoController extends Controller
{
    ///
    // public function index()
    // {
    //     if(auth()->user()->cannot('viewAny', Penjualan_DO::class)) abort('403', 'access denied');

    //     $do = Penjualan_DO::all();
    //     return view('penjualan.do.index', compact('do'));
    // }

    public function index(Request $request)
    {
        if (auth()->user()->cannot('viewAny', Penjualan_DO::class)) abort('403', 'access denied');

        $so = Penjualan_SO::all();

        if ($request->ajax()) {
            return datatables()->of($so)

                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    return '<td>
                <a class="badge badge-light-secondary" href="' . route('do.show', $row->id) . '"><i data-feather="eye"></i> Lihat</a>            
                </td>';
                })

                ->editColumn('so_tanggal', function ($row) {
                    return $row->so_tanggal ? with(new Carbon($row->so_tanggal))->format('d-m-Y') : '';
                })
                ->editColumn('status_do', function ($row) {
                    if ($row->status_do == 0) {
                        $status_do = '<div style="width:130px" class="badge badge-light-warning">Belum Dikirim</div>';
                    } elseif ($row->status_do == 1) {
                        $status_do = '<div style="width:130px" class="badge badge-light-info">Dikirim Sebagian</div>';
                    } elseif ($row->status_do == 2) {
                        $status_do = '<div style="width:130px" class="badge badge-light-success">Sudah Dikirim</div>';
                    } else {
                        $status_do = "-";
                    }
                    return $status_do;
                })
                ->rawColumns(['actions', 'so_tanggal', 'status_do'])->make(true);
        }

        //return view('penjualan.do.index', compact('so'));
        return view('v2.sales.do.index', compact('so'));
    }

    public function create()
    {
        if (auth()->user()->cannot('create', Penjualan_DO::class)) abort('403', 'access denied');

        $so = Penjualan_SO::where('status_do', '!=', 2)->get();
        $gudang = Gudang::all();
        return view('penjualan.do.create', compact('so', 'gudang'));
    }

    public function store(Request $request)
    {

        $so = Penjualan_SO::find($request->id_so);

        // Insert Tabel DO
        $do = new Penjualan_DO;
        //$do->do_nomer = $so->so_nomer;
        $do->do_nomer = str_replace('SO', 'SJ', $so->so_nomer); //replace SO ke SJ
        $do->id_pelanggan = $so->id_pelanggan;
        $do->do_tanggal = date('Y-m-d');
        $do->alamat_do = $so->alamat_pengiriman;
        $do->pic_do = $so->penerima;
        $do->id_user = Auth::id();
        $do->so_id = $so->id;
        $do->save();

        $so_rinci = Penjualan_SO_Rinci::where('id_so', $request->id_so)->get();

        foreach ($so_rinci as $value) {

            $do_rinci = new Penjualan_DO_Rinci();
            $do_rinci->do_id = $do->id;
            $do_rinci->qty = $value->qty_barang;
            $do_rinci->id_barang = $value->id_barang;
            $do_rinci->so_rinci_id = $value->id;
            $do_rinci->save();

            // Ubah SO_Rinci
            $barang_SO = Penjualan_SO_rinci::find($value->id);
            $barang_SO->is_delivered = 1;
            $barang_SO->save();


            // Update Gudang Transaksi Barang
            $transaksibarang = new TransaksiBarang;
            $transaksibarang->id_barang = $value->id_barang;
            $transaksibarang->id_gudang = $request->gudang;
            $transaksibarang->jenis_transaksi = 'Out';
            $transaksibarang->nomer_transaksi = $so->so_nomer;
            $transaksibarang->sumber_transaksi = 'DO';
            $transaksibarang->qty = '-' . $value->qty_barang;
            $transaksibarang->save();

            // Mengurangi Gudang & Jumlah 
            // Update QTY Barang
            // Insert Balance Stock Item
            $barang = Barang::find($value->id_barang);
            $barang->balance_stok = $barang->balance_stok - $value->qty_barang;
            $barang->save();
            // Update Barang Di Gudang
            $gudang = GudangBarang::where(['barang_id' => $value->id_barang, 'gudang_id' => $request->gudang])->first();
            if (!empty($gudang)) {
                $gudang->gudang_id = $request->gudang;
                $gudang->barang_id = $value->id_barang;
                $gudang->qty = $gudang->qty - $value->qty_barang;
                $gudang->save();
            } else {
                $gudang = new GudangBarang;
                $gudang->gudang_id = $request->gudang;
                $gudang->barang_id = $value->id_barang;
                $gudang->qty = '-' . $value->qty_barang;
                $gudang->save();
            }
        }
        $so->status_do = 2;
        $so->save();

        // Kirim notifikasi ke Grup Mires Mahisa
        // menggunakan api telegram
        $urlApiTelegram = config('telegram_config.telegram_api_url') . 'api/v1/send_notif_telegram/sales/send_new_delivery_order';
        $body = [
            'api_token' => config('telegram_config.telegram_api_token'),
            'nomerDo' => $do->do_nomer,
            'jenisPenjualan' => Penjualan_SO::where('so_nomer', $so->so_nomer)->with('jenisPenjualan')->get()[0]->jenisPenjualan->jenis_penjualan,
            'namaPenerima' => $do->pic_do,
            'tanggal' => $do->do_tanggal,
            'user' => Auth::user()->name
        ];

        $response = Http::post($urlApiTelegram, $body);

        // JANGAN DIHAPUS CODE DI BAWAH INI //
        // Auth::user()->notify(new SendNotificationSalesTelegram([
        //     'text' => '*DELIVERY ORDER BARU*, Sales Order dengan nomer *' . $so->so_nomer . "* sudah dibuatkan Surat Jalan oleh " . Auth::user()->name . " pada Jam : *" . date('Y-m-d H:i:s') . "*"
        // ]));
        // ================================== //

        return redirect('admin/do/' . $so->id)->with('success', 'Berhasil Membuat Kiriman Penjualan!');
    }

    public function show($id)
    {
        if (auth()->user()->cannot('view', Penjualan_DO::class)) abort('403', 'access denied');

        $so = Penjualan_SO::find($id);
        $gudang = Gudang::all();
        $data = [
            'so' => $so->selectRaw('penjualan_so.*, jenis_penjualan.jenis_penjualan as jenis_penjualan')
                ->join('jenis_penjualan', 'penjualan_so.jenis_penjualan', 'jenis_penjualan.id')
                ->with('rinci.barang')->with('berkas')->with('pelanggan')->find($so->id),
            'gudang' => $gudang
        ];

        //return view('penjualan.do.show', $data);
        return view('v2.sales.do.show', $data);
    }

    public function edit($id)
    {
        if (auth()->user()->cannot('update', Penjualan_DO::class)) abort('403', 'access denied');

        if (Penjualan_DO::find($id)->so->status_invoice != 0) {
            return redirect('admin/do/' . $id)->with('fail', 'DO Tidak Dapat Diedit Karena Sudah Dibuatkan Invoice!');
        }
        $do = Penjualan_DO::with('pelanggan')->with('rinci.barang')->with('rinci.sorinciid')->with('so')->find($id);
        $berkas = BerkasDeliveryorder::wherePenjualanDoId($id)->first();
        return view('penjualan.do.edit', compact('do', 'berkas'));
    }

    public function update($id)
    {
        if (auth()->user()->cannot('update', Penjualan_DO::class)) abort('403', 'access denied');

        $request = Request();
        $validate = request()->validate([
            'do_nomer' => 'required',
            'do_tanggal' => 'required',
            'do_alamat' => 'required',
            'do_pic' => 'required',
        ]);


        $do = Penjualan_DO::find($id);
        $do->do_nomer = Request('do_nomer');
        $do->do_tanggal = Request('do_tanggal');
        $do->alamat_do = Request('do_alamat');
        $do->pic_do = Request('do_pic');
        $do->keterangan = Request('keterangan');
        $do->id_pelanggan = $do->id_pelanggan;
        $do->so_id = $do->so_id;
        $do->save();

        // update berkas permintaan pembelian
        $berkas_do = BerkasDeliveryorder::where('penjualan_do_id', $id)->first();

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
                    $file->move('uploads/do_penjualan', $berkas);

                    if (!empty($berkas_do)) {
                        File::delete('uploads/do_penjualan/' . $berkas_do->$var);
                    }

                    array_push($collectNamaBerkas, $berkas);
                }
            }

            $saveBerkas = count($collectNamaBerkas);

            if (!empty($berkas_do)) {
                for ($i = 0; $i < $saveBerkas; $i++) {
                    $flag = $i + 1;
                    $var = "berkas_" . $flag;
                    $berkas_do->$var = $collectNamaBerkas[$i];
                }
            } else {
                $berkas_do = new BerkasDeliveryorder();
                $berkas_do->penjualan_do_id = $do->id;

                for ($i = 0; $i < $saveBerkas; $i++) {
                    $flag = $i + 1;
                    $var = "berkas_" . $flag;
                    $berkas_do->$var = $collectNamaBerkas[$i];
                }
            }

            $berkas_do->save();
        }

        return redirect('admin/do/' . $id)->with('success', 'Berhasil Mengubah Data!');
    }

    public function destroy($id)
    {
        if (auth()->user()->cannot('delete', Penjualan_DO::class)) abort('403', 'access denied');

        $so = Penjualan_DO::find($id)->so;
        if (Penjualan_DO::find($id)->so->status_invoice != 0) {
            return redirect('admin/do/' . $id)->with('fail', 'Tidak Dapat Dihapus Karena Sudah Dibuatkan Invoice!');
        }
        $count_do = Penjualan_DO::where('so_id', $so->id)->count();
        $status_do_so = $so->status_do;

        // Delete Berkas
        $berkas_do = BerkasDeliveryorder::where('penjualan_do_id', $id)->first();
        if ($berkas_do != null) {
            if ($berkas_do->berkas_1 != '') {
                if ($berkas_do->berkas_1 != '') {
                    File::delete('uploads/do_penjualan/' . $berkas_do->berkas_1);
                }
            }
            if ($berkas_do->berkas_2 != '') {
                if ($berkas_do->berkas_2 != '') {
                    File::delete('uploads/do_penjualan/' . $berkas_do->berkas_2);
                }
            }
            if ($berkas_do->berkas_3 != '') {
                if ($berkas_do->berkas_3 != '') {
                    File::delete('uploads/do_penjualan/' . $berkas_do->berkas_3);
                }
            }
            if ($berkas_do->berkas_4 != '') {
                if ($berkas_do->berkas_4 != '') {
                    File::delete('uploads/do_penjualan/' . $berkas_do->berkas_4);
                }
            }
            if ($berkas_do->berkas_5 != '') {
                if ($berkas_do->berkas_5 != '') {
                    File::delete('uploads/do_penjualan/' . $berkas_do->berkas_5);
                }
            }
            $berkas_do->delete();
        }


        // Cek barang sudah dikirim
        if ($status_do_so == 2) {
            // Cek Apakah Faktur lebih dari satu
            if ($count_do > 1) {
                // Jika Iya Maka Ubah Status Menjadi dikirim sebagian
                $so->status_do = 1;
                $so->save();

                // Ubah Rinci Jadi Belum Dikirim
                $do_rinci = Penjualan_DO_rinci::where('do_id', $id)->get();
                foreach ($do_rinci as $do_rinci) {
                    $so_rinci = Penjualan_SO_rinci::find($do_rinci->so_rinci_id);
                    $so_rinci->is_delivered = 0;
                    $so_rinci->save();
                }
            } else {
                // Jika kurang dari samadengan 1 ubah status menjadi belum dikirim
                $so->status_do = 0;
                $so->save();

                // Ubah Rinci Jadi Belum Dikirim
                $do_rinci = Penjualan_DO_rinci::where('do_id', $id)->get();
                foreach ($do_rinci as $do_rinci) {
                    $so_rinci = Penjualan_SO_rinci::find($do_rinci->so_rinci_id);
                    $so_rinci->is_delivered = 0;
                    $so_rinci->save();
                }
            }
        } else {
            // Cek Apakah Faktur lebih dari satu
            if ($count_do <= 1) {
                $so->status_do = 0;
                $so->save();
            }
        }


        // Delete Rinci DO
        Penjualan_DO_rinci::where('do_id', $id)->delete();
        // Delete DO
        $do = Penjualan_DO::find($id);
        $do->delete();

        return redirect('admin/do')->with('success', 'Berhasil Menghapus Delivery Order');
    }
    //// END CRUD ////

    public function print_sj($id)
    {
 
        if (auth()->user()->cannot('view', Penjualan_DO::class)) abort('403', 'access denied');

        $so = Penjualan_SO::find($id);
        $so_rinci = Penjualan_SO_rinci::where('id_so', $id)->get();
        $do = Penjualan_DO::where('so_id', $id)->first();

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
            ->loadView('v2.sales.do.print_sj', compact('do', 'so'));

        return $pdf->stream();
    }

    public function exportPDF()
    {
        $do = Penjualan_DO::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('penjualan.do.exportpdf', compact('do'));
        return $pdf->download('Kiriman Penjualan.pdf');
    }

    public function printPDF()
    {
        $do = Penjualan_DO::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('penjualan.do.exportpdf', compact('do'));
        return $pdf->stream();
    }

    public function get_so($id)
    {
        if (auth()->user()->cannot('view', Penjualan_DO::class)) abort('403', 'access denied');

        $so = Penjualan_SO::where(['id_pelanggan' => $id])->where('status_do', '!=', 2)->get();

        return response()->json(['data' => $so]);
    }

    public function get_so_rinci($id)
    {
        if (auth()->user()->cannot('view', Penjualan_DO::class)) abort('403', 'access denied');


        $so = Penjualan_SO::find($id);
        $pelanggan = Pelanggan::find($so->id_pelanggan);
        $data = Penjualan_SO_rinci::where(['id_so' => $id, 'is_delivered' => 0])->with('barang')->get();

        // Cek Apakah Ada DO Dengan nomer sama sebelumnya
        $do_count = Penjualan_DO::where('do_nomer', 'DO-' . $so->so_nomer)->count();

        // Cek Apakah Ada Kekurangan Di DO Rinci
        $no = 0;
        foreach ($data as $val) {
            if (!empty(Penjualan_DO_Rinci::where('so_rinci_id', $val->id)->get())) {
                $do_rinci = Penjualan_DO_Rinci::where('so_rinci_id', $val->id)->get();
                $jumlah_rinci = 0;
                foreach ($do_rinci as $rinci) {
                    $jumlah_rinci += $rinci->qty;
                }
                $data[$no++]['jumlah_kirim'] = $jumlah_rinci;
            }
        }

        $array = [
            'data' => $data,
            'so' => $so,
            'pelanggan' => $pelanggan,
            'count_do' => $do_count
        ];


        return response()->json($array);
    }

    public function _store()
    {
        if (auth()->user()->cannot('create', Penjualan_DO::class)) abort('403', 'access denied');

        $request = Request();
        $validate = request()->validate([
            'do_nomer' => 'required|unique:penjualan_do,do_nomer',
            'do_tanggal' => 'required',
            'do_alamat' => 'required',
            'do_pic' => 'required',
        ]);

        if (Request('id_pelanggan') == 0 || Request('so_penjualan_id') == 0) {
            return redirect()->back()->with('error', 'Pilih Customer & SO Terlebih Dahulu!');
        }
        // Insert Tabel DO
        $do = new Penjualan_DO();
        $do->do_nomer = Request('do_nomer');
        $do->id_pelanggan = Request('id_pelanggan');
        $do->do_tanggal = Request('do_tanggal');
        $do->alamat_do = Request('do_alamat');
        $do->pic_do = Request('do_pic');
        $do->keterangan = Request('keterangan');
        $do->id_user = Auth::id();
        $do->so_id = Request('so_penjualan_id');
        $do->save();

        // Insert Do Rinci
        foreach (Request('id') as $id) {
            $do_rinci = new Penjualan_DO_Rinci();
            $do_rinci->do_id = $do->id;
            $do_rinci->qty = Request('kirim')[$id];
            $do_rinci->id_barang = Request('barang_id')[$id];
            if (!empty(Request('note')[$id])) {
                $do_rinci->note = Request('note')[$id];
            }
            $do_rinci->so_rinci_id = $id;
            $do_rinci->save();

            // Cek Apakah Jumlah Dikirm Sama Dengan Jumlah Barang Yang Dipesan
            $barang_SO = Penjualan_SO_rinci::find($id);
            if ($barang_SO->qty_barang <= Request('kirim')[$id]) {
                // Ubah DO Rinci Menjadi Sudah Diterima
                $barang_SO->is_delivered = 1;
                $barang_SO->save();
            } else {
                // Cek apakah ada DO Sebelumnya Lalu Hitung Jumlahnya
                $jumlah_do = Penjualan_DO_Rinci::where('so_rinci_id', $id)->get();
                $qty = 0;
                foreach ($jumlah_do as $jumlah) {
                    // Hitung Jumlah
                    $qty += $jumlah->qty;
                }

                if (Penjualan_SO_rinci::find($id)->qty_barang <= $qty) {
                    $barang_SO->is_delivered = 1;
                    $barang_SO->save();
                }
            }

            // Input Transaksi Barang
            if (Request('barang_id')[$id] != '' || Request('barang_id')[$id] != null) {
                $transaksibarang = new TransaksiBarang;
                $transaksibarang->id_barang = Request('barang_id')[$id];
                $transaksibarang->id_gudang = Request('gudang')[$id];
                $transaksibarang->jenis_transaksi = 'Out';
                $transaksibarang->nomer_transaksi = $request->nomer_ri;
                $transaksibarang->sumber_transaksi = 'DO';
                $transaksibarang->qty = '-' . Request('jumlah')[$id];
                $transaksibarang->save();
            }
        }

        // Cek Apakah Semua Pesanan SO Sudah Diterima
        $jumlah_so_rinci_dikirim = count(Penjualan_SO_rinci::where(['is_delivered' => 1, 'id_so' => Request('so_penjualan_id')])->get());
        $jumlah_so_rinci = count(Penjualan_SO_rinci::where('id_so', Request('so_penjualan_id'))->get());

        $so = Penjualan_SO::find(Request('so_penjualan_id'));
        if ($jumlah_so_rinci_dikirim >= $jumlah_so_rinci) {

            $so->status_do = 2;
            // Jika Sudah Maka Ubah Status Menjadi 2
            $so->save();
        } else {
            $so->status_do = 1;
            // Jika Belum Maka Ubah Status Menjadi 1
            $so->save();
        }

        // Insert Berkas
        $berkas1 = '';
        $berkas2 = '';
        $berkas3 = '';
        $berkas4 = '';
        $berkas5 = '';

        if ($request->hasFile('berkas_1')) {
            $file = $request->file('berkas_1');
            $originalName1 = $file->getClientOriginalName();
            $berkas1 = $originalName1;
            $file->move('uploads/do_penjualan', $berkas1);
        }
        if ($request->hasFile('berkas_2')) {
            $file = $request->file('berkas_2');
            $originalName2 = $file->getClientOriginalName();
            $berkas2 = $originalName2;
            $file->move('uploads/do_penjualan', $berkas2);
        }
        if ($request->hasFile('berkas_3')) {
            $file = $request->file('berkas_3');
            $originalName3 = $file->getClientOriginalName();
            $berkas3 = $originalName3;
            $file->move('uploads/do_penjualan', $berkas3);
        }
        if ($request->hasFile('berkas_4')) {
            $file = $request->file('berkas_4');
            $originalName4 = $file->getClientOriginalName();
            $berkas4 = $originalName4;
            $file->move('uploads/do_penjualan', $berkas4);
        }
        if ($request->hasFile('berkas_5')) {
            $file = $request->file('berkas_5');
            $originalName5 = $file->getClientOriginalName();
            $berkas5 = $originalName5;
            $file->move('uploads/do_penjualan', $berkas5);
        }

        // buat berkas permintaan pembelian
        $berkas_do = new BerkasDeliveryorder();
        $berkas_do->penjualan_do_id = $do->id;
        $berkas_do->berkas_1 = $berkas1;
        $berkas_do->berkas_2 = $berkas2;
        $berkas_do->berkas_3 = $berkas3;
        $berkas_do->berkas_4 = $berkas4;
        $berkas_do->berkas_5 = $berkas5;
        $berkas_do->save();

        return redirect('admin/do')->with('success', 'Berhasil Membuat Delivery!');
    }

    public function __store()
    {
        if (auth()->user()->cannot('create', Penjualan_DO::class)) abort('403', 'access denied');

        $request = Request();
        $validate = request()->validate([
            'do_nomer' => 'required',
            'do_tanggal' => 'required',
            'do_alamat' => 'required'
        ]);

        // Insert Tabel DO
        $do = new Penjualan_DO();
        $do->do_nomer = Request('do_nomer');
        $do->id_pelanggan = Request('id_pelanggan');
        $do->do_tanggal = Request('do_tanggal');
        $do->alamat_do = Request('do_alamat');
        $do->pic_do = Request('pic_do');
        $do->keterangan = Request('keterangan');
        $do->id_user = Auth::id();
        $do->so_id = Request('so_penjualan_id');
        $do->save();

        // Insert Do Rinci
        foreach (Request('id') as $id) {
            if (!empty(Request('kirim')[$id]) && Request('kirim')[$id] != 0) {

                $do_rinci = new Penjualan_DO_Rinci();
                $do_rinci->do_id = $do->id;
                $do_rinci->qty = Request('kirim')[$id];
                $do_rinci->id_barang = Request('barang_id')[$id];
                if (!empty(Request('note')[$id])) {
                    $do_rinci->note = Request('note')[$id];
                }
                $do_rinci->so_rinci_id = $id;
                $do_rinci->save();

                // Cek Apakah Jumlah Dikirm Sama Dengan Jumlah Barang Yang Dipesan
                $barang_SO = Penjualan_SO_rinci::find($id);
                if ($barang_SO->qty_barang <= Request('kirim')[$id]) {
                    // Ubah DO Rinci Menjadi Sudah Diterima
                    $barang_SO->is_delivered = 1;
                    $barang_SO->save();
                } else {
                    // Cek apakah ada DO Sebelumnya Lalu Hitung Jumlahnya
                    $jumlah_do = Penjualan_DO_Rinci::where('so_rinci_id', $id)->get();
                    $qty = 0;
                    foreach ($jumlah_do as $jumlah) {
                        // Hitung Jumlah
                        $qty += $jumlah->qty;
                    }

                    if (Penjualan_SO_rinci::find($id)->qty_barang <= $qty) {
                        $barang_SO->is_delivered = 1;
                        $barang_SO->save();
                    }
                }

                // Update Gudang Transaksi Barang

                if (Request('barang_id')[$id] != '' || Request('barang_id')[$id] != null || !empty(Request('barang_id')[$id] != null)) {
                    $transaksibarang = new TransaksiBarang;
                    $transaksibarang->id_barang = Request('barang_id')[$id];
                    $transaksibarang->id_gudang = Request('gudang')[$id];
                    $transaksibarang->jenis_transaksi = 'Out';
                    $transaksibarang->nomer_transaksi = Request('do_nomer');;
                    $transaksibarang->sumber_transaksi = 'DO';
                    $transaksibarang->qty = '-' . Request('kirim')[$id];
                    $transaksibarang->save();

                    // Mengurangi Gudang & Jumlah 
                    // Update QTY Barang
                    // Insert Balance Stock Item
                    $barang = Barang::find(Request('barang_id')[$id]);
                    $barang->balance_stok = $barang->balance_stok - Request('kirim')[$id];
                    $barang->save();
                    // Update Barang Di Gudang
                    $gudang = GudangBarang::where(['barang_id' => Request('barang_id')[$id], 'gudang_id' => Request('gudang')[$id]])->first();
                    if (!empty($gudang)) {
                        $gudang->gudang_id = Request('gudang')[$id];
                        $gudang->barang_id = Request('barang_id')[$id];
                        $gudang->qty = $gudang->qty - Request('kirim')[$id];
                        $gudang->save();
                    } else {
                        $gudang = new GudangBarang;
                        $gudang->gudang_id = Request('gudang')[$id];
                        $gudang->barang_id = Request('barang_id')[$id];
                        $gudang->qty = '-' . Request('kirim')[$id];
                        $gudang->save();
                    }
                }
            }
        }

        // Cek Apakah Semua Pesanan SO Sudah Diterima
        $jumlah_so_rinci_dikirim = count(Penjualan_SO_rinci::where(['is_delivered' => 1, 'id_so' => Request('so_penjualan_id')])->get());
        $jumlah_so_rinci = count(Penjualan_SO_rinci::where('id_so', Request('so_penjualan_id'))->get());

        $so = Penjualan_SO::find(Request('so_penjualan_id'));
        if ($jumlah_so_rinci_dikirim >= $jumlah_so_rinci) {

            $so->status_do = 2;
            // Jika Sudah Maka Ubah Status Menjadi 2
            $so->save();
        } else {
            $so->status_do = 1;
            // Jika Belum Maka Ubah Status Menjadi 1
            $so->save();
        }


        // buat data berkas
        if (!empty($request->berkas)) {
            $totalBerkas = count($request->berkas);
            $collectNamaBerkas = [];

            for ($i = 0; $i < $totalBerkas; $i++) {
                if ($request->hasFile('berkas.' . $i)) {
                    $file = $request->file('berkas')[$i];
                    $originalName = $file->getClientOriginalName();
                    $berkas = str_replace(" ", "", $originalName);
                    $file->move('uploads/do_penjualan', $berkas);

                    array_push($collectNamaBerkas, $berkas);
                }
            }

            $saveBerkas = count($collectNamaBerkas);

            $berkas_do = new BerkasDeliveryorder();
            $berkas_do->penjualan_do_id = $do->id;

            for ($i = 0; $i < $saveBerkas; $i++) {
                $flag = $i + 1;
                $var = "berkas_" . $flag;
                $berkas_do->$var = $collectNamaBerkas[$i];
            }
            $berkas_do->save();
        }


        return redirect('admin/do')->with('success', 'Berhasil Membuat Delivery!');
    }

    public function _update($id)
    {
        $request = Request();
        $validate = request()->validate([
            'do_nomer' => 'required',
            'do_tanggal' => 'required',
            'do_alamat' => 'required',
            'do_pic' => 'required',
        ]);


        $do = Penjualan_DO::find($id);
        $do->do_nomer = Request('do_nomer');
        $do->do_tanggal = Request('do_tanggal');
        $do->alamat_do = Request('do_alamat');
        $do->pic_do = Request('do_pic');
        $do->keterangan = Request('keterangan');
        $do->id_pelanggan = $do->id_pelanggan;
        $do->so_id = $do->so_id;
        $do->save();

        // update berkas permintaan pembelian
        $berkas_do = BerkasDeliveryorder::where('penjualan_do_id', $id)->first();

        if ($request->hasFile('berkas_1')) {
            $file = $request->file('berkas_1');
            $originalName1 = $file->getClientOriginalName();
            $berkas1 = $originalName1;
            $file->move('uploads/do_penjualan', $berkas1);
            if ($berkas_do->berkas_1 != '') {
                File::delete('uploads/do_penjualan/' . $berkas_do->berkas_1);
            }
            $berkas_do->berkas_1 = $berkas1;
        }
        if ($request->hasFile('berkas_2')) {
            $file = $request->file('berkas_2');
            $originalName2 = $file->getClientOriginalName();
            $berkas2 = $originalName2;
            $file->move('uploads/do_penjualan', $berkas2);
            if ($berkas_do->berkas_2 != '') {
                File::delete('uploads/do_penjualan/' . $berkas_do->berkas_2);
            }
            $berkas_do->berkas_2 = $berkas2;
        }
        if ($request->hasFile('berkas_3')) {
            $file = $request->file('berkas_3');
            $originalName3 = $file->getClientOriginalName();
            $berkas3 = $originalName3;
            $file->move('uploads/do_penjualan', $berkas3);
            if ($berkas_do->berkas_3 != '') {
                File::delete('uploads/do_penjualan/' . $berkas_do->berkas_3);
            }
            $berkas_do->berkas_3 = $berkas3;
        }
        if ($request->hasFile('berkas_4')) {
            $file = $request->file('berkas_4');
            $originalName4 = $file->getClientOriginalName();
            $berkas4 = $originalName4;
            $file->move('uploads/do_penjualan', $berkas4);
            if ($berkas_do->berkas_4 != '') {
                File::delete('uploads/do_penjualan/' . $berkas_do->berkas_4);
            }
            $berkas_do->berkas_4 = $berkas4;
        }
        if ($request->hasFile('berkas_5')) {
            $file = $request->file('berkas_5');
            $originalName5 = $file->getClientOriginalName();
            $berkas5 = $originalName5;
            $file->move('uploads/do_penjualan', $berkas5);
            if ($berkas_do->berkas_5 != '') {
                File::delete('uploads/do_penjualan/' . $berkas_do->berkas_5);
            }
            $berkas_do->berkas_5 = $berkas5;
        }

        $berkas_do->save();

        return redirect('admin/do/' . $id)->with('success', 'Berhasil Mengubah Data!');
    }




    public function print($id)
    {
        if (auth()->user()->cannot('view', Penjualan_DO::class)) abort('403', 'access denied');


        $do = Penjualan_DO::where('so_id', $id)->first();

        if ($do) {

            return view('penjualan.do.print', compact('do'));
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('penjualan.do.print', compact('do'));
            // return $pdf->download('Surat Jalan (DO).pdf');
            return $pdf->stream();
            // return view('pmtpembelian.print');
        }
    }
}
